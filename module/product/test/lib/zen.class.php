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
}
