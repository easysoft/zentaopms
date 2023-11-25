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
     * Get stories by search.
     *
     * @param  int    $queryID
     * @param  string $type
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getStoriesBySearchTest($queryID, $type, $orderBy)
    {
        global $tester;
        $recTotal = 0;
        $recPerPage = 1;
        $pageID = 0;
        $tester->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $objects = $this->objectModel->getStoriesBySearch($queryID, $type, $orderBy, $pager);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get requirements by search.
     *
     * @param  int    $queryID
     * @param  string $type
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getRequirementsBySearchTest($queryID, $type, $orderBy)
    {
        global $tester;
        $recTotal = 0;
        $recPerPage = 1;
        $pageID = 0;
        $tester->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $objects = $this->objectModel->getRequirementsBySearch($queryID, $type, $orderBy, $pager);

        if(dao::isError()) return dao::getError();

        return $objects;
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
}
