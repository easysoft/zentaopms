<?php
declare(strict_types=1);
class personnelTest
{
    public function __construct(string $user = '')
    {
        global $tester;
        if($user) su($user);
        $this->objectModel = $tester->loadModel('personnel');
        $tester->app->loadClass('dao');
    }

    /**
     * 测试获取项目集的可访问人员。
     * Test get accessible personnel.
     *
     * @param  int    $programID
     * @param  int    $deptID
     * @param  string $browseType
     * @param  int    $queryID
     * @access public
     * @return array
     */
    public function getAccessiblePersonnelTest(int $programID = 0, int $deptID = 0, string $browseType = 'all', int $queryID = 0): array
    {
        $objects = $this->objectModel->getAccessiblePersonnel($programID, $deptID, $browseType, $queryID);

        if(dao::isError()) return dao::getError();

        return array_filter($objects, function($item){return $item->account !== 'admin';});
    }

    /**
     * 测试检查你是否有权限查看项目集。
     * Test check if you have permission to view the program
     *
     * @param  int        $programID
     * @param  string     $account
     * @access public
     * @return bool|array
     */
    public function canViewProgramTest(int $programID, string $account): bool|array
    {
        su($account);
        $canView = $this->objectModel->canViewProgram($programID, $account);

        if(dao::isError()) return dao::getError();

        return $canView;
    }

    /**
     * 测试获取参与项目集的人员列表。
     * Test get invest person list.
     *
     * @param  int    $programID
     * @access public
     * @return array
     */
    public function getInvestTest(int $programID = 0): array
    {
        $objects = $this->objectModel->getInvest($programID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * 测试获取用户参与的项目风险。
     * Test get user project risk invest.
     *
     * @param  int          $programID
     * @access public
     * @return string|array
     */
    public function getRiskInvestTest(int $programID = 0): string|array
    {
        /* Get all projects under the current program. */
        global $tester;
        $projects = $tester->dao->select('id,model,type,parent,path,name')->from(TABLE_PROJECT)
            ->where('type')->eq('project')
            ->andWhere('path')->like("%,{$programID},%")
            ->andWhere('deleted')->eq('0')
            ->orderBy('id_desc')
            ->fetchAll('id');
        $accountPairs = $this->objectModel->getInvolvedProjects($projects);

        $objects = $this->objectModel->getRiskInvest($accountPairs, $projects);

        if(dao::isError()) return dao::getError();

        $return = '';
        foreach($objects as $account => $invest)
        {
            $return .= "{$account}:{$invest['createdRisk']},{$invest['resolvedRisk']},{$invest['pendingRisk']};";
        }
        return $return;
    }

    /**
     * 测试获取用户参与的项目问题。
     * Test get user project issue invest.
     *
     * @param  int          $programID
     * @access public
     * @return string|array
     */
    public function getIssueInvestTest(int $programID = 0): string|array
    {
        /* Get all projects under the current program. */
        global $tester;
        $projects = $tester->dao->select('id,model,type,parent,path,name')->from(TABLE_PROJECT)
            ->where('type')->eq('project')
            ->andWhere('path')->like("%,{$programID},%")
            ->andWhere('deleted')->eq('0')
            ->orderBy('id_desc')
            ->fetchAll('id');
        $accountPairs = $this->objectModel->getInvolvedProjects($projects);

        $objects = $this->objectModel->getIssueInvest($accountPairs, $projects);

        if(dao::isError()) return dao::getError();

        $return = '';
        foreach($objects as $account => $invest)
        {
            $return .= "{$account}:{$invest['createdIssue']},{$invest['resolvedIssue']},{$invest['pendingIssue']};";
        }
        return $return;
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
     * @param  array  $projectID
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
     * @param  array  $projects
     * @param  array  $accounts
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
     * GetUserHoursTest
     *
     * @param  int   $projectID
     * @param  array $accounts
     * @access public
     * @return void
     */
    public function getUserHoursTest($projectID, $accounts)
    {
        global $tester;
        $projects = $tester->dao->select('id')->from(TABLE_PROJECT)->where('id')->in($projectID)->fetchall('id');
        $tasks = $tester->dao->select('id,status,openedBy,finishedBy,assignedTo,project')->from(TABLE_TASK)
            ->where('project')->in(array_keys($projects))
            ->andWhere('deleted')->eq('0')
            ->fetchAll('id');

        /* Initialize personnel related tasks. */
        $invest = array();
        foreach($accounts as $account)
        {
            $invest[$account]['createdTask']  = 0;
            $invest[$account]['finishedTask'] = 0;
            $invest[$account]['pendingTask']  = 0;
            $invest[$account]['consumedTask'] = 0;
            $invest[$account]['leftTask']     = 0;
        }

        $userTasks = array();

        foreach($tasks as $task)
        {
            if($task->openedBy && isset($invest[$task->openedBy]))
            {
                $invest[$task->openedBy]['createdTask'] += 1;
                $userTasks[$task->openedBy][$task->id]   = $task->id;
            }

            if($task->finishedBy && isset($invest[$task->finishedBy]))
            {
                $invest[$task->finishedBy]['finishedTask'] += 1;
                $userTasks[$task->finishedBy][$task->id]    = $task->id;
            }

            if($task->assignedTo && $task->status == 'wait' && isset($invest[$task->assignedTo]))
            {
                $invest[$task->assignedTo]['pendingTask'] += 1;
                $userTasks[$task->assignedTo][$task->id]   = $task->id;
            }
        }

        $objects = $this->objectModel->getUserHours($userTasks);

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
     * @param  array  $user
     * @access public
     * @return void
     */
    public function addWhitelistTest($objectType = '', $objectID = 0, $user = '')
    {
        $users = array('accounts' => $user);
        foreach($users as $key => $value) $_POST[$key] = $value;

        $objects = $this->objectModel->addWhitelist($objectType, $objectID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Delete product whitelist Test
     *
     * @param  int    $productID
     * @param  string $account
     * @access public
     * @return void
     */
    public function deleteProductWhitelistTest($productID, $account = '')
    {
        global $tester;
        $this->addWhitelistTest('product', $productID, array('admin'));
        $tester->dao->update(TABLE_ACL)->set('source')->eq('sync')->where('objectID')->eq($productID)->andWhere('objectType')->eq('product')->exec();

        $objects = $this->objectModel->deleteProductWhitelist($productID, $account);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Delete program whitelist Test
     *
     * @param  int    $programID
     * @param  string $account
     * @access public
     * @return void
     */
    public function deleteProgramWhitelistTest($programID, $account)
    {
        global $tester;
        $list1 = $tester->dao->select('*')->from(TABLE_ACL)->where('objectID')->eq($programID)->andWhere('objectType')->eq('program')->andWhere('account')->eq($account)->andWhere('source')->eq('sync')->fetchAll();

        $this->objectModel->deleteProgramWhitelist($programID, $account);

        if(dao::isError() || count($list1) < 1) return -1;

        unset(dao::$cache[TABLE_ACL]);
        $list2 = $tester->dao->select('*')->from(TABLE_ACL)->where('objectID')->eq($programID)->andWhere('objectType')->eq('program')->andWhere('account')->eq($account)->andWhere('source')->eq('sync')->fetchAll();

        return count($list1) == count($list2);
    }

    /**
     * Delete project whitelist Test
     *
     * @param  int    $objectID
     * @param  string $account
     * @access public
     * @return void
     */
    public function deleteProjectWhitelistTest($objectID, $account)
    {
        global $tester;
        $this->addWhitelistTest('project', $objectID, array($account));
        $tester->dao->update(TABLE_ACL)->set('source')->eq('sync')->where('objectID')->eq($objectID)->andWhere('objectType')->eq('project')->exec();
        $objects = $this->objectModel->deleteProjectWhitelist($objectID, $account);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Delete execution whitelist Test
     *
     * @param  int    $executionID
     * @param  string $account
     * @access public
     * @return void
     */
    public function deleteExecutionWhitelistTest($executionID, $account = '')
    {
        global $tester;
        $this->addWhitelistTest('sprint', $executionID, array($account));
        $tester->dao->update(TABLE_ACL)->set('source')->eq('sync')->where('objectID')->eq($executionID)->andWhere('objectType')->eq('sprint')->exec();
        $objects = $this->objectModel->deleteExecutionWhitelist($executionID, $account);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Delete whitelist Test
     *
     * @param array $users
     * @param  string $objectType
     * @param  int    $objectID
     * @param  int    $groupID
     * @access public
     * @return void
     */
    public function deleteWhitelistTest($users = array(), $objectType = 'program', $objectID = 0, $groupID = 0)
    {
        global $tester;
        $this->addWhitelistTest($objectType, $objectID, $users);
        $tester->dao->update(TABLE_ACL)->set('source')->eq('sync')->where('objectID')->eq($objectID)->andWhere('objectType')->eq($objectType)->exec();
        $objects = $this->objectModel->deleteWhitelist($users, $objectType, $objectID, $groupID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * 测试判断操作是否可以点击。
     * Test judge an action is clickable or not.
     *
     * @param  object     $report
     * @param  string     $action
     * @access public
     * @return int|array
     */
    public function isClickableTest(object $report, string $action): int|array
    {
        $isClickable = $this->objectModel->isClickable($report, $action);
        if(dao::isError()) return dao::getError();
        return $isClickable ? 1 : 0;
    }
}
