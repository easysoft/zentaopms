<?php
helper::importControl('execution');
class myExecution extends execution
{
    public function kanban($executionID, $browseType = 'all', $orderBy = 'id_asc', $groupBy = 'default')
    {
        $execution = $this->execution->getById($executionID);
        $this->loadModel('project')->setMenu($execution->project);

        parent::kanban($executionID, $browseType, $orderBy, $groupBy);
    }
}
