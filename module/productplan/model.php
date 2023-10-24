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
     * Get plan by id.
     *
     * @param  int    $planID
     * @param  bool   $setImgSize
     * @access public
     * @return object
     */
    public function getByID($planID, $setImgSize = false)
    {
        $plan = $this->dao->findByID((int)$planID)->from(TABLE_PRODUCTPLAN)->fetch();
        if(!$plan) return false;

        $plan = $this->loadModel('file')->replaceImgURL($plan, 'desc');
        if($setImgSize) $plan->desc = $this->file->setImgSize($plan->desc);
        return $plan;
    }

    /**
     * Get plans by idList
     *
     * @param  int    $planIDList
     * @access public
     * @return array
     */
    public function getByIDList($planIDList)
    {
        return $this->dao->select('*')->from(TABLE_PRODUCTPLAN)->where('id')->in($planIDList)->orderBy('begin desc')->fetchAll('id');
    }

    /**
     * Get last plan.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  int    $parent
     * @access public
     * @return object
     */
    public function getLast($productID, $branch = '', $parent = 0)
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
            ->beginIF($parent <= 0)->andWhere('parent')->le((int)$parent)->fi()
            ->beginIF($parent > 0)->andWhere('parent')->eq((int)$parent)->fi()
            ->andWhere('product')->eq((int)$productID)
            ->andWhere('end')->ne($this->config->productplan->future)
            ->beginIF($branch !== '' and !empty($branchQuery))->andWhere($branchQuery)->fi()
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
        if(!empty($queryID))
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set('productplanQuery', $query->sql);
                $this->session->set('productplanForm', $query->form);
            }
            else
            {
                $this->session->set('productplanQuery', ' 1 = 1');
            }
        }
        else
        {
            if($this->session->productplanQuery == false) $this->session->set('productplanQuery', ' 1 = 1');
        }

        $productplanQuery = $this->session->productplanQuery;

        $date     = date('Y-m-d');
        $products = (strpos($param, 'noproduct') !== false and empty($product)) ? $this->loadModel('product')->getList() : array(0);
        $plans    = $this->dao->select('*')->from(TABLE_PRODUCTPLAN)
            ->where('deleted')->eq(0)
            ->beginIF(strpos($param, 'noproduct') === false or !empty($product))->andWhere('product')->eq($product)->fi()
            ->beginIF(strpos($param, 'noproduct') !== false and empty($product))->andWhere('product')->in(array_keys($products))->fi()
            ->beginIF(!empty($branch) and $branch != 'all')->andWhere('branch')->eq($branch)->fi()
            ->beginIF(strpos(',all,undone,bySearch,review,', ",$browseType,") === false)->andWhere('status')->eq($browseType)->fi()
            ->beginIF($browseType == 'undone')->andWhere('status')->in('wait,doing')->fi()
            ->beginIF($browseType == 'bySearch')->andWhere($productplanQuery)->fi()
            ->beginIF($browseType == 'review')->andWhere("FIND_IN_SET('{$this->app->user->account}', reviewers)")->fi()
            ->beginIF(strpos($param, 'skipparent') !== false)->andWhere('parent')->ne(-1)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        if(!empty($plans))
        {
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
                        $planStory = new stdclass();
                        $planStory->plan = $plan->id;
                        $planStory->story = $storyID;
                        $planStory->order = $order ++;
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
        $planPairs = $this->dao->select("id,title")->from(TABLE_PRODUCTPLAN)
            ->where('product')->eq($productID)
            ->andWhere('parent')->le(0)
            ->andWhere('deleted')->eq(0)
            ->beginIF($exclude)->andWhere('status')->notin($exclude)
            ->orderBy('id_desc')
            ->fetchPairs();

        return $planPairs;
    }

    /**
     * Get plan pairs.
     *
     * @param  array|int        $product
     * @param  int|string|array $branch
     * @param  string           $param unexpired|noclosed
     * @param  bool             $skipParent
     * @access public
     * @return array
     */
    public function getPairs($product = 0, $branch = '', $param = '', $skipParent = false)
    {
        $this->app->loadLang('branch');

        $date = date('Y-m-d');

        $branchQuery = '';
        if($branch !== '' and $branch != 'all')
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

        $plans = $this->dao->select('t1.id,t1.title,t1.parent,t1.begin,t1.end,t3.type as productType,t1.branch')->from(TABLE_PRODUCTPLAN)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t3.id=t1.product')
            ->where('t1.product')->in($product)
            ->andWhere('t1.deleted')->eq(0)
            ->beginIF(!empty($branchQuery))->andWhere($branchQuery)->fi()
            ->beginIF(strpos($param, 'unexpired') !== false)->andWhere('t1.end')->ge($date)->fi()
            ->beginIF(strpos($param, 'noclosed')  !== false)->andWhere('t1.status')->ne('closed')->fi()
            ->orderBy('t1.begin desc')
            ->fetchAll('id');

        $plans     = $this->reorder4Children($plans);
        $plans     = $this->relationBranch($plans);
        $planPairs = array();
        foreach($plans as $plan)
        {
            if($skipParent and $plan->parent == '-1') continue;
            if($plan->parent > 0 and isset($plans[$plan->parent])) $plan->title = $plans[$plan->parent]->title . ' /' . $plan->title;
            $planPairs[$plan->id] = $plan->title . " [{$plan->begin} ~ {$plan->end}]";
            if($plan->begin == $this->config->productplan->future and $plan->end == $this->config->productplan->future) $planPairs[$plan->id] = $plan->title . ' ' . $this->lang->productplan->future;
            if($plan->productType != 'normal') $planPairs[$plan->id] = $planPairs[$plan->id] . ' / ' . ($plan->branchName ? $plan->branchName : $this->lang->branch->main);
        }
        return array('' => '') + $planPairs;
    }

    /**
     * Get plan pairs for story.
     *
     * @param  array|int    $product
     * @param  int          $branch
     * @param  string       $param skipParent|withMainPlan|unexpired|noclosed
     * @access public
     * @return array
     */
    public function getPairsForStory($product = 0, $branch = '', $param = '')
    {
        $date   = date('Y-m-d');
        $param  = strtolower($param);
        $branch = strpos($param, 'withmainplan') !== false ? "0,$branch" : $branch;

        $branchQuery = '';
        if($branch !== '' and $branch != 'all')
        {
            $branchQuery .= '(';
            $branchCount = count(explode(',', $branch));
            foreach(explode(',', $branch) as $index => $branchID)
            {
                $branchQuery .= "FIND_IN_SET('$branchID', branch)";
                if($index < $branchCount - 1) $branchQuery .= ' OR ';
            }
            $branchQuery .= ')';
        }

        $plans  = $this->dao->select('id,title,parent,begin,end')->from(TABLE_PRODUCTPLAN)
            ->where('product')->in($product)
            ->andWhere('deleted')->eq(0)
            ->beginIF(strpos($param, 'unexpired') !== false)->andWhere('end')->ge($date)->fi()
            ->beginIF(strpos($param, 'noclosed') !== false)->andWhere('status')->ne('closed')->fi()
            ->beginIF($branch !== 'all' and $branch !== '' and !empty($branchQuery))->andWhere($branchQuery)->fi()
            ->orderBy('begin desc')
            ->fetchAll('id');

        $plans       = $this->reorder4Children($plans);
        $planPairs   = array();
        $parentTitle = array();
        foreach($plans as $plan)
        {
            if($plan->parent == '-1')
            {
                $parentTitle[$plan->id] = $plan->title;
                if(strpos($param, 'skipparent') !== false) continue;
            }
            if($plan->parent > 0 and isset($parentTitle[$plan->parent])) $plan->title = $parentTitle[$plan->parent] . ' /' . $plan->title;
            $planPairs[$plan->id] = $plan->title . " [{$plan->begin} ~ {$plan->end}]";
            if($plan->begin == $this->config->productplan->future and $plan->end == $this->config->productplan->future) $planPairs[$plan->id] = $plan->title . ' ' . $this->lang->productplan->future;
        }

        return array('' => '') + $planPairs;
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
        return array('' => '') + $planPairs;
    }

    /**
     * Get plan group by product id list.
     *
     * @param  string|array $products
     * @param  string       $param skipParent|unexpired
     * @param  string       $field name
     * @param  string       $orderBy id_desc|begin_desc
     * @access public
     * @return array
     */
    public function getGroupByProduct($products = '', $param = '', $field = 'name', $orderBy = 'id_desc')
    {
        $date  = date('Y-m-d');
        $param = strtolower($param);
        $plans = $this->dao->select('t1.*,t2.type as productType')->from(TABLE_PRODUCTPLAN)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t2.id=t1.product')
            ->where('t1.deleted')->eq(0)
            ->beginIF($products)->andWhere('t1.product')->in($products)->fi()
            ->beginIF(strpos($param, 'unexpired') !== false)->andWhere('t1.end')->ge($date)->fi()
            ->orderBy('t1.' . $orderBy)
            ->fetchAll('id');

        if(!empty($plans) and $field == 'name') $plans = $this->reorder4Children($plans);

        $plans = $this->relationBranch($plans);

        $parentTitle = array();
        $planGroup   = array();
        foreach($plans as $plan)
        {
            foreach(explode(',', $plan->branch) as $branch)
            {
                if(!isset($planGroup[$plan->product][$branch])) $planGroup[$plan->product][$branch] = array('' => '');

                if($plan->parent == '-1' and strpos($param, 'skipparent') !== false) continue 2;

                if($field == 'name')
                {
                    if($plan->parent > 0 and isset($plans[$plan->parent])) $plan->title = $plans[$plan->parent]->title . ' /' . $plan->title;
                    $planGroup[$plan->product][$branch][$plan->id] = $plan->title . " [{$plan->begin} ~ {$plan->end}]";
                    if($plan->begin == $this->config->productplan->future and $plan->end == $this->config->productplan->future) $planGroup[$plan->product][$branch][$plan->id] = $plan->title . ' ' . $this->lang->productplan->future;
                    if($plan->productType != 'normal') $planGroup[$plan->product][$branch][$plan->id] = $planGroup[$plan->product][$branch][$plan->id] . ' / ' . ($plan->branchName ? $plan->branchName : $this->lang->branch->main);
                }
                else
                {
                    $planGroup[$plan->product][$branch][$plan->id] = $plan;
                }
            }
        }
        return $planGroup;
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
        if($branches !== '')
        {
            $branchQuery .= '(';
            if(!is_array($branches)) $branches = explode(',', $branches);
            foreach($branches as $branchID)
            {
                $branchQuery .= "CONCAT(',', branch, ',') LIKE '%,$branchID,%'";
                if($branchID != end($branches)) $branchQuery .= ' OR ';
            }
            $branchQuery .= " OR `branch` IN ('" . implode("','", $branches) . "')";
            $branchQuery .= ')';
        }

        $date  = date('Y-m-d');
        $param = strtolower($param);
        $plans = $this->dao->select('parent,branch,id,title,begin,end')->from(TABLE_PRODUCTPLAN)
            ->where('product')->eq($productID)
            ->andWhere('deleted')->eq(0)
            ->beginIF(!empty($branchQuery))->andWhere($branchQuery)->fi()
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
     * Create a plan.
     *
     * @access public
     * @return int
     */
    public function create()
    {
        $plan = fixer::input('post')->stripTags($this->config->productplan->editor->create['id'], $this->config->allowedTags)
            ->setIF($this->post->future || empty($_POST['begin']), 'begin', $this->config->productplan->future)
            ->setIF($this->post->future || empty($_POST['end']), 'end', $this->config->productplan->future)
            ->setDefault('createdBy', $this->app->user->account)
            ->setDefault('createdDate', helper::now())
            ->setDefault('branch,order', 0)
            ->join('branch', ',')
            ->remove('delta,uid,future')
            ->get();

        $product = $this->loadModel('product')->getByID($plan->product);
        if($product->type != 'normal' and !isset($_POST['branch']))
        {
            $this->lang->product->branch = sprintf($this->lang->product->branch, $this->lang->product->branchName[$product->type]);
            dao::$errors['branch'] = sprintf($this->lang->error->notempty, $this->lang->product->branch);
        }

        if($plan->parent > 0)
        {
            $parentPlan = $this->getByID($plan->parent);
            if($parentPlan->begin != $this->config->productplan->future)
            {
                if($plan->begin < $parentPlan->begin) dao::$errors['begin'] = sprintf($this->lang->productplan->beginLetterParent, $parentPlan->begin);
            }
            if($parentPlan->end != $this->config->productplan->future)
            {
                if($plan->end !== $this->config->productplan->future and $plan->end > $parentPlan->end) dao::$errors['end'] = sprintf($this->lang->productplan->endGreaterParent, $parentPlan->end);
            }
        }

        if(!$this->post->future and strpos($this->config->productplan->create->requiredFields, 'begin') !== false and empty($_POST['begin']))
        {
            dao::$errors['begin'] = sprintf($this->lang->error->notempty, $this->lang->productplan->begin);
        }
        if(!$this->post->future and strpos($this->config->productplan->create->requiredFields, 'end') !== false and empty($_POST['end']))
        {
            dao::$errors['end'] = sprintf($this->lang->error->notempty, $this->lang->productplan->end);
        }
        if(dao::isError()) return false;

        $plan = $this->loadModel('file')->processImgURL($plan, $this->config->productplan->editor->create['id'], $this->post->uid);
        $this->dao->insert(TABLE_PRODUCTPLAN)->data($plan)
            ->autoCheck()
            ->batchCheck($this->config->productplan->create->requiredFields, 'notempty')
            ->checkIF(!$this->post->future && !empty($_POST['begin']) && !empty($_POST['end']), 'end', 'ge', $plan->begin)
            ->checkFlow()
            ->exec();
        if(!dao::isError())
        {
            $planID = $this->dao->lastInsertID();
            $this->file->updateObjectID($this->post->uid, $planID, 'plan');
            $this->loadModel('score')->create('productplan', 'create', $planID);
            if(!empty($plan->parent) and $parentPlan->parent == '0')
            {
                $plan->id = $planID;
                $this->transferStoriesAndBugs($plan);
            }
            return $planID;
        }
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
            ->join('branch', ',')
            ->add('id', $planID)
            ->remove('delta,uid,future')
            ->get();

        $plan = $this->buildPlanByStatus($this->post->status, '', $plan);

        $product = $this->loadModel('product')->getByID($oldPlan->product);
        if($product->type != 'normal')
        {
            if(!isset($_POST['branch']))
            {
                $this->lang->product->branch = sprintf($this->lang->product->branch, $this->lang->product->branchName[$product->type]);
                dao::$errors['branch'] = sprintf($this->lang->error->notempty, $this->lang->product->branch);
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

        $parentPlan = $this->getByID($plan->parent);
        $futureTime = $this->config->productplan->future;

        if($plan->parent > 0)
        {
            if($parentPlan->begin !== $futureTime)
            {
                if($plan->begin < $parentPlan->begin) dao::$errors['begin'] = sprintf($this->lang->productplan->beginLetterParent, $parentPlan->begin);
            }
            if($parentPlan->end !== $futureTime)
            {
                if($plan->end !== $futureTime and $plan->end > $parentPlan->end) dao::$errors['end'] = sprintf($this->lang->productplan->endGreaterParent, $parentPlan->end);
            }
        }
        elseif($oldPlan->parent == -1 and ($plan->begin != $futureTime or $plan->end != $futureTime))
        {
            $childPlans = $this->getChildren($planID);
            $minBegin   = $plan->begin;
            $maxEnd     = $plan->end;
            foreach($childPlans as $childID => $childPlan)
            {
                $childPlan = isset($plans[$childID]) ? $plans[$childID] : $childPlan;
                if($childPlan->begin < $minBegin) $minBegin = $childPlan->begin;
                if($childPlan->end > $maxEnd) $maxEnd = $childPlan->end;
            }
            if($minBegin < $plan->begin and $minBegin != $futureTime) dao::$errors['begin'] = sprintf($this->lang->productplan->beginGreaterChildTip, $oldPlan->title, $plan->begin, $minBegin);
            if($maxEnd > $plan->end and $maxEnd != $futureTime) dao::$errors['end'] = sprintf($this->lang->productplan->endLetterChildTip, $oldPlan->title, $plan->end, $maxEnd);
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
    public function updateStatus($planID, $status = '', $action = '')
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
     * Update a parent plan's status.
     *
     * @param  int    $parentID
     * @access public
     * @return bool
     */
    public function updateParentStatus($parentID)
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
        if(count($childStatus) == 1 and isset($childStatus['wait'])) return;
        elseif(count($childStatus) == 1 and isset($childStatus['closed']))
        {
            if($oldPlan->status != 'closed')
            {
                $status       = 'closed';
                $parentAction = 'closedbychild';
            }
        }
        elseif(!isset($childStatus['wait']) and !isset($childStatus['doing']))
        {
            if($oldPlan->status != 'done')
            {
                $status       = 'done';
                $parentAction = 'finishedbychild';
            }
        }
        else
        {
            if($oldPlan->status != 'doing')
            {
                $status       = 'doing';
                $parentAction = $this->app->rawMethod == 'create' ? 'createchild' : 'activatedbychild';
            }
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
     * @access public
     * @return array
     */
    public function batchUpdate($productID)
    {
        $data     = fixer::input('post')->get();
        $oldPlans = $this->getByIDList($data->id);

        $this->app->loadClass('purifier', true);
        $config   = HTMLPurifier_Config::createDefault();
        $config->set('Cache.DefinitionImpl', null);
        $purifier = new HTMLPurifier($config);

        $plans = array();
        $extendFields = $this->getFlowExtendFields();
        $product      = $this->loadModel('product')->getByID($productID);
        foreach($data->id as $planID)
        {
            if($product->type != 'normal' and $oldPlans[$planID]->parent != '-1' and !isset($data->branch[$planID]))
            {
                $this->lang->product->branch = sprintf($this->lang->product->branch, $this->lang->product->branchName[$product->type]);
                return helper::end(js::error(sprintf($this->lang->error->notempty, $this->lang->product->branch)));
            }

            $isFuture = isset($data->future[$planID]) ? true : false;
            if(isset($data->status[$planID]) and $data->status[$planID] != 'wait') $isFuture = false;

            $plan = new stdclass();
            $plan->branch = isset($data->branch[$planID]) ? join(',', $data->branch[$planID]) : $oldPlans[$planID]->branch;
            $plan->title  = $data->title[$planID];
            $plan->begin  = isset($data->begin[$planID]) ? $data->begin[$planID] : '';
            $plan->end    = isset($data->end[$planID]) ? $data->end[$planID] : '';
            $plan->status = isset($data->status[$planID]) ? $data->status[$planID] : $oldPlans[$planID]->status;
            $plan->parent = $oldPlans[$planID]->parent;
            $plan->id     = $planID;

            if(empty($plan->title)) return helper::end(js::alert(sprintf($this->lang->productplan->errorNoTitle, $planID)));
            if($plan->begin > $plan->end and !empty($plan->end)) return helper::end(js::alert(sprintf($this->lang->productplan->beginGeEnd, $planID)));

            if($plan->begin == '') $plan->begin = $this->config->productplan->future;
            if($plan->end   == '') $plan->end   = $this->config->productplan->future;

            foreach($extendFields as $extendField)
            {
                $plan->{$extendField->field} = $this->post->{$extendField->field}[$planID];
                if(is_array($plan->{$extendField->field})) $plan->{$extendField->field} = join(',', $plan->{$extendField->field});

                $plan->{$extendField->field} = htmlSpecialString($plan->{$extendField->field});
            }

            $plans[$planID] = $plan;
        }

        $changes = array();
        $parents = array();
        foreach($plans as $planID => $plan)
        {
            $parentID = $oldPlans[$planID]->parent;
            /* Determine whether the begin and end dates of the parent plan and the child plan are correct. */
            if($parentID > 0)
            {
                $parent = isset($plans[$parentID]) ? $plans[$parentID] : $this->getByID($parentID);
                if($parent->begin != $this->config->productplan->future and $plan->begin != $this->config->productplan->future and $plan->begin < $parent->begin)
                {
                    return helper::end(js::alert(sprintf($this->lang->productplan->beginLetterParentTip, $planID, $plan->begin, $parent->begin)));
                }
                elseif($parent->end != $this->config->productplan->future and $plan->end != $this->config->productplan->future and $plan->end > $parent->end)
                {
                    return helper::end(js::alert(sprintf($this->lang->productplan->endGreaterParentTip, $planID, $plan->end, $parent->end)));
                }
            }
            elseif($parentID == -1 and $plan->begin != $this->config->productplan->future)
            {
                $childPlans = $this->dao->select('*')->from(TABLE_PRODUCTPLAN)->where('parent')->eq($planID)->andWhere('deleted')->eq(0)->fetchAll('id');
                $minBegin   = $plan->begin;
                $maxEnd     = $plan->end;
                foreach($childPlans as $childID => $childPlan)
                {
                    $childPlan = isset($plans[$childID]) ? $plans[$childID] : $childPlan;
                    if($childPlan->begin < $minBegin and $minBegin != $this->config->productplan->future) $minBegin = $childPlan->begin;
                    if($childPlan->end > $maxEnd and $maxEnd != $this->config->productplan->future) $maxEnd = $childPlan->end;
                }
                if($minBegin < $plan->begin and $minBegin != $this->config->productplan->future) return helper::end(js::alert(sprintf($this->lang->productplan->beginGreaterChildTip, $planID, $plan->begin, $minBegin)));
                if($maxEnd > $plan->end and $maxEnd != $this->config->productplan->future) return helper::end(js::alert(sprintf($this->lang->productplan->endLetterChildTip, $planID, $plan->end, $maxEnd)));
            }

            $change = common::createChanges($oldPlans[$planID], $plan);
            if($change)
            {
                if($parentID > 0 and !isset($parents[$parentID])) $parents[$parentID] = $parentID;
                $this->dao->update(TABLE_PRODUCTPLAN)->data($plan)->autoCheck()->checkFlow()->where('id')->eq($planID)->exec();
                if(dao::isError()) return helper::end(js::error(dao::getError()));
                $changes[$planID] = $change;
            }
        }

        foreach($parents as $parent) $this->updateParentStatus($parent);

        return $changes;
    }

    /**
     * Batch change the status of productplan.
     *
     * @param  array   $planIDList
     * @param  string  $status
     * @access public
     * @return array
     */
    public function batchChangeStatus($status)
    {
        $this->loadModel('action');
        $allChanges = array();

        $planIDList = $this->post->planIDList;
        if($status == 'closed') $closedReasons = $this->post->closedReasons;

        $oldPlans = $this->getByIDList($planIDList, $status);

        foreach($oldPlans as $planID => $oldPlan)
        {
            $oldPlan = $oldPlans[$planID];
            if($status == $oldPlan->status) continue;

            $plan = $this->buildPlanByStatus($status, $status == 'closed' ? $closedReasons[$planID] : '');

            $this->dao->update(TABLE_PRODUCTPLAN)->data($plan)->autoCheck()->where('id')->eq((int)$planID)->exec();

            if($oldPlan->parent > 0) $this->updateParentStatus($oldPlan->parent);

            if(!dao::isError())
            {
                $allChanges[$planID] = common::createChanges($oldPlan, $plan);
            }
            else
            {
                return print(js::error(dao::getError()));
            }
        }

        foreach($allChanges as $planID => $changes)
        {
            $comment = $this->post->comments[$planID] ? $this->post->comments[$planID] : '';
            $actionID = $this->action->create('productplan', $planID, 'edited', $comment);
            $this->action->logHistory($actionID, $changes);
        }
        return $allChanges;
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
            if($maxEnd > $end and $end != $this->config->productplan->future) dao::$errors['end'] = sprintf($this->lang->endLetterChild, $maxEnd);
        }
        elseif($plan->parent > 0)
        {
            $parentPlan = $this->getByID($plan->parent);
            if($begin < $parentPlan->begin and $parentPlan->begin != $this->config->productplan->future) dao::$errors['begin'] = sprintf($this->lang->productplan->beginLetterParent, $parentPlan->begin);
            if($end > $parentPlan->end and $parentPlan->end != $this->config->productplan->future) dao::$errors['end'] = sprintf($this->lang->productplan->endGreaterParent, $parentPlan->end);
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
        $plan    = $this->getByID($planID);

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
        $this->dao->update(TABLE_STORY)->set('plan')->eq(join(',', $plans))->where('id')->eq((int)$storyID)->exec();

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

        $bugs = $this->loadModel('bug')->getByList($this->post->bugs);
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
                $this->execution->linkStory($projectID, $planStories, $planProducts);
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
    public function reorder4Children($plans)
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
     * Get relation branch for plans.
     *
     * @param  array    $plans
     * @access public
     * @return array
     */
    public function relationBranch($plans)
    {
        if(empty($plans)) return $plans;

        $this->app->loadLang('branch');
        $branchMap = $this->dao->select('id, name')->from(TABLE_BRANCH)
            ->where('status')->eq('active')
            ->andWhere('deleted')->eq('0')
            ->fetchPairs('id', 'name');
        $branchMap[BRANCH_MAIN] = $this->lang->branch->main;

        foreach($plans as &$plan)
        {
            if($plan->branch)
            {
                $branchName = '';
                foreach(explode(',', $plan->branch) as $planBranch)
                {
                    $branchName .= isset($branchMap[$planBranch]) ? $branchMap[$planBranch] : '';
                    $branchName .= ',';
                }

                $plan->branchName = trim($branchName, ',');
            }
            else
            {
                $plan->branchName = $this->lang->branch->main;
            }
        }

        return $plans;
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
     * @param  string $type
     * @access public
     * @return string
     */
    public function buildOperateMenu($plan, $type = 'view')
    {
        $params = "planID=$plan->id";

        $canStart       = common::hasPriv('productplan', 'start');
        $canFinish      = common::hasPriv('productplan', 'finish');
        $canClose       = common::hasPriv('productplan', 'close');
        $canCreateExec  = common::hasPriv('execution', 'create');
        $canLinkStory   = common::hasPriv('productplan', 'linkStory', $plan);
        $canLinkBug     = common::hasPriv('productplan', 'linkBug', $plan);
        $canEdit        = common::hasPriv('productplan', 'edit', $plan);
        $canCreateChild = common::hasPriv('productplan', 'create');
        $canDelete      = common::hasPriv('productplan', 'delete', $plan);

        $menu  = '';
        $menu .= $this->buildMenu('productplan', 'start', $params, $plan, $type, 'play', 'hiddenwin', '', false, '', $this->lang->productplan->startAB);
        $menu .= $this->buildMenu('productplan', 'finish', $params, $plan, $type, 'checked', 'hiddenwin', '', false, '', $this->lang->productplan->finishAB);
        $menu .= $this->buildMenu('productplan', 'close', $params, $plan, $type, 'off', 'hiddenwin', 'iframe', true, '', $this->lang->productplan->closeAB);

        if($type == 'view') $menu .= $this->buildMenu('productplan', 'activate', $params, $plan, $type, 'magic', 'hiddenwin', '', false, '', $this->lang->productplan->activateAB);

        if($type == 'browse')
        {
            $canClickExecution = true;
            if($plan->parent < 0 || $plan->expired || in_array($plan->status, array('done', 'closed')) || !common::hasPriv('execution', 'create', $plan)) $canClickExecution = false;

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
                $menu .= html::a('#projects', '<i class="icon-plus"></i>', '', "data-toggle='modal' data-id='$plan->id' onclick='getPlanID(this, $plan->branch)' class='btn' title='{$this->lang->productplan->createExecution}'");
            }
            elseif($canCreateExec)
            {
                $menu .= "<button type='button' class='btn disabled'><i class='icon-plus' title='{$this->lang->productplan->createExecution}'></i></button>";
            }

            if($type == 'browse' and ($canStart or $canFinish or $canClose or $canCreateExec) and ($canLinkStory or $canLinkBug or $canEdit or $canCreateChild or $canDelete))
            {
                $menu .= "<div class='dividing-line'></div>";
            }

            if($canLinkStory and $plan->parent >= 0)
            {
                $menu .= $this->buildMenu($this->app->rawModule, 'view', "{$params}&type=story&orderBy=id_desc&link=true", $plan, $type, 'link', '', '', '', '', $this->lang->productplan->linkStory);
            }
            elseif($canLinkStory)
            {
                $menu .= "<button type='button' class='disabled btn'><i class='icon-link' title='{$this->lang->productplan->linkStory}'></i></button>";
            }

            if($canLinkBug and $plan->parent >= 0)
            {
                $menu .= $this->buildMenu($this->app->rawModule, 'view', "{$params}&type=bug&orderBy=id_desc&link=true", $plan, $type, 'bug', '', '', '', '',  $this->lang->productplan->linkBug);
            }
            elseif($canLinkBug)
            {
                $menu .= "<button type='button' class='disabled btn'><i class='icon-bug' title='{$this->lang->productplan->linkBug}'></i></button>";
            }

            $menu .= $this->buildMenu($this->app->rawModule, 'edit', $params, $plan, $type);
        }

        $menu .= $this->buildMenu($this->app->rawModule, 'create', "product={$plan->product}&branch={$plan->branch}&parent={$plan->id}", $plan, $type, 'split', '', '', '', '', $this->lang->productplan->children);

        if($type == 'browse' and ($canLinkStory or $canLinkBug or $canEdit or $canCreateChild) and $canDelete)
        {
            $menu .= "<div class='dividing-line'></div>";
        }

        if($type == 'browse') $menu .= $this->buildMenu('productplan', 'delete', "{$params}&confirm=no", $plan, $type, 'trash', 'hiddenwin', '', '', $this->lang->productplan->delete);

        if($type == 'view')
        {
            $menu .= "<div class='divider'></div>";
            $menu .= $this->buildFlowMenu('productplan', $plan, $type, 'direct');
            $menu .= "<div class='divider'></div>";

            $editClickable   = $this->buildMenu($this->app->rawModule, 'edit',   $params, $plan, $type, '', '', '', '', '', '', false);
            $deleteClickable = $this->buildMenu('productplan', 'delete', $params, $plan, $type, '', '', '', '', '', '', false);
            if($canEdit and $editClickable) $menu .= html::a(helper::createLink('productplan', 'edit', $params), "<i class='icon-common-edit icon-edit'></i> " . $this->lang->edit, '', "class='btn btn-link' title='{$this->lang->edit}'");
            if($canDelete and $deleteClickable) $menu .= html::a(helper::createLink('productplan', 'delete', $params), "<i class='icon-common-delete icon-trash'></i> " . $this->lang->delete, '', "class='btn btn-link' title='{$this->lang->delete}' target='hiddenwin'");
        }

        return $menu;
    }

    /**
     * Build search form.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @param  object $product
     * @access public
     * @return void
     */
    public function buildSearchForm($queryID, $actionURL, $product)
    {
        $this->config->productplan->search['actionURL'] = $actionURL;
        $this->config->productplan->search['queryID']   = $queryID;

        if($product->type != 'normal') $this->config->productplan->search['params']['branch']['values']  = array('' => '', '0' => $this->lang->branch->main) + $this->loadModel('branch')->getPairs($product->id, 'noempty');
        if($product->type == 'normal') unset($this->config->productplan->search['fields']['branch']);
        $this->loadModel('search')->setSearchParams($this->config->productplan->search);
    }

    /**
     * Transfer stories and bugs to new plan.
     *
     * @param  object $plan
     * @access public
     * @return void
     */
    public function transferStoriesAndBugs($plan)
    {
        $this->dao->update(TABLE_PRODUCTPLAN)->set('parent')->eq('-1')->where('id')->eq($plan->parent)->andWhere('parent')->eq('0')->exec();

        /* Transfer stories linked with the parent plan to the child plan. */
        $stories = $this->dao->select('*')->from(TABLE_STORY)->where("CONCAT(',', plan, ',')")->like("%,{$plan->parent},%")->fetchAll('id');
        $unlinkStories = array();
        foreach($stories as $storyID => $story)
        {
            if(!empty($story->branch) and strpos(",$plan->branch,", ",$story->branch,") === false)
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
        $bugs = $this->dao->select('*')->from(TABLE_BUG)->where('plan')->eq($plan->parent)->fetchAll('id');
        $unlinkBugs = array();
        foreach($bugs as $bugID => $bug)
        {
            if(!empty($bug->branch) and strpos(",$plan->branch,", ",$bug->branch,") === false) $unlinkBugs[$bugID] = $bugID;
        }
        if(!empty($unlinkBugs)) $this->dao->update(TABLE_BUG)->set('plan')->eq(0)->where('plan')->eq($plan->parent)->andWhere('id')->in($unlinkBugs)->exec();
        $this->dao->update(TABLE_BUG)->set('plan')->eq($plan->id)->where('plan')->eq($plan->parent)->exec();
    }

    /**
     * Get plan list by ids.
     *
     * @param  array  $planIds
     * @param  boo    $order
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
}
