<?php
/**
 * The model file of action module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     action
 * @version     $Id: model.php 5028 2013-07-06 02:59:41Z wyd621@gmail.com $
 * @link        http://www.zentao.net
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
     * @param  string      $objectType
     * @param  int         $objectID
     * @param  string      $actionType
     * @param  string|bool $comment
     * @param  string|int  $extra        the extra info of this action, according to different modules and actions, can set different extra.
     * @param  string      $actor
     * @param  bool        $autoDelete
     * @access public
     * @return int
     */
    public function create(string $objectType, int $objectID, string $actionType, string|bool $comment = '', string|float $extra = '', string $actor = '', bool $autoDelete = true): int
    {
        if(strtolower($actionType) == 'commented' && empty($comment)) return false;

        $actor      = $actor ? $actor : (!empty($this->app->user->account) ? $this->app->user->account : 'system');
        $actionType = strtolower($actionType);
        $actor      = ($actionType == 'openedbysystem' || $actionType == 'closedbysystem') ? '' : $actor;
        if($actor == 'guest' && $actionType == 'logout') return false;

        $objectType = str_replace('`', '', $objectType);

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

        /* 处理action。 */
        /* Process action. */
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

        if($this->post->uid) $this->file->updateObjectID($this->post->uid, $objectID, $objectType);

        /* 调用消息通知函数。 */
        /* Call the message notification function. */
        $this->loadModel('message')->send(strtolower($objectType), $objectID, $actionType, $actionID, $actor, $extra);

        /* 为全局搜索添加索引。 */
        /* Add index for global search. */
        $this->saveIndex($objectType, $objectID, $actionType);

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
     * 获取对象的产品
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
        $emptyRecord = array('product' => ',0,', 'project' => 0, 'execution' => 0);

        switch($objectType)
        {
            case 'program':
                return $emptyRecord;
            case 'product':
                return array('product' => ",$objectID,", 'project' => 0, 'execution' => 0);
            case 'project':
            case 'execution':
                $products = $this->dao->select('product')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($objectID)->fetchPairs('product');
                $productList = ',' . join(',', array_keys($products)) . ',';

                $relation = array($objectType => $objectID, 'product' => $productList);

                if($objectType == 'execution')
                {
                    $project = $this->dao->select('project')->from(TABLE_EXECUTION)->where('id')->eq($objectID)->fetch('project');
                    $relation['project'] = $project;
                }
                else
                {
                    $relation['execution'] = 0;
                }

                return $relation;
        }
        /* 只处理这些对象。 */
        /* Only process these object types. */
        if(strpos($this->config->action->needGetRelateField, ",{$objectType},") !== false)
        {
            if(!isset($this->config->objectTables[$objectType])) return $emptyRecord;

            $record = $emptyRecord;
            switch($objectType)
            {
                case 'story':
                    if($actionType == 'linked2build' or $actionType == 'unlinkedfrombuild')
                    {
                        $build = $this->dao->select('project,execution')->from(TABLE_BUILD)->where('id')->eq((int)$extra)->fetch();
                        $record['project']   = $build->project;
                        $record['execution'] = $build->execution;
                    }
                    elseif($actionType == 'estimated')
                    {
                        $record['project']   = $this->dao->select('project')->from(TABLE_EXECUTION)->where('id')->eq((int)$extra)->fetch('project');
                        $record['execution'] = (int)$extra;
                    }
                    else
                    {
                        $projects = $this->dao->select('t2.id,t2.project,t2.type')->from(TABLE_PROJECTSTORY)->alias('t1')
                            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
                            ->where('t1.story')->eq($objectID)
                            ->fetchAll();
                        foreach($projects as $project)
                        {
                            if($project->type == 'project')
                            {
                                $record['project'] = $project->id;
                                continue;
                            }
                            $record['project']   = $project->project;
                            $record['execution'] = $project->id;
                        }
                    }
                case 'productplan':
                case 'branch':
                    $record['product'] = $objectID == 0 ? $extra : $this->dao->select('product')->from($this->config->objectTables[$objectType])->where('id')->eq($objectID)->fetch('product');
                    break;
                case 'testcase':
                case 'case':
                    $result = $this->dao->select('product, project, execution')->from($this->config->objectTables[$objectType])->where('id')->eq($objectID)->fetch();
                    $record['product']   = $result->product;
                    $record['project']   = $result->project;
                    $record['execution'] = $result->execution;
                    if(strpos(',linked2testtask,unlinkedfromtesttask,assigned,run,', ',' . $actionType . ',') !== false and (int)$extra)
                    {
                        $testtask = $this->dao->select('project,execution')->from(TABLE_TESTTASK)->where('id')->eq((int)$extra)->fetch();
                        $record['project']   = $testtask->project;
                        $record['execution'] = $testtask->execution;
                    }
                    break;
                case 'build':
                case 'bug':
                case 'testtask':
                case 'doc':
                    $result = $this->dao->select('product, project, execution')->from($this->config->objectTables[$objectType])->where('id')->eq($objectID)->fetch();
                    $record['product']   = $result->product;
                    $record['project']   = $result->project;
                    $record['execution'] = $result->execution;
                    break;
                case 'repo':
                    $record['execution'] = $this->dao->select('execution')->from($this->config->objectTables[$objectType])->where('id')->eq($objectID)->fetch('execution');
                    break;
                case 'release':
                    $result = $this->dao->select('product, build')->from($this->config->objectTables[$objectType])->where('id')->eq($objectID)->fetch();
                    $record['product'] = $result->product;
                    $record['project'] = $this->dao->select('project')->from(TABLE_BUILD)->where('id')->eq($result->build)->fetch('project');
                    break;
                case 'task':
                    $fields = 'project, execution, story';
                    $result = $this->dao->select($fields)->from($this->config->objectTables[$objectType])->where('id')->eq($objectID)->fetch();
                    if(empty($result)) break;

                    if($result->story != 0)
                    {
                        $product = $this->dao->select('product')->from(TABLE_STORY)->where('id')->eq($result->story)->fetchPairs('product');
                        $record['product'] = join(',', array_keys($product));
                    }
                    else
                    {
                        $products = $this->dao->select('product')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($result->execution)->fetchPairs('product');
                        $record['product'] = join(',', array_keys($products));
                    }
                    $record['project']   = $result->project;
                    $record['execution'] = $result->execution;
                    break;
                case 'kanbanlane':
                    $record['execution'] = $this->dao->select('execution')->from(TABLE_KANBANLANE)->where('id')->eq($objectID)->fetch('execution');
                    break;
                case 'kanbancolumn':
                    $record['execution'] = $extra;
                    break;
                case 'team':
                    $team = $this->dao->select('type')->from(TABLE_PROJECT)->where('id')->eq($objectID)->fetch();
                    $type = $team->type == 'project' ? 'project' : 'execution';
                    $record[$type] = $objectID;
                    break;
                case 'whitelist':
                    if($extra == 'product') $record['product'] = $objectID;
                    if($extra == 'project') $record['project'] = $objectID;
                    if($extra == 'sprint' or $extra == 'stage') $record['execution'] = $objectID;
                    break;
                case 'module':
                    if(strpos(',deleted,', ",$actionType,") === false) $module = $this->dao->select('*')->from(TABLE_MODULE)->where('id')->in($extra)->fetch();
                    if(strpos(',deleted,', ",$actionType,") !== false) $module = $this->dao->select('*')->from(TABLE_MODULE)->where('id')->eq($objectID)->fetch();
                    if(!empty($module) and $module->type == 'story') $record['product'] = $module->root;
                    break;
                case 'review':
                    $result = $this->dao->select('*')->from($this->config->objectTables[$objectType])->where('id')->eq($objectID)->fetch();
                    if($result)
                    {
                        $products = $this->dao->select('product')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($result->project)->fetchPairs('product');
                        $record['product']   = join(',', array_keys($products));
                        $record['project']   = zget($result, 'project', 0);
                    }
                    break;
                default:
                    $result = $this->dao->select('*')->from($this->config->objectTables[$objectType])->where('id')->eq($objectID)->fetch();
                    $record['product']   = zget($result, 'product', '0');
                    $record['project']   = zget($result, 'project', 0);
                    $record['execution'] = zget($result, 'execution', 0);
            }

            if($actionType == 'unlinkedfromproject' or $actionType == 'linked2project') $record['project'] = (int)$extra ;
            if(in_array($actionType, array('unlinkedfromexecution', 'linked2execution', 'linked2kanban'))) $record['execution'] = (int)$extra;

            if($record)
            {
                $record['product'] = isset($record['product']) ? ',' . $record['product'] . ',' : ',0,';
                if(empty($record['project']))   $record['project']   = 0;
                if(empty($record['execution'])) $record['execution'] = 0;

                if(!empty($record['execution']) and empty($record['project'])) $record['project'] = $this->dao->select('project')->from(TABLE_EXECUTION)->where('id')->eq($record['execution'])->fetch('project');
                return $record;
            }

            return $emptyRecord;
        }

        return $emptyRecord;
    }

    /**
     * Get actions of an object.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @access public
     * @return array
     */
    public function getList(string $objectType, int $objectID): array
    {
        $objectID  = is_array($objectID) ? $objectID : (int)$objectID;
        $modules   = $objectType == 'module' ? $this->dao->select('id')->from(TABLE_MODULE)->where('root')->in($objectID)->fetchPairs('id') : array();
        $commiters = $this->loadModel('user')->getCommiters();
        $actions   = $this->dao->select('*')->from(TABLE_ACTION)
            ->beginIF($objectType == 'project')
            ->where("objectType IN('project', 'testtask', 'build')")
            ->andWhere('project')->in($objectID)
            ->fi()
            ->beginIF($objectType == 'story')
            ->where('objectType')->in('story,requirement')
            ->andWhere('objectID')->in($objectID)
            ->fi()
            ->beginIF($objectType == 'case')
            ->where('objectType')->in('case,testcase')
            ->andWhere('objectID')->in($objectID)
            ->fi()
            ->beginIF($objectType == 'module')
            ->where('objectType')->eq($objectType)
            ->andWhere('((action')->ne('deleted')->andWhere('objectID')->in($objectID)->markRight(1)
            ->orWhere('(action')->eq('deleted')->andWhere('objectID')->in($modules)->markRight(1)->markRight(1)
            ->fi()
            ->beginIF(strpos('project,case,story,module', $objectType) === false)
            ->where('objectType')->eq($objectType)
            ->andWhere('objectID')->in($objectID)
            ->fi()
            ->orderBy('date, id')
            ->fetchAll('id');

        $histories = $this->getHistory(array_keys($actions));

        if($objectType == 'project')
        {
            $actions = $this->processProjectActions($actions);
        }

        foreach($actions as $actionID => $action)
        {
            $actionName = strtolower($action->action);
            if($actionName == 'svncommited' && isset($commiters[$action->actor]))
            {
                $action->actor = $commiters[$action->actor];
            }
            elseif($actionName == 'gitcommited' && isset($commiters[$action->actor]))
            {
                $action->actor = $commiters[$action->actor];
            }
            elseif($actionName == 'linked2execution' || $actionName == 'linked2kanban')
            {
                $execution = $this->dao->select('name,type,multiple')->from(TABLE_PROJECT)->where('id')->eq($action->extra)->fetch();
                if(!empty($execution))
                {
                    if($execution->type != 'project' && empty($execution->multiple))
                    {
                        unset($actions[$actionID]);
                        continue;
                    }

                    $name   = $execution->name;
                    $method = $execution->type == 'kanban' ? 'kanban' : 'view';
                    $action->extra = (!common::hasPriv('execution', $method) || ($method == 'kanban' && isonlybody())) ? $name : html::a(helper::createLink('execution', $method, "executionID=$action->execution"), $name, '', "data-app='execution'");
                }
            }
            elseif($actionName == 'linked2project')
            {
                $project   = $this->dao->select('name,model')->from(TABLE_PROJECT)->where('id')->eq($action->extra)->fetch();
                $productID = trim($action->product, ',');
                $name      = $project->name;
                $method    = $project->model == 'kanban' ? 'index' : 'view';
                if($name) $action->extra = common::hasPriv('project', $method) ? html::a(helper::createLink('project', $method, "projectID=$action->project"), $name) : $name;
            }
            elseif($actionName == 'linked2plan')
            {
                $title = $this->dao->select('title')->from(TABLE_PRODUCTPLAN)->where('id')->eq($action->extra)->fetch('title');
                if($title) $action->extra = common::hasPriv('productplan', 'view') ? html::a(helper::createLink('productplan', 'view', "planID=$action->extra"), $title) : $title;
            }
            elseif($actionName == 'linked2build')
            {
                $name = $this->dao->select('name')->from(TABLE_BUILD)->where('id')->eq($action->extra)->fetch('name');
                if($name) $action->extra = common::hasPriv('build', 'view') ? html::a(helper::createLink('build', 'view', "builID=$action->extra&type={$action->objectType}"), $name) : $name;
            }
            elseif($actionName == 'linked2bug')
            {
                $name = $this->dao->select('name')->from(TABLE_BUILD)->where('id')->eq($action->extra)->fetch('name');
                if($name) $action->extra = common::hasPriv('build', 'view') ? html::a(helper::createLink('build', 'view', "builID=$action->extra&type={$action->objectType}"), $name) : $name;
            }
            elseif($actionName == 'linked2release')
            {
                $name = $this->dao->select('name')->from(TABLE_RELEASE)->where('id')->eq($action->extra)->fetch('name');
                if($name) $action->extra = common::hasPriv('release', 'view') ? html::a(helper::createLink('release', 'view', "releaseID=$action->extra&type={$action->objectType}"), $name) : $name;
            }
            elseif($actionName == 'linked2testtask')
            {
                $name = $this->dao->select('name')->from(TABLE_TESTTASK)->where('id')->eq($action->extra)->fetch('name');
                if($name) $action->extra = common::hasPriv('testtask', 'view') ? html::a(helper::createLink('testtask', 'view', "taskID=$action->extra"), $name) : $name;
            }
            elseif($actionName == 'linked2revision' || $actionName == 'unlinkedfromrevision')
            {
                $commit = $this->dao->select('repo,revision')->from(TABLE_REPOHISTORY)->where('id')->eq($action->extra)->fetch();
                if($commit)
                {
                    $revision = substr($commit->revision, 0, 10);
                    $action->extra = common::hasPriv('repo', 'revision') ? html::a(helper::createLink('repo', 'revision', "repoID=$commit->repo&objectID=0&revision=$commit->revision"), $revision) : $revision;
                }
            }
            elseif($actionName == 'moved' && $action->objectType != 'module')
            {
                $name = $this->dao->select('name')->from(TABLE_PROJECT)->where('id')->eq($action->extra)->fetch('name');
                if($name) $action->extra = common::hasPriv('execution', 'task') ? html::a(helper::createLink('execution', 'task', "executionID=$action->extra"), "#$action->extra " . $name) : "#$action->extra " . $name;
            }
            elseif($actionName == 'frombug' && common::hasPriv('bug', 'view'))
            {
                $action->extra = html::a(helper::createLink('bug', 'view', "bugID=$action->extra"), $action->extra);
            }
            elseif($actionName == 'unlinkedfromexecution')
            {
                $name = $this->dao->select('name')->from(TABLE_PROJECT)->where('id')->eq($action->extra)->fetch('name');
                if($name) $action->extra = common::hasPriv('execution', 'view') ? html::a(helper::createLink('execution', 'view', "executionID=$action->extra"), "#$action->extra " . $name) : "#$action->extra " . $name;
            }
            elseif($actionName == 'unlinkedfromproject')
            {
                $name      = $this->dao->select('name')->from(TABLE_PROJECT)->where('id')->eq($action->extra)->fetch('name');
                $productID = trim($action->product, ',');
                if($name) $action->extra = common::hasPriv('projectstory', 'story') ? html::a(helper::createLink('projectstory', 'story', "projectID=$action->execution&productID=$productID"), "#$action->extra " . $name) : "#$action->extra " . $name;
            }
            elseif($actionName == 'unlinkedfrombuild')
            {
                $name = $this->dao->select('name')->from(TABLE_BUILD)->where('id')->eq($action->extra)->fetch('name');
                if($name) $action->extra = common::hasPriv('build', 'view') ? html::a(helper::createLink('build', 'view', "builID=$action->extra&type={$action->objectType}"), $name) : $name;
            }
            elseif($actionName == 'unlinkedfromrelease')
            {
                $name = $this->dao->select('name')->from(TABLE_RELEASE)->where('id')->eq($action->extra)->fetch('name');
                if($name) $action->extra = common::hasPriv('release', 'view') ? html::a(helper::createLink('release', 'view', "releaseID=$action->extra&type={$action->objectType}"), $name) : $name;
            }
            elseif($actionName == 'unlinkedfromplan')
            {
                $title = $this->dao->select('title')->from(TABLE_PRODUCTPLAN)->where('id')->eq($action->extra)->fetch('title');
                if($title) $action->extra = common::hasPriv('productplan', 'view') ? html::a(helper::createLink('productplan', 'view', "planID=$action->extra"), "#$action->extra " . $title) : "#$action->extra " . $title;
            }
            elseif($actionName == 'unlinkedfromtesttask')
            {
                $name = $this->dao->select('name')->from(TABLE_TESTTASK)->where('id')->eq($action->extra)->fetch('name');
                if($name) $action->extra = common::hasPriv('testtask', 'view') ? html::a(helper::createLink('testtask', 'view', "taskID=$action->extra"), $name) : $name;
            }
            elseif(strpos('feedback,ticket', $action->objectType) === false && $actionName == 'tostory')
            {
                $productShadow = $this->dao->select('shadow')->from(TABLE_PRODUCT)->where('id')->in(trim($action->product, ','))->fetch('shadow');
                $title         = $this->dao->select('title')->from(TABLE_STORY)->where('id')->eq($action->extra)->fetch('title');
                $defaultExtra  = "#$action->extra " . $title;
                if($productShadow)
                {
                    $projectID = $this->dao->select('project')->from(TABLE_PROJECTSTORY)->where('story')->eq($action->extra)->fetch('project');
                    if($title) $action->extra = (common::hasPriv('projectstory', 'view') && $projectID) ? html::a(helper::createLink('projectstory', 'view', "storyID={$action->extra}&projectID=$projectID"), $defaultExtra) : $defaultExtra;
                }
                else
                {
                    if($title) $action->extra = common::hasPriv('story', 'view') ?  html::a(helper::createLink('story', 'view', "storyID=$action->extra"), $defaultExtra) : $defaultExtra;
                }
            }
            elseif($actionName == 'importedcard')
            {
                $title = $this->dao->select('name')->from(TABLE_KANBAN)->where('id')->eq($action->extra)->fetch('name');
                if($title) $action->extra = (common::hasPriv('kanban', 'view') && !isonlybody()) ? html::a(helper::createLink('kanban', 'view', "kanbanID=$action->extra"), "#$action->extra " . $title) : "#$action->extra " . $title;
            }
            elseif($actionName == 'createchildren')
            {
                $names = $this->dao->select('id,name')->from(TABLE_TASK)->where('id')->in($action->extra)->fetchPairs('id', 'name');
                $action->extra = '';
                if($names)
                {
                    foreach($names as $id => $name) $action->extra .= common::hasPriv('task', 'view') ? html::a(helper::createLink('task', 'view', "taskID=$id"), "#$id " . $name) . ', ' : "#$id " . $name . ', ';
                }
                $action->extra = trim(trim($action->extra), ',');
            }

            /* 瀑布模型相关的代码。 */
            /* Code for wataerfall mode. */
            elseif($actionName == 'createrequirements')
            {
                $names = $this->dao->select('id,title')->from(TABLE_STORY)->where('id')->in($action->extra)->fetchPairs('id', 'title');
                $action->extra = '';
                if($names)
                {
                    foreach($names as $id => $name) $action->extra .= common::hasPriv('requriement', 'view') ? html::a(helper::createLink('story', 'view', "storyID=$id"), "#$id " . $name) . ', ' : "#$id " . $name . ', ';
                }
                $action->extra = trim(trim($action->extra), ',');
            }
            elseif($action->objectType != 'feedback' && (strpos(',totask,linkchildtask,unlinkchildrentask,linkparenttask,unlinkparenttask,deletechildrentask,', ",$actionName,") !== false))
            {
                $name = $this->dao->select('name')->from(TABLE_TASK)->where('id')->eq($action->extra)->fetch('name');
                if($name) $action->extra = common::hasPriv('task', 'view') ? html::a(helper::createLink('task', 'view', "taskID=$action->extra"), "#$action->extra " . $name) : "#$action->extra " . $name;
            }
            elseif($actionName == 'linkchildstory' || $actionName == 'unlinkchildrenstory' || $actionName == 'linkparentstory' || $actionName == 'unlinkparentstory' || $actionName == 'deletechildrenstory')
            {
                $name = $this->dao->select('title')->from(TABLE_STORY)->where('id')->eq($action->extra)->fetch('title');
                if($name) $action->extra = common::hasPriv('story', 'view') ? html::a(helper::createLink('story', 'view', "storyID=$action->extra"), "#$action->extra " . $name) : "#$action->extra " . $name;
            }
            elseif($actionName == 'buildopened')
            {
                $name = $this->dao->select('name')->from(TABLE_BUILD)->where('id')->eq($action->objectID)->fetch('name');
                if($name) $action->extra = common::hasPriv('build', 'view') ? html::a(helper::createLink('build', 'view', "buildID=$action->objectID"), "#$action->objectID " . $name) : "#$action->objectID " . $name;
            }
            elseif($actionName == 'testtaskopened' || $actionName == 'testtaskstarted' || $actionName == 'testtaskclosed')
            {
                $name = $this->dao->select('name')->from(TABLE_TESTTASK)->where('id')->eq($action->objectID)->fetch('name');
                if($name) $action->extra = common::hasPriv('testtask', 'view') ? html::a(helper::createLink('testtask', 'view', "testtaskID=$action->objectID"), "#$action->objectID " . $name) : "#$action->objectID " . $name;
            }
            elseif($actionName == 'fromlib' && $action->objectType == 'case')
            {
                $name = $this->dao->select('name')->from(TABLE_TESTSUITE)->where('id')->eq($action->extra)->fetch('name');
                if($name) $action->extra = common::hasPriv('caselib', 'browse') ? html::a(helper::createLink('caselib', 'browse', "libID=$action->extra"), $name) : $name;
            }
            elseif(strpos(',importfromstorylib,importfromrisklib,importfromissuelib,importfromopportunitylib,', ",{$actionName},") !== false && $this->config->edition == 'max')
            {
                $name = $this->dao->select('name')->from(TABLE_ASSETLIB)->where('id')->eq($action->extra)->fetch('name');
                if($name) $action->extra = common::hasPriv('assetlib', $action->objectType) ? html::a(helper::createLink('assetlib', $action->objectType, "libID=$action->extra"), $name) : $name;
            }
            elseif(($actionName == 'closed' && $action->objectType == 'story') || ($actionName == 'resolved' && $action->objectType == 'bug'))
            {
                $action->appendLink = '';
                if(strpos($action->extra, '|') !== false) $action->extra = substr($action->extra, 0, strpos($action->extra, '|'));
                if(strpos($action->extra, ':') !== false)
                {
                    list($extra, $id) = explode(':', $action->extra);
                    $action->extra    = $extra;
                    if($id)
                    {
                        $table = $action->objectType == 'story' ? TABLE_STORY : TABLE_BUG;
                        $name  = $this->dao->select('title')->from($table)->where('id')->eq($id)->fetch('title');
                        if($name) $action->appendLink = html::a(helper::createLink($action->objectType, 'view', "id=$id"), "#$id " . $name);
                    }
                }
            }
            elseif($actionName == 'finished' && $objectType == 'todo')
            {
                $action->appendLink = '';
                if(strpos($action->extra, ':')!== false)
                {
                    list($extra, $id) = explode(':', $action->extra);
                    $action->extra    = strtolower($extra);
                    if($id)
                    {
                        $table = $this->config->objectTables[$action->extra];
                        $field = $this->config->action->objectNameFields[$action->extra];
                        $name  = $this->dao->select($field)->from($table)->where('id')->eq($id)->fetch($field);
                        if($name) $action->appendLink = html::a(helper::createLink($action->extra, 'view', "id=$id"), "#$id " . $name);
                    }
                }
            }
            elseif(($actionName == 'opened' || $actionName == 'managed' || $actionName == 'edited') && ($objectType == 'execution' || $objectType == 'project'))
            {
                $this->app->loadLang('execution');
                $linkedProducts = $this->dao->select('id,name')->from(TABLE_PRODUCT)->where('id')->in($action->extra)->fetchPairs('id', 'name');
                $action->extra  = '';
                if($linkedProducts && $this->config->vision == 'rnd')
                {
                    foreach($linkedProducts as $productID => $productName) $linkedProducts[$productID] = html::a(helper::createLink('product', 'browse', "productID=$productID"), "#{$productID} {$productName}");
                    $action->extra = sprintf($this->lang->execution->action->extra, '<strong>' . join(', ', $linkedProducts) . '</strong>');
                }
            }
            $action->history = isset($histories[$actionID]) ? $histories[$actionID] : array();

            $actionName = strtolower($action->action);
            if($actionName == 'svncommited')
            {
                foreach($action->history as $history)
                {
                    if($history->field == 'subversion') $history->diff = str_replace('+', '%2B', $history->diff);
                }
            }
            elseif($actionName == 'gitcommited')
            {
                foreach($action->history as $history)
                {
                    if($history->field == 'git') $history->diff = str_replace('+', '%2B', $history->diff);
                }
            }
            elseif(strpos(',linkstory,unlinkstory,createchildrenstory,', ",$actionName,") !== false)
            {
                $extra = '';
                foreach(explode(',', $action->extra) as $id) $extra .= common::hasPriv('story', 'view') ? html::a(helper::createLink('story', 'view', "storyID=$id"), "#$id ") . ', ' : "#$id, ";
                $action->extra = trim(trim($extra), ',');
            }
            elseif($actionName == 'linkbug' or $actionName == 'unlinkbug')
            {
                $extra = '';
                foreach(explode(',', $action->extra) as $id) $extra .= common::hasPriv('bug', 'view') ? html::a(helper::createLink('bug', 'view', "bugID=$id"), "#$id ") . ', ' : "#$id, ";
                $action->extra = trim(trim($extra), ',');
            }

            $this->loadModel('file');
            $action->comment = $this->file->setImgSize($action->comment, $this->config->action->commonImgSize);

            $actions[$actionID] = $action;
        }

        return $actions;
    }

    /**
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
            if($action->objectType != 'project' and !isset($map[$action->objectType][$action->action])) unset($actions[$key]);
            if(isset($map[$action->objectType][$action->action])) $action->action = $map[$action->objectType][$action->action];
        }

        return $actions;
    }

    /**
     * 获取一条操作记录。
     * Get an action record.
     *
     * @param  int    $actionID
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
     * @param  string    $objectType
     * @param  string    $type all|hidden
     * @param  string    $orderBy
     * @param  object    $pager
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
            ->orderBy($orderBy)->page($pager)->fetchAll();
        if(!$trashes) return array();

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

            $objectIdList = array_unique($objectIdList);
            $table        = $this->config->objectTables[$objectType];
            $field        = $this->config->action->objectNameFields[$objectType];
            if($objectType == 'pipeline')
            {
                $objectNames['jenkins'] = $this->dao->select("id, $field AS name")->from($table)->where('id')->in($objectIdList)->andWhere('type')->eq('jenkins')->fetchPairs();
                $objectNames['gitlab']  = $this->dao->select("id, $field AS name")->from($table)->where('id')->in($objectIdList)->andWhere('type')->eq('gitlab')->fetchPairs();
            }
            else
            {
                $objectNames[$objectType] = $this->dao->select("id, $field AS name")->from($table)->where('id')->in($objectIdList)->fetchPairs();
            }
        }

        /* 将name字段添加到回收站数据中。 */
        /* Add name field to the trashes. */
        foreach($trashes as $trash)
        {
            $objectType = $trash->objectType;
            if($objectType == 'pipeline')
            {
                if(isset($objectNames['gitlab'][$trash->objectID]))  $objectType = 'gitlab';
                if(isset($objectNames['jenkins'][$trash->objectID])) $objectType = 'jenkins';
                $trash->objectType = $objectType;
            }

            $trash->objectName = isset($objectNames[$objectType][$trash->objectID]) ? $objectNames[$objectType][$trash->objectID] : '';
        }

        return $trashes;
    }

    /**
     * 通过查询获取回收站内的对象。
     * Get deleted objects by search.
     *
     * @param  string $objectType
     * @param  string $type all|hidden
     * @param  int    $queryID
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getTrashesBySearch(string $objectType, string $type, int $queryID, string $orderBy, object $pager = null): array
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
            if($this->session->trashQuery == false) $this->session->set('trashQuery', ' 1 = 1');
        }

        $extra      = $type == 'hidden' ? self::BE_HIDDEN : self::CAN_UNDELETED;
        $trashQuery = $this->session->trashQuery;
        $trashQuery = str_replace(array('`objectID`', '`actor`', '`date`'), array('t1.`objectID`', 't1.`actor`', 't1.`date`'), $trashQuery);
        $table      = $this->config->objectTables[$objectType];
        $nameField  = isset($this->config->action->objectNameFields[$objectType]) ? 't2.' . "`{$this->config->action->objectNameFields[$objectType]}`" : '';

        if($nameField) $trashQuery = preg_replace("/`objectName`/", $nameField, $trashQuery);

        if($objectType != 'pipeline')
        {
            $trashes = $this->dao->select("t1.*, $nameField as objectName")->from(TABLE_ACTION)->alias('t1')
                ->leftJoin($table)->alias('t2')->on('t1.objectID=t2.id')
                ->where('t1.action')->eq('deleted')
                ->andWhere($trashQuery)
                ->andWhere('t1.extra')->eq($extra)
                ->andWhere('t1.vision')->eq($this->config->vision)
                ->beginIF($objectType != 'all')->andWhere('t1.objectType')->eq($objectType)->fi()
                ->orderBy($orderBy)
                ->page($pager)
                ->fetchAll('objectID');
        }
        else
        {
            $trashes = $this->dao->select("t1.*, t1.objectType as type, t2.name as objectName, t2.type as objectType")->from(TABLE_ACTION)->alias('t1')
                ->leftJoin(TABLE_PIPELINE)->alias('t2')->on('t1.objectID=t2.id')
                ->where('t1.action')->eq('deleted')
                ->andWhere($trashQuery)
                ->andWhere('t1.extra')->eq($extra)
                ->andWhere('t1.vision')->eq($this->config->vision)
                ->andWhere('(t2.type')->eq('gitlab')
                ->orWhere('t2.type')->eq('jenkins')
                ->markRight(1)
                ->orderBy($orderBy)
                ->page($pager)
                ->fetchAll('objectID');
        }

        return $trashes;
    }

    /**
     * Get object type list of trashes.
     *
     * @param  string  $type
     * @access public
     * @return array
     */
    public function getTrashObjectTypes($type)
    {
        $extra = $type == 'hidden' ? self::BE_HIDDEN : self::CAN_UNDELETED;
        return $this->dao->select('objectType')->from(TABLE_ACTION)->where('action')->eq('deleted')->andWhere('extra')->eq($extra)->andWhere('vision')->eq($this->config->vision)->fetchAll('objectType');
    }

    /**
     * Get histories of an action.
     *
     * @param  int    $actionID
     * @access public
     * @return array
     */
    public function getHistory($actionID)
    {
        return $this->dao->select()->from(TABLE_HISTORY)->where('action')->in($actionID)->fetchGroup('action');
    }

    /**
     * Log histories for an action.
     *
     * @param  int    $actionID
     * @param  array  $changes
     * @access public
     * @return void
     */
    public function logHistory($actionID, $changes)
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
        }
    }

    /**
     * Print actions of an object.
     *
     * @param  object    $action
     * @param  string   $desc
     * @access public
     * @return void
     */
    public function renderAction($action, $desc = '')
    {
        if(!isset($action->objectType) || !isset($action->action)) return false;

        $objectType = $action->objectType;
        $actionType = strtolower($action->action);

        /**
         * Set the desc string of this action.
         *
         * 1. If the module of this action has defined desc of this actionType, use it.
         * 2. If no defined in the module language, search the common action define.
         * 3. If not found in the lang->action->desc, use the $lang->action->desc->common or $lang->action->desc->extra as the default.
         */
        if(empty($desc))
        {
            if($action->objectType == 'story' && $action->action == 'reviewed' && strpos($action->extra, ',') !== false)
            {
                $desc = $this->lang->$objectType->action->rejectreviewed;
            }
            elseif($action->objectType == 'productplan' && in_array($action->action, array('startedbychild','finishedbychild','closedbychild','activatedbychild', 'createchild')))
            {
                $desc = $this->lang->$objectType->action->changebychild;
            }
            elseif($action->objectType == 'module' && in_array($action->action, array('created', 'moved', 'deleted')))
            {
                $desc = $this->lang->$objectType->action->{$action->action};
            }
            elseif(strpos('createmr,editmr,removemr', $action->action) !== false && strpos($action->extra, '::') !== false)
            {
                $mrAction = str_replace('mr', '', $action->action) . 'Action';
                list($mrDate, $mrActor, $mrLink) = explode('::', $action->extra);

                if(isonlybody()) $mrLink .= ($this->config->requestType == 'GET' ? '&onlybody=yes' : '?onlybody=yes');

                $this->app->loadLang('mr');
                $desc = sprintf($this->lang->mr->$mrAction, $mrDate, $mrActor, $mrLink);
            }
            elseif($this->config->edition == 'max' && strpos($this->config->action->assetType, ",{$action->objectType},") !== false && $action->action == 'approved')
            {
                $desc = empty($this->lang->action->approve->{$action->extra}) ? '' : $this->lang->action->approve->{$action->extra};
            }
            elseif(isset($this->lang->$objectType) && isset($this->lang->$objectType->action->$actionType))
            {
                $desc = $this->lang->$objectType->action->$actionType;
            }
            elseif($action->objectType == 'instance' && isset($this->lang->action->desc->$actionType))
            {
                $desc  = $this->lang->action->desc->$actionType;
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
            elseif(isset($this->lang->action->desc->$actionType))
            {
                $desc = $this->lang->action->desc->$actionType;
            }
            else
            {
                $desc = $action->extra ? $this->lang->action->desc->extra : $this->lang->action->desc->common;
            }
        }

        $action->date = substr($action->date, 0, 19);
        if($this->app->getViewType() == 'mhtml') $action->date = date('m-d H:i', strtotime($action->date));

        /* Cycle actions, replace vars. */
        foreach($action as $key => $value)
        {
            if($key == 'history') continue;

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
                    $desc['main'] = str_replace('$' . $key, $value, $desc['main']);
                }
            }
            else
            {
                if($actionType == 'restoredsnapshot' && in_array($action->objectType, array('vm', 'zanode')) && $value == 'defaultSnap') $value = $this->lang->$objectType->snapshot->defaultSnapName;

                $desc = str_replace('$' . $key, $value, $desc);
            }
        }

        /* If the desc is an array, process extra. Please bug/lang. */
        if(!is_array($desc)) return $desc;

        $extra = strtolower($action->extra);

        /* Fix bug #741. */
        if(isset($desc['extra'])) $desc['extra'] = $this->lang->$objectType->{$desc['extra']};

        $actionDesc = '';
        if(isset($desc['extra'][$extra]))
        {
            $actionDesc = str_replace('$extra', $desc['extra'][$extra], $desc['main']);
        }
        else
        {
            $actionDesc = str_replace('$extra', $action->extra, $desc['main']);
        }

        if($action->objectType == 'story' && $action->action == 'reviewed')
        {
            if(strpos($action->extra, ',') !== false)
            {
                list($extra, $reason) = explode(',', $extra);
                $desc['reason'] = $this->lang->$objectType->{$desc['reason']};
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
                $desc['operate'] = $this->lang->$objectType->{$desc['operate']};
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
            $actionDesc  = str_replace('$extra', zget($moduleNames, $action->objectID), $desc['main']);
        }
        return $actionDesc;
    }

    /**
     * Print actions of an object.
     *
     * @param  object    $action
     * @param  string   $desc
     * @access public
     * @return void
     */
    public function printAction($action, $desc = '')
    {
        $content = $this->renderAction($action, $desc);
        if(is_string($content))
        {
            echo $content;
            return;
        }
        return false;
    }

    /**
     * 动态的获取action。
     * Get actions as dynamic.
     *
     * @param  string $account
     * @param  string $period
     * @param  string $orderBy
     * @param  object $pager
     * @param  string|int $productID   all|int(like 123)|notzero   all => include zero, notzero, greater than 0
     * @param  string|int $projectID   same as productID
     * @param  string|int $executionID same as productID
     * @param  string $date
     * @param  string $direction
     * @access public
     * @return array
     */
    public function getDynamic(string $account = 'all', string $period = 'all', string $orderBy = 'date_desc', object $pager = null, string|int $productID = 'all', string|int $projectID = 'all', string|int $executionID = 'all', string $date = '', string $direction = 'next'): array
    {
        /* 计算时间段的开始和结束时间。 */
        /* Computer the begin and end date of a period. */
        $beginAndEnd = $this->computeBeginAndEnd($period);
        extract($beginAndEnd);

        /* 构建权限搜索条件。 */
        /* Build has priv search condition. */
        $condition  = '1=1';
        $executions = array();
        if(!$this->app->user->admin)
        {
            /* 验证用户的产品/项目/执行权限。 */
            /* Verify user's product/project/execution permissions。*/
            $aclViews = isset($this->app->user->rights['acls']['views']) ? $this->app->user->rights['acls']['views'] : array();
            if($productID == 'all')   $authedProducts   = (empty($aclViews) || (!empty($aclViews) && !empty($aclViews['product'])))   ? $this->app->user->view->products : '0';
            if($projectID == 'all')   $authedProjects   = (empty($aclViews) || (!empty($aclViews) && !empty($aclViews['project'])))   ? $this->app->user->view->projects : '0';
            if($executionID == 'all') $authedExecutions = (empty($aclViews) || (!empty($aclViews) && !empty($aclViews['execution']))) ? $this->app->user->view->sprints  : '0';

            if(empty($authedProducts)) $authedProducts = '0';

            /* 组建产品/项目/执行搜索条件。 */
            /* Build product/project/execution search condition. */
            if($productID == 'all' && $projectID == 'all')
            {
                $productCondition = '';
                foreach(explode(',', $authedProducts) as $product) $productCondition = empty($productCondition) ? "(execution = '0' AND project = '0' AND (product LIKE '%,$product,%'" : "$productCondition OR product LIKE '%,$product,%'";
                if(!empty($productCondition)) $productCondition .= '))';

                $projectCondition   = "(execution = '0' AND project != '0' AND project " . helper::dbIN($authedProjects) . ')';
                $executionCondition = isset($authedExecutions) ? "(execution != 0 AND execution " . helper::dbIN($authedExecutions) . ')' : "(execution != 0 AND execution = '$executionID')";
            }
            elseif($productID == 'all' && is_numeric($projectID))
            {
                $products   = $this->loadModel('product')->getProductPairsByProject($projectID);
                $executions = $this->loadModel('execution')->getPairs($projectID) + array(0 => 0);

                $authedExecutions = isset($authedExecutions) ? array_intersect(array_keys($executions), explode(',', $authedExecutions)) : array_keys($executions);

                $productCondition = '';
                foreach(array_keys($products) as $product) $productCondition = empty($productCondition) ? "(execution = '0' AND project = '0' AND (product LIKE '%,$product,%'" : "$productCondition OR product LIKE '%,$product,%'";
                if(!empty($productCondition)) $productCondition .= '))';

                $projectCondition   = "(execution = '0' AND project = '$projectID')";
                $executionCondition = "(execution != '0' AND execution " . helper::dbIN($authedExecutions) . ')';
            }
            elseif(is_numeric($productID) && $projectID == 'all')
            {
                $this->loadModel('product');
                $projects   = $this->product->getProjectPairsByProduct($productID);
                $executions = $this->product->getExecutionPairsByProduct($productID) + array(0 => 0);

                $authedProjects   = array_intersect(array_keys($projects), explode(',', $authedProjects));
                $authedExecutions = isset($authedExecutions) ? array_intersect(array_keys($executions), explode(',', $authedExecutions)) : array_keys($executions);

                $productCondition   = "(execution = '0' AND project = '0' AND product LIKE '%,$productID,%')";
                $projectCondition   = "(execution = '0' AND project != '0' AND project " . helper::dbIN($authedProjects) . ')';
                $executionCondition = "(execution != '0' AND execution " . helper::dbIN($authedExecutions) . ')';
            }

            $condition = "((product =',0,' OR product = '0' OR product=',,') AND project = '0' AND execution = '0')";
            if(!empty($productCondition))   $condition .= " OR $productCondition";
            if(!empty($projectCondition))   $condition .= " OR $projectCondition";
            if(!empty($executionCondition)) $condition .= " OR $executionCondition";
        }

        $actionCondition = $this->getActionCondition();
        if(!$actionCondition && !$this->app->user->admin && isset($this->app->user->rights['acls']['actions'])) return array();

        /* 用户不传入时间的情况下，限定只能查询今年的数据。 */
        /* If the user does not enter the time, only this year's data can be queried. */
        $beginDate = '';
        if($period == 'all')
        {
            $year = date('Y');
            $beginDate = $year . '-01-01';
            
            /* 查询所有动态时最多查询最后两年的数据。 */
            /* When query all dynamic then query the data of the last two years at most. */
            if($this->app->getMethodName() == 'dynamic') $beginDate = $year - 1 . '-01-01';
        }

        $programCondition = empty($this->app->user->view->programs) ? '0' : $this->app->user->view->programs;

        $efforts = $this->dao->select('id')->from(TABLE_EFFORT)->where($condition)->fetchPairs();
        $efforts = !empty($efforts) ? implode(',', $efforts) : 0;

        $noMultipleExecutions = $this->dao->select('id')->from(TABLE_PROJECT)->where('multiple')->eq(0)->andWhere('type')->in('sprint,kanban')->fetchPairs('id', 'id');

        $condition = "(`objectType` IN ('doc', 'doclib') OR ($condition)) AND `objectType` NOT IN ('program', 'effort', 'execution')";
        if($noMultipleExecutions) $condition .= " OR (`objectID` NOT " . helper::dbIN($noMultipleExecutions) . " AND `objectType` = 'execution')";
        $condition .= " OR (`objectID` IN ($programCondition) AND `objectType` = 'program')";
        $condition .= " OR (`objectID` IN ($efforts) AND `objectType` = 'effort')";
        $condition  = "($condition)";
    
        /* 获取action数据。 */
        /* Get actions. */
        $actions = $this->dao->select('*')->from(TABLE_ACTION)
            ->where('objectType')->notIN($this->config->action->ignoreObjectType4Dynamic)
            ->andWhere('vision')->eq($this->config->vision)
            ->beginIF($period != 'all')->andWhere('date')->gt($begin)->fi()
            ->beginIF($period != 'all')->andWhere('date')->lt($end)->fi()
            ->beginIF($date)->andWhere('date' . ($direction == 'next' ? '<' : '>') . "'{$date}'")->fi()
            ->beginIF($account != 'all')->andWhere('actor')->eq($account)->fi()
            ->beginIF($beginDate)->andWhere('date')->ge($beginDate)->fi()
            ->beginIF(is_numeric($productID))->andWhere('product')->like("%,$productID,%")->fi()
            ->andWhere('1=1', true)
            ->beginIF(is_numeric($projectID))->andWhere('project')->eq($projectID)->fi()
            ->beginIF(!empty($executions))->andWhere('execution')->in(array_keys($executions))->fi()
            ->beginIF(is_numeric($executionID))->andWhere('execution')->eq($executionID)->fi()
            ->markRight(1)
            /* lite模式下需要排除的一些类型。 */
            /* Types excluded from Lite. */
            ->beginIF($this->config->vision == 'lite')->andWhere('objectType')->notin('product')->fi()
            ->beginIF($this->config->systemMode == 'light')->andWhere('objectType')->notin('program')->fi()
            ->beginIF($productID == 'notzero')->andWhere('product')->gt(0)->andWhere('product')->notlike('%,0,%')->fi()
            ->beginIF($projectID == 'notzero')->andWhere('project')->gt(0)->fi()
            ->beginIF($executionID == 'notzero')->andWhere('execution')->gt(0)->fi()
            ->andWhere($condition)
            ->beginIF($actionCondition)->andWhere("($actionCondition)")->fi()
            /* 过滤客户端的登陆登出操作。 */
            /* Filter out client login/logout actions. */
            ->andWhere('action')->notin('disconnectxuanxuan,reconnectxuanxuan,loginxuanxuan,logoutxuanxuan,editmr,removemr')
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();

        if(!$actions) return array();

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'action');
        return $this->transformActions($actions);
    }

    /**
     * Get dynamic show action.
     *
     * @return String
     */
    public function getActionCondition()
    {
        if($this->app->user->admin) return '';

        $actionCondition = '';
        if(isset($this->app->user->rights['acls']['actions']))
        {
            if(empty($this->app->user->rights['acls']['actions'])) return '';

            foreach($this->app->user->rights['acls']['actions'] as $moduleName => $actions)
            {
                if(isset($this->lang->mainNav->$moduleName) and !empty($this->app->user->rights['acls']['views']) and !isset($this->app->user->rights['acls']['views'][$moduleName])) continue;
                $actionCondition .= "(`objectType` = '$moduleName' and `action` " . helper::dbIN($actions) . ") or ";
            }
            $actionCondition = trim($actionCondition, 'or ');
        }
        return $actionCondition;
    }

    /**
     * Get dynamic by search.
     *
     * @param  array  $products
     * @param  array  $projects
     * @param  array  $executions
     * @param  int    $queryID
     * @param  string $orderBy
     * @param  object $pager
     * @param  string $date
     * @param  string $direction
     * @access public
     * @return array
     */
    public function getDynamicBySearch($products, $projects, $executions, $queryID, $orderBy = 'date_desc', $pager = null, $date = '', $direction = 'next')
    {
        $query = $queryID ? $this->loadModel('search')->getQuery($queryID) : '';

        /* Get the sql and form status from the query. */
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

        $productID = 0;
        if(preg_match("/`product` = '(\d*)'/", $actionQuery, $out))
        {
            $productID = $out[1];
        }

        /* If the sql not include 'product', add check purview for product. */
        if(strpos($actionQuery, $allProducts) !== false)
        {
            $actionQuery = str_replace($allProducts, '1', $actionQuery);
        }

        /* If the sql not include 'project', add check purview for project. */
        if(strpos($actionQuery, $allProjects) !== false)
        {
            $actionQuery = str_replace($allProjects, '1', $actionQuery);
        }

        /* If the sql not include 'execution', add check purview for execution. */
        if(strpos($actionQuery, $allExecutions) !== false)
        {
            $actionQuery = str_replace($allExecutions, '1', $actionQuery);
        }

        $actionQuery = str_replace("`product` = '$productID'", "`product` LIKE '%,$productID,%'", $actionQuery);

        if($date) $actionQuery = "($actionQuery) AND " . ('date' . ($direction == 'next' ? '<' : '>') . "'{$date}'");

        /* If this vision is lite, delete product actions. */
        if($this->config->vision == 'lite') $actionQuery .= " AND objectType != 'product'";

        $actionQuery .= " AND vision = '" . $this->config->vision . "'";
        $actions      = $this->getBySQL($actionQuery, $orderBy, $pager);

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'action');
        if(!$actions) return array();
        return $this->transformActions($actions);
    }

    /**
     * 通过sql获取actions。
     * Get actions by SQL.
     *
     * @param  string $sql
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getBySQL(string $sql, string $orderBy, object $pager = null): array
    {
        $actionCondition = $this->getActionCondition();
        if(is_array($actionCondition)) return array();

        return $this->dao->select('*')->from(TABLE_ACTION)
            ->where($sql)
            ->beginIF(!empty($actionCondition))->andWhere("($actionCondition)")->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
    }

    /**
     * 转换actions用于显示。
     * Transform the actions for display.
     *
     * @param  array  $actions
     * @access public
     * @return array
     */
    public function transformActions($actions): array
    {
        $this->app->loadLang('todo');
        $this->app->loadLang('stakeholder');
        $this->app->loadLang('branch');
        $this->app->loadLang('execution');
        
        /* 获取评论用户以及受信任的部门用户。 */
        /* Get commiters and the same department users. */
        $commiters = $this->loadModel('user')->getCommiters();
        $deptUsers = isset($this->app->user->dept) ? $this->loadModel('dept')->getDeptUserPairs($this->app->user->dept, 'id') : '';

        /* 通过action获取对象名称，所属项目以及需求。 */
        /* Get object names, object projects and requirements by actions. */
        $relatedData     = $this->getRelatedDataByActions($actions);
        $objectNames     = $relatedData['objectNames'];
        $relatedProjects = $relatedData['relatedProjects'];
        $requirements    = $relatedData['requirements'];

        $projectIdList = array();
        foreach($relatedProjects as $objectType => $idList) $projectIdList += $idList;

        /* 获取需要验证的元素列表。 */
        /* Get the list of elements that need to be verified. */
        $shadowProducts   = $this->dao->select('id')->from(TABLE_PRODUCT)->where('shadow')->eq(1)->fetchPairs();
        $projectMultiples = $this->dao->select('id,type,multiple')->from(TABLE_PROJECT)->where('id')->in($projectIdList)->fetchAll('id');
        $docList          = $this->loadModel('doc')->getPrivDocs('', 0, 'all');
        $apiList          = $this->loadModel('api')->getPrivApis();
        $docLibList       = $this->doc->getLibs('hasApi');

        foreach($actions as $i => $action)
        {
            /* 如果doc,api,doclib,product类型对应的对象不存在，则从actions中删除。*/
            /* If the object corresponding to the doc, api, doclib, and product types does not exist, it will be deleted from actions. */
            if($action->objectType == 'doc' && !isset($docList[$action->objectID])) unset($actions[$i]);
            if($action->objectType == 'api' && !isset($apiList[$action->objectID])) unset($actions[$i]);
            if($action->objectType == 'doclib' && !isset($docLibList[$action->objectID])) unset($actions[$i]);
            if($action->objectType == 'product' && isset($shadowProducts[$action->objectID]))
            {
                unset($actions[$i]);
                continue;
            }
    
            /* 为action添加objectName属性。 */
            /* Add objectName field to the action. */
            $action->objectName = isset($objectNames[$action->objectType][$action->objectID]) ? $objectNames[$action->objectType][$action->objectID] : '';

            if($action->objectType == 'program' && strpos('syncexecution,syncproject,syncprogram', $action->action) !== false)
            {
                $action->objectName .= $this->lang->action->label->startProgram;
            }
            elseif($action->objectType == 'branch' && $action->action == 'mergedbranch')
            {
                if($action->objectID == 0) $action->objectName = $this->lang->branch->main;
                $action->objectName = '"' . $action->extra . ' "' . $this->lang->action->to . ' "' . $action->objectName . '"';
            }
            elseif($action->objectType == 'user')
            {
                $user = $this->dao->select('id,realname')->from(TABLE_USER)->where('id')->eq($action->objectID)->fetch();
                if($user) $action->objectName = $user->realname;
            }
            elseif($action->objectType == 'kanbancard' && strpos($action->action, 'imported') !== false && $action->action != 'importedcard')
            {
                $objectType  = str_replace('imported', '', $action->action);
                $objectTable = zget($this->config->objectTables, $objectType);
                $objectName  = ($objectType == 'productplan' || $objectType == 'ticket') ? 'title' : 'name';
                $action->objectName = $this->dao->select($objectName)->from($objectTable)->where('id')->eq($action->extra)->fetch($objectName);
            }
            elseif(strpos(',module,chartgroup,', ",$action->objectType,") !== false && !empty($action->extra) && $action->action != 'deleted')
            {
                $modules = $this->dao->select('id,name')->from(TABLE_MODULE)->where('id')->in(explode(',', $action->extra))->fetchPairs('id');
                $action->objectName = implode(',', $modules);
            }
            elseif($action->objectType == 'mr' && $action->action == 'deleted')
            {
                $action->objectName = $action->extra;
            }
            elseif($action->objectType == 'pivot')
            {
                $pivotNames = json_decode($action->objectName, true);
                $action->objectName = zget($pivotNames, $this->app->getClientLang(), '');
                if(empty($action->objectName))
                {
                    $pivotNames = array_filter($pivotNames);
                    $action->objectName = reset($pivotNames);
                }
            }

            $projectID = isset($relatedProjects[$action->objectType][$action->objectID]) ? $relatedProjects[$action->objectType][$action->objectID] : 0;

            $actionType = strtolower($action->action);
            $objectType = strtolower($action->objectType);

            $action->originalDate = $action->date;
            $action->date         = date(DT_MONTHTIME2, strtotime($action->date));
            $action->actionLabel  = isset($this->lang->$objectType->$actionType) ? $this->lang->$objectType->$actionType : $action->action;
            $action->actionLabel  = isset($this->lang->action->label->$actionType) ? $this->lang->action->label->$actionType : $action->actionLabel;
            $action->objectLabel  = $this->getObjectLabel($objectType, $action->objectID, $actionType, $requirements);
            
            /* 如果action的类型为login或者logout，则不需要链接。*/
            /* If action type is login or logout, needn't link. */
            if($actionType == 'svncommited' || $actionType == 'gitcommited') $action->actor = zget($commiters, $action->actor);

            /* 获取gitlab,gitea,或者gogs的对象名称。 */
            /* Get gitlab, gitea or gogs objectname. */
            if(empty($action->objectName) && (substr($objectType, 0, 6) == 'gitlab' || substr($objectType, 0, 5) == 'gitea' || substr($objectType, 0, 4) == 'gogs')) $action->objectName = $action->extra;

            /* 其它类型的action，设置action的objectLink属性。 */
            /* For other types of actions, set the objectLink attribute of the action. */
            $this->setObjectLink($action, $deptUsers, $shadowProducts, zget($projectMultiples, $projectID, ''));

            /* 设置合并请求的objectLink属性。 */
            /* Set merge request objectLink. */
            if((empty($action->objectName) || $action->action == 'deleted') && $action->objectType == 'mr') $action->objectLink = '';

            $action->major = (isset($this->config->action->majorList[$action->objectType]) && in_array($action->action, $this->config->action->majorList[$action->objectType])) ? 1 : 0;
        }
        return $actions;
    }

    /**
     * Get related data by actions.
     *
     * @param  array    $actions
     * @access public
     * @return array
     */
    public function getRelatedDataByActions($actions)
    {
        $this->loadModel('user');

        $objectNames     = array();
        $relatedProjects = array();
        $requirements    = array();
        $objectTypes     = array();

        foreach($actions as $object) $objectTypes[$object->objectType][$object->objectID] = $object->objectID;
        foreach($objectTypes as $objectType => $objectIdList)
        {
            if(!isset($this->config->objectTables[$objectType]) and $objectType != 'makeup') continue;    // If no defination for this type, omit it.

            $table = $objectType == 'makeup' ? '`' . $this->config->db->prefix . 'overtime`' : $this->config->objectTables[$objectType];
            $field = zget($this->config->action->objectNameFields, $objectType, '');
            if(empty($field)) continue;

            if($table != TABLE_TODO)
            {
                $objectName     = array();
                $relatedProject = array();
                if(strpos(",{$this->config->action->needGetProjectType},", ",{$objectType},") !== false)
                {
                    $objectInfo = $this->dao->select("id, project, $field AS name")->from($table)->where('id')->in($objectIdList)->fetchAll();
                    if($objectType == 'gapanalysis') $users = $this->user->getPairs('noletter');
                    foreach($objectInfo as $object)
                    {
                        $objectName[$object->id]     = $objectType == 'gapanalysis' ? zget($users, $object->name) : $object->name;
                        $relatedProject[$object->id] = $object->project;
                    }
                }
                elseif($objectType == 'project' or $objectType == 'execution')
                {
                    $objectInfo = $this->dao->select("id, project, $field AS name")->from($table)->where('id')->in($objectIdList)->fetchAll();
                    foreach($objectInfo as $object)
                    {
                        $objectName[$object->id]     = $object->name;
                        $relatedProject[$object->id] = $object->project > 0 ? $object->project : $object->id;
                    }
                }
                elseif($objectType == 'story')
                {
                    $objectInfo = $this->dao->select('id,title,type')->from($table)->where('id')->in($objectIdList)->fetchAll();
                    foreach($objectInfo as $object)
                    {
                        $objectName[$object->id] = $object->title;
                        if($object->type == 'requirement') $requirements[$object->id] = $object->id;
                    }
                }
                elseif($objectType == 'reviewcl')
                {
                    $objectInfo = $this->dao->select('id,title')->from($table)->where('id')->in($objectIdList)->fetchAll();
                    foreach($objectInfo as $object) $objectName[$object->id] = $object->title;
                }
                elseif($objectType == 'team')
                {
                    $objectInfo = $this->dao->select('id,team,type')->from(TABLE_PROJECT)->where('id')->in($objectIdList)->fetchAll();
                    foreach($objectInfo as $object)
                    {
                        $objectName[$object->id] = $object->team;
                        if($object->type == 'project') $relatedProject[$object->id] = $object->id;
                    }
                }
                elseif($objectType == 'stakeholder')
                {
                    $objectName = $this->dao->select("t1.id, t2.realname")->from($table)->alias('t1')
                        ->leftJoin(TABLE_USER)->alias('t2')->on("t1.{$field} = t2.account")
                        ->where('t1.id')->in($objectIdList)
                        ->fetchPairs();
                }
                elseif($objectType == 'branch')
                {
                    $this->app->loadLang('branch');
                    $objectName = $this->dao->select("id,name")->from(TABLE_BRANCH)->where('id')->in($objectIdList)->fetchPairs();
                    if(in_array(BRANCH_MAIN, $objectIdList)) $objectName[BRANCH_MAIN] = $this->lang->branch->main;
                }
                elseif($objectType == 'privlang')
                {
                    $objectName = $this->dao->select("objectID AS id, $field AS name")->from($table)->where('objectID')->in($objectIdList)->andWhere('objectType')->eq('priv')->fetchPairs();
                }
                else
                {
                    $objectName = $this->dao->select("id, $field AS name")->from($table)->where('id')->in($objectIdList)->fetchPairs();
                }

                $objectNames[$objectType]     = $objectName;
                $relatedProjects[$objectType] = $relatedProject;
            }
            else
            {
                $todos = $this->dao->select("id, $field AS name, account, private, type, objectID")->from($table)->where('id')->in($objectIdList)->fetchAll('id');
                foreach($todos as $id => $todo)
                {
                    if($todo->type == 'task') $todo->name = $this->dao->findById($todo->objectID)->from(TABLE_TASK)->fetch('name');
                    if($todo->type == 'bug')  $todo->name = $this->dao->findById($todo->objectID)->from(TABLE_BUG)->fetch('title');

                    $objectNames[$objectType][$id] = $todo->name;
                    if($todo->private == 1 and $todo->account != $this->app->user->account) $objectNames[$objectType][$id] = $this->lang->todo->thisIsPrivate;
                }
            }
        }
        $objectNames['user'][0] = 'guest';    // Add guest account.

        $relatedData['objectNames']     = $objectNames;
        $relatedData['relatedProjects'] = $relatedProjects;
        $relatedData['requirements']    = $requirements;
        return $relatedData;
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
        if(isset($this->lang->action->label->$objectType))
        {
            $objectLabel = $this->lang->action->label->$objectType;

            /* 用户故事替换为需求。 */
            /* Replace story to requirement. */
            if(isset($requirements[$objectID]) && is_string($objectLabel)) $objectLabel = str_replace($this->lang->SRCommon, $this->lang->URCommon, $objectLabel);

            if(!is_array($objectLabel)) $actionObjectLabel = $objectLabel;
            if(is_array($objectLabel) && isset($objectLabel[$actionType])) $actionObjectLabel = $objectLabel[$actionType];

            if($objectType == 'module' && $actionType == 'deleted')
            {
                $moduleType = $this->dao->select('type')->from(TABLE_MODULE)->where('id')->eq($objectID)->fetch('type');
                if($moduleType == 'doc')
                {
                    $this->app->loadLang('doc');
                    $actionObjectLabel = $this->lang->doc->menuTitle;
                }
            }
        }

        if($this->config->edition == 'max' && $objectType == 'assetlib')
        {
            $libType = $this->dao->select('type')->from(TABLE_ASSETLIB)->where('id')->eq($objectID)->fetch('type');
            if(strpos('story,issue,risk,opportunity,practice,component', $libType) !== false) $actionObjectLabel = $this->lang->action->label->{$libType . 'assetlib'};
        }

        return $actionObjectLabel;
    }

    /**
     * 设置对象的链接。
     * Set objectLink
     *
     * @param  object   $action
     * @param  array    $deptUsers
     * @param  array    $shadowProducts
     * @param  object   $project
     * @access public
     * @return object|bool
     */
    public function setObjectLink($action, $deptUsers, $shadowProducts, $project = null)
    {
        $this->app->loadConfig('doc');

        $action->objectLink  = '';
        $action->objectLabel = zget($this->lang->action->objectTypes, $action->objectLabel);

        if(strpos($action->objectLabel, '|') !== false)
        {
            list($objectLabel, $moduleName, $methodName, $vars) = explode('|', $action->objectLabel);

            /* Fix bug #2961. */
            $isLoginOrLogout = $action->objectType == 'user' and ($action->action == 'login' or $action->action == 'logout');

            $action->objectLabel = $objectLabel;
            $action->product     = trim($action->product, ',');

            $noLinkObjects = array('program', 'project', 'product', 'execution');
            if(in_array($action->objectType, $noLinkObjects))
            {
                $objectTable   = zget($this->config->objectTables, $action->objectType);
                $objectDeleted = $this->dao->select('deleted')->from($objectTable)->where('id')->eq($action->objectID)->fetch('deleted');
                if($objectDeleted) return $action;
            }

            if($this->config->edition == 'max'
               and strpos($this->config->action->assetType, ",{$action->objectType},") !== false
               and empty($action->project) and empty($action->product) and empty($action->execution))
            {
                if($action->objectType == 'doc')
                {
                    $assetLibType = $this->dao->select('assetLibType')->from(TABLE_DOC)->where('id')->eq($action->objectID)->fetch('assetLibType');
                    if($assetLibType) $method = $assetLibType == 'practice' ? 'practiceView' : 'componentView';
                }
                else
                {
                    $method = $this->config->action->assetViewMethod[$action->objectType];
                }

                $action->objectLink = helper::createLink($moduleName, $methodName, sprintf($vars, $action->objectID));
                if(isset($method)) $action->objectLink = helper::createLink('assetlib', $method, sprintf($vars, $action->objectID));
            }
            else
            {
                if($action->objectType == 'doclib')
                {
                    $libID = $action->objectID;
                    $type  = 'custom';
                    if(!empty($action->project))   $type = 'project';
                    if(!empty($action->execution)) $type = 'execution';
                    if(!empty($action->product))   $type = 'product';

                    $libObjectID = $type != 'custom' ? $action->$type : '';
                    $libObjectID = trim($libObjectID, ',');
                    if(empty($libObjectID) and $type != 'custom') return false;

                    $params = sprintf($vars, $type, $libObjectID, $libID);
                }
                elseif($action->objectType == 'api')
                {
                    $api = $this->dao->select('id,lib,module')->from(TABLE_API)->where('id')->eq($action->objectID)->fetch();
                    $params = sprintf($vars, $api->lib, $api->id, $api->module);
                }
                elseif($action->objectType == 'branch')
                {
                    $params = sprintf($vars, trim($action->product, ','));
                }
                elseif($action->objectType == 'kanbanspace')
                {
                    $kanbanSpace = $this->dao->select('type')->from(TABLE_KANBANSPACE)->where('id')->eq($action->objectID)->fetch();
                    $params = sprintf($vars, $kanbanSpace->type);
                }
                elseif($action->objectType == 'kanbancolumn' or $action->objectType == 'kanbanlane')
                {
                    $params = sprintf($vars, $action->extra);
                }
                elseif($action->objectType == 'module' and $action->action == 'deleted')
                {
                    $params = sprintf($vars, trim($action->product, ','));
                }
                else
                {
                    $params = sprintf($vars, $action->objectID);
                }
                $action->objectLink = helper::createLink($moduleName, $methodName, $params);

                if($action->objectType == 'execution')
                {
                    $execution = $this->loadModel('execution')->getById($action->objectID);
                    if(!empty($execution) and $execution->type == 'kanban') $action->objectLink = helper::createLink('execution', 'kanban', "executionID={$action->objectID}");
                }

                if($action->objectType == 'story')
                {
                    $story = $this->loadModel('story')->getByID($action->objectID);
                    if(!empty($story))
                    {
                        $moduleName = $story->type;
                        $action->objectLink = isset($shadowProducts[$story->product]) ? helper::createLink('projectstory', 'view', "storyID=$story->id") : helper::createLink('story', 'view', "id=$story->id&version=0&param=0&storyType=$story->type");
                    }
                }

                if($action->objectType == 'doclib')
                {
                    $docLib             = $this->dao->select('type,product,project,execution,deleted')->from(TABLE_DOCLIB)->where('id')->eq($action->objectID)->fetch();
                    $docLib->objectID   = strpos('product,project,execution', $docLib->type) !== false ? $docLib->{$docLib->type} : 0;
                    $appendLib          = $docLib->deleted == '1' ? $action->objectID : 0;
                    if($docLib->type == 'api')
                    {
                        $module = 'api';
                        $method = 'index';
                        $params = "libID={$action->objectID}&moduleID=0&apiID=0&version=0&release=0&appendLib={$appendLib}";
                        if(!empty($docLib->project) or !empty($docLib->product))
                        {
                            $module = 'doc';
                            if(!empty($docLib->product))
                            {
                                $objectID = $docLib->product;
                                $method   = 'productspace';
                            }

                            if(!empty($docLib->project))
                            {
                                $objectID = $docLib->project;
                                $method   = 'projectspace';
                            }
                            $params = "objectID={$objectID}&libID={$action->objectID}";
                        }
                        $action->objectLink = helper::createLink($module, $method, $params);
                    }
                    else
                    {
                        $method = 'tablecontents';
                        if(isset($this->config->doc->spaceMethod[$docLib->type])) $method = $this->config->doc->spaceMethod[$docLib->type];
                        if($method == 'myspace') $params = "type=mine&libID={$action->objectID}";
                        if(!in_array($method, array('myspace', 'tablecontents'))) $params = "objectID={$docLib->objectID}&libID={$action->objectID}";
                        $action->objectLink = helper::createLink('doc', $method, $params);
                    }
                }
                elseif($action->objectType == 'user')
                {
                    $action->objectLink = !isset($deptUsers[$action->objectID]) ? 'javascript:void(0)' : helper::createLink($moduleName, $methodName, sprintf($vars, $action->objectID));
                }
            }
            if(!common::hasPriv($moduleName, $methodName) and !$isLoginOrLogout) $action->objectLink = '';
        }
        elseif($action->objectType == 'team')
        {
            if($action->project)   $action->objectLink = common::hasPriv('project', 'team')   ? helper::createLink('project',   'team', 'projectID=' . $action->project) : '';
            if($action->execution) $action->objectLink = common::hasPriv('execution', 'team') ? helper::createLink('execution', 'team', 'executionID=' . $action->execution) : '';
        }
        elseif($action->objectType == 'privpackage')
        {
            $action->objectLink = '';
        }
        elseif($action->objectType == 'privlang')
        {
            $action->objectLink = '';
        }

        if($action->objectType == 'stakeholder' and $action->project == 0) $action->objectLink = '';

        if($action->objectType == 'story' and $action->action == 'import2storylib') $action->objectLink = helper::createLink('assetlib', 'storyView', "storyID=$action->objectID");
        if($action->objectType == 'story' and $this->config->vision == 'lite') $action->objectLink = helper::createLink('projectstory', 'view', "storyID=$action->objectID");

        if(strpos(',kanbanregion,kanbancard,', ",{$action->objectType},") !== false)
        {
            $table    = $this->config->objectTables[$action->objectType];
            $kanbanID = $this->dao->select('kanban')->from($table)->where('id')->eq($action->objectID)->fetch('kanban');

            $action->objectLink = helper::createLink('kanban', 'view', "kanbanID=$kanbanID");
        }

        if(strpos(',kanbanlane,kanbancolumn,', ",{$action->objectType},") !== false and empty($action->extra))
        {
            $table    = $this->config->objectTables[$action->objectType];
            $kanbanID = $this->dao->select('t2.kanban')->from($table)->alias('t1')
                ->leftJoin(TABLE_KANBANREGION)->alias('t2')->on('t1.region=t2.id')
                ->where('t1.id')->eq($action->objectID)
                ->fetch('kanban');

            $action->objectLink = helper::createLink('kanban', 'view', "kanbanID=$kanbanID");
        }

        if($action->objectType == 'chartgroup') $action->objectLink = '';
        if($action->objectType == 'branch' and $action->action == 'mergedbranch') $action->objectLink = 'javascript:void(0)';
        if($action->objectType == 'module')
        {
            $moduleType = $this->dao->select('type')->from(TABLE_MODULE)->where('id')->eq($action->objectID)->fetch('type');
            if($moduleType == 'doc')
            {
                $this->app->loadLang('doc');
                $action->objectLabel = $this->lang->doc->menuTitle;
            }
        }

        if($action->objectType == 'review') $action->objectLink = helper::createLink('review', 'view', "reviewID=$action->objectID");

        /* Set app for no multiple project. */
        if(!empty($action->objectLink) and !empty($project) and empty($project->multiple)) $action->objectLink .= '#app=project';
        if($this->config->vision == 'lite' and $action->objectType == 'module') $action->objectLink .= '#app=project';

        return $action;
    }

    /**
     * 根据给定的一段时间的参数计算日期的开始和结束。
     * Compute the begin date and end date of a period.
     *
     * @param  string    $period   all|today|yesterday|twodaysago|latest2days|thisweek|lastweek|thismonth|lastmonth
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

        /* If the period is by week, add the end time to the end date. */
        if($period == 'thisweek' or $period == 'lastweek')
        {
            $func = "get$period";
            extract(date::$func());
            return array('begin' => $begin, 'end' => $end . ' 23:59:59');
        }

        if($period == 'thismonth')  return date::getThisMonth();
        if($period == 'lastmonth')  return date::getLastMonth();

        return array('begin' => EPOCH_DATE,  'end' => FUTURE_DATE);
    }

    /**
     * Print changes of every action.
     *
     * @param  string    $objectType
     * @param  array     $histories
     * @param  bool      $canChangeTag
     * @access public
     * @return void
     */
    public function renderChanges($objectType, $histories, $canChangeTag = true)
    {
        if(empty($histories)) return;

        $maxLength            = 0;          // The max length of fields names.
        $historiesWithDiff    = array();    // To save histories without diff info.
        $historiesWithoutDiff = array();    // To save histories with diff info.

        /* Diff histories by hasing diff info or not. Thus we can to make sure the field with diff show at last. */
        foreach($histories as $history)
        {
            $fieldName = $history->field;
            $history->fieldLabel = (isset($this->lang->$objectType) && isset($this->lang->$objectType->$fieldName)) ? $this->lang->$objectType->$fieldName : $fieldName;
            if($objectType == 'module') $history->fieldLabel = $this->lang->tree->$fieldName;
            if($fieldName == 'fileName') $history->fieldLabel = $this->lang->file->$fieldName;
            if(($length = strlen($history->fieldLabel)) > $maxLength) $maxLength = $length;
            $history->diff ? $historiesWithDiff[] = $history : $historiesWithoutDiff[] = $history;
        }
        $histories = array_merge($historiesWithoutDiff, $historiesWithDiff);

        $content = '';

        foreach($histories as $history)
        {
            $history->fieldLabel = str_pad($history->fieldLabel, $maxLength, $this->lang->action->label->space);
            if($history->diff != '')
            {
                $history->diff      = str_replace(array('<ins>', '</ins>', '<del>', '</del>'), array('[ins]', '[/ins]', '[del]', '[/del]'), $history->diff);
                $history->diff      = ($history->field != 'subversion' && $history->field != 'git') ? htmlSpecialString($history->diff) : $history->diff;   // Keep the diff link.
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
     * Print changes of every action.
     *
     * @param  string    $objectType
     * @param  array     $histories
     * @param  bool      $canChangeTag
     * @access public
     * @return void
     */
    public function printChanges($objectType, $histories, $canChangeTag = true)
    {
        $content = $this->renderChanges($objectType, $histories, $canChangeTag);
        if(is_string($content)) echo $content;
    }

    /**
     * Delete action by objectType.
     *
     * @param  string $objectType
     * @access public
     * @return void
     */
    public function deleteByType($objectType)
    {
        $this->dao->delete()->from(TABLE_ACTION)->where('objectType')->eq($objectType)->exec();
    }

    /**
     * Undelete a record.
     *
     * @param  int      $actionID
     * @access public
     * @return string|bool
     */
    public function undelete(int $actionID): string|bool
    {
        $action = $this->getById($actionID);
        if($action->action != 'deleted') return true;

        $table = $this->config->objectTables[$action->objectType];

        $orderby = '';
        $field   = '*';
        switch($action->objectType)
        {
            case 'product':
                $field   = 'id, name, code, acl';
                break;
            case 'program':
            case 'project':
            case 'execution':
                $field   = 'id, acl, name, hasProduct';
                break;
            case 'doc':
                $orderby = 'version desc';
            default:
                break;
        }

        $object = $this->actionTao->getObjectBaseInfo($table, array('id' => $action->objectID), $field, $orderby);

        if($action->objectType == 'execution')
        {
            if($object->deleted && empty($object->project)) return $this->lang->action->undeletedTips;

            $projectCount = $this->dao->select('count(*) AS count')->from(TABLE_PROJECT)->where('id')->eq($object->project)->andWhere('deleted')->eq('0')->fetch('count');
            if((int)$projectCount == 0) return $this->lang->action->executionNoProject;
        }

        if($action->objectType == 'repo' && in_array($object->SCM, array('Gitlab', 'Gitea', 'Gogs')))
        {
            $server = $this->dao->select('*')->from(TABLE_PIPELINE)->where('id')->eq($object->serviceHost)->andWhere('deleted')->eq('0')->fetch();
            if(empty($server)) return $this->lang->action->repoNoServer;
        }

        if(in_array($action->objectType, array('program', 'project', 'execution', 'product')))
        {
            $objectType = $action->objectType == 'execution' ? 'sprint' : $action->objectType;
            if($object->acl != 'open') $this->loadModel('user')->updateUserView($object->id, $objectType);

            /* 恢复隐藏产品。 */
            /* Resotre hidden products. */
            if($action->objectType == 'project' && !$object->hasProduct)
            {
                $productID = $this->loadModel('product')->getProductIDByProject($object->id);;
                $this->dao->update(TABLE_PRODUCT)
                    ->set('name')->eq($object->name)
                    ->set('deleted')->eq(0)
                    ->where('id')->eq($productID)
                    ->exec();
            }
        }

        if($action->objectType == 'module')
        {
            $repeatName = $this->loadModel('tree')->checkUnique($object);
            if($repeatName) return sprintf($this->lang->tree->repeatName, $repeatName);
        }

        if($action->objectType == 'reviewissue' && $object->parent)
        {
            $review = $this->dao->select('*')->from(TABLE_REVIEW)->where('id')->eq($object->review)->fetch();
            if($review->deleted)
            {
                $this->app->loadLang('reviewissue');
                return $this->lang->reviewissue->undeleteAction;
            }
        }

        if($action->objectType == 'release' && $object->shadow) $this->dao->update(TABLE_BUILD)->set('deleted')->eq(0)->where('id')->eq($object->shadow)->exec();

        if($action->objectType == 'case' && $object->parent)
        {
            $scene = $this->dao->select('*')->from(VIEW_SCENECASE)->where('id')->eq($object->scene)->fetch();
            if($scene->deleted) return $this->lang->action->refusecase;
        }

        if($action->objectType == 'scene' && $object->parent)
        {
            $scenerow = $this->dao->select('*')->from(VIEW_SCENECASE)->where('id')->eq($object->parent)->fetch();
            if($scenerow->deleted) return $this->lang->action->refusescene;
        }

        if($action->objectType == 'doc' && $object->files) $this->dao->update(TABLE_FILE)->set('deleted')->eq('0')->where('id')->in($object->files)->exec();

        /* 恢复被删除的元素。 */
        /* Resotre deleted object. */
        $this->dao->update($table)->set('deleted')->eq(0)->where('id')->eq($action->objectID)->exec();

        /* 当还原项目或者执行的时候恢复用户的产品权限。 */
        /* Revert userView products when undelete project or execution. */
        if($action->objectType == 'project' || $action->objectType == 'execution')
        {
            $this->loadModel('product');
            $products = $this->product->getProducts($object->id, 'all', '', false);
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

        /* 恢复产品计划的父级状态。 */
        /* Revert productplan parent status. */
        if($action->objectType == 'productplan') $this->loadModel('productplan')->changeParentField($action->objectID);

        /* 还原子任务的时候更新任务状态。 */
        /* Update task status when undelete child task. */
        if($action->objectType == 'task') $this->loadModel('task')->updateParentStatus($action->objectID);

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
        if($action->action != 'deleted') return false;

        $this->dao->update(TABLE_ACTION)->set('extra')->eq(self::BE_HIDDEN)->where('id')->eq($actionID)->exec();
        $this->create($action->objectType, $action->objectID, 'hidden');

        return dao::isError();
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

        return dao::isError();
    }

    /**
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
     * @param  string $type all|today|yesterday|thisweek|lastweek|thismonth|lastmonth
     * @param  string $orderBy date_desc|date_asc
     * @access public
     * @return array
     */
    public function buildDateGroup($actions, $direction = 'next', $type = 'today', $orderBy = 'date_desc')
    {
        $dateGroup = array();
        foreach($actions as $action)
        {
            $timeStamp    = strtotime(isset($action->originalDate) ? $action->originalDate : $action->date);
            $date         = date(DT_DATE3, $timeStamp);
            $action->time = date(DT_TIME2, $timeStamp);
            $dateGroup[$date][] = $action;
        }

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

        /* Modify date to the corrret order. */
        if($this->app->rawModule != 'company' and $direction != 'next')
        {
            $dateGroup = array_reverse($dateGroup);
        }
        elseif($this->app->rawModule == 'company')
        {
            if($direction == 'pre') $dateGroup = array_reverse($dateGroup);
            if(($direction == 'next' and $orderBy == 'date_asc') or ($direction == 'pre' and $orderBy == 'date_desc'))
            {
                foreach($dateGroup as $key => $dateItem) $dateGroup[$key] = array_reverse($dateItem);
            }
        }
        return $dateGroup;
    }

    /**
     * Check Has pre or next.
     *
     * @param  string $date
     * @param  string $direction
     * @access public
     * @return bool
     */
    public function hasPreOrNext($date, $direction = 'next')
    {
        $condition = $this->session->actionQueryCondition;

        /* Remove date condition for direction. */
        $condition = preg_replace("/AND +date[\<\>]'\d{4}\-\d{2}\-\d{2}'/", '', $condition);
        $count     = $this->dao->select('count(*) as count')->from(TABLE_ACTION)->where($condition)
            ->andWhere('date' . ($direction == 'next' ? '<' : '>') . "'{$date}'")
            ->fetch('count');
        return $count > 0;
    }

    /**
     * Save global search object index information.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @param  string $actionType
     * @access public
     * @return bool
     */
    public function saveIndex($objectType, $objectID, $actionType)
    {
        $this->loadModel('search');
        $actionType = strtolower($actionType);
        if(!isset($this->config->search->fields->$objectType)) return true;
        if(strpos($this->config->search->buildAction, ",{$actionType},") === false and empty($_POST['comment'])) return true;
        if($actionType == 'deleted' or $actionType == 'erased') return $this->search->deleteIndex($objectType, $objectID);

        $field = $this->config->search->fields->$objectType;
        $query = $this->search->buildIndexQuery($objectType, $testDeleted = false);
        $data  = $query->andWhere('t1.' . $field->id)->eq($objectID)->fetch();
        if(empty($data)) return true;

        $data->comment = '';
        if($objectType == 'effort' and $data->objectType == 'task') return true;
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
    }

    /**
     * Print actions of an object for API(JIHU).
     *
     * @param  object    $action
     * @access public
     * @return void
     */
    public function printActionForGitLab($action)
    {
        if(!isset($action->objectType) or !isset($action->action)) return false;

        $objectType = $action->objectType;
        $actionType = strtolower($action->action);

        if(isset($this->lang->action->apiTitle->$actionType) and isset($action->extra))
        {
            /* If extra column is a username, then assemble link to that. */
            if($action->action == "assigned")
            {
                $userDetails = $this->loadModel('user')->getUserDetailsForAPI($action->extra);
                if(isset($userDetails[$action->extra]))
                {
                    $userDetail    = $userDetails[$action->extra];
                    $action->extra = "<a href='{$userDetail->url}' target='_blank'>{$action->extra}</a>";
                }
            }

            echo sprintf($this->lang->action->apiTitle->$actionType, $action->extra);
        }
        elseif(isset($this->lang->action->apiTitle->$actionType) and !isset($action->extra))
        {
            echo $this->lang->action->apiTitle->$actionType;
        }
        else
        {
            echo $actionType;
        }
    }

    /**
     * Process action for API.
     *
     * @param  array  $actions
     * @param  array  $users
     * @param  array  $objectLang
     * @access public
     * @return array
     */
    public function processActionForAPI($actions, $users = array(), $objectLang = array())
    {
        $actions = (array)$actions;
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
                    $history->fieldName = zget($objectLang, $history->field);
                    $action->history[$i] = $history;
                }
            }
        }
        return array_values($actions);
    }

    /**
     * Process dynamic for API.
     *
     * @param  array    $dynamics
     * @access public
     * @return array
     */
    public function processDynamicForAPI($dynamics)
    {
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
        foreach($dynamics as $key => $dynamic)
        {
            if($dynamic->objectType == 'user') continue;

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
     * Build search form.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return void
     */
    public function buildTrashSearchForm(int $queryID, string $actionURL)
    {
        $this->config->trash->search['actionURL'] = $actionURL;
        $this->config->trash->search['queryID']   = $queryID;

        $this->loadModel('search')->setSearchParams($this->config->trash->search);
    }

    /**
     * Restore stages.
     *
     * @param  array  $stageList
     * @access public
     * @return void
     */
    public function restoreStages($stageList)
    {
        $deletedActions = $this->dao->select('*')->from(TABLE_ACTION)
            ->where('objectID')->in(array_keys($stageList))
            ->andWhere('objectType')->eq('execution')
            ->andWhere('action')->eq('deleted')
            ->orderBy('id_desc')
            ->fetchGroup('objectID');

        foreach($stageList as $stageID => $stage)
        {
            $deletedAction = $deletedActions[$stageID][0];
            $this->dao->update(TABLE_EXECUTION)->set('deleted')->eq('0')->where('id')->eq($stageID)->exec();
            $this->dao->update(TABLE_ACTION)->set('extra')->eq(actionModel::BE_UNDELETED)->where('id')->eq($deletedAction->id)->exec();
            $this->create($deletedAction->objectType, $deletedAction->objectID, 'undeleted');
        }
    }

    /**
     * 获取属性相同的对象。
     * Get repeat object.
     *
     * @param  object $action
     * @access public
     * @return object|bool
     */
    public function getRepeatObject(object $action, object &$object): object|bool
    {
        if($action->objectType == 'product')
        {
            $object       = $this->dao->select('*')->from(TABLE_PRODUCT)->where('id')->eq($action->objectID)->fetch();
            $programID    = isset($object->program) ?? 0;
            $repeatObject = $this->dao->select('*')->from(TABLE_PRODUCT)
                ->where('id')->ne($action->objectID)
                ->andWhere("(name = '{$product->name}' and program = {$programID})", true)
                ->beginIF($product->code)->orWhere("code = '{$product->code}'")->fi()
                ->markRight(1)
                ->andWhere('deleted')->eq('0')
                ->fetch();
        }
        else
        {
            $object        = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($action->objectID)->fetch();
            $sprintProject = isset($object->project) ?? 0;
            $repeatObject  = $this->dao->select('*')->from(TABLE_PROJECT)
                ->where('id')->ne($action->objectID)
                ->beginIF($action->objectType == 'program' || $action->objectType == 'project')->andWhere("(name = '{$object->name}' and parent = {$object->parent})", true)->fi()
                ->beginIF($action->objectType == 'execution')->andWhere("(name = '{$object->name}' and project = {$sprintProject})", true)->fi()
                ->beginIF($action->objectType == 'project' && $object->code)->orWhere("(code = '{$object->code}' and model = '$object->model')")->fi()
                ->beginIF($action->objectType == 'execution' && $object->code)->orWhere("code = '{$object->code}'")->fi()
                ->markRight(1)
                ->beginIF($action->objectType == 'program')->andWhere('type')->eq('program')->fi()
                ->beginIF($action->objectType == 'project')->andWhere('type')->eq('project')->fi()
                ->beginIF($action->objectType == 'execution')->andWhere('type')->in('sprint,stage,kanban')->fi()
                ->andWhere('deleted')->eq('0')
                ->fetch();
        }
        return $repeatObject;
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
     * @return void
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
     * @param  int $id
     * @param  array $params
     * @access public
     * @return void
     */
    public function updateObjectByID(string $table, int $id, array $params)
    {
        $updateParams = array();
        foreach($params as $key => $value) $updateParams[] = '`' . $key . '`' . '="' . $value . '"';
        $this->dao->update($table)->set(implode(',', $updateParams))->where('id')->eq($id)->exec();
    }

    /**
     * 根据执行id获取attribute属性。
     * Get attribute by execution id.
     *
     * @param  int    $executionID
     * @access public
     * @return string
     */
    public function getAttributeByExecutionID(int $executionID): string
    {
        return $this->actionTao->getAttributeByID($executionID);
    }

    /**
     * 根据id获取已经删除的阶段。
     * Get deleted stage by ids.
     *
     * @param  array $list
     * @access public
     * @return array
     */
    public function getDeletedStagedByList(array $list): array
    {
        return $this->actionTao->getDeletedStagedList($list);
    }

    /**
     * 更新阶段的attribute属性。
     * Update stage attribute.
     *
     * @param  string $attribute
     * @param  array  $idList
     * @access public
     * @return int
     */
    public function updateStageAttribute(string $attribute, array $stages): int
    {
        return $this->dao->update(TABLE_EXECUTION)->set('attribute')->eq($attribute)->where('id')->in($stages)->exec();
    }
}
