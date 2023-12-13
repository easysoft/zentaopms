<?php
class productPlan
{
    public function __construct($user = 'admin')
    {
        global $tester, $app;
        su($user);
        $app->rawModule  = 'productplan';
        $app->rawMethod  = 'browse';
        $app->moduleName = 'productplan';
        $app->methodName = 'browse';
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
     * Get group by product
     *
     * @param  array  $products
     * @param  string $param
     * @param  string $field
     * @param  string $orderBy
     * @access public
     * @return count
     */
    public function getGroupByProduct($products, $param, $orderBy = 'id_desc')
    {
        $productplans = $this->productplan->getGroupByProduct($products, $param, $orderBy);
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
        $productplans = $this->productplan->getBranchPlanPairs($productID, array($branches));
        if(dao::isError()) return dao::getError();
        return $productplans;
    }

    /**
     * 创建一个计划。
     * Create a productplan.
     *
     * @param  object $param
     * @param  int    $isFuture
     * @access public
     * @return object|array
     */
    public function createTest(object $postData, int $isFuture = 0): object|array
    {
        $this->productplan->config->productplan->create->requiredFields = 'title,begin,end';
        $postData->branch = 0;
        $planID = $this->productplan->create($postData, $isFuture);

        if(dao::isError()) return dao::getError();
        return $this->productplan->getByID($planID);
    }

    /**
     * 更新一个计划。
     * Update a plan.
     *
     * @param  int    $planID
     * @param  object $plan
     * @access public
     * @return array
     */
    public function updateTest(int $planID, object $plan): array
    {
        $oldPlan = $this->productplan->getByID($planID);
        $productplans = $this->productplan->update($plan, $oldPlan);
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
     * 更新父计划的状态。
     * Update a parent plan's status.
     *
     * @param  int    $parentID
     * @access public
     * @return bool
     */
    public function updateParentStatusTest(int $parentID): array
    {
        $this->productplan->app->rawMethod = 'create';
        $oldPlan = $this->productplan->getByID($parentID);

        $this->productplan->updateParentStatus($parentID);
        if(dao::isError()) return dao::getError();

        $newPlan = $this->productplan->getByID($parentID);
        return common::createChanges($oldPlan, $newPlan);
    }

    /**
     * Batch change status
     *
     * @param  string $status
     * @param  bool   $hasReson
     * @access public
     * @return array
     */
    public function batchChangeStatus($status, $hasReson = false)
    {
        $planIdList = array(3, 5);
        if($hasReson)
        {
            $planIdList = array(7, 8);
            $_POST['closedReason'] = array(
                7 => 'reason1',
                8 => 'reason2',
            );
        }
        $this->productplan->batchChangeStatus($planIdList, $status);
        if(dao::isError()) return dao::getError();

        return $this->productplan->dao->select('*')->from(TABLE_PRODUCTPLAN)->where('id')->in($planIdList)->fetchAll('id');
    }

    /**
     * 将父计划的parent改为-1, 没有子计划的父计划的parent改为0。
     * Change parent field by planID.
     *
     * @param  int          $planID
     * @access public
     * @return array|object
     */
    public function changeParentFieldTest(int $planID): array|object
    {
        $this->productplan->changeParentField($planID);

        if(dao::isError()) return dao::getError();
        return $this->productplan->getByID($planID);
    }

    /**
     * Link story
     *
     * @param  int    $planID
     * @param  array  $storyIdList
     * @access public
     * @return void
     */
    public function linkStory($planID, $storyIdList)
    {

        $productplans = $this->productplan->linkStory($planID, $storyIdList);

        if(dao::isError()) return dao::getError();
        return $this->productplan->dao->select('*')->from(TABLE_PLANSTORY)->where('plan')->eq($planID)->fetchAll('story');
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
     * 关联项目。
     * Link project.
     *
     * @param  int    $projectID
     * @param  array  $newPlans
     * @access public
     * @return array
     */
    public function linkProjectTest(int $projectID, array $newPlans): array
    {
        $this->productplan->dao->delete()->from(TABLE_PROJECTSTORY)->where('project')->eq($projectID)->exec();

        $this->productplan->linkProject($projectID, $newPlans);

        if(dao::isError()) return dao::getError();
        return $this->productplan->dao->select('*')->from(TABLE_PROJECTSTORY)->where('project')->eq($projectID)->fetchAll();
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
        $plan         = $this->productplan->getByID($planID);
        $productplans = $this->productplan->isClickable($plan, $action);

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

    /**
     * 检查更新计划的数据。
     * Check data for update.
     *
     * @param  int       $planID
     * @param  object    $plan
     * @access public
     * @return array|bool
     */
    public function checkDataForUpdateTest(int $planID, object $plan): array|bool
    {
        $oldPlan = $this->productplan->getByID($planID);
        $result  = $this->productplan->checkDataForUpdate($plan, $oldPlan);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * 测试更新产品计划的关联信息。
     * Test syncLinkedStories method.
     *
     * @param  int    $planID
     * @param  array  $storyIdList
     * @param  bool   $deleteOld
     * @access public
     * @return string|array
     */
    public function syncLinkedStoriesTest(int $planID, array $storyIdList, bool $deleteOld): string|array
    {
        $this->productplan->syncLinkedStories($planID, $storyIdList, $deleteOld);

        if(dao::isError()) return dao::getError();

        $planStories = $this->productplan->dao->select('story')->from(TABLE_PLANSTORY)->where('plan')->eq($planID)->fetchPairs();
        return implode(',', $planStories);
    }

    /**
     * Test unlinkBug method.
     *
     * @param  int    $bugID
     * @access public
     * @return object|false
     */
    public function unlinkBugTest($bugID)
    {
        $this->productplan->unlinkBug($bugID);

        return $this->productplan->dao->select('*')->from(TABLE_BUG)->where('id')->eq($bugID)->fetch();
    }

    /**
     * Test unlinkStory method.
     *
     * @param  int    $storyID
     * @access public
     * @return object|false
     */
    public function unlinkStoryTest($storyID, $planID)
    {
        $this->productplan->unlinkStory($storyID, $planID);

        return $this->productplan->dao->select('*')->from(TABLE_STORY)->where('id')->eq($storyID)->fetch();
    }

    /**
     * Test unlinkOldBranch method.
     *
     * @param  int    $changeBranch
     * @access public
     * @return array
     */
    public function unlinkOldBranchTest($changeBranch = false)
    {
        $changes = array(
            1 => array(array('field' => $changeBranch ? 'branch' : 'name', 'old' => 1, 'new' => 2))
        );
        $this->productplan->unlinkOldBranch($changes);

        return $this->productplan->dao->select('*')->from(TABLE_BUG)->fetchAll('id');
    }

    /**
     * 批量更新计划。
     * Batch update plan list.
     *
     * @param  int    $prodcutID
     * @param  array  $plans
     * @access public
     * @return array
     */
    public function batchUpdateTest(int $prodcutID, array $plans): array
    {
        $oldPlans = $this->productplan->getByIDList(array_keys($plans));
        $this->productplan->batchUpdate($prodcutID, $plans);
        if(dao::isError()) return dao::getError();

        $changes = array();
        foreach($plans as $planID => $plan)
        {
            $oldPlan = $oldPlans[$planID];
            $changes[$planID] = common::createChanges($oldPlan, $plan);
        }
        return $changes;
    }

    /**
     * 检查计划的日期。
     * Check date for plan.
     *
     * @param  int    $planID
     * @param  string $begin
     * @param  string $end
     * @access public
     * @return array
     */
    public function checkDate4PlanTest(int $planID, string $begin, string $end): array
    {
        $plan = $this->productplan->getByID($planID);
        $this->productplan->checkDate4Plan($plan, $begin, $end);

        if(dao::isError()) return dao::getError();
        return array('测试通过');
    }
}
