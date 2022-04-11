<?php
class personnelTest
{
    public function __construct($user)
    {
        global $tester;
        su($user);
        $this->objectModel = $tester->loadModel('personnel');
        $tester->app->loadClass('dao');
    }
    /**
     * Get accessible personnel test
     *
     * @param int $programID
     * @param int $deptID
     * @param string $browseType
     * @param int $queryID
     * @access public
     * @return array
     */
    public function getAccessiblePersonnelTest($programID = 0, $deptID = 0, $browseType = 'all', $queryID = 0)
    {
        $objects = $this->objectModel->getAccessiblePersonnel($programID, $deptID, $browseType, $queryID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Check if you have permission to view the program
     *
     * @param  int    mixed $programID
     * @param  string mixed $account
     * @access public
     * @return bool
     */
    public function canViewProgramTest($programID, $account)
    {
        $objects = $this->objectModel->canViewProgram($programID, $account);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get invest test
     *
     * @param int $programID
     * @access public
     * @return array
     */
    public function getInvestTest($programID = 0)
    {
        $objects = $this->objectModel->getInvest($programID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getRiskInvestTest($accounts, $projectID)
    {
        $projects  = $this->personnel->dao->select('*')->from(TABLE_PROJECT)->where('id')->in($projectID)->fetchAll('id');
        $objects = $this->objectModel->getRiskInvest($accounts, $projects);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getIssueInvestTest($accounts, $projects)
    {
        $objects = $this->objectModel->getIssueInvest($accounts, $projects);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getBugAndStoryInvestTest($accounts, $programID)
    {
        $objects = $this->objectModel->getBugAndStoryInvest($accounts, $programID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getInvolvedProjectsTest($projects)
    {
        $objects = $this->objectModel->getInvolvedProjects($projects);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get involved executions test
     *
     * @param  array  mixed $projectID
     * @access public
     * @return void
     */
    public function getInvolvedExecutionsTest($projectID)
    {
        global $tester;
        $project = $tester->dao->select('id')->from(TABLE_PROJECT)->where('project')->in($projectID)->fetchall('project');
        $objects = $this->objectModel->getInvolvedExecutions($project);
        a($objects);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getProjectTaskInvestTest($projects, $accounts)
    {
        $objects = $this->objectModel->getProjectTaskInvest($projects, $accounts);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getUserEffortHoursTest($userTasks)
    {
        $objects = $this->objectModel->getUserEffortHours($userTasks);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getUserHoursTest($userTasks)
    {
        $objects = $this->objectModel->getUserHours($userTasks);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getSprintAndStageTest($projects)
    {
        $objects = $this->objectModel->getSprintAndStage($projects);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getCopiedObjectsTest($objectID, $objectType)
    {
        $objects = $this->objectModel->getCopiedObjects($objectID, $objectType);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getWhitelistTest($objectID = 0, $objectType = '', $orderBy = 'id_desc', $pager = '')
    {
        $objects = $this->objectModel->getWhitelist($objectID = 0, $objectType = '', $orderBy = 'id_desc', $pager = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getWhitelistAccountTest($objectID = 0, $objectType = '')
    {
        $objects = $this->objectModel->getWhitelistAccount($objectID = 0, $objectType = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function updateWhitelistTest($users = array(), $objectType = '', $objectID = 0, $type = 'whitelist', $source = 'add', $updateType = 'replace')
    {
        $objects = $this->objectModel->updateWhitelist($users = array(), $objectType = '', $objectID = 0, $type = 'whitelist', $source = 'add', $updateType = 'replace');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function addWhitelistTest($objectType = '', $objectID = 0)
    {
        $objects = $this->objectModel->addWhitelist($objectType = '', $objectID = 0);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function deleteProductWhitelistTest($productID, $account = '')
    {
        $objects = $this->objectModel->deleteProductWhitelist($productID, $account = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function deleteProgramWhitelistTest($programID = 0, $account = '')
    {
        $objects = $this->objectModel->deleteProgramWhitelist($programID = 0, $account = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function deleteProjectWhitelistTest($objectID = 0, $account = '')
    {
        $objects = $this->objectModel->deleteProjectWhitelist($objectID = 0, $account = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function deleteExecutionWhitelistTest($executionID, $account = '')
    {
        $objects = $this->objectModel->deleteExecutionWhitelist($executionID, $account = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function deleteWhitelistTest($users = array(), $objectType = 'program', $objectID = 0, $groupID = 0)
    {
        $objects = $this->objectModel->deleteWhitelist($users = array(), $objectType = 'program', $objectID = 0, $groupID = 0);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function createMemberLinkTest($dept = 0, $programID = 0)
    {
        $objects = $this->objectModel->createMemberLink($dept = 0, $programID = 0);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function buildSearchFormTest($queryID = 0, $actionURL = '')
    {
        $objects = $this->objectModel->buildSearchForm($queryID = 0, $actionURL = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }
}
