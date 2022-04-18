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
        global $tester;
        $projects  = $tester->personnel->dao->select('*')->from(TABLE_PROJECT)->where('id')->in($projectID)->fetchAll('id');
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

    /**
     * Get bug and story invest test
     *
     * @param  array mixed $accounts
     * @param  int   mixed $programID
     * @access public
     * @return void
     */
    public function getBugAndStoryInvestTest($accounts, $programID)
    {
        $objects = $this->objectModel->getBugAndStoryInvest($accounts, $programID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getInvolvedProjectsTest($projects)
    {
        global $tester;
        $projectID = $tester->dao->select('id')->from(TABLE_PROJECT)->where('id')->in($projects)->fetchall('id');
        $objects = $this->objectModel->getInvolvedProjects($projectID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get involved executions test
     *
     * @param  array  mixed $projectID
     * @access public
     * @return array
     */
    public function getInvolvedExecutionsTest($projectID)
    {
        global $tester;
        $project = $tester->dao->select('id')->from(TABLE_PROJECT)->where('id')->in($projectID)->fetchall('id');
        $objects = $this->objectModel->getInvolvedExecutions($project);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get project task invest test
     *
     * @param  array  mixed $projects
     * @param  array  mixed $accounts
     * @access public
     * @return void
     */
    public function getProjectTaskInvestTest($projectID, $accounts)
    {
        global $tester;
        $project = $tester->dao->select('id')->from(TABLE_PROJECT)->where('id')->in($projectID)->fetchall('id');
        $objects = $this->objectModel->getProjectTaskInvest($project, $accounts);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * getUserEffortHoursTest
     *
     * @param mixed $projectID
     * @access public
     * @return void
     */
    public function getUserEffortHoursTest($projectID)
    {
        global $tester;
        $projects = $tester->dao->select('id')->from(TABLE_PROJECT)->where('id')->in($projectID)->fetchall('id');
        $tasks = $tester->dao->select('id,status,openedBy,finishedBy,assignedTo,project')->from(TABLE_TASK)
            ->where('project')->in(array_keys($projects))
            ->andWhere('deleted')->eq('0')
            ->fetchAll('id');

        $userTasks = array();

        foreach($tasks as $task)
        {
            if($task->openedBy && isset($invest[$task->openedBy]))
            {
                $invest[$task->openedBy]['createdTask'] += 1;
                $userTasks[$task->openedBy][$task->id]    = $task->id;
            }

            if($task->finishedBy && isset($invest[$task->finishedBy]))
            {
                $invest[$task->finishedBy]['finishedTask'] += 1;
                $userTasks[$task->finishedBy][$task->id]     = $task->id;
            }

            if($task->assignedTo && $task->status == 'wait' && isset($invest[$task->assignedTo]))
            {
                $invest[$task->assignedTo]['pendingTask'] += 1;
                $userTasks[$task->assignedTo][$task->id]    = $task->id;
            }
        }

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

    /**
     * Get copied objects test
     *
     * @param  int    mixed $objectID
     * @param  string mixed $objectType
     * @access public
     * @return array
     */
    public function getCopiedObjectsTest($objectID, $objectType)
    {
        $objects = $this->objectModel->getCopiedObjects($objectID, $objectType);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get whitelist test
     *
     * @param  int    $objectID
     * @param  string $objectType
     * @param  string $orderBy
     * @param  string $pager
     * @access public
     * @return array
     */
    public function getWhitelistTest($objectID = 0, $objectType = '', $orderBy = 'id_desc', $pager = '')
    {
        $objects = $this->objectModel->getWhitelist($objectID, $objectType, $orderBy, $pager);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get whitelist account test
     *
     * @param  int    $objectID
     * @param  string $objectType
     * @access public
     * @return array
     */
    public function getWhitelistAccountTest($objectID = 0, $objectType = '')
    {
        $objects = $this->objectModel->getWhitelistAccount($objectID, $objectType);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function updateWhitelistTest($users = array(), $objectType = '', $objectID = 0, $type = 'whitelist', $source = 'add', $updateType = 'replace')
    {
        $objects = $this->objectModel->updateWhitelist($users, $objectType, $objectID, $type, $source, $updateType);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Add whitelist test
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @param  string mixed $user
     * @access public
     * @return void
     */
    public function addWhitelistTest($objectType = '', $objectID = 0, $user)
    {
        $users = array('accounts' => $user);
        foreach($users as $key => $value) $_POST[$key] = $value;

        $objects = $this->objectModel->addWhitelist($objectType, $objectID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function deleteProductWhitelistTest($productID, $account = '')
    {
        $objects = $this->objectModel->deleteProductWhitelist($productID, $account);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function deleteProgramWhitelistTest($programID = 0, $account = '')
    {
        $objects = $this->objectModel->deleteProgramWhitelist($programID, $account);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function deleteProjectWhitelistTest($objectID = 0, $account = '')
    {
        $objects = $this->objectModel->deleteProjectWhitelist($objectID, $account);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function deleteExecutionWhitelistTest($executionID, $account = '')
    {
        $objects = $this->objectModel->deleteExecutionWhitelist($executionID, $account);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function deleteWhitelistTest($users = array(), $objectType = 'program', $objectID = 0, $groupID = 0)
    {
        $objects = $this->objectModel->deleteWhitelist($users, $objectType, $objectID, $groupID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function createMemberLinkTest($dept = 0, $programID = 0)
    {
        $objects = $this->objectModel->createMemberLink($dept, $programID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function buildSearchFormTest($queryID = 0, $actionURL = '')
    {
        $objects = $this->objectModel->buildSearchForm($queryID, $actionURL);

        if(dao::isError()) return dao::getError();

        return $objects;
    }
}
