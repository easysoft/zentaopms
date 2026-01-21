<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class storyTaoTest extends baseTest
{
    protected $moduleName = 'story';
    protected $className  = 'tao';

    /**
     * Test doSaveUploadImage method.
     *
     * @param  int    $storyID
     * @param  string $fileName
     * @param  string $testType
     * @access public
     * @return object
     */
    public function doSaveUploadImageTest(int $storyID, string $fileName, string $testType): object
    {
        global $app;

        // 创建spec对象
        $spec = new stdClass();
        $spec->spec = '原始内容';
        $spec->files = '';

        // 确保file save路径存在
        $storyTao = $this->getInstance($this->moduleName, $this->className);
        $storyTao->loadModel('file');
        if(!is_dir($storyTao->file->savePath)) mkdir($storyTao->file->savePath, 0777, true);

        // 根据测试类型设置不同的session数据
        switch($testType) {
            case 'image':
                // 模拟图片文件上传
                $app->session->storyImagesFile = array(
                    $fileName => array(
                        'pathname' => $fileName,
                        'title' => $fileName,
                        'extension' => 'jpg',
                        'size' => 1024,
                        'realpath' => '/tmp/zentao_test/test_image.jpg'
                    )
                );
                break;
            case 'file':
                // 模拟文档文件上传
                $app->session->storyImagesFile = array(
                    $fileName => array(
                        'pathname' => $fileName,
                        'title' => $fileName,
                        'extension' => 'pdf',
                        'size' => 2048,
                        'realpath' => '/tmp/zentao_test/test_doc.pdf'
                    )
                );
                break;
            case 'empty_session':
                // 清空session
                $app->session->storyImagesFile = array();
                break;
            case 'missing_file':
                // 文件不存在的情况
                $app->session->storyImagesFile = array(
                    $fileName => array(
                        'pathname' => $fileName,
                        'title' => $fileName,
                        'extension' => 'jpg',
                        'size' => 1024,
                        'realpath' => '/tmp/zentao_test/nonexistent.jpg'
                    )
                );
                break;
            case 'empty_name':
                // 空文件名情况
                $app->session->storyImagesFile = array();
                break;
        }

        $result = $this->invokeArgs('doSaveUploadImage', [$storyID, $fileName, $spec], $this->moduleName, $this->className, $storyTao);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getProductReviewers method.
     *
     * @param  int   $productID
     * @param  array $storyReviewers
     * @access public
     * @return array|bool
     */
    public function getProductReviewersTest(int $productID, array $storyReviewers = array()): array|bool
    {
        $result = $this->invokeArgs('getProductReviewers', [$productID, $storyReviewers]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test buildReorderResult method.
     *
     * @param  array $parent
     * @access public
     * @return array
     */
    public function buildReorderResultTest(array $parent): array
    {
        $result = array();
        $this->instance->buildReorderResult($parent, $result);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test buildStoryTree method.
     *
     * @param  array $stories
     * @param  int   $parentId
     * @param  array $originStories
     * @access public
     * @return array
     */
    public function buildStoryTreeTest(array $stories, int $parentId = 0, array $originStories = array()): array
    {
        $result = $this->instance->buildStoryTree($stories, $parentId, $originStories);
        if(dao::isError()) return dao::getError();

        return $result;
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
        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('checkCanSplit');
        $method->setAccessible(true);

        return $method->invoke($this->instance, $story);
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
        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('checkCanSubdivide');
        $method->setAccessible(true);

        return $method->invoke($this->instance, $story, $isShadowProduct);
    }

    /**
     * Test checkConditions method.
     *
     * @param  string $method
     * @param  object $story
     * @access public
     * @return bool
     */
    public function checkConditionsTest(string $methodName, object $story): bool
    {
        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('checkConditions');
        $method->setAccessible(true);

        $result = $method->invoke($this->instance, $methodName, $story);
        if(dao::isError()) return dao::getError();

        return $result;
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
        $this->instance->closeBugWhenToStory($bugID, $storyID);

        if(empty($bugID) or empty($storyID)) return array();
        $bug = (array)$this->instance->dao->select('*')->from(TABLE_BUG)->where('id')->eq($bugID)->fetch();
        $bug['files'] = $this->instance->dao->select('*')->from(TABLE_FILE)->where('objectType')->eq('story')->andWhere('objectID')->eq($storyID)->fetchAll('id');
        return $bug;
    }

    /**
     * Test computeStage method.
     *
     * @param  array $children
     * @access public
     * @return string
     */
    public function computeStageTest(array $children): string
    {
        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('computeStage');
        $method->setAccessible(true);

        $result = $method->invoke($this->instance, $children);
        if(dao::isError()) return dao::getError();

        return $result;
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
        $this->instance->doChangeParent($storyID, $story, $oldStory);
        return $this->instance->dao->select('*')->from(TABLE_STORY)->where('id')->in("$storyID,$story->parent,$oldStory->parent")->orWhere('parent')->in("$storyID,$story->parent,$oldStory->parent")->fetchAll('id');
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
        $this->instance->dao->delete()->from(TABLE_STORYREVIEW)->exec();
        $this->instance->doCreateReviewer($storyID, $reviewer);

        if(dao::isError()) return dao::getError();
        return $this->instance->dao->select('*')->from(TABLE_STORYREVIEW)->fetchAll();
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
        $this->instance->dao->delete()->from(TABLE_STORYSPEC)->exec();
        $this->instance->doCreateSpec($storyID, $story, $files);

        if(dao::isError()) return dao::getError();
        return $this->instance->dao->select('*')->from(TABLE_STORYSPEC)->fetchAll('', false);
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
        $this->instance->dao->delete()->from(TABLE_STORY)->exec();
        $storyID = $this->instance->doCreateStory($story);

        if(dao::isError()) return dao::getError();
        return $this->instance->dao->select('*')->from(TABLE_STORY)->where('id')->eq($storyID)->fetch();
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
        return $this->instance->fetchBaseInfo($storyID);
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
        $unclosedStatus = $this->instance->lang->story->statusList;
        unset($unclosedStatus['closed']);

        $storyDAO = $this->instance->dao->select("DISTINCT t2.*")->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t2.product = t3.id')
            ->where('t1.project')->eq($executionID)
            ->andWhere('t2.type')->eq('story')
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t3.deleted')->eq(0);

        return $this->instance->fetchExecutionStories($storyDAO, $productID, 'byBranch', '', 't2.id_desc', $pager);
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
        $unclosedStatus = $this->instance->lang->story->statusList;
        unset($unclosedStatus['closed']);

        $storyIdList = array('1,2,3,4,5,6,7');
        $project     = $this->instance->dao->select("*")->from(TABLE_PROJECT)->where('id')->eq($projectID)->fetch();
        $storyDAO    = $this->instance->dao->select("DISTINCT t2.*")->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t2.product = t3.id')
            ->where('t1.project')->eq($projectID)
            ->andWhere('t2.type')->eq('story')
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t3.deleted')->eq(0);

        return $this->instance->fetchProjectStories($storyDAO, $productID, $type, $branch, $storyIdList, 't2.id_desc', $pager, empty($project) ? null : $project);
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
        $this->instance->finishTodoWhenToStory($todoID, $storyID);

        if(empty($todoID) or empty($storyID)) return '';
        return $this->instance->dao->select('id,status')->from(TABLE_TODO)->where('id')->eq($todoID)->fetch('status');
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
        $story = \$this->instance->loadModel('story')->getById($storyID);
        return $this->instance->getAffectedBugs($story, $users);
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
        $story = \$this->instance->loadModel('story')->getById($storyID);
        return $this->instance->getAffectedCases($story, $users);
    }

    /**
     * Test getAffectedChildren method.
     *
     * @param  object $story
     * @param  array  $users
     * @access public
     * @return object|array
     */
    public function getAffectedChildrenTest(object $story, array $users = array()): object|array
    {
        if(empty($users)) $users = array('admin' => '管理员');

        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('getAffectedChildren');
        $method->setAccessible(true);

        $result = $method->invoke($this->instance, $story, $users);
        if(dao::isError()) return dao::getError();

        return $result;
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
        $story = \$this->instance->loadModel('story')->getById($storyID);
        return $this->instance->getAffectedProjects($story, $users);
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
        $story = \$this->instance->loadModel('story')->getById($storyID);
        return $this->instance->getAffectedTwins($story, $users);
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
        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('getChildItems');
        $method->setAccessible(true);

        $result = $method->invoke($this->instance, $stories);
        if(dao::isError()) return dao::getError();

        return $result;
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
        $stories = $this->instance->getExecutionStoriesBySearch($executionID, $queryID, $productID, 't2.id_desc', 'story', '', $excludeStories, $pager);
        return count($stories);
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
        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('getFinishClosedTotal');
        $method->setAccessible(true);

        $result = $method->invoke($this->instance, $storyType);
        if(dao::isError()) return dao::getError();

        return array_sum($result);
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
        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('getStoriesCountByProductIDs');
        $method->setAccessible(true);

        $result = $method->invoke($this->instance, $productIDs, $storyType);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getTasksForTrack method.
     *
     * @param  array $storyIdList
     * @access public
     * @return array
     */
    public function getTasksForTrackTest(array $storyIdList): array
    {
        $result = $this->instance->getTasksForTrack($storyIdList);
        if(dao::isError()) return dao::getError();

        return $result;
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
        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('getUnClosedTotal');
        $method->setAccessible(true);

        $result = $method->invoke($this->instance, $storyType);
        if(dao::isError()) return dao::getError();

        return $result;
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
        $this->instance->dao->delete()->from(TABLE_PROJECTSTORY)->exec();
        $this->instance->dao->delete()->from(TABLE_ACTION)->exec();
        $story = $this->instance->dao->select('*')->from(TABLE_STORY)->where('id')->eq($storyID)->fetch();
        if(empty($story)) $story = new stdclass();

        $this->instance->linkToExecutionForCreate($executionID, $storyID, $story, $extra);
        return array_filter((array)$this->instance->dao->select('*')->from(TABLE_ACTION)->orderBy('id_desc')->limit(1)->fetch());
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
        $this->instance->setStageToClosed($storyID, $linkedBranches, $linkedProjects);
        if(dao::isError()) return dao::getError();

        $story = $this->instance->dao->select('*')->from(TABLE_STORY)->where('id')->eq($storyID)->fetch();
        $story->stages = $this->instance->dao->select('*')->from(TABLE_STORYSTAGE)->where('story')->eq($storyID)->orderBy('branch')->fetchAll();
        return $story;
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
        $this->instance->setStageToPlanned($storyID, $stages, $oldStages);
        if(dao::isError()) return dao::getError();

        $story = $this->instance->dao->select('*')->from(TABLE_STORY)->where('id')->eq($storyID)->fetch();
        $story->stages = $this->instance->dao->select('*')->from(TABLE_STORYSTAGE)->where('story')->eq($storyID)->orderBy('branch')->fetchAll();
        return $story;
    }

    /**
     * Test updateLane method.
     *
     * @param  int    $storyID
     * @param  string $storyType
     * @access public
     * @return mixed
     */
    public function updateLaneTest(int $storyID, string $storyType)
    {
        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('updateLane');
        $method->setAccessible(true);

        $result = $method->invoke($this->instance, $storyID, $storyType);
        if(dao::isError()) return dao::getError();

        // updateLane方法实际没有返回值，返回执行状态
        return $result === null ? 'success' : $result;
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
        $this->instance->updateStage($storyID, $stages, $oldStages, $linkedProjects);
        if(dao::isError()) return dao::getError();

        $story = $this->instance->dao->select('*')->from(TABLE_STORY)->where('id')->eq($storyID)->fetch();
        $story->stages = $this->instance->dao->select('*')->from(TABLE_STORYSTAGE)->where('story')->eq($storyID)->orderBy('branch')->fetchAll();
        return $story;
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
        $this->instance->updateTwins($storyIdList, $mainStoryID);

        if(empty($storyIdList)) return array();
        if($storyIdList)
        {
            $twins = $this->instance->dao->select('id,twins')->from(TABLE_STORY)->where('id')->in($storyIdList)->fetchPairs('id', 'twins');
            return array_map(function($item){return str_replace(',', ':', $item);}, $twins);
        }
    }
}
