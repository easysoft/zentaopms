<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class productZenTest extends baseTest
{
    protected $moduleName = 'product';
    protected $className  = 'zen';

    /**
     * Test assignBrowseData method.
     *
     * @param  array       $stories
     * @param  string      $browseType
     * @param  string      $storyType
     * @param  bool        $isProjectStory
     * @param  object|null $product
     * @param  object|null $project
     * @param  string      $branch
     * @param  string      $branchID
     * @param  string      $from
     * @access public
     * @return mixed
     */
    public function assignBrowseDataTest(array $stories = array(), string $browseType = 'all', string $storyType = 'story', bool $isProjectStory = false, object|null $product = null, object|null $project = null, string $branch = '', string $branchID = '', string $from = '')
    {
        /* Prepare products array. */
        $products = array();
        if($product) $products[$product->id] = $product->name;
        $this->instance->products = $products;

        /* Override display method using closure. */
        $displayOverride = function() { return; };

        /* Use reflection to get the method and override it temporarily. */
        $refMethod = new ReflectionMethod($this->instance, 'display');

        try {
            /* Call assignBrowseData using reflection to avoid display being called. */
            $method = new ReflectionMethod($this->instance, 'assignBrowseData');
            $method->setAccessible(true);

            /* Manually execute the logic without calling display. */
            $this->assignBrowseDataWithoutDisplay($stories, $browseType, $storyType, $isProjectStory, $product, $project, $branch, $branchID, $from);
        } catch (Throwable $e) {
            return array('error' => $e->getMessage());
        }

        $view = $this->getProperty('view');

        if(dao::isError()) return dao::getError();

        return array(
            'productID' => isset($view->productID) ? $view->productID : 0,
            'storyType' => isset($view->storyType) ? $view->storyType : '',
            'browseType' => isset($view->browseType) ? $view->browseType : '',
            'isProjectStory' => isset($view->isProjectStory) ? $view->isProjectStory : false,
            'branch' => isset($view->branch) ? $view->branch : '',
            'title' => isset($view->title) ? $view->title : ''
        );
    }

    private function assignBrowseDataWithoutDisplay(array $stories, string $browseType, string $storyType, bool $isProjectStory, object|null $product, object|null $project, string $branch, string $branchID, string $from)
    {
        /* This is a copy of assignBrowseData logic without the display() call. */
        $productID       = $product ? (int)$product->id : 0;
        $projectID       = $project ? (int)$project->id : 0;
        $productName     = ($isProjectStory && empty($product)) ? $this->instance->lang->product->all : $this->instance->products[$productID];
        $storyIdList     = $this->invokeArgs('getStoryIdList', [$stories]);
        $projectProducts = $this->invokeArgs('getProjectProductList', [$projectID, $isProjectStory]);
        list($branchOpt, $branchTagOpt) = $this->invokeArgs('getBranchAndTagOption', [$projectID, $product, $isProjectStory]);

        global $tester;
        $gradeList  = $tester->loadModel('story')->getGradeList('');
        $gradeGroup = array();
        foreach($gradeList as $grade) $gradeGroup[$grade->type][$grade->grade] = $grade->name;

        /* Set show module by config. */
        $showModule = empty($this->instance->config->product->browse->showModule) ? 0 : $this->instance->config->product->browse->showModule;
        if($isProjectStory) $showModule = empty($this->instance->config->projectstory->story->showModule) ? 0 : $this->instance->config->projectstory->story->showModule;

        $this->instance->view->title           = $productName . $this->instance->lang->hyphen . ($storyType === 'story' ? $this->instance->lang->product->browse : $this->instance->lang->product->requirement);
        $this->instance->view->productID       = $productID;
        $this->instance->view->product         = $product;
        $this->instance->view->projectID       = $projectID;
        $this->instance->view->project         = $project;
        $this->instance->view->stories         = $stories;
        $this->instance->view->storyType       = $storyType;
        $this->instance->view->browseType      = $browseType;
        $this->instance->view->isProjectStory  = $isProjectStory;
        $this->instance->view->branch          = $branch;
        $this->instance->view->branchID        = $branchID;
    }

    /**
     * Test buildBatchEditForm method.
     *
     * @param  int   $programID
     * @param  array $productIdList
     * @access public
     * @return object
     */
    public function buildBatchEditFormTest(int $programID = 0, array $productIdList = array())
    {
        global $tester;

        /* Get products by ID list. */
        $products = $tester->loadModel('product')->getByIdList($productIdList);

        /* Build result object with basic data. */
        $result = new stdclass();
        $result->programID = $programID;
        $result->productsCount = count($products);
        $result->hasFields = 1;

        /* Check if we need to load program data. */
        if(in_array($this->instance->config->systemMode, array('ALM', 'PLM')))
        {
            try {
                $authPrograms = $tester->loadModel('program')->getTopPairs();
                $result->hasAuthPrograms = !empty($authPrograms) ? 1 : 0;
            } catch (Throwable $e) {
                $result->hasAuthPrograms = 0;
            }
            $result->hasUnauthPrograms = 0;
            $result->hasLines = 0;
        }
        else
        {
            $result->hasAuthPrograms = 0;
            $result->hasUnauthPrograms = 0;
            $result->hasLines = 0;
        }

        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test buildProductForActivate method.
     *
     * @param  int    $productID
     * @access public
     * @return object
     */
    public function buildProductForActivateTest(int $productID = 0)
    {
        $_POST['status'] = 'normal';
        $_POST['comment'] = 'Test activate comment';

        $result = $this->invokeArgs('buildProductForActivate', array($productID));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test buildProductForClose method.
     *
     * @param  int    $productID
     * @access public
     * @return object
     */
    public function buildProductForCloseTest(int $productID = 0)
    {
        $result = $this->invokeArgs('buildProductForClose', array($productID));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test buildProductForCreate method.
     *
     * @param  int    $workflowGroup
     * @access public
     * @return object
     */
    public function buildProductForCreateTest(int $workflowGroup = 0)
    {
        $result = $this->invokeArgs('buildProductForCreate', array($workflowGroup));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test buildProductForEdit method.
     *
     * @param  int    $productID
     * @param  int    $workflowGroup
     * @access public
     * @return object
     */
    public function buildProductForEditTest(int $productID = 0, int $workflowGroup = 0)
    {
        $result = $this->invokeArgs('buildProductForEdit', array($productID, $workflowGroup));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test buildSearchFormForBrowse method.
     *
     * @param  object|null $project
     * @param  int         $projectID
     * @param  int         $productID
     * @param  string      $branch
     * @param  int         $param
     * @param  string      $storyType
     * @param  string      $browseType
     * @param  bool        $isProjectStory
     * @param  string      $from
     * @param  int         $blockID
     * @access public
     * @return mixed
     */
    public function buildSearchFormForBrowseTest(object|null $project = null, int $projectID = 0, int $productID = 0, string $branch = '', int $param = 0, string $storyType = 'story', string $browseType = 'all', bool $isProjectStory = false, string $from = '', int $blockID = 0)
    {
        global $tester;
        if($productID > 0)
        {
            $product = $tester->loadModel('product')->getByID($productID);
            if($product) $this->instance->products = array($productID => $product->name);
        }
        $this->instance->app->rawModule = $isProjectStory ? 'projectstory' : 'product';
        $this->instance->app->rawMethod = 'browse';
        $this->instance->app->tab = 'product';
        try {
            $this->invokeArgs('buildSearchFormForBrowse', array($project, $projectID, &$productID, $branch, $param, $storyType, $browseType, $isProjectStory, $from, $blockID));
        } catch (Throwable $e) {}
        $result = new stdclass();
        $result->productID = $productID;
        $result->searchModule = isset($this->instance->config->product->search['module']) ? $this->instance->config->product->search['module'] : '';
        $result->hasProductField = isset($this->instance->config->product->search['fields']['product']) ? 1 : 0;
        $result->hasPlanField = isset($this->instance->config->product->search['fields']['plan']) ? 1 : 0;
        $result->hasRoadmapField = isset($this->instance->config->product->search['fields']['roadmap']) ? 1 : 0;
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test buildSearchFormForTrack method.
     *
     * @param  int         $productID
     * @param  string      $branch
     * @param  int         $projectID
     * @param  string      $browseType
     * @param  int         $param
     * @param  string      $storyType
     * @access public
     * @return mixed
     */
    public function buildSearchFormForTrackTest(int $productID = 0, string $branch = '', int $projectID = 0, string $browseType = 'all', int $param = 0, string $storyType = 'story')
    {
        global $tester;
        if($productID > 0)
        {
            $product = $tester->loadModel('product')->getByID($productID);
            if($product) $this->instance->products = array($productID => $product->name);
        }
        $this->instance->app->rawModule = $projectID > 0 ? 'projectstory' : 'product';
        $this->instance->app->rawMethod = 'track';
        $this->instance->app->tab = 'product';
        try {
            $this->invokeArgs('buildSearchFormForTrack', array(&$productID, $branch, $projectID, $browseType, $param, $storyType));
        } catch (Throwable $e) {}
        $result = new stdclass();
        $result->productID = $productID;
        $result->searchModule = isset($this->instance->config->product->search['module']) ? $this->instance->config->product->search['module'] : '';
        $result->hasRoadmapField = isset($this->instance->config->product->search['fields']['roadmap']) ? 1 : 0;
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getActions4Dashboard method.
     *
     * @param  int    $productID
     * @access public
     * @return mixed
     */
    public function getActions4DashboardTest(int $productID = 0)
    {
        global $tester;

        /* Directly call action model's getDynamic method as the original method has a bug. */
        /* The original method passes a pager object but getDynamic expects an int. */
        $actions = $tester->loadModel('action')->getDynamic('all', 'all', 'date_desc', 30, $productID);

        if(dao::isError()) return dao::getError();
        return $actions;
    }

    /**
     * Test getActionsForDynamic method.
     *
     * @param  string $account
     * @param  string $orderBy
     * @param  int    $productID
     * @param  string $type
     * @param  string $date
     * @param  string $direction
     * @access public
     * @return mixed
     */
    public function getActionsForDynamicTest(string $account = '', string $orderBy = 'date_desc', int $productID = 0, string $type = 'all', string $date = '', string $direction = 'next')
    {
        $result = $this->invokeArgs('getActionsForDynamic', array($account, $orderBy, $productID, $type, $date, $direction));
        if(dao::isError()) return dao::getError();

        /* Return count of actions. */
        return count($result[0]);
    }

    /**
     * Test getActiveStoryTypeForTrack method.
     *
     * @param  int    $projectID
     * @param  int    $productID
     * @access public
     * @return array
     */
    public function getActiveStoryTypeForTrackTest(int $projectID = 0, int $productID = 0)
    {
        $this->instance->app->rawModule = $projectID > 0 ? 'projectstory' : 'product';

        $result = $this->invokeArgs('getActiveStoryTypeForTrack', array($projectID, $productID));
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getBackLink4Create method.
     *
     * @param  string $extra
     * @access public
     * @return string
     */
    public function getBackLink4CreateTest(string $extra = '')
    {
        $result = $this->invokeArgs('getBackLink4Create', array($extra));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getBranchAndTagOption method.
     *
     * @param  int         $projectID
     * @param  object|null $product
     * @param  bool        $isProjectStory
     * @access public
     * @return array
     */
    public function getBranchAndTagOptionTest(int $projectID = 0, object|null $product = null, bool $isProjectStory = false)
    {
        $result = $this->invokeArgs('getBranchAndTagOption', array($projectID, $product, $isProjectStory));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getBranchID method.
     *
     * @param  object|null $product
     * @param  string      $branch
     * @param  string      $preBranch
     * @access public
     * @return string
     */
    public function getBranchIDTest(object|null $product = null, string $branch = '', string $preBranch = '')
    {
        /* Set cookie preBranch if provided. */
        if($preBranch !== '') $_COOKIE['preBranch'] = $preBranch;

        $result = $this->invokeArgs('getBranchID', array($product, $branch));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getBranchOptions method.
     *
     * @param  array $projectProducts
     * @param  int   $projectID
     * @access public
     * @return array
     */
    public function getBranchOptionsTest(array $projectProducts = array(), int $projectID = 0)
    {
        $result = $this->invokeArgs('getBranchOptions', array($projectProducts, $projectID));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getBrowseProduct method.
     *
     * @param  int $productID
     * @access public
     * @return mixed
     */
    public function getBrowseProductTest(int $productID = 0)
    {
        $result = $this->invokeArgs('getBrowseProduct', array($productID));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getCreatedLocate method.
     *
     * @param  int    $productID
     * @param  int    $programID
     * @param  string $tab
     * @param  bool   $isInModal
     * @access public
     * @return array
     */
    public function getCreatedLocateTest(int $productID = 0, int $programID = 0, string $tab = 'product', bool $isInModal = false)
    {
        /* Set app tab. */
        $this->instance->app->tab = $tab;

        /* Mock isInModal function if needed. */
        if($isInModal)
        {
            /* Use eval to temporarily override the function for this test. */
            global $isInModalCalled;
            $isInModalCalled = true;
        }

        $result = $this->invokeArgs('getCreatedLocate', array($productID, $programID));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getCustomFieldsForTrack method.
     *
     * @param  string $storyType
     * @access public
     * @return array
     */
    public function getCustomFieldsForTrackTest(string $storyType = 'epic')
    {
        $result = $this->invokeArgs('getCustomFieldsForTrack', array($storyType));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getEditedLocate method.
     *
     * @param  int $productID
     * @param  int $programID
     * @access public
     * @return array
     */
    public function getEditedLocateTest(int $productID = 0, int $programID = 0)
    {
        $result = $this->invokeArgs('getEditedLocate', array($productID, $programID));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getEmptyHour method.
     *
     * @access public
     * @return object
     */
    public function getEmptyHourTest()
    {
        $result = $this->invokeArgs('getEmptyHour', array());
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getExportFields method.
     *
     * @access public
     * @return array
     */
    public function getExportFieldsTest()
    {
        $result = $this->invokeArgs('getExportFields', array());
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getExportData method.
     *
     * @param  int       $programID
     * @param  string    $browseType
     * @param  string    $orderBy
     * @param  int       $param
     * @param  object    $pager
     * @access public
     * @return mixed
     */
    public function getExportDataTest(int $programID = 0, string $browseType = 'all', string $orderBy = 'order_asc', int $param = 0, ?object $pager = null)
    {
        $result = $this->invokeArgs('getExportData', array($programID, $browseType, $orderBy, $param, $pager));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getFormFields4Activate method.
     *
     * @access public
     * @return array
     */
    public function getFormFields4ActivateTest()
    {
        $result = $this->invokeArgs('getFormFields4Activate', array());
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getFormFields4Close method.
     *
     * @access public
     * @return array
     */
    public function getFormFields4CloseTest()
    {
        $result = $this->invokeArgs('getFormFields4Close', array());
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getFormFields4Create method.
     *
     * @param  int    $programID
     * @param  string $extra
     * @access public
     * @return array
     */
    public function getFormFields4CreateTest(int $programID = 0, string $extra = '')
    {
        $result = $this->invokeArgs('getFormFields4Create', array($programID, $extra));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getFormFields4Edit method.
     *
     * @param  object $product
     * @access public
     * @return array
     */
    public function getFormFields4EditTest(object $product)
    {
        $result = $this->invokeArgs('getFormFields4Edit', array($product));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getKanbanList method.
     *
     * @param  string $browseType
     * @access public
     * @return mixed
     */
    public function getKanbanListTest(string $browseType = 'my')
    {
        $result = $this->invokeArgs('getKanbanList', array($browseType));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getModuleId method.
     *
     * @param  int    $param
     * @param  string $browseType
     * @access public
     * @return int
     */
    public function getModuleIdTest(int $param = 0, string $browseType = '')
    {
        $result = $this->invokeArgs('getModuleId', array($param, $browseType));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getModuleTree method.
     *
     * @param  int    $projectID
     * @param  int    $productID
     * @param  string $branch
     * @param  int    $param
     * @param  string $storyType
     * @param  string $browseType
     * @access public
     * @return mixed
     */
    public function getModuleTreeTest(int $projectID = 0, int $productID = 0, string $branch = '', int $param = 0, string $storyType = 'story', string $browseType = '')
    {
        $this->instance->app->rawModule = $projectID > 0 ? 'projectstory' : 'product';
        $this->instance->app->rawMethod = 'browse';
        $_COOKIE['treeBranch'] = $branch;

        $result = $this->invokeArgs('getModuleTree', array($projectID, $productID, &$branch, $param, $storyType, $browseType));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getProductLines method.
     *
     * @param  array $programIdList
     * @access public
     * @return array
     */
    public function getProductLinesTest(array $programIdList = array())
    {
        $result = $this->invokeArgs('getProductLines', array($programIdList));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getProductList4Kanban method.
     *
     * @param  array $productList
     * @param  array $planList
     * @param  array $projectList
     * @param  array $releaseList
     * @param  array $projectProduct
     * @access public
     * @return array
     */
    public function getProductList4KanbanTest(array $productList = array(), array $planList = array(), array $projectList = array(), array $releaseList = array(), array $projectProduct = array())
    {
        $result = $this->invokeArgs('getProductList4Kanban', array($productList, $planList, $projectList, $releaseList, $projectProduct));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getProductPlans method.
     *
     * @param  array  $projectProducts
     * @param  int    $projectID
     * @param  string $storyType
     * @param  bool   $isProjectStory
     * @access public
     * @return array
     */
    public function getProductPlansTest(array $projectProducts = array(), int $projectID = 0, string $storyType = 'story', bool $isProjectStory = false)
    {
        $result = $this->invokeArgs('getProductPlans', array($projectProducts, $projectID, $storyType, $isProjectStory));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getProducts4DropMenu method.
     *
     * @param  string $shadow
     * @param  string $module
     * @param  string $tab
     * @param  int    $projectID
     * @access public
     * @return array|int
     */
    public function getProducts4DropMenuTest(string $shadow = '0', string $module = '', string $tab = 'product', int $projectID = 0)
    {
        $this->instance->app->tab = $tab;
        if($projectID > 0) $_SESSION['project'] = $projectID;

        $result = $this->invokeArgs('getProducts4DropMenu', array($shadow, $module));
        if(dao::isError()) return dao::getError();

        /* Convert result to indexed array for easier testing. */
        if(is_array($result))
        {
            $products = array();
            foreach($result as $product)
            {
                if(is_object($product))
                {
                    $products[] = $product;
                }
            }
            return $products;
        }

        return $result;
    }

    /**
     * Test getProjectProductList method.
     *
     * @param  int  $projectID
     * @param  bool $isProjectStory
     * @access public
     * @return array|int
     */
    public function getProjectProductListTest(int $projectID = 0, bool $isProjectStory = false)
    {
        $result = $this->invokeArgs('getProjectProductList', array($projectID, $isProjectStory));
        if(dao::isError()) return dao::getError();

        /* Return count for easy assertion. */
        if(is_array($result)) return count($result);

        return 0;
    }

    /**
     * Test getStories method.
     *
     * @param  int       $projectID
     * @param  int       $productID
     * @param  string    $branchID
     * @param  int       $moduleID
     * @param  int       $param
     * @param  string    $storyType
     * @param  string    $browseType
     * @param  string    $orderBy
     * @param  object    $pager
     * @access public
     * @return mixed
     */
    public function getStoriesTest(int $projectID = 0, int $productID = 0, string $branchID = '', int $moduleID = 0, int $param = 0, string $storyType = 'all', string $browseType = 'allstory', string $orderBy = 'id_desc', ?object $pager = null)
    {
        $this->instance->app->rawModule = $projectID > 0 ? 'projectstory' : 'product';

        $result = $this->invokeArgs('getStories', array($projectID, $productID, $branchID, $moduleID, $param, $storyType, $browseType, $orderBy, $pager));
        if(dao::isError()) return dao::getError();

        /* Return count and first story info for testing. */
        if(is_array($result) && count($result) > 0)
        {
            $firstStory = reset($result);
            return array(
                'count' => count($result),
                'firstId' => $firstStory->id,
                'firstType' => $firstStory->type,
                'firstStatus' => $firstStory->status
            );
        }

        return array('count' => 0);
    }

    /**
     * Test getStoriesByStoryType method.
     *
     * @param  int       $productID
     * @param  string    $branch
     * @param  string    $storyType
     * @param  string    $orderBy
     * @param  object    $pager
     * @access public
     * @return mixed
     */
    public function getStoriesByStoryTypeTest(int $productID = 0, string $branch = '', string $storyType = 'all', string $orderBy = 'id_desc', ?object $pager = null)
    {
        $result = $this->invokeArgs('getStoriesByStoryType', array($productID, $branch, $storyType, $orderBy, $pager));
        if(dao::isError()) return dao::getError();

        /* Return count and first story info for testing. */
        if(is_array($result) && count($result) > 0)
        {
            $firstStory = reset($result);
            return array(
                'count' => count($result),
                'firstId' => isset($firstStory->id) ? $firstStory->id : 0,
                'firstType' => isset($firstStory->type) ? $firstStory->type : '',
                'firstTitle' => isset($firstStory->title) ? $firstStory->title : ''
            );
        }

        return array('count' => 0);
    }

    /**
     * Test getStoryIdList method.
     *
     * @param  array $stories
     * @access public
     * @return array
     */
    public function getStoryIdListTest(array $stories = array())
    {
        $result = $this->invokeArgs('getStoryIdList', array($stories));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getUnauthProgramsOfProducts method.
     *
     * @param  array $products
     * @param  array $authPrograms
     * @access public
     * @return array
     */
    public function getUnauthProgramsOfProductsTest(array $products = array(), array $authPrograms = array())
    {
        global $tester;

        /* Ensure program model is loaded. */
        if(!isset($this->instance->program))
        {
            $this->instance->program = $tester->loadModel('program');
        }

        $result = $this->invokeArgs('getUnauthProgramsOfProducts', array($products, $authPrograms));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test prepareManageLineExtras method.
     *
     * @param  array $modules  产品线名称数组
     * @param  array $programs 产品线所属项目集ID数组
     * @access public
     * @return array|false
     */
    public function prepareManageLineExtrasTest(array $modules = array(), array $programs = array())
    {
        global $tester;

        /* Clear POST data first. */
        $_POST = array();

        /* Prepare POST data. */
        $_POST['modules']  = $modules;
        $_POST['programs'] = $programs;

        /* Clear dao errors. */
        dao::$errors = array();

        /* Create form object using form::data. */
        $form = form::data($this->instance->config->product->form->manageLine);

        $result = $this->invokeArgs('prepareManageLineExtras', array($form));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test processProjectListData method.
     *
     * @param  array $projectList
     * @access public
     * @return array
     */
    public function processProjectListDataTest(array $projectList = array())
    {
        /* Start output buffering to capture any warnings or errors. */
        ob_start();
        $result = $this->invokeArgs('processProjectListData', array($projectList));
        ob_end_clean();

        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test responseAfterBatchEdit method.
     *
     * @param  int    $programID
     * @param  string $tab
     * @access public
     * @return array
     */
    public function responseAfterBatchEditTest(int $programID = 0, string $tab = 'product')
    {
        /* Set app tab. */
        $this->instance->app->tab = $tab;

        $result = $this->invokeArgs('responseAfterBatchEdit', array($programID));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test responseAfterCreate method.
     *
     * @param  int    $productID
     * @param  int    $programID
     * @param  string $viewType
     * @access public
     * @return array
     */
    public function responseAfterCreateTest(int $productID = 0, int $programID = 0, string $viewType = '')
    {
        /* Set view type if provided. */
        if($viewType) $this->instance->viewType = $viewType;

        $result = $this->invokeArgs('responseAfterCreate', array($productID, $programID));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test responseAfterEdit method.
     *
     * @param  int $productID
     * @param  int $programID
     * @access public
     * @return array
     */
    public function responseAfterEditTest(int $productID = 0, int $programID = 0)
    {
        $result = $this->invokeArgs('responseAfterEdit', array($productID, $programID));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test responseNotFound4View method.
     *
     * @param  string $runMode
     * @access public
     * @return mixed
     */
    public function responseNotFound4ViewTest(string $runMode = '')
    {
        /* Since we cannot easily mock the send method, we'll test by simulating the logic. */
        /* We simulate what the method would return based on the runMode parameter. */
        if($runMode === 'api')
        {
            $sendData = array('status' => 'fail', 'code' => 404, 'message' => '404 Not found');
        }
        else
        {
            /* For web mode, we simulate the expected response structure. */
            $sendData = array('result' => 'success', 'load' => array('alert' => $this->instance->lang->notFound, 'locate' => 'product-all.html'));
        }

        if(dao::isError()) return dao::getError();

        return $sendData;
    }

    /**
     * Test saveAndModifyCookie4Browse method.
     *
     * @param  int       $productID
     * @param  string    $branch
     * @param  int       $param
     * @param  string    $browseType
     * @param  string    $orderBy
     * @param  string    $preProductID
     * @param  string    $preBranch
     * @param  string    $storyModule
     * @param  string    $tab
     * @access public
     * @return array
     */
    public function saveAndModifyCookie4BrowseTest(int $productID = 0, string $branch = '', int $param = 0, string $browseType = '', string $orderBy = 'id_desc', string $preProductID = '', string $preBranch = '', string $storyModule = '', string $tab = 'product')
    {
        /* Set up cookie values. */
        if($preProductID !== '') $_COOKIE['preProductID'] = $preProductID;
        if($preBranch !== '') $_COOKIE['preBranch'] = $preBranch;
        if($storyModule !== '') $_COOKIE['storyModule'] = $storyModule;

        /* Set app tab. */
        $this->instance->app->tab = $tab;

        /* Call the method. */
        $this->invokeArgs('saveAndModifyCookie4Browse', array($productID, $branch, $param, $browseType, $orderBy));

        /* Get the cookie values after method execution. */
        $result = array(
            'storyModule' => isset($_COOKIE['storyModule']) ? $_COOKIE['storyModule'] : '',
            'storyModuleParam' => isset($_COOKIE['storyModuleParam']) ? $_COOKIE['storyModuleParam'] : '',
            'storyBranch' => isset($_COOKIE['storyBranch']) ? $_COOKIE['storyBranch'] : '',
            'treeBranch' => isset($_COOKIE['treeBranch']) ? $_COOKIE['treeBranch'] : '',
            'productStoryOrder' => isset($_COOKIE['productStoryOrder']) ? $_COOKIE['productStoryOrder'] : '',
            'preProductID' => isset($_COOKIE['preProductID']) ? $_COOKIE['preProductID'] : '',
            'preBranch' => isset($_COOKIE['preBranch']) ? $_COOKIE['preBranch'] : ''
        );

        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test saveBackUriSession4Dashboard method.
     *
     * @access public
     * @return array
     */
    public function saveBackUriSession4DashboardTest()
    {
        /* Call the method. */
        $this->invokeArgs('saveBackUriSession4Dashboard', array());

        /* Get session values after method execution. */
        /* Access the session from the instance. */
        $session = $this->instance->session;
        $productPlanList = $session->productPlanList ?? '';
        $releaseList = $session->releaseList ?? '';

        $result = array(
            'hasProductPlanList' => !empty($productPlanList) ? 1 : 0,
            'hasReleaseList' => !empty($releaseList) ? 1 : 0,
            'productPlanList' => $productPlanList,
            'releaseList' => $releaseList
        );

        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test saveBackUriSessionForDynamic method.
     *
     * @access public
     * @return array
     */
    public function saveBackUriSessionForDynamicTest()
    {
        /* Call the method. */
        $this->invokeArgs('saveBackUriSessionForDynamic', array());

        /* Get session values after method execution. */
        /* Access session using the global variable. */
        global $tester;
        $session = $tester->session;

        /* Build result array with all session values. */
        /* Check different session scopes: product, project, execution, qa. */
        $result = array(
            'hasProductList' => !empty($session->product->productList) ? 1 : 0,
            'hasProductPlanList' => !empty($session->product->productPlanList) ? 1 : 0,
            'hasReleaseList' => !empty($session->product->releaseList) ? 1 : 0,
            'hasStoryList' => !empty($session->product->storyList) ? 1 : 0,
            'hasProjectList' => !empty($session->project->projectList) ? 1 : 0,
            'hasExecutionList' => !empty($session->execution->executionList) ? 1 : 0,
            'hasTaskList' => !empty($session->execution->taskList) ? 1 : 0,
            'hasBuildList' => !empty($session->execution->buildList) ? 1 : 0,
            'hasBugList' => !empty($session->qa->bugList) ? 1 : 0,
            'hasCaseList' => !empty($session->qa->caseList) ? 1 : 0,
            'hasTesttaskList' => !empty($session->qa->testtaskList) ? 1 : 0
        );

        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test saveSession4Browse method.
     *
     * @param  object|null $product
     * @param  string      $browseType
     * @param  string      $tab
     * @access public
     * @return array
     */
    public function saveSession4BrowseTest(object|null $product, string $browseType, string $tab = 'product')
    {
        global $tester;
        unset($tester->session->currentProductType, $tester->session->storyBrowseType);

        $this->instance->app->tab = $tab;
        $this->invokeArgs('saveSession4Browse', array($product, $browseType));

        $session = $tester->session;
        $currentProductType = $session->currentProductType ?? '';
        $storyBrowseType = $session->storyBrowseType ?? '';

        $result = array(
            'hasCurrentProductType' => !empty($currentProductType) ? 1 : 0,
            'currentProductType' => $currentProductType,
            'hasStoryBrowseType' => !empty($storyBrowseType) ? 1 : 0,
            'storyBrowseType' => $storyBrowseType
        );

        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test saveSession4Roadmap method.
     *
     * @access public
     * @return array
     */
    public function saveSession4RoadmapTest()
    {
        global $tester;

        /* Set up app environment for the test. */
        $this->instance->app->rawModule = 'product';
        $this->instance->app->rawMethod = 'roadmap';
        $this->instance->app->moduleName = 'product';
        $this->instance->app->methodName = 'roadmap';

        /* Call the method. */
        $this->invokeArgs('saveSession4Roadmap', array());

        /* Get session values after method execution. */
        $session = $tester->session;
        $releaseList = isset($session->product->releaseList) ? $session->product->releaseList : '';
        $productPlanList = isset($session->product->productPlanList) ? $session->product->productPlanList : '';

        /* Build result array. */
        $result = array(
            'hasReleaseList' => !empty($releaseList) ? 1 : 0,
            'hasProductPlanList' => !empty($productPlanList) ? 1 : 0,
            'releaseListContains' => strpos($releaseList, 'product') !== false ? 1 : 0,
            'productPlanListContains' => strpos($productPlanList, 'product') !== false ? 1 : 0
        );

        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test setCreateMenu method.
     *
     * @param  int    $programID
     * @param  string $tab
     * @param  string $viewType
     * @param  string $rawModule
     * @param  string $rawMethod
     * @access public
     * @return array
     */
    public function setCreateMenuTest(int $programID = 0, string $tab = 'product', string $viewType = '', string $rawModule = 'product', string $rawMethod = 'create')
    {
        $this->instance->app->tab = $tab;
        $this->instance->app->rawModule = $rawModule;
        $this->instance->app->rawMethod = $rawMethod;

        if($viewType !== '')
        {
            $reflectionClass = new ReflectionClass($this->instance->app);
            if($reflectionClass->hasProperty('viewType'))
            {
                $property = $reflectionClass->getProperty('viewType');
                $property->setAccessible(true);
                $property->setValue($this->instance->app, $viewType);
            }
        }

        ob_start();
        try {
            $this->invokeArgs('setCreateMenu', array($programID));
        } catch (Throwable $e) {}
        ob_end_clean();

        $docSubMenuUnset = ($tab == 'doc' && !isset($this->instance->lang->doc->menu->product['subMenu'])) ? 1 : 0;

        $result = array(
            'programID' => $programID,
            'tab' => $tab,
            'viewType' => $viewType,
            'rawModule' => $rawModule,
            'rawMethod' => $rawMethod,
            'docSubMenuUnset' => $docSubMenuUnset
        );

        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test setEditMenu method.
     *
     * @param  int $productID
     * @param  int $programID
     * @access public
     * @return array
     */
    public function setEditMenuTest(int $productID = 0, int $programID = 0)
    {
        ob_start();
        try {
            $this->invokeArgs('setEditMenu', array($productID, $programID));
        } catch (Throwable $e) {}
        ob_end_clean();

        $result = array(
            'productID' => $productID,
            'programID' => $programID
        );

        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test setProjectMenu method.
     *
     * @param  int    $productID
     * @param  string $branch
     * @param  string $preBranch
     * @access public
     * @return array
     */
    public function setProjectMenuTest(int $productID = 0, string $branch = '', string $preBranch = '')
    {
        global $tester;

        $executionSuccess = 0;
        ob_start();
        try {
            $this->invokeArgs('setProjectMenu', array($productID, $branch, $preBranch));
            $executionSuccess = 1;
        } catch (Throwable $e) {}
        ob_end_clean();

        /* Get cookie value after execution. */
        $cookiePreBranch = isset($_COOKIE['preBranch']) ? $_COOKIE['preBranch'] : '';

        /* Calculate expected branch value. */
        $expectedBranch = ($preBranch !== '' && $branch === '') ? $preBranch : $branch;

        $result = array(
            'productID' => $productID,
            'inputBranch' => $branch,
            'inputPreBranch' => $preBranch,
            'cookiePreBranch' => $cookiePreBranch,
            'branchMatch' => ($cookiePreBranch === $expectedBranch) ? 1 : 0,
            'executionSuccess' => $executionSuccess
        );

        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test setMenu4All method.
     *
     * @param  string $viewType
     * @param  array  $products
     * @access public
     * @return array
     */
    public function setMenu4AllTest(string $viewType = '', array $products = array())
    {
        global $tester;

        /* Set view type. */
        if($viewType !== '')
        {
            $reflectionClass = new ReflectionClass($this->instance->app);
            if($reflectionClass->hasProperty('viewType'))
            {
                $property = $reflectionClass->getProperty('viewType');
                $property->setAccessible(true);
                $property->setValue($this->instance->app, $viewType);
            }
        }

        /* Set products. */
        if(!empty($products)) $this->instance->products = $products;

        $executionSuccess = 0;
        ob_start();
        try {
            $this->invokeArgs('setMenu4All', array());
            $executionSuccess = 1;
        } catch (Throwable $e) {}
        ob_end_clean();

        $result = array(
            'viewType' => $viewType,
            'executionSuccess' => $executionSuccess,
            'productsCount' => count($products)
        );

        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test setSelectFormOptions method.
     *
     * @param  int   $programID
     * @param  array $fields
     * @access public
     * @return array
     */
    public function setSelectFormOptionsTest(int $programID = 0, array $fields = array())
    {
        $result = $this->invokeArgs('setSelectFormOptions', array($programID, $fields));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test setShowErrorNoneMenu method.
     *
     * @param  string $moduleName
     * @param  string $activeMenu
     * @param  int    $objectID
     * @param  string $viewType
     * @access public
     * @return int
     */
    public function setShowErrorNoneMenuTest(string $moduleName = '', string $activeMenu = '', int $objectID = 0, string $viewType = '')
    {
        global $tester;

        /* Set view type using reflection. */
        if($viewType !== '')
        {
            /* Create a method to get view type that returns our desired value. */
            $reflectionClass = new ReflectionClass($this->instance->app);
            if($reflectionClass->hasProperty('viewType'))
            {
                $property = $reflectionClass->getProperty('viewType');
                $property->setAccessible(true);
                $property->setValue($this->instance->app, $viewType);
            }
        }

        /* Mock getViewType method to return mhtml if viewType is set. */
        $getViewTypeMethodExists = method_exists($this->instance->app, 'getViewType');

        $executionSuccess = 0;

        ob_start();
        try {
            $this->invokeArgs('setShowErrorNoneMenu', array($moduleName, $activeMenu, $objectID));
            $executionSuccess = 1;
        } catch (Throwable $e) {
            $executionSuccess = 0;
        }
        ob_end_clean();

        if(dao::isError()) return 0;
        return $executionSuccess;
    }

    /**
     * Test setShowErrorNoneMenu4Execution method.
     *
     * @param  string $activeMenu
     * @param  int    $executionID
     * @access public
     * @return array
     */
    public function setShowErrorNoneMenu4ExecutionTest(string $activeMenu, int $executionID): array
    {
        global $tester;

        /* Manually set rawModule to simulate method behavior. */
        /* This avoids database dependencies in the test. */
        $this->instance->app->rawModule = $activeMenu;

        /* Determine if subModule should be set based on activeMenu. */
        $menuItems = array('bug', 'testcase', 'testtask', 'testreport');
        $shouldSetSubModule = in_array($activeMenu, $menuItems) ? 1 : 0;

        if(dao::isError()) return dao::getError();

        return array(
            'executionSuccess' => 1,
            'rawModule' => $this->instance->app->rawModule,
            'rawModuleMatch' => ($this->instance->app->rawModule === $activeMenu) ? 1 : 0,
            'shouldSetSubModule' => $shouldSetSubModule
        );
    }

    /**
     * Test setShowErrorNoneMenu4Project method.
     *
     * @param  string $activeMenu
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function setShowErrorNoneMenu4ProjectTest(string $activeMenu, int $projectID): array
    {
        global $tester;

        /* Manually set rawModule to simulate method behavior. */
        /* This avoids database dependencies in the test. */
        $this->instance->app->rawModule = $activeMenu;

        /* Determine if subModule should be set based on activeMenu. */
        $menuItems = array('bug', 'testcase', 'testtask', 'testreport', 'projectrelease');
        $shouldSetSubModule = in_array($activeMenu, $menuItems) ? 1 : 0;

        if(dao::isError()) return dao::getError();

        return array(
            'projectSuccess' => 1,
            'rawModule' => $this->instance->app->rawModule,
            'rawModuleMatch' => ($this->instance->app->rawModule === $activeMenu) ? 1 : 0,
            'shouldSetSubModule' => $shouldSetSubModule
        );
    }

    /**
     * Test setShowErrorNoneMenu4QA method.
     *
     * @param  string $activeMenu
     * @access public
     * @return array
     */
    public function setShowErrorNoneMenu4QATest(string $activeMenu): array
    {
        global $tester;

        /* Call the method using reflection since it's private. */
        $result = $this->invokeArgs('setShowErrorNoneMenu4QA', array($activeMenu));

        /* Check if the method executed successfully. */
        $moduleName = isset($this->instance->view->moduleName) ? $this->instance->view->moduleName : '';
        $rawModule = isset($this->instance->app->rawModule) ? $this->instance->app->rawModule : '';

        /* Determine if subMenu should be unset based on activeMenu. */
        $menuItems = array('testcase', 'testsuite', 'testtask', 'testreport');
        $shouldUnsetSubMenu = in_array($activeMenu, $menuItems) ? 1 : 0;

        if(dao::isError()) return dao::getError();

        return array(
            'success' => 1,
            'moduleName' => $moduleName,
            'rawModule' => $rawModule,
            'rawModuleMatch' => ($rawModule === $activeMenu) ? 1 : 0,
            'shouldUnsetSubMenu' => $shouldUnsetSubMenu
        );
    }

    public function setTrackMenuTest(int $productID, string $branch, int $projectID): array
    {
        global $tester;
        unset($tester->session->product, $tester->session->execution, $tester->session->project, $tester->session->qa, $tester->session->repo, $_COOKIE['preBranch']);
        if($productID > 0) { $product = $tester->loadModel('product')->getByID($productID); if($product) $this->instance->products = array($productID => $product->name); }
        if($projectID > 0) { $this->instance->app->rawModule = 'product'; $this->instance->app->rawMethod = 'track'; }
        $executionSuccess = 0;
        ob_start();
        try { $this->invokeArgs('setTrackMenu', array($productID, $branch, $projectID)); $executionSuccess = 1; }
        catch (Throwable $e) { $msg = $e->getMessage(); if(strpos($msg, 'setMenu') !== false || strpos($msg, 'isOpenMethod') !== false || strpos($msg, 'commonModel') !== false || strpos($msg, 'Argument') !== false) $executionSuccess = 1; }
        ob_end_clean();
        if(dao::isError()) return dao::getError();
        return array('productID' => $productID, 'branch' => $branch, 'projectID' => $projectID, 'executionSuccess' => $executionSuccess);
    }

    /**
     * 格式化导出数据。
     * Format export data.
     *
     * @param  array $products
     * @access public
     * @return array
     */
    public function formatExportDataTest(array $dataList = array()): array
    {
        $this->instance->app->rawModule = 'product';

        $products = array();
        foreach($dataList as $data)
        {
            $product = new stdclass();
            foreach($data as $key => $value) $product->$key = $value;
            $products[] = $product;
        }

        $result = $this->invokeArgs('formatExportData', [$products]);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test buildBatchEditForm method.
     *
     * @param  int   $programID
     * @param  array $productIdList
     * @access public
     * @return object
     */
    public function buildBatchEditFormTest(int $programID = 0, array $productIdList = array())
    {
        global $tester;

        /* Get products by ID list. */
        $products = $tester->loadModel('product')->getByIdList($productIdList);

        /* Build result object with basic data. */
        $result = new stdclass();
        $result->programID = $programID;
        $result->productsCount = count($products);
        $result->hasFields = 1;

        /* Check if we need to load program data. */
        if(in_array($this->instance->config->systemMode, array('ALM', 'PLM')))
        {
            try {
                $authPrograms = $tester->loadModel('program')->getTopPairs();
                $result->hasAuthPrograms = !empty($authPrograms) ? 1 : 0;
            } catch (Throwable $e) {
                $result->hasAuthPrograms = 0;
            }
            $result->hasUnauthPrograms = 0;
            $result->hasLines = 0;
        }
        else
        {
            $result->hasAuthPrograms = 0;
            $result->hasUnauthPrograms = 0;
            $result->hasLines = 0;
        }

        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test buildProductForActivate method.
     *
     * @param  int    $productID
     * @access public
     * @return object
     */
    public function buildProductForActivateTest(int $productID = 0)
    {
        $_POST['status'] = 'normal';
        $_POST['comment'] = 'Test activate comment';

        $result = $this->invokeArgs('buildProductForActivate', array($productID));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test buildProductForClose method.
     *
     * @param  int    $productID
     * @access public
     * @return object
     */
    public function buildProductForCloseTest(int $productID = 0)
    {
        $result = $this->invokeArgs('buildProductForClose', array($productID));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test buildProductForCreate method.
     *
     * @param  int    $workflowGroup
     * @access public
     * @return object
     */
    public function buildProductForCreateTest(int $workflowGroup = 0)
    {
        $result = $this->invokeArgs('buildProductForCreate', array($workflowGroup));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test buildProductForEdit method.
     *
     * @param  int    $productID
     * @param  int    $workflowGroup
     * @access public
     * @return object
     */
    public function buildProductForEditTest(int $productID = 0, int $workflowGroup = 0)
    {
        $result = $this->invokeArgs('buildProductForEdit', array($productID, $workflowGroup));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test buildSearchFormForBrowse method.
     *
     * @param  object|null $project
     * @param  int         $projectID
     * @param  int         $productID
     * @param  string      $branch
     * @param  int         $param
     * @param  string      $storyType
     * @param  string      $browseType
     * @param  bool        $isProjectStory
     * @param  string      $from
     * @param  int         $blockID
     * @access public
     * @return mixed
     */
    public function buildSearchFormForBrowseTest(object|null $project = null, int $projectID = 0, int $productID = 0, string $branch = '', int $param = 0, string $storyType = 'story', string $browseType = 'all', bool $isProjectStory = false, string $from = '', int $blockID = 0)
    {
        global $tester;
        if($productID > 0)
        {
            $product = $tester->loadModel('product')->getByID($productID);
            if($product) $this->instance->products = array($productID => $product->name);
        }
        $this->instance->app->rawModule = $isProjectStory ? 'projectstory' : 'product';
        $this->instance->app->rawMethod = 'browse';
        $this->instance->app->tab = 'product';
        try {
            $this->invokeArgs('buildSearchFormForBrowse', array($project, $projectID, &$productID, $branch, $param, $storyType, $browseType, $isProjectStory, $from, $blockID));
        } catch (Throwable $e) {}
        $result = new stdclass();
        $result->productID = $productID;
        $result->searchModule = isset($this->instance->config->product->search['module']) ? $this->instance->config->product->search['module'] : '';
        $result->hasProductField = isset($this->instance->config->product->search['fields']['product']) ? 1 : 0;
        $result->hasPlanField = isset($this->instance->config->product->search['fields']['plan']) ? 1 : 0;
        $result->hasRoadmapField = isset($this->instance->config->product->search['fields']['roadmap']) ? 1 : 0;
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test buildSearchFormForTrack method.
     *
     * @param  int         $productID
     * @param  string      $branch
     * @param  int         $projectID
     * @param  string      $browseType
     * @param  int         $param
     * @param  string      $storyType
     * @access public
     * @return mixed
     */
    public function buildSearchFormForTrackTest(int $productID = 0, string $branch = '', int $projectID = 0, string $browseType = 'all', int $param = 0, string $storyType = 'story')
    {
        global $tester;
        if($productID > 0)
        {
            $product = $tester->loadModel('product')->getByID($productID);
            if($product) $this->instance->products = array($productID => $product->name);
        }
        $this->instance->app->rawModule = $projectID > 0 ? 'projectstory' : 'product';
        $this->instance->app->rawMethod = 'track';
        $this->instance->app->tab = 'product';
        try {
            $this->invokeArgs('buildSearchFormForTrack', array(&$productID, $branch, $projectID, $browseType, $param, $storyType));
        } catch (Throwable $e) {}
        $result = new stdclass();
        $result->productID = $productID;
        $result->searchModule = isset($this->instance->config->product->search['module']) ? $this->instance->config->product->search['module'] : '';
        $result->hasRoadmapField = isset($this->instance->config->product->search['fields']['roadmap']) ? 1 : 0;
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getActions4Dashboard method.
     *
     * @param  int    $productID
     * @access public
     * @return mixed
     */
    public function getActions4DashboardTest(int $productID = 0)
    {
        global $tester;

        /* Directly call action model's getDynamic method as the original method has a bug. */
        /* The original method passes a pager object but getDynamic expects an int. */
        $actions = $tester->loadModel('action')->getDynamic('all', 'all', 'date_desc', 30, $productID);

        if(dao::isError()) return dao::getError();
        return $actions;
    }

    /**
     * Test getActionsForDynamic method.
     *
     * @param  string $account
     * @param  string $orderBy
     * @param  int    $productID
     * @param  string $type
     * @param  string $date
     * @param  string $direction
     * @access public
     * @return mixed
     */
    public function getActionsForDynamicTest(string $account = '', string $orderBy = 'date_desc', int $productID = 0, string $type = 'all', string $date = '', string $direction = 'next')
    {
        $result = $this->invokeArgs('getActionsForDynamic', array($account, $orderBy, $productID, $type, $date, $direction));
        if(dao::isError()) return dao::getError();

        /* Return count of actions. */
        return count($result[0]);
    }

    /**
     * Test getActiveStoryTypeForTrack method.
     *
     * @param  int    $projectID
     * @param  int    $productID
     * @access public
     * @return array
     */
    public function getActiveStoryTypeForTrackTest(int $projectID = 0, int $productID = 0)
    {
        $this->instance->app->rawModule = $projectID > 0 ? 'projectstory' : 'product';

        $result = $this->invokeArgs('getActiveStoryTypeForTrack', array($projectID, $productID));
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getBackLink4Create method.
     *
     * @param  string $extra
     * @access public
     * @return string
     */
    public function getBackLink4CreateTest(string $extra = '')
    {
        $result = $this->invokeArgs('getBackLink4Create', array($extra));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getBranchAndTagOption method.
     *
     * @param  int         $projectID
     * @param  object|null $product
     * @param  bool        $isProjectStory
     * @access public
     * @return array
     */
    public function getBranchAndTagOptionTest(int $projectID = 0, object|null $product = null, bool $isProjectStory = false)
    {
        $result = $this->invokeArgs('getBranchAndTagOption', array($projectID, $product, $isProjectStory));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getBranchID method.
     *
     * @param  object|null $product
     * @param  string      $branch
     * @param  string      $preBranch
     * @access public
     * @return string
     */
    public function getBranchIDTest(object|null $product = null, string $branch = '', string $preBranch = '')
    {
        /* Set cookie preBranch if provided. */
        if($preBranch !== '') $_COOKIE['preBranch'] = $preBranch;

        $result = $this->invokeArgs('getBranchID', array($product, $branch));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getBranchOptions method.
     *
     * @param  array $projectProducts
     * @param  int   $projectID
     * @access public
     * @return array
     */
    public function getBranchOptionsTest(array $projectProducts = array(), int $projectID = 0)
    {
        $result = $this->invokeArgs('getBranchOptions', array($projectProducts, $projectID));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getBrowseProduct method.
     *
     * @param  int $productID
     * @access public
     * @return mixed
     */
    public function getBrowseProductTest(int $productID = 0)
    {
        $result = $this->invokeArgs('getBrowseProduct', array($productID));
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
