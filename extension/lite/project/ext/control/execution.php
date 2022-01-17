<?php
class project extends control
{
    public function execution($status = 'all', $projectID = 0, $orderBy = 'order_asc', $productID = 0, $recTotal = 0, $recPerPage = 10, $pageID = 1)
    {
        $this->project->setMenu($projectID);
        $this->app->loadLang('execution');

        $this->view->projectID   = $projectID;
        $this->view->status      = $status;
        $this->view->kanbans     = $this->loadModel('kanban')->getKanbanByProject($projectID, $status == 'doing' ? 'active' : $status);
        $this->view->usersAvatar = $this->loadModel('user')->getAvatarPairs();
        $this->display();
    }
}
