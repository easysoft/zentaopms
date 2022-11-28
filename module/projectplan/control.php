<?php
class projectplan extends control
{
    /**
     * Browse plans.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  string $browseType
     * @param  int    $queryID
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse($productID = 0, $branch = '', $browseType = 'undone', $queryID = 0, $orderBy = 'begin_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1 )
    {
        echo $this->fetch('productplan', 'browse', "productID=$productID&branch=$branch&browseType=$browseType&queryID=$queryID&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
    }

    /**
     * Create a plan.
     *
     * @param string $productID
     * @param int    $branchID
     * @param int    $parent
     *
     * @access public
     * @return void
     */
    public function create($productID = '', $branchID = 0, $parent = 0)
    {
        echo $this->fetch('productplan', 'create', "productID=$productID&branchID=$branchID&parent=$parent");
    }

    /**
     * Edit a plan.
     *
     * @param int $planID
     *
     * @access public
     * @return void
     */
    public function edit($planID)
    {
        echo $this->fetch('productplan', 'edit', "planID=$planID");
    }

    /**
     * View plan.
     *
     * @param  int    $planID
     * @param  string $type
     * @param  string $orderBy
     * @param  string $link
     * @param  string $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     *
     * @access public
     * @return void
     */
    public function view($planID = 0, $type = 'story', $orderBy = 'order_desc', $link = 'false', $param = '', $recTotal = 0, $recPerPage = 100, $pageID = 1)
    {
        echo $this->fetch('productplan', 'view', "planID=$planID&type=$type&orderBy=$orderBy&link=$link&param=$param&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
    }
}
