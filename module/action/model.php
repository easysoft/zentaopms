<?php
declare(strict_types=1);
/**
 * The model file of action module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     action
 * @version     $Id: model.php 5028 2013-07-06 02:59:41Z wyd621@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<?php
class actionModel extends model
{
    const BE_UNDELETED  = 0;    // The deleted object has been undeleted.
    const CAN_UNDELETED = 1;    // The deleted object can be undeleted.
    const BE_HIDDEN     = 2;    // The deleted object has been hidden.

    /**
     * 创建一个操作记录。
     * Create a action.
     *
     * @param  string           $objectType
     * @param  int              $objectID
     * @param  string           $actionType
     * @param  string|bool      $comment
     * @param  string|int|float $extra        the extra info of this action, according to different modules and actions, can set different extra.
     * @param  string            $actor
     * @param  bool              $autoDelete
     * @access public
     * @return int|bool
     */
    public function create(string $objectType, int $objectID, string $actionType, string|bool $comment = '', string|int|float $extra = '', string $actor = '', bool $autoDelete = true): int|bool
    {
        if(strtolower($actionType) == 'commented' && empty($comment)) return false;

        $actor      = $actor ? $actor : (!empty($this->app->user->account) ? $this->app->user->account : 'system');
        $actionType = strtolower($actionType);
        $actor      = ($actionType == 'openedbysystem' || $actionType == 'closedbysystem') ? '' : $actor;
        if($actor == 'guest' && $actionType == 'logout') return false;

        $objectType = str_replace('`', '', $objectType);
        $extra      = (string)$extra;

        $action             = new stdclass();
        $action->objectType = strtolower($objectType);
        $action->objectID   = $objectID;
        $action->actor      = $actor;
        $action->action     = $actionType;
        $action->date       = helper::now();
        $action->extra      = (string)$extra;
        if(!$this->app->upgrading) $action->vision = $this->config->vision;
        if($objectType == 'story' && in_array($actionType, array('reviewpassed', 'reviewrejected', 'reviewclarified', 'reviewreverted', 'synctwins'))) $action->actor = $this->lang->action->system;

        /* 使用purifier处理注解。 */
        /* Use purifier to process comment. Fix bug #2683. */
        if(empty($comment)) $comment = '';
        $action->comment = fixer::stripDataTags($comment);

        if($this->post->uid)
        {
            $action = $this->loadModel('file')->processImgURL($action, 'comment', $this->post->uid);
            if($autoDelete) $this->file->autoDelete($this->post->uid);
        }

        /* 获取对象的产品项目以及执行。 */
        /* Get product project and execution for this object. */
        $relation          = $this->getRelatedFields($action->objectType, $objectID, $actionType, $extra);
        $action->product   = $relation['product'];
        $action->project   = (int)$relation['project'];
        $action->execution = (int)$relation['execution'];
        $this->dao->insert(TABLE_ACTION)->data($action)->autoCheck()->exec();
        $actionID = $this->dao->lastInsertID();

        $hasRecentTable = true;
        if($this->app->upgrading)
        {
            $fromVersion = $this->loadModel('setting')->getItem('owner=system&module=common&section=global&key=version');
            if(is_numeric($fromVersion[0]) && version_compare($fromVersion, '18.6', '<'))               $hasRecentTable = false;
            if(strpos($fromVersion, 'pro') !== false) $hasRecentTable = false;
            if(strpos($fromVersion, 'biz') !== false && version_compare($fromVersion, 'biz8.6',   '<')) $hasRecentTable = false;
            if(strpos($fromVersion, 'max') !== false && version_compare($fromVersion, 'max4.6',   '<')) $hasRecentTable = false;
            if(strpos($fromVersion, 'ipd') !== false && version_compare($fromVersion, 'ipd1.0.1', '<')) $hasRecentTable = false;
        }
        if($hasRecentTable) $this->dao->insert(TABLE_ACTIONRECENT)->data($action)->autoCheck()->exec();

        if($this->post->uid) $this->file->updateObjectID($this->post->uid, $objectID, $objectType);

        $this->loadModel('message')->send(strtolower($objectType), $objectID, $actionType, $actionID, $actor, $extra);

        $this->saveIndex($objectType, $objectID, $actionType);

        $changeFunc = 'after' . ucfirst($objectType);
        if(method_exists($this, $changeFunc)) call_user_func_array(array($this, $changeFunc), array($action, $actionID));

        return $actionID;
    }

    /**
     * 访问任务或者bug时，更新action的read字段。
     * Update read field of action when view a task/bug.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @access public
     * @return bool
     */
    public function read(string $objectType, int $objectID): bool
    {
        $this->dao->update(TABLE_ACTION)
            ->set('`read`')->eq(1)
            ->where('objectType')->eq($objectType)
            ->andWhere('objectID')->eq($objectID)
            ->andWhere('`read`')->eq(0)
            ->exec();

        return !dao::isError();
    }

    /**
     * 获取对象的产品项目以及执行。
     * Get product, project, execution of the object.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @param  string $actionType
     * @param  string $extra
     * @access public
     * @return array
     */
    public function getRelatedFields(string $objectType, int $objectID, string $actionType = '', string $extra = ''): array
    {
        /* 处理项目、执行、产品、计划相关。 */
        /* Process project, execution, product, plan related. */
        if(in_array($objectType, array('project', 'execution', 'product', 'program', 'marketresearch')))
        {
            list($product, $project, $execution) = $this->actionTao->getNoFilterRequiredRelation($objectType, $objectID);
            return array('product' => ',' . implode(',', $product) . ',', 'project' => $project, 'execution' => $execution);
        }

        /* 过滤不在配置项中的类型。 */
        /* Filter object types not in configuration items。 */
        $product = array(0);
        $project = $execution = 0;
        if(strpos($this->config->action->needGetRelateField, ",{$objectType},") !== false)
        {
            list($product, $project, $execution) = $this->actionTao->getNeedRelatedFields($objectType, $objectID, $actionType, $extra);
            if($actionType == 'unlinkedfromproject' || $actionType == 'linked2project') $project = (int)$extra ;
            if(in_array($actionType, array('unlinkedfromexecution', 'linked2execution', 'linked2kanban'))) $execution = (int)$extra;

            if($execution && !$project) $project = $this->dao->select('project')->from(TABLE_EXECUTION)->where('id')->eq($execution)->fetch('project');
        }
        return array('product' => is_array($product) ? ',' . implode(',', $product) . ',': ',' . $product . ',', 'project' => $project, 'execution' => $execution);
    }

    /**
     * 根据对象类型和对象ID获取操作记录。
     * Get actions by objectType and objectID.
     *
     * @param  string    $objectType
     * @param  array|int $objectID
     * @access public
     * @return array
     */
    public function getList(string $objectType, array|int $objectID): array
    {
        $modules   = $objectType == 'module' ? $this->dao->select('id')->from(TABLE_MODULE)->where('root')->in($objectID)->fetchPairs('id') : array();
        $commiters = $this->loadModel('user')->getCommiters();
        $actions   = $this->actionTao->getActionListByTypeAndID($objectType, $objectID, $modules);
        $histories = $this->getHistory(array_keys($actions));
        if($objectType == 'project') $actions = $this->processProjectActions($actions);

        foreach($actions as $actionID => $action)
        {
            $actionName = strtolower($action->action);

            if(substr($actionName, 0, 7)  == 'linked2')      $this->actionTao->getLinkedExtra($action, substr($actionName, 7));
            if(substr($actionName, 0, 12) == 'unlinkedfrom') $this->actionTao->getLinkedExtra($action, substr($actionName, 12));

            if(in_array($actionName, array('svncommited', 'gitcommited')) && isset($commiters[$action->actor])) $action->actor = $commiters[$action->actor];
            if(!in_array($action->objectType, array('feedback', 'ticket')) && $actionName == 'tostory') $this->actionTao->processToStoryActionExtra($action);

            if($actionName == 'moved' && $action->objectType != 'module') $this->actionTao->processActionExtra(TABLE_PROJECT, $action, 'name', 'execution', 'task');
            if($actionName == 'frombug' && common::hasPriv('bug', 'view')) $action->extra = html::a(helper::createLink('bug', 'view', "bugID={$action->extra}"), $action->extra);
            if($actionName == 'importedcard') $this->actionTao->processActionExtra(TABLE_KANBAN, $action, 'name', 'kanban', 'view', true);
            if($actionName == 'createchildren') $this->actionTao->processCreateChildrenActionExtra($action);
            if($actionName == 'createrequirements') $this->actionTao->processCreateRequirementsActionExtra($action);
            if($actionName == 'deletechildrendemand') $this->actionTao->processActionExtra(TABLE_DEMAND, $action, 'title', 'demand', 'view');
            if($actionName == 'buildopened') $this->actionTao->processActionExtra(TABLE_BUILD, $action, 'name', 'build', 'view');
            if($actionName == 'fromlib' && $action->objectType == 'case') $this->actionTao->processActionExtra(TABLE_TESTSUITE, $action, 'name', 'caselib', 'browse');
            if($actionName == 'changedbycharter' && $action->objectType == 'story') $this->actionTao->processActionExtra(TABLE_CHARTER, $action, 'name', 'charter', 'view');
            if(($actionName == 'finished' && $objectType == 'todo') || ($actionName == 'closed' && in_array($action->objectType, array('story', 'demand'))) || ($actionName == 'resolved' && $action->objectType == 'bug')) $this->actionTao->processAppendLinkByExtra($action);

            if(in_array($actionName, array('totask', 'linkchildtask', 'unlinkchildrentask', 'linkparenttask', 'unlinkparenttask', 'deletechildrentask', 'converttotask')) && $action->objectType != 'feedback') $this->actionTao->processActionExtra(TABLE_TASK, $action, 'name', 'task', 'view');;
            if(in_array($actionName, array('linkchildstory', 'unlinkchildrenstory', 'linkparentstory', 'unlinkparentstory', 'deletechildrenstory'))) $this->actionTao->processActionExtra(TABLE_STORY, $action, 'title', 'story', 'view');
            if(in_array($actionName, array('testtaskopened', 'testtaskstarted', 'testtaskclosed'))) $this->actionTao->processActionExtra(TABLE_TESTTASK, $action, 'name', 'testtask', 'view');
            if(in_array($actionName, array('importfromstorylib', 'importfromrisklib', 'importfromissuelib', 'importfromopportunitylib')) && in_array($this->config->edition, array('max', 'ipd'))) $this->actionTao->processActionExtra(TABLE_ASSETLIB, $action, 'name', 'assetlib', $action->objectType);
            if(in_array($actionName, array('opened', 'managed', 'edited')) && in_array($objectType, array('execution', 'project'))) $this->processExecutionAndProjectActionExtra($action);
            if(in_array($actionName, array('linkstory', 'unlinkstory', 'createchildrenstory', 'linkur', 'unlinkur'))) $this->actionTao->processLinkStoryAndBugActionExtra($action, 'story', 'view');
            if(in_array($actionName, array('linkbug', 'unlinkbug'))) $this->actionTao->processLinkStoryAndBugActionExtra($action, 'bug', 'view');
            if($actionName == 'repocreated') $action->extra = str_replace("class='iframe'", 'data-app="devops"', $action->extra);

            $action->history = zget($histories, $actionID, array());
            if($actionName == 'svncommited') array_map(function($history) {if($history->field == 'subversion') $history->diff = str_replace('+', '%2B', $history->diff);}, $action->history);
            if($actionName == 'gitcommited') array_map(function($history) {if($history->field == 'git') $history->diff = str_replace('+', '%2B', $history->diff);}, $action->history);

            $action->comment = $this->loadModel('file')->setImgSize($action->comment, $this->config->action->commonImgSize);

            $actions[$actionID] = $action;
        }
        return $actions;
    }

    /**
     * 将项目行动类型转换为通用行动类型。
     * Process Project Actions change actionStype.
     *
     * @param  array  $actions
     * @access public
     * @return array
     */
    public function processProjectActions(array $actions): array
    {
        /* 定义行动映射表。 */
        /* Define the action map table. */
        $map = array();
        $map['testtask']['opened']  = 'testtaskopened';
        $map['testtask']['started'] = 'testtaskstarted';
        $map['testtask']['closed']  = 'testtaskclosed';
        $map['build']['opened']     = 'buildopened';

        /* 处理action数据。 */
        /* Process actions data. */
        foreach($actions as $key => $action)
        {
            if($action->objectType != 'project' && !isset($map[$action->objectType][$action->action])) unset($actions[$key]);
            if(isset($map[$action->objectType][$action->action])) $action->action = $map[$action->objectType][$action->action];
        }

        return $actions;
    }

    /**
     * 获取一条操作记录。
     * Get an action record.
     *
     * @param  int         $actionID
     * @access public
     * @return object|bool
     */
    public function getById(int $actionID): object|bool
    {
        $action = $this->actionTao->fetchBaseInfo($actionID);
        if(!$action) return false;

        /* 当action值为repocreated的时候拼接域名。 */
        /* Splice domain name for connection when the action is equal to 'repocreated'.*/
        if($action->action == 'repocreated') $action->extra = str_replace("href='", "href='" . common::getSysURL(), $action->extra);

        return $action;
    }

    /**
     * 获取已删除的对象。
     * Get deleted objects.
     *
     * @param  string $objectType
     * @param  string $type      all|hidden
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getTrashes(string $objectType, string $type, string $orderBy, object $pager = null): array
    {
        $extra   = $type == 'hidden' ? self::BE_HIDDEN : self::CAN_UNDELETED;
        $trashes = $this->dao->select('*')->from(TABLE_ACTION)
            ->where('action')->eq('deleted')
            ->beginIF($objectType != 'all')->andWhere('objectType')->eq($objectType)->fi()
            ->andWhere('extra')->eq($extra)
            ->andWhere('vision')->eq($this->config->vision)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
        if(empty($trashes)) return array();

        /* 按对象类型对已删除的对象进行分组，并获取名称字段。 */
        /* Group trashes by objectType, and get there name field. */
        foreach($trashes as $object)
        {
            $object->objectType = str_replace('`', '', $object->objectType);
            $typeTrashes[$object->objectType][] = $object->objectID;
        }
        foreach($typeTrashes as $objectType => $objectIdList)
        {
            if(!isset($this->config->objectTables[$objectType])) continue;
            if(!isset($this->config->action->objectNameFields[$objectType])) continue;

            $table        = $this->config->objectTables[$objectType];
            $field        = $this->config->action->objectNameFields[$objectType];
            $objectIdList = array_unique($objectIdList);
            if($objectType == 'pipeline')
            {
                $objectNames['jenkins'] = $this->dao->select("id, {$field} AS name")->from($table)->where('id')->in($objectIdList)->andWhere('type')->eq('jenkins')->fetchPairs();
                $objectNames['gitlab']  = $this->dao->select("id, {$field} AS name")->from($table)->where('id')->in($objectIdList)->andWhere('type')->eq('gitlab')->fetchPairs();
            }
            else
            {
                $objectNames[$objectType] = $this->dao->select("id, {$field} AS name")->from($table)->where('id')->in($objectIdList)->fetchPairs();
            }
        }

        /* 将对象名称字段添加到回收站数据中。 */
        /* Add name field to the trashes. */
        foreach($trashes as $trash)
        {
            if($trash->objectType == 'pipeline' && isset($objectNames['gitlab'][$trash->objectID]))  $trash->objectType = 'gitlab';
            if($trash->objectType == 'pipeline' && isset($objectNames['jenkins'][$trash->objectID])) $trash->objectType = 'jenkins';

            $trash->objectName = isset($objectNames[$trash->objectType][$trash->objectID]) ? $objectNames[$trash->objectType][$trash->objectID] : '';
        }
        return $trashes;
    }

    /**
     * 通过查询获取回收站内的对象。
     * Get deleted objects by search.
     *
     * @param  string     $objectType
     * @param  string     $type       all|hidden
     * @param  string|int $queryID
     * @param  string     $orderBy
     * @param  object     $pager
     * @access public
     * @return array
     */
    public function getTrashesBySearch(string $objectType, string $type, string|int $queryID, string $orderBy, object $pager = null): array
    {
        if($objectType == 'all') return array();
        if($queryID && $queryID != 'myQueryID')
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set('trashQuery', $query->sql);
                $this->session->set('trashForm', $query->form);
            }
            else
            {
                $this->session->set('trashQuery', ' 1 = 1');
            }
        }
        else
        {
            if($this->session->trashQuery === false) $this->session->set('trashQuery', ' 1 = 1');
        }

        $extra      = $type == 'hidden' ? self::BE_HIDDEN : self::CAN_UNDELETED;
        $table      = $this->config->objectTables[$objectType];
        $nameField  = isset($this->config->action->objectNameFields[$objectType]) ? 't2.' . '`' . $this->config->action->objectNameFields[$objectType] . '`' : '';
        $trashQuery = $this->session->trashQuery;
        $trashQuery = str_replace(array('`objectID`', '`actor`', '`date`'), array('t1.`objectID`', 't1.`actor`', 't1.`date`'), $trashQuery);
        if($nameField) $trashQuery = preg_replace("/`objectName`/", $nameField, $trashQuery);
        $queryFields = $objectType != 'pipeline' ? "t1.*, {$nameField} AS objectName" : 't1.*, t1.objectType AS type, t2.name AS objectName, t2.type AS objectType';

        $trashes = $this->dao->select($queryFields)->from(TABLE_ACTION)->alias('t1')
            ->leftJoin($table)->alias('t2')->on('t1.objectID=t2.id')
            ->where('t1.action')->eq('deleted')
            ->andWhere($trashQuery)
            ->andWhere('t1.extra')->eq($extra)
            ->andWhere('t1.vision')->eq($this->config->vision)
            ->beginIF($objectType != 'pipeline' && $objectType != 'all')->andWhere('t1.objectType')->eq($objectType)->fi()

            ->beginIF($objectType == 'pipeline')
            ->andWhere('(t2.type')->eq('gitlab')
            ->orWhere('t2.type')->eq('jenkins')
            ->markRight(1)
            ->fi()

            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('objectID');

        return $trashes;
    }

    /**
     * 获取回收站的对象类型列表。
     * Get object type list of trashes.
     *
     * @param  string $type
     * @access public
     * @return array
     */
    public function getTrashObjectTypes(string $type): array
    {
        $extra = $type == 'hidden' ? self::BE_HIDDEN : self::CAN_UNDELETED;
        return $this->dao->select('objectType')->from(TABLE_ACTION)->where('action')->eq('deleted')->andWhere('extra')->eq($extra)->andWhere('vision')->eq($this->config->vision)->fetchAll('objectType');
    }

    /**
     * 获取一个操作的历史记录。
     * Get histories of an action.
     *
     * @param  array|int  $actionID
     * @access public
     * @return array
     */
    public function getHistory(array|int $actionID): array
    {
        return $this->dao->select()->from(TABLE_HISTORY)->where('action')->in($actionID)->fetchGroup('action');
    }

    /**
     * 记录操作的历史记录。
     * Log histories for an action.
     *
     * @param  int    $actionID
     * @param  array  $changes
     * @access public
     * @return bool
     */
    public function logHistory(int $actionID, array $changes): bool
    {
        if(empty($actionID)) return false;
        foreach($changes as $change)
        {
            if(is_object($change))
            {
                $change->action = $actionID;
            }
            else
            {
                $change['action'] = $actionID;
            }
            $this->dao->insert(TABLE_HISTORY)->data($change)->exec();
            if(dao::isError()) return false;
        }
        return true;
    }

    /**
     * 打印一个对象的所有操作记录。
     * Print actions of an object.
     *
     * @param  object $action
     * @param  string $desc
     * @access public
     * @return void
     */
    public function renderAction(object $action, string $desc = '')
    {
        if(!isset($action->objectType) || !isset($action->action)) return false;

        $objectType = $action->objectType;
        $actionType = strtolower($action->action);

        /**
         *
         * 设置操作的描述。
         *
         * 1. 如果模块中定义了操作的描述，使用模块中定义的。
         * 2. 如果模块中没有定义，使用公共的操作描述。
         * 3. 如果公共的操作描述中没有定义，使用默认的操作描述。
         *
         * Set the desc string of this action.
         *
         * 1. If the module of this action has defined desc of this actionType, use it.
         * 2. If no defined in the module language, search the common action define.
         * 3. If not found in the lang->action->desc, use the $lang->action->desc->common or $lang->action->desc->extra as the default.
         */
        if(empty($desc))
        {
            if(($action->objectType == 'story' or $action->objectType == 'demand') && $action->action == 'reviewed' && strpos($action->extra, ',') !== false)
            {
                $desc = $this->lang->{$objectType}->action->rejectreviewed;
            }
            elseif($action->objectType == 'productplan' && in_array($action->action, array('startedbychild','finishedbychild','closedbychild','activatedbychild', 'createchild')))
            {
                $desc = $this->lang->{$objectType}->action->changebychild;
            }
            elseif($action->objectType == 'module' && in_array($action->action, array('created', 'moved', 'deleted')))
            {
                $desc = $this->lang->{$objectType}->action->{$action->action};
            }
            elseif(strpos('createmr,editmr,removemr', $action->action) !== false && strpos($action->extra, '::') !== false)
            {
                $mrAction = str_replace('mr', '', $action->action) . 'Action';
                list($mrDate, $mrActor, $mrLink) = explode('::', $action->extra);

                if(isInModal()) $mrLink .= ($this->config->requestType == 'GET' ? '&onlybody=yes' : '?onlybody=yes');

                $this->app->loadLang('mr');
                $desc = sprintf($this->lang->mr->{$mrAction}, $mrDate, $mrActor, $mrLink);
            }
            elseif(in_array($this->config->edition, array('max', 'ipd')) && strpos($this->config->action->assetType, ",{$action->objectType},") !== false && $action->action == 'approved')
            {
                $desc = empty($this->lang->action->approve->{$action->extra}) ? '' : $this->lang->action->approve->{$action->extra};
            }
            elseif(isset($this->lang->{$objectType}) && isset($this->lang->{$objectType}->action->{$actionType}))
            {
                $desc = $this->lang->{$objectType}->action->{$actionType};
            }
            elseif($action->objectType == 'instance' && isset($this->lang->action->desc->{$actionType}))
            {
                $desc  = $this->lang->action->desc->{$actionType};
                $extra = json_decode($action->extra);
                if($actionType == 'adjustmemory')
                {
                    $action->newMemory = $action->comment;
                    $action->comment = '';
                }
                if(!empty($extra))
                {
                    $action->oldName    = zget($extra->data, 'oldName', '');
                    $action->newName    = zget($extra->data, 'newName', '');
                    $action->oldVersion = zget($extra->data, 'oldVersion', '');
                    $action->newVersion = zget($extra->data, 'newVersion', '');
                    $action->oldAppName = zget($extra->data, 'oldAppName', '');
                    $action->newAppName = zget($extra->data, 'newAppName', '');
                    $enableAutoBackup   = zget($extra->data, 'autoBackup', 0);

                    if($actionType == 'saveautobackupsettings' && $enableAutoBackup) $desc = $this->lang->action->desc->closeautobackupsettings;
                    $action->extra = '';

                    if(!empty($extra->result->code) && $extra->result->code != 200 && !empty($extra->result->message)) $action->comment = $extra->result->message;
                    if(is_string($extra->result) && $extra->result != 'fail')
                    {
                        $action->comment = "\n" . $extra->message;
                    }
                }
            }
            elseif(isset($this->lang->action->desc->{$actionType}))
            {
                $desc = $this->lang->action->desc->{$actionType};
            }
            else
            {
                $desc = $action->extra ? $this->lang->action->desc->extra : $this->lang->action->desc->common;
            }
        }

        $action->date = substr($action->date, 0, 19);
        if($this->app->getViewType() == 'mhtml') $action->date = date('m-d H:i', strtotime($action->date));

        /* 遍历actions, 替换变量。 */
        /* Cycle actions, replace vars. */
        foreach($action as $key => $value)
        {
            if($key == 'history') continue;

            /* 如果desc是数组，替换变量。 */
            /* Desc can be an array or string. */
            if(is_array($desc))
            {
                if($key == 'extra') continue;
                if($action->objectType == 'story' && $action->action == 'reviewed' && strpos($action->extra, '|') !== false && $key == 'actor')
                {
                    $desc['main'] = str_replace('$actor', $this->lang->action->superReviewer . ' ' . $value, $desc['main']);
                }
                else
                {
                    $desc['main'] = str_replace('$' . $key, (string)$value, $desc['main']);
                }
            }
            else
            {
                if($actionType == 'restoredsnapshot' && in_array($action->objectType, array('vm', 'zanode')) && $value == 'defaultSnap') $value = $this->lang->{$objectType}->snapshot->defaultSnapName;

                $desc = str_replace('$' . $key, (string)$value, $desc);
            }
        }

        /* 如果desc是数组，处理extra。 */
        /* If the desc is an array, process extra. Please bug/lang. */
        if(!is_array($desc)) return $desc;

        $extra = strtolower($action->extra);

        /* Fix bug #741. */
        if(isset($desc['extra'])) $desc['extra'] = $this->lang->{$objectType}->{$desc['extra']};

        $actionDesc = '';
        if(isset($desc['extra'][$extra]))
        {
            $actionDesc = str_replace('$extra', $desc['extra'][$extra], $desc['main']);
        }
        else
        {
            $actionDesc = str_replace('$extra', $action->extra, $desc['main']);
        }

        if(($action->objectType == 'story' or $action->objectType == 'demand') && $action->action == 'reviewed')
        {
            if(strpos($action->extra, ',') !== false)
            {
                list($extra, $reason) = explode(',', $extra);
                $desc['reason'] = $this->lang->{$objectType}->{$desc['reason']};
                $actionDesc = str_replace(array('$extra', '$reason'), array($desc['extra'][$extra], $desc['reason'][$reason]), $desc['main']);
            }

            if(strpos($action->extra, '|') !== false)
            {
                list($extra, $isSuperReviewer) = explode('|', $extra);
                $actionDesc = str_replace('$extra', $desc['extra'][$extra], $desc['main']);
            }
        }

        if($action->objectType == 'story' && $action->action == 'synctwins')
        {
            if(!empty($extra) && strpos($extra, '|') !== false)
            {
                list($operate, $storyID) = explode('|', $extra);
                $desc['operate'] = $this->lang->{$objectType}->{$desc['operate']};
                $link = common::hasPriv('story', 'view') ? html::a(helper::createLink('story', 'view', "storyID=$storyID"), "#$storyID ") : "#$storyID";
                $actionDesc = str_replace(array('$extra', '$operate'), array($link, $desc['operate'][$operate]), $desc['main']);
            }
        }

        if($action->objectType == 'module' && strpos(',created,moved,', $action->action) !== false)
        {
            $moduleNames = $this->loadModel('tree')->getOptionMenu($action->objectID, 'story', 0, 'all', '');
            $modules     = explode(',', $action->extra);
            $moduleNames = array_intersect_key($moduleNames, array_combine($modules, $modules));
            $moduleNames = implode(', ', $moduleNames);
            $actionDesc  = str_replace('$extra', $moduleNames, $desc['main']);
        }
        elseif($action->objectType == 'module' && $action->action == 'deleted')
        {
            $module      = $this->dao->select('*')->from(TABLE_MODULE)->where('id')->eq($action->objectID)->fetch();
            $moduleNames = $this->loadModel('tree')->getOptionMenu($module->root, 'story', 0, 'all', '');
            $actionDesc  = str_replace('$extra', (string)zget($moduleNames, $action->objectID), $desc['main']);
        }
        return $actionDesc;
    }

    /**
     * 格式化操作备注。
     * Format action comment.
     *
     * @param string $comment
     * @access public
     * @return string
     */
    public function formatActionComment(string $comment): string
    {
        if(str_contains($comment, '<pre class="prettyprint lang-html">'))
        {
            $before   = explode('<pre class="prettyprint lang-html">', $comment);
            $after    = explode('</pre>', $before[1]);
            $htmlCode = $after[0];
            return $before[0] . htmlspecialchars($htmlCode) . $after[1];
        }

        return strip_tags($comment) === $comment
            ? nl2br($comment)
            : $comment;
    }

    /**
     * 构建操作记录列表，便于前端组件进行渲染。
     * Build action list for render by frontend component.
     *
     * @param array $actions
     * @param array $users
     * @param bool  $commentEditable
     * @access public
     * @return array
     */
    public function buildActionList(array $actions, array $users = null, $commentEditable = true): array
    {
        if(empty($users)) $users = $this->loadModel('user')->getPairs('noletter');

        $list = array();
        foreach($actions as $action)
        {
            $item = new stdClass();
            if(strlen(trim(($action->comment))) !== 0)
            {
                $item->comment         = $this->formatActionComment($action->comment);
                $item->commentEditable = $commentEditable && end($actions) == $action && $action->actor == $this->app->user->account && common::hasPriv('action', 'editComment');
            }

            if($action->action === 'assigned' || $action->action === 'toaudit')
            {
                $action->extra = zget($users, $action->extra);
                if(str_contains($action->extra, ':')) $action->extra = substr($action->extra, strpos($action->extra, ':') + 1);
            }
            $action->actor = zget($users, $action->actor);
            if(str_contains($action->actor, ':')) $action->actor = substr($action->actor, strpos($action->actor, ':') + 1);

            if(!empty($action->history)) $item->historyChanges = $this->renderChanges($action->objectType, $action->history);

            $item->id      = $action->id;
            $item->action  = $action->action;
            $item->content = $this->renderAction($action);

            $list[] = $item;
        }
        return $list;
    }

    /**
     * 打印一个对象的所有操作记录。
     * Print actions of an object.
     *
     * @param  object    $action
     * @param  string   $desc
     * @access public
     * @return void
     */
    public function printAction(object $action, string $desc = '')
    {
        $content = $this->renderAction($action, $desc);
        if(is_string($content)) echo $content;
        return;
    }

    /**
     * 获取动态。
     * Get actions as dynamic.
     *
     * @param  string     $account
     * @param  string     $period
     * @param  string     $orderBy
     * @param  int        $limit
     * @param  string|int $productID   all|int(like 123)|notzero   all => include zero, notzero, greater than 0
     * @param  string|int $projectID   same as productID
     * @param  string|int $executionID same as productID
     * @param  string     $date
     * @param  string     $direction
     * @access public
     * @return array
     */
    public function getDynamic(string $account = 'all', string $period = 'all', string $orderBy = 'date_desc', int $limit = 50, string|int $productID = 'all', string|int $projectID = 'all', string|int $executionID = 'all', string $date = '', string $direction = 'next'): array
    {
        /* 计算时间段的开始和结束时间。 */
        /* Computer the begin and end date of a period. */
        $beginAndEnd = $this->computeBeginAndEnd($period);
        extract($beginAndEnd);

        /* 构建权限搜索条件。 */
        /* Build has priv search condition. */
        $executions = array();
        $condition = !$this->app->user->admin ? $this->buildUserAclsSearchCondition($productID, $projectID, $executionID, $executions) : '1=1';

        $actionCondition = $this->getActionCondition();
        if(!$actionCondition && !$this->app->user->admin && isset($this->app->user->rights['acls']['actions'])) return array();

        $condition = "`objectType` IN ('doc', 'doclib')" . ($condition = '1=1'? '' : "OR ({$condition})") . " OR `objectType` NOT IN ('program', 'effort', 'execution')";

        $programCondition = empty($this->app->user->view->programs) ? '0' : $this->app->user->view->programs;
        $condition .= " OR (`objectID` in ($programCondition) AND `objectType` = 'program')";

        /* 用户不传入时间的情况下，限定只能查询今年的数据。 */
        /* If the user does not enter the time, only this year's data can be queried. */
        $beginDate = '';
        if($period == 'all')
        {
            $year = date('Y');

            /* 查询所有动态时最多查询最后两年的数据。 */
            /* When query all dynamic then query the data of the last two years at most. */
            if($this->app->getMethodName() == 'dynamic') $year = $year - 1;
            $beginDate = $year . '-01-01';
        }

        /* 查询项目动态时，只查项目创建日期之后的动态。 */
        /* When you query project actions, only the actions after the date the project was created. */
        if(is_numeric($projectID))
        {
            $openedDate = $this->dao->select('openedDate')->from(TABLE_PROJECT)->where('id')->eq($projectID)->fetch('openedDate');
            $beginDate  = $openedDate > $beginDate ? $openedDate : $beginDate;
        }

        $this->actionTao->processEffortCondition($condition, $period, $begin, $end, $beginDate);

        $noMultipleExecutions = $this->dao->select('id')->from(TABLE_PROJECT)->where('multiple')->eq(0)->andWhere('type')->in('sprint,kanban')->fetchPairs();
        if($noMultipleExecutions) $condition = count($noMultipleExecutions) > 1 ? "({$condition}) AND (`objectType` != 'execution' || (`objectID` NOT " . helper::dbIN($noMultipleExecutions) . " AND `objectType` = 'execution'))" : "({$condition}) AND (`objectType` != 'execution' || (`objectID` !" . helper::dbIN($noMultipleExecutions) . " AND `objectType` = 'execution'))";

        $condition = "({$condition})";

        $actions = $this->actionTao->getActionListByCondition($condition, $date, $period, $begin, $end, $direction, $account, $beginDate, $productID, $projectID, $executionID, $executions, $actionCondition, $orderBy, $limit);
        if(!$actions) return array();

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'action');

        return $this->transformActions($actions);
    }

    /**
     * 通过视野获取用户可访问的动态类型。
     * Get the action types that the user can access through the vision.
     *
     * @access public
     * @return string
     */
    public function getActionCondition(): string
    {
        if($this->app->user->admin) return '';

        $actionCondition = '';
        if(!empty($this->app->user->rights['acls']['actions']))
        {
            foreach($this->app->user->rights['acls']['actions'] as $moduleName => $actions)
            {
                if(isset($this->lang->mainNav->{$moduleName}) && !empty($this->app->user->rights['acls']['views']) && !isset($this->app->user->rights['acls']['views'][$moduleName])) continue;
                $actionCondition .= "(`objectType` = '{$moduleName}' AND `action` " . helper::dbIN($actions) . ") OR ";
            }
            $actionCondition = trim($actionCondition, 'OR ');
        }
        return $actionCondition;
    }

    /**
     * 搜索获取动态。
     * Get dynamic by search.
     *
     * @param  int    $queryID
     * @param  string $orderBy
     * @param  int    $limit
     * @param  string $date
     * @param  string $direction
     * @access public
     * @return array
     */
    public function getDynamicBySearch(int $queryID, string $orderBy = 'date_desc', int $limit = 50, string $date = '', string $direction = 'next'): array
    {
        $query = $queryID ? $this->loadModel('search')->getQuery($queryID) : '';

        /* 获取sql和表单内容。 */
        /* Get sql and form content. */
        if($query)
        {
            $this->session->set('actionQuery', $query->sql);
            $this->session->set('actionForm', $query->form);
        }
        if($this->session->actionQuery == false) $this->session->set('actionQuery', ' 1 = 1');

        $allProducts   = "`product` = 'all'";
        $allProjects   = "`project` = 'all'";
        $allExecutions = "`execution` = 'all'";
        $actionQuery   = $this->session->actionQuery;
        $productID     = 0;
        if(preg_match("/`product` = '(\d*)'/", $actionQuery, $out)) $productID = $out[1];

        /* 如果查询条件中包含所有产品的查询条件，不限制产品。 */
        /* If the query condition include all products, no limit product. */
        if(strpos($actionQuery, $allProducts) !== false) $actionQuery = str_replace($allProducts, '1 = 1', $actionQuery);
        /* 如果查询条件中包含所有项目的查询条件，不限制项目。 */
        /* If the query condition include all projects, no limit project. */
        if(strpos($actionQuery, $allProjects) !== false) $actionQuery = str_replace($allProjects, '1 = 1', $actionQuery);
        /* 如果查询条件中包含所有执行的查询条件，不限制执行。 */
        /* If the query condition include all executions, no limit execution. */
        if(strpos($actionQuery, $allExecutions) !== false) $actionQuery = str_replace($allExecutions, '1 = 1', $actionQuery);

        $actionQuery = str_replace("`product` = '{$productID}'", "`product` LIKE '%,{$productID},%'", $actionQuery);
        if($date) $actionQuery = "({$actionQuery}) AND " . ('date' . ($direction == 'next' ? '<' : '>') . "'{$date}'");

        /* 如果当前版本为lite，则过滤掉产品相关的动态。 */
        /* If this vision is lite, delete product actions. */
        if($this->config->vision == 'lite') $actionQuery .= " AND objectType != 'product'";

        $actionQuery .= " AND vision = '{$this->config->vision}'";
        $actions      = $this->getBySQL($actionQuery, $orderBy, $limit);

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'action');
        return $actions ? $this->transformActions($actions) : array();
    }

    /**
     * 通过sql获取actions。
     * Get actions by SQL.
     *
     * @param  string $sql
     * @param  string $orderBy
     * @param  int    $limit
     * @access public
     * @return array
     */
    public function getBySQL(string $sql, string $orderBy, int $limit = 50): array
    {
        $actionCondition = $this->getActionCondition();
        return $this->dao->select('*')->from(TABLE_ACTION)
            ->where($sql)
            ->beginIF(!empty($actionCondition))->andWhere("($actionCondition)")->fi()
            ->orderBy($orderBy)
            ->limit($limit)
            ->fetchAll();
    }

    /**
     * 转换动态用于显示。
     * Transform the actions for display.
     *
     * @param  array  $actions
     * @access public
     * @return array
     */
    public function transformActions(array $actions): array
    {
        /* 获取评论用户以及当前登陆用户的本部门用户。 */
        /* Get the commenters and the users of the current user's department. */
        $commiters = $this->loadModel('user')->getCommiters();
        $deptUsers = isset($this->app->user->dept) ? $this->loadModel('dept')->getDeptUserPairs($this->app->user->dept, 'id') : '';

        /* 通过action获取对象名称，所属项目以及需求。 */
        /* Get object names, object projects and requirements by actions. */
        list($objectNames, $relatedProjects, $requirements) = $this->getRelatedDataByActions($actions);

        $projectIdList = array();
        foreach($relatedProjects as $objectType => $idList) $projectIdList = array_merge($projectIdList, $idList);

        /* If idList include ',*,' Format ',*,' to '*'. */
        foreach($projectIdList as $key => $idList)
        {
            $idList = explode(',', (string)$idList);

            foreach($idList as $id) $projectIdList[] = $id;
            unset($projectIdList[$key]);
        }

        if($projectIdList) $projectIdList = array_unique($projectIdList);

        /* 获取需要验证的元素列表。 */
        /* Get the list of elements that need to be verified. */
        $shadowProducts   = $this->dao->select('id')->from(TABLE_PRODUCT)->where('shadow')->eq(1)->fetchPairs();
        $projectMultiples = $this->dao->select('id,type,multiple')->from(TABLE_PROJECT)->where('id')->in($projectIdList)->fetchAll('id');
        $docList          = $this->loadModel('doc')->getPrivDocs(array(), 0, 'all');
        $apiList          = $this->loadModel('api')->getPrivApis();
        $docLibList       = $this->doc->getLibs('hasApi');
        foreach($actions as $i => $action)
        {
            /* 如果doc,api,doclib,product类型对应的对象不存在，则从actions中删除。*/
            /* If the object corresponding to the doc, api, doclib, and product types does not exist, it will be deleted from actions. */
            if(!$this->actionTao->checkIsActionLegal($action, $shadowProducts, $docList, $apiList, $docLibList))
            {
                unset($actions[$i]);
                continue;
            }

            $actionType = strtolower($action->action);
            $objectType = strtolower($action->objectType);
            $projectID  = isset($relatedProjects[$action->objectType][$action->objectID]) ? $relatedProjects[$action->objectType][$action->objectID] : 0;

            $action->originalDate = $action->date;
            $action->date         = date(DT_MONTHTIME2, strtotime($action->date));
            $action->actionLabel  = isset($this->lang->{$objectType}->{$actionType}) ? $this->lang->{$objectType}->{$actionType} : $action->action;
            $action->actionLabel  = isset($this->lang->action->label->{$actionType}) ? $this->lang->action->label->{$actionType} : $action->actionLabel;
            $action->objectLabel  = $this->getObjectLabel($objectType, $action->objectID, $actionType, $requirements);
            $action->major        = isset($this->config->action->majorList[$action->objectType]) && in_array($action->action, $this->config->action->majorList[$action->objectType]) ? 1 : 0;
            if($actionType == 'svncommited' || $actionType == 'gitcommited') $action->actor = zget($commiters, $action->actor);

            /* 设置对象的名称和链接。 */
            /* Set object name and set object link. */
            $this->actionTao->addObjectNameForAction($action, $objectNames, $objectType);
            $this->setObjectLink($action, $deptUsers, $shadowProducts, zget($projectMultiples, $projectID, ''));
        }
        return $actions;
    }

    /**
     * 通过actions获取关联的数据。
     * Get related data by actions.
     *
     * @param  array  $actions
     * @access public
     * @return array
     */
    public function getRelatedDataByActions(array $actions): array
    {
        /* Init object type array. */
        $objectTypes = array();
        foreach($actions as $object) $objectTypes[$object->objectType][$object->objectID] = $object->objectID;

        if(isset($objectTypes['todo']))   $this->app->loadLang('todo');
        if(isset($objectTypes['branch'])) $this->app->loadLang('branch');
        $users = isset($objectTypes['gapanalysis']) || isset($objectTypes['stakeholder']) ? $this->loadModel('user')->getPairs('noletter') : array();

        $objectNames = $relatedProjects = $requirements = array();
        foreach($objectTypes as $objectType => $objectIdList)
        {
            if(!isset($this->config->objectTables[$objectType]) && $objectType != 'makeup') continue;    // If no defination for this type, omit it.

            /* Get object name field, if it's empty, continue. */
            $table = $objectType == 'makeup' ? '`' . $this->config->db->prefix . 'overtime`' : $this->config->objectTables[$objectType];
            $field = zget($this->config->action->objectNameFields, $objectType, '');
            if(empty($field)) continue;

            /* Get object name, related projects, requirements. */
            list($objectName, $relatedProject, $requirements) = $this->getObjectRelatedData($table, $objectType, $objectIdList, $field, $users, $requirements);
            if($objectType == 'branch' && in_array(BRANCH_MAIN, $objectIdList)) $objectName[BRANCH_MAIN] = $this->lang->branch->main;

            $objectNames[$objectType]     = $objectName;
            $relatedProjects[$objectType] = $relatedProject;
        }

        $objectNames['user'][0] = 'guest';    // Add guest account.

        return array($objectNames, $relatedProjects, $requirements);
    }

    /**
     * 获取对象的标签。
     * Get object label.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @param  string $actionType
     * @param  array  $requirements
     * @access public
     * @return string
     */
    public function getObjectLabel(string $objectType, int $objectID, string $actionType, array $requirements): string
    {
        $actionObjectLabel = $objectType;
        if(isset($this->lang->action->label->{$objectType}))
        {
            $objectLabel = $this->lang->action->label->{$objectType};

            /* 用户故事替换为需求。 */
            /* Replace story to requirement. */
            if(isset($requirements[$objectID]) && is_string($objectLabel)) $objectLabel = str_replace($this->lang->SRCommon, $this->lang->URCommon, $objectLabel);

            if(!is_array($objectLabel)) $actionObjectLabel = $objectLabel;
            if(is_array($objectLabel) && isset($objectLabel[$actionType])) $actionObjectLabel = $objectLabel[$actionType];

            if($objectType == 'module')
            {
                $moduleType = $this->dao->select('type')->from(TABLE_MODULE)->where('id')->eq($objectID)->fetch('type');
                if($moduleType == 'doc')
                {
                    $this->app->loadLang('doc');
                    $actionObjectLabel = $this->lang->doc->menuTitle;
                }
            }
        }

        if(in_array($this->config->edition, array('max', 'ipd')) && $objectType == 'assetlib')
        {
            $libType = $this->dao->select('type')->from(TABLE_ASSETLIB)->where('id')->eq($objectID)->fetch('type');
            if(strpos('story,issue,risk,opportunity,practice,component', $libType) !== false) $actionObjectLabel = $this->lang->action->label->{$libType . 'assetlib'};
        }

        return $actionObjectLabel;
    }

    /**
     * 设置action的objectLink属性。
     * Set the objectLink attribute of action.
     *
     * @param  object     $action
     * @param  array      $deptUsers
     * @param  array      $shadowProducts
     * @param  object|int $project
     * @access public
     * @return object|bool
     */
    public function setObjectLink(object $action, array $deptUsers, array $shadowProducts, object|string $project = ""): object|bool
    {
        $action->objectLink  = $moduleName = $methodName = $params = '';
        $action->objectLabel = zget($this->lang->action->objectTypes, $action->objectLabel);

        if(strpos($action->objectLabel, '|') !== false)
        {
            list($objectLabel, $moduleName, $methodName, $vars) = explode('|', $action->objectLabel);
            $action->objectLabel = $objectLabel;
            $action->product     = trim($action->product, ',');

            if(in_array($action->objectType, array('program', 'project', 'product', 'execution')))
            {
                $objectTable   = zget($this->config->objectTables, $action->objectType);
                $objectDeleted = $this->dao->select('deleted')->from($objectTable)->where('id')->eq($action->objectID)->fetch('deleted');
                if($objectDeleted) return $action;
            }

            if(in_array($this->config->edition, array('max', 'ipd')) && strpos($this->config->action->assetType, ",{$action->objectType},") !== false && empty($action->project) && empty($action->product) && empty($action->execution))
            {
                $this->actionTao->processMaxDocObjectLink($action, $moduleName, $methodName, $vars);
            }
            else
            {
                if($action->objectType !== 'doclib') $params = $this->actionTao->getObjectLinkParams($action, $vars);
                if($action->objectType == 'doclib')
                {
                    list($moduleName, $methodName, $params) = $this->actionTao->getDoclibTypeParams($action);
                }
                elseif($action->objectType == 'story')
                {
                    $story = $this->loadModel('story')->getByID($action->objectID);
                    if(!empty($story) && isset($shadowProducts[$story->product])) $moduleName = 'projectstory';
                }
                $action->objectLink = helper::createLink($moduleName, $methodName, $params);
            }
        }

        if($action->objectType == 'team') list($moduleName, $methodName, $params) = $this->getObjectTypeTeamParams($action);
        if($action->objectType == 'story' && $this->config->vision == 'lite') list($moduleName, $methodName, $params) = array('projectstory', 'view', "storyID={$action->objectID}");
        if($action->objectType == 'review') list($moduleName, $methodName, $params) = array('review', 'view', "reviewID={$action->objectID}");

        /* Set app for no multiple project. */
        if(!empty($action->objectLink) && !empty($project) && empty($project->multiple)) $action->objectLink .= '#app=project';
        if($this->config->vision == 'lite' && $action->objectType == 'module') $action->objectLink .= '#app=project';

        $action->objectLink = !$this->actionTao->checkActionClickable($action, $deptUsers, $moduleName, $methodName) ? '' : helper::createLink($moduleName, $methodName, $params);
        return $action;
    }

    /**
     * 根据给定的一段时间的参数计算日期的开始和结束。
     * Compute the begin date and end date of a period.
     *
     * @param  string $period   all|today|yesterday|twodaysago|latest2days|thisweek|lastweek|thismonth|lastmonth
     * @access public
     * @return array
     */
    public function computeBeginAndEnd(string $period): array
    {
        $period = strtolower($period);
        if($period == 'all') return array('begin' => EPOCH_DATE,  'end' => FUTURE_DATE);

        $this->app->loadClass('date');

        $today      = date('Y-m-d');
        $tomorrow   = date::tomorrow();
        $yesterday  = date::yesterday();
        $twoDaysAgo = date::twoDaysAgo();

        if($period == 'today')       return array('begin' => $today,      'end' => $tomorrow);
        if($period == 'yesterday')   return array('begin' => $yesterday,  'end' => $today);
        if($period == 'twodaysago')  return array('begin' => $twoDaysAgo, 'end' => $yesterday);
        if($period == 'latest3days') return array('begin' => $twoDaysAgo, 'end' => $tomorrow);

        /* 如果时间段为周，则给结束日期增加结束时间。 */
        /* If the period is by week, add the end time to the end date. */
        if($period == 'thisweek' || $period == 'lastweek')
        {
            $func = "get$period";
            extract(date::$func());
            return array('begin' => $begin, 'end' => $end);
        }

        if($period == 'thismonth')  return date::getThisMonth();
        if($period == 'lastmonth')  return date::getLastMonth();

        return array('begin' => EPOCH_DATE,  'end' => FUTURE_DATE);
    }

    /**
     * 渲染每一个action的历史记录。
     * Render histories of every action.
     *
     * @param  string $objectType
     * @param  array  $histories
     * @param  bool   $canChangeTag
     * @access public
     * @return string
     */
    public function renderChanges(string $objectType, array $histories, bool $canChangeTag = true): string
    {
        if(empty($histories)) return '';

        $maxLength            = 0;          // The max length of fields names.
        $historiesWithDiff    = array();    // To save histories without diff info.
        $historiesWithoutDiff = array();    // To save histories with diff info.

        /* 区别是否有diff信息，以便于将有diff信息的字段放在最后。 */
        /* Diff histories by hasing diff info or not. Thus we can to make sure the field with diff show at last. */
        foreach($histories as $history)
        {
            $fieldName = $history->field;
            $history->fieldLabel = isset($this->lang->{$objectType}) && isset($this->lang->{$objectType}->{$fieldName}) ? $this->lang->{$objectType}->{$fieldName} : $fieldName;
            if($objectType == 'module')   $history->fieldLabel = $this->lang->tree->{$fieldName};
            if($fieldName  == 'fileName') $history->fieldLabel = $this->lang->file->{$fieldName};
            if(($length = strlen($history->fieldLabel)) > $maxLength) $maxLength = $length;
            $history->diff ? $historiesWithDiff[] = $history : $historiesWithoutDiff[] = $history;
        }
        $histories = array_merge($historiesWithoutDiff, $historiesWithDiff);

        /* 处理历史记录中的差别。 */
        /* Process the diff of histories. */
        $content = '';
        foreach($histories as $history)
        {
            $history->fieldLabel = str_pad($history->fieldLabel, $maxLength, $this->lang->action->label->space);
            if($history->diff != '')
            {
                $history->diff      = str_replace(array('<ins>', '</ins>', '<del>', '</del>'), array('[ins]', '[/ins]', '[del]', '[/del]'), $history->diff);
                $history->diff      = $history->field != 'subversion' && $history->field != 'git' ? htmlSpecialString($history->diff) : $history->diff;   // Keep the diff link.
                $history->diff      = str_replace(array('[ins]', '[/ins]', '[del]', '[/del]'), array('<ins>', '</ins>', '<del>', '</del>'), $history->diff);
                $history->diff      = nl2br($history->diff);
                $history->noTagDiff = $canChangeTag ? preg_replace('/&lt;\/?([a-z][a-z0-9]*)[^\/]*\/?&gt;/Ui', '', $history->diff) : '';
                $content .= sprintf($this->lang->action->desc->diff2, $history->fieldLabel, $history->noTagDiff, $history->diff);
            }
            else
            {
                $content .= sprintf($this->lang->action->desc->diff1, $history->fieldLabel, $history->old, $history->new);
            }
        }
        return $content;
    }

    /**
     * 打印每一个action的历史记录。
     * Print histories of every action.
     *
     * @param  string $objectType
     * @param  array  $histories
     * @param  bool   $canChangeTag
     * @access public
     * @return void
     */
    public function printChanges(string $objectType, array $histories, bool $canChangeTag = true): void
    {
        $content = $this->renderChanges($objectType, $histories, $canChangeTag);
        if(is_string($content)) echo $content;
    }

    /**
     * 通过对象类型删除action。
     * Delete action by objectType.
     *
     * @param  string $objectType
     * @access public
     * @return bool
     */
    public function deleteByType(string $objectType): bool
    {
        $this->dao->delete()->from(TABLE_ACTION)->where('objectType')->eq($objectType)->exec();

        return !dao::isError();
    }

    /**
     * 恢复一条记录。
     * Undelete a record.
     *
     * @param  int    $actionID
     * @access public
     * @return string|bool
     */
    public function undelete(int $actionID): string|bool
    {
        if($actionID <= 0) return false;

        $action = $this->getById($actionID);
        if(!$action || $action->action != 'deleted') return false;

        list($table, $orderby, $field) = $this->actionTao->getUndeleteParamsByObjectType($action->objectType);
        $object = $this->actionTao->getObjectBaseInfo($table, array('id' => $action->objectID), $field, $orderby);
        if(empty($object)) return false;

        $result = $this->checkActionCanUndelete($action, $object);
        if($result !== true) return $result;

        /* 恢复被删除的元素。 */
        /* Resotre deleted object. */
        if($action->objectType == 'doc') $table = TABLE_DOC;
        $this->dao->update($table)->set('deleted')->eq(0)->where('id')->eq($action->objectID)->exec();

        $this->recoverRelatedData($action, $object);

        /* 在action表中更新action记录。 */
        /* Update action record in action table. */
        $this->dao->update(TABLE_ACTION)->set('extra')->eq(actionModel::BE_UNDELETED)->where('id')->eq($actionID)->exec();
        $this->create($action->objectType, $action->objectID, 'undeleted');

        return true;
    }

    /**
     * 隐藏一个对象。
     * Hide an object.
     *
     * @param  int    $actionID
     * @access public
     * @return bool
     */
    public function hideOne(int $actionID): bool
    {
        $action = $this->getById($actionID);
        if(!$action || $action->action != 'deleted') return false;

        $this->dao->update(TABLE_ACTION)->set('extra')->eq(self::BE_HIDDEN)->where('id')->eq($actionID)->exec();
        $this->create($action->objectType, $action->objectID, 'hidden');

        return !dao::isError();
    }

    /**
     * 隐藏所有被删除的对象。
     * Hide all deleted objects.
     *
     * @access public
     * @return bool
     */
    public function hideAll(): bool
    {
        $this->dao->update(TABLE_ACTION)
            ->set('extra')->eq(self::BE_HIDDEN)
            ->where('action')->eq('deleted')
            ->andWhere('extra')->eq(self::CAN_UNDELETED)
            ->exec();

        return !dao::isError();
    }

    /**
     * 更新一个action的评论。
     * Update comment of a action.
     *
     * @param  int    $actionID
     * @param  string $comment
     * @param  string $uid
     * @access public
     * @return bool
     */
    public function updateComment(int $actionID, string $comment, string $uid): bool
    {
        $action = $this->getById($actionID);
        if(!$action) return false;

        /* 只保留允许的标签。 */
        /* Keep only allowed tags. */
        $action->comment = trim(strip_tags($comment, $this->config->allowedTags));

        /* 处理评论内的图片。*/
        /* Handle images in comment. */
        $action = $this->loadModel('file')->processImgURL($action, 'comment', $uid);

        $this->dao->update(TABLE_ACTION)
            ->set('date')->eq(helper::now())
            ->set('comment')->eq($comment)
            ->where('id')->eq($actionID)
            ->exec();
        $this->file->updateObjectID($uid, $action->objectID, $action->objectType);

        return true;
    }

    /**
     * 根据actions构建日期组。
     * Build date group by actions
     *
     * @param  array  $actions
     * @param  string $direction
     * @param  string $orderBy    date_desc|date_asc
     * @access public
     * @return array
     */
    public function buildDateGroup(array $actions, string $direction = 'next', string $orderBy = 'date_desc'): array
    {
        $dateGroup = array();
        foreach($actions as $action)
        {
            $timeStamp    = strtotime(isset($action->originalDate) ? $action->originalDate : $action->date);
            $date         = date(DT_DATE3, $timeStamp);
            $action->time = date(DT_TIME2, $timeStamp);
            $dateGroup[$date][] = $action;
        }

        /* 查询数据并且写入日期分组中。 */
        /* Query data and write into data packets. */
        if($dateGroup)
        {
            $lastDateActions = $this->dao->select('*')->from(TABLE_ACTION)->where($this->session->actionQueryCondition)->andWhere("(LEFT(`date`, 10) = '" . substr($action->originalDate, 0, 10) . "')")->orderBy($this->session->actionOrderBy)->fetchAll('id');
            if(count($dateGroup[$date]) < count($lastDateActions))
            {
                unset($dateGroup[$date]);
                $lastDateActions = $this->transformActions($lastDateActions);
                foreach($lastDateActions as $action)
                {
                    $timeStamp    = strtotime(isset($action->originalDate) ? $action->originalDate : $action->date);
                    $date         = date(DT_DATE3, $timeStamp);
                    $action->time = date(DT_TIME2, $timeStamp);
                    $dateGroup[$date][] = $action;
                }
            }
        }

        /* 将日期的顺序修改正确。 */
        /* Modify date to the corrret order. */
        if($this->app->rawModule != 'company' && $direction != 'next')
        {
            $dateGroup = array_reverse($dateGroup);
        }
        elseif($this->app->rawModule == 'company')
        {
            if($direction == 'pre') $dateGroup = array_reverse($dateGroup);
            if(($direction == 'next' && $orderBy == 'date_asc') || ($direction == 'pre' && $orderBy == 'date_desc'))
            {
                foreach($dateGroup as $key => $dateItem) $dateGroup[$key] = array_reverse($dateItem);
            }
        }
        return $dateGroup;
    }

    /**
     * 检查是否有上一条或者下一条。
     * Check Has pre or next.
     *
     * @param  string $date
     * @param  string $direction
     * @access public
     * @return bool
     */
    public function hasPreOrNext(string $date, string $direction = 'next'): bool
    {
        if(empty($date)) return false;
        $condition = $this->session->actionQueryCondition;

        /* 移除搜索中的时间筛选条件。 */
        /* Remove time filter from search. */
        $condition = preg_replace("/AND +date[\<\>]'\d{4}\-\d{2}\-\d{2}'/", '', $condition);
        $count     = $this->dao->select('count(*) as count')
            ->from(TABLE_ACTION)
            ->where($condition)
            ->andWhere('date' . ($direction == 'next' ? '<' : '>') . "'{$date}'")
            ->fetch('count');

        return $count > 0;
    }

    /**
     * 保存全局搜索对象索引信息。
     * Save global search object index information.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @param  string $actionType
     * @access public
     * @return bool
     */
    public function saveIndex(string $objectType, int $objectID, string $actionType): bool
    {
        $this->loadModel('search');
        $actionType = strtolower($actionType);
        if(!isset($this->config->search->fields->{$objectType})) return false;
        if(strpos($this->config->search->buildAction, ",{$actionType},") === false && empty($_POST['comment'])) return false;
        if($actionType == 'deleted' || $actionType == 'erased') return $this->search->deleteIndex($objectType, $objectID);

        $field = $this->config->search->fields->{$objectType};
        $query = $this->search->buildIndexQuery($objectType, false);
        $data  = $query->andWhere('t1.' . $field->id)->eq($objectID)->fetch();
        if(empty($data)) return false;

        $data->comment = '';
        if($objectType == 'effort' && $data->objectType == 'task') return false;
        if($objectType == 'case')
        {
            $caseStep     = $this->dao->select('*')->from(TABLE_CASESTEP)->where('`case`')->eq($objectID)->andWhere('version')->eq($data->version)->fetchAll();
            $data->desc   = '';
            $data->expect = '';
            foreach($caseStep as $step)
            {
                $data->desc   .= $step->desc . "\n";
                $data->expect .= $step->expect . "\n";
            }
        }

        $actions = $this->dao->select('*')->from(TABLE_ACTION)
            ->where('objectType')->eq($objectType)
            ->andWhere('objectID')->eq($objectID)
            ->orderBy('id asc')
            ->fetchAll();
        foreach($actions as $action)
        {
            if($action->action == 'opened') $data->{$field->addedDate} = $action->date;
            $data->{$field->editedDate} = $action->date;
            if(!empty($action->comment)) $data->comment .= $action->comment . "\n";
        }

        $this->search->saveIndex($objectType, $data);

        return !dao::isError();
    }

    /**
     * 打印API（极狐）对象上的操作。
     * Print actions of an object for API(JIHU).
     *
     * @param  object    $action
     * @access public
     * @return false|void
     */
    public function printActionForGitLab(object $action)
    {
        if(!isset($action->objectType) || !isset($action->action)) return false;

        $actionType = strtolower($action->action);
        if(isset($this->lang->action->apiTitle->{$actionType}) && isset($action->extra))
        {
            /* 如果extra列是一个用户名，则组装链接。 */
            /* If extra column is a username, then assemble link to that. */
            if($action->action == "assigned")
            {
                $user = $this->loadModel('user')->getById($action->extra);
                if($user)
                {
                    $url = helper::createLink('user', 'profile', "userID={$user->id}");
                    $action->extra = "<a href='{$url}' target='_blank'>{$action->extra}</a>";
                }
            }

            echo sprintf($this->lang->action->apiTitle->{$actionType}, $action->extra);
        }
        elseif(isset($this->lang->action->apiTitle->{$actionType}) && !isset($action->extra))
        {
            echo $this->lang->action->apiTitle->{$actionType};
        }
        else
        {
            echo $actionType;
        }
    }

    /**
     * 处理操作记录用于API。
     * Process action for API.
     *
     * @param  array  $actions
     * @param  array  $users
     * @param  array  $objectLang
     * @access public
     * @return array
     */
    public function processActionForAPI(array $actions, array $users = array(), array $objectLang = array()): array
    {
        foreach($actions as $action)
        {
            $action->actor = zget($users, $action->actor);
            if($action->action == 'assigned') $action->extra = zget($users, $action->extra);
            if(strpos($action->actor, ':') !== false) $action->actor = substr($action->actor, strpos($action->actor, ':') + 1);

            ob_start();
            $this->printAction($action);
            $action->desc = ob_get_contents();
            ob_end_clean();

            if($action->history)
            {
                foreach($action->history as $i => $history)
                {
                    $history->fieldName  = zget($objectLang, $history->field);
                    $action->history[$i] = $history;
                }
            }
        }
        return array_values($actions);
    }

    /**
     * 处理动态用于API。
     * Process dynamic for API.
     *
     * @param  array  $dynamics
     * @access public
     * @return array
     */
    public function processDynamicForAPI(array $dynamics): array
    {
        /* 获取用户列表。 */
        /* Get user list. */
        $users = $this->loadModel('user')->getList();
        $simplifyUsers = array();
        foreach($users as $user)
        {
            $simplifyUser = new stdclass();
            $simplifyUser->id       = $user->id;
            $simplifyUser->account  = $user->account;
            $simplifyUser->realname = $user->realname;
            $simplifyUser->avatar   = $user->avatar;
            $simplifyUsers[$user->account] = $simplifyUser;
        }

        $actions = array();
        foreach($dynamics as $dynamic)
        {
            if($dynamic->objectType == 'user') continue; //过滤掉用户动态。

            $simplifyUser = zget($simplifyUsers, $dynamic->actor, '');
            $actor = $simplifyUser;
            if(empty($simplifyUser))
            {
                $actor = new stdclass();
                $actor->id       = 0;
                $actor->account  = $dynamic->actor;
                $actor->realname = $dynamic->actor;
                $actor->avatar   = '';
            }

            $dynamic->actor = $actor;
            $actions[]      = $dynamic;
        }

        return $actions;
    }

    /**
     * 构建搜索表单数据。
     * Build search form data.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return void
     */
    public function buildTrashSearchForm(int $queryID, string $actionURL): void
    {
        $this->config->trash->search['actionURL'] = $actionURL;
        $this->config->trash->search['queryID']   = $queryID;

        $this->loadModel('search')->setSearchParams($this->config->trash->search);
    }

    /**
     * 恢复阶段。
     * Restore stages.
     *
     * @param  array  $stageList
     * @access public
     * @return bool
     */
    public function restoreStages(array $stageList): bool
    {
        $deletedActions = $this->dao->select('*')->from(TABLE_ACTION)
            ->where('objectID')->in(array_keys($stageList))
            ->andWhere('objectType')->eq('execution')
            ->andWhere('action')->eq('deleted')
            ->orderBy('id_desc')
            ->fetchGroup('objectID');

        foreach($stageList as $stageID)
        {
            $deletedAction = $deletedActions[$stageID][0];
            $this->dao->update(TABLE_EXECUTION)->set('deleted')->eq('0')->where('id')->eq($stageID)->exec();
            $this->dao->update(TABLE_ACTION)->set('extra')->eq(actionModel::BE_UNDELETED)->where('id')->eq($deletedAction->id)->exec();
            $this->create($deletedAction->objectType, $deletedAction->objectID, 'undeleted');
            if(dao::isError()) return false;
        }
        return true;
    }

    /**
     * 获取属性相同的对象。
     * Get repeat object.
     *
     * @param  object $action
     * @param  string $table
     * @access public
     * @return array
     */
    public function getRepeatObject(object $action, string $table): array
    {
        $object = $this->dao->select('*')->from($table)->where('id')->eq($action->objectID)->fetch();
        if($action->objectType == 'product')
        {
            $programID    = isset($object->program) ? $object->program : 0;
            $repeatObject = $this->dao->select('*')->from(TABLE_PRODUCT)
                ->where('id')->ne($action->objectID)
                ->andWhere("(name = '{$object->name}' AND program = {$programID})", true)
                ->beginIF($object->code)->orWhere("code = '{$object->code}'")->fi()
                ->markRight(1)
                ->andWhere('deleted')->eq('0')
                ->fetch();
        }
        else
        {
            $sprintProject = isset($object->project) ? $object->project : 0;
            $repeatObject  = $this->dao->select('*')->from(TABLE_PROJECT)
                ->where('id')->ne($action->objectID)
                ->beginIF($action->objectType == 'program' || $action->objectType == 'project')->andWhere("(name = '{$object->name}' AND parent = {$object->parent})", true)->fi()
                ->beginIF($action->objectType == 'execution')->andWhere("(name = '{$object->name}' AND project = {$sprintProject})", true)->fi()
                ->beginIF($action->objectType == 'project' && $object->code)->orWhere("(code = '{$object->code}' and model = '{$object->model}')")->fi()
                ->beginIF($action->objectType == 'execution' && $object->code)->orWhere("code = '{$object->code}'")->fi()
                ->markRight(1)
                ->beginIF($action->objectType == 'program')->andWhere('type')->eq('program')->fi()
                ->beginIF($action->objectType == 'project')->andWhere('type')->eq('project')->fi()
                ->beginIF($action->objectType == 'execution')->andWhere('type')->in('sprint,stage,kanban')->fi()
                ->andWhere('deleted')->eq('0')
                ->fetch();
        }

        return array($repeatObject, $object);
    }

    /**
     * 获取和需求属性相近的对象。
     * Get like object.
     *
     * @param  string $table
     * @param  string $columns
     * @param  string $param
     * @param  string $value
     * @access public
     * @return array
     */
    public function getLikeObject(string $table, string $columns, string $param, string $value): array
    {
        return $this->dao->select($columns)->from($table)->where($param)->like($value)->fetchPairs();
    }

    /**
     * 通过id更新对象。
     * Update object by id.
     *
     * @param  string $table
     * @param  int    $id
     * @param  array  $params
     * @access public
     * @return bool
     */
    public function updateObjectByID(string $table, int $id, array $params): bool
    {
        $updateParams = array();
        foreach($params as $key => $value) $updateParams[] = '`' . $key . '`' . '="' . $value . '"';
        $this->dao->update($table)->set(implode(',', $updateParams))->where('id')->eq($id)->exec();

        return !dao::isError();
    }

    /**
     * 根据执行id获取attribute属性。
     * Get attribute by execution id.
     *
     * @param  int    $executionID
     * @access public
     * @return string|bool
     */
    public function getAttributeByExecutionID(int $executionID): string|bool
    {
        return $this->dao->select('attribute')->from(TABLE_EXECUTION)->where('id')->eq($executionID)->fetch('attribute');
    }

    /**
     * 根据id获取已经删除的阶段。
     * Get deleted stage by ids.
     *
     * @param  array $list
     * @access public
     * @return array|bool
     */
    public function getDeletedStagedByList(array $list): array|bool
    {
        return $this->dao->select('*')->from(TABLE_EXECUTION)->where('id')->in($list)->andWhere('deleted')->eq(1)->andWhere('type')->eq('stage')->orderBy('id_asc')->fetchAll('id');
    }

    /**
     * 更新阶段的attribute属性。
     * Update stage attribute.
     *
     * @param  string $attribute
     * @param  array  $executions
     * @access public
     * @return bool
     */
    public function updateStageAttribute(string $attribute, array $executions): bool
    {
        $this->dao->update(TABLE_EXECUTION)->set('attribute')->eq($attribute)->where('id')->in($executions)->exec();

        return !dao::isError();
    }

    /**
     * 获取动态对象关联的数据。
     * Get action object related data.
     *
     * @param  string $table
     * @param  string $objectType
     * @param  array  $objectIdList
     * @param  string $field
     * @param  array  $users
     * @param  array  $requirements
     * @access public
     * @return array
     */
    public function getObjectRelatedData(string $table, string $objectType, array $objectIdList, string $field, array $users, array $requirements): array
    {
        $objectName     = array();
        $relatedProject = array();
        if($table == TABLE_TODO)
        {
            $todos = $this->dao->select("id, {$field} AS name, account, private, type, objectID")->from($table)->where('id')->in($objectIdList)->fetchAll();
            foreach($todos as $todo)
            {
                /* Get related object name. */
                if(in_array($todo->type, array('task', 'bug', 'story', 'testtask'))) $todo->name = $this->dao->findById($todo->objectID)->from($this->config->objectTables[$todo->type])->fetch($this->config->action->objectNameFields[$todo->type]);
                $objectName[$todo->id] = $todo->private == 1 && $todo->account != $this->app->user->account ? $this->lang->todo->thisIsPrivate : $todo->name;
            }
        }
        elseif(strpos(",{$this->config->action->needGetProjectType},", ",{$objectType},") !== false || $objectType == 'project' || $objectType == 'execution')
        {
            $objectInfo = $this->dao->select("id, project, {$field} AS name")->from($table)->where('id')->in($objectIdList)->fetchAll();
            foreach($objectInfo as $object)
            {
                $objectName[$object->id]     = $objectType == 'gapanalysis' ? zget($users, $object->name) : $object->name; // Get user realname if objectType is gapanalysis.
                $relatedProject[$object->id] = $object->project;
            }
        }
        elseif($objectType == 'story' || $objectType == 'team') // Get story or team related data.
        {
            if($objectType == 'team') $table = TABLE_PROJECT;
            $objectField = $objectType == 'story' ? 'id,title,type' : 'id,team AS title,type';
            $objectInfo  = $this->dao->select($objectField)->from($table)->where('id')->in($objectIdList)->fetchAll();
            foreach($objectInfo as $object)
            {
                $objectName[$object->id] = $object->title;
                if($object->type == 'requirement') $requirements[$object->id] = $object->id;
                if($object->type == 'project') $relatedProject[$object->id] = $object->id;
            }
        }
        elseif($objectType == 'stakeholder') // Get stakeholder realname.
        {
            $objectName = $this->dao->select("id, {$field} AS name")->from($table)->where('id')->in($objectIdList)->fetchPairs();
            foreach($objectName as $id => $name) $objectName[$id] = zget($users, $name);
        }
        else
        {
            $objectName = $this->dao->select("id, {$field} AS name")->from($table)->where('id')->in($objectIdList)->fetchPairs();
        }
        return array($objectName, $relatedProject, $requirements);
    }

    /**
     * 获取对象类型为team的link元素。
     * Get link element of objecttype team.
     *
     * @param  object  $action
     * @access private
     * @return array
     */
    private function getObjectTypeTeamParams(object $action): array
    {
        if($action->project) return array('project', 'team', 'projectID=' . $action->project);
        if($action->execution) return array('execution', 'team', 'executionID=' . $action->execution);

        return array('', '', '');
    }

    /**
     * 检查action是否可以被还原。
     * Check action can be undeleted.
     *
     * @param  object      $action
     * @param  object      $object
     * @access private
     * @return string|bool
     */
    private function checkActionCanUndelete(object $action, object $object): string|bool
    {
        if($action->objectType == 'execution')
        {
            if($object->deleted && empty($object->project)) return $this->lang->action->undeletedTips;
            $projectCount = $this->dao->select('count(*) AS count')->from(TABLE_PROJECT)->where('id')->eq($object->project)->andWhere('deleted')->eq('0')->fetch('count');
            if((int)$projectCount == 0) return $this->lang->action->executionNoProject;
        }
        elseif($action->objectType == 'repo' && in_array($object->SCM, array('Gitlab', 'Gitea', 'Gogs')))
        {
            $server = $this->dao->select('*')->from(TABLE_PIPELINE)->where('id')->eq($object->serviceHost)->andWhere('deleted')->eq('0')->fetch();
            if(empty($server)) return $this->lang->action->repoNoServer;
        }
        elseif($action->objectType == 'module')
        {
            $repeatName = $this->loadModel('tree')->checkUnique($object);
            if($repeatName) return sprintf($this->lang->tree->repeatName, $repeatName);

            if($object->parent > 0)
            {
                $parent = $this->dao->select('*')->from(TABLE_MODULE)->where('id')->eq($object->parent)->fetch();
                if($parent && $parent->deleted == '1') return $this->lang->action->refusemodule;
            }
        }
        elseif($action->objectType == 'case' && $object->scene)
        {
            $scene = $this->dao->select('*')->from(TABLE_SCENE)->where('id')->eq($object->scene)->fetch();
            if($scene->deleted) return $this->lang->action->refusecase;
        }
        elseif($action->objectType == 'scene' && $object->parent)
        {
            $scenerow = $this->dao->select('*')->from(TABLE_SCENE)->where('id')->eq($object->parent)->fetch();
            if($scenerow->deleted) return $this->lang->action->refusescene;
        }
        elseif($action->objectType == 'reviewissue' && !empty($object->review))
        {
            $review = $this->dao->select('*')->from(TABLE_REVIEW)->where('id')->eq($object->review)->fetch();
            if($review->deleted)
            {
                $this->app->loadLang('reviewissue');
                return $this->lang->reviewissue->undeleteAction;
            }
        }

        return true;
    }

    /**
     * 恢复被删除对象的关联数据。
     * Recover related data of deleted object.
     *
     * @param  object  $action
     * @param  object  $object
     * @access private
     * @return void
     */
    private function recoverRelatedData(object $action, object $object): void
    {
        if($action->objectType == 'release' && $object->shadow) $this->dao->update(TABLE_BUILD)->set('deleted')->eq(0)->where('id')->eq($object->shadow)->exec();

        if(in_array($action->objectType, array('program', 'project', 'execution', 'product')))
        {
            $objectType = $action->objectType == 'execution' ? 'sprint' : $action->objectType;
            if($object->acl != 'open') $this->loadModel('user')->updateUserView($object->id, $objectType);

            /* 恢复隐藏产品。 */
            /* Resotre hidden products. */
            if($action->objectType == 'project' && !$object->hasProduct)
            {
                $productID = $this->loadModel('product')->getProductIDByProject($object->id);;
                $this->dao->update(TABLE_PRODUCT)->set('name')->eq($object->name)->set('deleted')->eq(0)->where('id')->eq($productID)->exec();
            }
        }
        if($action->objectType == 'doc' && $object->files) $this->dao->update(TABLE_FILE)->set('deleted')->eq('0')->where('id')->in($object->files)->exec();

        /* 当还原项目或者执行的时候恢复用户的产品权限。 */
        /* Revert userView products when undelete project or execution. */
        if($action->objectType == 'project' || $action->objectType == 'execution')
        {
            $products = $this->loadModel('product')->getProducts($object->id, 'all', '', false);
            if(!empty($products)) $this->loadModel('user')->updateUserView(array_keys($products), 'product');

            if($action->objectType == 'execution')
            {
                $execution = $this->dao->select('id, type, project, grade, parent, status, deleted')->from(TABLE_EXECUTION)->where('id')->eq($action->objectID)->fetch();
                $this->loadModel('common')->syncExecutionByChild($execution);
            }
        }

        /* 还原产品或者项目的时候恢复文档库。 */
        /* Revert doclib when undelete product or project. */
        if($action->objectType == 'execution' || $action->objectType == 'product') $this->dao->update(TABLE_DOCLIB)->set('deleted')->eq(0)->where($action->objectType)->eq($action->objectID)->exec();

        /* 还原子任务的时候更新任务状态。 */
        /* Update task status when undelete child task. */
        if($action->objectType == 'task') $this->loadModel('task')->updateParentStatus($action->objectID);
    }

    /*
     * 生成用户访问权限检查sql。
     * Build user access check sql.
     *
     * @param  string|int $productID
     * @param  string|int $projectID
     * @param  string|int $executionID
     * @param  array      $executions
     * @access private
     * @return string
     */
    private function buildUserAclsSearchCondition(string|int $productID, string|int $projectID, string|int $executionID, array &$executions): string
    {
        /* 验证用户的产品/项目/执行权限。 */
        /* Verify user's product/project/execution permissions。*/
        $aclViews = isset($this->app->user->rights['acls']['views']) ? $this->app->user->rights['acls']['views'] : array();
        if($productID == 'all' || $productID == 0)     $grantedProducts   = empty($aclViews) || !empty($aclViews['product'])   ? $this->app->user->view->products : '0';
        if($projectID == 'all' || $projectID == 0)     $grantedProjects   = empty($aclViews) || !empty($aclViews['project'])   ? $this->app->user->view->projects : '0';
        if($executionID == 'all' || $executionID == 0) $grantedExecutions = empty($aclViews) || !empty($aclViews['execution']) ? $this->app->user->view->sprints  : '0';
        if(empty($grantedProducts)) $grantedProducts = '0';

        /* If product is selected, show related projects and executions. */
        if($productID && is_numeric($productID))
        {
            $productID  = (int)$productID;
            $projects   = $this->loadModel('product')->getProjectPairsByProduct($productID);
            $executions = $this->product->getExecutionPairsByProduct($productID) + array(0 => 0);

            $grantedProjects   = isset($grantedProjects) ? array_intersect(array_keys($projects), explode(',', $grantedProjects)) : array_keys($projects);
            $grantedExecutions = isset($grantedExecutions) ? array_intersect(array_keys($executions), explode(',', $grantedExecutions)) : array_keys($executions);
        }

        /* If project is selected, show related products and executions. */
        if($projectID && is_numeric($projectID))
        {
            $projectID  = (int)$projectID;
            $products   = $this->loadModel('product')->getProductPairsByProject($projectID);
            $executions = $this->loadModel('execution')->fetchPairs($projectID) + array(0 => 0);

            $grantedProducts   = isset($grantedProducts) ? array_intersect(array_keys($products), is_array($grantedProducts) ? $grantedProducts : explode(',', $grantedProducts)) : array_keys($products);
            $grantedExecutions = isset($grantedExecutions) ? array_intersect(array_keys($executions), is_array($grantedExecutions) ? $grantedExecutions : explode(',', $grantedExecutions)) : array_keys($executions);
        }

        /* 组建产品/项目/执行搜索条件。 */
        /* Build product/project/execution search condition. */
        if(isset($grantedProducts))
        {
            if(is_string($grantedProducts)) $grantedProducts = explode(',', $grantedProducts);
            $productCondition = '';
            foreach($grantedProducts as $product) $productCondition = empty($productCondition) ? " OR (execution = '0' and project = '0' and (product LIKE '%,{$product},%'" : "{$productCondition} OR product LIKE '%,{$product},%'";
            if(!empty($productCondition)) $productCondition .= '))';
        }
        else
        {
            $productCondition   = " OR (execution = '0' and project = '0' and product like '%,{$productID},%')";
        }
        $projectCondition   = isset($grantedProjects) ? "(execution = '0' and project != '0' and project " . helper::dbIN($grantedProjects) . ')' : "(execution = '0' and project = '{$projectID}')";
        $executionCondition = isset($grantedExecutions) ? "(execution != '0' and execution " . helper::dbIN($grantedExecutions) . ')' : "(execution != '0' and execution = '{$executionID}')";

        $condition = "((product =',0,' or product = '0' or product=',,') AND project = '0' AND execution = '0') {$productCondition} OR {$projectCondition} OR {$executionCondition}";
        return $condition;
    }

    /**
     * 执行和项目相关操作记录的extra信息。
     * Build execution and project action extra info.
     *
     * @param  object  $action
     * @access private
     * @return void
     */
    private function processExecutionAndProjectActionExtra(object $action): void
    {
        $this->app->loadLang('execution');
        $linkedProducts = $this->dao->select('id,name')->from(TABLE_PRODUCT)->where('id')->in($action->extra)->fetchPairs('id', 'name');
        $action->extra  = '';
        if($linkedProducts && $this->config->vision == 'rnd')
        {
            foreach($linkedProducts as $productID => $productName) $linkedProducts[$productID] = html::a(helper::createLink('product', 'browse', "productID={$productID}"), "#{$productID} {$productName}");
            $action->extra = sprintf($this->lang->execution->action->extra, '<strong>' . join(', ', $linkedProducts) . '</strong>');
        }
    }

    /**
     * 获取动态的数量。
     * Get dynamic count.
     *
     * @access public
     * @return int
     */
    public function getDynamicCount($period = 'all'): int
    {
        $condition = $this->session->actionQueryCondition ? $this->session->actionQueryCondition : '1=1';

        $table = $this->actionTao->getActionTable($period);

        return $this->dao->select('count(1) AS count')->from($table)->where($condition)->fetch('count');
    }

    /**
     * 清除一个月前的动态记录。
     * Clear dynamic records older than one month.
     *
     * @access public
     * @return bool
     */
    public function cleanActions(): bool
    {
        $cleanDate = zget($this->app->config->global, 'cleanActionsDate', '');
        $today     = helper::today();
        if($cleanDate == $today) return true;

        $this->loadModel('setting')->setItem('system.common.global.cleanActionsDate', $today);

        $lastMonth = date('Y-m-d', strtotime('-1 month'));
        $this->dao->delete()->from(TABLE_ACTIONRECENT)->where('date')->lt($lastMonth)->exec();
        return !dao::isError();
    }

    /**
     * 获取最早的动态记录。
     * Get the first action.
     *
     * @access public
     * @return object|bool
     */
    public function getFirstAction(): object|bool
    {
        return $this->dao->select('*')->from(TABLE_ACTION)->orderBy('id')->limit(1)->fetch();
    }
}
