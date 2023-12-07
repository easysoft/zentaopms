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
     * 测试从项目集删除白名单。
     * Test delete whitelist from program.
     *
     * @param  int          $programID
     * @param  string       $account
     * @access public
     * @return string|array
     */
    public function deleteProgramWhitelistTest(int $programID, string $account): string|array
    {
        $this->objectModel->deleteProgramWhitelist($programID, $account);

        if(dao::isError()) return -1;

        unset(dao::$cache[TABLE_ACL]);
        global $tester;
        $whitelist = $tester->dao->select('whitelist')->from(TABLE_PROJECT)->where('id')->eq($programID)->fetch('whitelist');

        return $whitelist;
    }

    /**
     * 测试从项目删除白名单。
     * Test delete whitelist from project.
     *
     * @param  int          $objectID
     * @param  string       $account
     * @access public
     * @return string|array
     */
    public function deleteProjectWhitelistTest(int $objectID, string $account): string|array
    {
        $this->objectModel->deleteProjectWhitelist($objectID, $account);

        if(dao::isError()) return dao::getError();

        global $tester;
        $project   = $tester->dao->select('id,project,whitelist')->from(TABLE_PROJECT)->where('id')->eq($objectID)->fetch();
        if(!$project) return '0';
        $projectID = $project->project ? $project->project : $objectID;
        $whitelist = $tester->dao->select('whitelist')->from(TABLE_PROJECT)->where('id')->eq($projectID)->fetch('whitelist');
        return $whitelist;
    }

    /**
     * 测试从执行删除白名单。
     * Test delete whitelist from execution.
     *
     * @param  int          $executionID
     * @param  string       $account
     * @access public
     * @return string|array
     */
    public function deleteExecutionWhitelistTest(int $executionID, string $account = ''): string|array
    {
        $this->objectModel->deleteExecutionWhitelist($executionID, $account);

        if(dao::isError()) return dao::getError();

        global $tester;
        $whitelist = $tester->dao->select('whitelist')->from(TABLE_PROJECT)->where('id')->eq($executionID)->fetch('whitelist');
        return $whitelist;
    }

    /**
     * 测试根据部门创建访问连接。
     * Test access link by department.
     *
     * @param  int     $programID
     * @param  int     $deptID
     * @access public
     * @return int|array
     */
    public function createMemberLinkTest(int $programID, int $deptID): int|array
    {
        $dept = new stdclass();
        $dept->id = $deptID;
        $link = $this->objectModel->createMemberLink($dept, $programID);
        if(dao::isError()) return dao::getError();

        $isCorrect = preg_match("/personnel.*accessible.*{$programID}.*{$deptID}/", $link);
        return $isCorrect;
    }

    /**
     * 测试构建搜索表单。
     * Test build search form.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return array
     */
    public function buildSearchFormTest(int $queryID, string $actionURL): array
    {
        $this->objectModel->buildSearchForm($queryID, $actionURL);
        if(dao::isError()) return dao::getError();

        global $tester;
        return $tester->config->personnel->accessible->search;
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
