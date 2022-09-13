<?php
helper::importControl('report');
class myReport extends report
{
    public function projectlist()
    {
        $projectList = $this->loadModel('program')->getProjectList();
//        $projectList = $this->manhour->getProjectPairList();
        echo json_encode($projectList);
    }
}