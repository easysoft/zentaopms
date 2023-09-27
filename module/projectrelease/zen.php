<?php
declare(strict_types=1);
class projectreleaseZen extends projectrelease
{
    /**
     * 获取当前项目的所有产品，当前产品，分支，项目
     * Get products of the project and current product, branch, project.
     *
     * @param  int    $projectID
     * @param  int    $productID
     * @param  int    $branch
     * @access public
     * @return void
     */
    public function commonAction(int $projectID = 0, int $productID = 0, int $branch = 0)
    {
        /* 获取当前项目的所有产品。*/
        /* Get product list by project. */
        $this->products = $this->product->getProductPairsByProject($projectID);
        if(empty($this->products)) return print($this->locate($this->createLink('product', 'showErrorNone', 'moduleName=project&activeMenu=projectrelease&projectID=' . $projectID)));

        /* 获取当前的产品。*/
        /*  Get current product. */
        if(!$productID) $productID = key($this->products);
        $product = $this->product->getByID($productID);

        $this->view->products = $this->products;
        $this->view->product  = $product;
        $this->view->branches = (isset($product->type) and $product->type == 'normal') ? array() : $this->loadModel('branch')->getPairs($productID, 'active', $projectID);
        $this->view->branch   = $branch;
        $this->view->project  = $this->project->getByID($projectID);
    }
}
