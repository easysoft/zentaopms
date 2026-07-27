<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class codescanModelTest extends baseTest
{
    protected $moduleName = 'codescan';
    protected $className  = 'model';

    /**
     * Test isClickable method.
     *
     * @param  object $codeScan
     * @param  string $action
     * @access public
     * @return bool
     */
    public function isClickableTest(object $codeScan, string $action): bool
    {
        return $this->instance->isClickable($codeScan, $action);
    }

    /**
     * Test getScanRulesets method.
     *
     * @param  array $params
     * @access public
     * @return array|object
     */
    public function getScanRulesetsTest(array $params = array())
    {
        $result = $this->instance->getScanRulesets($params);
        if(dao::isError()) return array();
        return $result;
    }

    /**
     * Test getScanRulesetRules method.
     *
     * @param  int   $rulesetID
     * @param  array $param
     * @access public
     * @return array|object
     */
    public function getScanRulesetRulesTest(int $rulesetID = 0, array $param = array())
    {
        $result = $this->instance->getScanRulesetRules($rulesetID, $param);
        if(dao::isError()) return array();
        return $result;
    }

    /**
     * Test getScanRulesetUnlinkRules method.
     *
     * @param  int   $rulesetID
     * @param  array $param
     * @access public
     * @return array|object
     */
    public function getScanRulesetUnlinkRulesTest(int $rulesetID = 0, array $param = array())
    {
        $result = $this->instance->getScanRulesetUnlinkRules($rulesetID, $param);
        if(dao::isError()) return array();
        return $result;
    }

    /**
     * Test createRuleset method.
     *
     * @param  object $formData
     * @access public
     * @return int|false
     */
    public function createRulesetTest(object $formData)
    {
        $result = $this->instance->createRuleset($formData);
        if(dao::isError()) return array();
        return $result;
    }

    /**
     * Test getRuleset method.
     *
     * @param  int $rulesetID
     * @access public
     * @return object|false
     */
    public function getRulesetTest(int $rulesetID = 0)
    {
        $result = $this->instance->getRuleset($rulesetID);
        if(dao::isError()) return array();
        return $result;
    }

    /**
     * Test editRuleset method.
     *
     * @param  int    $ruleID
     * @param  object $formData
     * @access public
     * @return bool
     */
    public function editRulesetTest(int $ruleID = 0, object $formData = null)
    {
        if($formData === null) $formData = new stdclass();
        $result = $this->instance->editRuleset($ruleID, $formData);
        if(dao::isError()) return array();
        return $result;
    }

    /**
     * Test deleteRuleset method.
     *
     * @param  int $ruleID
     * @access public
     * @return bool
     */
    public function deleteRulesetTest(int $ruleID = 0)
    {
        $result = $this->instance->deleteRuleset($ruleID);
        if(dao::isError()) return array();
        return $result;
    }

    /**
     * Test getScanRules method.
     *
     * @param  array $params
     * @access public
     * @return array|object
     */
    public function getScanRulesTest(array $params = array())
    {
        $result = $this->instance->getScanRules($params);
        if(dao::isError()) return array();
        return $result;
    }

    /**
     * Test getScanRule method.
     *
     * @param  int $ruleID
     * @access public
     * @return object|null
     */
    public function getScanRuleTest(int $ruleID = 0)
    {
        $result = $this->instance->getScanRule($ruleID);
        if(dao::isError()) return array();
        return $result;
    }

    /**
     * Test getScanRulesConfig method.
     *
     * @param  string $type
     * @access public
     * @return array
     */
    public function getScanRulesConfigTest(string $type = '')
    {
        $result = $this->instance->getScanRulesConfig($type);
        if(dao::isError()) return array();
        return $result;
    }

    /**
     * Test updateScanRuleStatus method.
     *
     * @param  int $ruleID
     * @access public
     * @return bool
     */
    public function updateScanRuleStatusTest(int $ruleID = 0)
    {
        $result = $this->instance->updateScanRuleStatus($ruleID);
        if(dao::isError()) return array();
        return $result;
    }

    /**
     * Test updateScanRulesetStatus method.
     *
     * @param  int    $rulesetID
     * @param  string $status
     * @access public
     * @return bool|object
     */
    public function updateScanRulesetStatusTest(int $rulesetID = 0, string $status = 'disabled')
    {
        $result = $this->instance->updateScanRulesetStatus($rulesetID, $status);
        if(dao::isError()) return array();
        return $result;
    }

    /**
     * Test linkRulesInRuleset method.
     *
     * @param  int   $rulesetID
     * @param  array $rules
     * @access public
     * @return bool
     */
    public function linkRulesInRulesetTest(int $rulesetID = 0, array $rules = array())
    {
        $result = $this->instance->linkRulesInRuleset($rulesetID, $rules);
        if(dao::isError()) return array();
        return $result;
    }

    /**
     * Test unlinkRules method.
     *
     * @param  int   $rulesetID
     * @param  array $rules
     * @access public
     * @return bool
     */
    public function unlinkRulesTest(int $rulesetID = 0, array $rules = array())
    {
        $result = $this->instance->unlinkRules($rulesetID, $rules);
        if(dao::isError()) return array();
        return $result;
    }

    /**
     * Test getScanSolutions method.
     *
     * @param  array $params
     * @access public
     * @return array|object
     */
    public function getScanSolutionsTest(array $params = array())
    {
        $result = $this->instance->getScanSolutions($params);
        if(dao::isError()) return array();
        return $result;
    }

    /**
     * Test getSolution method.
     *
     * @param  int $solutionID
     * @access public
     * @return object|false
     */
    public function getSolutionTest(int $solutionID = 0)
    {
        $result = $this->instance->getSolution($solutionID);
        if(dao::isError()) return array();
        return $result;
    }

    /**
     * Test updateSolutionStatus method.
     *
     * @param  int $solutionID
     * @access public
     * @return bool
     */
    public function updateSolutionStatusTest(int $solutionID = 0)
    {
        $result = $this->instance->updateSolutionStatus($solutionID);
        if(dao::isError()) return array();
        return $result;
    }

    /**
     * Test createSolution method.
     *
     * @param  object $formData
     * @access public
     * @return int|false
     */
    public function createSolutionTest(object $formData)
    {
        $result = $this->instance->createSolution($formData);
        if(dao::isError()) return array();
        return $result;
    }

    /**
     * Test bindRulesets method.
     *
     * @param  int   $solutionID
     * @param  array $rulesets
     * @access public
     * @return bool
     */
    public function bindRulesetsTest(int $solutionID = 0, array $rulesets = array())
    {
        $result = $this->instance->bindRulesets($solutionID, $rulesets);
        if(dao::isError()) return array();
        return $result;
    }

    /**
     * Test unbindRulesets method.
     *
     * @param  int   $solutionID
     * @param  array $rulesets
     * @access public
     * @return bool
     */
    public function unbindRulesetsTest(int $solutionID = 0, array $rulesets = array())
    {
        $result = $this->instance->unbindRulesets($solutionID, $rulesets);
        if(dao::isError()) return array();
        return $result;
    }

    /**
     * Test editSolution method.
     *
     * @param  int    $solutionID
     * @param  object $formData
     * @access public
     * @return bool
     */
    public function editSolutionTest(int $solutionID = 0, object $formData = null)
    {
        if($formData === null) $formData = new stdclass();
        $result = $this->instance->editSolution($solutionID, $formData);
        if(dao::isError()) return array();
        return $result;
    }

    /**
     * Test deleteSolution method.
     *
     * @param  int $solutionID
     * @access public
     * @return bool
     */
    public function deleteSolutionTest(int $solutionID = 0)
    {
        $result = $this->instance->deleteSolution($solutionID);
        if(dao::isError()) return array();
        return $result;
    }

    /**
     * Test getSolutionUnlinkRulesets method.
     *
     * @param  int   $solutionID
     * @param  array $param
     * @access public
     * @return array|object
     */
    public function getSolutionUnlinkRulesetsTest(int $solutionID = 0, array $param = array())
    {
        $result = $this->instance->getSolutionUnlinkRulesets($solutionID, $param);
        if(dao::isError()) return array();
        return $result;
    }

    /**
     * Test linkRulesetInSolution method.
     *
     * @param  int   $solutionID
     * @param  array $rulesets
     * @access public
     * @return bool
     */
    public function linkRulesetInSolutionTest(int $solutionID = 0, array $rulesets = array())
    {
        $result = $this->instance->linkRulesetInSolution($solutionID, $rulesets);
        if(dao::isError()) return array();
        return $result;
    }

    /**
     * Test getScanPlans method.
     *
     * @param  int   $repoID
     * @param  array $params
     * @access public
     * @return array|object
     */
    public function getScanPlansTest(int $repoID = 0, array $params = array())
    {
        $result = $this->instance->getScanPlans($repoID, $params);
        if(dao::isError()) return array();
        return $result;
    }

    /**
     * Test getScanPlan method.
     *
     * @param  int $planID
     * @param  int $repoID
     * @access public
     * @return array|object
     */
    public function getScanPlanTest(int $planID = 0, int $repoID = 0)
    {
        $result = $this->instance->getScanPlan($planID, $repoID);
        if(dao::isError()) return array();
        return $result;
    }

    /**
     * Test deleteScanPlan method.
     *
     * @param  int $serviceRepoID
     * @param  int $planID
     * @access public
     * @return bool
     */
    public function deleteScanPlanTest(int $serviceRepoID = 0, int $planID = 0)
    {
        $result = $this->instance->deleteScanPlan($serviceRepoID, $planID);
        if(dao::isError()) return array();
        return $result;
    }

    /**
     * Test createPlan method.
     *
     * @param  object $formData
     * @access public
     * @return int|false
     */
    public function createPlanTest(object $formData)
    {
        $result = $this->instance->createPlan($formData);
        if(dao::isError()) return array();
        return $result;
    }

    /**
     * Test batchCreateConditions method.
     *
     * @param  int   $repoID
     * @param  int   $planID
     * @param  array $conditions
     * @access public
     * @return bool
     */
    public function batchCreateConditionsTest(int $repoID = 0, int $planID = 0, array $conditions = array())
    {
        $result = $this->instance->batchCreateConditions($repoID, $planID, $conditions);
        if(dao::isError()) return array();
        return $result;
    }

    /**
     * Test getPlanConditions method.
     *
     * @param  int $repoID
     * @param  int $planID
     * @access public
     * @return array|object
     */
    public function getPlanConditionsTest(int $repoID = 0, int $planID = 0)
    {
        $result = $this->instance->getPlanConditions($repoID, $planID);
        if(dao::isError()) return array();
        return $result;
    }

    /**
     * Test batchDeletePlanConditions method.
     *
     * @param  int   $repoID
     * @param  int   $planID
     * @param  array $conditions
     * @access public
     * @return bool
     */
    public function batchDeletePlanConditionsTest(int $repoID = 0, int $planID = 0, array $conditions = array())
    {
        $result = $this->instance->batchDeletePlanConditions($repoID, $planID, $conditions);
        if(dao::isError()) return array();
        return $result;
    }

    /**
     * Test editPlan method.
     *
     * @param  int    $repoID
     * @param  int    $planID
     * @param  object $formData
     * @access public
     * @return bool
     */
    public function editPlanTest(int $repoID = 0, int $planID = 0, object $formData = null)
    {
        if($formData === null) $formData = new stdclass();
        $result = $this->instance->editPlan($repoID, $planID, $formData);
        if(dao::isError()) return array();
        return $result;
    }

    /**
     * Test bindOrUnbindSolutions method.
     *
     * @param  int   $repoID
     * @param  int   $planID
     * @param  array $solutions
     * @param  bool  $bind
     * @access public
     * @return bool
     */
    public function bindOrUnbindSolutionsTest(int $repoID = 0, int $planID = 0, array $solutions = array(), bool $bind = true)
    {
        $result = $this->instance->bindOrUnbindSolutions($repoID, $planID, $solutions, $bind);
        if(dao::isError()) return array();
        return $result;
    }

    /**
     * Test getScanTasks method.
     *
     * @param  int   $repoID
     * @param  int   $planID
     * @param  array $params
     * @access public
     * @return array|object
     */
    public function getScanTasksTest(int $repoID = 0, int $planID = 0, array $params = array())
    {
        $result = $this->instance->getScanTasks($repoID, $planID, $params);
        if(dao::isError()) return array();
        return $result;
    }

    /**
     * Test getListByAPI method.
     *
     * @param  string $api
     * @param  array  $params
     * @access public
     * @return array|object
     */
    public function getListByAPITest(string $api = '', array $params = array())
    {
        $result = $this->instance->getListByAPI($api, $params);
        if(dao::isError()) return array();
        return $result;
    }

    /**
     * Test getScanTask method.
     *
     * @param  int $taskID
     * @access public
     * @return array|object
     */
    public function getScanTaskTest(int $taskID = 0)
    {
        $result = $this->instance->getScanTask($taskID);
        if(dao::isError()) return array();
        return $result;
    }

    /**
     * Test formatDuration method.
     *
     * @param  int $duration
     * @access public
     * @return string
     */
    public function formatDurationTest(int $duration = 0)
    {
        return $this->instance->formatDuration($duration);
    }

    /**
     * Test execScanTask method.
     *
     * @param  object $plan
     * @param  string $branch
     * @access public
     * @return object|false
     */
    public function execScanTaskTest(object $plan = null, string $branch = '')
    {
        if($plan === null) $plan = new stdclass();
        $result = $this->instance->execScanTask($plan, $branch);
        if(dao::isError()) return array();
        return $result;
    }

    /**
     * Test changeIssueState method.
     *
     * @param  int|array $issueIdList
     * @param  string    $status
     * @param  string    $solution
     * @param  string    $solutionDate
     * @param  int       $ignoreDate
     * @access public
     * @return bool
     */
    public function changeIssueStateTest($issueIdList = 0, string $status = '', string $solution = '', string $solutionDate = '', int $ignoreDate = 0)
    {
        $result = $this->instance->changeIssueState($issueIdList, $status, $solution, $solutionDate, $ignoreDate);
        if(dao::isError()) return array();
        return $result;
    }

    /**
     * Test getScanIssueList method.
     *
     * @param  int   $taskID
     * @param  array $params
     * @access public
     * @return object|array
     */
    public function getScanIssueListTest(int $taskID = 0, array $params = array())
    {
        $result = $this->instance->getScanIssueList($taskID, $params);
        if(dao::isError()) return array();
        return $result;
    }

    /**
     * Test getScanIssueListByIdList method.
     *
     * @param  array $issueIdList
     * @access public
     * @return object|array
     */
    public function getScanIssueListByIdListTest(array $issueIdList = array())
    {
        $result = $this->instance->getScanIssueListByIdList($issueIdList);
        if(dao::isError()) return array();
        return $result;
    }

    /**
     * Test getScanIssue method.
     *
     * @param  int  $issueID
     * @param  bool $showBug
     * @access public
     * @return array|object
     */
    public function getScanIssueTest(int $issueID = 0, bool $showBug = true)
    {
        $result = $this->instance->getScanIssue($issueID, $showBug);
        if(dao::isError()) return array();
        return $result;
    }

    /**
     * Test processIssueSnipe method.
     *
     * @param  object $issue
     * @access public
     * @return object
     */
    public function processIssueSnipeTest(object $issue = null)
    {
        if($issue === null) $issue = new stdclass();
        return $this->instance->processIssueSnipe($issue);
    }

    /**
     * Test getLinkedBugList method.
     *
     * @param  array|int $issueList
     * @param  string    $status
     * @access public
     * @return array
     */
    public function getLinkedBugListTest($issueList = array(), string $status = '')
    {
        $result = $this->instance->getLinkedBugList($issueList, $status);
        if(dao::isError()) return array();
        return $result;
    }

    /**
     * Test getIssueResolvedByTop method.
     *
     * @param  int $repoID
     * @param  int $top
     * @access public
     * @return array
     */
    public function getIssueResolvedByTopTest(int $repoID = 0, int $top = 10)
    {
        $result = $this->instance->getIssueResolvedByTop($repoID, $top);
        if(dao::isError()) return array();
        return $result;
    }

    /**
     * Test getIssueTreeList method.
     *
     * @param  int    $repoID
     * @param  int    $taskID
     * @param  string $type
     * @access public
     * @return array
     */
    public function getIssueTreeListTest(int $repoID = 0, int $taskID = 0, string $type = 'file')
    {
        $result = $this->instance->getIssueTreeList($repoID, $taskID, $type);
        if(dao::isError()) return array();
        return $result;
    }

    /**
     * Test formatNumberToW method.
     *
     * @param  int $number
     * @access public
     * @return string
     */
    public function formatNumberToWTest(int $number = 0)
    {
        return $this->instance->formatNumberToW($number);
    }

    /**
     * Test getRepoMetrics method.
     *
     * @param  int $repoID
     * @param  int $taskID
     * @access public
     * @return object|array
     */
    public function getRepoMetricsTest(int $repoID = 0, int $taskID = 0)
    {
        $result = $this->instance->getRepoMetrics($repoID, $taskID);
        if(dao::isError()) return array();
        return $result;
    }

    /**
     * Test refreshOverview method.
     *
     * @access public
     * @return bool
     */
    public function refreshOverviewTest()
    {
        $result = $this->instance->refreshOverview();
        if(dao::isError()) return array();
        return $result;
    }

    /**
     * Test getLastExecuteTime method.
     *
     * @access public
     * @return string
     */
    public function getLastExecuteTimeTest()
    {
        return $this->instance->getLastExecuteTime();
    }

    /**
     * Test getIssueTrendsByRepo method.
     *
     * @param  int    $repoID
     * @param  int    $beginDate
     * @param  string $scope
     * @access public
     * @return array
     */
    public function getIssueTrendsByRepoTest(int $repoID = 0, int $beginDate = 0, string $scope = 'day')
    {
        $result = $this->instance->getIssueTrendsByRepo($repoID, $beginDate, $scope);
        if(dao::isError()) return array();
        return $result;
    }

    /**
     * Test resendTask method.
     *
     * @param  int $taskID
     * @access public
     * @return bool
     */
    public function resendTaskTest(int $taskID = 0)
    {
        $result = $this->instance->resendTask($taskID);
        if(dao::isError()) return array();
        return $result;
    }
}
