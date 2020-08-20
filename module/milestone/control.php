<?php
class milestone extends control
{
    public function index($programID = 0, $projectID = 0, $productID = 0)
    {
        $this->loadModel('program');
        $this->loadModel('project');
        list($this->lang->modulePageNav, $projectID) = $this->milestone->getPageNav($programID, $projectID, $productID);

        $this->view->title = $this->lang->milestone->title;

        if(!$projectID)
        {
            $this->view->projectID = $projectID;
            $this->display();
            die;
        }

        $productID = $this->loadModel('product')->getProductIDByProject($projectID);
        $stageList = $this->loadModel('programplan')->getPairs($programID, $productID);
        unset($stageList[0]);
 
        $this->view->projectID      = $projectID;
        $this->view->programID      = $programID;
        $this->view->stageList      = $stageList;
        $this->view->basicInfo      = $this->milestone->getBasicInfo($programID, $projectID);
        $this->view->process        = $this->milestone->getProcess($programID, $projectID);
        $this->view->charts         = $this->milestone->getCharts($programID, $projectID);
        $this->view->productQuality = $this->milestone->getProductQuality($programID, $projectID);
        $this->view->workhours      = $this->milestone->getWorkhours($programID, $projectID);
        $this->view->measures       = $this->milestone->getMeasures($programID, $projectID);
        $this->view->projectRisk    = $this->milestone->getProjectRisk($programID);
        $this->view->users          = $this->loadModel('user')->getPairs('noclosed|noletter');
        $this->view->stageInfo      = $this->milestone->getStageDemand($programID, $projectID, $productID, $stageList);
        $this->view->otherproblems  = $this->milestone->otherProblemsList($programID, $projectID);
        $this->view->nextMilestone  = $this->milestone->getNextMilestone($programID, $projectID, $stageList);

        $this->display();
    }

    public function ajaxAddMeasures()
    {
        $data = fixer::input('post')->get();
        if(empty($data->projectID)) return 0;
        return $this->milestone->ajaxAddMeasures($data);
    }

    public function saveOtherProblem()
    {
        $re = $this->milestone->saveOtherProblem();
        $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));
    }

    public function ajaxSaveEstimate()
    {
        $taskID = $this->post->taskID;
        $estimate = $this->post->estimate; 
        $re = $this->milestone->ajaxSaveEstimate($taskID,$estimate);
        $this->send(array('result' => 'success','message' => $this->lang->saveSuccess));
    }
}
