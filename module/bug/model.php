<?php
declare(strict_types=1);
/**
 * The model file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id: model.php 5079 2013-07-10 00:44:34Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<?php
class bugModel extends model
{
    /**
     * bug 的入库操作。
     * Insert bug into zt_bug.
     *
     * @param  object    $bug
     * @param  string    $from
     * @access public
     * @return int|false
     */
    public function create(object $bug, string $from = ''): int|false
    {
        $this->dao->insert(TABLE_BUG)->data($bug, 'laneID')
            ->autoCheck()
            ->checkIF(!empty($bug->notifyEmail), 'notifyEmail', 'email')
            ->batchCheck($this->config->bug->create->requiredFields, 'notempty')
            ->checkFlow()
            ->exec();

        if(dao::isError()) return false;

        $bugID = $this->dao->lastInsertID();

        $action = $from == 'sonarqube' ? 'fromSonarqube' : 'Opened';
        $this->loadModel('action')->create('bug', $bugID, $action);

        /* Add score for create. */
        if(!empty($bug->case))
        {
            $this->loadModel('score')->create('bug', 'createFormCase', $bug->case);
        }
        else
        {
            $this->loadModel('score')->create('bug', 'create', $bugID);
        }

        return $bugID;
    }

    /**
     * Gitlab 问题转为 bug。
     * Create bug from gitlab issue.
     *
     * @param  object    $bug
     * @param  int       $executionID
     * @access public
     * @return int|bool
     */
    public function createBugFromGitlabIssue(object $bug, int $executionID): int|bool
    {
        $bug->openedBy     = $this->app->user->account;
        $bug->openedDate   = helper::now();
        $bug->assignedDate = isset($bug->assignedTo) ? helper::now() : null;
        $bug->openedBuild  = 'trunk';
        $bug->story        = 0;
        $bug->task         = 0;
        $bug->pri          = 3;
        $bug->severity     = 3;
        $bug->project      = $this->dao->select('project')->from(TABLE_EXECUTION)->where('id')->eq($executionID)->fetch('project');

        $this->dao->insert(TABLE_BUG)->data($bug, $skip = 'gitlab,gitlabProject')->autoCheck()->batchCheck($this->config->bug->create->requiredFields, 'notempty')->exec();
        if(!dao::isError()) return $this->dao->lastInsertID();

        return false;
    }

    /**
     * 根据浏览类型获取 bug 列表。
     * Get bug list by browse type.
     *
     * @param  string     $browseType
     * @param  array      $productIdList
     * @param  int        $projectID
     * @param  int[]      $executionIdList
     * @param  int|string $branch
     * @param  int        $moduleID
     * @param  int        $queryID
     * @param  string     $orderBy
     * @param  object     $pager
     * @access public
     * @return array
     */
    public function getList(string $browseType, array $productIdList, int $projectID, array $executionIdList, int|string $branch = 'all', int $moduleID = 0, int $queryID = 0, string $orderBy = 'id_desc', object $pager = null): array
    {
        if($browseType == 'bymodule' && $this->session->bugBrowseType && $this->session->bugBrowseType != 'bysearch') $browseType = $this->session->bugBrowseType;

        if(!in_array($browseType, $this->config->bug->browseTypeList)) return array();

        /* 处理排序。*/
        /* Process sort field. */
        if(strpos($orderBy, 'pri_') !== false)      $orderBy = str_replace('pri_', 'priOrder_', $orderBy);
        if(strpos($orderBy, 'severity_') !== false) $orderBy = str_replace('severity_', 'severityOrder_', $orderBy);

        $modules = $moduleID ? $this->loadModel('tree')->getAllChildID($moduleID) : array();
        $bugList = $this->bugTao->getListByBrowseType($browseType, $productIdList, $projectID, $executionIdList, $branch, $modules, $queryID, $orderBy, $pager);

        return $this->bugTao->batchAppendDelayedDays($bugList);
    }

    /**
     * 获取计划关联的 bugs。
     * Get bug list of a plan.
     *
     * @param  int    $planID
     * @param  string $status
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getPlanBugs(int $planID, string $status = 'all', string $orderBy = 'id_desc', object $pager = null): array
    {
        if(strpos($orderBy, 'pri_') !== false) $orderBy = str_replace('pri_', 'priOrder_', $orderBy);

        $bugs = $this->dao->select("*, IF(`pri` = 0, {$this->config->maxPriValue}, `pri`) AS priOrder")->from(TABLE_BUG)
            ->where('plan')->eq($planID)
            ->beginIF(!$this->app->user->admin)->andWhere('execution')->in('0,' . $this->app->user->view->sprints)->fi()
            ->beginIF($status != 'all')->andWhere('status')->in($status)->fi()
            ->andWhere('deleted')->eq(0)
            ->orderBy($orderBy)->page($pager)
            ->fetchAll('id');

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'bug');

        return $bugs;
    }

    /**
     * 获取 bug。
     * Get info of a bug.
     *
     * @param  int          $bugID
     * @param  bool         $setImgSize
     * @access public
     * @return object|false
     */
    public function getByID(int $bugID, bool $setImgSize = false): object|false
    {
        $bug = $this->bugTao->fetchBugInfo($bugID);
        if(!$bug) return false;

        $bug = $this->loadModel('file')->replaceImgURL($bug, 'steps');
        if($setImgSize) $bug->steps = $this->file->setImgSize($bug->steps);

        if($bug->project)      $bug->projectName       = $this->bugTao->getNameFromTable($bug->project,      TABLE_PROJECT, 'name');
        if($bug->duplicateBug) $bug->duplicateBugTitle = $this->bugTao->getNameFromTable($bug->duplicateBug, TABLE_BUG,     'title');
        if($bug->case)         $bug->caseTitle         = $this->bugTao->getNameFromTable($bug->case,         TABLE_CASE,    'title');
        if($bug->toStory)      $bug->toStoryTitle      = $this->bugTao->getNameFromTable($bug->toStory,      TABLE_STORY,   'title');
        if($bug->toTask)       $bug->toTaskTitle       = $this->bugTao->getNameFromTable($bug->toTask,       TABLE_TASK,    'name');
        if($bug->relatedBug)   $bug->relatedBugTitles  = $this->bugTao->getBugPairsByList($bug->relatedBug);

        $bug->linkMRTitles = $this->loadModel('mr')->getLinkedMRPairs($bugID, 'bug');
        $bug->toCases      = $this->bugTao->getCasesFromBug($bugID);
        $bug->files        = $this->file->getByObject('bug', $bugID);

        return $this->bugTao->appendDelayedDays($bug);
    }

    /**
     * 获取指定字段的 bug 列表。
     * Get bugs by ID list.
     *
     * @param  int|array|string $bugIdList
     * @param  string           $fields
     * @param  string           $orderBy
     * @access public
     * @return array
     */
    public function getByIdList(int|array|string $bugIdList = 0, string $fields = '*', string $orderBy = ''): array
    {
        return $this->dao->select($fields)->from(TABLE_BUG)
            ->where('deleted')->eq('0')
            ->beginIF($bugIdList)->andWhere('id')->in($bugIdList)->fi()
            ->beginIF($orderBy)->orderBy($orderBy)->fi()
            ->fetchAll('id');
    }

    /**
     * 获取激活的未转为 bug 和任务的 bugs。
     * Get active bugs.
     *
     * @param  array|int  $products
     * @param  int|string $branch
     * @param  string     $executions
     * @param  array      $excludeBugs
     * @param  object     $pager
     * @param  string     $orderBy
     * @access public
     * @return array
     */
    public function getActiveBugs(array|int $products, int|string $branch, string $executions, array $excludeBugs, object $pager = null, string $orderBy = 'id desc'): array
    {
        return $this->dao->select('*')->from(TABLE_BUG)
            ->where('status')->eq('active')
            ->andWhere('toStory')->eq(0)
            ->andWhere('toTask')->eq(0)
            ->beginIF(!empty($products))->andWhere('product')->in($products)->fi()
            ->beginIF($branch !== '' and $branch !== 'all')->andWhere('branch')->in("0,$branch")->fi()
            ->beginIF(!empty($executions))->andWhere('execution')->in($executions)->fi()
            ->beginIF($excludeBugs)->andWhere('id')->notIN($excludeBugs)->fi()
            ->andWhere('deleted')->eq(0)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
    }

    /**
     * 获取激活和延期处理的 bug 列表。
     * Get active and postponed bugs.
     *
     * @param  array  $products
     * @param  int    $executionID
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getActiveAndPostponedBugs(array $products, int $executionID, object $pager = null): array
    {
        return $this->dao->select('t1.*')->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')->on('t1.product = t2.product')
            ->where("((t1.status = 'resolved' AND t1.resolution = 'postponed') OR (t1.status = 'active'))")
            ->andWhere('t1.toTask')->eq(0)
            ->andWhere('t1.toStory')->eq(0)
            ->beginIF(!empty($products))->andWhere('t1.product')->in($products)->fi()
            ->beginIF(empty($products))->andWhere('t1.execution')->eq($executionID)->fi()
            ->andWhere('t2.project')->eq($executionID)
            ->andWhere("(t2.branch = '0' OR t1.branch = '0' OR t2.branch = t1.branch)")
            ->andWhere('t1.deleted')->eq('0')
            ->orderBy('id desc')
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * 获取模块的负责人。
     * Get the owner of module.
     *
     * @param  int    $moduleID
     * @param  int    $productID
     * @access public
     * @return array
     */
    public function getModuleOwner(int $moduleID, int $productID): array
    {
        $users = $this->loadModel('user')->getPairs('nodeleted');

        /* 获取所属产品的测试负责人。*/
        /* Return the QD of the product. */
        $account  = $this->dao->findByID($productID)->from(TABLE_PRODUCT)->fetch('QD');
        $account  = isset($users[$account]) ? $account : '';
        $realname = zget($users, $account, '');

        /* 如果没有模块 ID，直接返回测试负责人。*/
        if(!$moduleID) return array($account, $realname);

        /* 获取模块，如果模块为空，直接返回测试负责人。*/
        $module = $this->dao->findByID($moduleID)->from(TABLE_MODULE)->andWhere('root')->eq($productID)->fetch();
        if(empty($module)) return array($account, $realname);

        /* 如果模块有负责人返回模块负责人。*/
        if($module->owner && isset($users[$module->owner])) return array($module->owner, $users[$module->owner]);

        /* 获取除了模块ID以外的模块的路径，如果没有其他路径，返回测试负责人。*/
        $moduleIdList = explode(',', trim(str_replace(",$module->id,", ',', $module->path), ','));
        if(!$moduleIdList) return array($account, $realname);

        /* 从上级到下级，如果有模块有负责人，返回模块负责人。*/
        krsort($moduleIdList);
        $modules = $this->dao->select('*')->from(TABLE_MODULE)->where('id')->in($moduleIdList)->andWhere('deleted')->eq('0')->fetchAll('id');
        foreach($modules as $module)
        {
            if($module->owner && isset($users[$module->owner])) return array($module->owner, $users[$module->owner]);
        }

        return array($account, $realname);
    }

    /**
     * 更新 bug 信息。
     * Update a bug.
     *
     * @param  object      $bug
     * @param  string      $action
     * @access public
     * @return array|false
     */
    public function update(object $bug, string $action = 'Edited'): array|false
    {
        $oldBug = $this->getByID($bug->id);

        $this->dao->update(TABLE_BUG)->data($bug, 'deleteFiles,comment')
            ->autoCheck()
            ->batchCheck($this->config->bug->edit->requiredFields, 'notempty')
            ->checkIF(!empty($bug->resolvedBy), 'resolution',  'notempty')
            ->checkIF(!empty($bug->closedBy),   'resolution',  'notempty')
            ->checkIF(!empty($bug->notifyEmail),'notifyEmail', 'email')
            ->checkIF(!empty($bug->resolution) && $bug->resolution == 'duplicate', 'duplicateBug', 'notempty')
            ->checkIF(!empty($bug->resolution) && $bug->resolution == 'fixed',     'resolvedBuild','notempty')
            ->checkFlow()
            ->where('id')->eq($bug->id)
            ->exec();

        if(dao::isError()) return false;

        $changes = common::createChanges($oldBug, $bug);
        if($changes || !empty($bug->comment))
        {
            $actionID = $this->loadModel('action')->create('bug', $bug->id, $changes ? $action : 'Commented', zget($bug, 'comment', ''));
            if($changes) $this->action->logHistory($actionID, $changes);
        }

        if(dao::isError()) return false;

        return $changes;
    }

    /**
     * 将 bug 指派给一个用户。
     * Assign a bug to a user.
     *
     * @param  object $bug
     * @param  object $oldBug
     * @access public
     * @return bool
     */
    public function assign(object $bug, object $oldBug): bool
    {
        $this->dao->update(TABLE_BUG)->data($bug, 'comment')->autoCheck()->checkFlow()->where('id')->eq($bug->id)->exec();
        if(dao::isError()) return false;

        /* 记录指派动作。*/
        /* Record log. */
        $actionID = $this->loadModel('action')->create('bug', $bug->id, 'Assigned', $bug->comment, $bug->assignedTo);
        $changes  = common::createChanges($oldBug, $bug);
        if($changes) $this->action->logHistory($actionID, $changes);

        return !dao::isError();
    }

    /**
     * 确认 bug。
     * Confirm a bug.
     *
     * @param  object $bug
     * @param  array  $kanbanData
     * @access public
     * @return bool
     */
    public function confirm(object $bug, array $kanbanData = array()): bool
    {
        $oldBug = $this->getByID($bug->id);

        $this->dao->update(TABLE_BUG)->data($bug, 'comment')->autoCheck()->checkFlow()->where('id')->eq($bug->id)->exec();
        if(dao::isError()) return false;

        /* 确认 bug 后的积分变动。*/
        /* Record score after confirming bug. */
        $this->loadModel('score')->create('bug', 'confirm', $oldBug);

        /* 如果 bug 有所属执行，更新看板数据。*/
        /* Update kanban if the bug has execution. */
        if($oldBug->execution)
        {
            $this->loadModel('kanban');
            if(!isset($kanbanData['toColID'])) $this->kanban->updateLane($oldBug->execution, 'bug', $oldBug->id);
            if(isset($kanbanData['toColID']))  $this->kanban->moveCard((int)$oldBug->id, (int)$kanbanData['fromColID'], (int)$kanbanData['toColID'], (int)$kanbanData['fromLaneID'], (int)$kanbanData['toLaneID'], (int)$oldBug->execution);
        }

        /* 记录历史记录。*/
        /* Record history. */
        $changes  = common::createChanges($oldBug, $bug);
        $actionID = $this->loadModel('action')->create('bug', $oldBug->id, 'bugConfirmed', $bug->comment);
        if($changes) $this->action->logHistory($actionID, $changes);

        return !dao::isError();
    }

    /**
     * 解决一个bug。
     * Resolve a bug.
     *
     * @param  object      $bugID
     * @param  array       $output
     * @access public
     * @return bool
     */
    public function resolve(object $bug, array $output = array()): bool
    {
        /* Get old bug. */
        $oldBug = $this->getById($bug->id);

        /* Update bug. */
        $this->dao->update(TABLE_BUG)->data($bug, 'buildName,createBuild,buildExecution,comment')
            ->autoCheck()
            ->batchCheck($this->config->bug->resolve->requiredFields, 'notempty')
            ->checkIF($bug->resolution == 'duplicate', 'duplicateBug', 'notempty')
            ->checkIF($bug->resolution == 'fixed',     'resolvedBuild','notempty')
            ->checkFlow()
            ->where('id')->eq($bug->id)
            ->exec();

        if(dao::isError()) return false;

        /* Add score. */
        $this->loadModel('score')->create('bug', 'resolve', $oldBug);

        /* Move bug card in kanban. */
        if($oldBug->execution)
        {
            if(!isset($output['toColID'])) $this->loadModel('kanban')->updateLane($oldBug->execution, 'bug', $bug->id);
            if(isset($output['toColID'])) $this->loadModel('kanban')->moveCard($bug->id, $output['fromColID'], $output['toColID'], $output['fromLaneID'], $output['toLaneID']);
        }

        /* Link bug to build and release. */
        $this->linkBugToBuild($bug->id, $bug->resolvedBuild);

        /* Save files and record log. */
        $files      = $this->loadModel('file')->saveUpload('bug', $bug->id);
        $fileAction = !empty($files) ? $this->lang->addFiles . implode(',', $files) . "\n" : '';
        $changes    = common::createChanges($oldBug, $bug);
        $actionID   = $this->loadModel('action')->create('bug', $bug->id, 'Resolved', $fileAction . (!empty($bug->comment) ? $bug->comment : ''), $bug->resolution . (isset($bug->duplicateBug) ? ':' . $bug->duplicateBug : ''));
        if($changes) $this->action->logHistory($actionID, $changes);

        /* If the edition is not pms, update feedback. */
        if($this->config->edition != 'open' && $oldBug->feedback) $this->loadModel('feedback')->updateStatus('bug', $oldBug->feedback, $bug->status, $oldBug->status);

        return !dao::isError();
    }

    /**
     * 在解决bug的时候创建一个版本。
     * Create build when resolving a bug.
     *
     * @param  object $bug
     * @param  object $oldBug
     * @access public
     * @return bool
     */
    public function createBuild(object $bug, object $oldBug): bool
    {
        /* Construct build data. */
        $buildData = new stdclass();
        $buildData->product     = (int)$oldBug->product;
        $buildData->branch      = (int)$oldBug->branch;
        $buildData->project     = $bug->buildExecution ? $this->dao->select('project')->from(TABLE_EXECUTION)->where('id')->eq($bug->buildExecution)->fetch('project') : 0;
        $buildData->execution   = $bug->buildExecution;
        $buildData->name        = $bug->buildName;
        $buildData->date        = date('Y-m-d');
        $buildData->builder     = $this->app->user->account;
        $buildData->createdBy   = $this->app->user->account;
        $buildData->createdDate = helper::now();

        /* Create a build. */
        $this->lang->build->name = $this->lang->bug->placeholder->newBuildName;
        $this->dao->insert(TABLE_BUILD)->data($buildData)->autoCheck()
            ->check('name', 'unique', "product = {$buildData->product} AND branch = {$buildData->branch} AND deleted = '0'")
            ->batchCheck('name,execution', 'notempty')
            ->exec();
        if(dao::isError())
        {
            $error = dao::getError();
            if(isset($error['name']))
            {
                $error['buildName'] = $error['name'];
                unset($error['name']);
            }
            if(isset($error['execution']))
            {
                $executionLang = $this->lang->bug->execution;
                if($oldBug->execution)
                {
                    $execution = $this->dao->findByID($oldBug->execution)->from(TABLE_EXECUTION)->fetch();
                    if($execution and $execution->type == 'kanban') $executionLang = $this->lang->bug->kanban;
                }
                $error['buildExecution'] = sprintf($this->lang->error->notempty, $executionLang);
                unset($error['execution']);
            }
            dao::$errors = $error;
            return false;
        }

        /* Get build id, and record log. */
        $buildID = $this->dao->lastInsertID();
        $this->loadModel('action')->create('build', $buildID, 'opened');
        $bug->resolvedBuild = $buildID;

        return !dao::isError();
    }

    /**
     * 激活一个bug。
     * Activate a bug.
     *
     * @param  object $bug
     * @param  array  $kanbanParams
     * @access public
     * @return bool
     */
    public function activate(object $bug, array $kanbanParams = array()): bool
    {
        $oldBug = parent::fetchByID($bug->id);

        $this->dao->update(TABLE_BUG)->data($bug, 'comment')->check('openedBuild', 'notempty')->autoCheck()->checkFlow()->where('id')->eq($bug->id)->exec();
        if(dao::isError()) return false;

        /* Update build. */
        $solveBuild = $this->dao->select('id, bugs')->from(TABLE_BUILD)->where("FIND_IN_SET('{$bug->id}', bugs)")->limit(1)->fetch();
        if($solveBuild)
        {
            $buildBugs = trim(str_replace(",{$bug->id},", ',', ",$solveBuild->bugs,"), ',');
            $this->dao->update(TABLE_BUILD)->set('bugs')->eq($buildBugs)->where('id')->eq($solveBuild->id)->exec();
        }

        /* Update kanban. */
        if($oldBug->execution)
        {
            $this->loadModel('kanban');
            if(!isset($kanbanParams['toColID'])) $this->kanban->updateLane($oldBug->execution, 'bug', $bug->id);
            if(isset($kanbanParams['toColID'])) $this->kanban->moveCard($bug->id, $kanbanParams['fromColID'], $kanbanParams['toColID'], $kanbanParams['fromLaneID'], $kanbanParams['toLaneID']);
        }

        $changes = common::createChanges($oldBug, $bug);
        $files   = $this->loadModel('file')->saveUpload('bug', $bug->id);
        if($changes || $files)
        {
            $fileAction = !empty($files) ? $this->lang->addFiles . implode(',', $files) . "\n" : '';
            $actionID   = $this->loadModel('action')->create('bug', $bug->id, 'Activated', $fileAction . $bug->comment);
            $this->action->logHistory($actionID, $changes);
        }

        return !dao::isError();
    }

    /**
     * 关闭一个bug。
     * Close a bug.
     *
     * @param  object $bug
     * @param  array  $output
     * @access public
     * @return bool
     */
    public function close(object $bug, array $output = array()): bool
    {
        $oldBug = $this->getById($bug->id);

        $this->dao->update(TABLE_BUG)->data($bug, 'comment')->autoCheck()->checkFlow()->where('id')->eq($bug->id)->exec();
        if(dao::isError()) return false;

        if($this->config->edition != 'open' && $oldBug->feedback) $this->loadModel('feedback')->updateStatus('bug', $oldBug->feedback, $bug->status, $oldBug->status);

        $changes = common::createChanges($oldBug, $bug);
        $actionID = $this->loadModel('action')->create('bug', $bug->id, 'Closed', $bug->comment);
        if($changes) $this->action->logHistory($actionID, $changes);

        if($oldBug->execution)
        {
            $this->loadModel('kanban');
            if(!isset($output['toColID'])) $this->kanban->updateLane($oldBug->execution, 'bug', $bug->id);
            if(isset($output['toColID'])) $this->kanban->moveCard($bug->id, $output['fromColID'], $output['toColID'], $output['fromLaneID'], $output['toLaneID']);
        }

        /* 给原bug的抄送人发送完消息后，再处理它。 */
        /* After sending a message to the cc of the original bug, then process with it. */
        $this->dao->update(TABLE_BUG)->set('assignedTo')->eq('closed')->where('id')->eq($bug->id)->exec();

        return !dao::isError();
    }

    /**
     * 获取可以关联的 bug 列表。
     * Get bugs to link.
     *
     * @param  int    $bugID
     * @param  bool   $bySearch
     * @param  int    $queryID
     * @param  string $excludeBugs
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getBugs2Link(int $bugID, bool $bySearch = false, string $excludeBugs = '', int $queryID = 0, object $pager = null): array
    {
        $bug = $this->getByID($bugID);

        $excludeBugs .= ",{$bug->id},{$bug->relatedBug}";

        if($bySearch) return $this->getBySearch('bug', (array)$bug->product, 'all', 0, 0, $queryID, $excludeBugs, 'id desc', $pager);

        return $this->dao->select('*')->from(TABLE_BUG)
            ->where('deleted')->eq('0')
            ->andWhere('id')->notin($excludeBugs)
            ->andWhere('product')->eq($bug->product)
            ->beginIF($bug->project)->andWhere('project')->eq($bug->project)->fi()
            ->beginIF($bug->execution)->andWhere('execution')->eq($bug->execution)->fi()
            ->orderBy('id desc')
            ->page($pager)
            ->fetchAll();
    }

    /**
     * Get statistic.
     *
     * @param  int    $productID
     * @param  string $endDate
     * @param  int    $days
     * @access public
     * @return void
     */
    public function getStatistic($productID = 0, $endDate = '', $days = 30)
    {
        $startDate = '';
        if(empty($endDate)) $endDate = date('Y-m-d');

        $dateArr = array();
        for($day = $days - 1; $day >= 0; $day--)
        {
            $time = strtotime(-$day . ' day', strtotime($endDate));
            $date = date('m/d', $time);
            $dateArr[$date] = new stdClass();
            $dateArr[$date]->num  = 0;
            $dateArr[$date]->date = $date;

            if($day == $days -1) $startDate = date('Y-m-d', $time) . ' 00:00:00';
        }

        $dateFields = array('openedDate', 'resolvedDate', 'closedDate');
        $staticData = array();
        foreach($dateFields as $field)
        {
            $bugCount = $this->dao->select("count(id) as num, date_format($field, '%m/%d') as date")->from(TABLE_BUG)
                ->where('product')->eq($productID)
                ->andWhere('deleted')->eq(0)
                ->andWhere($field)->between($startDate, $endDate . ' 23:50:59')
                ->groupBy('date')
                ->fetchAll('date');
            $staticData[$field] = array_merge($dateArr, $bugCount);
        }
        return $staticData;
    }

    /**
     * 构造搜索表单。
     * Build search form.
     *
     * @param  int    $productID
     * @param  array  $products
     * @param  int    $queryID
     * @param  string $actionURL
     * @param  string $branch
     * @access public
     * @return void
     */
    public function buildSearchForm(int $productID, array $products, int $queryID, string $actionURL, string $branch = '0'): void
    {
        if(commonModel::isTutorialMode()) return;
        $projectID = $this->lang->navGroup->bug == 'qa' ? 0 : $this->session->project;

        /* Get product params. */
        $productParams = ($productID && isset($products[$productID])) ? array($productID => $products[$productID]) : $products;
        $productParams = $productParams + array('all' => $this->lang->all);

        /* Get project params. */
        $projectParams = $this->loadModel('product')->getProjectPairsByProduct($productID, 'all');
        $projectParams = $projectParams + array('all' => $this->lang->bug->allProject);

        /* Get all modules. */
        $this->loadModel('tree');
        if($productID) $modules = $this->tree->getOptionMenu($productID, 'bug', 0, $branch);
        if(!$productID)
        {
            $modules = array();
            foreach($products as $id => $productName) $modules += $this->tree->getOptionMenu($id, 'bug');
        }

        $this->config->bug->search['actionURL'] = $actionURL;
        $this->config->bug->search['queryID']   = $queryID;
        $this->config->bug->search['params']['project']['values']       = $projectParams;
        $this->config->bug->search['params']['product']['values']       = $productParams;
        $this->config->bug->search['params']['plan']['values']          = $this->loadModel('productplan')->getPairs($productID);
        $this->config->bug->search['params']['module']['values']        = $modules;
        $this->config->bug->search['params']['execution']['values']     = $this->loadModel('product')->getExecutionPairsByProduct($productID, '0', (int)$projectID);
        $this->config->bug->search['params']['severity']['values']      = array(0 => '') + $this->lang->bug->severityList; //Fix bug #939.
        $this->config->bug->search['params']['openedBuild']['values']   = $this->loadModel('build')->getBuildPairs(array($productID), 'all', 'withbranch|releasetag');
        $this->config->bug->search['params']['resolvedBuild']['values'] = $this->config->bug->search['params']['openedBuild']['values'];

        $product = $this->loadModel('product')->fetchByID($productID);
        if($product->type == 'normal')
        {
            unset($this->config->bug->search['fields']['branch']);
            unset($this->config->bug->search['params']['branch']);
        }
        else
        {
            $branches = $this->loadModel('branch')->getPairs($productID, 'noempty');
            $this->config->bug->search['fields']['branch']           = sprintf($this->lang->product->branch, $this->lang->product->branchName[$product->type]);
            $this->config->bug->search['params']['branch']['values'] = array('' => '', BRANCH_MAIN => $this->lang->branch->main) + $branches + array('all' => $this->lang->branch->all);
        }

        $this->loadModel('search')->setSearchParams($this->config->bug->search);
    }

    /**
     * 获取 bugs 的影响版本和解决版本。
     * Process the openedBuild and resolvedBuild fields for bugs.
     *
     * @param  array  $bugs
     * @access public
     * @return array
     */
    public function processBuildForBugs(array $bugs): array
    {
        $productIdList = array();
        foreach($bugs as $bug) $productIdList[$bug->product] = $bug->product;
        $builds = $this->loadModel('build')->getBuildPairs(array_unique($productIdList), 'all', $params = 'noterminate,nodone,hasdeleted');

        /* Process the openedBuild and resolvedBuild fields. */
        foreach($bugs as $bug)
        {
            $openBuildIdList = explode(',', $bug->openedBuild);

            $openedBuild = '';
            foreach($openBuildIdList as $buildID) $openedBuild .= zget($builds, $buildID) . ',';

            $bug->openedBuild   = rtrim($openedBuild, ',');
            $bug->resolvedBuild = zget($builds, $bug->resolvedBuild);
        }
        return $bugs;
    }

    /**
     * 测试获取当前用户的 bugs。
     * Get user bugs.
     *
     * @param  string $account
     * @param  string $type
     * @param  string $orderBy
     * @param  int    $limit
     * @param  object $pager
     * @param  int    $executionID
     * @param  int    $queryID
     * @access public
     * @return array
     */
    public function getUserBugs(string $account, string $type = 'assignedTo', string $orderBy = 'id_desc', int $limit = 0, object $pager = null, int $executionID = 0, int $queryID = 0): array
    {
        if($type != 'bySearch' and !$this->loadModel('common')->checkField(TABLE_BUG, $type)) return array();

        $moduleName = $this->app->rawMethod == 'work' ? 'workBug' : 'contributeBug';
        $queryName  = $moduleName . 'Query';
        $formName   = $moduleName . 'Form';
        if($queryID)
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set($queryName, $query->sql);
                $this->session->set($formName, $query->form);
            }
            else
            {
                $this->session->set($queryName, ' 1 = 1');
            }
        }
        else
        {
            if($this->session->$queryName === false) $this->session->set($queryName, ' 1 = 1');
        }
        $query = $this->session->$queryName;
        $query = preg_replace('/`(\w+)`/', 't1.`$1`', $query);

        if($moduleName == 'contributeBug') $bugsAssignedByMe = $this->loadModel('my')->getAssignedByMe($account, null, $orderBy, 'bug');
        return $this->dao->select("t1.*, t2.name AS productName, t2.shadow, IF(t1.`pri` = 0, {$this->config->maxPriValue}, t1.`pri`) AS priOrder, IF(t1.`severity` = 0, {$this->config->maxPriValue}, t1.`severity`) AS severityOrder")->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq('0')
            ->beginIF($type == 'bySearch')->andWhere($query)->fi()
            ->beginIF($executionID)->andWhere('t1.execution')->eq($executionID)->fi()
            ->beginIF($type != 'closedBy' and $this->app->moduleName == 'block')->andWhere('t1.status')->ne('closed')->fi()
            ->beginIF($type != 'all' and $type != 'bySearch')->andWhere("t1.`$type`")->eq($account)->fi()
            ->beginIF($type == 'bySearch' and $moduleName == 'workBug')->andWhere("t1.assignedTo")->eq($account)->fi()
            ->beginIF($type == 'assignedTo' and $moduleName == 'workBug')->andWhere('t1.status')->ne('closed')->fi()
            ->beginIF($type == 'bySearch' and $moduleName == 'contributeBug')
            ->andWhere('t1.openedBy', 1)->eq($account)
            ->orWhere('t1.closedBy')->eq($account)
            ->orWhere('t1.resolvedBy')->eq($account)
            ->orWhere('t1.id')->in(!empty($bugsAssignedByMe) ? array_keys($bugsAssignedByMe) : array())
            ->markRight(1)
            ->fi()
            ->orderBy($orderBy)
            ->beginIF($limit > 0)->limit($limit)->fi()
            ->page($pager)
            ->fetchAll();
    }

    /**
     * 测试获取用户的bugs的 id => title 数组。
     * Get bug pairs of a user.
     *
     * @param  string    $account
     * @param  bool      $appendProduct
     * @param  int       $limit
     * @param  array     $skipProductIdList
     * @param  array     $skipExecutionIdList
     * @param  int|array $appendBugID
     * @access public
     * @return array
     */
    public function getUserBugPairs(string $account, bool $appendProduct = true, int $limit = 0, array $skipProductIdList = array(), array $skipExecutionIdList = array(), array|int $appendBugID = 0): array
    {
        $deletedProjectIdList = $this->dao->select('id')->from(TABLE_PROJECT)->where('deleted')->eq(1)->fetchPairs();

        $bugs = array();
        $stmt = $this->dao->select('t1.id, t1.title, t2.name as product')
            ->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')
            ->on('t1.product=t2.id')
            ->where('t1.assignedTo')->eq($account)
            ->andWhere('t1.status')->ne('closed')
            ->beginIF(!empty($deletedProjectIdList))->andWhere('t1.execution')->notin($deletedProjectIdList)->fi()
            ->beginIF(!empty($skipProductIdList))->andWhere('t1.product')->notin($skipProductIdList)->fi()
            ->beginIF(!empty($skipExecutionIdList))->andWhere('t1.execution')->notin($skipExecutionIdList)->fi()
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->beginIF(!empty($appendBugID))->orWhere('t1.id')->in($appendBugID)->fi()
            ->orderBy('id desc')
            ->beginIF($limit > 0)->limit($limit)->fi()
            ->query();
        while($bug = $stmt->fetch())
        {
            if($appendProduct) $bug->title = $bug->product . ' / ' . $bug->title;
            $bugs[$bug->id] = $bug->title;
        }
        return $bugs;
    }

    /**
     * 获取某个项目的 bugs。
     * Get bugs of a project.
     *
     * @param  int        $projectID
     * @param  int        $productID
     * @param  int|string $branchID
     * @param  int        $build
     * @param  string     $type
     * @param  int        $param
     * @param  string     $orderBy
     * @param  string     $excludeBugs
     * @param  object     $pager
     * @access public
     * @return array
     */
    public function getProjectBugs(int $projectID, int $productID = 0, int|string $branchID = 0, int $build = 0, string $type = '', int $param = 0, string $orderBy = 'id_desc', string $excludeBugs = '', object $pager = null): array
    {
        if(strpos($orderBy, 'pri_') !== false)      $orderBy = str_replace('pri_', 'priOrder_', $orderBy);
        if(strpos($orderBy, 'severity_') !== false) $orderBy = str_replace('severity_', 'severityOrder_', $orderBy);

        $type = strtolower($type);

        if($type == 'bysearch')
        {
            $bugs = $this->getBySearch('project', $productID, $branchID, $projectID, 0, $param, $excludeBugs, $orderBy, $pager);
        }
        else
        {
            $bugs = $this->dao->select("t1.*, IF(t1.`pri` = 0, {$this->config->maxPriValue}, t1.`pri`) AS priOrder, IF(t1.`severity` = 0, {$this->config->maxPriValue}, t1.`severity`) AS severityOrder")->from(TABLE_BUG)->alias('t1')
                ->leftJoin(TABLE_MODULE)->alias('t2')->on('t1.module=t2.id')
                ->where('t1.deleted')->eq(0)
                ->beginIF(empty($build))->andWhere('t1.project')->eq($projectID)->fi()
                ->beginIF(!empty($productID))->andWhere('t1.product')->eq($productID)->fi()
                ->beginIF(!empty($productID) and $branchID != 'all')->andWhere('t1.branch')->eq($branchID)->fi()
                ->beginIF($type == 'unresolved')->andWhere('t1.status')->eq('active')->fi()
                ->beginIF($type == 'noclosed')->andWhere('t1.status')->ne('closed')->fi()
                ->beginIF($type == 'assignedtome')->andWhere('t1.assignedTo')->eq($this->app->user->account)->fi()
                ->beginIF($type == 'openedbyme')->andWhere('t1.openedBy')->eq($this->app->user->account)->fi()
                ->beginIF(!empty($param))->andWhere('t2.path')->like("%,$param,%")->andWhere('t2.deleted')->eq(0)->fi()
                ->beginIF($build)->andWhere("CONCAT(',', t1.openedBuild, ',') like '%,$build,%'")->fi()
                ->beginIF($excludeBugs)->andWhere('t1.id')->notIN($excludeBugs)->fi()
                ->orderBy($orderBy)
                ->page($pager)
                ->fetchAll();
        }

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'bug', false);

        return $bugs;
    }

    /**
     * 获取执行的 bug。
     * Get bugs of a execution.
     *
     * @param  int          $executionID
     * @param  int          $productID
     * @param  int|string   $branchID
     * @param  string|array $builds
     * @param  string       $type
     * @param  int          $param
     * @param  string       $orderBy
     * @param  string       $excludeBugs
     * @param  object       $pager
     * @access public
     * @return array
     */
    public function getExecutionBugs(int $executionID, int $productID = 0, string|int $branchID = 'all', string|array $builds = '0', string $type = '', int $param = 0, string $orderBy = 'id_desc', string $excludeBugs = '', object $pager = null): array
    {
        if(strpos($orderBy, 'pri_') !== false)      $orderBy = str_replace('pri_', 'priOrder_', $orderBy);
        if(strpos($orderBy, 'severity_') !== false) $orderBy = str_replace('severity_', 'severityOrder_', $orderBy);

        $type = strtolower($type);
        if($type == 'bysearch')
        {
            $bugs = $this->getBySearch('execution', $productID, $branchID, $projectID = 0, $executionID, $param, $excludeBugs, $orderBy, $pager);
        }
        else
        {
            $condition = '';
            if($builds)
            {
                $conditions = array();

                if(!is_array($builds)) $builds = array_unique(explode(',', $builds));
                foreach($builds as $build) $conditions[] = "FIND_IN_SET('$build', t1.openedBuild)";

                $condition = implode(' OR ', $conditions);
                $condition = "($condition)";
            }

            $bugs = $this->dao->select("t1.*, IF(t1.`pri` = 0, {$this->config->maxPriValue}, t1.`pri`) AS priOrder, IF(t1.`severity` = 0, {$this->config->maxPriValue}, t1.`severity`) AS severityOrder")->from(TABLE_BUG)->alias('t1')
                ->leftJoin(TABLE_MODULE)->alias('t2')->on('t1.module = t2.id')
                ->where('t1.deleted')->eq('0')
                ->beginIF(!empty($productID) && $branchID !== 'all')->andWhere('t1.branch')->eq($branchID)->fi()
                ->beginIF(empty($builds))->andWhere('t1.execution')->eq($executionID)->fi()
                ->beginIF(!empty($productID))->andWhere('t1.product')->eq($productID)->fi()
                ->beginIF($type == 'unresolved')->andWhere('t1.status')->eq('active')->fi()
                ->beginIF($type == 'noclosed')->andWhere('t1.status')->ne('closed')->fi()
                ->beginIF($condition)->andWhere("$condition")->fi()
                ->beginIF(!empty($param))
                ->andWhere('t2.path')->like("%,$param,%")
                ->andWhere('t2.deleted')->eq('0')
                ->fi()
                ->beginIF($excludeBugs)->andWhere('t1.id')->notIN($excludeBugs)->fi()
                ->orderBy($orderBy)
                ->page($pager)
                ->fetchAll('id');
        }

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'bug', false);

        return $bugs;
    }

    /**
     * 获取产品未关联版本的bug。
     * Get product left bugs.
     *
     * @param  array      $buildIdList
     * @param  int        $productID
     * @param  int|string $branch
     * @param  string     $linkedBugs
     * @param  object     $pager
     * @access public
     * @return array|null
     */
    public function getProductLeftBugs(array $buildIdList, int $productID, int|string $branch = '', string $linkedBugs = '', object $pager = null): array|null
    {
        /* 获取版本关联的执行。 */
        /* Get executions of builds. */
        $executionIdList = $this->getLinkedExecutionByIdList($buildIdList);
        if(empty($executionIdList)) return array();

        /* 获取执行的最小开始时间和最大结束时间。 */
        /* Get min begin date and max end date. */
        $minBegin   = '1970-01-01';
        $maxEnd     = '1970-01-01';
        $executions = $this->dao->select('*')->from(TABLE_EXECUTION)->where('id')->in($executionIdList)->fetchAll();
        foreach($executions as $execution)
        {
            if(empty($minBegin) || $minBegin > $execution->begin) $minBegin = $execution->begin;
            if(empty($maxEnd)   || $maxEnd   < $execution->end)   $maxEnd   = $execution->end;
        }

        /* 获取在最小开始日期之前 未完成的版本id。 */
        /* Get undone builds before min begin date. */
        $beforeBuilds = $this->dao->select('t1.id')->from(TABLE_BUILD)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.execution=t2.id')
            ->where('t1.product')->eq($productID)
            ->andWhere('t2.status')->ne('done')
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t1.date')->lt($minBegin)
            ->fetchPairs('id', 'id');

        /* 返回在执行最小开始和最大结束时间内的未关联版本的bugs。 */
        /* Return bugs that unrelate builds in the execution timeframe. */
        return $this->dao->select('*')->from(TABLE_BUG)->where('deleted')->eq(0)
            ->andWhere('product')->eq($productID)
            ->andWhere('toStory')->eq(0)
            ->andWhere('openedDate')->ge($minBegin)
            ->andWhere('openedDate')->le($maxEnd)
            ->andWhere("(status = 'active' OR resolvedDate > '{$maxEnd}')")
            ->andWhere('openedBuild')->notin($beforeBuilds)
            ->beginIF($linkedBugs)->andWhere('id')->notIN($linkedBugs)->fi()
            ->beginIF($branch !== '')->andWhere('branch')->in("0,$branch")->fi()
            ->page($pager)
            ->fetchAll();
    }

    /**
     * 获取产品的bug键对。
     * Get product bug pairs.
     *
     * @param  int        $productID
     * @param  int|string $branch
     * @param  string     $search
     * @param  int        $limit
     * @access public
     * @return array
     */
    public function getProductBugPairs(int $productID, int|string $branch = '', string $search = '', int $limit = 0): array
    {
        /* 获取产品的bugs。 */
        /* Get product bugs. */
        $data = $this->dao->select('id, title')->from(TABLE_BUG)
            ->where('product')->eq((int)$productID)
            ->beginIF(!$this->app->user->admin)->andWhere('execution')->in('0,' . $this->app->user->view->sprints)->fi()
            ->beginIF($branch !== '')->andWhere('branch')->in($branch)->fi()
            ->beginIF(strlen(trim($search)))->andWhere('title')->like('%' . $search . '%')->fi()
            ->andWhere('deleted')->eq(0)
            ->orderBy('id desc')
            ->beginIF($limit)->limit($limit)->fi()
            ->fetchAll();
        /* 将bugs转为bug键对。 */
        /* Convert bugs to bug pairs. */
        $bugs = array();
        foreach($data as $bug) $bugs[$bug->id] = $bug->id . ':' . $bug->title;
        return $bugs;
    }

    /**
     * 获取产品成员键对。
     * get Product member pairs.
     *
     * @param  int    $productID
     * @param  string $branchID
     * @access public
     * @return array
     */
    public function getProductMemberPairs(int $productID, string $branchID = ''): array
    {
        if(commonModel::isTutorialMode()) return $this->loadModel('tutorial')->getTeamMembersPairs();

        /* 获取产品关联的项目。 */
        /* Get related projects of product. */
        $projects = $this->loadModel('product')->getProjectPairsByProduct($productID, $branchID);

        /* 获取项目的团队成员。 */
        /* Get team members of projects. */
        return $this->loadModel('user')->getTeamMemberPairs(array_keys($projects));
    }

    /**
     * 通过版本 id 和产品 id 获取 bugs。
     * Get bugs according to buildID and productID.
     *
     * @param  array  $buildIdList
     * @param  int    $productID
     * @param  string $branch
     * @param  string $linkedBugs
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getReleaseBugs(array $buildIdList, int $productID, int|string $branch = 0, string $linkedBugs = '', object $pager = null): array
    {
        $executionIdList = $this->getLinkedExecutionByIdList($buildIdList);
        if(empty($executionIdList)) return array();

        $executions = $this->dao->select('id,type,begin')->from(TABLE_EXECUTION)->where('id')->in($executionIdList)->fetchAll('id');
        if(count($executionIdList) > 1)  $condition  = 'execution NOT ' . helper::dbIN($executionIdList);
        else $condition  = 'execution !' . helper::dbIN($executionIdList);
        $minBegin   = '';
        foreach($executions as $execution)
        {
            if(empty($minBegin) or $minBegin > $execution->begin) $minBegin = $execution->begin;
            $condition .= " OR (`execution` = '{$execution->id}' AND openedDate < '{$execution->begin}')";
        }

        return $this->dao->select('*')->from(TABLE_BUG)
            ->where('resolution')->ne('postponed')
            ->beginIF(!empty($minBegin))->andWhere('resolvedDate')->ge($minBegin)->fi()
            ->andWhere('product')->eq($productID)
            ->beginIF($linkedBugs)->andWhere('id')->notIN($linkedBugs)->fi()
            ->beginIF($branch)->andWhere('branch')->in("0,$branch")->fi()
            ->andWhere("($condition)")
            ->andWhere('deleted')->eq(0)
            ->orderBy('openedDate ASC')
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * 通过版本 id 列表获取版本关联的执行。
     * Get linked execution by build id list.
     *
     * @param  array  $buildIdList
     * @access public
     * @return array
     */
    public function getLinkedExecutionByIdList(array $buildIdList): array
    {
        /* Get information of builds. */
        $builds = $this->dao->select('id,execution,builds')->from(TABLE_BUILD)->where('id')->in($buildIdList)->fetchAll('id');

        $executionIdList   = array();
        $linkedBuildIdList = array();
        foreach($builds as $build)
        {
            /* 如果版本的builds字段不为空，将版本的builds追加到linkedBuildIdList数组。 */
            /* If build builds is not empty, append build builds to linkedBuildIdList. */
            if($build->builds) $linkedBuildIdList = array_merge($linkedBuildIdList, explode(',', $build->builds));
            /* 如果版本的执行字段不为空，将执行字段追加到executionIdList数组。 */
            /* If build execution is not empty, append build execution to executionIdList. */
            if(!empty($build->execution)) $executionIdList[$build->execution] = $build->execution;
        }

        /* 如果linkedBuildIdList不为空，将关联builds的版本追加到executionIdList数组。 */
        /* If linkedBuildIdList is not empty, append the execution of the linked builds to executionIdList. */
        if($linkedBuildIdList)
        {
            $linkedBuilds = $this->dao->select('*')->from(TABLE_BUILD)->where('id')->in(array_unique($linkedBuildIdList))->fetchAll('id');
            foreach($linkedBuilds as $build)
            {
                if(empty($build->execution)) continue;
                $executionIdList[$build->execution] = $build->execution;
            }
        }
        return $executionIdList;
    }

    /**
     * 获取需求产生的bugs。
     * Get bugs of a story.
     *
     * @param  int    $storyID
     * @param  int    $executionID
     * @access public
     * @return array
     */
    public function getStoryBugs(int $storyID, int $executionID = 0): array|null
    {
        /* 获取需求产生的bugs。 */
        /* Get bugs of the story. */
        return $this->dao->select('id, title, pri, type, status, assignedTo, resolvedBy, resolution')
            ->from(TABLE_BUG)
            ->where('story')->eq($storyID)
            ->beginIF($executionID)->andWhere('execution')->eq($executionID)->fi()
            ->andWhere('deleted')->eq(0)
            ->fetchAll('id');
    }

    /**
     * 获取用例关联的bugs。
     * Get case bugs.
     *
     * @param  int        $runID
     * @param  int        $caseID
     * @param  int        $version
     * @param  string     $orderBy
     * @access public
     * @return array|null
     */
    public function getCaseBugs(int $runID, int $caseID = 0, int $version = 0, string $orderBy = 'id_asc'): array|null
    {
        /* 获取用例关联的bugs。 */
        /* Get case bugs. */
        return $this->dao->select('*')->from(TABLE_BUG)
            ->where('1=1')
            ->beginIF($runID)->andWhere('`result`')->eq($runID)->fi()
            ->beginIF($runID == 0 and $caseID)->andWhere('`case`')->eq($caseID)->fi()
            ->beginIF($version)->andWhere('`caseVersion`')->eq($version)->fi()
            ->andWhere('deleted')->eq(0)
            ->orderBy($orderBy)
            ->fetchAll('id');
    }

    /**
     * 获取需求关联的 bug 数量。
     * Get counts of some stories' bugs.
     *
     * @param  array  $stories
     * @param  int    $executionID
     * @access public
     * @return array
     */
    public function getStoryBugCounts(array $stories, int $executionID = 0): array
    {
        if(empty($stories)) return array();
        $bugCounts = $this->dao->select('story, COUNT(*) AS bugs')
            ->from(TABLE_BUG)
            ->where('story')->in($stories)
            ->andWhere('deleted')->eq(0)
            ->beginIF($executionID)->andWhere('execution')->eq($executionID)->fi()
            ->groupBy('story')
            ->fetchPairs();
        foreach($stories as $storyID) if(!isset($bugCounts[$storyID])) $bugCounts[$storyID] = 0;
        return $bugCounts;
    }

    /**
     * 从测试结果中获取bug信息。
     * Get bug info from a result.
     *
     * @param  int    $resultID
     * @param  int    $caseID
     * @param  string $caseID
     * @access public
     * @return array
     */
    public function getBugInfoFromResult(int $resultID, int $caseID = 0, string $stepIdList = ''): array
    {
        $result = $this->dao->findById($resultID)->from(TABLE_TESTRESULT)->fetch();
        if(!$result) return array();

        if($caseID > 0)
        {
            $run = new stdclass();
            $run->case = $this->loadModel('testcase')->getById($caseID, $result->version);
        }
        else
        {
            $run = $this->loadModel('testtask')->getRunById($result->run);
        }

        $stepResults = unserialize($result->stepResults);
        if(!empty($stepResults))
        {
            $bugStep   = '';
            $bugResult = isset($stepResults[0]) ? zget($stepResults[0], 'real') : '';
            $bugExpect = '';
            $caseSteps = $run->case->steps;
            $caseSteps = array_combine(array_column($caseSteps, 'id'), $caseSteps);
            $steps     = explode('_', trim($stepIdList, '_'));
            foreach($steps as $stepId)
            {
                if(!isset($caseSteps[$stepId])) continue;
                $step = $caseSteps[$stepId];

                $stepDesc   = str_replace("\n", "<br />", $step->desc);
                $stepExpect = str_replace("\n", "<br />", $step->expect);
                $stepResult = (!isset($stepResults[$stepId]) or empty($stepResults[$stepId]['real'])) ? '' : $stepResults[$stepId]['real'];

                $bugStep   .= $step->name . '. ' . $stepDesc . "<br />";
                $bugResult .= $step->name . '. ' . $stepResult . "<br />";
                $bugExpect .= $step->name . '. ' . $stepExpect . "<br />";
            }
        }

        $bugSteps  = $run->case->precondition != '' ? "<p>[" . $this->lang->testcase->precondition . "]</p>" . "\n" . $run->case->precondition : '';
        $bugSteps .= !empty($stepResults) && !empty($bugStep)   ? str_replace(array('<br/>', '<p></p>'), '', $this->lang->bug->tplStep)   . $bugStep   : $this->lang->bug->tplStep;
        $bugSteps .= !empty($stepResults) && !empty($bugResult) ? str_replace(array('<br/>', '<p></p>'), '', $this->lang->bug->tplResult) . $bugResult : $this->lang->bug->tplResult;
        $bugSteps .= !empty($stepResults) && !empty($bugExpect) ? str_replace(array('<br/>', '<p></p>'), '', $this->lang->bug->tplExpect) . $bugExpect : $this->lang->bug->tplExpect;

        if(!empty($run->task)) $testtask = $this->loadModel('testtask')->getByID($run->task);
        $executionID = isset($testtask->execution) ? $testtask->execution : 0;

        if(!$executionID and $caseID > 0) $executionID = isset($run->case->execution) ? $run->case->execution : 0; // Fix feedback #1043.
        if(!$executionID and $this->app->tab == 'execution') $executionID = $this->session->execution;

        return array('title' => $run->case->title, 'caseID' => $caseID, 'steps' => $bugSteps, 'storyID' => $run->case->story, 'moduleID' => $run->case->module, 'version' => $run->case->version, 'executionID' => $executionID);
    }

    /**
     * 统计迭代 bug 数量。
     * Statistics by bug execution.
     *
     * @access public
     * @return array
     */
    public function getDataOfBugsPerExecution(): array
    {
        $datas = $this->dao->select('execution AS name, count(execution) AS value')->from(TABLE_BUG)->where($this->reportCondition())->groupBy('execution')->orderBy('value_desc')->fetchAll('name');
        if(!$datas) return array();

        $executionPairs = $this->loadModel('execution')->getPairs();
        $maxLength      = common::checkNotCN() ? 22 : 12;
        foreach($datas as $executionID => $data)
        {
            $data->name  = zget($executionPairs, $executionID, $this->lang->report->undefined);
            $data->title = $data->name;

            if(mb_strlen($data->name, 'UTF-8') > $maxLength) $data->name = mb_substr($data->name, 0, $maxLength, 'UTF-8') . '...';
        }
        return $datas;
    }

    /**
     * 统计版本 Bug 数量。
     * Statistics by bug build.
     *
     * @access public
     * @return array
     */
    public function getDataOfBugsPerBuild(): array
    {
        $datas = $this->dao->select('openedBuild AS name, COUNT(openedBuild) AS value')->from(TABLE_BUG)->where($this->reportCondition())->groupBy('openedBuild')->orderBy('value DESC')->fetchAll('name');
        if(!$datas) return array();

        foreach($datas as $buildIdList => $data)
        {
            if(is_int($buildIdList)) continue;

            $openedBuildIdList = explode(',', $buildIdList);

            /* Bug 可以关联多个影响版本，一个版本被多个 bug 关联时，累加 bug 数量。*/
            /* Bugs can be associated with multiple builds. When a build is associated with multiple bugs, accumulated bugs.*/
            foreach($openedBuildIdList as $buildID)
            {
                if(!isset($datas[$buildID]))
                {
                    $datas[$buildID] = new stdclass();
                    $datas[$buildID]->name  = $buildID;
                    $datas[$buildID]->value = 0;
                }

                $datas[$buildID]->value += $data->value;
            }

            unset($datas[$buildIdList]);
        }

        /* 统计最后一次查询的所属产品列表。*/
        /* Get products in the last query. */
        $productIdList = $this->session->product ? array($this->session->product) : array();
        if($this->reportCondition() !== true)
        {
            preg_match('/`product` IN \((?P<productIdList>.+)\)/', $this->reportCondition(), $matches);
            if(!empty($matches) && isset($matches['productIdList']))
            {
                $productIdList = str_replace('\'', '', $matches['productIdList']);
                $productIdList = explode(',', $productIdList);
            }
        }

        $builds = $this->loadModel('build')->getBuildPairs($productIdList, 0, 'hasdeleted');

        $this->app->loadLang('report');
        foreach($datas as $buildID => $data) $data->name = zget($builds, $buildID, $this->lang->report->undefined);

        ksort($datas);

        return $datas;
    }

    /**
     * 统计模块 bug 数量。
     * Statistics by bug module.
     *
     * @access public
     * @return array
     */
    public function getDataOfBugsPerModule(): array
    {
        $datas = $this->dao->select('module AS name, COUNT(module) AS value')->from(TABLE_BUG)->where($this->reportCondition())->groupBy('module')->orderBy('value DESC')->fetchAll('name');
        if(!$datas) return array();

        $modules = $this->loadModel('tree')->getModulesName(array_keys($datas), true, true);

        foreach($datas as $moduleID => $data) $data->name = zget($modules, $moduleID, '/');

        return $datas;
    }

    /**
     * 统计每天新增 bug 数量。
     * Statistics by the number of created bug daily.
     *
     * @access public
     * @return array
     */
    public function getDataOfOpenedBugsPerDay(): array
    {
        return $this->dao->select('DATE_FORMAT(openedDate, "%Y-%m-%d") AS name, COUNT(*) AS value')->from(TABLE_BUG)->where($this->reportCondition())->groupBy('name')->orderBy('name')->fetchAll();
    }

    /**
     * 统计每天解决 bug 数量。
     * Statistics by the number of resolved bug.
     *
     * @access public
     * @return array
     */
    public function getDataOfResolvedBugsPerDay(): array
    {
        return $this->dao->select('DATE_FORMAT(resolvedDate, "%Y-%m-%d") AS name, COUNT(*) AS value')->from(TABLE_BUG)
            ->where($this->reportCondition())
            ->groupBy('name')
            ->having('name != 0000-00-00')
            ->orderBy('name')
            ->fetchAll();
    }

    /**
     * 统计每天关闭 bug 数量。
     * Statistics by the number of closed bug.
     *
     * @access public
     * @return array
     */
    public function getDataOfClosedBugsPerDay(): array
    {
        return $this->dao->select('DATE_FORMAT(closedDate, "%Y-%m-%d") AS name, COUNT(*) AS value')->from(TABLE_BUG)
            ->where($this->reportCondition())
            ->groupBy('name')
            ->having('name != 0000-00-00')
            ->orderBy('name')
            ->fetchAll();
    }

    /**
     * 统计每人提交的 bug 数量。
     * Statistics by bug creator.
     *
     * @access public
     * @return array
     */
    public function getDataOfOpenedBugsPerUser(): array
    {
        $datas = $this->dao->select('openedBy AS name, COUNT(*) AS value')->from(TABLE_BUG)->where($this->reportCondition())->groupBy('name')->orderBy('value DESC')->fetchAll('name');
        if(!$datas) return array();

        if(!isset($this->users)) $this->users = $this->loadModel('user')->getPairs('noletter');
        foreach($datas as $account => $data) $data->name = zget($this->users, $account);

        return $datas;
    }

    /**
     * 统计每人解决的 bug 数量。
     * Statistics by bug resolver.
     *
     * @access public
     * @return array
     */
    public function getDataOfResolvedBugsPerUser(): array
    {
        $datas = $this->dao->select('resolvedBy AS name, COUNT(*) AS value')
            ->from(TABLE_BUG)->where($this->reportCondition())
            ->andWhere('resolvedBy')->ne('')
            ->groupBy('name')
            ->orderBy('value DESC')
            ->fetchAll('name');
        if(!$datas) return array();

        if(!isset($this->users)) $this->users = $this->loadModel('user')->getPairs('noletter');
        foreach($datas as $account => $data) $data->name = zget($this->users, $account);

        return $datas;
    }

    /**
     * 统计每人关闭的 bug 数量。
     * Statistics by bug closer.
     *
     * @access public
     * @return array
     */
    public function getDataOfClosedBugsPerUser(): array
    {
        $datas = $this->dao->select('closedBy AS name, COUNT(*) AS value')
            ->from(TABLE_BUG)
            ->where($this->reportCondition())
            ->andWhere('closedBy')->ne('')
            ->groupBy('name')
            ->orderBy('value DESC')
            ->fetchAll('name');
        if(!$datas) return array();

        if(!isset($this->users)) $this->users = $this->loadModel('user')->getPairs('noletter');
        foreach($datas as $account => $data) $data->name = zget($this->users, $account);

        return $datas;
    }

    /**
     * 按照 bug 严重程度统计。
     * Statistics by bug severity
     *
     * @access public
     * @return array
     */
    public function getDataOfBugsPerSeverity(): array
    {
        $datas = $this->dao->select('severity AS name, COUNT(*) AS value')->from(TABLE_BUG)->where($this->reportCondition())->groupBy('name')->orderBy('value DESC')->fetchAll('name');
        if(!$datas) return array();

        foreach($datas as $severity => $data) $data->name = $this->lang->bug->report->bugsPerSeverity->graph->xAxisName . ':' . zget($this->lang->bug->severityList, $severity);
        return $datas;
    }

    /**
     * 按照解决方案统计。
     * Statistics by bug resolution.
     *
     * @access public
     * @return array
     */
    public function getDataOfBugsPerResolution(): array
    {
        $datas = $this->dao->select('resolution AS name, COUNT(*) AS value')
            ->from(TABLE_BUG)
            ->where($this->reportCondition())
            ->andWhere('resolution')->ne('')
            ->groupBy('name')
            ->orderBy('value DESC')
            ->fetchAll('name');
        if(!$datas) return array();

        foreach($datas as $resolution => $data) $data->name = zget($this->lang->bug->resolutionList, $resolution);

        return $datas;
    }

    /**
     * 按 bug 状态统计。
     * Statistics by bug status.
     *
     * @access public
     * @return array
     */
    public function getDataOfBugsPerStatus(): array
    {
        $datas = $this->dao->select('status AS name, COUNT(*) AS value')->from(TABLE_BUG)->where($this->reportCondition())->groupBy('name')->orderBy('value DESC')->fetchAll('name');
        if(!$datas) return array();

        foreach($datas as $status => $data) $data->name = zget($this->lang->bug->statusList, $status);

        return $datas;
    }

    /**
     * 按照 bug 优先级统计。
     * Statistics by bug pri.
     *
     * @access public
     * @return array
     */
    public function getDataOfBugsPerPri(): array
    {
        $datas = $this->dao->select('pri AS name, COUNT(*) AS value')->from(TABLE_BUG)->where($this->reportCondition())->groupBy('name')->orderBy('value DESC')->fetchAll('name');
        if(!$datas) return array();

        foreach($datas as $data) $data->name = $this->lang->bug->report->bugsPerPri->graph->xAxisName . ':' . zget($this->lang->bug->priList, $data->name);

        return $datas;
    }

    /**
     * 按照 bug 激活次数统计。
     * Statistics by bug activation times.
     *
     * @access public
     * @return array
     */
    public function getDataOfBugsPerActivatedCount(): array
    {
        $datas = $this->dao->select('activatedCount AS name, COUNT(*) AS value')->from(TABLE_BUG)->where($this->reportCondition())->groupBy('name')->orderBy('value DESC')->fetchAll('name');
        if(!$datas) return array();

        foreach($datas as $data) $data->name = $this->lang->bug->report->bugsPerActivatedCount->graph->xAxisName . ':' . $data->name;

        return $datas;
    }

    /**
     * 按照 bug 类型统计。
     * Statistics by bug type.
     *
     * @access public
     * @return array
     */
    public function getDataOfBugsPerType(): array
    {
        $datas = $this->dao->select('type AS name, COUNT(*) AS value')->from(TABLE_BUG)->where($this->reportCondition())->groupBy('name')->orderBy('value DESC')->fetchAll('name');
        if(!$datas) return array();

        foreach($datas as $type => $data) $data->name = zget($this->lang->bug->typeList, $type);

        return $datas;
    }

    /**
     * 按照 bug 指派给统计。
     * Statistics by bug assignment.
     *
     * @access public
     * @return array
     */
    public function getDataOfBugsPerAssignedTo(): array
    {
        $datas = $this->dao->select('assignedTo AS name, COUNT(*) AS value')->from(TABLE_BUG)->where($this->reportCondition())->groupBy('name')->orderBy('value DESC')->fetchAll('name');
        if(!$datas) return array();

        if(!isset($this->users)) $this->users = $this->loadModel('user')->getPairs('noletter');
        foreach($datas as $account => $data) $data->name = zget($this->users, $account);

        return $datas;
    }

    /**
     * 通过 sonarqube id 获取bug。
     * Get by Sonarqube id.
     *
     * @param  int         $sonarqubeID
     * @access public
     * @return array|false
     */
    public function getBySonarqubeID(int $sonarqubeID): array|bool
    {
        return $this->dao->select('issueKey')->from(TABLE_BUG)->where('issueKey')->like("$sonarqubeID:%")->fetchPairs();
    }

    /**
     * 获取bug查询语句。
     * Get bug query.
     *
     * @param  string $bugQuery
     * @access public
     * @return string
     */
    public function getBugQuery(string $bugQuery): string
    {
        /* 如果要查询所有产品，将当前用户可以看到的"`product` = 'all'" 换为自己可以看到所有的产品ID。 */
        /* If you need to query all products, replace "`product` = 'all'" with the products that the current user can see.. */
        $allProduct = "`product` = 'all'";
        if(strpos($bugQuery, $allProduct) !== false)
        {
            $products = $this->app->user->view->products;
            $bugQuery = str_replace($allProduct, '1', $bugQuery);
            $bugQuery = $bugQuery . ' AND `product` ' . helper::dbIN($products);
        }

        /* 如果要查询所有项目，将当前用户可以看到的"`project` = 'all'" 换为自己可以看到所有的项目ID。 */
        /* If you need to query all projects, replace "`project` = 'all'" with the projects that the current user can see.. */
        $allProject = "`project` = 'all'";
        if(strpos($bugQuery, $allProject) !== false)
        {
            $projects = $this->loadModel('project')->getPairs();
            if(is_array($projects)) $projectIdList = implode(',', array_keys($projects));
            $bugQuery = str_replace($allProject, '1', $bugQuery);
            $bugQuery = $bugQuery . ' AND `project` in (' . $projectIdList . ')';
        }

        /* 如果要依据解决日期搜索，解决日期应该不是'0000-00-00'。 */
        /* If you need to query by resolvedDate, resolvedDate shouldn't be '0000-00-00'. */
        if(strpos($bugQuery, ' `resolvedDate` ') !== false) $bugQuery = str_replace(' `resolvedDate` ', " `resolvedDate` != '0000-00-00 00:00:00' AND `resolvedDate` ", $bugQuery);
        /* 如果要依据关闭日期搜索，关闭日期应该不是'0000-00-00'。 */
        /* If you need to query by closedDate, closedDate shouldn't be '0000-00-00'. */
        if(strpos($bugQuery, ' `closedDate` ') !== false)   $bugQuery = str_replace(' `closedDate` ', " `closedDate` != '0000-00-00 00:00:00' AND `closedDate` ", $bugQuery);
        /* 如果要依据需求搜索，需求ID应该不是0。 */
        /* If you need to query by story, story shouldn't be zero. */
        if(strpos($bugQuery, ' `story` ') !== false)
        {
            /* 如果要搜索条件是包含或者不包含，应该从需求的标题、关键字、描述、期望中搜索。 */
            /* If query condition is include or notinclude, you should query from the title, keywords, spec, and verify of the story. */
            preg_match_all("/`story`[ ]+(NOT *)?LIKE[ ]+'%([^%]*)%'/Ui", $bugQuery, $out);
            if(!empty($out[2]))
            {
                foreach($out[2] as $searchValue)
                {
                    $story = $this->dao->select('id')->from(TABLE_STORY)->alias('t1')
                        ->leftJoin(TABLE_STORYSPEC)->alias('t2')->on('t1.id=t2.story')
                        ->where('t1.title')->like("%$searchValue%")
                        ->orWhere('t1.keywords')->like("%$searchValue%")
                        ->orWhere('t2.spec')->like("%$searchValue%")
                        ->orWhere('t2.verify')->like("%$searchValue%")
                        ->fetchPairs('id');
                    if(empty($story)) $story = array(0);

                    $bugQuery = preg_replace("/`story`[ ]+(NOT[ ]*)?LIKE[ ]+'%$searchValue%'/Ui", '`story` $1 IN (' . implode(',', $story) .')', $bugQuery);
                }
            }
            $bugQuery .= ' AND `story` != 0';
        }
        return $bugQuery;
    }

    /**
     * 获取产品 bugs。
     * Get product bugs.
     *
     * @param  array  $productIdList
     * @param  string $type
     * @param  string $begin
     * @param  string $end
     * @access public
     * @return array
     */
    public function getProductBugs(array $productIdList, string $type = '', string $begin = '', string $end = ''): array
    {
        return $this->dao->select('*')->from(TABLE_BUG)
            ->where('product')->in($productIdList)
            ->beginIF($type == 'resolved')->andWhere('resolvedDate')->ge($begin)->andWhere('resolvedDate')->le("{$end} 23:59:59")->fi()
            ->beginIF($type == 'opened')->andWhere('openedDate')->ge($begin)->andWhere('openedDate')->le("{$end} 23:59:59")->fi()
            ->andWhere('deleted')->eq(0)
            ->fetchAll();
    }

    /**
     * 获取重新激活的 bugs。
     * Get activated bugs.
     *
     * @param  array  $productIdList
     * @param  string $begin
     * @param  string $end
     * @param  array  $buildIdList
     * @access public
     * @return array
     */
    public function getActivatedBugs(array $productIdList, string $begin, string $end, array $buildIdList): array
    {
        /* Get all bugs in the builds of products. */
        $buildBugs = array();
        $allBugs   = $this->getProductBugs($productIdList);
        foreach($allBugs as $bug)
        {
            $intersect = array_intersect(explode(',', $bug->openedBuild), $buildIdList);
            if(!empty($intersect)) $buildBugs[$bug->id] = $bug;
        }

        /* Get bug reactivated actions during the testreport. */
        $actions = $this->dao->select('id,objectID')->from(TABLE_ACTION)
            ->where('objectType')->eq('bug')
            ->andWhere('action')->eq('activated')
            ->andWhere('date')->ge($begin)
            ->andWhere('date')->le($end . ' 23:59:59')
            ->andWhere('objectID')->in(array_keys($buildBugs))
            ->fetchPairs();
        $histories  = $this->loadModel('action')->getHistory(array_keys($actions));
        foreach($actions as $actionID => $bugID) $bugActions[$bugID][$actionID] = zget($histories, $actionID, array());

        /* Get reactivated bugs. */
        $activatedBugs = array();
        foreach($buildBugs as $bug)
        {
            if(empty($bugActions[$bug->id])) continue;
            foreach($bugActions[$bug->id] as $actionID => $histories)
            {
                foreach($histories as $history)
                {
                    if($history->field == 'openedBuild' && !in_array($history->new, $buildIdList)) continue;
                    $activatedBugs[$bug->id] = $bug;
                }
            }
        }
        return $activatedBugs;
    }

    /**
     * Get related objects id lists.
     *
     * @param  int    $object
     * @param  string $pairs
     * @access public
     * @return void
     */
    public function getRelatedObjects(string $object, string $pairs = ''): array
    {
        /* Get bugs. */
        $bugs = $this->loadModel('transfer')->getQueryDatas('bug');

        /* Get related objects id lists. */
        $relatedObjectIdList = array();
        $relatedObjects      = array();

        foreach($bugs as $bug)
        {
            if(is_numeric($bug->$object)) $relatedObjectIdList[$bug->$object] = $bug->$object;
        }

        if($object == 'openedBuild' or $object == 'resolvedBuild') $object = 'build';

        /* Get related objects title or names. */
        $table = $this->config->objectTables[$object];
        if($table) $relatedObjects = $this->dao->select($pairs)->from($table)->where('id')->in($relatedObjectIdList)->fetchPairs();

        if(in_array($object, array('build','resolvedBuild'))) $relatedObjects = array('trunk' => $this->lang->trunk) + $relatedObjects;
        return array('' => '', 0 => '') + $relatedObjects;
    }

    /**
     * 判断当前动作是否可以点击。
     * Adjust the action is clickable.
     *
     * @param  string $bug
     * @param  string $action
     * @param  string $module
     * @access public
     * @return bool
     */
    public static function isClickable(object $object, string $action, string $module = 'bug'): bool
    {
        $action = strtolower($action);

        /* 如果bug状态是激活，没有确认过，这个bug可以被确认。 */
        /* If the status is active, and the confirmed is 0, the bug can be confirm. */
        if($module == 'bug' && $action == 'confirm')  return $object->status == 'active' && $object->confirmed == 0;
        /* 如果bug不是关闭状态，这个bug可以被指派。 */
        /* If the status isn't closed, the bug can be assginTo. */
        if($module == 'bug' && $action == 'assignTo') return $object->status != 'closed';
        /* 如果bug是激活状态，这个bug可以被解决。 */
        /* If the status is active, the bug can be resolve. */
        if($module == 'bug' && $action == 'resolve')  return $object->status == 'active';
        /* 如果bug是解决状态，这个bug可以被关闭。 */
        /* If the status is resolved, the bug can be close. */
        if($module == 'bug' && $action == 'close')    return $object->status == 'resolved';
        /* 如果bug不是激活状态，这个bug可以被激活。 */
        /* If the status isn't active, the bug can be activate. */
        if($module == 'bug' && $action == 'activate') return $object->status != 'active';
        /* 如果bug是激活状态，这个bug可以被转为需求。 */
        /* If the status is active, the bug can be toStory. */
        if($module == 'bug' && $action == 'tostory')  return $object->status == 'active';

        return true;
    }

    /**
     * Get report condition from session.
     *
     * @access public
     * @return void
     */
    public function reportCondition()
    {
        if(isset($_SESSION['bugQueryCondition']))
        {
            if(!$this->session->bugOnlyCondition) return 'id in (' . preg_replace('/SELECT .* FROM/', 'SELECT t1.id FROM', $this->session->bugQueryCondition) . ')';
            return $this->session->bugQueryCondition;
        }
        return '1=1';
    }

    /**
     * Link bug to build and release
     *
     * @param  int        $bugID
     * @param  int|string $resolvedBuild
     * @access public
     * @return bool
     */
    public function linkBugToBuild(int $bugID, int|string $resolvedBuild): bool
    {
        /* 如果版本为空，或者版本为主干，返回。 */
        /* If resolved build is empty or resolved build is trunk, return true. */
        if(empty($resolvedBuild) || $resolvedBuild == 'trunk') return true;

        /* 获取版本信息，并且将bugs关联到版本。 */
        /* Get build information, and relate the bugs to the build. */
        $build     = $this->dao->select('id,product,bugs')->from(TABLE_BUILD)->where('id')->eq($resolvedBuild)->fetch();
        $buildBugs = $build->bugs . ',' . $bugID;
        $buildBugs = explode(',', trim($buildBugs, ','));
        $buildBugs = array_unique($buildBugs);
        $this->dao->update(TABLE_BUILD)->set('bugs')->eq(implode(',', $buildBugs))->where('id')->eq($resolvedBuild)->exec();

        /* 将bugs关联到版本关联的发布。 */
        /* Relate bugs to the build-related release. */
        $release = $this->dao->select('id,bugs')->from(TABLE_RELEASE)->where('product')->eq($build->product)->andWhere("(FIND_IN_SET('$resolvedBuild', build) or shadow = $resolvedBuild)")->andWhere('deleted')->eq('0')->fetch();
        if($release)
        {
            $releaseBugs = $release->bugs . ',' . $bugID;
            $releaseBugs = explode(',', trim($releaseBugs, ','));
            $releaseBugs = array_unique($releaseBugs);
            $this->dao->update(TABLE_RELEASE)->set('bugs')->eq(implode(',', $releaseBugs))->where('id')->eq($release->id)->exec();
        }

        return true;
    }

    /**
     * 更新相关 bug。
     * Update the related bug.
     *
     * @param  int    $bugID
     * @param  string $relatedBug
     * @param  string $oldRelatedBug
     * @access public
     * @return bool
     */
    public function updateRelatedBug(int $bugID, string $relatedBug, string $oldRelatedBug): bool
    {
        /* 获取需要修改的相关 bug。 */
        /* Get related bugs that need to change. */
        $relatedBugs        = explode(',', $relatedBug);
        $oldRelatedBugs     = explode(',', $oldRelatedBug);
        $addedRelatedBugs   = array_diff($relatedBugs, $oldRelatedBugs);
        $removedRelatedBugs = array_diff($oldRelatedBugs, $relatedBugs);
        $changedRelatedBugs = array_merge($addedRelatedBugs, $removedRelatedBugs);
        $changedRelatedBugs = $this->dao->select('id, relatedBug')->from(TABLE_BUG)->where('id')->in(array_filter($changedRelatedBugs))->fetchPairs();

        /* 更新相关 bug。 */
        /* Update the related bug. */
        foreach($changedRelatedBugs as $changedBugID => $relatedBugs)
        {
            if(in_array($changedBugID, $addedRelatedBugs))
            {
                $relatedBugs = explode(',', $relatedBugs);
                if(!empty($relatedBugs) && !in_array($bugID, $relatedBugs)) $relatedBugs[] = $bugID;
            }
            else
            {
                $relatedBugs = explode(',', $relatedBugs);
                unset($relatedBugs[array_search($bugID, $relatedBugs)]);
            }

            $currentRelatedBug = implode(',', array_filter($relatedBugs));

            $this->dao->update(TABLE_BUG)->set('relatedBug')->eq($currentRelatedBug)->where('id')->eq($changedBugID)->exec();
        }

        return !dao::isError();
    }

    /**
     * 获取指派用户和抄送给用户列表。
     * Get toList and ccList.
     *
     * @param  object      $bug
     * @access public
     * @return array|false
     */
    public function getToAndCcList(object $bug): array|false
    {
        /* Set toList and ccList. */
        $toList = $bug->assignedTo ? $bug->assignedTo : '';
        $ccList = trim((string)$bug->mailto, ',');
        if(empty($toList))
        {
            if(empty($ccList)) return false;
            if(strpos($ccList, ',') === false)
            {
                $toList = $ccList;
                $ccList = '';
            }
            else
            {
                $commaPos = strpos($ccList, ',');
                $toList = substr($ccList, 0, $commaPos);
                $ccList = substr($ccList, $commaPos + 1);
            }
        }
        elseif($bug->status == 'closed')
        {
            $ccList .= ',' . $bug->resolvedBy;
        }

        return array($toList, $ccList);
    }

    /**
     * Bug 列表底部的统计信息。
     * The statistic summary in table footer.
     *
     * @param  array  $bugs
     * @access public
     * @return string
     */
    public function summary(array $bugs): string
    {
        /* 获取为解决的bug数量。 */
        /* Get unresolved bug count. */
        $unresolved = 0;
        foreach($bugs as $bug)
        {
            if($bug->status != 'resolved' && $bug->status != 'closed') $unresolved ++;
        }

        /* 返回bug的统计信息。 */
        /* Return the statistics of the bugs. */
        return sprintf($this->lang->bug->notice->summary, count($bugs), $unresolved);
    }

    /**
     * 搜索 bug。
     * Search bugs.
     *
     * @param  string     $object
     * @param  array|int  $productIdList
     * @param  int|string $branch
     * @param  int        $projectID
     * @param  int        $executionID
     * @param  int        $queryID
     * @param  string     $excludeBugs
     * @param  string     $orderBy
     * @param  object     $pager
     * @access public
     * @return array
     */
    public function getBySearch(string $object = 'bug', array|int $productIdList = array(), int|string $branch = 0, int $projectID = 0, int $executionID = 0, int $queryID = 0, string $excludeBugs = '', string $orderBy = '', object $pager = null): array
    {
        $bugQuery = $this->processSearchQuery($object, $queryID, $productIdList, (string)$branch);

        return $this->dao->select("*, IF(`pri` = 0, {$this->config->maxPriValue}, `pri`) AS priOrder, IF(`severity` = 0, {$this->config->maxPriValue}, `severity`) AS severityOrder")->from(TABLE_BUG)
            ->where($bugQuery)
            ->andWhere('deleted')->eq('0')
            ->beginIF($excludeBugs)->andWhere('id')->notIN($excludeBugs)->fi()

            ->beginIF($object == 'bug' && !$this->app->user->admin)
            ->andWhere('project')->in('0,' . $this->app->user->view->projects)
            ->andWhere('execution')->in('0,' . $this->app->user->view->sprints)
            ->fi()

            ->beginIF($projectID)
            ->andWhere('project', true)->eq($projectID)
            ->fi()
            ->beginIF($projectID && $object == 'bug')
            ->orWhere('project')->eq(0)
            ->andWhere('openedBuild')->eq('trunk')
            ->fi()
            ->beginIF($projectID)
            ->markRight(1)
            ->fi()

            ->beginIF($object == 'execution')
            ->andWhere('execution')->eq($executionID)
            ->fi()

            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
    }
}
