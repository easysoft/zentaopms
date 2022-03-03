<?php
helper::importControl('tree');
class myTree extends tree
{
    /**
     * Module browse.
     *
     * @param  int    $rootID
     * @param  string $view story|bug|case|doc
     * @param  int    $currentModuleID
     * @param  int    $branch
     * @param  string $from
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function browse($rootID, $view, $currentModuleID = 0, $branch = 0, $from = '', $projectID = 0)
    {
        $this->view->projectID   = $projectID;
        $this->view->projectName = $this->dao->findById($projectID)->from(TABLE_PROJECT)->fetch('name');
        return parent::browse($rootID, $view, $currentModuleID, $branch, $from);
    }
}
