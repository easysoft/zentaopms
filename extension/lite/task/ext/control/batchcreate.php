<?php
helper::importControl('task');
class myTask extends task
{
    public function batchCreate($executionID = 0, $storyID = 0, $moduleID = 0, $taskID = 0, $iframe = 0, $extra = '')
    {
        $executions = $this->execution->getPairs();
        if(empty($executions))
        {
            echo(js::alert($this->lang->task->kanbanDenied));
            die(js::locate(helper::createLink('execution', 'create')));
        }

        $regionList = $this->loadModel('kanban')->getRegionPairs($executionID, 0, 'execution');

        /* Filter Kanban without Lane. */
        $lanes = $this->kanban->getLaneGroupByRegion(array_keys($regionList), 'task');
        foreach($regionList as $key => $region)
        {
            if(!isset($lanes[$key])) unset($regionList[$key]);
        }

        $this->view->regionList = $regionList;
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

        $this->config->execution->task->allModule = 1;
        $this->view->lanes = $lanes;

        if(!empty($_POST))
        {
            $laneIDList = $_POST['lane'];
            foreach($laneIDList as $key => $lane)
            {
                $_POST['column'][$key] = $this->dao->select("t1.id")->from(TABLE_KANBANCOLUMN)->alias('t1')
                    ->leftJoin(TABLE_KANBANLANE)->alias('t2')
                    ->on("t2.group = t1.group")
                    ->where('t1.deleted')->eq('0')
                    ->andWhere('t2.id')->eq($lane)
                    ->andWhere('t1.type')->eq('wait')
                    ->fetch('id');
            }
        }
        return parent::batchCreate($executionID, $storyID, $moduleID, $taskID, $iframe, $extra);
    }
}
