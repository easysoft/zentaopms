<?php

class manhourReport extends reportModel
{
    /**
     * Get project pair array [name,id]
     * @param $status
     * @param $orderBy
     * @param $pager
     * @param $involved
     * @return array
     */
    public function getProjectPairList($status = 'all', $orderBy = 'order_desc', $pager = null, $involved = 0)
    {
        $projects = $this->loadModel('project')->getInfoList($status, $orderBy, $pager, $involved);
        $result = array();
        foreach ($projects as $item) {
            $key = $item->id;
            $val = $item->name;
            $result[$key] = $val;
        }
        return $result;
    }

    /**
     * Get Project Consume Information by Project ID
     * @param $projectId
     * @param $beginDate
     * @param $endDate
     * @return array
     */
    public function getProjectConsumeInfoById($projectId, $beginDate, $endDate)
    {
        // select project,account,date,sum(consumed) as consumed from
        //   zt_taskestimate as t1 left join zt_task as t2 on t1.task=t2.id
        // where project=${projectId}
        // group by account,date

        return $this->dao->select('t2.project,t1.account,t3.realname,t3.nickname,t1.date,sum(t1.consumed) as consumed , count(t1.id) as mergedRow')
            ->from(TABLE_TASKESTIMATE)->alias('t1')->leftJoin(TABLE_TASK)->alias('t2')->on('t1.task=t2.id')
            ->leftJoin(TABLE_USER)->alias('t3')->on('t1.account=t3.account')
            ->where('project')->eq($projectId)
            ->andWhere('date')->between($beginDate, $endDate)
            ->groupBy('account,date')
            ->fetchGroup('date');
    }

    /**
     * Get Project Consume Information by Project ID List
     * @param $projectIdList
     * @param $beginDate
     * @param $endDate
     * @return array
     */
    public function getProjectConsumeInfoByIdList($projectIdList, $beginDate, $endDate)
    {
        $result = array();
        $projectNameMap = $this->getProjectPairList();
        for ($i = 0; $i < count($projectIdList); $i++) {
            $projectInfo = new stdclass();
            $projectInfo->projectId = $projectIdList[$i];
            $projectInfo->projectName = $projectNameMap[$projectIdList[$i]];
            $projectInfo->consumeInfo = $this->getProjectConsumeInfoById($projectIdList[$i], $beginDate, $endDate);
            if (count($projectInfo->consumeInfo) > 0) {
                $projectInfo->users = $this->getUsers($projectInfo->consumeInfo);
                $result[$i] = $projectInfo;
            }
        }
        return $result;
    }

    /**
     * Get Project Consume Information for all the Projects
     * @param $beginDate
     * @param $endDate
     * @return array
     */
    public function getAllProjectConsumeInfo($beginDate, $endDate)
    {
        $result = array();
        $projectNameMap = $this->getProjectPairList();
        $i = 0;
        foreach ($projectNameMap as $key => $val) {
            $projectInfo = new stdclass();
            $projectInfo->projectId = $key;
            $projectInfo->projectName = $val;
            $projectInfo->consumeInfo = $this->getProjectConsumeInfoById($key, $beginDate, $endDate);
            if (count($projectInfo->consumeInfo) > 0) {
                $projectInfo->users = $this->getUsers($projectInfo->consumeInfo);
                $result[$i++] = $projectInfo;
            }
        }
        return $result;
    }

    /**
     * Extract Users Set from Consume Information
     * @param $consumeInfo
     * @return array
     */
    public function getUsers($consumeInfo)
    {
        $users = array();
        $i=0;
        foreach ($consumeInfo as $infos){
            foreach($infos as $val){
                $users[$i++] = $val->realname;
            }
        }
        $i = 0;
        $res = array();
        foreach (array_unique($users) as $item){
            $res[$i++] = $item;
        }
        return $res;
    }
    /**
     * Get project info.
     *
     * @param  string    $status
     * @param  string    $orderBy
     * @param  int       $pager
     * @param  int       $involved
     * @access public
     * @return array
     */
    public function getInfoList($status = 'undone', $orderBy = 'order_desc', $pager = null, $involved = 0)
    {
        /* Init vars. */
        $projects = $this->loadModel('program')->getProjectList(0, $status, 0, $orderBy, $pager, 0, $involved);
        if(empty($projects)) return array();

        $projectIdList = array_keys($projects);
        $teams = $this->dao->select('t1.root, count(t1.id) as count')->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account=t2.account')
            ->where('t1.root')->in($projectIdList)
            ->andWhere('t2.deleted')->eq(0)
            ->groupBy('t1.root')
            ->fetchAll('root');

        $condition = $this->config->systemMode == 'classic' ? 't2.id as project' : 't2.parent as project';
        $estimates = $this->dao->select("$condition, sum(estimate) as estimate")->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.execution = t2.id')
            ->where('t1.parent')->lt(1)
            ->beginIF($this->config->systemMode == 'new')->andWhere('t2.parent')->in($projectIdList)->fi()
            ->beginIF($this->config->systemMode == 'classic')->andWhere('t2.id')->in($projectIdList)->fi()
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->groupBy('project')
            ->fetchAll('project');

        $this->app->loadClass('pager', $static = true);
        foreach($projects as $projectID => $project)
        {
            $orderBy = $project->model == 'waterfall' ? 'id_asc' : 'id_desc';
            $pager   = $project->model == 'waterfall' ? null : new pager(0, 1, 1);
            $project->executions = $this->loadModel('execution')->getStatData($projectID, 'undone', 0, 0, false, '', $orderBy, $pager);
            $project->teamCount  = isset($teams[$projectID]) ? $teams[$projectID]->count : 0;
            $project->estimate   = isset($estimates[$projectID]) ? round($estimates[$projectID]->estimate, 2) : 0;
            $project->parentName = $this->getParentProgram($project);
        }
        return $projects;
    }
}
