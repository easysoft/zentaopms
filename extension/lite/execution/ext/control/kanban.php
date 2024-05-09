<?php
helper::importControl('execution');
class myExecution extends execution
{
    public function kanban($executionID, $browseType = 'all', $orderBy = 'id_asc', $groupBy = 'default')
    {
        $this->app->loadLang('kanban');
        common::setMenuVars('execution', $executionID);

        $currentMethod = $this->app->methodName;
        $execution     = $this->execution->getById($executionID);
        $this->loadModel('project')->setMenu($execution->project);

        $browseType = 'task';
        parent::kanban($executionID, $browseType, $orderBy, $groupBy);
    }
}
