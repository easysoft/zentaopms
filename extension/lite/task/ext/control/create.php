<?php
helper::importControl('task');
class myTask extends task
{
    /**
     * 创建一个任务。
     * Create a task.
     *
     * @param  int    $executionID
     * @param  int    $storyID
     * @param  int    $moduleID
     * @param  int    $taskID
     * @param  int    $todoID
     * @param  string $extra
     * @param  int    $bugID
     * @access public
     * @return void
     */
    public function create(int $executionID = 0, int $storyID = 0, int $moduleID = 0, int $taskID = 0, int $todoID = 0, string $extra = '', int $bugID = 0)
    {
        $executions = $this->execution->getPairs();
        if(empty($executions)) $this->sendError($this->lang->task->kanbanDenied, helper::createLink('execution', 'create'));

        $executionID = $this->execution->checkAccess($executionID, $executions);
        $regionList  = $this->loadModel('kanban')->getRegionPairs($executionID, 0, 'execution');

        /* Filter Kanban without Lane. */
        $lanes = $this->kanban->getLaneGroupByRegion(array_keys($regionList), 'task');
        foreach($regionList as $key => $region)
        {
            if(!isset($lanes[$key])) unset($regionList[$key]);
        }

        $laneList = array();
        $regionID = key($lanes);
        if($regionID) $laneList = $this->kanban->getLanePairsByRegion($regionID, 'task');

        $this->view->regionList = $regionList;
        $this->view->laneList   = $laneList;
        $this->view->extra      = $extra;
        $extra = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);

        if(isset($output['laneID']))
        {
            $regionID = $this->dao->select("*")->from(TABLE_KANBANLANE)->where('id')->eq($output['laneID'])->fetch('region');
            $this->view->regionID = $regionID;
        }

        if(isset($_POST['region']))
        {
            $regionID = $_POST['region'];
            $laneID   = $_POST['otherLane'];
            $columnID =  $this->dao->select("t1.id")->from(TABLE_KANBANCOLUMN)->alias('t1')
                ->leftJoin(TABLE_KANBANLANE)->alias('t2')
                ->on("t2.group = t1.group")
                ->where('t1.deleted')->eq('0')
                ->andWhere('t2.id')->eq($laneID)
                ->andWhere('t1.type')->eq('wait')
                ->fetch('id');

            $extra = "laneID=$laneID,columnID=$columnID";
        }
        return parent::create($executionID, $storyID, $moduleID, $taskID, $todoID, $extra, $bugID);
    }
}
