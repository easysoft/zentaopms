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
     * @param  string $params
     * @access public
     * @return void
     */
    public function createTest($executionID = 0, $bugID = 0, $from = '', $extra = '', $params = '')
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
    public function batchCreateTest($productID = 0, $branch = 0, $type = 'story', $params = '')
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
    public function batchToTaskTest($executionID, $projectID = 0, $params = '')
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

    /**
     * Test get stories by assignedBy.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @access public
     * @return string
     */
    public function getByAssignedByTest($productID, $branch)
    {
        global $tester;
        $stories = $this->objectModel->getByAssignedBy($productID, $branch, $modules = array(), $account = $tester->app->user->account, $type = 'story', $orderBy = '', $pager = null);

        $title = '';
        foreach($stories as $story)
        {
            $title .= ',' . $story->title;
        }
        $title = trim($title, ',');
        $title = str_replace("'", '', $title);

        if(dao::isError()) return dao::getError();

        return $title;
    }
}
