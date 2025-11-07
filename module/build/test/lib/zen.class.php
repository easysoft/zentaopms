<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class buildZenTest extends baseTest
{
    protected $moduleName = 'build';
    protected $className  = 'zen';

    /**
     * Test assignCreateData method.
     *
     * @param  int       $productID
     * @param  int       $executionID
     * @param  int       $projectID
     * @param  string    $status
     * @access public
     * @return array
     */
    public function assignCreateDataTest($productID = 0, $executionID = 0, $projectID = 0, $status = '')
    {
        global $tester;
        $build = $tester->loadModel('build');
        $execution = $tester->loadModel('execution');
        $product = $tester->loadModel('product');
        $project = $tester->loadModel('project');

        $productGroups = $branchGroups = array();
        $noClosedParam = (isset($build->config->CRExecution) && $build->config->CRExecution == 0) ? '|noclosed' : '';
        $executions = $execution->getPairs($projectID, 'all', 'stagefilter|leaf|order_asc' . $noClosedParam);
        $executionID = empty($executionID) && !empty($executions) ? (int)key($executions) : $executionID;

        if($executionID || $projectID) $productGroups = $product->getProducts($executionID ? $executionID : $projectID, $status);
        if($executionID || $projectID) $branchGroups = $project->getBranchesByProject($executionID ? $executionID : $projectID);

        $productID = $productID ? $productID : key($productGroups);
        $branches = $products = array();

        if(!empty($productGroups[$productID]) && $productGroups[$productID]->type != 'normal' && !empty($branchGroups[$productID]))
        {
            $branchPairs = $tester->loadModel('branch')->getPairs($productID, 'active');
            foreach($branchGroups[$productID] as $branchID => $branch) if(isset($branchPairs[$branchID])) $branches[$branchID] = $branchPairs[$branchID];
        }

        foreach($productGroups as $prod) $products[$prod->id] = $prod->name;

        if(dao::isError()) return dao::getError();

        return array(
            'productID' => $productID, 'executionID' => $executionID, 'products' => $products,
            'executions' => $executions, 'branches' => $branches,
            'users' => $tester->loadModel('user')->getPairs('nodeleted|noclosed'),
            'product' => isset($productGroups[$productID]) ? $productGroups[$productID] : '',
            'project' => $project->getByID($projectID)
        );
    }

    /**
     * Test assignBugVarsForView method.
     *
     * @param  object    $build
     * @param  string    $type
     * @param  string    $sort
     * @param  string    $param
     * @param  object    $bugPager
     * @param  object    $generatedBugPager
     * @access public
     * @return array
     */
    public function assignBugVarsForViewTest($build = null, $type = '', $sort = '', $param = '', $bugPager = null, $generatedBugPager = null)
    {
        if($bugPager === null)
        {
            $bugPager = new stdclass();
            $bugPager->recTotal  = 0;
            $bugPager->pageTotal = 1;
        }

        if($generatedBugPager === null)
        {
            $generatedBugPager = new stdclass();
            $generatedBugPager->recTotal  = 0;
            $generatedBugPager->pageTotal = 1;
        }

        $this->invokeArgs('assignBugVarsForView', [$build, $type, $sort, $param, $bugPager, $generatedBugPager]);

        if(dao::isError()) return dao::getError();

        return array(
            'type'              => $this->instance->view->type ?? '',
            'param'             => $this->instance->view->param ?? '',
            'bugs'              => $this->instance->view->bugs ?? array(),
            'generatedBugs'     => $this->instance->view->generatedBugs ?? array(),
            'hasBugPager'       => isset($this->instance->view->bugPager),
            'hasGeneratedPager' => isset($this->instance->view->generatedBugPager)
        );
    }

    /**
     * Test assignEditData method.
     *
     * @param  object    $build
     * @access public
     * @return array
     */
    public function assignEditDataTest($build = null)
    {
        global $tester;
        if($build === null) return array();

        $builds    = array();
        $status    = empty($this->instance->config->CRProduct) ? 'noclosed' : '';
        $projectID = $build->execution ? (int)$build->execution : (int)$build->project;
        $productGroups = $tester->loadModel('product')->getProducts($projectID, $status);
        $branches  = $tester->loadModel('branch')->getList($build->product, $projectID, 'all');
        if(!$build->execution) $builds = $tester->loadModel('build')->getBuildPairs(array($build->product), 'all', 'noempty,notrunk,singled,separate', $build->project, 'project', $build->builds, false, $build->system);

        $executions = $tester->loadModel('product')->getExecutionPairsByProduct($build->product, $build->branch, (int)$tester->session->project, 'stagefilter');
        $execution  = $build->execution ? $tester->loadModel('execution')->getByID((int)$build->execution) : '';
        if($build->execution && !isset($executions[$build->execution]))
        {
            $execution = $tester->loadModel('execution')->getByID($build->execution);
            $executions[$build->execution] = $execution ? $execution->name : '';
        }
        if($build->product && !isset($productGroups[$build->product]))
        {
            $product = $tester->loadModel('product')->getById($build->product);
            $product->branch = $build->branch;
            $productGroups[$build->product] = $product;
        }

        $branchTagOption = array();
        foreach($branches as $branchInfo) $branchTagOption[$branchInfo->id] = $branchInfo->name . ($branchInfo->status == 'closed' ? ' (已关闭)' : '');
        foreach(explode(',', $build->branch) as $buildBranch) if(!isset($branchTagOption[$buildBranch])) $branchTagOption[$buildBranch] = $tester->loadModel('branch')->getById($buildBranch, 0, 'name');

        $products = array();
        foreach($productGroups as $product) $products[$product->id] = $product->name;
        if(dao::isError()) return dao::getError();

        return array('products' => $products, 'product' => isset($productGroups[$build->product]) ? $productGroups[$build->product] : '', 'branchTagOption' => $branchTagOption, 'builds' => $builds, 'executions' => $executions, 'executionType' => !empty($execution) && $execution->type == 'stage' ? 1 : 0);
    }

    /**
     * Test assignProductVarsForView method.
     *
     * @param  object    $build
     * @param  string    $type
     * @param  string    $sort
     * @param  object    $storyPager
     * @access public
     * @return array
     */
    public function assignProductVarsForViewTest($build = null, $type = '', $sort = '', $storyPager = null)
    {
        if($storyPager === null)
        {
            $storyPager = new stdclass();
            $storyPager->recTotal  = 0;
            $storyPager->pageTotal = 1;
        }

        $this->invokeArgs('assignProductVarsForView', [$build, $type, $sort, $storyPager]);

        if(dao::isError()) return dao::getError();

        return array(
            'branchName'   => $this->instance->view->branchName ?? '',
            'stories'      => $this->instance->view->stories ?? array(),
            'hasStoryPager' => isset($this->instance->view->storyPager),
            'storyCount'   => count($this->instance->view->stories ?? array())
        );
    }

    /**
     * Test buildBuildForCreate method.
     *
     * @access public
     * @return object|array
     */
    public function buildBuildForCreateTest()
    {
        $result = $this->invokeArgs('buildBuildForCreate', []);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test buildBuildForEdit method.
     *
     * @param  int    $buildID
     * @access public
     * @return object|array
     */
    public function buildBuildForEditTest($buildID = 0)
    {
        $result = $this->invokeArgs('buildBuildForEdit', [$buildID]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test buildLinkBugSearchForm method.
     *
     * @param  object    $build
     * @param  int       $queryID
     * @param  string    $productType
     * @access public
     * @return array
     */
    public function buildLinkBugSearchFormTest($build = null, $queryID = 0, $productType = 'normal')
    {
        global $tester;
        if($build === null) return array();

        $this->invokeArgs('buildLinkBugSearchForm', [$build, $queryID, $productType]);

        if(dao::isError()) return dao::getError();

        return array(
            'actionURL'       => $tester->config->bug->search['actionURL'] ?? '',
            'queryID'         => $tester->config->bug->search['queryID'] ?? 0,
            'style'           => $tester->config->bug->search['style'] ?? '',
            'hasPlanField'    => isset($tester->config->bug->search['fields']['plan']) ? 1 : 0,
            'hasBranchField'  => isset($tester->config->bug->search['fields']['branch']) ? 1 : 0,
            'hasProductField' => isset($tester->config->bug->search['fields']['product']) ? 1 : 0,
            'hasProjectField' => isset($tester->config->bug->search['fields']['project']) ? 1 : 0,
            'branchValues'    => $tester->config->bug->search['params']['branch']['values'] ?? array()
        );
    }

    /**
     * Test buildLinkStorySearchForm method.
     *
     * @param  object    $build
     * @param  int       $queryID
     * @param  string    $productType
     * @access public
     * @return array
     */
    public function buildLinkStorySearchFormTest($build = null, $queryID = 0, $productType = 'normal')
    {
        global $tester;
        if($build === null) return array();

        $this->invokeArgs('buildLinkStorySearchForm', [$build, $queryID, $productType]);

        if(dao::isError()) return dao::getError();

        return array(
            'actionURL'       => $tester->config->product->search['actionURL'] ?? '',
            'queryID'         => $tester->config->product->search['queryID'] ?? 0,
            'style'           => $tester->config->product->search['style'] ?? '',
            'hasPlanField'    => isset($tester->config->product->search['fields']['plan']) ? 1 : 0,
            'hasBranchField'  => isset($tester->config->product->search['fields']['branch']) ? 1 : 0,
            'hasProductField' => isset($tester->config->product->search['fields']['product']) ? 1 : 0,
            'hasProjectField' => isset($tester->config->product->search['fields']['project']) ? 1 : 0,
            'hasGradeField'   => isset($tester->config->product->search['fields']['grade']) ? 1 : 0,
            'branchValues'    => $tester->config->product->search['params']['branch']['values'] ?? array()
        );
    }

    /**
     * Test getExcludeStoryIdList method.
     *
     * @param  object    $build
     * @access public
     * @return int
     */
    public function getExcludeStoryIdListTest($build = null)
    {
        if($build === null) return 0;

        $result = $this->invokeArgs('getExcludeStoryIdList', [$build]);
        if(dao::isError()) return dao::getError();
        return count($result);
    }

    /**
     * Test setMenuForView method.
     *
     * @param  object    $build
     * @access public
     * @return array
     */
    public function setMenuForViewTest($build = null)
    {
        global $tester;
        if($build === null) return array();

        $tester->session->project = $build->project;

        $objectType = 'execution';
        $objectID   = $build->execution;
        if($tester->app->tab == 'project')
        {
            $objectType = 'project';
            $objectID   = $build->project;
        }

        $executions = $tester->loadModel('execution')->getPairs($tester->session->project, 'all', 'empty');
        $title      = "BUILD #$build->id $build->name" . (isset($executions[$build->execution]) ? " - " . $executions[$build->execution] : '');
        $buildPairs = $tester->loadModel('build')->getBuildPairs(0, 'all', 'noempty,notrunk', (int)$objectID, $objectType);
        $builds     = $tester->loadModel('build')->getByList(array_keys($buildPairs));

        if(dao::isError()) return dao::getError();

        return array(
            'title'           => $title,
            'executionsSet'   => 1,
            'buildPairsSet'   => 1,
            'buildsSet'       => 1,
            'objectIDSet'     => 1,
            'buildPairsCount' => count($buildPairs),
            'buildsCount'     => count($builds),
            'sessionProject'  => $tester->session->project ?? 0
        );
    }
}
