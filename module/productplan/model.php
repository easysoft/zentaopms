<?php
declare(strict_types=1);
/**
 * The model file of productplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     productplan
 * @version     $Id: model.php 4639 2013-04-11 02:06:35Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<?php
class productplanModel extends model
{
    /**
     * 通过ID获取计划信息。
     * Get plan by id.
     *
     * @param  int          $planID
     * @param  bool         $setImgSize
     * @access public
     * @return object|false
     */
    public function getByID(int $planID, bool $setImgSize = false): object|false
    {
        $plan = $this->dao->findByID($planID)->from(TABLE_PRODUCTPLAN)->fetch();
        if(!$plan) return false;

        $plan = $this->loadModel('file')->replaceImgURL($plan, 'desc');
        if($setImgSize) $plan->desc = $this->file->setImgSize((string)$plan->desc);

        $plan->isParent = 0;
        if($plan->parent == -1) $plan->isParent = 1;
        return $plan;
    }

    /**
     * 获取计划列表信息。
     * Get plans by idList
     *
     * @param  array  $planIdList
     * @access public
     * @return array
     */
    public function getByIDList(array $planIdList): array
    {
        return $this->dao->select('*')->from(TABLE_PRODUCTPLAN)->where('id')->in($planIdList)->orderBy('begin desc')->fetchAll('id');
    }

    /**
     * 获取创建的最后一个计划。
     * Get last plan.
     *
     * @param  int          $productID
     * @param  string       $branch
     * @param  int          $parent
     * @access public
     * @return object|false
     */
    public function getLast(int $productID, string $branch = '', int $parent = 0): object|false
    {
        $branchQuery = '';
        if($branch !== '')
        {
            $branchQuery .= '(';
            $branchCount = count(explode(',', $branch));
            foreach(explode(',', $branch) as $index => $branchID)
            {
                $branchQuery .= "CONCAT(',', branch, ',') LIKE '%,$branchID,%'";
                if($index < $branchCount - 1) $branchQuery .= ' AND ';
            }
            $branchQuery .= ')';
        }

        return $this->dao->select('*')->from(TABLE_PRODUCTPLAN)
            ->where('deleted')->eq(0)
            ->beginIF($parent <= 0)->andWhere('parent')->le($parent)->fi()
            ->beginIF($parent > 0)->andWhere('parent')->eq($parent)->fi()
            ->andWhere('product')->eq($productID)
            ->andWhere('end')->ne($this->config->productplan->future)
            ->beginIF($branch !== '' && !empty($branchQuery))->andWhere($branchQuery)->fi()
            ->orderBy('end desc')
            ->limit(1)
            ->fetch();
    }

    /**
     * 获取产品计划列表。
     * Get plan list.
     *
     * @param  int    $productID
     * @param  string $branch
     * @param  string $browseType all|undone|wait|doing|done|closed
     * @param  object $pager
     * @param  string $orderBy
     * @param  string $param      skipparent|noproduct
     * @param  int    $queryID
     * @access public
     * @return array
     */
    public function getList(int $productID = 0, string $branch = '', string $browseType = 'undone', object|null $pager = null, string $orderBy = 'begin_desc', string $param = '', int $queryID = 0): array
    {
        $this->loadModel('search')->setQuery('productplan', $queryID);

        $products = (strpos($param, 'noproduct') !== false && empty($productID)) ? $this->loadModel('product')->getPairs($param) : array($productID => $productID);
        $plans    = $this->productplanTao->getPlanList(array_keys($products), $branch, $browseType, $param, $orderBy, $pager);
        if(empty($plans)) return array();

        $plans        = $this->reorder4Children($plans);
        $planIdList   = array_keys($plans);
        $planProjects = $this->productplanTao->getPlanProjects($planIdList, strpos($param, 'noproduct') === false || $productID ? $productID : null);

        $product = $this->loadModel('product')->getById($productID);
        $this->loadModel('story');
        if(!empty($product) && $product->type == 'normal') $storyGroups = $this->story->getStoriesByPlanIdList($planIdList);
        $storyCountInTable = $this->dao->select('plan,count(story) as count')->from(TABLE_PLANSTORY)->where('plan')->in($planIdList)->groupBy('plan')->fetchPairs('plan', 'count');

        $bugs = $this->dao->select('plan, id')->from(TABLE_BUG)->where("plan")->in($planIdList)->andWhere('deleted')->eq(0)->fetchGroup('plan', 'id');
        foreach($plans as $plan)
        {
            $storyPairs = array();
            if(!empty($product) && $product->type == 'normal')
            {
                $stories = zget($storyGroups, $plan->id, array());
                foreach($stories as $story) $storyPairs[$story->id] = $story->estimate;
            }
            else
            {
                $storyPairs = $this->story->getPairs(0, $plan->id, 'estimate');
            }

            $bugCount = isset($bugs[$plan->id]) ? count($bugs[$plan->id]) : 0;
            $plan->bugs     = zget($plan, 'bugs', 0)    + $bugCount;
            $plan->hour     = zget($plan, 'hour', 0)    + array_sum($storyPairs);
            $plan->stories  = zget($plan, 'stories', 0) + count($storyPairs);
            $plan->projects = zget($planProjects, $plan->id, '');
            $plan->expired  = $plan->end < helper::today();

            /* Sync linked stories. */
            if(!isset($storyCountInTable[$plan->id]) || $storyCountInTable[$plan->id] != $plan->stories) $this->productplanTao->syncLinkedStories($plan->id, array_keys($storyPairs));

            if(!$plan->parent || !isset($plans[$plan->parent])) continue;
            $plans[$plan->parent]->bugs    = zget($plans[$plan->parent], 'bugs', 0)    + $plan->bugs;
            $plans[$plan->parent]->hour    = zget($plans[$plan->parent], 'hour', 0)    + $plan->hour;
            $plans[$plan->parent]->stories = zget($plans[$plan->parent], 'stories', 0) + $plan->stories;
        }

        return $plans;
    }

    /**
     * 获取产品下的父计划。
     * Get top plan pairs.
     *
     * @param  int    $productID
     * @param  string $exclude
     * @access public
     * @return array
     */
    public function getTopPlanPairs(int $productID, string $exclude = ''): array
    {
        return $this->dao->select("id,title")->from(TABLE_PRODUCTPLAN)
            ->where('product')->eq($productID)
            ->andWhere('parent')->le(0)
            ->andWhere('deleted')->eq(0)
            ->beginIF($exclude)->andWhere('status')->notin($exclude)
            ->orderBy('id_desc')
            ->fetchPairs();
    }

    /**
     * 获取计划id:name的键值对。
     * Get the key-value pair for plan id:name
     *
     * @param  array|int        $productIdList
     * @param  int|string|array $branch
     * @param  string           $param         unexpired|noclosed
     * @param  bool             $skipParent
     * @access public
     * @return array
     */
    public function getPairs(array|int $productIdList = 0, int|string|array $branch = '', string $param = '', bool $skipParent = false): array
    {
        $this->app->loadLang('branch');

        /* Get the query condition for the branch. */
        $branchQuery = '';
        if($branch !== '' && $branch != 'all')
        {
            if(is_int($branch)) $branchQuery = "t1.branch = '$branch'";
            if(is_string($branch)) $branch = array_unique(array_filter(explode(',', trim($branch, ','))));
            if(is_array($branch) && !empty($branch))
            {
                if(count($branch) == 1) $branchQuery = "t1.branch = '" . current($branch) . "'";
                if(count($branch) > 1)
                {
                    foreach($branch as $key => $branchID) $branch[$key] = "FIND_IN_SET('$branchID', t1.branch)";
                    $branchQuery = '(' . implode(' OR ', $branch) . ')';
                }
            }
        }

        $plans = $this->dao->select('t1.id,t1.title,t1.parent,t1.begin,t1.end,t2.type as productType,t1.branch')->from(TABLE_PRODUCTPLAN)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t2.id=t1.product')
            ->where('t1.product')->in($productIdList)
            ->andWhere('t1.deleted')->eq(0)
            ->beginIF(!empty($branchQuery))->andWhere($branchQuery)->fi()
            ->beginIF(strpos($param, 'unexpired') !== false)->andWhere('t1.end')->ge(date('Y-m-d'))->fi()
            ->beginIF(strpos($param, 'noclosed')  !== false)->andWhere('t1.status')->ne('closed')->fi()
            ->orderBy('t1.begin desc')
            ->fetchAll('id');

        /* Build the plan name based on the condition. */
        $plans     = $this->reorder4Children($plans);
        $plans     = $this->relationBranch($plans);
        $planPairs = array();
        foreach($plans as $plan)
        {
            if($skipParent && $plan->parent == '-1') continue;

            if($plan->parent > 0 && isset($plans[$plan->parent])) $plan->title = $plans[$plan->parent]->title . ' /' . $plan->title;

            $planPairs[$plan->id] = $plan->title . " [{$plan->begin} ~ {$plan->end}]";

            if($plan->begin == $this->config->productplan->future && $plan->end == $this->config->productplan->future) $planPairs[$plan->id] = $plan->title . ' ' . $this->lang->productplan->future;
            if(str_contains($param, 'cleantitle')) $planPairs[$plan->id] = $plan->title;
            if($plan->productType != 'normal') $planPairs[$plan->id] = $planPairs[$plan->id] . ' / ' . ($plan->branchName ? $plan->branchName : $this->lang->branch->main);
        }
        return $planPairs;
    }

    /**
     * 通过产品ID列表获取计划信息。
     * Get plan info by product id list.
     *
     * @param  array  $productIdList
     * @access public
     * @return array
     */
    public function getForProducts(array $productIdList): array
    {
        $plans = $this->dao->select('id,title,parent,begin,end')->from(TABLE_PRODUCTPLAN)
            ->where('product')->in($productIdList)
            ->andWhere('deleted')->eq(0)
            ->orderBy('begin desc')
            ->fetchAll('id');

        $plans       = $this->reorder4Children($plans);
        $planPairs   = array();
        $parentTitle = array();
        foreach($plans as $plan)
        {
            if($plan->parent == '-1') $parentTitle[$plan->id] = $plan->title;
            if($plan->parent > 0 and isset($parentTitle[$plan->parent])) $plan->title = $parentTitle[$plan->parent] . ' /' . $plan->title;
            $planPairs[$plan->id] = $plan->title;
        }
        return $planPairs;
    }

    /**
     * 根据产品ID获取计划分组。
     * Get plan group by product id list.
     *
     * @param  array  $productIdList
     * @param  string $param         skipparent|unexpired
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getGroupByProduct(array $productIdList = array(), string $param = '', string $orderBy = 'id_desc'): array
    {
        $plans = $this->dao->select('t1.*,t2.type as productType')->from(TABLE_PRODUCTPLAN)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t2.id=t1.product')
            ->where('t1.deleted')->eq(0)
            ->beginIF($productIdList)->andWhere('t1.product')->in($productIdList)->fi()
            ->beginIF(strpos($param, 'unexpired') !== false)->andWhere('t1.end')->ge(helper::today())->fi()
            ->orderBy('t1.' . $orderBy)
            ->fetchAll('id');
        $plans = $this->relationBranch($plans);

        $planGroup = array();
        foreach($plans as $plan)
        {
            foreach(explode(',', $plan->branch) as $branch)
            {
                if(!isset($planGroup[$plan->product][$branch])) $planGroup[$plan->product][$branch] = array();

                if($plan->parent == '-1' && strpos($param, 'skipparent') !== false) continue 2;

                $plan->expired = $plan->end < helper::today();
                $planGroup[$plan->product][$branch][$plan->id] = $plan;
            }
        }
        return $planGroup;
    }

    /**
     * 获取产品下的计划列表信息。
     * Get plan list information under the product.
     *
     * @param  array  $productIdList
     * @param  string $end
     * @access public
     * @return array
     */
    public function getProductPlans(array $productIdList = array(), string $end = ''): array
    {
        return $this->dao->select('*')->from(TABLE_PRODUCTPLAN)
            ->where('deleted')->eq(0)
            ->beginIF($productIdList)->andWhere('product')->in($productIdList)->fi()
            ->beginIF($end)->andWhere('end')->ge(helper::today())->fi()
            ->fetchGroup('product');
    }

    /**
     * 获取计划下的所有子计划。
     * Get Children plan.
     *
     * @param  int    $planID
     * @access public
     * @return array
     */
    public function getChildren(int $planID): array
    {
        return $this->dao->select('*')->from(TABLE_PRODUCTPLAN)->where('parent')->eq($planID)->andWhere('deleted')->eq('0')->fetchAll('id');
    }

    /**
     * Get plan list by story id list.
     *
     * @param  string|array $storyIdList
     * @access public
     * @return array
     */
    public function getPlansByStories(array $storyIdList): array
    {
        if(empty($storyIdList)) return array();
        return $this->dao->select('t2.id as storyID, t3.*')->from(TABLE_PLANSTORY)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t2.id=t1.story')
            ->leftJoin(TABLE_PRODUCTPLAN)->alias('t3')->on('t3.id=t1.plan')
            ->where('t2.id')->in($storyIdList)
            ->fetchGroup('storyID', 'id');
    }

    /**
     * 获取分支计划的对应关系。
     * Get branch plan pairs.
     *
     * @param  int    $productID
     * @param  array  $branches
     * @param  string $param
     * @param  bool   $skipParent
     * @access public
     * @return array
     */
    public function getBranchPlanPairs(int $productID, array $branches = array(), string $param = '', bool $skipParent = false): array
    {
        $branchQuery = '';
        if(!empty($branches) and is_array($branches))
        {
            $branchQuery .= '(';
            foreach($branches as $branchID)
            {
                $branchQuery .= "CONCAT(',', branch, ',') LIKE '%,$branchID,%'";
                if($branchID != end($branches)) $branchQuery .= ' OR ';
            }
            $branchQuery .= ')';
        }

        $param = strtolower($param);
        $plans = $this->dao->select('parent,branch,id,title,begin,end')->from(TABLE_PRODUCTPLAN)
            ->where('product')->eq($productID)
            ->andWhere('deleted')->eq(0)
            ->beginIF(!empty($branchQuery))->andWhere($branchQuery)->fi()
            ->beginIF($branches != '')->andWhere('branch')->in($branches)->fi()
            ->beginIF(strpos($param, 'unexpired') !== false)->andWhere('end')->ge(helper::today())->fi()
            ->orderBy('begin desc')
            ->fetchAll('id');

        $planPairs = array();
        foreach($plans as $planID => $plan)
        {
            foreach(explode(',', $plan->branch) as $branch)
            {
                if($skipParent and $plan->parent == '-1') continue 2;

                if($plan->parent > 0 and isset($plans[$plan->parent])) $plan->title = $plans[$plan->parent]->title . ' /' . $plan->title;
                $planPairs[$branch][$planID] = $plan->title . ' [' . $plan->begin . '~' . $plan->end . ']';
                if($branch !== BRANCH_MAIN) $planPairs[BRANCH_MAIN][$planID] = $plan->title . ' [' . $plan->begin . '~' . $plan->end . ']';
            }
        }
        return $planPairs;
    }

    /**
     * 创建一个计划。
     * Create a plan.
     *
     * @param  object    $plan
     * @param  int       $isFuture
     * @access public
     * @return int|false
     */
    public function create(object $plan, int $isFuture): int|false
    {
        $product = $this->loadModel('product')->getByID((int)$plan->product);
        if($product->type != 'normal' && $plan->branch == '')
        {
            $this->lang->product->branch = sprintf($this->lang->product->branch, $this->lang->product->branchName[$product->type]);
            dao::$errors['branch[]'] = sprintf($this->lang->error->notempty, $this->lang->product->branch);
            return false;
        }

        if($plan->parent > 0)
        {
            $parentPlan = $this->getByID($plan->parent);
            if($parentPlan->begin != $this->config->productplan->future && $plan->begin < $parentPlan->begin) dao::$errors['begin'] = sprintf($this->lang->productplan->beginLessThanParent, $parentPlan->begin);
            if($parentPlan->end != $this->config->productplan->future && $plan->end > $parentPlan->end) dao::$errors['end'] = sprintf($this->lang->productplan->endGreatThanParent, $parentPlan->end);
        }

        if(!$isFuture && strpos($this->config->productplan->create->requiredFields, 'begin') !== false && empty($plan->begin)) dao::$errors['begin'] = sprintf($this->lang->error->notempty, $this->lang->productplan->begin);
        if(!$isFuture && strpos($this->config->productplan->create->requiredFields, 'end') !== false && empty($plan->end)) dao::$errors['end'] = sprintf($this->lang->error->notempty, $this->lang->productplan->end);
        if(dao::isError()) return false;

        $plan->begin = $isFuture || empty($plan->begin) ? $this->config->productplan->future : $plan->begin;
        $plan->end   = $isFuture || empty($plan->end)   ? $this->config->productplan->future : $plan->end;

        $plan = $this->loadModel('file')->processImgURL($plan, $this->config->productplan->editor->create['id'], (string)$this->post->uid);
        $this->dao->insert(TABLE_PRODUCTPLAN)->data($plan)
            ->autoCheck()
            ->batchCheck($this->config->productplan->create->requiredFields, 'notempty')
            ->checkIF(!$isFuture && $plan->begin != $this->config->productplan->future, 'end', 'ge', $plan->begin)
            ->checkFlow()
            ->exec();
        if(dao::isError()) return false;

        $planID = $this->dao->lastInsertID();
        $this->file->updateObjectID($this->post->uid, $planID, 'plan');
        $this->loadModel('score')->create('productplan', 'create', $planID);
        if(!empty($plan->parent) && $parentPlan->parent == '0')
        {
            $plan->id = $planID;
            $this->transferStoriesAndBugs($plan);
        }
        return $planID;
    }

    /**
     * 更新一个计划。
     * Update a plan.
     *
     * @param  object      $plan
     * @param  object      $oldPlan
     * @access public
     * @return array|false
     */
    public function update($plan, $oldPlan): array|false
    {
        $plan = $this->buildPlanByStatus($plan->status, '', $plan);
        $this->checkDataForUpdate($plan, $oldPlan);
        if(dao::isError()) return false;

        $parentPlan = $this->getByID($plan->parent);
        $futureTime = $this->config->productplan->future;
        if($plan->parent > 0)
        {
            if($parentPlan->begin !== $futureTime && $plan->begin < $parentPlan->begin) dao::$errors['begin'] = sprintf($this->lang->productplan->beginLessThanParent, $parentPlan->begin);
            if($parentPlan->end !== $futureTime && $plan->end > $parentPlan->end) dao::$errors['end'] = sprintf($this->lang->productplan->endGreatThanParent, $parentPlan->end);
        }
        elseif($oldPlan->parent == -1 && ($plan->begin != $futureTime || $plan->end != $futureTime))
        {
            $childPlans = $this->getChildren($oldPlan->id);
            $minBegin   = $plan->begin;
            $maxEnd     = $plan->end;
            foreach($childPlans as $childPlan)
            {
                if($childPlan->begin < $minBegin) $minBegin = $childPlan->begin;
                if($childPlan->end > $maxEnd) $maxEnd = $childPlan->end;
            }
            if($minBegin < $plan->begin && $minBegin != $futureTime) dao::$errors['begin'] = sprintf($this->lang->productplan->beginGreaterChildTip, $oldPlan->title, $plan->begin, $minBegin);
            if($maxEnd > $plan->end && $maxEnd != $futureTime) dao::$errors['end'] = sprintf($this->lang->productplan->endLessThanChildTip, $oldPlan->title, $plan->end, $maxEnd);
        }
        if(dao::isError()) return false;

        $plan = $this->loadModel('file')->processImgURL($plan, $this->config->productplan->editor->edit['id'], (string)$this->post->uid);
        $this->dao->update(TABLE_PRODUCTPLAN)->data($plan)
            ->autoCheck()
            ->batchCheck($this->config->productplan->edit->requiredFields, 'notempty')
            ->checkIF($plan->begin != $futureTime && $plan->end != $futureTime, 'end', 'ge', $plan->begin)
            ->checkFlow()
            ->where('id')->eq($oldPlan->id)
            ->exec();
        if(dao::isError()) return false;

        if($plan->parent > 0) $this->updateParentStatus($plan->parent);
        if($oldPlan->parent > 0) $this->updateParentStatus($oldPlan->parent);
        if(dao::isError()) return false;

        $this->file->updateObjectID($this->post->uid, $oldPlan->id, 'plan');
        if(!empty($plan->parent) && isset($parentPlan->parent) && $parentPlan->parent == '0') $this->transferStoriesAndBugs($plan);
        return common::createChanges($oldPlan, $plan);
    }

    /**
     * 检查更新计划的数据。
     * Check data for update plan.
     *
     * @param  object $plan
     * @param  object $oldPlan
     * @access public
     * @return bool
     */
    public function checkDataForUpdate(object $plan, object $oldPlan): bool
    {
        $product = $this->loadModel('product')->getByID($oldPlan->product);
        if($product->type != 'normal')
        {
            if($plan->branch == '')
            {
                $this->lang->product->branch = sprintf($this->lang->product->branch, $this->lang->product->branchName[$product->type]);
                dao::$errors['branch[]'] = sprintf($this->lang->error->notempty, $this->lang->product->branch);
                return false;
            }
            else
            {
                if($oldPlan->parent == -1)
                {
                    /* Get branches of child plans. */
                    $childBranches = array();
                    $childPlans    = $this->getChildren($oldPlan->id);
                    $branchPairs   = $this->loadModel('branch')->getPairs($oldPlan->product);
                    foreach($childPlans as $children)
                    {
                        foreach(explode(',', $children->branch) as $childBranchID) $childBranches[$childBranchID] = $childBranchID;
                    }

                    /* Get branches of parent plan that cannot delete. */
                    $canDeleteBranch = true;
                    $deleteBranches  = '';
                    foreach(explode(',', $oldPlan->branch) as $oldBranchID)
                    {
                        if(strpos(",$plan->branch,", ",$oldBranchID,") === false && isset($childBranches[$oldBranchID]))
                        {
                            $canDeleteBranch = false;
                            if(isset($branchPairs[$oldBranchID])) $deleteBranches .= "{$branchPairs[$oldBranchID]},";
                        }
                    }

                    if(!$canDeleteBranch)
                    {
                        $this->lang->productplan->deleteBranchTip = str_replace('@branch@', $this->lang->product->branchName[$product->type], $this->lang->productplan->deleteBranchTip);
                        dao::$errors['branch[]'] = sprintf($this->lang->productplan->deleteBranchTip, trim($deleteBranches, ','));
                        return false;
                    }
                }
            }
        }

        return true;
    }

    /**
     * 更新计划和父计划的状态。
     * Update a plan's status.
     *
     * @param  int    $planID
     * @param  string $status doing|done|closed
     * @param  string $action started|finished|closed|activated
     * @access public
     * @return bool
     */
    public function updateStatus(int $planID, string $status = '', string $action = ''): bool
    {
        $oldPlan = $this->getByID($planID);
        if(!$oldPlan) return false;

        $plan = $this->buildPlanByStatus($status, (string)$this->post->closedReason);
        $this->dao->update(TABLE_PRODUCTPLAN)->data($plan)->where('id')->eq($planID)->exec();
        if(dao::isError()) return false;

        $changes  = common::createChanges($oldPlan, $plan);
        $actionID = $this->loadModel('action')->create('productplan', $planID, $action, (string)$this->post->comment);
        $this->action->logHistory($actionID, $changes);

        if($oldPlan->parent > 0) $this->updateParentStatus($oldPlan->parent);

        return !dao::isError();
    }

    /**
     * 根据状态构建计划对象。
     * Build a plan object by status.
     *
     * @param  string $status doing|done|closed
     * @param  string $closedReason
     * @param  object $plan
     * @access public
     * @return object
     */
    public function buildPlanByStatus(string $status, string $closedReason = '', object $plan = null): object
    {
        $now = helper::now();

        if(!$plan) $plan = new stdclass();
        $plan->status = $status;

        if($status == 'doing')
        {
            $plan->finishedDate = null;
            $plan->closedDate   = null;
            $plan->closedReason = '';
        }
        elseif($status == 'done')
        {
            $plan->finishedDate = $now;
            $plan->closedDate   = null;
            $plan->closedReason = '';
        }
        elseif($status == 'closed')
        {
            $plan->closedDate   = $now;
            $plan->closedReason = $closedReason;
        }

        return $plan;
    }

    /**
     * 更新父计划的状态。
     * Update a parent plan's status.
     *
     * @param  int    $parentID
     * @access public
     * @return bool
     */
    public function updateParentStatus(int $parentID): bool
    {
        $oldPlan     = $this->getByID($parentID);
        $childStatus = $this->dao->select('status')->from(TABLE_PRODUCTPLAN)->where('parent')->eq($parentID)->andWhere('deleted')->eq(0)->fetchPairs();

        /* If the subplan is empty, update the plan. */
        if(empty($childStatus))
        {
            $this->dao->update(TABLE_PRODUCTPLAN)->set('parent')->eq(0)->set('status')->eq('wait')->where('id')->eq($parentID)->exec();
            return !dao::isError();
        }

        $plan = new stdclass();
        if(count($childStatus) == 1 && isset($childStatus['wait'])) return true;
        if(count($childStatus) == 1 && isset($childStatus['closed']))
        {
            if($oldPlan->status != 'closed')
            {
                $status       = 'closed';
                $parentAction = 'closedbychild';
            }
        }
        elseif(!isset($childStatus['wait']) && !isset($childStatus['doing']))
        {
            if($oldPlan->status != 'done')
            {
                $status       = 'done';
                $parentAction = 'finishedbychild';
            }
        }
        elseif($oldPlan->status != 'doing')
        {
            $status       = 'doing';
            $parentAction = $this->app->rawMethod == 'create' ? 'createchild' : 'activatedbychild';
        }

        if(!empty($status))
        {
            $plan = $this->buildPlanByStatus($status);
            $this->dao->update(TABLE_PRODUCTPLAN)->data($plan)->where('id')->eq($parentID)->exec();
            $this->loadModel('action')->create('productplan', $parentID, $parentAction, '', $parentAction);
        }
        return !dao::isError();
    }

    /**
     * 批量更新计划。
     * Batch update plan list.
     *
     * @param  int    $productID
     * @param  array  $plans
     * @access public
     * @return array
     */
    public function batchUpdate(int $productID, array $plans): array|bool
    {
        $this->loadModel('action');
        $oldPlans     = $this->getByIDList(array_keys($plans));
        $product      = $this->loadModel('product')->getByID($productID);
        $futureConfig = $this->config->productplan->future;

        $changes = array();
        $parents = array();
        foreach($plans as $planID => $plan)
        {
            $oldPlan  = $oldPlans[$planID];
            $change   = common::createChanges($oldPlan, $plan);
            if(empty($change)) continue;

            $parentID = $oldPlan->parent;
            if($parentID > 0 && !isset($parents[$parentID])) $parents[$parentID] = $parentID;

            $this->dao->update(TABLE_PRODUCTPLAN)->data($plan)->autoCheck()->checkFlow()->where('id')->eq($planID)->exec();
            if(dao::isError()) return false;

            $actionID = $this->action->create('productplan', $planID, 'Edited');
            $this->action->logHistory($actionID, $change);

            $changes[$planID] = $change;
        }

        foreach($parents as $parent) $this->updateParentStatus($parent);
        if($changes) $this->unlinkOldBranch($changes);

        if(dao::isError()) return false;
        return true;
    }

    /**
     * 批量更新计划的状态。
     * Batch change the status of productplan.
     *
     * @param  array  $planIdList
     * @param  string $status
     * @access public
     * @return bool
     */
    public function batchChangeStatus(array $planIdList, string $status): bool
    {
        if($status == 'closed')
        {
            $closedReasons = $this->post->closedReason ? $this->post->closedReason : array();
            if(empty($closedReasons))
            {
                dao::$errors['closedReason[]'] = sprintf($this->lang->error->notempty, $this->lang->productplan->closedReason);
                return false;
            }

            foreach($closedReasons as $planID => $reason)
            {
                if(empty($reason))
                {
                    dao::$errors['closedReason[]'] = sprintf($this->lang->error->notempty, $this->lang->productplan->closedReason);
                    return false;
                }
            }
        }

        $oldPlans = $this->getByIDList($planIdList);
        foreach($oldPlans as $planID => $oldPlan)
        {
            if($status == $oldPlan->status) continue;

            $plan = $this->buildPlanByStatus($status, $status == 'closed' ? $closedReasons[$planID] : '');

            $this->dao->update(TABLE_PRODUCTPLAN)->data($plan)->autoCheck()->where('id')->eq((int)$planID)->exec();
            if(dao::isError()) return false;

            if($oldPlan->parent > 0) $this->updateParentStatus($oldPlan->parent);

            $changes = common::createChanges($oldPlan, $plan);
            if(empty($changes)) continue;

            $comment  = isset($_POST['comment'][$planID]) ? $this->post->comment[$planID] : '';
            $actionID = $this->loadModel('action')->create('productplan', $planID, 'edited', $comment);
            $this->action->logHistory($actionID, $changes);
        }

        return !dao::isError();
    }

    /**
     * 检查计划的日期。
     * Check date for plan.
     *
     * @param  object $plan
     * @param  string $begin
     * @param  string $end
     * @access public
     * @return void
     */
    public function checkDate4Plan(object $plan, string $begin, string $end): void
    {
        if($plan->parent == -1)
        {
            $childPlans = $this->dao->select('*')->from(TABLE_PRODUCTPLAN)->where('parent')->eq($plan->id)->andWhere('deleted')->eq(0)->fetchAll();
            $minBegin   = $begin;
            $maxEnd     = $end;
            foreach($childPlans as $childPlan)
            {
                if($childPlan->begin < $minBegin && $minBegin != $this->config->productplan->future) $minBegin = $childPlan->begin;
                if($childPlan->end > $maxEnd && $maxEnd != $this->config->productplan->future) $maxEnd = $childPlan->end;
            }
            if($minBegin < $begin && $begin != $this->config->productplan->future) dao::$errors['begin'] = sprintf($this->lang->productplan->beginGreaterChild, $minBegin);
            if($maxEnd > $end && $end != $this->config->productplan->future) dao::$errors['end'] = sprintf($this->lang->productplan->endLessThanChild, $maxEnd);
        }
        elseif($plan->parent > 0)
        {
            $parentPlan = $this->getByID($plan->parent);
            if($begin < $parentPlan->begin && $parentPlan->begin != $this->config->productplan->future) dao::$errors['begin'] = sprintf($this->lang->productplan->beginLessThanParent, $parentPlan->begin);
            if($end > $parentPlan->end && $parentPlan->end != $this->config->productplan->future) dao::$errors['end'] = sprintf($this->lang->productplan->endGreatThanParent, $parentPlan->end);
        }
    }

    /**
     * 将父计划的parent改为-1, 没有子计划的父计划的parent改为0。
     * Change parent field by planID.
     *
     * @param  int    $planID
     * @access public
     * @return bool
     */
    public function changeParentField(int $planID): bool
    {
        $plan = $this->getByID($planID);
        if($plan->parent <= 0) return true;

        $childCount = count($this->getChildren($plan->parent));
        $parent     = $childCount == 0 ? '0' : '-1';

        if($childCount >= 0) $this->dao->update(TABLE_PRODUCTPLAN)->set('parent')->eq($parent)->where('id')->eq((int)$plan->parent)->exec();
        $this->dao->update(TABLE_PRODUCTPLAN)->set('parent')->eq('0')->where('id')->eq($planID)->exec();

        return !dao::isError();
    }

    /**
     * 批量关联需求。
     * Batch link story.
     *
     * @param  int    $planID
     * @param  array  $storyIdList
     * @access public
     * @return bool
     */
    public function linkStory(int $planID, array $storyIdList): bool
    {
        $stories = $this->loadModel('story')->getByList($storyIdList);
        if(!$stories) return false;

        $this->loadModel('action');
        foreach($storyIdList as $storyID)
        {
            if(!isset($stories[$storyID])) continue;

            $story = $stories[$storyID];
            if(strpos(",$story->plan,", ",{$planID},") !== false) continue;

            /* Update the plan linked with the story and the order of the story in the plan. */
            $storyID = (int)$storyID;
            $this->dao->update(TABLE_STORY)->set('plan')->eq($planID)->where('id')->eq($storyID)->exec();

            $this->story->updateStoryOrderOfPlan($storyID, (string)$planID, $story->plan);

            $this->action->create('story', $storyID, 'linked2plan', '', $planID);
            $this->story->setStage($storyID);
        }

        $this->productplanTao->syncLinkedStories($planID, $storyIdList, false);
        $this->action->create('productplan', $planID, 'linkstory', '', implode(',', $storyIdList));

        return !dao::isError();
    }

    /**
     * 取消关联需求。
     * Unlink story.
     *
     * @param  int    $storyID
     * @access public
     * @return bool
     */
    public function unlinkStory(int $storyID, int $planID): bool
    {
        $story = $this->dao->findByID($storyID)->from(TABLE_STORY)->fetch();
        if(!$story) return false;

        $plans = array_unique(explode(',', trim(str_replace(",$planID,", ',', ',' . trim($story->plan) . ','). ',')));
        $this->dao->update(TABLE_STORY)->set('plan')->eq(implode(',', $plans))->where('id')->eq((int)$storyID)->exec();

        /* Delete the story in the sort of the plan. */
        $this->loadModel('story')->updateStoryOrderOfPlan($storyID, '', (string)$planID);

        $this->story->setStage($storyID);
        $this->loadModel('action')->create('story', $storyID, 'unlinkedfromplan', '', $planID);

        return !dao::isError();
    }

    /**
     * 关联Bug。
     * Link bugs.
     *
     * @param  int    $planID
     * @param  array  $bugIdList
     * @access public
     * @return bool
     */
    public function linkBug(int $planID, array $bugIdList): bool
    {
        $this->loadModel('action');

        $bugs = $this->loadModel('bug')->getByIdList($bugIdList);
        foreach($bugIdList as $bugID)
        {
            if(!isset($bugs[$bugID])) continue;

            $bug   = $bugs[$bugID];
            $bugID = (int)$bugID;
            if($bug->plan == $planID) continue;

            $this->dao->update(TABLE_BUG)->set('plan')->eq($planID)->where('id')->eq($bugID)->exec();
            $this->action->create('bug', $bugID, 'linked2plan', '', $planID);
        }

        $this->action->create('productplan', $planID, 'linkbug', '', implode(',', $bugIdList));

        return !dao::isError();
    }

    /**
     * 取消关联Bug。
     * Unlink bug.
     *
     * @param  int    $bugID
     * @access public
     * @return bool
     */
    public function unlinkBug(int $bugID): bool
    {
        $planID = $this->dao->findByID($bugID)->from(TABLE_BUG)->fetch('plan');
        if(!$planID) return false;

        $this->dao->update(TABLE_BUG)->set('plan')->eq(0)->where('id')->eq((int)$bugID)->exec();

        $this->loadModel('action')->create('bug', $bugID, 'unlinkedfromplan', '', $planID);
        return !dao::isError();
    }

    /**
     * 关联项目。
     * Link project.
     *
     * @param  int    $projectID
     * @param  array  $newPlans
     * @access public
     * @return void
     */
    public function linkProject(int $projectID, array $newPlans): void
    {
        $this->loadModel('execution');
        $this->loadModel('story');
        $this->loadModel('project');
        foreach($newPlans as $planID)
        {
            $planStories = $planProducts = array();
            $planStory   = $this->story->getPlanStories((int)$planID);
            if(!empty($planStory))
            {
                $projectProducts = $this->project->getBranchesByProject($projectID);

                foreach($planStory as $id => $story)
                {
                    $projectBranches = zget($projectProducts, $story->product, array());
                    if($story->status != 'active' || (!empty($story->branch) && !empty($projectBranches) && !isset($projectBranches[$story->branch])))
                    {
                        unset($planStory[$id]);
                        continue;
                    }
                    $planProducts[$story->id] = $story->product;
                }
                $planStories = array_keys($planStory);
                $this->execution->linkStory($projectID, $planStories);
            }
        }
    }

    /**
     * 根据子计划重新排序。
     * Reorder for children plans.
     *
     * @param  array  $plans
     * @access public
     * @return array
     */
    public function reorder4Children(array $plans): array
    {
        /* Get children and unset. */
        $childrenPlans = array();
        foreach($plans as $plan)
        {
            if($plan->parent > 0)
            {
                $childrenPlans[$plan->parent][$plan->id] = $plan;
                if(isset($plans[$plan->parent])) unset($plans[$plan->id]);
            }
        }

        if(!empty($childrenPlans))
        {
            /* Append to parent plan. */
            $reorderedPlans = array();
            foreach($plans as $plan)
            {
                $reorderedPlans[$plan->id] = $plan;
                if(isset($childrenPlans[$plan->id]))
                {
                    $plan->children = count($childrenPlans[$plan->id]);
                    foreach($childrenPlans[$plan->id] as $childrenPlan) $reorderedPlans[$childrenPlan->id] = $childrenPlan;
                }
            }
            $plans = $reorderedPlans;
        }

        return $plans;
    }

    /**
     * 获取计划关联的分支信息。
     * Get relation branch for plan list.
     *
     * @param  array  $planList
     * @access public
     * @return array
     */
    public function relationBranch(array $planList): array
    {
        if(empty($planList)) return $planList;

        $branchMap = $this->loadModel('branch')->getPairs(0, 'active');
        foreach($planList as &$plan)
        {
            $plan->branchName = $this->lang->branch->main;
            if($plan->branch)
            {
                $branchName = array();
                foreach(explode(',', $plan->branch) as $planBranch)
                {
                    if(isset($branchMap[$planBranch])) $branchName[] = $branchMap[$planBranch];
                }
                if($branchName) $plan->branchName = implode(',', $branchName);
            }
        }

        return $planList;
    }

    /**
     * 判断操作是否可点击。
     * Judge an action is clickable or not.
     *
     * @param  object $plan
     * @param  string $action
     * @access public
     * @return bool
     */
    public static function isClickable(object $plan, string $action): bool
    {
        global $app;
        switch($action)
        {
            case 'create' :
                if($plan->parent > 0 || strpos('done,closed', $plan->status) !== false) return false;
                break;
            case 'start' :
                if($plan->status != 'wait' || $plan->isParent) return false;
                break;
            case 'finish' :
                if($plan->status != 'doing' || $plan->isParent) return false;
                break;
            case 'close' :
                if($plan->status == 'closed' || $plan->isParent) return false;
                break;
            case 'activate' :
                if($plan->status == 'wait' || $plan->status == 'doing' || $plan->isParent) return false;
                break;
            case 'delete' :
                if($plan->isParent) return false;
                break;
            case 'createExecution' :
                if($plan->isParent || $plan->expired || in_array($plan->status, array('done', 'closed')) || !common::hasPriv('execution', 'create', $plan)) return false;

                $product          = $app->control->loadModel('product')->getByID($plan->product);
                $branchList       = $app->control->loadModel('branch')->getList($plan->product, 0, 'all');
                $branchStatusList = array();
                foreach($branchList as $productBranch) $branchStatusList[$productBranch->id] = $productBranch->status;

                if($product->type != 'normal')
                {
                    $branchStatus = isset($branchStatusList[$plan->branch]) ? $branchStatusList[$plan->branch] : '';
                    if($branchStatus == 'closed') return false;
                }

                break;
            case 'linkStory' :
                if($plan->isParent) return false;
                break;
            case 'linkBug' :
                if($plan->isParent) return false;
                break;
        }

        return true;
    }

    /**
     * 构造计划搜索功能数据。
     * Build search form for plan.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @param  object $product
     * @access public
     * @return void
     */
    public function buildSearchForm(int $queryID, string $actionURL, object $product)
    {
        global $app;
        $app->loadLang('branch');
        $this->config->productplan->search['actionURL'] = $actionURL;
        $this->config->productplan->search['queryID']   = $queryID;

        if($product->type != 'normal') $this->config->productplan->search['params']['branch']['values']  = array('' => '', '0' => $this->lang->branch->main) + $this->loadModel('branch')->getPairs($product->id, 'noempty');
        if($product->type == 'normal') unset($this->config->productplan->search['fields']['branch']);
        $this->loadModel('search')->setSearchParams($this->config->productplan->search);
    }

    /**
     * 将父计划下的需求和Bug转移到子计划下。
     * Transfer stories and bugs to new plan.
     *
     * @param  object $plan
     * @access public
     * @return void
     */
    public function transferStoriesAndBugs(object $plan): void
    {
        $this->dao->update(TABLE_PRODUCTPLAN)->set('parent')->eq('-1')->where('id')->eq($plan->parent)->andWhere('parent')->eq('0')->exec();

        /* Transfer stories linked with the parent plan to the child plan. */
        $stories       = $this->dao->select('*')->from(TABLE_STORY)->where("CONCAT(',', plan, ',')")->like("%,{$plan->parent},%")->fetchAll('id');
        $unlinkStories = array();
        foreach($stories as $storyID => $story)
        {
            if(!empty($story->branch) && strpos(",$plan->branch,", ",$story->branch,") === false)
            {
                $unlinkStories[$storyID] = $storyID;
                $storyPlan = str_replace(",{$plan->parent},", ',', ",$story->plan,");
            }
            else
            {
                $storyPlan = str_replace(",{$plan->parent},", ",$plan->id,", ",$story->plan,");
            }
            $storyPlan = trim($storyPlan, ',');

            $this->dao->update(TABLE_STORY)->set('plan')->eq($storyPlan)->where('id')->eq($storyID)->exec();
        }
        if(!empty($unlinkStories)) $this->dao->delete()->from(TABLE_PLANSTORY)->where('plan')->eq($plan->parent)->andWhere('story')->in($unlinkStories)->exec();
        $this->dao->update(TABLE_PLANSTORY)->set('plan')->eq($plan->id)->where('plan')->eq($plan->parent)->exec();

        /* Transfer bugs linked with the parent plan to the child plan. */
        $bugs       = $this->dao->select('*')->from(TABLE_BUG)->where('plan')->eq($plan->parent)->fetchAll('id');
        $unlinkBugs = array();
        foreach($bugs as $bugID => $bug)
        {
            if(!empty($bug->branch) && strpos(",$plan->branch,", ",$bug->branch,") === false) $unlinkBugs[$bugID] = $bugID;
        }
        if(!empty($unlinkBugs)) $this->dao->update(TABLE_BUG)->set('plan')->eq(0)->where('plan')->eq($plan->parent)->andWhere('id')->in($unlinkBugs)->exec();
        $this->dao->update(TABLE_BUG)->set('plan')->eq($plan->id)->where('plan')->eq($plan->parent)->exec();
    }

    /**
     * 当编辑计划分支时，取消计划关联的需求和Bug。
     * Unlink story and bug when edit branch of plan.
     * @param  array     $changeList
     * @access protected
     * @return bool
     */
    public function unlinkOldBranch(array $changeList): bool
    {
        foreach($changeList as $planID => $changes)
        {
            $oldBranch = '';
            $newBranch = '';
            foreach($changes as $change)
            {
                if($change['field'] == 'branch')
                {
                    $oldBranch = $change['old'];
                    $newBranch = $change['new'];
                    break;
                }
            }

            $planStories = $this->loadModel('story')->getPlanStories($planID, 'all');
            $planBugs    = $this->loadModel('bug')->getPlanBugs($planID, 'all');
            if($oldBranch)
            {
                foreach($planStories as $storyID => $story)
                {
                    if($story->branch && str_contains(",$newBranch,", ",$story->branch,")) $this->unlinkStory($storyID, $planID);
                }

                foreach($planBugs as $bugID => $bug)
                {
                    if($bug->branch && str_contains(",$newBranch,", ",$bug->branch,")) $this->unlinkBug($bugID, $planID);
                }
            }
        }

        return !dao::isError();
    }

    /**
     * 检查是否有未取消关联的需求和Bug。
     * Check if there are unlinked stories and bugs.
     *
     * @param  array  $branchIdList
     * @param  int    $planID
     * @param  string $type
     * @access public
     * @return int
     */
    public function checkUnlinkObjects(array $branchIdList, int $planID, string $type = 'story'): int
    {
        return (int) $this->dao->select('id')->from(zget($this->config->objectTables, $type, TABLE_STORY))
            ->where('branch')->in($branchIdList)
            ->beginIF($type == 'story')->andWhere("CONCAT(',', plan, ',')")->like("%,{$planID},%")->fi()
            ->beginIF($type == 'bug')->andWhere('plan')->eq($planID)->fi()
            ->fetch('id');
    }
}
