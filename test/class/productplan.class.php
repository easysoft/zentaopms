<?php
class productPlan
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
    public function getList($product, $branch, $browseType, $pager, $orderBy, $param)
    {
        $productplans = $this->productplan->getList($product, $branch, $browseType, $pager, $orderBy, $param);
        return $productplans;
    }

    /**
     * getPairs
     *
     * @param int $product
     * @param string $branch
     * @param string $expired
     * @param mixed $skipParent
     * @access public
     * @return void
     */
    public function getPairs($product, $branch = '', $expired = '', $skipParent = false)
    {
        $productplans = $this->productplan->getPairs($product, $branch = '', $expired = '', $skipParent = false);
        return $productplans;
    }

    /**
     * getPairsForStory
     *
     * @param mrray|int mixed $product
     * @param int       mixed $branch
     * @param string    mixed $param
     * @access public
     * @return void
     */
    public function getPairsForStory($product, $branch, $param)
    {
        $productplans = $this->productplan->getPairsForStory($product, $branch, $param);
        return count($productplans) - 1;
    }

    /**
     * getForProducts
     *
     * @param  array(int) mixed $products
     * @access public
     * @return void
     */
    public function getForProducts($products)
    {
        $productplans = $this->productplan->getForProducts($products);
        return count($productplans) - 1;
    }

    /**
     * getGroupByProduct
     *
     * @param  array(int) mixed $products
     * @param  string     mixed $param
     * @param  string     $field
     * @param  string     $orderBy
     * @access public
     * @return void
     */
    public function getGroupByProduct($products, $param, $field = 'name', $orderBy = 'id_desc')
    {
        $productplans = $this->productplan->getGroupByProduct($products, $param, $field, $orderBy);
        return count($productplans);
    }

    /**
     * getChildren
     *
     * @param  int mixed $planID
     * @access public
     * @return void
     */
    public function getChildren($planID)
    {
        $productplans = $this->productplan->getChildren($planID);
        return count($productplans);
    }

    /**
     * getPlansByStories
     *
     * @param  array(int) mixed $storyIdList
     * @access public
     * @return void
     */
    public function getPlansByStories($storyIdList)
    {
        $productplans = $this->productplan->getPlansByStories($storyIdList);
        return count($productplans);
    }

    /**
     * getBranchPlanPairs
     *
     * @param  int mixed $productID
     * @param  int mixed $branches
     * @access public
     * @return void
     */
    public function getBranchPlanPairs($productID, $branches)
    {
        $productplans = $this->productplan->getBranchPlanPairs($productID, $branches);
        return $productplans;
    }

    /**
     * @param  array
     */
    public function create($param) //声明一个create方法
    {
        //初始化传的参数，这里可以设置默认值
        $createPlan = array('title' => '', 'begin' => '', 'end' => '', 'delta' => '', 'desc' => '', 'uid' => '', 'product' => '', 'parent' => '');
        //设置foreach循环，将初始化参数传给全局变量$_POST中，通过此方式将传的参数提供给源码create方法
        foreach($createPlan as $field => $defaultvalue) $_POST[$field] = $defaultvalue;
        //二次遍历数组将此方法的形参传入$_POST中，这行可以替换掉初始化参数，做到以传入参数为最终结果的目的。同时不传参数的话取默认值
        foreach($param as $key => $value) $_POST[$key] = $value;
        //这行代码的作用是调用源码中的方法(参数通过$_POST直接取，具体原理可以不知道)
        $productPlans = $this->productplan->create();
        //将方法返回内容返回
        return $productPlans;
    }

    /**
     * update
     *
     * @param  int   mixed $planID
     * @param  array mixed $values
     * @access public
     * @return void
     */
    public function update($planID, $values)
    {
        $updatePlan = array('title' => '', 'status' => '', 'begin' => '', 'end' => '', 'desc' => '', 'uid' => '', 'product' => '');

        foreach($updatePlan as $field => $defaultvalue) $_POST[$field] = $defaultvalue;
        foreach($values as $key =>$value) $_POST[$key] = $value;

        $productplans = $this->productplan->update($planID);

        return $productplans;
    }

    /**
     * updateStatus
     *
     * @param  int    mixed $planID
     * @param  string $status
     * @param  string $action
     * @access public
     * @return void
     */
    public function updateStatus($planID, $status = '', $action = '')
    {
        $productplans = $this->productplan->updateStatus($planID, $status, $action);
        return $productplans;
    }

    /**
     * updateParentStatus
     *
     * @param  int   mixed $parentId
     * @access public
     * @return void
     */
    public function updateParentStatus($parentId)
    {
        $productplans = $this->productplan->updateParentStatus($parentId);
        return $productplans;
    }

    /**
     * batchUpdate
     *
     * @param  int   mixed $productId
     * @access public
     * @return void
     */
    public function batchUpdate($productId, $bratch)
    {
        $batch = array('id' => array(), 'title' => array(), 'begin' => array(), 'end' => array());

        foreach($batch as $field => $defaultvalue) $_POST[$field] = $defaultvalue;
        foreach($bratch as $key => $value) $_POST[$key] = $value;

        $productplans = $this->productplan->batchUpdate($productId);
        return $productplans;
    }

    public function batchChangeStatus($status)
    {
        $posts = array(6, 5, 4);

        foreach($posts as $field => $defaultvalue) $_POST[$field] = $defaultvalue;
        //foreach($param as $key => $value) $_POST[$key] = $valuep;

        $productplans = $this->productplan->batchChangeStatus($status);
        return $productplans;
    }

}
?>
