<?php
declare(strict_types=1);
class bugTao extends bugModel
{
    /**
     * Get bug details, including all contents of the TABLE_BUG, execution name, associated story name, associated story status, associated story version, associated task name, and associated plan name.
     * 获取bug的详情，包含bug表的所有内容、所属执行名称、关联需求名称、关联需求状态、关联需求版本、关联任务名称、关联计划名称
     *
     * @param  int   $bugID
     * @access protected
     * @return object|false
     */
    protected function fetchBugInfo(string $bugID): object|false
    {
        return $this->dao->select('t1.*, t2.name AS executionName, t3.title AS storyTitle, t3.status AS storyStatus, t3.version AS latestStoryVersion, t4.name AS taskName, t5.title AS planName')
            ->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.execution = t2.id')
            ->leftJoin(TABLE_STORY)->alias('t3')->on('t1.story = t3.id')
            ->leftJoin(TABLE_TASK)->alias('t4')->on('t1.task = t4.id')
            ->leftJoin(TABLE_PRODUCTPLAN)->alias('t5')->on('t1.plan = t5.id')
            ->where('t1.id')->eq((int)$bugID)->fetch();
    }

    /**
     * Get bug list by browse type.
     * 通过浏览类型获取bug列表。
     *
     * @param  string       $browseType
     * @param  int|array    $productIdList
     * @param  int|string   $branch
     * @param  int|array    $moduleIdList
     * @param  int[]        $executionIdList
     * @param  string       $orderBy
     * @param  object       $pager
     * @param  int          $projectID
     * @access protected
     * @return array
     */
    protected function getListByBrowseType(string $browseType, int|array $productIdList, int $projectID, array $executionIdList, int|string $branch, int|array $moduleIdList, string $orderBy, object $pager = null): array
    {
        $browseType            = strtolower($browseType);
        $lastEditedDate        = '';
        $bugIdListAssignedByMe = array();

        if($browseType == 'longlifebugs') $lastEditedDate = date(DT_DATE1, time() - $this->config->bug->longlife * 24 * 3600);
        if($browseType == 'assignedbyme')
        {
            $bugIdListAssignedByMe = $this->dao->select('objectID')->from(TABLE_ACTION)
                ->where('objectType')->eq('bug')
                ->andWhere('action')->eq('assigned')
                ->andWhere('actor')->eq($this->app->user->account)
                ->fetchPairs('objectID', 'objectID');
        }

        $bugList = $this->dao->select("*, IF(`pri` = 0, {$this->config->maxPriValue}, `pri`) as priOrder, IF(`severity` = 0, {$this->config->maxPriValue}, `severity`) as severityOrder")->from(TABLE_BUG)
            ->where('deleted')->eq('0')
            ->andWhere('product')->in($productIdList)
            ->beginIF($projectID)->andWhere('project')->eq($projectID)->fi()
            ->beginIF($this->app->tab !== 'qa')->andWhere('execution')->in($executionIdList)->fi()
            ->beginIF($branch !== 'all')->andWhere('branch')->in($branch)->fi()
            ->beginIF($moduleIdList)->andWhere('module')->in($moduleIdList)->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('project')->in('0,' . $this->app->user->view->projects)->fi()

            ->beginIF($browseType == 'assigntome')->andWhere('assignedTo')->eq($this->app->user->account)->fi()
            ->beginIF($browseType == 'openedbyme')->andWhere('openedBy')->eq($this->app->user->account)->fi()
            ->beginIF($browseType == 'resolvedbyme')->andWhere('resolvedBy')->eq($this->app->user->account)->fi()
            ->beginIF($browseType == 'assigntonull')->andWhere('assignedTo')->eq('')->fi()
            ->beginIF($browseType == 'unconfirmed')->andWhere('confirmed')->eq(0)->fi()
            ->beginIF($browseType == 'unclosed')->andWhere('status')->ne('closed')->fi()
            ->beginIF($browseType == 'unresolved')->andWhere('status')->eq('active')->fi()
            ->beginIF($browseType == 'toclosed')->andWhere('status')->eq('resolved')->fi()
            ->beginIF($browseType == 'postponedbugs')->andWhere('resolution')->eq('postponed')->fi()
            ->beginIF($browseType == 'review')->andWhere("FIND_IN_SET('{$this->app->user->account}', reviewers)")->fi()

            ->beginIF($browseType == 'longlifebugs')
            ->andWhere('lastEditedDate')->lt($lastEditedDate)
            ->andWhere('openedDate')->lt($lastEditedDate)
            ->andWhere('status')->ne('closed')
            ->fi()

            ->beginIF($browseType == 'overduebugs')
            ->andWhere('status')->eq('active')
            ->andWhere('deadline')->lt(helper::today())
            ->fi()

            ->beginIF($browseType == 'assignedbyme')
            ->andWhere('status')->ne('closed')
            ->andWhere('id')->in($bugIdListAssignedByMe)
            ->fi()

            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'bug');
        return $bugList;
    }

    /**
     * Get bug list of story need confirm.
     * 获取需要确认需求变动的bug列表。
     *
     * @param  int|array  $productIdList
     * @param  int        $projectID
     * @param  int[]      $executionIdList
     * @param  int|string $branch
     * @param  int|array  $moduleIdList
     * @param  string     $orderBy
     * @param  object     $pager
     * @access protected
     * @return array
     */
    protected function getListByNeedconfirm(int|array $productIdList, int $projectID, array $executionIdList, int|string $branch, int|array $moduleIdList, string $orderBy, object $pager = null): array
    {
        return $this->dao->select("t1.*, t2.title AS storyTitle, IF(t1.`pri` = 0, {$this->config->maxPriValue}, t1.`pri`) as priOrder, IF(t1.`severity` = 0, {$this->config->maxPriValue}, t1.`severity`) as severityOrder")->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->where('t1.deleted')->eq('0')
            ->andWhere("t2.status = 'active'")
            ->andWhere('t2.version > t1.storyVersion')
            ->andWhere('t1.product')->in($productIdList)
            ->beginIF($projectID)->andWhere('t1.project')->eq($projectID)->fi()
            ->beginIF($this->app->tab !== 'qa')->andWhere('t1.execution')->in($executionIdList)->fi()
            ->beginIF($branch !== 'all')->andWhere('t1.branch')->in($branch)->fi()
            ->beginIF($moduleIdList)->andWhere('t1.module')->in($moduleIdList)->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('t1.project')->in('0,' . $this->app->user->view->projects)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get cases created by bug.
     * 获取bug建的用例。
     *
     * @param  int    $bugID
     * @access protected
     * @return array
     */
    protected function getCasesFromBug(string $bugID): array
    {
        return $this->dao->select('id, title')->from(TABLE_CASE)->where('`fromBug`')->eq($bugID)->fetchPairs();
    }

    /**
     * Get an array of id and title pairs by buglist.
     * 传入一个buglist，获得bug的id和title键值对数组。
     *
     * @param  string|array $bugList
     * @access protected
     * @return array
     */
    protected function getBugPairsByList(string|array $bugList): array
    {
        return $this->dao->select('id,title')->from(TABLE_BUG)->where('id')->in($bugList)->fetchPairs();
    }

    /**
     * Get object title/name base on the params.
     * 根据传入的参数，获取对象名称。
     *
     * @param  int    $objectID
     * @param  string $table
     * @param  string $field
     * @access protected
     * @return string
     */
    protected function getNameFromTable(int $objectID, string $table, string $field): string
    {
        return $this->dao->findById($objectID)->from($table)->fields($field)->fetch($field);
    }

    /**
     * 更新完bug后的相关处理。
     * Relevant processing after updating bug.
     *
     * @param  object $bug
     * @param  object $oldBug
     * @access protected
     * @return void
     */
    protected function afterUpdate(object $bug, object $oldBug)
    {
        /* 解除旧的版本关联关系，关联新的版本。*/
        /* Link bug to build and release. */
        if($bug->resolution == 'fixed' && !empty($bug->resolvedBuild) && $bug->resolvedBuild != $oldBug->resolvedBuild)
        {
            if(!empty($oldBug->resolvedBuild)) $this->loadModel('build')->unlinkBug($oldBug->resolvedBuild, $bug->id);
            $this->linkBugToBuild($bug->id, $bug->resolvedBuild);
        }

        /* 解除旧的计划关联关系，关联新的计划。*/
        /* Link new plan, unlink old plan. */
        if($bug->plan != $oldBug->plan)
        {
            $this->loadModel('action');
            if(!empty($oldBug->plan)) $this->action->create('productplan', $oldBug->plan, 'unlinkbug', '', $bug->id);
            if(!empty($bug->plan))    $this->action->create('productplan', $bug->plan, 'linkbug', '', $bug->id);
        }

        $this->updateLinkBug($bug->id, $bug->linkBug, $oldBug->linkBug);

        /* 给bug解决者积分奖励。*/
        /* Add score to resolvedby. */
        if(!empty($bug->resolvedBy)) $this->loadModel('score')->create('bug', 'resolve', $bug->id);

        /* 更新bug所属看板的泳道。*/
        /* Update the lane of the bug kanban. */
        if($bug->execution and $bug->status != $oldBug->status) $this->loadModel('kanban')->updateLane($bug->execution, 'bug');

        /* 更新反馈的状态。*/
        /* Update the status of feedback. */
        if(($this->config->edition != 'open') && $oldBug->feedback) $this->loadModel('feedback')->updateStatus('bug', $oldBug->feedback, $bug->status, $oldBug->status);

        /* 更新bug的附件。*/
        /* Update the files of bug. */
        $this->loadModel('file')->processFile4Object('bug', $oldBug, $bug);

        return !dao::isError();
    }

    /**
     * 更新相关bug。
     * Update the linked bug.
     *
     * @param  string $bugID
     * @param  string $linkBug
     * @param  string $oldLinkBug
     * @access protected
     * @return bool
     */
    protected function updateLinkBug(string $bugID, string $linkBug, string $oldLinkBug): bool
    {
        $linkBugs        = explode(',', $linkBug);
        $oldLinkBugs     = explode(',', $oldLinkBug);
        $addedLinkBugs   = array_diff($linkBugs, $oldLinkBugs);
        $removedLinkBugs = array_diff($oldLinkBugs, $linkBugs);
        $changedLinkBugs = array_merge($addedLinkBugs, $removedLinkBugs);
        $changedLinkBugs = $this->dao->select('id, linkbug')->from(TABLE_BUG)->where('id')->in(array_filter($changedLinkBugs))->fetchPairs();

        foreach($changedLinkBugs as $changedBugID => $linkBugs)
        {
            if(in_array($changedBugID, $addedLinkBugs))
            {
                $linkBugs = explode(',', $linkBugs);
                if(!empty($linkBugs) && !in_array($bugID, $linkBugs)) $linkBugs[] = $bugID;
            }
            else
            {
                $linkBugs = explode(',', $linkBugs);
                unset($linkBugs[array_search($bugID, $linkBugs)]);
            }

            $currentLinkBug = implode(',', array_filter($linkBugs));

            $this->dao->update(TABLE_BUG)->set('linkBug')->eq($currentLinkBug)->where('id')->eq($changedBugID)->exec();
        }

        return !dao::isError();
    }

    /**
     * Call checkDelayBug in foreach to check if the bug is delay.
     * 循环调用checkDelayBug，检查bug是否延期
     *
     * @param  array  $bugs
     * @access protected
     * @return object[]
     */
    protected function batchAppendDelayedDays(array $bugs): array
    {
        foreach($bugs as $bug) $this->appendDelayedDays($bug);

        return $bugs;
    }

    /**
     * If the bug is delayed, add the bug->delay field to show the delay time (day).
     * 添加bug->delay字段，内容为延期的时长（天），不延期则为0
     *
     * @param  object $bug
     * @access protected
     * @return object
     */
    protected function appendDelayedDays(object $bug): object
    {
        if(helper::isZeroDate($bug->deadline)) return $bug;

        $delay = 0;
        if($bug->resolvedDate and !helper::isZeroDate($bug->resolvedDate))
        {
            $delay = helper::diffDate(substr($bug->resolvedDate, 0, 10), $bug->deadline);
        }
        elseif($bug->status == 'active')
        {
            $delay = helper::diffDate(helper::today(), $bug->deadline);
        }

        if($delay > 0) $bug->delay = $delay;

        return $bug;
    }

    /**
     * 关闭bug后，更新看板的状态。
     * Update kanban status after close bug.
     *
     * @param  object $bug
     * @param  object $oldBug
     * @access protected
     * @return array
     */
    protected function updateKanbanAfterClose(object $bug, object $oldBug):array
    {
        $extra  = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);
        if($oldBug->execution)
        {
            $this->loadModel('kanban');
            if(!isset($output['toColID'])) $this->kanban->updateLane($oldBug->execution, 'bug', $bug->id);
            if(isset($output['toColID'])) $this->kanban->moveCard($bug->id, $output['fromColID'], $output['toColID'], $output['fromLaneID'], $output['toLaneID']);
        }

        return array($bug, $oldBug);
    }

    /**
     * 关闭bug后，更新动态
     * Update action after close bug.
     *
     * @param  object $bug
     * @param  object $oldBug
     * @access protected
     * @return array
     */
    protected function updateActionAfterClose(object $bug, object $oldBug):array
    {
        if(($this->config->edition == 'biz' || $this->config->edition == 'max') && $oldBug->feedback) $this->loadModel('feedback')->updateStatus('bug', $oldBug->feedback, $bug->status, $oldBug->status);

        $this->loadModel('action');
        $changes  = common::createChanges($oldBug, $bug);
        $actionID = $this->action->create('bug', $bug->id, 'Closed', $bug->comment);
        $this->action->logHistory($actionID, $changes);

        return array($bug, $oldBug);
    }
}
