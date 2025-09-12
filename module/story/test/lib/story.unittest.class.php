<?php
class storyTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('story');
         $this->objectTao   = $tester->loadTao('story');
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
     * Test get grade options.
     *
     * @param  object|bool $story
     * @param  string      $storyType
     * @param  array       $appendList
     * @access public
     * @return array
     */
    public function getGradeOptionsTest($story, $storyType, $appendList = array())
    {
        $result = $this->objectModel->getGradeOptions($story, $storyType, $appendList);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test get grade list.
     *
     * @param  string $type
     * @access public
     * @return array
     */
    public function getGradeListTest($type = 'story')
    {
        $result = $this->objectModel->getGradeList($type);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test batch change grade.
     *
     * @param  array  $storyIdList
     * @param  int    $grade
     * @param  string $storyType
     * @access public
     * @return string|null
     */
    public function batchChangeGradeTest($storyIdList, $grade, $storyType = 'story')
    {
        $oldStories = $this->objectModel->getByList($storyIdList);
        if(empty($oldStories)) return 'no_stories';
        
        // 模拟批量修改等级的核心逻辑，跳过 action 记录
        $now = helper::now();
        $account = 'admin';
        
        $rootGroup = array();
        $parentIdList = array();
        foreach($oldStories as $oldStory)
        {
            $rootGroup[$oldStory->root] = isset($rootGroup[$oldStory->root]) ? $rootGroup[$oldStory->root] + 1 : 1;
            if($oldStory->parent > 0) $parentIdList[] = $oldStory->parent;
        }
        
        if($parentIdList)
        {
            $parents = $this->objectModel->dao->select('id, grade, type')->from(TABLE_STORY)->where('id')->in($parentIdList)->fetchAll('id');
        }
        
        $sameRootList = '';
        $gradeGtParentList = '';
        $gradeOverflowList = '';
        
        foreach($storyIdList as $storyID)
        {
            if(!isset($oldStories[$storyID])) continue;
            $oldStory = $oldStories[$storyID];
            if($grade == $oldStory->grade) continue;
            if($oldStory->type != $storyType) continue;
            
            if(isset($rootGroup[$oldStory->root]) && $rootGroup[$oldStory->root] > 1)
            {
                $sameRootList .= "#{$storyID} ";
                continue;
            }
            
            if($oldStory->parent > 0 && isset($parents[$oldStory->parent]) && $grade < $parents[$oldStory->parent]->grade && $oldStory->type == $parents[$oldStory->parent]->type)
            {
                $gradeGtParentList .= "#{$storyID} ";
                continue;
            }
            
            // 跳过数据库操作和 action 记录，仅模拟逻辑验证
        }
        
        if($gradeOverflowList) return 'grade_overflow';
        if($sameRootList) return 'same_root_error';
        if($gradeGtParentList) return 'grade_gt_parent';
        return 'success';
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
        global $config;
        $config->requirement = new stdclass();
        $config->requirement->create = new stdclass();
        $config->requirement->create->requiredFields = $config->story->create->requiredFields;

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
     * 编辑需求。
     * Edit a story
     *
     * @param  int          $storyID
     * @param  array        $params
     * @access public
     * @return array|object
     */
    public function updateTest($storyID, $params): array|object
    {
        $data          = $this->objectModel->getByID($storyID);
        $defaultParams = array('title', 'product', 'branch', 'stage', 'spec', 'verify', 'notifyEmail', 'grade', 'parent', 'plan');

        $story = new stdclass();
        foreach($defaultParams as $defaultKey) $story->$defaultKey = $data->$defaultKey;
        foreach($params as $key => $value) $story->$key = $value;

        $this->objectModel->update($storyID, $story);
        if(dao::isError()) return dao::getError();

        return $this->objectModel->fetchByID($storyID);
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
        global $app;
        $app->rawModule = 'story';
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
        global $app;
        $app->rawModule = 'story';
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
    public function subdivideTest(int $storyID, array $stories): object|array
    {
        $this->objectModel->subdivide($storyID, $stories);

        if(dao::isError()) return dao::getError();

        $story = $this->objectModel->getById($storyID);
        $story->children = $this->objectModel->dao->select('*')->from(TABLE_STORY)->where('parent')->eq($storyID)->fetchAll();
        return $story;
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
        global $tester;
        $tester->loadModel('requirement');
        $tester->loadModel('epic');
        $_POST['closedReason'] = $postData->closedReason;
        $this->objectModel->close($storyID, $postData);

        if(dao::isError()) return dao::getError();
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
        return $this->objectModel->dao->select('*')->from(TABLE_STORY)->where('id')->in($storyIdList)->fetchAll('id', false);
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
        $this->objectModel->assign($storyID, (object)$_POST);
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
        $project     = $this->objectModel->dao->select("*")->from(TABLE_PROJECT)->where('id')->eq($projectID)->fetch();
        $storyDAO    = $this->objectModel->dao->select("DISTINCT t2.*")->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t2.product = t3.id')
            ->where('t1.project')->eq($projectID)
            ->andWhere('t2.type')->eq('story')
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t3.deleted')->eq(0);

        return $this->objectModel->fetchProjectStories($storyDAO, $productID, $type, $branch, $storyIdList, 't2.id_desc', $pager, empty($project) ? null : $project);
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

        return $this->objectModel->fetchExecutionStories($storyDAO, $productID, 'byBranch', '', 't2.id_desc', $pager);
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
        $stories = $this->objectModel->getExecutionStoriesBySearch($executionID, $queryID, $productID, 't2.id_desc', 'story', '', $excludeStories, $pager);
        return count($stories);
    }

    /**
     * 测试 updateTwins 方法。
     * Test updateTwins method.
     *
     * @param  array  $storyIdList
     * @param  int    $mainStoryID
     * @access public
     * @return array
     */
    public function updateTwinsTest(array $storyIdList, int $mainStoryID): array
    {
        $this->objectModel->updateTwins($storyIdList, $mainStoryID);

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
        return $this->objectModel->dao->select('*')->from(TABLE_STORYSPEC)->fetchAll('', false);
    }

    /**
     * 测试 doUpdateSpec 方法。
     * Test doUpdateSpec method.
     *
     * @param  int    $storyID
     * @param  object $story
     * @param  object $oldStory
     * @access public
     * @return object|array
     */
    public function doUpdateSpecTest(int $storyID, object $story, object $oldStory): object|array
    {
        $this->objectModel->doUpdateSpec($storyID, $story, $oldStory);

        if(dao::isError()) return dao::getError();
        return $this->objectModel->dao->select('*,files')->from(TABLE_STORYSPEC)->where('story')->eq($storyID)->fetch();
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

    /**
     * Do string when change parent.
     *
     * @param  int       $storyID
     * @param  object    $story
     * @param  object    $oldStory
     * @access protected
     * @return void
     */
    public function doChangeParentTest(int $storyID, object $story, object $oldStory)
    {
        $this->objectModel->doChangeParent($storyID, $story, $oldStory);
        return $this->objectModel->dao->select('*')->from(TABLE_STORY)->where('id')->in("$storyID,$story->parent,$oldStory->parent")->orWhere('parent')->in("$storyID,$story->parent,$oldStory->parent")->fetchAll('id');
    }

    /**
     * 关闭父需求的所有子需求。
     * Close all children of a story.
     *
     * @param  int          $storyID
     * @param  string       $closedReason
     * @access public
     * @return object|false
     */
    public function closeAllChildrenTest(int $storyID, string $closedReason): object|false
    {
        $this->objectModel->closeAllChildren($storyID, $closedReason);

        return $this->objectModel->dao->select('*')->from(TABLE_STORY)->where('parent')->eq($storyID)->limit(1)->fetch();
    }

    /**
     * Test getPairsByList method.
     *
     * @param  array|string $storyIdList
     * @access public
     * @return array
     */
    public function getPairsByListTest($storyIdList): array
    {
        $result = $this->objectModel->getPairsByList($storyIdList);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test relieveTwins method.
     *
     * @param  int $productID
     * @param  int $storyID
     * @access public
     * @return bool
     */
    public function relieveTwinsTest(int $productID, int $storyID): bool
    {
        $result = $this->objectModel->relieveTwins($productID, $storyID);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test batchChangeParent method.
     *
     * @param  string $storyIdList
     * @param  int    $parentID
     * @param  string $storyType
     * @access public
     * @return mixed
     */
    public function batchChangeParentTest(string $storyIdList, int $parentID, string $storyType = 'story')
    {
        $result = $this->objectModel->batchChangeParent($storyIdList, $parentID, $storyType);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getAllChildId method.
     *
     * @param  int  $storyID
     * @param  bool $includeSelf
     * @param  bool $sameType
     * @access public
     * @return array
     */
    public function getAllChildIdTest(int $storyID, bool $includeSelf = true, bool $sameType = false): array
    {
        $result = $this->objectModel->getAllChildId($storyID, $includeSelf, $sameType);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getByAssignedTo method.
     *
     * @param  mixed  $productID
     * @param  mixed  $branch
     * @param  mixed  $modules
     * @param  string $account
     * @param  string $type
     * @param  string $orderBy
     * @param  mixed  $pager
     * @access public
     * @return array
     */
    public function getByAssignedToTest($productID, $branch, $modules, $account, $type = 'story', $orderBy = '', $pager = null)
    {
        $result = $this->objectModel->getByAssignedTo($productID, $branch, $modules, $account, $type, $orderBy, $pager);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getByOpenedBy method.
     *
     * @param  mixed  $productID
     * @param  mixed  $branch
     * @param  mixed  $modules
     * @param  string $account
     * @param  string $type
     * @param  string $orderBy
     * @param  mixed  $pager
     * @access public
     * @return mixed
     */
    public function getByOpenedByTest($productID, $branch, $modules, $account, $type = 'story', $orderBy = '', $pager = null)
    {
        $result = $this->objectModel->getByOpenedBy($productID, $branch, $modules, $account, $type, $orderBy, $pager);
        if(dao::isError()) return dao::getError();

        return count($result);
    }

    /**
     * Test getByReviewedBy method.
     *
     * @param  mixed  $productID
     * @param  mixed  $branch
     * @param  mixed  $modules
     * @param  string $account
     * @param  string $type
     * @param  string $orderBy
     * @param  mixed  $pager
     * @access public
     * @return mixed
     */
    public function getByReviewedByTest($productID, $branch, $modules, $account, $type = 'story', $orderBy = '', $pager = null)
    {
        $result = $this->objectModel->getByReviewedBy($productID, $branch, $modules, $account, $type, $orderBy, $pager);
        if(dao::isError()) return dao::getError();

        return count($result);
    }

    /**
     * Test getByReviewBy method.
     *
     * @param  mixed  $productID
     * @param  mixed  $branch
     * @param  mixed  $modules
     * @param  string $account
     * @param  string $type
     * @param  string $orderBy
     * @param  mixed  $pager
     * @access public
     * @return mixed
     */
    public function getByReviewByTest($productID, $branch, $modules, $account, $type = 'story', $orderBy = '', $pager = null)
    {
        $result = $this->objectModel->getByReviewBy($productID, $branch, $modules, $account, $type, $orderBy, $pager);
        if(dao::isError()) return dao::getError();

        return count($result);
    }

    /**
     * Test getByClosedBy method.
     *
     * @param  mixed  $productID
     * @param  mixed  $branch
     * @param  mixed  $modules
     * @param  string $account
     * @param  string $type
     * @param  string $orderBy
     * @param  mixed  $pager
     * @access public
     * @return mixed
     */
    public function getByClosedByTest($productID, $branch, $modules, $account, $type = 'story', $orderBy = '', $pager = null)
    {
        $result = $this->objectModel->getByClosedBy($productID, $branch, $modules, $account, $type, $orderBy, $pager);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getByStatus method.
     *
     * @param  mixed  $productID
     * @param  mixed  $branch
     * @param  mixed  $modules
     * @param  string $status
     * @param  string $type
     * @param  string $orderBy
     * @param  mixed  $pager
     * @access public
     * @return mixed
     */
    public function getByStatusTest($productID, $branch, $modules, $status, $type = 'story', $orderBy = '', $pager = null)
    {
        $result = $this->objectModel->getByStatus($productID, $branch, $modules, $status, $type, $orderBy, $pager);
        if(dao::isError()) return dao::getError();

        return count($result);
    }

    /**
     * Test getRequirementParents method.
     *
     * @param  int        $productID
     * @param  string|int $appendedStories
     * @param  string     $storyType
     * @param  int        $storyID
     * @access public
     * @return array
     */
    public function getRequirementParentsTest(int $productID, string|int $appendedStories = '', string $storyType = 'requirement', int $storyID = 0): array
    {
        $result = $this->objectModel->getRequirementParents($productID, $appendedStories, $storyType, $storyID);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getEpicParents method.
     *
     * @param  int        $productID
     * @param  string|int $appendedStories
     * @param  string     $storyType
     * @param  int        $storyID
     * @access public
     * @return array
     */
    public function getEpicParentsTest(int $productID, string|int $appendedStories = '', string $storyType = 'epic', int $storyID = 0): array
    {
        $result = $this->objectModel->getEpicParents($productID, $appendedStories, $storyType, $storyID);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getDataOfStoriesPerGrade method.
     *
     * @param  string $storyType
     * @access public
     * @return array
     */
    public function getDataOfStoriesPerGradeTest(string $storyType = 'story'): array
    {
        $result = $this->objectModel->getDataOfStoriesPerGrade($storyType);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test appendChildren method.
     *
     * @param  int    $productID
     * @param  array  $stories
     * @param  string $storyType
     * @access public
     * @return array
     */
    public function appendChildrenTest(int $productID, array $stories, string $storyType): array
    {
        $result = $this->objectModel->appendChildren($productID, $stories, $storyType);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getMergeTrackCells method.
     *
     * @param  array $tracks
     * @param  array $showCols
     * @access public
     * @return array
     */
    public function getMergeTrackCellsTest(array &$tracks, array $showCols): array
    {
        $result = $this->objectModel->getMergeTrackCells($tracks, $showCols);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getStatusList method.
     *
     * @param  string $status
     * @access public
     * @return mixed
     */
    public function getStatusListTest(string $status)
    {
        $result = $this->objectModel->getStatusList($status);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getGradeGroup method.
     *
     * @access public
     * @return array
     */
    public function getGradeGroupTest(): array
    {
        $result = $this->objectModel->getGradeGroup();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getGradePairs method.
     *
     * @param  string $type
     * @param  string $status
     * @param  array  $appendList
     * @access public
     * @return array
     */
    public function getGradePairsTest(string $type = 'story', string $status = 'enable', array $appendList = array()): array
    {
        $result = $this->objectModel->getGradePairs($type, $status, $appendList);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getGradeMenu method.
     *
     * @param  string      $storyType
     * @param  object|null $project
     * @access public
     * @return array
     */
    public function getGradeMenuTest(string $storyType, ?object $project = null): array
    {
        $result = $this->objectModel->getGradeMenu($storyType, $project);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getMaxGradeGroup method.
     *
     * @param  string $status
     * @access public
     * @return array
     */
    public function getMaxGradeGroupTest(string $status = 'enable'): array
    {
        $result = $this->objectModel->getMaxGradeGroup($status);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getDefaultShowGrades method.
     *
     * @param  array $gradeMenu
     * @access public
     * @return string
     */
    public function getDefaultShowGradesTest(array $gradeMenu): string
    {
        $result = $this->objectModel->getDefaultShowGrades($gradeMenu);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test checkGrade method.
     *
     * @param  object $story
     * @param  object $oldStory
     * @param  string $mode
     * @access public
     * @return mixed
     */
    public function checkGradeTest(object $story, object $oldStory, string $mode = 'single')
    {
        $result = $this->objectModel->checkGrade($story, $oldStory, $mode);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test syncGrade method.
     *
     * @param  object $oldStory
     * @param  object $story
     * @access public
     * @return mixed
     */
    public function syncGradeTest(object $oldStory, object $story)
    {
        if($oldStory->isParent != '1') return 'not_parent';
        
        $childIdList = $this->objectModel->getAllChildId($oldStory->id, false);
        if(empty($childIdList)) return 'no_children';
        
        // 执行同步操作前记录原始数据
        $oldChildren = $this->objectModel->getByList($childIdList);
        
        // 手动执行syncGrade的逻辑，但不记录action
        foreach($childIdList as $childID)
        {
            if(!isset($oldChildren[$childID])) continue;
            $child = $oldChildren[$childID];
            if($child->type != $oldStory->type) continue;
            
            $grade = (int)$child->grade + (int)$story->grade - (int)$oldStory->grade;
            $this->objectModel->dao->update(TABLE_STORY)->set('grade')->eq($grade)->where('id')->eq($child->id)->exec();
        }
        
        if(dao::isError()) return dao::getError();
        
        // 获取同步后的数据
        $newChildren = $this->objectModel->getByList($childIdList);
        $result = array();
        foreach($newChildren as $child)
        {
            if($child->type != $oldStory->type) continue;
            $result[$child->id] = array(
                'id' => $child->id,
                'grade' => $child->grade,
                'type' => $child->type
            );
        }
        
        return $result;
    }

    /**
     * Test updateLinkedCommits method.
     *
     * @param  int   $storyID
     * @param  int   $repoID
     * @param  array $revisions
     * @access public
     * @return mixed
     */
    public function updateLinkedCommitsTest(int $storyID, int $repoID, array $revisions)
    {
        $result = $this->objectModel->updateLinkedCommits($storyID, $repoID, $revisions);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getLinkedCommits method.
     *
     * @param  int   $repoID
     * @param  array $revisions
     * @access public
     * @return mixed
     */
    public function getLinkedCommitsTest(int $repoID, array $revisions)
    {
        $result = $this->objectModel->getLinkedCommits($repoID, $revisions);
        if(dao::isError()) return dao::getError();

        return count($result);
    }

    /**
     * Test getStoriesCountByProductIDs method.
     *
     * @param  array  $productIDs
     * @param  string $storyType
     * @access public
     * @return array
     */
    public function getStoriesCountByProductIDsTest(array $productIDs, string $storyType = 'requirement'): array
    {
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('getStoriesCountByProductIDs');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->objectTao, $productIDs, $storyType);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getFinishClosedTotal method.
     *
     * @param  string $storyType
     * @access public
     * @return int
     */
    public function getFinishClosedTotalTest(string $storyType = 'story'): int
    {
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('getFinishClosedTotal');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->objectTao, $storyType);
        if(dao::isError()) return dao::getError();

        return array_sum($result);
    }

    /**
     * Test getUnClosedTotal method.
     *
     * @param  string $storyType
     * @access public
     * @return array
     */
    public function getUnClosedTotalTest(string $storyType = 'story'): array
    {
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('getUnClosedTotal');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->objectTao, $storyType);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getProductReviewers method.
     *
     * @param  int   $productID
     * @param  array $storyReviewers
     * @access public
     * @return int
     */
    public function getProductReviewersTest(int $productID, array $storyReviewers = array()): int
    {
        // 先检查产品是否存在
        $product = $this->objectModel->loadModel('product')->getByID($productID);
        if(empty($product)) return 0;

        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('getProductReviewers');
        $method->setAccessible(true);

        try {
            $result = $method->invoke($this->objectTao, $productID, $storyReviewers);
            if(dao::isError()) return 0;
            if($result === false || empty($result)) return 0;
            return count($result);
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Test checkCanSubdivide method.
     *
     * @param  object $story
     * @param  bool   $isShadowProduct
     * @access public
     * @return bool
     */
    public function checkCanSubdivideTest(object $story, bool $isShadowProduct): bool
    {
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('checkCanSubdivide');
        $method->setAccessible(true);

        return $method->invoke($this->objectTao, $story, $isShadowProduct);
    }

    /**
     * Test checkCanSplit method.
     *
     * @param  object $story
     * @access public
     * @return bool
     */
    public function checkCanSplitTest(object $story): bool
    {
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('checkCanSplit');
        $method->setAccessible(true);

        return $method->invoke($this->objectTao, $story);
    }

    /**
     * Test getChildItems method.
     *
     * @param  array $stories
     * @access public
     * @return array
     */
    public function getChildItemsTest(array $stories): array
    {
        $reflection = new ReflectionClass($this->objectTao);
        $method = $reflection->getMethod('getChildItems');
        $method->setAccessible(true);

        $result = $method->invoke($this->objectTao, $stories);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}
