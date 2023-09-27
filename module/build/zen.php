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
}
