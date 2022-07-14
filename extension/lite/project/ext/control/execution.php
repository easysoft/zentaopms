<?php
class project extends control
{
    public function execution($status = 'all', $projectID = 0, $orderBy = 'order_asc', $productID = 0, $recTotal = 0, $recPerPage = 10, $pageID = 1)
    {
        $projectID = $this->project->saveState($projectID, $this->project->getPairsByProgram());
        if($projectID == 0 and common::hasPriv('project', 'create')) $this->locate($this->createLink('project', 'create'));
        if($projectID == 0 and !common::hasPriv('project', 'create')) $this->locate($this->createLink('project', 'browse'));

        $this->project->setMenu($projectID);

        $kanbanList = $this->loadModel('execution')->getList($projectID, 'all', $status);

        $executionActions = array();
        foreach($kanbanList as $kanbanID => $kanban)
        {
            foreach($this->config->execution->statusActions as $action)
            {
                if($this->execution->isClickable($kanban, $action)) $executionActions[$kanbanID][] = $action;
            }
            if($this->execution->isClickable($kanban, 'delete')) $executionActions[$kanbanID][] = 'delete';
        }

        $allExecution = $this->execution->getList($projectID, 'all', 'all');
        $this->view->allExecutionsNum = empty($allExecution);

        $this->view->title            = $this->lang->project->kanban;

        $this->view->kanbanList       = array_values($kanbanList);
        $this->view->memberGroup      = $this->execution->getMembersByIdList(array_keys($kanbanList));
        $this->view->users            = $this->loadModel('user')->getPairs('noclosed|nodeleted');
        $this->view->usersAvatar      = $this->user->getAvatarPairs();
        $this->view->projectID        = $projectID;
        $this->view->status           = $status;
        $this->view->executionActions = $executionActions;

        $this->display();
    }
}
