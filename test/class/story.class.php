<?php
class storyTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('story');
    } 
    
    /**
     * Test get by id.
     * 
     * @param  int    $storyID 
     * @param  int    $version 
     * @access public
     * @return void
     */
    public function getByIdTest($storyID, $version = 0)
    {
        $story = $this->objectModel->getById($storyID, $version);

        if(dao::isError()) return dao::getError();

        return $story;
    }

    /**
     * Test get by list.
     * 
     * @param  int    $storyIdList 
     * @param  string $type 
     * @access public
     * @return void
     */
    public function getByListTest($storyIdList = 0, $type = 'story')
    {
        $stories = $this->objectModel->getByList($storyIdList, $type);

        if(dao::isError()) return dao::getError();

        return $stories;
    }

    /**
     * Test get test stories.
     * 
     * @param  array  $storyIdList 
     * @param  int    $executionID 
     * @access public
     * @return void
     */
    public function getTestStoriesTest($storyIdList, $executionID)
    {
        $objects = $this->objectModel->getTestStories($storyIdList, $executionID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get story specs.
     * 
     * @param  array $storyIdList 
     * @access public
     * @return void
     */
    public function getStorySpecsTest($storyIdList)
    {
        $objects = $this->objectModel->getStorySpecs($storyIdList);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get affected scope.
     * 
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function getAffectedScopeTest($storyID)
    {
        global $tester;
        $story = $tester->loadModel('story')->getById($storyID); 
        $scope = $this->objectModel->getAffectedScope($story);

        if(dao::isError()) return dao::getError();

        return $scope;
    }

    /**
     * Test get requierements.
     * 
     * @param  int    $productID 
     * @access public
     * @return void
     */
    public function getRequierementsTest($productID)
    {
        $requirements = $this->objectModel->getRequierements($productID);

        if(dao::isError()) return dao::getError();

        return $requirements;
    }

    /**
     * Test create story.
     * 
     * @param  int    $executionID 
     * @param  int    $bugID 
     * @param  string $from 
     * @param  string $extra 
     * @access public
     * @return void
     */
    public function createTest($executionID = 0, $bugID = 0, $from = '', $extra = '', $params)
    {
        $_POST  = $params;
        $result = $this->objectModel->create($executionID, $bugID, $from, $extra);
        unset($_POST);

        if(dao::isError()) return dao::getError();

        global $tester;
        $storyID = $result['id'];
        return $tester->loadModel('story')->getById($storyID); 
    }

    /**
     * Test create story from gitlab issue.
     * 
     * @param  int    $story 
     * @param  int    $executionID 
     * @access public
     * @return void
     */
    public function createStoryFromGitlabIssueTest($story, $executionID)
    {
        $storyID = $this->objectModel->createStoryFromGitlabIssue($story, $executionID);

        if(dao::isError()) return dao::getError();

        global $tester;
        return $tester->loadModel('story')->getById($storyID); 
    }

    /**
     * Test batch create stories.
     * 
     * @param  int    $productID 
     * @param  int    $branch 
     * @param  string $type 
     * @param  array  $params
     * @access public
     * @return void
     */
    public function batchCreateTest($productID = 0, $branch = 0, $type = 'story', $params)
    {
        $_POST   = $params;
        $results = $this->objectModel->batchCreate($productID, $branch, $type);
        unset($_POST);

        if(dao::isError()) return dao::getError();

        foreach($results as $result) $storyIdList[] = $result->storyID;

        global $tester;
        $stories = $tester->loadModel('story')->getByList($storyIdList);
        return $stories;
    }

    /**
     * Test change story.
     * 
     * @param  int    $storyID 
     * @param  array  $params
     * @access public
     * @return void
     */
    public function changeTest($storyID, $params)
    {
        $_POST = $params;
        $this->objectModel->change($storyID);
        unset($_POST);

        if(dao::isError()) return dao::getError();

        global $tester;
        return $tester->loadModel('story')->getById($storyID);
    }

    /**
     * Test update story.
     * 
     * @param  int    $storyID 
     * @param  array  $params
     * @access public
     * @return void
     */
    public function updateTest($storyID, $params)
    {
        $_POST = $params;
        $this->objectModel->update($storyID);
        unset($_POST);

        if(dao::isError()) return dao::getError();

        return $this->objectModel->getById($storyID);
    }

    /**
     * Test update story order of plan.
     * 
     * @param  int    $storyID 
     * @param  string $planIDList 
     * @param  string $oldPlanIDList 
     * @access public
     * @return void
     */
    public function updateStoryOrderOfPlanTest($storyID, $planIDList = '', $oldPlanIDList = '')
    {
        $this->objectModel->updateStoryOrderOfPlan($storyID, $planIDList, $oldPlanIDList);

        if(dao::isError()) return dao::getError();

        global $tester;
        return $tester->dao->select('*')->from(TABLE_PLANSTORY)->where('plan')->in($planIDList)->fetchAll();
    }

    /**
     * Test compute estimate.
     * 
     * @param  int    $storyID 
     * @access public
     * @return void
     */
    public function computeEstimateTest($storyID)
    {
        $this->objectModel->computeEstimate($storyID);

        if(dao::isError()) return dao::getError();

        return $this->objectModel->getByID($storyID);
    }

    /**
     * Test batch update stories.
     * 
     * @access public
     * @return void
     */
    public function batchUpdateTest($params)
    {
        $_POST      = $params;
        $allStories = $this->objectModel->batchUpdate();
        unset($_POST);

        if(dao::isError()) return dao::getError();

        $storyIdList = array_keys($allStories);
        return $this->objectModel->getByList($storyIdList);
    }

    /**
     * Test review story.
     * 
     * @param  int    $storyID 
     * @param  array  $params
     * @access public
     * @return void
     */
    public function reviewTest($storyID, $params)
    {
        $_POST = $params;
        $changes = $this->objectModel->review($storyID);
        unset($_POST);

        if(dao::isError()) return dao::getError();

        return $this->objectModel->getByID($storyID);
    }

    /**
     * Test batch review.
     * 
     * @param  int    $storyIdList 
     * @param  int    $result 
     * @param  int    $reason 
     * @access public
     * @return void
     */
    public function batchReviewTest($storyIdList, $result, $reason)
    {
        $actions = $this->objectModel->batchReview($storyIdList, $result, $reason);

        if(dao::isError()) return dao::getError();

        $storyIdList = array_keys($actions);
        return $this->objectModel->getByList($storyIdList);
    }

    /**
     * Test story subdivide.
     * 
     * @param  int    $storyID 
     * @param  array  $stories 
     * @access public
     * @return void
     */
    public function subdivideTest($storyID, $stories, $type)
    {
        $this->objectModel->subdivide($storyID, $stories);

        if(dao::isError()) return dao::getError();

        global $tester;
        if($type == 'requirement')
        {
            return $tester->dao->select('*')->from(TABLE_RELATION)->where('AID')->eq($storyID)->andWhere('AType')->eq($type)->fetchAll();
        }
        else
        {
            return $this->objectModel->getById($storyID);
        }
    }

    /**
     * Test close a story.
     * 
     * @param  int    $storyID 
     * @param  array  $params
     * @access public
     * @return void
     */
    public function closeTest($storyID, $params)
    {
        $_POST   = $params;
        $changes = $this->objectModel->close($storyID);
        unset($_POST);

        if(dao::isError()) return dao::getError();

        return $changes;
    }

    /**
     * Test batch close story.
     * 
     * @param  array $params 
     * @access public
     * @return void
     */
    public function batchCloseTest($params)
    {
        $_POST   = $params;
        $changes = $this->objectModel->batchClose();
        unset($_POST);

        if(dao::isError()) return dao::getError();

        $storyIdList = array_keys($changes);
        return $this->objectModel->getByList($storyIdList);
    }

    /**
     * Test batch change story module.
     * 
     * @param  int    $storyIdList 
     * @param  int    $moduleID 
     * @access public
     * @return void
     */
    public function batchChangeModuleTest($storyIdList, $moduleID)
    {
        $changes = $this->objectModel->batchChangeModule($storyIdList, $moduleID);

        if(dao::isError()) return dao::getError();

        $storyIdList = array_keys($changes);
        return $this->objectModel->getByList($storyIdList);
    }

    /**
     * Test batch change story plan.
     * 
     * @param  arary  $storyIdList 
     * @param  int    $planID 
     * @param  int    $oldPlanID 
     * @access public
     * @return void
     */
    public function batchChangePlanTest($storyIdList, $planID, $oldPlanID = 0)
    {
        $changes = $this->objectModel->batchChangePlan($storyIdList, $planID, $oldPlanID);

        if(dao::isError()) return dao::getError();

        $storyIdList = array_keys($changes);
        return $this->objectModel->getByList($storyIdList);
    }

    /**
     * Test batch change story branch.
     * 
     * @param  arary  $storyIdList 
     * @param  int    $branchID 
     * @param  string $confirm 
     * @param  array  $plans 
     * @access public
     * @return void
     */
    public function batchChangeBranchTest($storyIdList, $branchID, $confirm = '', $plans = array())
    {
        $changes = $this->objectModel->batchChangeBranch($storyIdList, $branchID, $confirm, $plans);

        if(dao::isError()) return dao::getError();

        $storyIdList = array_keys($changes);
        return $this->objectModel->getByList($storyIdList);
    }

    /**
     * Test batch change stage.
     * 
     * @param  arrau  $storyIdList 
     * @param  string $stage 
     * @access public
     * @return void
     */
    public function batchChangeStageTest($storyIdList, $stage)
    {
        $changes = $this->objectModel->batchChangeStage($storyIdList, $stage);

        if(dao::isError()) return dao::getError();

        $storyIdList = array_keys($changes);
        return $this->objectModel->getByList($storyIdList);
    }

    /**
     * Test story batch to task.
     * 
     * @param  int    $executionID 
     * @param  int    $projectID 
     * @param  array  $params
     * @access public
     * @return void
     */
    public function batchToTaskTest($executionID, $projectID = 0, $params)
    {
        $_POST      = $params;
        $taskIdList = $this->objectModel->batchToTask($executionID, $projectID);
        unset($params);

        if(dao::isError()) return dao::getError();

        global $tester;
        return $tester->loadModel('task')->getByList($taskIdList);
    }

    /**
     * Test assign a story.
     * 
     * @param  int    $storyID 
     * @param  string $assignedTo
     * @access public
     * @return void
     */
    public function assignTest($storyID, $assignedTo)
    {
        $_POST['assignedTo'] = $assignedTo;
        $this->objectModel->assign($storyID);
        unset($_POST);

        if(dao::isError()) return dao::getError();

        return $this->objectModel->getById($storyID);
    }

    /**
     * Test batch assign story.
     * 
     * @param  array  $params 
     * @access public
     * @return void
     */
    public function batchAssignToTest($params)
    {
        $_POST   = $params;
        $changes = $this->objectModel->batchAssignTo();
        unset($_POST);

        if(dao::isError()) return dao::getError();

        $storyIdList = array_keys($changes);
        return $this->objectModel->getByList($storyIdList);
    }

    public function getProductStoriesTest($productID = 0, $branch = 0, $moduleIdList = 0, $status = 'all', $type = 'story', $orderBy = 'id_desc', $hasParent = true, $excludeStories = '', $pager = null)
    {
        $objects = $this->objectModel->getProductStories($productID = 0, $branch = 0, $moduleIdList = 0, $status = 'all', $type = 'story', $orderBy = 'id_desc', $hasParent = true, $excludeStories = '', $pager = null);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getProductStoryPairsTest($productID = 0, $branch = 0, $moduleIdList = 0, $status = 'all', $order = 'id_desc', $limit = 0, $type = 'full', $storyType = 'story', $hasParent = true)
    {
        $objects = $this->objectModel->getProductStoryPairs($productID = 0, $branch = 0, $moduleIdList = 0, $status = 'all', $order = 'id_desc', $limit = 0, $type = 'full', $storyType = 'story', $hasParent = true);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getByAssignedToTest($productID, $branch, $modules, $account, $type = 'story', $orderBy = '', $pager = null)
    {
        $objects = $this->objectModel->getByAssignedTo($productID, $branch, $modules, $account, $type = 'story', $orderBy = '', $pager = null);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getByOpenedByTest($productID, $branch, $modules, $account, $type = 'story', $orderBy = '', $pager = null)
    {
        $objects = $this->objectModel->getByOpenedBy($productID, $branch, $modules, $account, $type = 'story', $orderBy = '', $pager = null);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getByReviewedByTest($productID, $branch, $modules, $account, $type = 'story', $orderBy = '', $pager = null)
    {
        $objects = $this->objectModel->getByReviewedBy($productID, $branch, $modules, $account, $type = 'story', $orderBy = '', $pager = null);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getByReviewByTest($productID, $branch, $modules, $account, $type = 'story', $orderBy = '', $pager = null)
    {
        $objects = $this->objectModel->getByReviewBy($productID, $branch, $modules, $account, $type = 'story', $orderBy = '', $pager = null);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getByClosedByTest($productID, $branch, $modules, $account, $type = 'story', $orderBy = '', $pager = null)
    {
        $objects = $this->objectModel->getByClosedBy($productID, $branch, $modules, $account, $type = 'story', $orderBy = '', $pager = null);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getByStatusTest($productID, $branch, $modules, $status, $type = 'story', $orderBy = '', $pager = null)
    {
        $objects = $this->objectModel->getByStatus($productID, $branch, $modules, $status, $type = 'story', $orderBy = '', $pager = null);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getByPlanTest($productID, $branch, $modules, $plan, $type = 'story', $orderBy = '', $pager = null)
    {
        $objects = $this->objectModel->getByPlan($productID, $branch, $modules, $plan, $type = 'story', $orderBy = '', $pager = null);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getByFieldTest($productID, $branch, $modules, $fieldName, $fieldValue, $type = 'story', $orderBy = '', $pager = null, $operator = 'equal')
    {
        $objects = $this->objectModel->getByField($productID, $branch, $modules, $fieldName, $fieldValue, $type = 'story', $orderBy = '', $pager = null, $operator = 'equal');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function get2BeClosedTest($productID, $branch, $modules, $type = 'story', $orderBy = '', $pager = null)
    {
        $objects = $this->objectModel->get2BeClosed($productID, $branch, $modules, $type = 'story', $orderBy = '', $pager = null);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getBySearchTest($productID, $branch = '', $queryID = 0, $orderBy = '', $executionID = '', $type = 'story', $excludeStories = '', $pager = null)
    {
        $objects = $this->objectModel->getBySearch($productID, $branch = '', $queryID = 0, $orderBy = '', $executionID = '', $type = 'story', $excludeStories = '', $pager = null);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getBySQLTest($productID, $sql, $orderBy, $pager = null, $type = 'story')
    {
        $objects = $this->objectModel->getBySQL($productID, $sql, $orderBy, $pager = null, $type = 'story');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getExecutionStoriesTest($executionID = 0, $productID = 0, $branch = 0, $orderBy = 't1.`order`_desc', $type = 'byModule', $param = 0, $storyType = 'story', $excludeStories = '', $pager = null)
    {
        $objects = $this->objectModel->getExecutionStories($executionID = 0, $productID = 0, $branch = 0, $orderBy = 't1.`order`_desc', $type = 'byModule', $param = 0, $storyType = 'story', $excludeStories = '', $pager = null);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getExecutionStoryPairsTest($executionID = 0, $productID = 0, $branch = 'all', $moduleIdList = 0, $type = 'full', $status = 'all')
    {
        $objects = $this->objectModel->getExecutionStoryPairs($executionID = 0, $productID = 0, $branch = 'all', $moduleIdList = 0, $type = 'full', $status = 'all');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getPlanStoriesTest($planID, $status = 'all', $orderBy = 'id_desc', $pager = null)
    {
        $objects = $this->objectModel->getPlanStories($planID, $status = 'all', $orderBy = 'id_desc', $pager = null);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getPlanStoryPairsTest($planID, $status = 'all', $orderBy = 'id_desc', $pager = null)
    {
        $objects = $this->objectModel->getPlanStoryPairs($planID, $status = 'all', $orderBy = 'id_desc', $pager = null);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getStoriesByPlanIdListTest($planIdList = '')
    {
        $objects = $this->objectModel->getStoriesByPlanIdList($planIdList = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getParentStoryPairsTest($productID, $append = '')
    {
        $objects = $this->objectModel->getParentStoryPairs($productID, $append = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getUserStoriesTest($account, $type = 'assignedTo', $orderBy = 'id_desc', $pager = null, $storyType = 'story', $includeLibStories = true)
    {
        $objects = $this->objectModel->getUserStories($account, $type = 'assignedTo', $orderBy = 'id_desc', $pager = null, $storyType = 'story', $includeLibStories = true);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getUserStoryPairsTest($account, $limit = 10, $type = 'story', $skipProductIDList = array())
    {
        $objects = $this->objectModel->getUserStoryPairs($account, $limit = 10, $type = 'story', $skipProductIDList = array());

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getIdListWithTaskTest($executionID)
    {
        $objects = $this->objectModel->getIdListWithTask($executionID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getTeamMembersTest($storyID, $actionType)
    {
        $objects = $this->objectModel->getTeamMembers($storyID, $actionType);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getVersionTest($storyID)
    {
        $objects = $this->objectModel->getVersion($storyID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getVersionsTest($storyID)
    {
        $objects = $this->objectModel->getVersions($storyID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getZeroCaseTest($productID, $branchID = 0, $orderBy = 'id_desc')
    {
        $objects = $this->objectModel->getZeroCase($productID, $branchID = 0, $orderBy = 'id_desc');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getChangedStoriesTest($story)
    {
        $objects = $this->objectModel->getChangedStories($story);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getAllStorySortTest($planID, $planOrder)
    {
        $objects = $this->objectModel->getAllStorySort($planID, $planOrder);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function batchGetStoryStageTest($stories)
    {
        $objects = $this->objectModel->batchGetStoryStage($stories);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function checkNeedConfirmTest($dataList)
    {
        $objects = $this->objectModel->checkNeedConfirm($dataList);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function formatStoriesTest($stories, $type = 'full', $limit = 0)
    {
        $objects = $this->objectModel->formatStories($stories, $type = 'full', $limit = 0);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function extractAccountsFromListTest($stories)
    {
        $objects = $this->objectModel->extractAccountsFromList($stories);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function extractAccountsFromSingleTest($story)
    {
        $objects = $this->objectModel->extractAccountsFromSingle($story);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function mergeChartOptionTest($chartType)
    {
        $objects = $this->objectModel->mergeChartOption($chartType);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getDataOfStorysPerProductTest()
    {
        $objects = $this->objectModel->getDataOfStorysPerProduct();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getDataOfStorysPerModuleTest()
    {
        $objects = $this->objectModel->getDataOfStorysPerModule();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getDataOfStorysPerSourceTest()
    {
        $objects = $this->objectModel->getDataOfStorysPerSource();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getDataOfStorysPerPlanTest()
    {
        $objects = $this->objectModel->getDataOfStorysPerPlan();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getDataOfStorysPerStatusTest()
    {
        $objects = $this->objectModel->getDataOfStorysPerStatus();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getDataOfStorysPerStageTest()
    {
        $objects = $this->objectModel->getDataOfStorysPerStage();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getDataOfStorysPerPriTest()
    {
        $objects = $this->objectModel->getDataOfStorysPerPri();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getDataOfStorysPerEstimateTest()
    {
        $objects = $this->objectModel->getDataOfStorysPerEstimate();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getDataOfStorysPerOpenedByTest()
    {
        $objects = $this->objectModel->getDataOfStorysPerOpenedBy();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getDataOfStorysPerAssignedToTest()
    {
        $objects = $this->objectModel->getDataOfStorysPerAssignedTo();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getDataOfStorysPerClosedReasonTest()
    {
        $objects = $this->objectModel->getDataOfStorysPerClosedReason();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getDataOfStorysPerChangeTest()
    {
        $objects = $this->objectModel->getDataOfStorysPerChange();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getKanbanGroupDataTest($stories)
    {
        $objects = $this->objectModel->getKanbanGroupData($stories);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getToAndCcListTest($story, $actionType)
    {
        $objects = $this->objectModel->getToAndCcList($story, $actionType);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function mergePlanTitleTest($productID, $stories, $branch = 0, $type = 'story')
    {
        $objects = $this->objectModel->mergePlanTitle($productID, $stories, $branch = 0, $type = 'story');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function mergeReviewerTest($stories, $isObject = false)
    {
        $objects = $this->objectModel->mergeReviewer($stories, $isObject = false);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function printCellTest($col, $story, $users, $branches, $storyStages, $modulePairs = array(), $storyTasks = array(), $storyBugs = array(), $storyCases = array(), $mode = 'datatable', $storyType = 'story')
    {
        $objects = $this->objectModel->printCell($col, $story, $users, $branches, $storyStages, $modulePairs = array(), $storyTasks = array(), $storyBugs = array(), $storyCases = array(), $mode = 'datatable', $storyType = 'story');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function printAssignedHtmlTest($story, $users)
    {
        $objects = $this->objectModel->printAssignedHtml($story, $users);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function reportConditionTest()
    {
        $objects = $this->objectModel->reportCondition();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function checkForceReviewTest()
    {
        $objects = $this->objectModel->checkForceReview();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getTracksTest($productID = 0, $branch = 0, $projectID = 0, $pager = null)
    {
        $objects = $this->objectModel->getTracks($productID = 0, $branch = 0, $projectID = 0, $pager = null);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getTrackByIDTest($storyID)
    {
        $objects = $this->objectModel->getTrackByID($storyID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getStoryRelationTest($storyID, $storyType, $fields = array())
    {
        $objects = $this->objectModel->getStoryRelation($storyID, $storyType, $fields = array());

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function linkStoryTest($executionID, $productID, $storyID)
    {
        $objects = $this->objectModel->linkStory($executionID, $productID, $storyID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function linkStoriesTest($storyID)
    {
        $objects = $this->objectModel->linkStories($storyID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function unlinkStoryTest($storyID, $linkedStoryID)
    {
        $objects = $this->objectModel->unlinkStory($storyID, $linkedStoryID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getRelationTest($storyID, $storyType, $fields = array())
    {
        $objects = $this->objectModel->getRelation($storyID, $storyType, $fields = array());

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getStoryRelationCountsTest($storyID, $storyType = '')
    {
        $objects = $this->objectModel->getStoryRelationCounts($storyID, $storyType = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getEstimateInfoTest($storyID, $round = 0)
    {
        $objects = $this->objectModel->getEstimateInfo($storyID, $round = 0);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getEstimateRoundsTest($storyID)
    {
        $objects = $this->objectModel->getEstimateRounds($storyID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function saveEstimateInfoTest($storyID)
    {
        $objects = $this->objectModel->saveEstimateInfo($storyID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function sortStoriesOfPlanTest($planID, $sortIDList, $orderBy = 'id_desc', $pageID = 1, $recPerPage = 100)
    {
        $objects = $this->objectModel->sortStoriesOfPlan($planID, $sortIDList, $orderBy = 'id_desc', $pageID = 1, $recPerPage = 100);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function replaceURLangTest($type)
    {
        $objects = $this->objectModel->replaceURLang($type);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getReviewerPairsTest($storyID, $version)
    {
        $objects = $this->objectModel->getReviewerPairs($storyID, $version);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function setStatusByReviewRulesTest($reviewerList)
    {
        $objects = $this->objectModel->setStatusByReviewRules($reviewerList);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function recordReviewActionTest($story, $result = '', $reason = '')
    {
        $objects = $this->objectModel->recordReviewAction($story, $result = '', $reason = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function updateStoryByReviewTest($storyID, $oldStory, $story)
    {
        $objects = $this->objectModel->updateStoryByReview($storyID, $oldStory, $story);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function superReviewTest($storyID, $oldStory, $story, $result = '', $reason = '')
    {
        $objects = $this->objectModel->superReview($storyID, $oldStory, $story, $result = '', $reason = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }
}
