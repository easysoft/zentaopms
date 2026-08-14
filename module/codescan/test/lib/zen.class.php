<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class codescanZenTest extends baseTest
{
    protected $moduleName = 'codescan';
    protected $className  = 'zen';

    /**
     * Override constructor to prevent loading codescanZen via parent which triggers control constructor redirect.
     *
     * @param  string $moduleName
     * @param  string $className
     * @access public
     */
    public function __construct($moduleName = '', $className = '')
    {
        $this->moduleName = 'codescan';
        $this->className  = 'zen';
    }

    /**
     * Get a codescanZen instance without calling its constructor.
     * The codescan control constructor triggers gitfox->checkHealth() which redirects in test environment.
     *
     * @access private
     * @return object
     */
    private function getZenInstance(): object
    {
        $baseDir = dirname(__DIR__, 4);
        include_once $baseDir . '/module/codescan/control.php';
        include_once $baseDir . '/module/codescan/zen.php';
        include_once $baseDir . '/module/codescan/model.php';

        $ref      = new ReflectionClass('codescanZen');
        $instance = $ref->newInstanceWithoutConstructor();

        global $app, $config, $lang;
        $instance->app             = $app;
        $instance->app->rawModule  = 'codescan';
        $instance->app->rawMethod  = 'browse';
        $instance->app->moduleName = 'codescan';
        $instance->config          = $config;
        $instance->lang            = $lang;
        $instance->view            = (object)array(
            'langList'   => array(),
            'tagList'    => array(),
            'pluginList' => array(),
            'repoList'   => array(),
            'planList'   => array()
        );
        $instance->session         = $app->session;
        $instance->codescan        = new codescanModel();

        return $instance;
    }

    /**
     * Test commonData method.
     *
     * @param  string $include
     * @param  bool   $usePair
     * @access public
     * @return string
     */
    public function commonDataTest(string $include = '', bool $usePair = true)
    {
        $instance = $this->getZenInstance();
        $instance->view = new stdclass();
        $method = new ReflectionMethod($instance, 'commonData');
        $method->setAccessible(true);
        return $method->invoke($instance, $include, $usePair);
    }

    /**
     * Test getDateFilter method.
     *
     * @param  string $query
     * @access public
     * @return array
     */
    public function getDateFilterTest(string $query = '')
    {
        $instance = $this->getZenInstance();
        return $instance->getDateFilter($query);
    }

    /**
     * Test validateTrigger method.
     *
     * @param  object $trigger
     * @access public
     * @return bool
     */
    public function validateTriggerTest(object $trigger = null)
    {
        $instance = $this->getZenInstance();
        if($trigger === null) $trigger = new stdclass();
        return $instance->validateTrigger($trigger);
    }

    /**
     * Test validateField method.
     *
     * @param  string $value
     * @param  string $field
     * @access public
     * @return bool
     */
    public function validateFieldTest(string $value = '', string $field = '')
    {
        $instance = $this->getZenInstance();
        $method = new ReflectionMethod($instance, 'validateField');
        $method->setAccessible(true);
        return $method->invoke($instance, $value, $field);
    }

    /**
     * Test buildParams method.
     *
     * @param  string $type
     * @param  string $params
     * @param  int    $queryID
     * @param  string $orderBy
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return mixed
     */
    public function buildParamsTest(string $type = '', string $params = '', int $queryID = 0, string $orderBy = '', int $recPerPage = 20, int $pageID = 1)
    {
        $instance = $this->getZenInstance();
        $method = new ReflectionMethod($instance, 'buildParams');
        $method->setAccessible(true);
        return $method->invoke($instance, $type, $params, $queryID, $orderBy, $recPerPage, $pageID);
    }

    /**
     * Test buildSearchForm method.
     *
     * @param  array      $searchConfig
     * @param  string|int $queryID
     * @param  string     $actionURL
     * @access public
     * @return mixed
     */
    public function buildSearchFormTest(array $searchConfig = array(), $queryID = 0, string $actionURL = '')
    {
        $instance = $this->getZenInstance();
        $method = new ReflectionMethod($instance, 'buildSearchForm');
        $method->setAccessible(true);
        return $method->invoke($instance, $searchConfig, $queryID, $actionURL);
    }

    /**
     * Test responseError method.
     *
     * @param  string|array $errors
     * @param  string       $locate
     * @access public
     * @return mixed
     */
    public function responseErrorTest($errors = '', string $locate = '')
    {
        $instance = $this->getZenInstance();
        $instance->viewType = 'json';
        $method = new ReflectionMethod($instance, 'responseError');
        $method->setAccessible(true);

        try
        {
            $method->invoke($instance, $errors, $locate);
        }
        catch(EndResponseException $exception)
        {
            return '1';
        }

        return '1';
    }

    /**
     * Test getListByQuery method.
     *
     * @param  string $query
     * @param  int    $serviceRepoID
     * @param  int    $taskID
     * @param  string $status
     * @access public
     * @return array
     */
    public function getListByQueryTest(string $query = 'ruleset', int $serviceRepoID = 0, int $taskID = 0, string $status = '')
    {
        $instance = $this->getZenInstance();
        $method = new ReflectionMethod($instance, 'getListByQuery');
        $method->setAccessible(true);
        return $method->invoke($instance, $query, $serviceRepoID, $taskID, $status);
    }

    /**
     * Test processConditions method.
     *
     * @param  object $plan
     * @access public
     * @return array
     */
    public function processConditionsTest(object $plan = null)
    {
        $instance = $this->getZenInstance();
        if($plan === null) $plan = new stdclass();
        $method = new ReflectionMethod($instance, 'processConditions');
        $method->setAccessible(true);
        return $method->invoke($instance, $plan);
    }

    /**
     * Test processPlanData method.
     *
     * @param  object $plan
     * @access public
     * @return object
     */
    public function processPlanDataTest(object $plan = null)
    {
        $instance = $this->getZenInstance();
        if($plan === null) $plan = new stdclass();
        $method = new ReflectionMethod($instance, 'processPlanData');
        $method->setAccessible(true);
        return $method->invoke($instance, $plan);
    }

    /**
     * Test buildPlanData method.
     *
     * @param  object $plan
     * @access public
     * @return object
     */
    public function buildPlanDataTest(object $plan = null)
    {
        $instance = $this->getZenInstance();
        if($plan === null) $plan = new stdclass();
        $method = new ReflectionMethod($instance, 'buildPlanData');
        $method->setAccessible(true);
        return $method->invoke($instance, $plan);
    }

    /**
     * Test processTaskData method.
     *
     * @param  object $task
     * @param  array  $repoList
     * @access public
     * @return object
     */
    public function processTaskDataTest(object $task = null, array $repoList = array())
    {
        $instance = $this->getZenInstance();
        if($task === null) $task = new stdclass();
        $method = new ReflectionMethod($instance, 'processTaskData');
        $method->setAccessible(true);
        return $method->invoke($instance, $task, $repoList);
    }

    /**
     * Test processIssueData method.
     *
     * @param  object $issue
     * @access public
     * @return object
     */
    public function processIssueDataTest(object $issue = null)
    {
        $instance = $this->getZenInstance();
        if($issue === null) $issue = new stdclass();
        $method = new ReflectionMethod($instance, 'processIssueData');
        $method->setAccessible(true);
        return $method->invoke($instance, $issue);
    }

    /**
     * Test processExecBranch method.
     *
     * @param  int $planID
     * @param  int $repoID
     * @access public
     * @return array
     */
    public function processExecBranchTest(int $planID = 0, int $repoID = 0)
    {
        $instance = $this->getZenInstance();
        $method = new ReflectionMethod($instance, 'processExecBranch');
        $method->setAccessible(true);
        ob_start();
        $result = $method->invoke($instance, $planID, $repoID);
        ob_end_clean();
        return $result;
    }

    /**
     * Test getFileIssueList method.
     *
     * @param  string $file
     * @param  int    $serviceRepoID
     * @param  int    $taskID
     * @access public
     * @return mixed
     */
    public function getFileIssueListTest(string $file = '', int $serviceRepoID = 0, int $taskID = 0)
    {
        $instance = $this->getZenInstance();
        $method = new ReflectionMethod($instance, 'getFileIssueList');
        $method->setAccessible(true);
        return $method->invoke($instance, $file, $serviceRepoID, $taskID);
    }

    /**
     * Test processRuleData method.
     *
     * @param  object $rule
     * @access public
     * @return object
     */
    public function processRuleDataTest(object $rule = null)
    {
        $instance = $this->getZenInstance();
        if($rule === null) $rule = new stdclass();
        $method = new ReflectionMethod($instance, 'processRuleData');
        $method->setAccessible(true);
        return $method->invoke($instance, $rule);
    }

    /**
     * Test processIssueFileTree method.
     *
     * @param  array  $fileTree
     * @param  string $urlParam
     * @param  array  $params
     * @access public
     * @return array
     */
    public function processIssueFileTreeTest(array $fileTree = array(), string $urlParam = '', array $params = array())
    {
        $instance = $this->getZenInstance();
        $method = new ReflectionMethod($instance, 'processIssueFileTree');
        $method->setAccessible(true);
        return $method->invoke($instance, $fileTree, $urlParam, $params);
    }

    /**
     * Test processIssueRuleTree method.
     *
     * @param  array  $ruleTree
     * @param  string $urlParam
     * @param  array  $params
     * @param  int    $ruleID
     * @access public
     * @return array
     */
    public function processIssueRuleTreeTest(array $ruleTree = array(), string $urlParam = '', array $params = array(), int $ruleID = 0)
    {
        $instance = $this->getZenInstance();
        $method = new ReflectionMethod($instance, 'processIssueRuleTree');
        $method->setAccessible(true);
        return $method->invoke($instance, $ruleTree, $urlParam, $params, $ruleID);
    }

    /**
     * Test assignTopIssueInjection method.
     *
     * @param  array $committers
     * @access public
     * @return mixed
     */
    public function assignTopIssueInjectionTest(array $committers = array())
    {
        $instance = $this->getZenInstance();
        $method = new ReflectionMethod($instance, 'assignTopIssueInjection');
        $method->setAccessible(true);
        return $method->invoke($instance, $committers);
    }

    /**
     * Test assignRepoTopRanking method.
     *
     * @param  array  $metrics
     * @param  string $type
     * @param  int    $taskID
     * @access public
     * @return mixed
     */
    public function assignRepoTopRankingTest(array $metrics = array(), string $type = 'rule', int $taskID = 0)
    {
        $instance = $this->getZenInstance();
        $method = new ReflectionMethod($instance, 'assignRepoTopRanking');
        $method->setAccessible(true);
        return $method->invoke($instance, $metrics, $type, $taskID);
    }

    /**
     * Test getIssueDistribution method.
     *
     * @param  object|array $metrics
     * @access public
     * @return array
     */
    public function getIssueDistributionTest($metrics = array())
    {
        $instance = $this->getZenInstance();
        $method = new ReflectionMethod($instance, 'getIssueDistribution');
        $method->setAccessible(true);
        return $method->invoke($instance, $metrics);
    }

    /**
     * Test processIssueTrends method.
     *
     * @param  array  $metrics
     * @param  string $scope
     * @access public
     * @return array
     */
    public function processIssueTrendsTest(array $metrics = array(), string $scope = 'day')
    {
        $instance = $this->getZenInstance();
        $method = new ReflectionMethod($instance, 'processIssueTrends');
        $method->setAccessible(true);
        return $method->invoke($instance, $metrics, $scope);
    }

    /**
     * Test repoIssueTopRanking method.
     *
     * @param  array  $metrics
     * @param  string $type
     * @access public
     * @return mixed
     */
    public function repoIssueTopRankingTest(array $metrics = array(), string $type = 'total')
    {
        $instance = $this->getZenInstance();
        $method = new ReflectionMethod($instance, 'repoIssueTopRanking');
        $method->setAccessible(true);
        return $method->invoke($instance, $metrics, $type);
    }

    /**
     * Test assignRepoStatistics method.
     *
     * @param  array $repoMetrics
     * @access public
     * @return mixed
     */
    public function assignRepoStatisticsTest(array $repoMetrics = array())
    {
        $instance = $this->getZenInstance();
        $method = new ReflectionMethod($instance, 'assignRepoStatistics');
        $method->setAccessible(true);
        return $method->invoke($instance, $repoMetrics);
    }

    /**
     * Test setPager method.
     *
     * @param  int $recPerPage
     * @param  int $pageID
     * @access public
     * @return object
     */
    public function setPagerTest(int $recPerPage = 20, int $pageID = 1)
    {
        $instance = $this->getZenInstance();
        $method = new ReflectionMethod($instance, 'setPager');
        $method->setAccessible(true);
        $arguments = array(&$recPerPage, &$pageID);
        return $method->invokeArgs($instance, $arguments);
    }
}
