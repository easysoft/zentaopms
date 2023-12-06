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

    /**
     * 测试获取项目成员帐户和参与项目的数量。
     * Test get the project member accounts and the number of participating projects.
     *
     * @param  array  $projects
     * @access public
     * @return array
     */
    public function getInvolvedProjectsTest(array $projects): array
    {
        global $tester;
        $projectID = $tester->dao->select('id')->from(TABLE_PROJECT)->where('id')->in($projects)->fetchAll('id');

        $objects = $this->objectModel->getInvolvedProjects($projectID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * 测试获取项目下的迭代或阶段。
     * Test get the iteration or phase under the project.
     *
     * @param  array  $projects
     * @access public
     * @return array
     */
    public function getInvolvedExecutionsTest(array $projects): array
    {
        global $tester;
        $project = $tester->dao->select('id')->from(TABLE_PROJECT)->where('id')->in($projects)->fetchAll('id');
        $objects = $this->objectModel->getInvolvedExecutions($project);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * 测试获取投入项目的任务。
     * test get project task invest.
     *
     * @param  array  $projects
     * @param  array  $accounts
     * @access public
     * @return array
     */
    public function getProjectTaskInvestTest(array $projects, array $accounts): array
    {
        global $tester;
        $project = $tester->dao->select('id')->from(TABLE_PROJECT)->where('id')->in($projects)->fetchAll('id');
        $objects = $this->objectModel->getProjectTaskInvest($project, $accounts);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * 测试获取用户工时。
     * Test get user hours.
     *
     * @param  int    $projectID
     * @param  array  $accounts
     * @access public
     * @return array
     */
    public function getUserHoursTest(int $projectID, array $accounts): array
    {
        global $tester;
        $projects = $tester->dao->select('id')->from(TABLE_PROJECT)->where('id')->in($projectID)->fetchAll('id');
        $tasks = $tester->dao->select('id,status,openedBy,finishedBy,assignedTo,project')->from(TABLE_TASK)
            ->where('project')->in(array_keys($projects))
            ->andWhere('deleted')->eq('0')
            ->fetchAll('id');

        /* Initialize personnel related tasks. */
        $invest = array();
        foreach($accounts as $account)
        {
            $invest[$account]['createdTask'] = $invest[$account]['finishedTask'] = $invest[$account]['pendingTask'] = $invest[$account]['consumedTask'] = $invest[$account]['leftTask'] = 0;
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
     * 测试获取对象的白名单。
     * Test get object whitelist.
     *
     * @param  int          $objectID
     * @param  string       $objectType
     * @param  string       $orderBy
     * @param  object       $pager
     * @access public
     * @return string|array
     */
    public function getWhitelistTest(int $objectID = 0, string $objectType = '', string $orderBy = 'id_desc', object $pager = null): string|array
    {
        $objects = $this->objectModel->getWhitelist($objectID, $objectType, $orderBy, $pager);

        if(dao::isError()) return dao::getError();

        return implode(',', array_column($objects, 'account'));
    }

    /**
     * 测试获取对象的白名单账号。
     * Test get whitelist account.
     *
     * @param  int         $objectID
     * @param  string      $objectType
     * @access public
     * @return array|array
     */
    public function getWhitelistAccountTest(int $objectID = 0, string $objectType = ''): string|array
    {
        $objects = $this->objectModel->getWhitelistAccount($objectID, $objectType);

        if(dao::isError()) return dao::getError();

        return implode(',', $objects);
    }

    /**
     * 测试更新白名单。
     * Test update whitelist.
     *
     * @param  array  $user数
     * @param  string $objectType
     * @param  int    $objectID
     * @param  string $type
     * @param  string $source
     * @param  string $updateType
     * @access public
     * @return string|array
     */
    public function updateWhitelistTest(array $users = array(), string $objectType = '', int $objectID = 0, string $type = 'whitelist', string $source = 'add', string $updateType = 'replace'): string|array
    {
        $this->objectModel->updateWhitelist($users, $objectType, $objectID, $type, $source, $updateType);

        if(dao::isError()) return dao::getError();

        global $tester;
        $return = '';

        $acls = $tester->dao->select('account,source')->from(TABLE_ACL)->where('objectID')->eq($objectID)->andWhere('objectType')->eq($objectType)->fetchPairs();
        if($acls)
        {
            $return = 'acls: ';
            foreach($acls as $account => $source) $return .= "{$account}:{$source},";
            $return = trim($return, ',');
            $return .= ';';

            $userViews = $tester->dao->select("account,{$objectType}s")->from(TABLE_USERVIEW)->where('account')->in(array_keys($acls))->fetchPairs();
            if($userViews)
            {
                $return   .= 'views: ';
                foreach($userViews as $account => $views) $return .="{$account}:{$views},";
                $return = trim($return, ',');
                $return .= ';';
            }
        }

        return $return;
    }

    /**
     * 测试从产品的白名单内删除用户。
     * Test delete user from product whitelist.
     *
     * @param  int          $productID
     * @param  string       $account
     * @access public
     * @return string|array
     */
    public function deleteProductWhitelistTest(int $productID, string $account = ''): string|array
    {
        $this->objectModel->deleteProductWhitelist($productID, $account);

        if(dao::isError()) return dao::getError();

        global $tester;
        $productViews = $tester->dao->select('products')->from(TABLE_USERVIEW)->where('account')->eq($account)->fetch('products');

        return $productViews;
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
