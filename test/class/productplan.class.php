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
     * Get by ID plan
     *
     * @param  int  mixed $planID
     * @param  bool mixed $setImgSize
     * @access public
     * @return array
     */
    public function getByIDPlan($planID, $setImgSize = false)
    {
        $productplans = $this->productplan->getByID($planID, $setImgSize = false);
        if(dao::isError()) return dao::getError();
        return $productplans;
    }

    /**
     * Get by ID list
     *
     * @param  array(int)  mixed $planIDList
     * @access public
     * @return array
     */
    public function getByIDList($planIDList)
    {
        $productplans = $this->productplan->getByIDList($planIDList);
        if(dao::isError()) return dao::getError();
        return $productplans;
    }

    /**
     * Get last
     *
     * @param  int mixed $productID
     * @param  int $branch
     * @param  int $parent
     * @access public
     * @return array
     */
    public function getLast($productID, $branch = 0, $parent = 0)
    {
        $productplans = $this->productplan->getLast($productID, $parent);
        if(dao::isError()) return dao::getError();
        return $productplans;
    }

    /**
     * Get list
     *
     * @param int $product
     * @param int $branch
     * @param string $browseType
     * @param mixed $pager
     * @param string $orderBy
     * @param string $param
     * @access public
     * @return array
     */
    public function getList($product = 0, $branch = 0, $browseType = 'undone', $pager = null, $orderBy = 'begin_desc', $param = '')
    {
        $productplans = $this->productplan->getList($product, $branch, $browseType, $pager, $orderBy, $param);
        if(dao::isError()) return dao::getError();
        return $productplans;
    }

    /**
     * Get pairs
     *
     * @param int $product
     * @param string $branch
     * @param string $expired
     * @param bool mixed $skipParent
     * @access public
     * @return array
     */
    public function getPairs($product, $branch = '', $expired = '', $skipParent = false)
    {
        $productplans = $this->productplan->getPairs($product, $branch = '', $expired = '', $skipParent = false);
        if(dao::isError()) return dao::getError();
        return $productplans;
    }

    /**
     * Get pairs for story
     *
     * @param mrray|int mixed $product
     * @param int       mixed $branch
     * @param string    mixed $param
     * @access public
     * @return count
     */
    public function getPairsForStory($product, $branch, $param)
    {
        $productplans = $this->productplan->getPairsForStory($product, $branch, $param);
        if(dao::isError()) return dao::getError();
        return count($productplans) - 1;
    }

    /**
     * Get for products
     *
     * @param  array(int) mixed $products
     * @access public
     * @return count
     */
    public function getForProducts($products)
    {
        $productplans = $this->productplan->getForProducts($products);
        if(dao::isError()) return dao::getError();
        return $productplans;
    }

    /**
     * Get group by product
     *
     * @param  array(int) mixed $products
     * @param  string     mixed $param
     * @param  string     $field
     * @param  string     $orderBy
     * @access public
     * @return count
     */
    public function getGroupByProduct($products, $param, $field = 'name', $orderBy = 'id_desc')
    {
        $productplans = $this->productplan->getGroupByProduct($products, $param, $field, $orderBy);
        if(dao::isError()) return dao::getError();
        return count($productplans);
    }

    /**
     * Get children
     *
     * @param  int mixed $planID
     * @access public
     * @return count
     */
    public function getChildren($planID)
    {
        $productplans = $this->productplan->getChildren($planID);
        if(dao::isError()) return dao::getError();
        return count($productplans);
    }

    /**
     * Get plans by stories
     *
     * @param  array(int) mixed $storyIdList
     * @access public
     * @return count
     */
    public function getPlansByStories($storyIdList)
    {
        $productplans = $this->productplan->getPlansByStories($storyIdList);
        if(dao::isError()) return dao::getError();
        return count($productplans);
    }

    /**
     * Get branch plan pairs
     *
     * @param  int mixed $productID
     * @param  int mixed $branches
     * @access public
     * @return array
     */
    public function getBranchPlanPairs($productID, $branches)
    {
        $productplans = $this->productplan->getBranchPlanPairs($productID, $branches);
        if(dao::isError()) return dao::getError();
        return $productplans;
    }

    /**
     * Get top plan pairs
     *
     * @param  int  $productID
     * @param  int  $branch
     * @param  int  $exclude
     * @access public
     * @return array
     */
    public function getTopPlanPairsTest($param)
    {
        $productID = $param['productID'];
        $branch    = $param['branch'];
        $exclude   = $param['exclude'];

        $planPairs = $this->productplan->getTopPlanPairs($productID, $branch, $exclude);

        if(dao::isError()) return dao::getError();

        return $planPairs;
    }

    /**
     * Create
     *
     * @param  array mixed $param
     * @access public
     * @return array
     */
    public function create($param)
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
        if(dao::isError()) return dao::getError();
        return $productPlans;
    }

    /**
     * Update
     *
     * @param  int   mixed $planID
     * @param  array mixed $values
     * @access public
     * @return array
     */
    public function update($planID, $values)
    {
        $updatePlan = array('title' => '', 'status' => '', 'begin' => '', 'end' => '', 'desc' => '', 'uid' => '', 'product' => '');

        foreach($updatePlan as $field => $defaultvalue) $_POST[$field] = $defaultvalue;
        foreach($values as $key =>$value) $_POST[$key] = $value;

        $productplans = $this->productplan->update($planID);
        if(dao::isError()) return dao::getError();
        return $productplans;
    }

    /**
     * Update status
     *
     * @param  int    mixed $planID
     * @param  string $status
     * @param  string $action
     * @access public
     * @return array
     */
    public function updateStatus($planID, $status = '', $action = '')
    {
        $productplans = $this->productplan->updateStatus($planID, $status, $action);
        if(dao::isError()) return dao::getError();
        return $productplans;
    }

    /**
     * Update parent status
     *
     * @param  int   mixed $parentId
     * @access public
     * @return bool
     */
    public function updateParentStatus($parentId)
    {
        $productplans = $this->productplan->updateParentStatus($parentId);
        if(dao::isError()) return dao::getError();
        return $productplans;
    }

    /**
     * Batch update
     *
     * @param  int   mixed $productId
     * @access public
     * @return array(array)
     */
    public function batchUpdate($productId, $bratch)
    {
        $batch = array('id' => array(), 'title' => array(), 'begin' => array(), 'end' => array());

        foreach($batch as $field => $defaultvalue) $_POST[$field] = $defaultvalue;
        foreach($bratch as $key => $value) $_POST[$key] = $value;

        $productplans = $this->productplan->batchUpdate($productId);
        if(dao::isError()) return dao::getError();
        return $productplans;
    }

    /**
     * Batch change status
     *
     * @param  string mixed $status
     * @access public
     * @return array
     */
    public function batchChangeStatus($status, $planIDList = array('planIDList' => array(4, 5)))
    {
        $planID = $planIDList;

        foreach($planID as $field => $defaultvalue) $_POST[$field] = $defaultvalue;

        $productplans = $this->productplan->batchChangeStatus($status);
        if(dao::isError()) return dao::getError();
        return $productplans;
    }

    /**
     * Change parent field
     *
     * @param  int    mixed $planID
     * @access public
     * @return true
     */
    public function changeParentField($planID)
    {
        $productplans = $this->productplan->changeParentField($planID);
        if(dao::isError()) return dao::getError();
        return $productplans;
    }

    /**
     * Link story
     *
     * @param  int   mixed $planID
     * @param  array(stories => array())mixed $storyID
     * @access public
     * @return void
     */
    public function linkStory($planID, $storyID)
    {

        foreach($storyID as $key => $value) $_POST[$key] = $value;

        $productplans = $this->productplan->linkStory($planID);

        if(dao::isError()) return dao::getError();
        return $productplans;
    }

    /**
     * Unlink story
     *
     * @param  int   mixed $storyID
     * @param  int   mixed $planID
     * @access public
     * @return void
     */
    public function unlinkStory($storyID, $planID)
    {
        $productPlans = $this->productplan->unlinkStory($storyID, $planID);

        if(dao::isError()) return dao::getError();

        return $productPlans;
    }

    /**
     * Link bug
     *
     * @param  int   mixed $planID
     * @param  array mixed $bugID
     * @access public
     * @return void
     */
    public function linkBug($planID, $bugID)
    {

        foreach($bugID as $key => $value) $_POST[$key] = $value;

        $productPlans = $this->productplan->linkBug($planID);

        if(dao::isError()) return dao::getError();

        return $productPlans;
    }

    /**
     * Unlink bug
     *
     * @param  int   mixed $bugID
     * @access public
     * @return void
     */
    public function unlinkBug($bugID)
    {
        $productplans = $this->productplan->unlinkBug($bugID);

        if(dao::isError()) return dao::getError();

        return $productplans;
    }

    /**
     * Link project
     *
     * @param  $projectID mixed $projectID
     * @param  $newPlans  mixed $newPlans
     * @access public
     * @return void
     */
    public function linkProject($projectID, $newPlans)
    {
        $productplans = $this->productplan->linkProject($projectID, $newPlans);

        if(dao::isError()) return dao::getError();

        return $productplans;
    }

    /**
     * Reorder 4 children
     *
     * @param  array  mixed $plans
     * @access public
     * @return array
     */
    public function reorder4Children($plans)
    {
        $plan = $this->productplan->dao->select('*')->from(TABLE_PRODUCTPLAN)
            ->where('id')->in($plans)
            ->andWhere('deleted')->eq(0)
            ->fetchAll('id');

        $productplans = $this->productplan->reorder4Children($plan);

        if(dao::isError()) return dao::getError();

        return $productplans;
    }

    /**
     * Is clickable
     *
     * @param  int    mixed $planID
     * @param  string mixed $action
     * @access public
     * @return void
     */
    public function isClickable($planID, $action)
    {
        $plan = $this->productplan->dao->select('*')->from(TABLE_PRODUCTPLAN)
            ->where('id')->eq($planID)
            ->andWhere('deleted')->eq(0)
            ->fetchAll('id');

        $plans = $plan[$planID];
        $productplans = $this->productplan->isClickable($plans, $action);

        if(dao::isError()) return dao::getError();

        return $productplans;
    }

}
?>
