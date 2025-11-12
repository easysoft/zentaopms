<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class bugZenTest extends baseTest
{
    protected $moduleName = 'bug';
    protected $className  = 'zen';

    /**
     * Test afterBatchCreate method.
     *
     * @param  object $bug
     * @param  array  $output
     * @access public
     * @return bool
     */
    public function afterBatchCreateTest(object $bug, array $output = array()): bool
    {
        $result = $this->invokeArgs('afterBatchCreate', [$bug, $output]);
        if(dao::isError()) return false;
        return $result;
    }

    /**
     * Test afterCreate method.
     *
     * @param  object $bug
     * @param  array  $params
     * @param  string $from
     * @access public
     * @return bool
     */
    public function afterCreateTest(object $bug, array $params = array(), string $from = ''): bool
    {
        $result = $this->invokeArgs('afterCreate', [$bug, $params, $from]);
        if(dao::isError()) return false;
        return $result;
    }

    /**
     * Test afterUpdate method.
     *
     * @param  object $bug
     * @param  object $oldBug
     * @access public
     * @return bool
     */
    public function afterUpdateTest(object $bug, object $oldBug): bool
    {
        $result = $this->invokeArgs('afterUpdate', [$bug, $oldBug]);
        if(dao::isError()) return false;
        return $result;
    }

    /**
     * Test assignBatchCreateVars method.
     *
     * @param  int    $executionID
     * @param  object $product
     * @param  string $branch
     * @param  array  $output
     * @param  array  $bugImagesFile
     * @access public
     * @return bool
     */
    public function assignBatchCreateVarsTest(int $executionID, object $product, string $branch = '0', array $output = array(), array $bugImagesFile = array()): bool
    {
        $this->invokeArgs('assignBatchCreateVars', [$executionID, $product, $branch, $output, $bugImagesFile]);
        if(dao::isError()) return false;
        return true;
    }

    /**
     * Test assignBatchEditVars method.
     *
     * @param  int    $productID
     * @param  string $branch
     * @access public
     * @return bool
     */
    public function assignBatchEditVarsTest(int $productID, string $branch): bool
    {
        ob_start();
        $this->invokeArgs('assignBatchEditVars', [$productID, $branch]);
        ob_end_clean();
        if(dao::isError()) return false;
        return true;
    }

    /**
     * Test assignKanbanVars method.
     *
     * @param  object $execution
     * @param  array  $output
     * @access public
     * @return array
     */
    public function assignKanbanVarsTest(object $execution, array $output = array()): array
    {
        $instance = $this->getInstance($this->moduleName, $this->className);
        $this->invokeArgs('assignKanbanVars', [$execution, $output]);
        if(dao::isError()) return array();

        return array(
            'executionType' => $instance->view->executionType ?? '',
            'regionID'      => $instance->view->regionID ?? 0,
            'laneID'        => $instance->view->laneID ?? 0,
            'regionPairs'   => !empty($instance->view->regionPairs) ? count($instance->view->regionPairs) : 0,
            'lanePairs'     => !empty($instance->view->lanePairs) ? count($instance->view->lanePairs) : 0,
        );
    }

    /**
     * Test assignProductRelatedVars method.
     *
     * @param  array $bugs
     * @param  array $products
     * @access public
     * @return array
     */
    public function assignProductRelatedVarsTest(array $bugs, array $products): array
    {
        $instance = $this->getInstance($this->moduleName, $this->className);

        try
        {
            $branchTagOption = $this->invokeArgs('assignProductRelatedVars', [$bugs, $products]);
        }
        catch(Throwable $e)
        {
            return array(
                'error'             => $e->getMessage(),
                'branchProduct'     => false,
                'modulesCount'      => 0,
                'productBugOptions' => 0,
                'branchTagOption'   => 0,
            );
        }

        if(dao::isError()) return array('error' => dao::getError());

        $branchCount = 0;
        if(!empty($branchTagOption))
        {
            foreach($branchTagOption as $productID => $branches)
            {
                $branchCount += count($branches);
            }
        }

        return array(
            'branchProduct'       => !empty($instance->view->branchProduct) ? 1 : 0,
            'modulesCount'        => !empty($instance->view->modules) ? count($instance->view->modules) : 0,
            'productBugOptions'   => !empty($instance->view->productBugOptions) ? count($instance->view->productBugOptions) : 0,
            'branchTagOptionView' => !empty($instance->view->branchTagOption) ? count($instance->view->branchTagOption) : 0,
            'returnValue'         => $branchCount,
        );
    }

    /**
     * Test assignUsersForBatchEdit method.
     *
     * @param  array  $bugs
     * @param  array  $productIdList
     * @param  array  $branchTagOption
     * @param  string $tab
     * @access public
     * @return array
     */
    public function assignUsersForBatchEditTest(array $bugs, array $productIdList, array $branchTagOption, string $tab = 'execution'): array
    {
        $instance = $this->getInstance($this->moduleName, $this->className);
        $instance->app->tab = $tab;

        $this->invokeArgs('assignUsersForBatchEdit', [$bugs, $productIdList, $branchTagOption]);
        if(dao::isError()) return array('error' => dao::getError());

        return array(
            'users'             => !empty($instance->view->users) ? count($instance->view->users) : 0,
            'productMembers'    => !empty($instance->view->productMembers) ? count($instance->view->productMembers) : 0,
            'projectMembers'    => !empty($instance->view->projectMembers) ? count($instance->view->projectMembers) : 0,
            'executionMembers'  => !empty($instance->view->executionMembers) ? count($instance->view->executionMembers) : 0,
        );
    }

    /**
     * Test assignVarsForBatchCreate method.
     *
     * @param  object $product
     * @param  object $project
     * @param  array  $bugImagesFile
     * @access public
     * @return array
     */
    public function assignVarsForBatchCreateTest(object $product, object $project, array $bugImagesFile = array()): array
    {
        $instance = $this->getInstance($this->moduleName, $this->className);

        $this->invokeArgs('assignVarsForBatchCreate', [$product, $project, $bugImagesFile]);
        if(dao::isError()) return array('error' => dao::getError());

        $customFields = $instance->view->customFields ?? array();
        $hasKanbanExecution = 0;
        if(!empty($customFields['execution']) && isset($project->model) && $project->model == 'kanban')
        {
            $hasKanbanExecution = 1;
        }

        return array(
            'customFields'       => !empty($customFields) ? count($customFields) : 0,
            'showFields'         => !empty($instance->view->showFields) ? $instance->view->showFields : '',
            'titles'             => !empty($instance->view->titles) ? count($instance->view->titles) : 0,
            'hasBranch'          => !empty($customFields['branch']) ? 1 : 0,
            'hasKanbanExecution' => $hasKanbanExecution,
        );
    }

    /**
     * Test assignVarsForEdit method.
     *
     * @param  object $bug
     * @param  object $product
     * @access public
     * @return array
     */
    public function assignVarsForEditTest(object $bug, object $product): array
    {
        $instance = $this->getInstance($this->moduleName, $this->className);

        try
        {
            $this->invokeArgs('assignVarsForEdit', [$bug, $product]);
        }
        catch(Throwable $e)
        {
            return array('error' => $e->getMessage());
        }

        if(dao::isError()) return array('error' => dao::getError());

        return array(
            'products'            => !empty($instance->view->products) ? count($instance->view->products) : 0,
            'openedBuilds'        => !empty($instance->view->openedBuilds) ? count($instance->view->openedBuilds) : 0,
            'resolvedBuilds'      => !empty($instance->view->resolvedBuilds) ? count($instance->view->resolvedBuilds) : 0,
            'plans'               => !empty($instance->view->plans) ? count($instance->view->plans) : 0,
            'stories'             => !empty($instance->view->stories) ? count($instance->view->stories) : 0,
            'tasks'               => !empty($instance->view->tasks) ? count($instance->view->tasks) : 0,
            'testtasks'           => !empty($instance->view->testtasks) ? count($instance->view->testtasks) : 0,
            'cases'               => !empty($instance->view->cases) ? count($instance->view->cases) : 0,
            'users'               => !empty($instance->view->users) ? count($instance->view->users) : 0,
            'assignedToList'      => !empty($instance->view->assignedToList) ? count($instance->view->assignedToList) : 0,
            'actions'             => !empty($instance->view->actions) ? count($instance->view->actions) : 0,
            'contactList'         => !empty($instance->view->contactList) ? count($instance->view->contactList) : 0,
            'execution'           => !empty($instance->view->execution) ? (int)$instance->view->execution->id : 0,
        );
    }

    /**
     * Test buildBrowseView method.
     *
     * @param  array  $bugs
     * @param  object $product
     * @param  string $branch
     * @param  string $browseType
     * @param  int    $moduleID
     * @param  array  $executions
     * @param  int    $param
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function buildBrowseViewTest(array $bugs, object $product, string $branch, string $browseType, int $moduleID, array $executions, int $param, string $orderBy, object $pager): array
    {
        $instance = $this->getInstance($this->moduleName, $this->className);

        try
        {
            $this->invokeArgs('buildBrowseView', [$bugs, $product, $branch, $browseType, $moduleID, $executions, $param, $orderBy, $pager]);
        }
        catch(Throwable $e)
        {
            return array('error' => $e->getMessage());
        }

        if(dao::isError()) return array('error' => dao::getError());

        return array(
            'product'         => !empty($instance->view->product) ? (int)$instance->view->product->id : 0,
            'branch'          => $instance->view->branch ?? '',
            'browseType'      => $instance->view->browseType ?? '',
            'currentModuleID' => $instance->view->currentModuleID ?? 0,
            'param'           => $instance->view->param ?? 0,
            'orderBy'         => $instance->view->orderBy ?? '',
            'bugsCount'       => !empty($instance->view->bugs) ? count($instance->view->bugs) : 0,
            'executionsCount' => !empty($instance->view->executions) ? count($instance->view->executions) : 0,
            'stories'         => !empty($instance->view->stories) ? count($instance->view->stories) : 0,
            'tasks'           => !empty($instance->view->tasks) ? count($instance->view->tasks) : 0,
            'plans'           => !empty($instance->view->plans) ? count($instance->view->plans) : 0,
            'builds'          => !empty($instance->view->builds) ? count($instance->view->builds) : 0,
            'users'           => !empty($instance->view->users) ? count($instance->view->users) : 0,
            'memberPairs'     => !empty($instance->view->memberPairs) ? count($instance->view->memberPairs) : 0,
        );
    }

    /**
     * Test buildBugForResolve method.
     *
     * @param  object $oldBug
     * @access public
     * @return object|false
     */
    public function buildBugForResolveTest(object $oldBug)
    {
        $result = $this->invokeArgs('buildBugForResolve', [$oldBug]);
        if(dao::isError()) return false;
        return $result;
    }

    /**
     * Test buildBugsForBatchCreate method.
     *
     * @param  int   $productID
     * @param  string $branch
     * @param  array $bugImagesFile
     * @access public
     * @return array|false
     */
    public function buildBugsForBatchCreateTest(int $productID, string $branch, array $bugImagesFile = array())
    {
        $result = $this->invokeArgs('buildBugsForBatchCreate', [$productID, $branch, $bugImagesFile]);
        if(dao::isError()) return false;

        $output = array();
        $output['count'] = count($result);
        if(!empty($result))
        {
            foreach($result as $index => $bug)
            {
                $output[$index] = $bug;
            }
        }

        return $output;
    }

    /**
     * Test buildBugsForBatchEdit method.
     *
     * @param  array $oldBugs
     * @access public
     * @return array|false
     */
    public function buildBugsForBatchEditTest(array $oldBugs = array())
    {
        $result = $this->invokeArgs('buildBugsForBatchEdit', [$oldBugs]);
        if(dao::isError()) return false;

        $output = array();
        $output['count'] = count($result);
        if(!empty($result))
        {
            foreach($result as $index => $bug)
            {
                $output[$index] = $bug;
            }
        }

        return $output;
    }

    /**
     * Test buildCreateForm method.
     *
     * @param  object $bug
     * @param  array  $param
     * @param  string $from
     * @access public
     * @return array
     */
    public function buildCreateFormTest(object $bug, array $param = array(), string $from = ''): array
    {
        $instance = $this->getInstance($this->moduleName, $this->className);

        /* Initialize view users to avoid undefined property error. */
        if(!isset($instance->view->users))
        {
            $instance->view->users = $instance->loadModel('user')->getPairs('noletter');
        }

        try
        {
            $this->invokeArgs('buildCreateForm', [$bug, $param, $from]);
        }
        catch(Throwable $e)
        {
            return array('error' => $e->getMessage());
        }

        if(dao::isError()) return array('error' => dao::getError());

        return array(
            'title'                 => $instance->view->title ?? '',
            'productMembers'        => !empty($instance->view->productMembers) ? count($instance->view->productMembers) : 0,
            'gobackLink'            => $instance->view->gobackLink ?? '',
            'productName'           => $instance->view->productName ?? '',
            'productsCount'         => !empty($instance->view->products) ? count($instance->view->products) : 0,
            'projectsCount'         => !empty($instance->view->projects) ? count($instance->view->projects) : 0,
            'executionsCount'       => !empty($instance->view->executions) ? count($instance->view->executions) : 0,
            'branchesCount'         => !empty($instance->view->branches) ? count($instance->view->branches) : 0,
            'buildsCount'           => !empty($instance->view->builds) ? count($instance->view->builds) : 0,
            'moduleOptionMenuCount' => !empty($instance->view->moduleOptionMenu) ? count($instance->view->moduleOptionMenu) : 0,
            'resultFilesCount'      => !empty($instance->view->resultFiles) ? count($instance->view->resultFiles) : 0,
            'plansCount'            => !empty($instance->view->plans) ? count($instance->view->plans) : 0,
            'casesCount'            => !empty($instance->view->cases) ? count($instance->view->cases) : 0,
        );
    }

    /**
     * Test buildEditForm method.
     *
     * @param  object $bug
     * @access public
     * @return array
     */
    public function buildEditFormTest(object $bug): array
    {
        $instance = $this->getInstance($this->moduleName, $this->className);

        try
        {
            $this->invokeArgs('buildEditForm', [$bug]);
        }
        catch(Throwable $e)
        {
            return array('error' => $e->getMessage());
        }

        if(dao::isError()) return array('error' => dao::getError());

        return array(
            'title'                      => $instance->view->title ?? '',
            'bug'                        => !empty($instance->view->bug) ? (int)$instance->view->bug->id : 0,
            'duplicateBugs'              => !empty($instance->view->duplicateBugs) ? count($instance->view->duplicateBugs) : 0,
            'product'                    => !empty($instance->view->product) ? (int)$instance->view->product->id : 0,
            'moduleOptionMenu'           => !empty($instance->view->moduleOptionMenu) ? count($instance->view->moduleOptionMenu) : 0,
            'projectID'                  => $instance->view->projectID ?? 0,
            'projects'                   => !empty($instance->view->projects) ? count($instance->view->projects) : 0,
            'executions'                 => !empty($instance->view->executions) ? count($instance->view->executions) : 0,
            'branchTagOption'            => !empty($instance->view->branchTagOption) ? count($instance->view->branchTagOption) : 0,
            'projectExecutionPairs'      => !empty($instance->view->projectExecutionPairs) ? count($instance->view->projectExecutionPairs) : 0,
        );
    }

    /**
     * Test buildSearchFormForLinkBugs method.
     *
     * @param  object $bug
     * @param  string $excludeBugs
     * @param  int    $queryID
     * @access public
     * @return array
     */
    public function buildSearchFormForLinkBugsTest(object $bug, string $excludeBugs, int $queryID): array
    {
        global $lang;
        $instance = $this->getInstance($this->moduleName, $this->className);

        /* Reset search config before each test to ensure independence. */
        $instance->config->bug->search['fields']['product']   = $lang->bug->product;
        $instance->config->bug->search['fields']['execution'] = $lang->bug->execution;
        $instance->config->bug->search['fields']['plan']      = $lang->bug->plan;

        try
        {
            $this->invokeArgs('buildSearchFormForLinkBugs', [$bug, $excludeBugs, $queryID]);
        }
        catch(Throwable $e)
        {
            return array('error' => $e->getMessage());
        }

        if(dao::isError()) return array('error' => dao::getError());

        /* Check if search form fields are properly configured. */
        $hasProductField = isset($instance->config->bug->search['fields']['product']);
        $hasExecutionField = isset($instance->config->bug->search['fields']['execution']);
        $hasPlanField = isset($instance->config->bug->search['fields']['plan']);

        return array(
            'hasProductField'   => $hasProductField ? 1 : 0,
            'hasExecutionField' => $hasExecutionField ? 1 : 0,
            'hasPlanField'      => $hasPlanField ? 1 : 0,
        );
    }

    /**
     * Test checkRquiredForEdit method.
     *
     * @param  object $bug
     * @access public
     * @return mixed
     */
    public function checkRquiredForEditTest(object $bug)
    {
        $result = $this->invokeArgs('checkRquiredForEdit', [$bug]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test extractObjectFromExtras method.
     *
     * @param  object $bug
     * @param  array  $output
     * @access public
     * @return object|false
     */
    public function extractObjectFromExtrasTest(object $bug, array $output = array())
    {
        $result = $this->invokeArgs('extractObjectFromExtras', [$bug, $output]);
        if(dao::isError()) return false;
        return $result;
    }

    /**
     * Test getAssignedToPairs method.
     *
     * @param  object $bug
     * @access public
     * @return array
     */
    public function getAssignedToPairsTest(object $bug): array
    {
        $result = $this->invokeArgs('getAssignedToPairs', [$bug]);
        if(dao::isError()) return array();
        return $result;
    }

    /**
     * Test getBatchResolveVars method.
     *
     * @param  array $oldBugs
     * @access public
     * @return array|false
     */
    public function getBatchResolveVarsTest(array $oldBugs)
    {
        $result = $this->invokeArgs('getBatchResolveVars', [$oldBugs]);
        if(dao::isError()) return false;
        return $result;
    }

    /**
     * Test getBrowseBugs method.
     *
     * @param  int          $productID
     * @param  string       $branch
     * @param  string       $browseType
     * @param  array        $executions
     * @param  int          $moduleID
     * @param  int          $queryID
     * @param  string       $orderBy
     * @param  object|null  $pager
     * @access public
     * @return array|false
     */
    public function getBrowseBugsTest(int $productID, string $branch, string $browseType, array $executions, int $moduleID, int $queryID, string $orderBy, object|null $pager = null)
    {
        $result = $this->invokeArgs('getBrowseBugs', [$productID, $branch, $browseType, $executions, $moduleID, $queryID, $orderBy, $pager]);
        if(dao::isError()) return false;
        return $result;
    }

    /**
     * Test getBuildsForCreate method.
     *
     * @param  object $bug
     * @access public
     * @return object|false
     */
    public function getBuildsForCreateTest(object $bug)
    {
        $result = $this->invokeArgs('getBuildsForCreate', [$bug]);
        if(dao::isError()) return false;
        return $result;
    }

    /**
     * Test getExecutionsForCreate method.
     *
     * @param  object $bug
     * @access public
     * @return object|false
     */
    public function getExecutionsForCreateTest(object $bug)
    {
        $result = $this->invokeArgs('getExecutionsForCreate', [$bug]);
        if(dao::isError()) return false;
        return $result;
    }

    /**
     * Test getExportFileName method.
     *
     * @param  int         $executionID
     * @param  string      $browseType
     * @param  object|bool $product
     * @access public
     * @return string
     */
    public function getExportFileNameTest(int $executionID, string $browseType, object|bool $product): string
    {
        $result = $this->invokeArgs('getExportFileName', [$executionID, $browseType, $product]);
        if(dao::isError()) return '';
        return $result;
    }

    /**
     * Test getModulesForCreate method.
     *
     * @param  object $bug
     * @access public
     * @return object
     */
    public function getModulesForCreateTest(object $bug): object
    {
        $result = $this->invokeArgs('getModulesForCreate', [$bug]);
        if(dao::isError()) return (object)array();
        return $result;
    }

    /**
     * Test getProductMembersForCreate method.
     *
     * @param  object $bug
     * @access public
     * @return array
     */
    public function getProductMembersForCreateTest(object $bug): array
    {
        $result = $this->invokeArgs('getProductMembersForCreate', [$bug]);
        if(dao::isError()) return array();
        return $result;
    }

    /**
     * Test getProductsForCreate method.
     *
     * @param  object $bug
     * @access public
     * @return object|false
     */
    public function getProductsForCreateTest(object $bug)
    {
        $result = $this->invokeArgs('getProductsForCreate', [$bug]);
        if(dao::isError()) return false;
        return $result;
    }

    /**
     * Test getProjectsForCreate method.
     *
     * @param  object $bug
     * @access public
     * @return object|false
     */
    public function getProjectsForCreateTest(object $bug)
    {
        $result = $this->invokeArgs('getProjectsForCreate', [$bug]);
        if(dao::isError()) return false;
        return $result;
    }

    /**
     * Test getStoriesForCreate method.
     *
     * @param  object $bug
     * @access public
     * @return object|false
     */
    public function getStoriesForCreateTest(object $bug)
    {
        $result = $this->invokeArgs('getStoriesForCreate', [$bug]);
        if(dao::isError()) return false;
        return $result;
    }

    /**
     * Get bug zen instance for setting view.
     *
     * @access public
     * @return object
     */
    public function getBugZenInstance(): object
    {
        return $this->getInstance('bug', 'zen');
    }

    /**
     * Test getTasksForCreate method.
     *
     * @param  object $bug
     * @access public
     * @return object|false
     */
    public function getTasksForCreateTest(object $bug)
    {
        $result = $this->invokeArgs('getTasksForCreate', [$bug]);
        if(dao::isError()) return false;
        return $result;
    }

    /**
     * Test initBug method.
     *
     * @param  array $fields
     * @access public
     * @return object
     */
    public function initBugTest(array $fields = array()): object
    {
        $result = $this->invokeArgs('initBug', [$fields]);
        if(dao::isError()) return (object)array();
        return $result;
    }

    /**
     * Test mergeChartOption method.
     *
     * @param  string $chartCode
     * @param  string $chartType
     * @access public
     * @return object|false
     */
    public function mergeChartOptionTest(string $chartCode, string $chartType = 'default')
    {
        $result = $this->invokeArgs('mergeChartOption', [$chartCode, $chartType]);
        if(dao::isError()) return false;
        return $result;
    }

    /**
     * Test operateAfterBatchEdit method.
     *
     * @param  object $bug
     * @param  object $oldBug
     * @access public
     * @return bool
     */
    public function operateAfterBatchEditTest(object $bug, object $oldBug): bool
    {
        $this->invokeArgs('operateAfterBatchEdit', [$bug, $oldBug]);
        if(dao::isError()) return false;
        return true;
    }

    /**
     * Test prepareBrowseParams method.
     *
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return array
     */
    public function prepareBrowseParamsTest(string $browseType, int $param, string $orderBy, int $recTotal, int $recPerPage, int $pageID): array
    {
        ob_start();
        $result = $this->invokeArgs('prepareBrowseParams', [$browseType, $param, $orderBy, $recTotal, $recPerPage, $pageID]);
        ob_end_clean();
        if(dao::isError()) return array();
        return array(
            'moduleID'    => $result[0],
            'queryID'     => $result[1],
            'realOrderBy' => $result[2],
            'pagerClass'  => get_class($result[3]),
            'recTotal'    => $result[3]->recTotal,
            'recPerPage'  => $result[3]->recPerPage,
            'pageID'      => $result[3]->pageID,
        );
    }

    /**
     * Test processImageForBatchCreate method.
     *
     * @param  object      $bug
     * @param  string|null $uploadImage
     * @param  array       $bugImagesFiles
     * @access public
     * @return array
     */
    public function processImageForBatchCreateTest(object $bug, string|null $uploadImage, array $bugImagesFiles): array
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('processImageForBatchCreate', [$bug, $uploadImage, $bugImagesFiles]);
            ob_end_clean();
            if(dao::isError()) return array('error' => dao::getError());
            return $result;
        }
        catch(Throwable $e)
        {
            ob_end_clean();
            return array();
        }
    }

    /**
     * Test responseAfterBatchCreate method.
     *
     * @param  int    $productID
     * @param  string $branch
     * @param  int    $executionID
     * @param  array  $bugIdList
     * @param  string $message
     * @access public
     * @return array
     */
    public function responseAfterBatchCreateTest(int $productID, string $branch, int $executionID, array $bugIdList, string $message = ''): array
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('responseAfterBatchCreate', [$productID, $branch, $executionID, $bugIdList, $message]);
            $output = ob_get_clean();
            if(dao::isError()) return array('error' => dao::getError());
            return array('result' => $result, 'output' => $output);
        }
        catch(EndResponseException $e)
        {
            ob_end_clean();
            return array('result' => true, 'response' => $e->getContent());
        }
        catch(Throwable $e)
        {
            ob_end_clean();
            return array('error' => $e->getMessage());
        }
    }

    /**
     * Test responseAfterBatchEdit method.
     *
     * @param  array  $toTaskIdList
     * @param  string $message
     * @access public
     * @return array
     */
    public function responseAfterBatchEditTest(array $toTaskIdList, string $message = ''): array
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('responseAfterBatchEdit', [$toTaskIdList, $message]);
            $output = ob_get_clean();
            if(dao::isError()) return array('error' => dao::getError());
            return array('result' => $result, 'output' => $output);
        }
        catch(EndResponseException $e)
        {
            ob_end_clean();
            return array('result' => true, 'response' => $e->getContent());
        }
        catch(Throwable $e)
        {
            ob_end_clean();
            return array('error' => $e->getMessage());
        }
    }

    /**
     * Test responseAfterCreate method.
     *
     * @param  object $bug
     * @param  array  $params
     * @param  string $message
     * @access public
     * @return array
     */
    public function responseAfterCreateTest(object $bug, array $params = array(), string $message = ''): array
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('responseAfterCreate', [$bug, $params, $message]);
            $output = ob_get_clean();
            if(dao::isError()) return array('error' => dao::getError());
            return array('result' => $result, 'output' => $output);
        }
        catch(EndResponseException $e)
        {
            ob_end_clean();
            return array('result' => true, 'response' => $e->getContent());
        }
        catch(Throwable $e)
        {
            ob_end_clean();
            return array('error' => $e->getMessage());
        }
    }

    /**
     * Test responseAfterDelete method.
     *
     * @param  object $bug
     * @param  string $from
     * @param  string $message
     * @access public
     * @return array
     */
    public function responseAfterDeleteTest(object $bug, string $from = '', string $message = ''): array
    {
        try
        {
            ob_start();
            $result = $this->invokeArgs('responseAfterDelete', [$bug, $from, $message]);
            $output = ob_get_clean();
            if(dao::isError()) return array('error' => dao::getError());
            return array('result' => $result, 'output' => $output);
        }
        catch(EndResponseException $e)
        {
            ob_end_clean();
            return array('result' => true, 'response' => $e->getContent());
        }
        catch(Throwable $e)
        {
            ob_end_clean();
            return array('error' => $e->getMessage());
        }
    }

    /**
     * Test responseInModal method.
     *
     * @param  string $message
     * @param  bool   $isInKanban
     * @param  string $tab
     * @access public
     * @return array
     */
    public function responseInModalTest(string $message = '', bool $isInKanban = false, string $tab = 'execution'): array
    {
        $instance = $this->getInstance($this->moduleName, $this->className);
        $instance->app->tab = $tab;
        $instance->viewType = 'json';

        try
        {
            ob_start();
            $result = $this->invokeArgs('responseInModal', [$message, $isInKanban]);
            $output = ob_get_clean();
            if(dao::isError()) return array('error' => dao::getError());
            return array('result' => $result, 'output' => $output);
        }
        catch(EndResponseException $e)
        {
            ob_end_clean();
            $responseData = json_decode($e->getContent());
            return (array)$responseData;
        }
        catch(Throwable $e)
        {
            ob_end_clean();
            return array('error' => $e->getMessage());
        }
    }

    /**
     * Test updateBug method.
     *
     * @param  object $bug
     * @param  array  $fields
     * @access public
     * @return object|false
     */
    public function updateBugTest(object $bug, array $fields): object|false
    {
        $result = $this->invokeArgs('updateBug', [$bug, $fields]);
        if(dao::isError()) return false;
        return $result;
    }

    /**
     * Test updateKanbanAfterCreate method.
     *
     * @param  object $bug
     * @param  int    $laneID
     * @param  int    $columnID
     * @param  string $from
     * @access public
     * @return bool
     */
    public function updateKanbanAfterCreateTest(object $bug, int $laneID, int $columnID, string $from = ''): bool
    {
        $this->invokeArgs('updateKanbanAfterCreate', [$bug, $laneID, $columnID, $from]);
        if(dao::isError()) return false;
        return true;
    }

    /**
     * Test updateTodoAfterCreate method.
     *
     * @param  int $bugID
     * @param  int $todoID
     * @access public
     * @return bool
     */
    public function updateTodoAfterCreateTest(int $bugID, int $todoID): bool
    {
        $result = $this->invokeArgs('updateTodoAfterCreate', [$bugID, $todoID]);
        if(dao::isError()) return false;
        return $result;
    }
}
