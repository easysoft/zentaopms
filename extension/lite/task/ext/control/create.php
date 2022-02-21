<?php
helper::importControl('task');
class myTask extends task
{
    public function create($executionID = 0, $storyID = 0, $moduleID = 0, $taskID = 0, $todoID = 0, $extra = '')
    {
        $executions = $this->execution->getPairs();
        if(empty($executions))
        {
            echo(js::alert($this->lang->task->kanbanDenied));
            die(js::locate(helper::createLink('execution', 'create')));
        }

        $regionList = $this->loadModel('kanban')->getRegionPairs($executionID, 0, 'execution');

        $this->view->regionList = $regionList;
        $this->view->laneList   = array();
        $this->view->extra      = $extra;
        $extra = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);
        $lanes = array();

        if(isset($output['laneID']))
        {
            $regionID = $this->dao->select("*")->from(TABLE_KANBANLANE)->where('id')->eq($output['laneID'])->fetch('region');
            $lanes    = $this->kanban->getLanePairsByRegion($regionID, 'task');
            $this->view->regionID = $regionID;
        }

        $this->view->lanes = $lanes;

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
        return parent::create($executionID, $storyID, $moduleID, $taskID, $todoID, $extra);
    }
}
