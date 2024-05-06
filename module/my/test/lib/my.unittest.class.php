<?php
declare(strict_types=1);
class myTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('my');
    }

    /**
     * 测试获取我的产品。
     * Test get my products.
     *
     * @param  string $type
     * @access public
     * @return object|array
     */
    public function getProductsTest(string $type): object|array
    {
        $objects = $this->objectModel->getProducts($type);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * 测试获取我的进行中的项目。
     * Test get my doing projects.
     *
     * @access public
     * @return object
     */
    public function getDoingProjectsTest(): object|array
    {
        $objects = $this->objectModel->getDoingProjects();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * 测试获取我的项目总览。
     * Test get project overview list.
     *
     * @access public
     * @return object|array
     */
    public function getOverviewTest(): object|array
    {
        $object = $this->objectModel->getOverview();

        if(dao::isError()) return dao::getError();

        return $object;
    }

    /**
     * 测试获取我的贡献。
     * Test get my contribute.
     *
     * @access public
     * @return object
     */
    public function getContributeTest(): object|array
    {
        $objects = $this->objectModel->getContribute();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * 获取我的最新动态。
     * Test get my latest actions.
     *
     * @access public
     * @return array
     */
    public function getActionsTest(): array
    {
        $objects = $this->objectModel->getActions();

        if(dao::isError()) return dao::getError();

        return array_column($objects, 'id');
    }

    /**
     * 获取由我指派的数据。
     * Test get assigned by me.
     *
     * @param  string $account
     * @param  string $orderBy
     * @param  string $objectType
     * @access public
     * @return int
     */
    public function getAssignedByMeTest(string $account, string $orderBy, string $objectType)
    {
        $objects = $this->objectModel->getAssignedByMe($account, null, $orderBy, $objectType);

        if(dao::isError()) return dao::getError();

        return count($objects);
    }

    /**
     * 测试通过搜索获取用例。
     * Get testcases by search.
     *
     * @param  int    $queryID
     * @param  string $type
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getTestcasesBySearchTest(int $queryID, string $type, string $orderBy): array
    {
        $objects = $this->objectModel->getTestcasesBySearch($queryID, $type, $orderBy, null);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * 通过搜索获取任务。
     * Get tasks by search.
     *
     * @param  string $account
     * @param  int    $limit
     * @access public
     * @return array
     */
    public function getTasksBySearchTest(string $account, int $limit = 0): array
    {
        $objects = $this->objectModel->getTasksBySearch($account, $limit, null);

        if(dao::isError()) return dao::getError();

        return array_keys($objects);
    }

    /**
     * 测试通过搜索获取 需求。
     * Test get stories by search.
     *
     * @param  int          $queryID
     * @param  string       $type
     * @param  string       $orderBy
     * @access public
     * @return string|array
     */
    public function getStoriesBySearchTest(int $queryID, string $type, string $orderBy): string|array
    {
        $objects = $this->objectModel->getStoriesBySearch($queryID, $type, $orderBy, null);

        if(dao::isError()) return dao::getError();

        return implode(',', array_keys($objects));
    }

    /**
     * 测试通过搜索获取用户需求。
     * Test get requirements by search.
     *
     * @param  int          $queryID
     * @param  string       $type
     * @param  string       $orderBy
     * @access public
     * @return string|array
     */
    public function getRequirementsBySearchTest(int $queryID, string $type, string $orderBy): string|array
    {
        $objects = $this->objectModel->getRequirementsBySearch($queryID, $type, $orderBy);

        if(dao::isError()) return dao::getError();

        return implode(',', array_keys($objects));
    }

    /**
     * 测试获取流程键值对。
     * Test get flow paris.
     *
     * @access public
     * @return array|string
     */
    public function getFlowPairsTest()
    {
        $flows = $this->objectModel->getFlowPairs();

        if(dao::isError()) return dao::getError();

        $return = '';
        foreach($flows as $module => $name) $return .= "{$module}:{$name},";

        return trim($return, ',');
    }

    /**
     * 测试设置菜单。
     * Test set menu.
     *
     * @param  string $account
     * @access public
     * @return array|string
     */
    public function setMenuTest(string $account): array|string
    {
        su($account);

        $this->objectModel->setMenu();

        if(dao::isError()) return dao::getError();

         global $tester;
        $return = '';
        foreach($tester->lang->my->menuOrder as $order => $menuName) $return .= "{$order}:{$menuName},";

        return trim($return, ',');
    }

    /**
     * 测试构建用例搜索表单。
     * Test build testcase search form.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return array
     */
    public function buildTestcaseSearchFormTest(int $queryID, string $actionURL): array
    {
        $this->objectModel->buildTestcaseSearchForm($queryID, $actionURL);

        if(dao::isError()) return dao::getError();
        global $tester;
        return $tester->config->testcase->search;
    }

    /**
     * 测试构建任务搜索表单。
     * Test build task search form.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return array
     */
    public function buildTaskSearchFormTest(int $queryID, string $actionURL): array
    {
        $this->objectModel->buildTaskSearchForm($queryID, $actionURL);

        if(dao::isError()) return dao::getError();
        global $tester;
        return $tester->config->execution->search;
    }

    /**
     * 测试构建 bug 搜索表单。
     * Test build bug search form.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return array
     */
    public function buildBugSearchFormTest(int $queryID, string $actionURL): array
    {
        global $tester;
        $tester->app->rawModule = 'my';
        $tester->app->rawMethod = 'bug';
        $this->objectModel->buildBugSearchForm($queryID, $actionURL);

        if(dao::isError()) return dao::getError();
        return $tester->config->bug->search;
    }

    /**
     * 测试构建风险搜索表单。
     * Test build risk search form.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return array
     */
    public function buildRiskSearchFormTest(int $queryID, string $actionURL): array
    {
        $this->objectModel->buildRiskSearchForm($queryID, $actionURL);

        if(dao::isError()) return dao::getError();
        global $tester;
        return $tester->config->risk->search;
    }

    /**
     * 通过搜索获取风险。
     * Get risks by search.
     *
     * @param  int    $queryID
     * @param  string $type
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getRisksBySearchTest(int $queryID, string $type, string $orderBy): array
    {
        $objects = $this->objectModel->getRisksBySearch($queryID, $type, $orderBy , null);

        if(dao::isError()) return dao::getError();

        return array_keys($objects);
    }

    /**
     * 测试构建需求搜索表单。
     * Test build story search form.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return array
     */
    public function buildStorySearchFormTest(int $queryID, string $actionURL): array
    {
        global $tester;
        $tester->app->rawModule = 'my';
        $tester->app->rawMethod = 'story';
        $this->objectModel->buildStorySearchForm($queryID, $actionURL);

        if(dao::isError()) return dao::getError();
        return $tester->config->product->search;
    }

    /**
     * 测试构建用户需求搜索表单。
     * Test build requirement search form.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return array
     */
    public function buildRequirementSearchFormTest(int $queryID, string $actionURL): array
    {
        global $tester;
        $tester->app->rawModule = 'my';
        $tester->app->rawMethod = 'requirement';
        $this->objectModel->buildRequirementSearchForm($queryID, $actionURL);

        if(dao::isError()) return dao::getError();
        return $tester->config->product->search;
    }

    /**
     * 测试构建工单搜索表单。
     * Test build ticket search form.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return bool
     */
    public function buildTicketSearchFormTest(int $queryID, string $actionURL): bool
    {
        global $tester;
        if(!isset($tester->config->ticket))
        {
            $tester->config->ticket = new stdclass();
            $tester->config->ticket->search = array();
            $tester->config->ticket->search['fields'] = array();
        }
        if(!isset($tester->config->ticket->search)) $tester->config->ticket->search = array();
        return $this->objectModel->buildTicketSearchForm($queryID, $actionURL);
    }

    /**
     * 测试获取审批中的需求。
     * Test get reviewing stories.
     *
     * @param  string           $account
     * @param  string           $orderBy
     * @param  bool             $checkExist
     * @access public
     * @return int|string|array
     */
    public function getReviewingStoriesTest(string $account, string $orderBy, bool $checkExist): int|string|array
    {
        su($account);
        $return = $this->objectModel->getReviewingStories($orderBy, $checkExist);

        if(dao::isError()) return dao::getError();

        if($return === true) return 'exist';
        return !empty($return) ? implode(',', array_column($return, 'id')) : 'empty';
    }

    /**
     * 测试获取审批中的用例。
     * Test get reviewing cases.
     *
     * @param  string           $account
     * @param  string           $orderBy
     * @param  bool             $checkExist
     * @access public
     * @return int|string|array
     */
    public function getReviewingCasesTest(string $account, string $orderBy, bool $checkExist): int|string|array
    {
        su($account);
        $return = $this->objectModel->getReviewingCases($orderBy, $checkExist);

        if(dao::isError()) return dao::getError();

        if($return === true) return 'exist';
        return !empty($return) ? implode(',', array_column($return, 'id')) : 'empty';
    }

    /**
     * 测试获取审批中的用例。
     * Test get reviewing demands.
     *
     * @param  string           $account
     * @param  string           $orderBy
     * @param  bool             $checkExist
     * @access public
     * @return int|string|array
     */
    public function getReviewingDemandsTest(string $account, string $orderBy, bool $checkExist): int|string|array
    {
        su($account);
        $return = $this->objectModel->getReviewingDemands($orderBy, $checkExist);

        if(dao::isError()) return dao::getError();

        if($return === true) return 'exist';
        return !empty($return) ? implode(',', array_column($return, 'id')) : 'empty';
    }

    /**
     * 测试获取审批中的审批。
     * Test get reviewing approvals.
     *
     * @param  string           $orderBy
     * @param  bool             $checkExist
     * @access public
     * @return int|string|array
     */
    public function getReviewingApprovalsTest(string $orderBy, bool $checkExist): int|string|array
    {
        $return = $this->objectModel->getReviewingApprovals($orderBy, $checkExist);

        if(dao::isError()) return dao::getError();

        if($return === true) return 'exist';
        return !empty($return) ? implode(',', array_column($return, 'id')) : 'empty';
    }

    /**
     * 测试获取审批中的工作流。
     * Test get reviewing flows.
     *
     * @param  string           $orderBy
     * @param  bool             $checkExist
     * @access public
     * @return int|string|array
     */
    public function getReviewingFlowsTest(string $orderBy, bool $checkExist): int|string|array
    {
        $return = $this->objectModel->getReviewingFlows($orderBy, $checkExist);

        if(dao::isError()) return dao::getError();

        if($return === true) return 'exist';
        return !empty($return) ? implode(',', array_column($return, 'id')) : 'empty';
    }

    /**
     * 测试获取审批中的反馈。
     * Test get reviewing feedbacks.
     *
     * @param  string           $orderBy
     * @param  bool             $checkExist
     * @access public
     * @return int|string|array
     */
    public function getReviewingFeedbacksTest(string $orderBy, bool $checkExist): int|string|array
    {
        $return = $this->objectModel->getReviewingFeedbacks($orderBy, $checkExist);

        if(dao::isError()) return dao::getError();

        if($return === true) return 'exist';
        return !empty($return) ? implode(',', array_column($return, 'id')) : 'empty';
    }

    /**
     * 测试获取审批中的OA。
     * Test get reviewing oas.
     *
     * @param  string           $orderBy
     * @param  bool             $checkExist
     * @access public
     * @return int|string|array
     */
    public function getReviewingOATest(string $orderBy, bool $checkExist): int|string|array
    {
        $return = $this->objectModel->getReviewingOA($orderBy, $checkExist);

        if(dao::isError()) return dao::getError();

        if($return === true) return 'exist';
        return !empty($return) ? implode(',', array_column($return, 'id')) : 'empty';
    }

    /**
     * 测试获取审批列表。
     * Test get reviewed list.
     *
     * @param  string       $browseType
     * @param  string       $orderBy
     * @access public
     * @return string|array
     */
    public function getReviewedListTest(string $browseType, string $orderBy): string|array
    {
        $return = $this->objectModel->getReviewedList($browseType, $orderBy);

        if(dao::isError()) return dao::getError();

        return implode(',', array_column($return, 'id'));
    }

    /**
     * 测试获取审批类型列表。
     * Test get reviewing type list.
     *
     * @param  string       $account
     * @access public
     * @return string|array
     */
    public function getReviewingTypeListTest(string $account): string|array
    {
        su($account);

        $menu = $this->objectModel->getReviewingTypeList();

        if(dao::isError()) return dao::getError();

        $return = '';
        foreach($menu as $menuKey => $menuName) $return .= "{$menuKey}:{$menuName},";
        return trim($return, ',');
    }

    /**
     * 测试获取审批类型列表。
     * Test get reviewing list.
     *
     * @param  string       $account
     * @param  string       $browseType
     * @param  string       $orderBy
     * @param  object       $pager
     * @access public
     * @return string|array
     */
    public function getReviewingListTest(string $account, string $browseType, string $orderBy, object $pager = null): string|array
    {
        su($account);

        $reviewList = $this->objectModel->getReviewingList($browseType, $orderBy, $pager);

        if(dao::isError()) return dao::getError();

        $return = '';
        foreach($reviewList as $review) $return .= "{$review->type},{$review->id};";
        return trim($return, ',');
    }
}
