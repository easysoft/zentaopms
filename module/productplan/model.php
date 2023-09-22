<?php
/**
 * The model file of productplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     productplan
 * @version     $Id: model.php 4639 2013-04-11 02:06:35Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
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
        return $plan;
    }

    /**
     * Get plans by idList
     *
     * @param  int    $planIdList
     * @access public
     * @return array
     */
    public function getByIDList($planIdList)
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
     * Get list
     *
     * @param  int    $product
     * @param  int    $branch
     * @param  string $browseType all|undone|wait|doing|done|closed
     * @param  object $pager
     * @param  string $orderBy
     * @param  string $param skipparent
     * @param  int    $queryID
     * @access public
     * @return object
     */
    public function getList($product = 0, $branch = 0, $browseType = 'undone', $pager = null, $orderBy = 'begin_desc', $param = '', $queryID = 0)
    {
        $this->loadModel('search')->setQuery('productplan', $queryID);

        $date     = date('Y-m-d');
        $products = (strpos($param, 'noproduct') !== false and empty($product)) ? $this->loadModel('product')->getList() : array(0);
        $plans    = $this->dao->select('*')->from(TABLE_PRODUCTPLAN)
            ->where('deleted')->eq(0)
            ->beginIF(strpos($param, 'noproduct') === false or !empty($product))->andWhere('product')->eq($product)->fi()
            ->beginIF(strpos($param, 'noproduct') !== false and empty($product))->andWhere('product')->in(array_keys($products))->fi()
            ->beginIF(!empty($branch) and $branch != 'all')->andWhere('branch')->eq($branch)->fi()
            ->beginIF(strpos(',all,undone,bySearch,review,', ",$browseType,") === false)->andWhere('status')->eq($browseType)->fi()
            ->beginIF($browseType == 'undone')->andWhere('status')->in('wait,doing')->fi()
            ->beginIF($browseType == 'bySearch')->andWhere($this->session->productplanQuery)->fi()
            ->beginIF($browseType == 'review')->andWhere("FIND_IN_SET('{$this->app->user->account}', reviewers)")->fi()
            ->beginIF(strpos($param, 'skipparent') !== false)->andWhere('parent')->ne(-1)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        if(empty($plans)) return $plans;

        $plans        = $this->reorder4Children($plans);
        $planIdList   = array_keys($plans);
        $planProjects = array();

        foreach($planIdList as $planID)
        {
            $planProjects[$planID] = $this->dao->select('t1.project,t2.name')->from(TABLE_PROJECTPRODUCT)->alias('t1')
                ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
                ->where('1=1')
                ->beginIF(strpos($param, 'noproduct') === false or !empty($product))->andWhere('t1.product')->eq($product)->fi()
                ->andWhere('t2.deleted')->eq(0)
                ->andWhere('t1.plan')->like("%,$planID,%")
                ->andWhere('t2.type')->in('sprint,stage,kanban')
                ->orderBy('project_desc')
                ->fetchAll('project');
        }

        $storyCountInTable = $this->dao->select('plan,count(story) as count')->from(TABLE_PLANSTORY)->where('plan')->in($planIdList)->groupBy('plan')->fetchPairs('plan', 'count');
        $product = $this->loadModel('product')->getById($product);
        if(!empty($product) and $product->type == 'normal')
        {
            $storyGroups = $this->dao->select('id,plan,estimate')->from(TABLE_STORY)
                ->where("plan")->in($planIdList)
                ->andWhere('deleted')->eq(0)
                ->fetchGroup('plan', 'id');
        }

        $bugs = $this->dao->select('*')->from(TABLE_BUG)->where("plan")->in($planIdList)->andWhere('deleted')->eq(0)->fetchGroup('plan', 'id');
        $parentStories = $parentBugs = $parentHour = array();
        foreach($plans as $plan)
        {
            if(!empty($product) and $product->type == 'normal')
            {
                $stories    = zget($storyGroups, $plan->id, array());
                $storyPairs = array();
                foreach($stories as $story) $storyPairs[$story->id] = $story->estimate;
            }
            else
            {
                $storyPairs = $this->dao->select('id,estimate')->from(TABLE_STORY)
                    ->where("CONCAT(',', plan, ',')")->like("%,{$plan->id},%")
                    ->andWhere('deleted')->eq(0)
                    ->fetchPairs('id', 'estimate');
            }
            $plan->stories  = count($storyPairs);
            $plan->bugs     = isset($bugs[$plan->id]) ? count($bugs[$plan->id]) : 0;
            $plan->hour     = array_sum($storyPairs);
            $plan->projects = zget($planProjects, $plan->id, '');
            $plan->expired  = $plan->end < $date ? true : false;

            /* Sync linked stories. */
            if(!isset($storyCountInTable[$plan->id]) or $storyCountInTable[$plan->id] != $plan->stories)
            {
                $this->dao->delete()->from(TABLE_PLANSTORY)->where('plan')->eq($plan->id)->exec();

                $order = 1;
                foreach($storyPairs as $storyID => $estimate)
                {
                    $order ++;
                    $planStory = new stdclass();
                    $planStory->plan  = $plan->id;
                    $planStory->story = $storyID;
                    $planStory->order = $order;
                    $this->dao->replace(TABLE_PLANSTORY)->data($planStory)->exec();
                }
            }

            if(!isset($parentStories[$plan->parent])) $parentStories[$plan->parent] = 0;
            if(!isset($parentBugs[$plan->parent]))    $parentBugs[$plan->parent]    = 0;
            if(!isset($parentHour[$plan->parent]))    $parentHour[$plan->parent]    = 0;

            $parentStories[$plan->parent] += $plan->stories;
            $parentBugs[$plan->parent]    += $plan->bugs;
            $parentHour[$plan->parent]    += $plan->hour;
        }

        unset($parentStories[0]);
        unset($parentBugs[0]);
        unset($parentHour[0]);
        foreach($parentStories as $parentID => $count)
        {
            if(!isset($plans[$parentID])) continue;
            $plan = $plans[$parentID];
            $plan->stories += $count;
            $plan->bugs    += $parentBugs[$parentID];
            $plan->hour    += $parentHour[$parentID];
        }

        return $plans;
    }

    /**
     * Get top plan pairs.
     *
     * @param int    $productID
     * @param int    $exclude
     * @access public
     * @return array
     */
    public function getTopPlanPairs($productID, $exclude = '')
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
            $branchQuery .= '(';
            $branchCount = count(explode(',', $branch));
            foreach(explode(',', $branch) as $index => $branchID)
            {
                $branchQuery .= "FIND_IN_SET('$branchID', t1.branch)";
                if($index < $branchCount - 1) $branchQuery .= ' OR ';
            }
            $branchQuery .= ')';
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
     * Get plans for products
     *
     * @param  array  $products
     * @access public
     * @return void
     */
    public function getForProducts($products)
    {
        $plans = $this->dao->select('id,title,parent,begin,end')->from(TABLE_PRODUCTPLAN)
            ->where('product')->in(array_keys($products))
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
     * Get Children plan.
     *
     * @param  int    $planID
     * @access public
     * @return array
     */
    public function getChildren($planID)
    {
        return $this->dao->select('*')->from(TABLE_PRODUCTPLAN)->where('parent')->eq((int)$planID)->andWhere('deleted')->eq('0')->fetchAll();
    }

    /**
     * Get plan list by story id list.
     *
     * @param  string|array $storyIdList
     * @access public
     * @return array
     */
    public function getPlansByStories($storyIdList)
    {
        if(empty($storyIdList)) return array();
        return $this->dao->select('t2.id as storyID, t3.*')->from(TABLE_PLANSTORY)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t2.id=t1.story')
            ->leftJoin(TABLE_PRODUCTPLAN)->alias('t3')->on('t3.id=t1.plan')
            ->where('t2.id')->in($storyIdList)
            ->fetchGroup('storyID', 'id');
    }

    /**
     * Get branch plan pairs.
     *
     * @param  int    $productID
     * @param  array  $branches
     * @param  string $param
     * @param  bool   $skipParent
     * @access public
     * @return array
     */
    public function getBranchPlanPairs($productID, $branches = '', $param = '', $skipParent = false)
    {
        $branchQuery = '';
        if($branches !== '' and $branches !== 'all')
        {
            $branchQuery .= '(';
            if(!is_array($branches)) $branches = explode(',', $branches);
            foreach($branches as $branchID)
            {
                $branchQuery .= "CONCAT(',', branch, ',') LIKE '%,$branchID,%'";
                if($branchID != end($branches)) $branchQuery .= ' OR ';
            }
            $branchQuery .= ')';
        }

        $date  = date('Y-m-d');
        $param = strtolower($param);
        $plans = $this->dao->select('parent,branch,id,title,begin,end')->from(TABLE_PRODUCTPLAN)
            ->where('product')->eq($productID)
            ->andWhere('deleted')->eq(0)
            ->beginIF(!empty($branchQuery))->andWhere($branchQuery)->fi()
            ->beginIF($branches != '')->andWhere('branch')->in($branches)->fi()
            ->beginIF(strpos($param, 'unexpired') !== false)->andWhere('end')->ge($date)->fi()
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
     * @param  bool      $isFuture
     * @access public
     * @return int|false
     */
    public function create(object $plan, bool $isFuture): int|false
    {
        $product = $this->loadModel('product')->getByID($plan->product);
        if($product->type != 'normal' && $plan->branch == '')
        {
            $this->lang->product->branch = sprintf($this->lang->product->branch, $this->lang->product->branchName[$product->type]);
            dao::$errors['branch[]'] = sprintf($this->lang->error->notempty, $this->lang->product->branch);
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

        $plan = $this->loadModel('file')->processImgURL($plan, $this->config->productplan->editor->create['id'], $this->post->uid);
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
     * Update a plan.
     *
     * @param  int    $planID
     * @access public
     * @return array
     */
    public function update($planID)
    {
        $oldPlan = $this->getByID($planID);
        $plan = fixer::input('post')->stripTags($this->config->productplan->editor->edit['id'], $this->config->allowedTags)
            ->setIF($this->post->future or empty($_POST['begin']), 'begin', $this->config->productplan->future)
            ->setIF($this->post->future or empty($_POST['end']), 'end', $this->config->productplan->future)
            ->setDefault('branch', 0)
            ->cleanINT('parent')
            ->join('branch', ',')
            ->add('id', $planID)
            ->remove('delta,uid,future')
            ->get();

        $plan = $this->buildPlanByStatus($this->post->status, '', $plan);

        $product = $this->loadModel('product')->getByID($oldPlan->product);
        if($product->type != 'normal')
        {
            if(empty($plan->branch))
            {
                $this->lang->product->branch = sprintf($this->lang->product->branch, $this->lang->product->branchName[$product->type]);
                dao::$errors['branch[]'] = sprintf($this->lang->error->notempty, $this->lang->product->branch);
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
                        if(strpos(",$plan->branch,", ",$oldBranchID,") === false and isset($childBranches[$oldBranchID]))
                        {
                            $canDeleteBranch = false;
                            if(isset($branchPairs[$oldBranchID])) $deleteBranches .= "{$branchPairs[$oldBranchID]},";
                        }
                    }

                    $this->lang->productplan->deleteBranchTip = str_replace('@branch@', $this->lang->product->branchName[$product->type], $this->lang->productplan->deleteBranchTip);
                    if(!$canDeleteBranch) dao::$errors[] = sprintf($this->lang->productplan->deleteBranchTip, trim($deleteBranches, ','));
                }
            }
        }
        if(dao::isError()) return false;

        $parentPlan = $this->getByID($plan->parent);
        $futureTime = $this->config->productplan->future;

        if($plan->parent > 0)
        {
            if($parentPlan->begin !== $futureTime)
            {
                if($plan->begin < $parentPlan->begin) dao::$errors['begin'] = sprintf($this->lang->productplan->beginLessThanParent, $parentPlan->begin);
            }
            if($parentPlan->end !== $futureTime)
            {
                if($plan->end !== $futureTime and $plan->end > $parentPlan->end) dao::$errors['end'] = sprintf($this->lang->productplan->endGreatThanParent, $parentPlan->end);
            }
        }
        elseif($oldPlan->parent == -1 and ($plan->begin != $futureTime or $plan->end != $futureTime))
        {
            $childPlans = $this->getChildren($planID);
            $minBegin   = $plan->begin;
            $maxEnd     = $plan->end;
            foreach($childPlans as $childPlan)
            {
                if($childPlan->begin < $minBegin) $minBegin = $childPlan->begin;
                if($childPlan->end > $maxEnd) $maxEnd = $childPlan->end;
            }
            if($minBegin < $plan->begin and $minBegin != $futureTime) dao::$errors['begin'] = sprintf($this->lang->productplan->beginGreaterChildTip, $oldPlan->title, $plan->begin, $minBegin);
            if($maxEnd > $plan->end and $maxEnd != $futureTime) dao::$errors['end'] = sprintf($this->lang->productplan->endLessThanChildTip, $oldPlan->title, $plan->end, $maxEnd);
        }

        if(dao::isError()) return false;
        $plan = $this->loadModel('file')->processImgURL($plan, $this->config->productplan->editor->edit['id'], $this->post->uid);
        $this->dao->update(TABLE_PRODUCTPLAN)->data($plan)
            ->autoCheck()
            ->batchCheck($this->config->productplan->edit->requiredFields, 'notempty')
            ->checkIF(!$this->post->future && !empty($_POST['begin']) && !empty($_POST['end']), 'end', 'ge', $plan->begin)
            ->checkFlow()
            ->where('id')->eq((int)$planID)
            ->exec();
        if(dao::isError()) return false;

        if($plan->parent > 0) $this->updateParentStatus($plan->parent);
        if($oldPlan->parent > 0) $this->updateParentStatus($oldPlan->parent);

        if(!dao::isError())
        {
            $this->file->updateObjectID($this->post->uid, $planID, 'plan');
            if(!empty($plan->parent) and $parentPlan->parent == '0') $this->transferStoriesAndBugs($plan);
            return common::createChanges($oldPlan, $plan);
        }
    }

    /**
     * Update a plan's status.
     *
     * @param  int    $planID
     * @param  string $status doing|done|closed
     * @param  string $action started|finished|closed|activated
     * @access public
     * @return bool
     */
    public function updateStatus(int $planID, string $status = '', string $action = '')
    {
        $oldPlan = $this->getByID($planID);

        $closedReason = $this->post->closedReason ? $this->post->closedReason : '';
        $plan = $this->buildPlanByStatus($status, $closedReason);

        $this->dao->update(TABLE_PRODUCTPLAN)->data($plan)->where('id')->eq($planID)->exec();
        if(dao::isError()) return false;

        $changes  = common::createChanges($oldPlan, $plan);

        $comment = $this->post->comment ? $this->post->comment : '';
        $actionID = $this->loadModel('action')->create('productplan', $planID, $action, $comment);
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
     * Batch update plan.
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
            $parentID = $oldPlan->parent;
            /* Determine whether the begin and end dates of the parent plan and the child plan are correct. */
            if($parentID > 0)
            {
                $parent = zget($plans, $parentID, $this->getByID($parentID));
                if($parent->begin != $futureConfig and $plan->begin != $futureConfig and $plan->begin < $parent->begin) return dao::$errors[] = sprintf($this->lang->productplan->beginLessThanParentTip, $planID, $plan->begin, $parent->begin);
                if($parent->end != $futureConfig and $plan->end != $futureConfig and $plan->end > $parent->end)         return dao::$errors[] = sprintf($this->lang->productplan->endGreatThanParentTip, $planID, $plan->end, $parent->end);
            }
            elseif($parentID == -1 and $plan->begin != $futureConfig)
            {
                $childPlans = $this->dao->select('*')->from(TABLE_PRODUCTPLAN)->where('parent')->eq($planID)->andWhere('deleted')->eq(0)->fetchAll('id');
                $minBegin   = $plan->begin;
                $maxEnd     = $plan->end;
                foreach($childPlans as $childID => $childPlan)
                {
                    $childPlan = isset($plans[$childID]) ? $plans[$childID] : $childPlan;
                    if($childPlan->begin < $minBegin and $minBegin != $this->config->productplan->future) $minBegin = $childPlan->begin;
                    if($childPlan->end > $maxEnd and $maxEnd != $this->config->productplan->future)       $maxEnd   = $childPlan->end;
                }
                if($minBegin < $plan->begin and $minBegin != $futureConfig) return dao::$errors[] = sprintf($this->lang->productplan->beginGreaterChildTip, $planID, $plan->begin, $minBegin);
                if($maxEnd > $plan->end     and $maxEnd != $futureConfig)   return dao::$errors[] = sprintf($this->lang->productplan->endLessThanChildTip, $planID, $plan->end, $maxEnd);
            }

            $change = common::createChanges($oldPlan, $plan);
            if(empty($change)) continue;

            if($parentID > 0 and !isset($parents[$parentID])) $parents[$parentID] = $parentID;

            $this->dao->update(TABLE_PRODUCTPLAN)->data($plan)->autoCheck()->checkFlow()->where('id')->eq($planID)->exec();
            if(dao::isError()) return false;

            $actionID = $this->action->create('productplan', $planID, 'Edited');
            $this->action->logHistory($actionID, $change);

            $changes[$planID] = $change;
        }

        foreach($parents as $parent) $this->updateParentStatus($parent);
        if($changes) $this->unlinkOldBranch($changes);
        return true;
    }

    /**
     * Batch change the status of productplan.
     *
     * @param  array   $planIdList
     * @param  string  $status
     * @access public
     * @return array
     */
    public function batchChangeStatus(string $status): array|bool
    {
        $planIdList    = $this->post->planIdList;
        $closedReasons = $status == 'closed' ? $this->post->closedReason : array();

        if($status == 'closed')
        {
            foreach($closedReasons as $planID => $reason)
            {
                if(empty($reason)) return dao::$errors['closedReason[]'] = sprintf($this->lang->error->notempty, $this->lang->productplan->closedReason);
            }
        }

        $oldPlans = $this->getByIDList($planIdList);
        foreach($oldPlans as $planID => $oldPlan)
        {
            if($status == $oldPlan->status) continue;

            $plan = $this->buildPlanByStatus($status, $closedReasons[$planID]);

            $this->dao->update(TABLE_PRODUCTPLAN)->data($plan)->autoCheck()->where('id')->eq((int)$planID)->exec();
            if(dao::isError()) return false;

            if($oldPlan->parent > 0) $this->updateParentStatus($oldPlan->parent);

            $changes = common::createChanges($oldPlan, $plan);
            if(empty($changes)) continue;

            $comment  = isset($_POST['comment'][$planID]) ? $this->post->comment[$planID] : '';
            $actionID = $this->loadModel('action')->create('productplan', $planID, 'edited', $comment);
            $this->action->logHistory($actionID, $changes);
        }

        return true;
    }

    /**
     * Check date for plan.
     *
     * @param  object $plan
     * @param  string $begin
     * @param  string $end
     * @access public
     * @return void
     */
    public function checkDate4Plan($plan, $begin, $end)
    {
        if($plan->parent == -1)
        {
            $childPlans = $this->dao->select('*')->from(TABLE_PRODUCTPLAN)->where('parent')->eq($plan->id)->andWhere('deleted')->eq(0)->fetchAll();
            $minBegin   = $begin;
            $maxEnd     = $end;
            foreach($childPlans as $childPlan)
            {
                if($childPlan->begin < $minBegin and $minBegin != $this->config->productplan->future) $minBegin = $childPlan->begin;
                if($childPlan->end > $maxEnd and $maxEnd != $this->config->productplan->future) $maxEnd = $childPlan->end;
            }
            if($minBegin < $begin and $begin != $this->config->productplan->future) dao::$errors['begin'] = sprintf($this->lang->beginGreaterChild, $minBegin);
            if($maxEnd > $end and $end != $this->config->productplan->future) dao::$errors['end'] = sprintf($this->lang->endLessThanChild, $maxEnd);
        }
        elseif($plan->parent > 0)
        {
            $parentPlan = $this->getByID($plan->parent);
            if($begin < $parentPlan->begin and $parentPlan->begin != $this->config->productplan->future) dao::$errors['begin'] = sprintf($this->lang->productplan->beginLessThanParent, $parentPlan->begin);
            if($end > $parentPlan->end and $parentPlan->end != $this->config->productplan->future) dao::$errors['end'] = sprintf($this->lang->productplan->endGreatThanParent, $parentPlan->end);
        }
    }

    /**
     * Change parent field by planID.
     *
     * @param  int    $planID
     * @access public
     * @return void
     */
    public function changeParentField($planID)
    {
        $plan = $this->getById($planID);
        if($plan->parent <= 0) return true;

        $childCount = count($this->getChildren($plan->parent));
        $parent     = $childCount == 0 ? '0' : '-1';

        $parentPlan = $this->dao->select('*')->from(TABLE_PRODUCTPLAN)->where('id')->eq($plan->parent)->andWhere('deleted')->eq(0)->fetch();
        if($parentPlan)
        {
            $this->dao->update(TABLE_PRODUCTPLAN)->set('parent')->eq($parent)->where('id')->eq((int)$plan->parent)->exec();
        }
        else
        {
            $this->dao->update(TABLE_PRODUCTPLAN)->set('parent')->eq('0')->where('id')->eq((int)$planID)->exec();
        }
    }

    /**
     * Link stories.
     *
     * @param  int    $planID
     * @access public
     * @return void
     */
    public function linkStory($planID)
    {
        $this->loadModel('story');
        $this->loadModel('action');

        $stories = $this->story->getByList($this->post->stories);

        foreach($this->post->stories as $storyID)
        {
            if(!isset($stories[$storyID])) continue;

            $story = $stories[$storyID];
            if(strpos(",$story->plan,", ",{$planID},") !== false) continue;

            /* Update the plan linked with the story and the order of the story in the plan. */
            $this->dao->update(TABLE_STORY)->set("plan")->eq($planID)->where('id')->eq((int)$storyID)->exec();

            $this->story->updateStoryOrderOfPlan($storyID, $planID, $story->plan);

            $this->action->create('story', $storyID, 'linked2plan', '', $planID);
            $this->story->setStage($storyID);
        }

        $this->action->create('productplan', $planID, 'linkstory', '', implode(',', $this->post->stories));
    }

    /**
     * Unlink story
     *
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function unlinkStory($storyID, $planID)
    {
        $story = $this->dao->findByID($storyID)->from(TABLE_STORY)->fetch();
        $plans = array_unique(explode(',', trim(str_replace(",$planID,", ',', ',' . trim($story->plan) . ','). ',')));
        $this->dao->update(TABLE_STORY)->set('plan')->eq(implode(',', $plans))->where('id')->eq((int)$storyID)->exec();

        /* Delete the story in the sort of the plan. */
        $this->loadModel('story');
        $this->story->updateStoryOrderOfPlan($storyID, '', $planID);

        $this->story->setStage($storyID);
        $this->loadModel('action')->create('story', $storyID, 'unlinkedfromplan', '', $planID);
    }

    /**
     * Link bugs.
     *
     * @param  int    $planID
     * @access public
     * @return void
     */
    public function linkBug($planID)
    {
        $this->loadModel('story');
        $this->loadModel('action');

        $bugs = $this->loadModel('bug')->getByIdList($this->post->bugs);
        foreach($this->post->bugs as $bugID)
        {
            if(!isset($bugs[$bugID])) continue;

            $bug = $bugs[$bugID];
            if($bug->plan == $planID) continue;

            $this->dao->update(TABLE_BUG)->set('plan')->eq($planID)->where('id')->eq((int)$bugID)->exec();
            $this->action->create('bug', $bugID, 'linked2plan', '', $planID);
        }

        $this->action->create('productplan', $planID, 'linkbug', '', implode(',', $this->post->bugs));
    }

    /**
     * Unlink bug.
     *
     * @param  int    $bugID
     * @access public
     * @return void
     */
    public function unlinkBug($bugID)
    {
        $planID = $this->dao->findByID($bugID)->from(TABLE_BUG)->fetch('plan');
        $this->dao->update(TABLE_BUG)->set('plan')->eq(0)->where('id')->eq((int)$bugID)->exec();
        $this->loadModel('action')->create('bug', $bugID, 'unlinkedfromplan', '', $planID);
    }

    /**
     * Link project.
     *
     * @param  int    $projectID
     * @param  array  $newPlans
     * @access public
     * @return void
     */
    public function linkProject($projectID, $newPlans)
    {
        $this->loadModel('execution');
        foreach($newPlans as $planID)
        {
            $planStories = $planProducts = array();
            $planStory   = $this->loadModel('story')->getPlanStories($planID);
            if(!empty($planStory))
            {
                $projectProducts = $this->loadModel('project')->getBranchesByProject($projectID);

                foreach($planStory as $id => $story)
                {
                    $projectBranches = zget($projectProducts, $story->product, array());
                    if($story->status != 'active' or (!empty($story->branch) and !empty($projectBranches) and !isset($projectBranches[$story->branch])))
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
     * Reorder for children plans.
     *
     * @param  array    $plans
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
     * Judge an action is clickable or not.
     *
     * @param  object $plan
     * @param  string $action
     * @param  string $module
     * @access public
     * @return void
     */
    public static function isClickable($plan, $action, $module = 'productplan')
    {
        $action    = strtolower($action);
        $clickable = commonModel::hasPriv($module, $action);
        if(!$clickable) return false;

        switch($action)
        {
            case 'create' :
                if($plan->parent > 0 or strpos('done,closed', $plan->status) !== false) return false;
                break;
            case 'start' :
                if($plan->status != 'wait' or $plan->parent < 0) return false;
                break;
            case 'finish' :
                if($plan->status != 'doing' or $plan->parent < 0) return false;
                break;
            case 'close' :
                if($plan->status == 'closed' or $plan->parent < 0) return false;
                break;
            case 'activate' :
                if($plan->status == 'wait' or $plan->status == 'doing' or $plan->parent < 0) return false;
                break;
            case 'delete' :
                if($plan->parent < 0) return false;
                break;
        }

        return true;
    }

    /**
     * Build operate menu.
     *
     * @param  object $plan
     * @access public
     * @return string
     */
    public function buildOperateMenu($plan)
    {
        $params = "planID=$plan->id";

        $canStart       = common::hasPriv('productplan', 'start')    && static::isClickable($plan, 'start');
        $canFinish      = common::hasPriv('productplan', 'finish')   && static::isClickable($plan, 'finish');
        $canClose       = common::hasPriv('productplan', 'close')    && static::isClickable($plan, 'close');
        $canActivate    = common::hasPriv('productplan', 'activate') && static::isClickable($plan, 'activate');
        $canEdit        = common::hasPriv('productplan', 'edit');
        $canCreateChild = common::hasPriv('productplan', 'create') && static::isClickable($plan, 'create');
        $canDelete      = common::hasPriv('productplan', 'delete') && static::isClickable($plan, 'delete');

        $menu = array();
        if($canStart)       $menu[] = array('icon' => 'play',    'class' => 'ghost', 'text' => $this->lang->productplan->startAB,    'data-url' => helper::createLink('productplan', 'start', $params . '&confirm=yes'), 'data-action' => 'start', 'onclick' => 'ajaxConfirmLoad(this)');
        if($canFinish)      $menu[] = array('icon' => 'checked', 'class' => 'ghost', 'text' => $this->lang->productplan->finishAB,   'data-url' => helper::createLink('productplan', 'finish', $params . '&confirm=yes'), 'data-action' => 'finish', 'onclick' => 'ajaxConfirmLoad(this)');
        if($canClose)       $menu[] = array('icon' => 'off',     'class' => 'ghost', 'text' => $this->lang->productplan->closeAB,    'url' => helper::createLink('productplan', 'close', $params, '', true), 'data-toggle' => 'modal');
        if($canActivate)    $menu[] = array('icon' => 'magic',   'class' => 'ghost', 'text' => $this->lang->productplan->activateAB, 'data-url' => helper::createLink('productplan', 'activate', $params . '&confirm=yes'), 'data-action' => 'activate', 'onclick' => 'ajaxConfirmLoad(this)');
        if($canCreateChild) $menu[] = array('icon' => 'split',   'class' => 'ghost', 'text' => $this->lang->productplan->children,   'url' => helper::createLink('productplan', 'create', "product={$plan->product}&branch={$plan->branch}&parent={$plan->id}"));
        if($canEdit)        $menu[] = array('icon' => 'edit',    'class' => 'ghost', 'text' => $this->lang->edit,   'url' => helper::createLink('productplan', 'edit', $params));
        if($canDelete)      $menu[] = array('icon' => 'trash',   'class' => 'ghost', 'text' => $this->lang->delete, 'data-url' => helper::createLink('productplan', 'delete', $params . '&confirm=yes'), 'data-action' => 'delete', 'onclick' => 'ajaxConfirmLoad(this)');

        return $menu;
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
     * Get plan list by ids.
     *
     * @param  array  $planIds
     * @param  bool   $order
     * @access public
     * @return array
     */
    public function getListByIds($planIds, $order = false)
    {
        $plans = $this->dao->select('*')->from(TABLE_PRODUCTPLAN)
            ->where('id')->in($planIds)
            ->orderBy('begin desc')
            ->fetchAll('id');

        if($order) $plans = $this->relationBranch($plans);

        return $plans;
    }

    /**
     * Get the total count of parent plan, child plan and indepentdent plan.
     *
     * @param  array  $planList
     * @access public
     * @return string
     */
    public function getSummary(array $planList): string
    {
        $totalParent = $totalChild = $totalIndependent = 0;

        foreach($planList as $plan)
        {
            if($plan->parent == -1) $totalParent ++;
            if($plan->parent > 0)   $totalChild ++;
            if($plan->parent == 0)  $totalIndependent ++;
        }

        return sprintf($this->lang->productplan->summary, count($planList), $totalParent, $totalChild, $totalIndependent);
    }

    /**
     * Build action button list.
     *
     * Copied form the 'buildOperateMenu' funciton.
     *
     * @param  object $plan
     * @param  string $type
     * @access public
     * @return array
     */
    public function buildActionBtnList(object $plan, string $type = 'view'): array
    {
        $params  = "planID=$plan->id";
        $actions = [];

        $canStart       = common::hasPriv('productplan', 'start');
        $canFinish      = common::hasPriv('productplan', 'finish');
        $canClose       = common::hasPriv('productplan', 'close');
        $canCreateExec  = common::hasPriv('execution',   'create');
        $canLinkStory   = common::hasPriv('productplan', 'linkStory', $plan);
        $canLinkBug     = common::hasPriv('productplan', 'linkBug', $plan);
        $canEdit        = common::hasPriv('productplan', 'edit');
        $canCreateChild = common::hasPriv('productplan', 'create');
        $canDelete      = common::hasPriv('productplan', 'delete');

        $actions[] = $this->buildActionBtn('productplan', 'start',    $params, $plan, $type, 'start',    'hiddenwin', '',       false, '', $this->lang->productplan->startAB);
        $actions[] = $this->buildActionBtn('productplan', 'finish',   $params, $plan, $type, 'finish',   'hiddenwin', '',       false, '', $this->lang->productplan->finishAB);
        $actions[] = $this->buildActionBtn('productplan', 'close',    $params, $plan, $type, 'close',    'hiddenwin', 'iframe', true,  '', $this->lang->productplan->closeAB);
        $actions[] = $this->buildActionBtn('productplan', 'activate', $params, $plan, $type, 'activate', 'hiddenwin', '',       false, '', $this->lang->productplan->activateAB);

        if($type == 'browse')
        {
            $canClickExecution = true;
            if($plan->parent < 0 || $plan->expired || in_array($plan->status, array('done', 'closed')) || !common::hasPriv('execution', 'create', $plan))
            {
                $canClickExecution = false;
            }

            if($canClickExecution)
            {
                $product     = $this->loadModel('product')->getById($plan->product);
                $branchList  = $this->loadModel('branch')->getList($plan->product, 0, 'all');

                $branchStatusList = array();
                foreach($branchList as $productBranch) $branchStatusList[$productBranch->id] = $productBranch->status;

                if($product->type != 'normal')
                {
                    $branchStatus = isset($branchStatusList[$plan->branch]) ? $branchStatusList[$plan->branch] : '';
                    if($branchStatus == 'closed') $canClickExecution = false;
                }
            }

            if($canClickExecution)
            {
                $actions[] = $this->buildActionBtn('none', 'plus', $params, $plan, $type, '', '', '', false, '', $this->lang->productplan->createExecution);
            }
            elseif($canCreateExec)
            {
                $actions[] = $this->buildActionBtn('none', 'plus', $params, $plan, $type, '', '', '', false, 'disabled', $this->lang->productplan->createExecution);
            }

            if($type == 'browse' and in_array(true, array($canStart, $canFinish, $canClose, $canCreateExec)) and in_array(true, array($canLinkStory, $canLinkBug, $canEdit, $canCreateChild, $canDelete)))
            {
                $actions[] = $this->buildActionBtn('none', 'divider', $params, $plan, $type);
            }

            if($canLinkStory and $plan->parent >= 0)
            {
                $actions[] = $this->buildActionBtn($this->app->rawModule, 'view', "{$params}&type=story&orderBy=id_desc&link=true", $plan, $type, 'link', '', '', '', '', $this->lang->productplan->linkStory);
            }
            elseif($canLinkStory)
            {
                $actions[] = $this->buildActionBtn('none', 'link', $params, $plan, $type, '', '', '', false, 'disabled', $this->lang->productplan->linkStory);
            }

            if($canLinkBug and $plan->parent >= 0)
            {
                $actions[] = $this->buildActionBtn($this->app->rawModule, 'view', "{$params}&type=bug&orderBy=id_desc&link=true", $plan, $type, 'bug', '', '', '', '',  $this->lang->productplan->linkBug);
            }
            elseif($canLinkBug)
            {
                $actions[] = $this->buildActionBtn('none', 'bug', $params, $plan, $type, '', '', '', false, 'disabled', $this->lang->productplan->linkBug);
            }

            $actions[] = $this->buildActionBtn($this->app->rawModule, 'edit', $params, $plan, $type);
        }

        if($canCreateChild) $actions[] = $this->buildActionBtn($this->app->rawModule, 'create', "product={$plan->product}&branch={$plan->branch}&parent={$plan->id}", $plan, $type, 'split', '', '', '', '', $this->lang->productplan->children);

        if($type == 'browse')
        {
            if(in_array(true, array($canLinkStory, $canLinkBug, $canEdit, $canCreateChild)) and $canDelete)
            {
                $actions[] = $this->buildActionBtn('none', 'divider', $params, $plan, $type);
            }
            $actions[] = $this->buildActionBtn('productplan', 'delete', "{$params}&confirm=no", $plan, $type, 'trash', 'hiddenwin', '', '', $this->lang->productplan->delete);
        }

        if($type == 'view')
        {
            $actions[] = $this->buildActionBtn('none', 'divider', $params, $plan, $type);
            /* TODO attach the buttons of workflow module. */
            $actions[] = $this->buildActionBtn('none', 'divider', $params, $plan, $type);

            /* Refactor the original business loggic. */
            $editClickable   = $this->buildActionBtn($this->app->rawModule, 'edit',   $params, $plan, $type, '', '', '', '', '', $this->lang->edit, false);
            $deleteClickable = $this->buildActionBtn('productplan', 'delete', $params, $plan, $type, 'trash', '', '', '', '', $this->lang->delete, false);
            if($canEdit and $editClickable) $actions[] = $editClickable;
            if($canDelete and $deleteClickable) $actions[] = $deleteClickable;
        }

        return $actions;
    }

    /**
     * 构建操作按钮。
     * Build action button.
     *
     * Copied from the buildMenu of model class, to refactor it.
     * Ensure that all the original parameters are kept to maintain compatibility with the 'buildMenu' method.
     *
     * @param  string $moduleName
     * @param  string $methodName
     * @param  string $params
     * @param  object $data
     * @param  string $type
     * @param  string $icon
     * @param  string $target
     * @param  string $class
     * @param  bool   $onlyBody
     * @param  string $misc
     * @param  string $title
     * @access private
     * @return array
     */
    private function buildActionBtn($moduleName, $methodName, $params, $data, $type = 'view', $icon = '', $target = '', $class = '', $onlyBody = false, $misc = '' , $title = ''): array
    {
        if($moduleName === 'none')
        {
            /* Special action button, with customize business logic of prductplan module. */
            return array
            (
                'name'     => $methodName,
                'hint'     => $title,
                'disabled' => !empty($misc),
            );
        }

        if(str_contains($moduleName, '.')) [$appName, $moduleName] = explode('.', $moduleName);

        if(str_contains($methodName, '_') && strpos($methodName, '_') > 0) [$module, $method] = explode('_', $methodName);

        if(empty($module)) $module = $moduleName;
        if(empty($method)) $method = $methodName;

        $isClick = true;
        if(method_exists($this, 'isClickable')) $isClick = $this->isClickable($data, $method, $module);

        $link       = helper::createLink($module, $method, $params, '', $onlyBody);
        $actionName = $icon == 'split' ? 'split' : $method;
        if(isset($this->config->productplan->dtable->fieldList['actions']['actionsMap'][$actionName]['url']))
        {
            $link = $this->config->productplan->dtable->fieldList['actions']['actionsMap'][$actionName]['url'];
        }

        global $lang;
        /* Set the icon title, try search the $method defination in $module's lang or $common's lang. */
        if(empty($title))
        {
            $title = $method;
            if($method == 'create' and $icon == 'copy') $method = 'copy';
            if(isset($lang->$method) and is_string($lang->$method)) $title = $lang->$method;
            if((isset($lang->$module->$method) or $this->app->loadLang($module)) and isset($lang->$module->$method))
            {
                $title = $method == 'report' ? $lang->$module->$method->common : $lang->$module->$method;
            }
            if($icon == 'toStory')   $title  = $lang->bug->toStory;
            if($icon == 'createBug') $title  = $lang->testtask->createBug;
        }

        /* set the class. */
        if(!$icon)
        {
            $icon = isset($lang->icons[$method]) ? $lang->icons[$method] : $method;
        }

        return array
        (
            'name'     => $icon ? $icon : $methodName,
            'url'      => !$isClick ? null : $link,
            'hint'     => $title,
            'disabled' => !$isClick
        );
    }

    /**
     * Unlink story and bug when edit branch of plan.
     * @param  int    $planID
     * @param  int    $oldBranch
     * @access protected
     * @return void
     */
    public function unlinkOldBranch($changes)
    {
        foreach($changes as $planID => $changes)
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
                    if($story->branch and str_contains(",$newBranch,", ",$story->branch,")) $this->unlinkStory($storyID, $planID);
                }

                foreach($planBugs as $bugID => $bug)
                {
                    if($bug->branch and str_contains(",$newBranch,", ",$bug->branch,")) $this->unlinkBug($bugID, $planID);
                }
            }
        }
    }
}
