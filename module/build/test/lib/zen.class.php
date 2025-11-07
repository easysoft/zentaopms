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
}
