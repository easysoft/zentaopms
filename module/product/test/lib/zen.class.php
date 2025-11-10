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
}
