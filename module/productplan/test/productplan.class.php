<?php
class productPlan
{
    public function __construct($user = 'admin')
    {
        global $tester;
        su($user);
        $this->productplan = $tester->loadModel('productplan');
    }

    /**
     * Get by ID plan
     *
     * @param  int  $planID
     * @param  bool $setImgSize
     * @access public
     * @return array
     */
    public function getByIDPlan($planID, $setImgSize = false)
    {
        $productplans = $this->productplan->getByID($planID, $setImgSize);
        if(dao::isError()) return dao::getError();
        return $productplans;
    }

    /**
     * Get by ID list
     *
     * @param  array  $planIDList
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
     * @param  int $productID
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
     * @param int    $product
     * @param int    $branch
     * @param string $browseType
     * @param object $pager
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
     * @param int    $product
     * @param string $branch
     * @param string $expired
     * @param bool   $skipParent
     * @access public
     * @return array
     */
    public function getPairs($product, $branch = '', $expired = '', $skipParent = false)
    {
        $productplans = $this->productplan->getPairs($product, $branch, $expired, $skipParent);
        if(dao::isError()) return dao::getError();
        return $productplans;
    }

    /**
     * Get for products
     *
     * @param  array  $products
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
     * @param  array  $products
     * @param  string $param
     * @param  string $field
     * @param  string $orderBy
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
     * @param  int $planID
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
     * @param  array  $storyIdList
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
     * @param  int $productID
     * @param  int $branches
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
     * 创建一个计划。
     * Create a productplan.
     *
     * @param  object $param
     * @param  bool   $isFuture
     * @access public
     * @return object|array
     */
    public function createTest(object $postData, bool $isFuture = false): object|array
    {
        $this->productplan->config->productplan->create->requiredFields = 'title,begin,end';
        $planID = $this->productplan->create($postData, $isFuture);

        if(dao::isError()) return dao::getError();
        return $this->productplan->getByID($planID);
    }

    /**
     * Update
     *
     * @param  int   $planID
     * @param  array $values
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
     * @param  int    $planID
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
     * @param  int $parentID
     * @access public
     * @return bool
     */
    public function updateParentStatus($parentID)
    {
        $productplans = $this->productplan->updateParentStatus($parentID);
        if(dao::isError()) return dao::getError();
        return $productplans;
    }

    /**
     * Batch update
     *
     * @param  int    $productID
     * @access public
     * @return array(array)
     */
    public function batchUpdate($productID, $bratch)
    {
        $batch = array('id' => array(), 'title' => array(), 'begin' => array(), 'end' => array());

        foreach($batch as $field => $defaultvalue) $_POST[$field] = $defaultvalue;
        foreach($bratch as $key => $value) $_POST[$key] = $value;

        $productplans = $this->productplan->batchUpdate($productID);
        if(dao::isError()) return dao::getError();
        return $productplans;
    }

    /**
     * Batch change status
     *
     * @param  string $status
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
     * @param  int    $planID
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
     * @param  int    $planID
     * @param  array  $storyID
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
     * @param  int   $storyID
     * @param  int   $planID
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
     * @param  int   $planID
     * @param  array $bugID
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
     * @param  int   $bugID
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
     * @param  int   $projectID
     * @param  array $newPlans
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
     * @param  array  $plans
     * @access public
     * @return array
     */
    public function reorder4ChildrenTest(array $plans): array
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
     * 测试 relationBranch.
     * Test relationBranch.
     *
     * @param  array  $plans
     * @access public
     * @return array
     */
    public function relationBranchTest(array $plans): array
    {
        $plan = $this->productplan->dao->select('*')->from(TABLE_PRODUCTPLAN)
            ->where('id')->in($plans)
            ->andWhere('deleted')->eq(0)
            ->fetchAll('id');

        $productplans = $this->productplan->relationBranch($plan);

        if(dao::isError()) return dao::getError();

        return $productplans;
    }

    /**
     * Is clickable
     *
     * @param  int    $planID
     * @param  string $action
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

    /**
     * Test build search form.
     *
     * @param  int    $queryID
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function buildSearchFormTest($queryID, $productID)
    {
        $product = $this->productplan->loadModel('product')->getByID($productID);
        $this->productplan->buildSearchForm($queryID, 'searchUrl', $product);

        return $_SESSION['productplansearchParams']['queryID'];
    }

    /**
     * 将父计划下的需求和Bug转移到子计划下。
     * Transfer stories and bugs to new plan.
     *
     * @param  int    $planID
     * @access public
     * @return int
     */
    public function transferStoriesAndBugsTest(int $planID): int
    {
        $plan = $this->productplan->getByID($planID);
        $this->productplan->transferStoriesAndBugs($plan);

        return $this->productplan->dao->select('plan')->from(TABLE_BUG)->where('id')->eq(1)->fetch('plan');
    }
}
?>
