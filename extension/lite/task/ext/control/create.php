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
        $this->view->laneList   = array('', '');

        return parent::create($executionID, $storyID, $moduleID, $taskID, $todoID, $extra);
    }
}
