<?php
declare(strict_types=1);
/**
 * The zen file of producrplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wang Yidong <yidong@easycorp.ltd>
 * @package     producrplan
 * @link        https://www.zentao.net
 */
class productplanZen extends productplan
{
    /**
     * 构建批量编辑计划数据。
     * Build plans for batch edit.
     *
     * @param  int          $productID
     * @access protected
     * @return array|string
     */
    protected function buildPlansForBatchEdit(): array|string
    {
        $plans        = form::batchData($this->config->productplan->form->batchEdit)->get();
        $oldPlans     = $this->productplan->getByIDList(array_keys($plans));
        $futureConfig = $this->config->productplan->future;
        foreach($plans as $planID => $plan)
        {
            $oldPlan  = $oldPlans[$planID];
            $parentID = $oldPlan->parent;

            if(empty($plan->begin))  $plan->begin  = $oldPlan->begin;
            if(empty($plan->end))    $plan->end    = $oldPlan->end;
            if(empty($plan->status)) $plan->status = $oldPlan->status;

            if($plan->begin > $plan->end && !empty($plan->end)) return dao::$errors["begin[{$planID}]"] = sprintf($this->lang->productplan->beginGeEnd, $planID);;
            if($plan->begin == '') $plan->begin = $this->config->productplan->future;
            if($plan->end   == '') $plan->end   = $this->config->productplan->future;
            if(!empty($_POST['future'][$planID]))
            {
                $plan->begin = $this->config->productplan->future;
                $plan->end   = $this->config->productplan->future;
            }

            /* Determine whether the begin and end dates of the parent plan and the child plan are correct. */
            if($parentID > 0)
            {
                $parent = zget($plans, $parentID, $this->productplan->getByID($parentID));
                if($parent->begin != $futureConfig && $plan->begin != $futureConfig && $plan->begin < $parent->begin) return dao::$errors["begin[{$planID}]"] = sprintf($this->lang->productplan->beginLessThanParentTip, $planID, $plan->begin, $parent->begin);
                if($parent->end != $futureConfig && $plan->end != $futureConfig && $plan->end > $parent->end)         return dao::$errors["end[{$planID}]"]   = sprintf($this->lang->productplan->endGreatThanParentTip, $planID, $plan->end, $parent->end);
            }
            elseif($parentID == -1 && $plan->begin != $futureConfig)
            {
                $childPlans = $this->productplan->getChildren($plan->id);
                $minBegin   = $plan->begin;
                $maxEnd     = $plan->end;
                foreach($childPlans as $childID => $childPlan)
                {
                    $childPlan = isset($plans[$childID]) ? $plans[$childID] : $childPlan;
                    if($childPlan->begin < $minBegin && $minBegin != $this->config->productplan->future) $minBegin = $childPlan->begin;
                    if($childPlan->end > $maxEnd && $maxEnd != $this->config->productplan->future)       $maxEnd   = $childPlan->end;
                }
                if($minBegin < $plan->begin && $minBegin != $futureConfig) return dao::$errors["begin[{$planID}]"] = sprintf($this->lang->productplan->beginGreaterChildTip, $planID, $plan->begin, $minBegin);
                if($maxEnd > $plan->end     && $maxEnd != $futureConfig)   return dao::$errors["end[{$planID}]"]   = sprintf($this->lang->productplan->endLessThanChildTip, $planID, $plan->end, $maxEnd);
            }

            if($plan->branch == '') $plan->branch = 0;
        }

        return $plans;
    }

    /**
     * 设置计划看板页面数据。
     * Set kanban page data.
     *
     * @param  object    $product
     * @param  string    $branchID
     * @param  string    $orderBy
     * @access protected
     * @return void
     */
    protected function assignKanbanData(object $product, string $branchID, string $orderBy)
    {
        $branches    = array();
        $branchPairs = array();
        $planCount   = 0;
        if(!in_array($orderBy, array_keys($this->lang->productplan->orderList))) $orderBy = key($this->lang->productplan->orderList);

        if($product->type == 'normal')
        {
            $planGroup = $this->productplan->getList($product->id, '0', 'all', null, $orderBy, 'skipparent');

            $this->view->planCount = count(array_filter($planGroup));
        }
        else
        {
            $planGroup = $this->productplan->getGroupByProduct(array($product->id), 'skipparent', $orderBy);
            $branches  = $this->loadModel('branch')->getPairs($product->id, 'active');

            foreach($branches as $id => $name)
            {
                $plans            = isset($planGroup[$product->id][$id]) ? array_filter($planGroup[$product->id][$id]) : array();
                $branchPairs[$id] = $name . ' ' . count($plans);
                $planCount       += count($plans);
            }

            $this->view->branches = array('all' => $this->lang->productplan->allAB . ' ' . $planCount) + $branchPairs;
        }

        $this->view->kanbanList = $this->loadModel('kanban')->getPlanKanban($product, $branchID, $planGroup);
    }

    /**
     * 构造计划列表页面数据。
     * Build data for browse page.
     *
     * @param  array     $plans
     * @param  array     $branchOption
     * @access protected
     * @return array
     */
    protected function buildDataForBrowse(array $plans, array $branchOption): array
    {
        if(empty($plans)) return $plans;
        foreach($plans as $plan)
        {
            $plan->branchName = '';
            if($this->session->currentProductType != 'normal')
            {
                foreach(explode(',', $plan->branch) as $branchID) $plan->branchName .= $branchOption[$branchID] . ',';
                $plan->branchName = trim($plan->branchName, ',');
            }

            if($plan->begin == $this->config->productplan->future) $plan->begin = $this->lang->productplan->future;
            if($plan->end   == $this->config->productplan->future) $plan->end   = $this->lang->productplan->future;

            $plan->actions  = $this->buildActionsList($plan);
            $plan->projects = array_values($plan->projects);
            $plan->desc     = strip_tags($plan->desc);
        }

        return $plans;
    }

    /**
     * 构造计划列表页面操作。
     * Build actions for browse page.
     *
     * @param  object    $plan
     * @access protected
     * @return array
     */
    protected function buildActionsList(object $plan): array
    {
        $actions = array();
        if(common::hasPriv('productplan', 'start'))     $actions[] = 'start';
        if(common::hasPriv('productplan', 'finish'))    $actions[] = 'finish';
        if(common::hasPriv('productplan', 'close'))     $actions[] = 'close';
        if(common::hasPriv('productplan', 'activate'))  $actions[] = 'activate';
        if(common::hasPriv('execution', 'create'))      $actions[] = 'createExecution';

        if(count($actions) > 0) $actions[] = 'divider';

        if(common::hasPriv('productplan', 'linkStory')) $actions[] = 'linkStory';
        if(common::hasPriv('productplan', 'linkBug'))   $actions[] = 'linkBug';
        if(common::hasPriv('productplan', 'edit'))      $actions[] = 'edit';
        if(common::hasPriv('productplan', 'create'))    $actions[] = 'create';
        if(common::hasPriv('productplan', 'delete'))    $actions[] = 'delete';

        return $actions;
    }

    /**
     * 统计父计划、子计划和独立计划的总数。
     * Get the total count of parent plan, child plan and indepentdent plan.
     *
     * @param  array     $planList
     * @access protected
     * @return string
     */
    protected function getSummary(array $planList): string
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
     * 设置计划详情页面session信息。
     * Set session for view page.
     *
     * @param  int       $planID
     * @param  string    $type
     * @param  string    $orderBy
     * @param  int       $pageID
     * @param  int       $recPerPage
     * @access protected
     * @return void
     */
    protected function setSessionForViewPage(int $planID, string $type, string $orderBy, int $pageID, int $recPerPage)
    {
        if(in_array($type, array('story', 'bug')) && ($orderBy != 'order_desc' || $pageID != 1 || $recPerPage != 100))
        {
            if($type == 'story')
            {
                $this->session->set('storyList', $this->app->getURI(true), 'product');
            }
            elseif($type == 'bug')
            {
                $this->session->set('bugList', $this->app->getURI(true), 'qa');
            }
            else
            {
                $this->session->set('storyList', $this->createLink('productplan', 'view', "planID={$planID}&type={$type}"), $type == 'story' ? 'product' : 'qa');
            }
        }
    }

    /**
     * 设置详情页面的属性。
     * Set attributes for view page.
     *
     * @param  object    $plan
     * @access protected
     * @return void
     */
    protected function assignViewData(object $plan): void
    {
        if($plan->parent > 0)     $this->view->parentPlan    = $this->productplan->getById($plan->parent);
        if($plan->parent == '-1') $this->view->childrenPlans = $this->productplan->getChildren($plan->id);

        $gradeList  = $this->loadModel('story')->getGradeList('');
        $gradeGroup = array();
        foreach($gradeList as $grade) $gradeGroup[$grade->type][$grade->grade] = $grade->name;

        $this->view->plan       = $plan;
        $this->view->gradeGroup = $gradeGroup;
        $this->view->actions    = $this->loadModel('action')->getList('productplan', $plan->id);
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->plans      = $this->productplan->getPairs($plan->product, $plan->branch, '', true);
        $this->view->modules    = $this->loadModel('tree')->getOptionMenu($plan->product);

        if($this->app->getViewType() == 'json')
        {
            unset($this->view->storyPager);
            unset($this->view->bugPager);
        }
    }

    /**
     * 构建关联需求页面的搜索表单。
     * Build search form for link story page.
     *
     * @param  object    $plan
     * @param  int       $queryID
     * @param  string    $orderBy
     * @access protected
     * @return void
     */
    protected function buildLinkStorySearchForm(object $plan, int $queryID, string $orderBy): void
    {
        $this->app->loadLang('story');
        $products = $this->loadModel('product')->getProductPairsByProject((int)$this->session->project);

        /* Build search form. */
        $this->config->product->search['actionURL'] = $this->createLink('productplan', 'view', "planID=$plan->id&type=story&orderBy=$orderBy&link=true&param=" . helper::safe64Encode('&browseType=bySearch&queryID=myQueryID'));
        $this->config->product->search['queryID']   = $queryID;
        $this->config->product->search['style']     = 'simple';
        $this->config->product->search['fields']['title']             = $this->lang->productplan->storyTitle;
        $this->config->product->search['params']['product']['values'] = $products + array('all' => $this->lang->product->allProductsOfProject);
        $this->config->product->search['params']['plan']['values']    = $this->productplan->getPairs($plan->product, $plan->branch, 'withMainPlan', true);
        $this->config->product->search['params']['module']['values']  = $this->loadModel('tree')->getOptionMenu($plan->product, 'story', 0, 'all');

        $storyStatusList = $this->lang->story->statusList;
        unset($storyStatusList['closed']);
        $this->config->product->search['params']['status'] = array('operator' => '=', 'control' => 'select', 'values' => $storyStatusList);

        $product = $this->loadModel('product')->getByID($plan->product);
        if($product->type == 'normal')
        {
            unset($this->config->product->search['fields']['branch']);
            unset($this->config->product->search['params']['branch']);
        }
        else
        {
            $branches = $this->loadModel('branch')->getPairsByIdList(explode(',', trim($plan->branch, ',')));
            $this->config->product->search['fields']['branch']           = sprintf($this->lang->product->branch, $this->lang->product->branchName[$product->type]);
            $this->config->product->search['params']['branch']['values'] = array('' => '', BRANCH_MAIN => $this->lang->branch->main) + $branches;
        }

        $gradeList = $this->loadModel('story')->getGradeList('');
        foreach($gradeList as $grade)
        {
            if(!$this->config->URAndSR  && $grade->type == 'requirement') continue;
            if(!$this->config->enableER && $grade->type == 'epic') continue;

            $key = (string)$grade->type . (string)$grade->grade;
            $gradePairs[$key] = $grade->name;
        }
        asort($gradePairs);
        $this->config->product->search['params']['grade']['values'] = $gradePairs;

        unset($this->config->product->search['fields']['product']);
        $this->loadModel('search')->setSearchParams($this->config->product->search);
    }

    /**
     * 构造关联bug页面的搜索表单。
     * Build search form for link bug page.
     *
     * @param  object    $plan
     * @param  int       $queryID
     * @param  string    $orderBy
     * @access protected
     * @return void
     */
    protected function buildBugSearchForm(object $plan, int $queryID, string $orderBy): void
    {
        $this->config->bug->search['actionURL'] = $this->createLink('productplan', 'view', "planID={$plan->id}&type=bug&orderBy={$orderBy}&link=true&param=" . helper::safe64Encode('&browseType=bySearch&queryID=myQueryID'));
        $this->config->bug->search['queryID']   = $queryID;
        $this->config->bug->search['style']     = 'simple';

        $modulePairs = $this->loadModel('tree')->getOptionMenu($plan->product, 'bug', 0, 'all');
        $this->config->bug->search['params']['plan']['values']          = $this->productplan->getPairs($plan->product, $plan->branch, 'withMainPlan', true);
        $this->config->bug->search['params']['execution']['values']     = $this->loadModel('product')->getExecutionPairsByProduct($plan->product, $plan->branch);
        $this->config->bug->search['params']['module']['values']        = $modulePairs;
        $this->config->bug->search['params']['openedBuild']['values']   = $this->loadModel('build')->getBuildPairs(array($plan->product), 'all', 'releasetag');
        $this->config->bug->search['params']['resolvedBuild']['values'] = $this->config->bug->search['params']['openedBuild']['values'];
        $this->config->bug->search['params']['module']['values']        = $modulePairs;
        $this->config->bug->search['params']['project']['values']       = $this->product->getProjectPairsByProduct($plan->product, $plan->branch);

        unset($this->config->bug->search['fields']['product']);

        $product = $this->loadModel('product')->getByID($plan->product);
        if($product->type == 'normal')
        {
            unset($this->config->bug->search['fields']['branch']);
            unset($this->config->bug->search['params']['branch']);
        }
        else
        {
            $branches = $this->loadModel('branch')->getPairsByIdList(explode(',', trim($plan->branch, ',')));
            $this->config->bug->search['fields']['branch']           = sprintf($this->lang->product->branch, $this->lang->product->branchName[$product->type]);
            $this->config->bug->search['params']['branch']['values'] = array('' => '', BRANCH_MAIN => $this->lang->branch->main) + $branches;
        }
        $this->loadModel('search')->setSearchParams($this->config->bug->search);
    }

    /**
     * 构造需求列表的摘要信息。
     * Get the summary of product's stories.
     *
     * @param  array  $stories
     * @access public
     * @return string
     */
    public function buildViewSummary(array $stories): string
    {
        $totalEstimate = 0.0;
        $storyIdList   = array();

        $rateCount = 0;
        $SRTotal   = 0;
        $URTotal   = 0;
        $ERTotal   = 0;

        foreach($stories as $story)
        {
            if($story->type == 'story')       $SRTotal += 1;
            if($story->type == 'requirement') $URTotal += 1;
            if($story->type == 'epic')        $ERTotal += 1;

            if($story->isParent == '0') $totalEstimate += $story->estimate;

            if($story->type != 'story') continue;
            if($story->isParent == '0' && ($story->status != 'closed' || in_array($story->closedReason, array('done', 'postponed'))))
            {
                $storyIdList[] = $story->id;
                $rateCount ++;
            }
        }

        $casesCount = count($this->loadModel('product')->filterNoCasesStory($storyIdList));
        $rate       = empty($stories) || $rateCount == 0 ? 0 : round($casesCount / $rateCount, 2);

        return sprintf($this->lang->productplan->storySummary, $ERTotal, $URTotal, $SRTotal, $totalEstimate, $rate * 100 . "%");
    }

    /**
     * 构造计划详情页面的操作菜单。
     * Build operate menu for plan detail page.
     *
     * @param  object $plan
     * @access public
     * @return array
     */
    public function buildViewActions(object $plan): array
    {
        $params = "planID=$plan->id";

        $canEdit        = common::hasPriv('productplan', 'edit');
        $canStart       = common::hasPriv('productplan', 'start')    && $this->productplan->isClickable($plan, 'start');
        $canFinish      = common::hasPriv('productplan', 'finish')   && $this->productplan->isClickable($plan, 'finish');
        $canClose       = common::hasPriv('productplan', 'close')    && $this->productplan->isClickable($plan, 'close');
        $canActivate    = common::hasPriv('productplan', 'activate') && $this->productplan->isClickable($plan, 'activate');
        $canCreateChild = common::hasPriv('productplan', 'create')   && $this->productplan->isClickable($plan, 'create');
        $canDelete      = common::hasPriv('productplan', 'delete')   && $this->productplan->isClickable($plan, 'delete');

        $menu = array();
        if($canStart)       $menu[] = array('icon' => 'play text-primary',    'class' => 'ghost', 'text' => $this->lang->productplan->startAB,    'data-url' => helper::createLink('productplan', 'start', $params), 'data-action' => 'start', 'onclick' => 'ajaxConfirmLoad(this)');
        if($canFinish)      $menu[] = array('icon' => 'checked text-primary', 'class' => 'ghost', 'text' => $this->lang->productplan->finishAB,   'data-url' => helper::createLink('productplan', 'finish', $params), 'data-action' => 'finish', 'onclick' => 'ajaxConfirmLoad(this)');
        if($canClose)       $menu[] = array('icon' => 'off text-primary',     'class' => 'ghost', 'text' => $this->lang->productplan->closeAB,    'url' => helper::createLink('productplan', 'close', $params, '', true), 'data-toggle' => 'modal');
        if($canActivate)    $menu[] = array('icon' => 'magic text-primary',   'class' => 'ghost', 'text' => $this->lang->productplan->activateAB, 'data-url' => helper::createLink('productplan', 'activate', $params), 'data-action' => 'activate', 'onclick' => 'ajaxConfirmLoad(this)');
        if($canCreateChild) $menu[] = array('icon' => 'split text-primary',   'class' => 'ghost', 'text' => $this->lang->productplan->children,   'url' => helper::createLink('productplan', 'create', "product={$plan->product}&branch={$plan->branch}&parent={$plan->id}"));
        if($canEdit)        $menu[] = array('icon' => 'edit text-primary',    'class' => 'ghost', 'text' => $this->lang->edit,                    'url' => helper::createLink('productplan', 'edit', $params));
        if($canDelete)      $menu[] = array('icon' => 'trash text-primary',   'class' => 'ghost', 'text' => $this->lang->delete,                  'data-url' => helper::createLink('productplan', 'delete', $params), 'data-action' => 'delete', 'onclick' => 'ajaxConfirmLoad(this)');

        return $menu;
    }

    /**
     * 对计划需求按照父子关系重新排序。
     * Reorder stories by parent-child relationship.
     *
     * @access public
     * @return void
     */
    public function reorderStories()
    {
        /* 获取不分页的 SQL 语句，为重新按照父子关系排序做准备。*/
        $sql = $this->dao->get();
        if(strpos($sql, 'LIMIT')) $sql = substr($sql, 0, strpos($sql, 'LIMIT'));

        /* 取出用户重新排序的关键字段。*/
        $stories = array();
        $query   = $this->dao->query($sql);
        while($story = $query->fetch()) $stories[$story->id] = $story->parent;

        /* 对需求重新按照父子关系排序，保证进入需求详情后上一页下一页的URL符合预期。 */
        $objectList = $this->loadModel('story')->reorderStories($stories);
        if($objectList)
        {
            $this->session->set('storyBrowseList', array('sql' => $sql, 'idkey' => 'id', 'objectList' => $objectList), $this->app->tab);
            $this->session->set('epicBrowseList', array('sql' => $sql, 'idkey' => 'id', 'objectList' => $objectList), $this->app->tab);
            $this->session->set('requirementBrowseList', array('sql' => $sql, 'idkey' => 'id', 'objectList' => $objectList), $this->app->tab);
        }
    }
}
