<?php
class Productplan
{
    public function __construct($user)
    {
        global $tester;
        su($user);
        $this->productplan = $tester->loadModel('productplan');
    }

    /**
     * getByIDPlan
     *
     * @param  int  mixed $planID
     * @param  bool mixed $setImgSize
     * @access public
     * @return void
     */
    public function getByIDPlan($planID, $setImgSize = false)
    {
        $productplans = $this->productplan->getByID($planID, $setImgSize = false);
        return $productplans;
    }

    /**
     * getByIDList
     *
     * @param  array(int)  mixed $planIDList
     * @access public
     * @return void
     */
    public function getByIDList($planIDList)
    {
        $productplans = $this->productplan->getByIDList($planIDList);
        return $productplans;
    }

    /**
     * getLast
     *
     * @param  int mixed $productID
     * @param  int $branch
     * @param  int $parent
     * @access public
     * @return void
     */
    public function getLast($productID, $branch = 0, $parent = 0)
    {
        $productplans = $this->productplan->getLast($productID, $branch = 0, $parent = 0);
        return $productplans;
    }

    /**
     * getList
     *
     * @param int $product
     * @param int $branch
     * @param string $browseType
     * @param mixed $pager
     * @param string $orderBy
     * @param string $param
     * @access public
     * @return void
     */
    public function getList($product = 0, $branch = 0, $browseType = 'doing', $pager = null, $orderBy = 'begin_desc', $param = '')
    {
        $productplans = $this->productplan->getList($product = 0, $branch = 0, $browseType = 'doing', $pager = null, $orderBy = 'begin_desc', $param = '');
        return $productplans;
    }

    public function getPairs($product = 0, $branch = '', $expired = '', $skipParent = false)
    {
        $productplans = $this->productplan->getPairs($product = 0, $branch = '', $expired = '', $skipParent = false);
        return $productplans;
    }

}

?>
