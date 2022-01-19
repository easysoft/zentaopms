<?php
helper::importControl('execution');
class myExecution extends execution
{
    public function all($status = 'all', $projectID = 0, $orderBy = 'order_asc', $productID = 0, $recTotal = 0, $recPerPage = 10, $pageID = 1)
    {
        $this->app->loadLang('my');
        $this->app->loadLang('product');
        $this->app->loadLang('stage');
        $this->app->loadLang('programplan');

        $from = $this->app->tab;
        if($from == 'execution') $this->session->set('executionList', $this->app->getURI(true), 'execution');

        if($from == 'project')
        {
            $projects  = $this->project->getPairsByProgram();
            $projectID = $this->project->saveState($projectID, $projects);
            $project = $this->loadModel('project')->getByID($projectID);
            $this->view->project = $project;
            $this->project->setMenu($projectID);
        }

        if($this->app->viewType == 'mhtml')
        {
            if($this->app->rawModule == 'project' and $this->app->rawMethod == 'execution')
            {
                $projects  = $this->project->getPairsByProgram();
                $projectID = $this->project->saveState($projectID, $projects);
                $this->project->setMenu($projectID);
            }
            else
            {
                $executionID = $this->execution->saveState(0, $this->executions);
                $this->execution->setMenu($executionID);
            }
        }

        /* Load pager and get tasks. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->title      = $this->lang->execution->allExecutions;
        $this->view->position[] = $this->lang->execution->allExecutions;

        $executionStats = $this->project->getStats($projectID, $status, $productID, 0, 30, $orderBy, $pager);
        $executionTeams = array();
        foreach($executionStats as $execution) $executionTeams[$execution->id] = $this->execution->getTeamMembers($execution->id);

        $this->view->executionStats = $executionStats;
        $this->view->executionTeams = $executionTeams;
        $this->view->productList    = $this->loadModel('product')->getProductPairsByProject($projectID);
        $this->view->productID      = $productID;
        $this->view->projectID      = $projectID;
        $this->view->projects       = array('') + $this->project->getPairsByProgram();
        $this->view->pager          = $pager;
        $this->view->orderBy        = $orderBy;
        $this->view->users          = $this->loadModel('user')->getPairs('noletter');
        $this->view->status         = $status;
        $this->view->from           = $from;
        $this->view->isStage        = (isset($project->model) and $project->model == 'waterfall') ? true : false;
        $this->view->users          = $this->loadModel('user')->getPairs('noletter|nodeleted');
        $this->view->usersAvatar    = $this->user->getAvatarPairs();
        $this->display();
    }
}
