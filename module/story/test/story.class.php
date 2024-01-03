<?php
class storyTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('story');
         su('admin');
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
     * @access public
     * @return void
     */
    public function getByListTest($storyIdList = 0, $mode = '')
    {
        $stories = $this->objectModel->getByList($storyIdList, $mode);

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
     * 测试 getAffectedProjects 方法
     * Test getAffectedProjects method
     *
     * @param  int    $storyID
     * @access public
     * @return object
     */
    public function getAffectedProjectsTest(int $storyID): object
    {
        global $tester;
        $users['admin'] = '管理员';
        $story = $tester->loadModel('story')->getById($storyID);
        return $this->objectModel->getAffectedProjects($story, $users);
    }

    /**
     * 测试 getAffectedBugs 方法
     * Test getAffectedBugs method
     *
     * @param  int    $storyID
     * @access public
     * @return object
     */
    public function getAffectedBugsTest(int $storyID): object
    {
        global $tester;
        $users['admin'] = '管理员';
        $story = $tester->loadModel('story')->getById($storyID);
        return $this->objectModel->getAffectedBugs($story, $users);
    }

    /**
     * 测试 getAffectedCases 方法
     * Test getAffectedCases method
     *
     * @param  int    $storyID
     * @access public
     * @return object
     */
    public function getAffectedCasesTest(int $storyID): object
    {
        global $tester;
        $users['admin'] = '管理员';
        $story = $tester->loadModel('story')->getById($storyID);
        return $this->objectModel->getAffectedCases($story, $users);
    }

    /**
     * 测试 getAffectedTwins 方法
     * Test getAffectedTwins method
     *
     * @param  int    $storyID
     * @access public
     * @return object
     */
    public function getAffectedTwinsTest(int $storyID): object
    {
        global $tester;
        $users['admin'] = '管理员';
        $story = $tester->loadModel('story')->getById($storyID);
        return $this->objectModel->getAffectedTwins($story, $users);
    }

    /**
     * Test get requierements.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function getRequirementsTest(int $productID): array
    {
        $requirements = $this->objectModel->getRequirements($productID);

        if(dao::isError()) return dao::getError();

        return $requirements;
    }

    /**
     * Test create story.
     *
     * @param  object $story
     * @param  int    $executionID
     * @param  int    $bugID
     * @param  string $extra
     * @access public
     * @return object|array
     */
    public function createTest(object $story, int $executionID = 0, int $bugID = 0, string $extra = ''): object|array
    {
        $storyID = $this->objectModel->create($story, $executionID, $bugID, $extra);
        if(dao::isError()) return dao::getError();

        $story = $this->objectModel->loadModel('story')->getByID($storyID);
        if($executionID) $story->linkedProjects = $this->objectModel->dao->select('*')->from(TABLE_PROJECTSTORY)->where('project')->eq($executionID)->fetchAll();
        if($bugID)       $story->linkedBug      = $this->objectModel->dao->select('*')->from(TABLE_BUG)->where('id')->eq($bugID)->fetch();
        return $story;
    }

    /**
     * Test create twins story.
     *
     * @param  object $story
     * @param  int    $executionID
     * @param  int    $bugID
     * @param  string $extra
     * @access public
     * @return object|array
     */
    public function createTwinsTest(object $story, int $executionID = 0, int $bugID = 0, string $extra = ''): object|array
    {
        $storyID = $this->objectModel->createTwins($story, $executionID, $bugID, $extra);
        if(dao::isError()) return dao::getError();

        $story = $this->objectModel->loadModel('story')->getByID($storyID);
        return $story;
    }

    /**
     * Test create story from gitlab issue.
     *
     * @param  object $story
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function createStoryFromGitlabIssueTest(object $story, int $executionID): object|array
    {
        $storyID = $this->objectModel->createStoryFromGitlabIssue($story, $executionID);

        if(dao::isError()) return dao::getError();

        return $this->objectModel->fetchById($storyID);
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
    public function batchCreateTest(int $productID = 0, int $branch = 0, string $type = 'story', array $params = array()): array
    {
        $stories = array();
        foreach($params as $field => $items)
        {
            foreach($items as $storyID => $value)
            {
                if(!isset($stories[$storyID])) $stories[$storyID] = new stdclass();
                $stories[$storyID]->$field = $value;
            }
        }
        foreach($stories as $story) $story->type = $type;

        $storyIdList = $this->objectModel->batchCreate($stories, $productID, $branch, $type);
        if(dao::isError()) return dao::getError();

        $stories = $this->objectModel->getByList($storyIdList);
        return $stories;
    }

    /**
     * Test change story.
     *
     * @param  int    $storyID
     * @param  object $params
     * @access public
     * @return object|array
     */
    public function changeTest(int $storyID, object $params): object|array
    {
        $this->objectModel->change($storyID, $params);
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
        $this->objectModel->update($storyID, $params);
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
     * @return object|array
     */
    public function updateStoryOrderOfPlanTest(int $storyID, string $planIDList = '', string $oldPlanIDList = ''): object|array
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
     * @return array
     */
    public function computeEstimateTest(int $storyID): array
    {
        $oldStory = $this->objectModel->getByID($storyID);
        if(empty($oldStory)) return array();

        $this->objectModel->computeEstimate($storyID);

        if(dao::isError()) return dao::getError();

        $newStory = $this->objectModel->getByID($storyID);
        return array('old' => $oldStory->estimate, 'new' => $newStory->estimate);
    }

    /**
     * Test batch update stories.
     *
     * @param  array  $stories
     * @access public
     * @return array
     */
    public function batchUpdateTest(array $stories): array
    {
        $this->objectModel->batchUpdate($stories);
        if(dao::isError()) return dao::getError();

        return $this->objectModel->dao->select('*')->from(TABLE_STORY)->where('id')->in(array_keys($stories))->fetchAll('id');
    }

    /**
     * Test review story.
     *
     * @param  int    $storyID
     * @param  object $data
     * @access public
     * @return object
     */
    public function reviewTest($storyID, $data): object
    {
        $this->objectModel->review($storyID, $data);
        return $this->objectModel->getByID($storyID);
    }

    /**
     * Test batch review.
     *
     * @param  array    $storyIdList
     * @param  string   $result
     * @param  string   $reason
     * @access public
     * @return array
     */
    public function batchReviewTest(array $storyIdList, string $result, string $reason = ''): array
    {
        $this->objectModel->batchReview($storyIdList, $result, $reason);
        return $this->objectModel->getByList($storyIdList);
    }

    /**
     * Test story subdivide.
     *
     * @param  int    $storyID
     * @param  array  $stories
     * @access public
     * @return object|array
     */
    public function subdivideTest(int $storyID, array $stories, string $type): object|array
    {
        $this->objectModel->dao->delete()->from(TABLE_RELATION)->exec();
        $this->objectModel->subdivide($storyID, $stories);

        if(dao::isError()) return dao::getError();

        global $tester;
        if($type == 'requirement')
        {
            return $tester->dao->select('*')->from(TABLE_RELATION)->fetchAll();
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
     * @param  object $postData
     * @access public
     * @return object|array|false
     */
    public function closeTest(int $storyID, object $postData)
    {
        $this->objectModel->close($storyID, $postData);
        return $this->objectModel->fetchByID($storyID);
    }

    /**
     * 测试批量关闭需求。
     * Test batch close the stories.
     *
     * @param  array  $stories
     * @access public
     * @return array
     */
    public function batchCloseTest($stories)
    {
        $this->objectModel->batchClose($stories);
        if(dao::isError()) return dao::getError();

        $storyIdList = array_keys($stories);
        return $this->objectModel->getByList($storyIdList);
    }

    /**
     * Test batch change story module.
     *
     * @param  int    $storyIdList
     * @param  int    $moduleID
     * @access public
     * @return array
     */
    public function batchChangeModuleTest(array $storyIdList, int $moduleID): array
    {
        $changes = $this->objectModel->batchChangeModule($storyIdList, $moduleID);

        if(dao::isError()) return dao::getError();

        return $this->objectModel->dao->select('*')->from(TABLE_STORY)->where('id')->in($storyIdList)->fetchAll('id');
    }

    /**
     * Test batch change story plan.
     *
     * @param  array $storyIdList
     * @param  int   $planID
     * @param  int   $oldPlanID
     * @access public
     * @return array
     */
    public function batchChangePlanTest(array $storyIdList, int $planID, int $oldPlanID = 0): array
    {
        $changes = $this->objectModel->batchChangePlan($storyIdList, $planID, $oldPlanID);

        if(dao::isError()) return dao::getError();

        $storyIdList = array_keys($changes);
        return $this->objectModel->getByList($storyIdList);
    }

    /**
     * Test batch change story branch.
     *
     * @param  array  $storyIdList
     * @param  int    $branchID
     * @param  string $confirm
     * @param  array  $plans
     * @access public
     * @return void
     */
    public function batchChangeBranchTest(array $storyIdList, int $branchID, string $confirm = '', array $plans = array())
    {
        $changes = $this->objectModel->batchChangeBranch($storyIdList, $branchID, $confirm, $plans);

        if(dao::isError()) return dao::getError();

        $storyIdList = array_keys($changes);
        return $this->objectModel->dao->select('*')->from(TABLE_STORY)->where('id')->in($storyIdList)->fetchAll('id');
    }

    /**
     * Test batch change stage.
     *
     * @param  array  $storyIdList
     * @param  string $stage
     * @access public
     * @return void
     */
    public function batchChangeStageTest($storyIdList, $stage)
    {
        $this->objectModel->batchChangeStage($storyIdList, $stage);

        if(dao::isError()) return dao::getError();

        return $this->objectModel->dao->select('*')->from(TABLE_STORY)->where('id')->in($storyIdList)->fetchAll();
    }

    /**
     * Test story batch to task.
     *
     * @access public
     * @return array
     */
    public function batchToTaskTest(): array
    {
        $task = new stdclass();
        $task->module       = 0;
        $task->story        = 0;
        $task->name         = '软件需求1';
        $task->type         = 'devel';
        $task->assignedTo   = '';
        $task->estimate     = 0;
        $task->estStarted   = date('Y-m-d');
        $task->deadline     = date('Y-m-d');
        $task->pri          = 3;
        $task->status       = 'wait';
        $task->vision       = 'rnd';
        $task->openedBy     = 'admin';
        $task->openedDate   = date('Y-m-d H:i:s');
        $task->version      = 1;
        $task->project      = 11;
        $task->execution    = 12;
        $task->left         = 0;
        $task->storyVersion = 1;
        $task->desc         = '';
        $task->mailto       = '';

        $tasks = array();
        $tasks[0] = clone $task;
        $tasks[0]->name  = '软件需求1';
        $tasks[0]->story = 1;
        $tasks[1] = clone $task;
        $tasks[1]->name  = '软件需求2';
        $tasks[1]->story = 3;
        $taskIdList = $this->objectModel->batchToTask($task->execution, $tasks);

        if(dao::isError()) return dao::getError();

        return $this->objectModel->dao->select('*')->from(TABLE_TASK)->where('id')->in($taskIdList)->orderBy('id')->fetchAll();
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
        $_POST['uid']        = '0';
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
    public function batchAssignToTest(array $storyIdList, string $assignedTo): array
    {
        $changes = $this->objectModel->batchAssignTo($storyIdList, $assignedTo);
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

    /**
     * 测试 fetchProjectStories 方法。
     * Test fetchProjectStories method.
     *
     * @param  int         $productID
     * @param  int         $projectID
     * @param  string      $type
     * @param  string      $branch
     * @param  object|null $pager
     * @access public
     * @return array
     */
    public function fetchProjectStoriesTest(int $productID, int $projectID, string $type = 'all', string $branch = '', object|null $pager = null): array
    {
        $unclosedStatus = $this->objectModel->lang->story->statusList;
        unset($unclosedStatus['closed']);

        $storyIdList = array('1,2,3,4,5,6,7');
        $storyDAO    = $this->objectModel->dao->select("DISTINCT t2.*")->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t2.product = t3.id')
            ->where('t1.project')->eq($projectID)
            ->andWhere('t2.type')->eq('story')
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t3.deleted')->eq(0);

        return $this->objectModel->fetchProjectStories($storyDAO, $productID, $type, $branch, $storyIdList, 't2.id_desc', $pager);
    }

    /**
     * 测试 fetchExecutionStories 方法。
     * Test fetchExecutionStories method.
     *
     * @param  int         $executionID
     * @param  int         $product
     * @param  object|null $pager
     * @access public
     * @return array
     */
    public function fetchExecutionStoriesTest(int $executionID, int $productID, object|null $pager = null): array
    {
        $unclosedStatus = $this->objectModel->lang->story->statusList;
        unset($unclosedStatus['closed']);

        $storyDAO = $this->objectModel->dao->select("DISTINCT t2.*")->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t2.product = t3.id')
            ->where('t1.project')->eq($executionID)
            ->andWhere('t2.type')->eq('story')
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t3.deleted')->eq(0);

        return $this->objectModel->fetchExecutionStories($storyDAO, $productID, 't2.id_desc', $pager);
    }

    /**
     * 测试 getExecutionStoriesBySearch。
     * Test getExecutionStoriesBySearch method.
     *
     * @param  int         $executionID
     * @param  int         $queryID
     * @param  int         $productID
     * @param  array       $excludeStories
     * @param  object|null $pager
     * @access public
     * @return int
     */
    public function getExecutionStoriesBySearchTest(int $executionID, int $queryID, int $productID, array $excludeStories = array(), object|null $pager = null): int
    {
        $stories = $this->objectModel->getExecutionStoriesBySearch($executionID, $queryID, $productID, 't2.id_desc', 'story', $excludeStories, $pager);
        return count($stories);
    }

    /**
     * 测试 updateTwins 方法。
     * Test updateTwins method.
     *
     * @param  array  $storyIdList
     * @access public
     * @return array
     */
    public function updateTwinsTest(array $storyIdList): array
    {
        $this->objectModel->updateTwins($storyIdList);

        if(empty($storyIdList)) return array();
        if($storyIdList)
        {
            $twins = $this->objectModel->dao->select('id,twins')->from(TABLE_STORY)->where('id')->in($storyIdList)->fetchPairs('id', 'twins');
            return array_map(function($item){return str_replace(',', ':', $item);}, $twins);
        }
    }

    /**
     * 测试 finishTodoWhenToStory 方法。
     * Test finishTodoWhenToStory.
     *
     * @param  int    $todoID
     * @param  int    $storyID
     * @access public
     * @return string
     */
    public function finishTodoWhenToStoryTest(int $todoID, int $storyID): string
    {
        $this->objectModel->finishTodoWhenToStory($todoID, $storyID);

        if(empty($todoID) or empty($storyID)) return '';
        return $this->objectModel->dao->select('id,status')->from(TABLE_TODO)->where('id')->eq($todoID)->fetch('status');
    }

    /**
     * 测试 closeBugWhenToStory 方法。
     * Test closeBugWhenToStory.
     *
     * @param  int    $bugID
     * @param  int    $storyID
     * @access public
     * @return array
     */
    public function closeBugWhenToStoryTest(int $bugID, int $storyID): array
    {
        $this->objectModel->closeBugWhenToStory($bugID, $storyID);

        if(empty($bugID) or empty($storyID)) return array();
        $bug = (array)$this->objectModel->dao->select('*')->from(TABLE_BUG)->where('id')->eq($bugID)->fetch();
        $bug['files'] = $this->objectModel->dao->select('*')->from(TABLE_FILE)->where('objectType')->eq('story')->andWhere('objectID')->eq($storyID)->fetchAll('id');
        return $bug;
    }

    /**
     * 测试 linkToExecutionForCreate 方法。
     * Test linkToExecutionForCreate method.
     *
     * @param  int    $executionID
     * @param  int    $storyID
     * @param  string $extra
     * @access public
     * @return array
     */
    public function linkToExecutionForCreateTest(int $executionID, int $storyID, string $extra = ''): array
    {
        $this->objectModel->dao->delete()->from(TABLE_PROJECTSTORY)->exec();
        $this->objectModel->dao->delete()->from(TABLE_ACTION)->exec();
        $story = $this->objectModel->dao->select('*')->from(TABLE_STORY)->where('id')->eq($storyID)->fetch();
        if(empty($story)) $story = new stdclass();

        $this->objectModel->linkToExecutionForCreate($executionID, $storyID, $story, $extra);
        return array_filter((array)$this->objectModel->dao->select('*')->from(TABLE_ACTION)->orderBy('id_desc')->limit(1)->fetch());
    }

    /**
     * 测试 doCreateURRelations 方法。
     * Test doCreateURRelations method.
     *
     * @param  int    $storyID
     * @param  array  $URList
     * @access public
     * @return array
     */
    public function doCreateURRelationsTest(int $storyID, array $URList): array
    {
        $this->objectModel->dao->delete()->from(TABLE_RELATION)->exec();
        $this->objectModel->doCreateURRelations($storyID, $URList);

        if(dao::isError()) return dao::getError();
        return $this->objectModel->dao->select('*')->from(TABLE_RELATION)->fetchAll();
    }

    /**
     * 测试 doCreateReviewer 方法。
     * Test doCreateReviewer method.
     *
     * @param  int    $storyID
     * @param  array  $reviewer
     * @access public
     * @return array
     */
    public function doCreateReviewerTest(int $storyID, array $reviewer): array
    {
        $this->objectModel->dao->delete()->from(TABLE_STORYREVIEW)->exec();
        $this->objectModel->doCreateReviewer($storyID, $reviewer);

        if(dao::isError()) return dao::getError();
        return $this->objectModel->dao->select('*')->from(TABLE_STORYREVIEW)->fetchAll();
    }

    /**
     * 测试 doUpdateReviewer 方法。
     * Test doUpdateReviewer method.
     *
     * @param  int    $storyID
     * @param  array  $reviewer
     * @access public
     * @return array
     */
    public function doUpdateReviewerTest(int $storyID, array $reviewer): array
    {
        $this->objectModel->doUpdateReviewer($storyID, (object)$reviewer);
        return $this->objectModel->dao->select('*')->from(TABLE_STORYREVIEW)->where('story')->eq($storyID)->fetchAll();
    }

    /**
     * 测试 doCreateSpec 方法。
     * Test doCreateSpec method.
     *
     * @param  int    $storyID
     * @param  object $story
     * @param  array  $files
     * @access public
     * @return array
     */
    public function doCreateSpecTest(int $storyID, object $story, array $files = array()): array
    {
        $this->objectModel->dao->delete()->from(TABLE_STORYSPEC)->exec();
        $this->objectModel->doCreateSpec($storyID, $story, $files);

        if(dao::isError()) return dao::getError();
        return $this->objectModel->dao->select('*')->from(TABLE_STORYSPEC)->fetchAll();
    }

    /**
     * 测试 doUpdateSpec 方法。
     * Test doUpdateSpec method.
     *
     * @param  int    $storyID
     * @param  object $story
     * @param  object $oldStory
     * @param  array  $addedFiles
     * @access public
     * @return object|array
     */
    public function doUpdateSpecTest(int $storyID, object $story, object $oldStory, array $addedFiles = array()): object|array
    {
        $this->objectModel->doUpdateSpec($storyID, $story, $oldStory, $addedFiles);

        if(dao::isError()) return dao::getError();
        return $this->objectModel->dao->select('*')->from(TABLE_STORYSPEC)->where('story')->eq($storyID)->fetch();
    }

    /**
     * 测试 doCreateStory 方法。
     * Test doCreateStory method.
     *
     * @param  object $story
     * @access public
     * @return object|array
     */
    public function doCreateStoryTest(object $story): object|array
    {
        $this->objectModel->dao->delete()->from(TABLE_STORY)->exec();
        $storyID = $this->objectModel->doCreateStory($story);

        if(dao::isError()) return dao::getError();
        return $this->objectModel->dao->select('*')->from(TABLE_STORY)->where('id')->eq($storyID)->fetch();
    }

    /**
     * 测试 setStageToPlanned 方法。
     * Test setStageToPlanned method.
     *
     * @param  int    $storyID
     * @param  array  $stages
     * @param  array  $oldStages
     * @access public
     * @return object|array
     */
    public function setStageToPlannedTest(int $storyID, array $stages = array(), array $oldStages = array()): object|array
    {
        $this->objectModel->setStageToPlanned($storyID, $stages, $oldStages);
        if(dao::isError()) return dao::getError();

        $story = $this->objectModel->dao->select('*')->from(TABLE_STORY)->where('id')->eq($storyID)->fetch();
        $story->stages = $this->objectModel->dao->select('*')->from(TABLE_STORYSTAGE)->where('story')->eq($storyID)->orderBy('branch')->fetchAll();
        return $story;
    }

    /**
     * 测试 setStageToClosed 方法。
     * Test setStageToClosed method.
     *
     * @param  int    $storyID
     * @param  array  $linkedBranches
     * @param  array  $linkedProjects
     * @access public
     * @return object|array
     */
    public function setStageToClosedTest(int $storyID, array $linkedBranches = array(), array $linkedProjects = array()): object|array
    {
        $this->objectModel->setStageToClosed($storyID, $linkedBranches, $linkedProjects);
        if(dao::isError()) return dao::getError();

        $story = $this->objectModel->dao->select('*')->from(TABLE_STORY)->where('id')->eq($storyID)->fetch();
        $story->stages = $this->objectModel->dao->select('*')->from(TABLE_STORYSTAGE)->where('story')->eq($storyID)->orderBy('branch')->fetchAll();
        return $story;
    }

    /**
     * 测试 setStage 方法。
     * Test setStage method.
     *
     * @param  int    $storyID
     * @access public
     * @return object|array
     */
    public function setStageTest(int $storyID): object|array
    {
        $this->objectModel->setStage($storyID);
        if(dao::isError()) return dao::getError();

        $story = $this->objectModel->dao->select('*')->from(TABLE_STORY)->where('id')->eq($storyID)->fetch();
        $story->stages = $this->objectModel->dao->select('*')->from(TABLE_STORYSTAGE)->where('story')->eq($storyID)->orderBy('branch')->fetchAll();
        return $story;
    }

    /**
     * 测试 updateStage 方法。
     * Test updateStage method.
     *
     * @param  int    $storyID
     * @param  array  $stages
     * @param  array  $oldStages
     * @param  array  $linkedProjects
     * @access public
     * @return object|array
     */
    public function updateStageTest(int $storyID, array $stages, array $oldStages = array(), array $linkedProjects = array()): object|array
    {
        $this->objectModel->updateStage($storyID, $stages, $oldStages, $linkedProjects);
        if(dao::isError()) return dao::getError();

        $story = $this->objectModel->dao->select('*')->from(TABLE_STORY)->where('id')->eq($storyID)->fetch();
        $story->stages = $this->objectModel->dao->select('*')->from(TABLE_STORYSTAGE)->where('story')->eq($storyID)->orderBy('branch')->fetchAll();
        return $story;
    }

    /**
     * 测试 linkStory 方法。
     * Test linkStory method.
     *
     * @param  int    $executionID
     * @param  int    $productID
     * @param  int    $storyID
     * @access public
     * @return array
     */
    public function linkStoryTest(int $executionID, int $productID, int $storyID): array
    {
        $this->objectModel->linkStory($executionID, $productID, $storyID);
        return $this->objectModel->dao->select('*')->from(TABLE_PROJECTSTORY)->where('project')->eq($executionID)->fetchAll();
    }

    /**
     * 获取一个需求的基础信息。
     * Fetch base info of a story.
     *
     * @param  int    $storyID
     * @access public
     * @return object|bool
     */
    public function fetchBaseInfoTest(int $storyID): object|false
    {
        return $this->objectModel->fetchBaseInfo($storyID);
    }

    /**
     * 测试 updateStoryVersion 方法
     * Test updateStoryVersion method
     *
     * @param  int    $storyID
     * @access public
     * @return object
     */
    public function updateStoryVersionTest(int $storyID): object
    {
        $story = $this->objectModel->fetchByID($storyID);
        $this->objectModel->dao->update(TABLE_STORY)->set('version')->eq(2)->where('id')->eq(3)->exec();
        $this->objectModel->updateStoryVersion($story);

        return $this->objectModel->dao->select('*')->from(TABLE_RELATION)->where('AType')->eq('requirement')->andWhere('BType')->eq('story')->andWhere('relation')->eq('subdivideinto')->andWhere('AID')->eq(3)->andWhere('BID')->eq($storyID)->fetch();
    }

    /**
     * 测试 syncTwins 方法。
     * Test syncTwins method
     *
     * @param  int    $storyID
     * @param  string $twins
     * @param  array  $changes
     * @access public
     * @return array
     */
    public function syncTwinsTest(int $storyID, string $twins, array $changes): array
    {
        $this->objectModel->syncTwins($storyID, $twins, $changes, 'changed');
        return $this->objectModel->dao->select('*')->from(TABLE_STORY)->where('id')->in($twins)->orderBy('id')->fetchAll();
    }

    /**
     * 测试 recordReviewAction 方法
     * Test recordReviewAction method
     *
     * @param  object $story
     * @access public
     * @return object
     */
    public function recordReviewActionTest(object $story): object
    {
        $oldStory = $this->objectModel->getByID(1);
        $actionID = $this->objectModel->recordReviewAction($oldStory, $story);
        return $this->objectModel->dao->select('*')->from(TABLE_ACTION)->where('id')->in($actionID)->fetch();
    }
}
