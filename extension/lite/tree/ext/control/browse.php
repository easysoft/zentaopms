<?php
helper::importControl('tree');
class myTree extends tree
{
    public function browse($rootID, $view, $currentModuleID = 0, $branch = 0, $from = '', $projectID = 0)
    {
        $this->view->projectID   = $projectID;
        $this->view->projectName = $this->dao->findById($projectID)->from(TABLE_PROJECT)->fetch('name');
        return parent::browse($rootID, $view, $currentModuleID, $branch, $from);
    }
}
