<?php
declare(strict_types=1);
class projectreleaseZen extends projectrelease
{
    /**
     * Common actions.
     *
     * @param  int    $projectID
     * @param  int    $productID
     * @param  int    $branch
     * @access public
     * @return void
     */
    public function commonAction($projectID = 0, $productID = 0, $branch = 0)
    {
        /* Get product and product list by project. */
        $this->products = $this->product->getProductPairsByProject($projectID);
        if(empty($this->products)) return print($this->locate($this->createLink('product', 'showErrorNone', 'moduleName=project&activeMenu=projectrelease&projectID=' . $projectID)));
        if(!$productID) $productID = key($this->products);
        $product = $this->product->getById($productID);

        $this->view->products = $this->products;
        $this->view->product  = $product;
        $this->view->branches = (isset($product->type) and $product->type == 'normal') ? array() : $this->loadModel('branch')->getPairs($productID, 'active', $projectID);
        $this->view->branch   = $branch;
        $this->view->project  = $this->project->getByID($projectID);
    }
}
