<?php
declare(strict_types=1);
/**
 * The zen file of build module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     build
 * @link        https://www.zentao.net
 */
class buildZen extends build
{
    /**
     * 生成创建计划的页面数据。
     * Generate the page data for creating a plan.
     *
     * @param  int       $productID
     * @param  int       $executionID
     * @param  int       $projectID
     * @param  string    $status
     * @access protected
     * @return void
     */
    protected function assignCreateData(int $productID, int $executionID, int $projectID, string $status)
    {
        $productGroups = $branchGroups = array();
        $executions    = $this->loadModel('execution')->getPairs($projectID, 'all', 'stagefilter|leaf|order_asc');
        $executionID   = empty($executionID) ? key($executions) : $executionID;
        if($executionID)
        {
            $productGroups = $this->loadModel('product')->getProducts($executionID, $status);
            $branchGroups  = $this->loadModel('project')->getBranchesByProject($executionID);
        }

        $this->commonActions($projectID);
        $productID = $productID ? $productID : key($productGroups);
        $branches  = $products = array();

        /* Set branches and products. */
        if(!empty($productGroups[$productID]) && $productGroups[$productID]->type != 'normal' && !empty($branchGroups[$productID]))
        {
            $branchPairs = $this->loadModel('branch')->getPairs($productID, 'active');
            foreach($branchGroups[$productID] as $branchID => $branch)
            {
                if(isset($branchPairs[$branchID])) $branches[$branchID] = $branchPairs[$branchID];
            }
        }

        $artifactRepos = array();
        if(!$this->view->hidden && $productGroups) $this->loadModel('artifactrepo');
        foreach($productGroups as $product)
        {
            $products[$product->id] = $product->name;
            if(!$this->view->hidden) $artifactRepos[$product->id] = $this->artifactrepo->getReposByProduct($product->id);
        }

        $this->view->title         = $this->lang->build->create;
        $this->view->users         = $this->loadModel('user')->getPairs('nodeleted|noclosed');
        $this->view->product       = isset($productGroups[$productID]) ? $productGroups[$productID] : '';
        $this->view->branches      = $branches;
        $this->view->products      = $products;
        $this->view->executionID   = $executionID;
        $this->view->executions    = $executions;
        $this->view->lastBuild     = $this->build->getLast($executionID, $projectID);
        $this->view->artifactRepos = $artifactRepos;
        $this->display();
    }

    /**
     * 生成编辑计划的页面数据。
     * Generate the page data for editing a plan.
     *
     * @param  object    $build
     * @access protected
     * @return void
     */
    protected function assignEditData(object $build)
    {
        $builds        = array();
        $status        = empty($this->config->CRProduct) ? 'noclosed' : '';
        $projectID     = $build->execution ? $build->execution : $build->project;
        $productGroups = $this->loadModel('product')->getProducts($projectID, $status);
        $branches      = $this->loadModel('branch')->getList($build->product, $projectID, 'all');
        if(!$build->execution) $builds = $this->build->getBuildPairs(array($build->product), 'all', 'noempty,notrunk,singled,separate', $build->project, 'project', $build->builds, false);

        /* Get execution info. */
        $executions = $this->product->getExecutionPairsByProduct($build->product, $build->branch, (int)$this->session->project, 'stagefilter');
        $execution  = $build->execution ? $this->loadModel('execution')->getByID($build->execution) : '';
        if($build->execution && !isset($executions[$build->execution]))
        {
            $execution = $this->loadModel('execution')->getByID($build->execution);
            $executions[$build->execution] = $execution ? $execution->name : '';
        }

        if(!isset($productGroups[$build->product]))
        {
            $product = $this->product->getById($build->product);
            $product->branch = $build->branch;
            $productGroups[$build->product] = $product;
        }

        /* Display status of branch. */
        $branchTagOption = array();
        foreach($branches as $branchInfo)
        {
            $branchTagOption[$branchInfo->id] = $branchInfo->name . ($branchInfo->status == 'closed' ? ' (' . $this->lang->branch->statusList['closed'] . ')' : '');
        }
        foreach(explode(',', $build->branch) as $buildBranch)
        {
            if(!isset($branchTagOption[$buildBranch])) $branchTagOption[$buildBranch] = $this->branch->getById($buildBranch, 0, 'name');
        }

        $products = array();
        foreach($productGroups as $product) $products[$product->id] = $product->name;

        $this->view->title           = $build->name . $this->lang->colon . $this->lang->build->edit;
        $this->view->products        = $products;
        $this->view->product         = isset($productGroups[$build->product]) ? $productGroups[$build->product] : '';
        $this->view->users           = $this->loadModel('user')->getPairs('noletter', $build->builder);
        $this->view->branchTagOption = $branchTagOption;
        $this->view->build           = $build;
        $this->view->testtask        = $this->loadModel('testtask')->getByBuild($build->id);
        $this->view->builds          = $builds;
        $this->view->executions      = $executions;
        $this->view->executionType   = !empty($execution) && $execution->type == 'stage' ? 1 : 0;
        $this->view->orderBy         = 'status_asc, stage_asc, id_desc';
        $this->display();
    }
}
