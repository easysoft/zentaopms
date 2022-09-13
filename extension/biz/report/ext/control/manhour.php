<?php
helper::importControl('report');
class myReport extends report
{
    public function manhour()
    {
        $this->view->title = $this->lang->manhour->tableName;
        $projectList = $this->report->getProjectPairList();;
        $this->view->projectList = $projectList;
        $this->display();
    }
}