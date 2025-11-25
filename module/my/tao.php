<?php
declare(strict_types=1);
/**
 * The tao file of my module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     my
 * @link        https://www.zentao.net
 */
class myTao extends myModel
{

    /**
     * 获取产品相关的由我指派的数据。
     * Get the data related to the product assigned by me.
     *
     * @param  array     $objectIdList
     * @param  string    $objectType
     * @param  string    $orderBy
     * @param  object    $pager
     * @access protected
     * @return array
     */
    protected function getProductRelatedAssignedByMe(array $objectIdList, string $objectType, string $module, string $orderBy, ?object $pager = null): array
    {
        $nameField  = $objectType == 'bug' ? 'productName' : 'productTitle';
        $orderBy    = strpos($orderBy, 'priOrder') !== false || strpos($orderBy, 'severityOrder') !== false || strpos($orderBy, $nameField) !== false ? $orderBy : "t1.{$orderBy}";
        $select     = "t1.*, t2.name AS {$nameField}, t2.shadow AS shadow, " . (strpos($orderBy, 'severity') !== false ? "IF(t1.`severity` = 0, {$this->config->maxPriValue}, t1.`severity`) AS severityOrder" : "IF(t1.`pri` = 0, {$this->config->maxPriValue}, t1.`pri`) AS priOrder");
        $objectList = $this->dao->select($select)->from($this->config->objectTables[$module])->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t1.id')->in($objectIdList)
            ->beginIF($module == 'story')->andWhere('t1.type')->eq($objectType)->fi()
            ->beginIF($module == 'story')->andWhere("FIND_IN_SET('{$this->config->vision}', t1.vision)")->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id', false);

        if($module == 'story')
        {
            $planList = array();
            foreach($objectList as $story) $planList[$story->plan] = $story->plan;
            $planPairs = $this->dao->select('id,title')->from(TABLE_PRODUCTPLAN)->where('id')->in($planList)->fetchPairs('id');
            foreach($objectList as $story) $story->planTitle = zget($planPairs, $story->plan, '');
        }
        return $objectList;
    }

    /**
     * 通过搜索查找任务。
     * Fetch tasks by search.
     *
     * @param  string    $query
     * @param  string    $moduleName
     * @param  string    $account
     * @param  array     $taskIdList
     * @param  string    $orderBy
     * @param  int       $limit
     * @param  object    $pager
     * @access protected
     * @return array
     */
    protected function fetchTasksBySearch(string $query, string $moduleName, string $account, array $taskIdList, string $orderBy, int $limit, ?object $pager = null): array
    {
        $query = preg_replace('/`(\w+)`/', 't1.`$1`', $query);
        $query = str_replace('t1.`project`', 't2.`project`', $query);

        $assignedToMatches   = array();
        $assignedToCondition = '';
        $operatorAndAccount  = '';
        if(strpos($query, '`assignedTo`') !== false)
        {
            preg_match("/`assignedTo`\s+(([^']*) ('([^']*)'))/", $query, $assignedToMatches);
            if(!empty($assignedToMatches))
            {
                $assignedToCondition = $assignedToMatches[0];
                $operatorAndAccount  = $assignedToMatches[1];
                $query = str_replace("t1.{$assignedToMatches[0]}", "(t1.{$assignedToCondition} or (t1.mode = 'multi' and t5.`account` {$operatorAndAccount} and t1.status != 'closed' and t5.status != 'done') )", $query);
            }
        }
        if(strpos($query, "t2.`project` = 'all'") !== false)   $query = str_replace("t2.`project` = 'all'", "t2.`project` != '0'", $query);
        if(strpos($query, "t2.`project` != 'all'") !== false)  $query = str_replace("t2.`project` != 'all'", "t2.`project` = '0'", $query);
        if(strpos($query, "t1.`execution` = 'all'") !== false) $query = str_replace("t1.`execution` = 'all'", "t1.`execution` != '0'", $query);

        $orderBy = str_replace('pri_', 'priOrder_', $orderBy);
        return $this->dao->select("t1.*, t4.id as project, t2.id as executionID, t2.name as executionName, t2.multiple as executionMultiple, t4.name as projectName, t2.type as executionType, t3.id as storyID, t3.title as storyTitle, t3.status AS storyStatus, t3.version AS latestStoryVersion, IF(t1.`pri` = 0, {$this->config->maxPriValue}, t1.`pri`) as priOrder")
            ->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on("t1.execution = t2.id")
            ->leftJoin(TABLE_STORY)->alias('t3')->on('t1.story = t3.id')
            ->leftJoin(TABLE_PROJECT)->alias('t4')->on("t2.project = t4.id")
            ->leftJoin(TABLE_TASKTEAM)->alias('t5')->on("t1.id = t5.task")
            ->where($query)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('(t2.status')->ne('suspended')->orWhere('t4.status')->ne('suspended')->markRight(1)
            ->beginIF($moduleName == 'workTask')
            ->beginIF(!empty($assignedToMatches))->andWhere("(t1.{$assignedToCondition} or (t1.mode = 'multi' and t5.`account` {$operatorAndAccount} and t1.status != 'closed' and t5.status != 'done') )")->fi()
            ->beginIF(empty($assignedToMatches))->andWhere("(t1.assignedTo = '{$account}' or (t1.mode = 'multi' and t5.`account` = '{$account}' and t1.status != 'closed' and t5.status != 'done') )")->fi()
            ->andWhere('t1.status')->notin('closed,cancel')
            ->fi()
            ->beginIF($moduleName == 'contributeTask')
            ->andWhere('t1.openedBy', 1)->eq($account)
            ->orWhere('t1.closedBy')->eq($account)
            ->orWhere('t1.canceledBy')->eq($account)
            ->orWhere('t1.finishedby')->eq($account)
            ->orWhere('t1.assignedTo')->eq($account)
            ->orWhere('t5.account')->eq($account)
            ->orWhere('t1.id')->in($taskIdList)
            ->markRight(1)
            ->fi()
            ->beginIF($this->config->vision)->andWhere('t1.vision')->eq($this->config->vision)->fi()
            ->beginIF($this->config->vision)->andWhere('t2.vision')->eq($this->config->vision)->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('t1.execution')->in($this->app->user->view->sprints)->fi()
            ->orderBy($orderBy)
            ->beginIF($limit > 0)->limit($limit)->fi()
            ->page($pager, 't1.id')
            ->fetchAll('id');
    }

    /**
     * 通过搜索查找需求。
     * Fetch stories by search.
     *
     * @param  string    $myStoryQuery
     * @param  string    $type
     * @param  string    $orderBy
     * @param  object    $pager
     * @param  array     $storiesAssignedByMe
     * @access protected
     * @return array
     */
    protected function fetchStoriesBySearch(string $myStoryQuery, string $type, string $orderBy, ?object $pager = null, array $storiesAssignedByMe = array()): array
    {
        if($type == 'contribute')
        {
            $storyIdList = !empty($storiesAssignedByMe) ? array_keys($storiesAssignedByMe) : array();
            $stories     = $this->dao->select("distinct t1.*, IF(t1.`pri` = 0, {$this->config->maxPriValue}, t1.`pri`) as priOrder, t2.name as productTitle, t2.shadow as shadow, t4.title as planTitle")->from(TABLE_STORY)->alias('t1')
                ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
                ->leftJoin(TABLE_PLANSTORY)->alias('t3')->on('t1.id = t3.plan')
                ->leftJoin(TABLE_PRODUCTPLAN)->alias('t4')->on('t3.plan = t4.id')
                ->leftJoin(TABLE_STORYREVIEW)->alias('t5')->on('t1.id = t5.story')
                ->where($myStoryQuery)
                ->andWhere('t1.type')->eq('story')
                ->andWhere('t1.openedBy',1)->eq($this->app->user->account)
                ->orWhere('t5.reviewer')->eq($this->app->user->account)
                ->orWhere('t1.closedBy')->eq($this->app->user->account)
                ->orWhere('t1.id')->in($storyIdList)
                ->markRight(1)
                ->andWhere('t1.deleted')->eq(0)
                ->orderBy($orderBy)
                ->page($pager, 't1.id')
                ->fetchAll('id');
        }
        else
        {
            $stories = $this->dao->select("distinct t1.*, IF(t1.`pri` = 0, {$this->config->maxPriValue}, t1.`pri`) as priOrder, t2.name as productTitle, t2.shadow as shadow, t4.title as planTitle")->from(TABLE_STORY)->alias('t1')
                ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
                ->leftJoin(TABLE_PLANSTORY)->alias('t3')->on('t1.id = t3.plan')
                ->leftJoin(TABLE_PRODUCTPLAN)->alias('t4')->on('t3.plan = t4.id')
                ->leftJoin(TABLE_STORYREVIEW)->alias('t5')->on('t1.id = t5.story AND t1.version=t5.version')
                ->where($myStoryQuery)
                ->andWhere('t1.type')->eq('story')
                ->andWhere('t1.assignedTo',1)->eq($this->app->user->account)
                ->orWhere("(t5.reviewer = '{$this->app->user->account}' AND t5.result = '' AND t1.status = 'reviewing')")
                ->markRight(1)
                ->andWhere('t1.product')->ne('0')
                ->andWhere('t1.deleted')->eq(0)
                ->orderBy($orderBy)
                ->page($pager, 't1.id')
                ->fetchAll('id');
        }
        return $stories;
    }

    /**
     * 通过搜索查找业务需求。
     * Fetch epics by search.
     *
     * @param  string    $myEpicQuery
     * @param  string    $type
     * @param  string    $orderBy
     * @param  object    $pager
     * @param  array     $epicIDList
     * @access protected
     * @return array
     */
    protected function fetchEpicsBySearch(string $myEpicQuery, string $type, string $orderBy, ?object $pager = null, array $epicIDList = array()): array
    {
         if($type == 'contribute')
         {
            $epics = $this->dao->select("distinct t1.*, IF(t1.`pri` = 0, {$this->config->maxPriValue}, t1.`pri`) as priOrder, t2.name as productTitle")->from(TABLE_STORY)->alias('t1')
                ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
                ->leftJoin(TABLE_STORYREVIEW)->alias('t3')->on('t1.id = t3.story')
                ->where($myEpicQuery)
                ->andWhere('t1.type')->eq('epic')
                ->andWhere('t1.openedBy',1)->eq($this->app->user->account)
                ->orWhere('t1.closedBy')->eq($this->app->user->account)
                ->orWhere('(t3.reviewer')->eq($this->app->user->account)
                ->andWhere('t3.result')->eq('')
                ->andWhere('t1.status')->eq('reviewing')
                ->markRight(1)
                ->orWhere('t1.id')->in($epicIDList)
                ->markRight(1)
                ->andWhere('t1.deleted')->eq(0)
                ->andWhere("FIND_IN_SET('{$this->config->vision}', t1.vision)")
                ->orderBy($orderBy)
                ->page($pager, 't1.id')
                ->fetchAll('id');
         }
         else
         {
             $epics = $this->dao->select("distinct t1.*, IF(t1.`pri` = 0, {$this->config->maxPriValue}, t1.`pri`) as priOrder, t2.name as productTitle")->from(TABLE_STORY)->alias('t1')
                ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
                ->leftJoin(TABLE_STORYREVIEW)->alias('t3')->on('t1.id = t3.story')
                ->where($myEpicQuery)
                ->andWhere('t1.type')->eq('epic')
                ->andWhere('t1.assignedTo',1)->eq($this->app->user->account)
                ->orWhere('(t3.reviewer')->eq($this->app->user->account)
                ->andWhere('t3.result')->eq('')
                ->andWhere('t1.status')->eq('reviewing')
                ->markRight(1)
                ->markRight(1)
                ->andWhere('t1.deleted')->eq(0)
                ->andWhere("FIND_IN_SET('{$this->config->vision}', t1.vision)")
                ->orderBy($orderBy)
                ->page($pager, 't1.id')
                ->fetchAll('id');
         }
         return $epics;
    }

    /**
     * 通过搜索查找用户需求。
     * Fetch requirements by search.
     *
     * @param  string    $myRequirementQuery
     * @param  string    $type
     * @param  string    $orderBy
     * @param  object    $pager
     * @param  array     $requirementIDList
     * @access protected
     * @return array
     */
    protected function fetchRequirementsBySearch(string $myRequirementQuery, string $type, string $orderBy, ?object $pager = null, array $requirementIDList = array()): array
    {
         if($type == 'contribute')
         {
            $requirements = $this->dao->select("distinct t1.*, IF(t1.`pri` = 0, {$this->config->maxPriValue}, t1.`pri`) as priOrder, t2.name as productTitle")->from(TABLE_STORY)->alias('t1')
                ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
                ->leftJoin(TABLE_STORYREVIEW)->alias('t3')->on('t1.id = t3.story')
                ->where($myRequirementQuery)
                ->andWhere('t1.type')->eq('requirement')
                ->andWhere('t1.openedBy',1)->eq($this->app->user->account)
                ->orWhere('t1.closedBy')->eq($this->app->user->account)
                ->orWhere('t3.reviewer')->eq($this->app->user->account)
                ->orWhere('t1.id')->in($requirementIDList)
                ->markRight(1)
                ->andWhere('t1.deleted')->eq(0)
                ->andWhere("FIND_IN_SET('{$this->config->vision}', t1.vision)")
                ->orderBy($orderBy)
                ->page($pager, 't1.id')
                ->fetchAll('id');
         }
         else
         {
             $requirements = $this->dao->select("distinct t1.*, IF(t1.`pri` = 0, {$this->config->maxPriValue}, t1.`pri`) as priOrder, t2.name as productTitle")->from(TABLE_STORY)->alias('t1')
                ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
                ->leftJoin(TABLE_STORYREVIEW)->alias('t3')->on('t1.id = t3.story')
                ->where($myRequirementQuery)
                ->andWhere('t1.type')->eq('requirement')
                ->andWhere('t1.assignedTo',1)->eq($this->app->user->account)
                ->orWhere('t3.reviewer')->eq($this->app->user->account)
                ->markRight(1)
                ->andWhere('t1.deleted')->eq(0)
                ->andWhere("FIND_IN_SET('{$this->config->vision}', t1.vision)")
                ->orderBy($orderBy)
                ->page($pager, 't1.id')
                ->fetchAll('id');
         }
         return $requirements;
    }

    /**
     * 构建待审批的审批数据。
     * Build the data of the approval to be approved.
     *
     * @param  array     $objectGroup
     * @param  array     $flows
     * @param  array     $objectNameFields
     * @access protected
     * @return array
     */
    protected function buildReviewingFlows(array $objectGroup, array $flows, array $objectNameFields): array
    {
        $approvalList = array();
        foreach($objectGroup as $objectType => $objects)
        {
            $title           = '';
            $titleFieldName  = zget($objectNameFields, $objectType, '');
            $openedDateField = zget($this->config->my->openedDateField, $objectType, 'openedDate');
            if(empty($titleFieldName) && isset($flows[$objectType]))
            {
                if(!empty($flows[$objectType]->titleField)) $titleFieldName = $flows[$objectType]->titleField;
                if(empty($flows[$objectType]->titleField)) $title = $flows[$objectType]->name;
                $openedDateField = 'createdDate';
            }

            foreach($objects as $object)
            {
                $data = new stdclass();
                $data->id      = $object->id;
                $data->title   = empty($titleFieldName) || !isset($object->$titleFieldName) ? $title . " #{$object->id}" : $object->{$titleFieldName};
                $data->type    = $objectType;
                $data->time    = $object->{$openedDateField};
                $data->status  = $objectType == 'charter' ? $object->reviewStatus : 'doing';
                $data->app     = isset($flows[$objectType]->app) ? $flows[$objectType]->app : zget($this->lang->navGroup, $objectType, '');
                $data->product = isset($object->product) ? $object->product : 0;
                $data->project = isset($object->project) ? $object->project : 0;
                $approvalList[] = $data;
            }
        }
        return $approvalList;
    }
}
