<?php
declare(strict_types=1);
/**
 * The control file of productplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     productplan
 * @version     $Id: control.php 4659 2013-04-17 06:45:08Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
class productplan extends control
{
    /**
     * 设置公共属性。
     * Common actions.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @access public
     * @return void
     */
    public function commonAction(int $productID, int $branch = 0)
    {
        $product = $this->loadModel('product')->getById($productID);
        if(empty($product)) $this->locate($this->createLink('product', 'create'));

        $this->lang->product->branch = sprintf($this->lang->product->branch, $this->lang->product->branchName[$product->type]);

        $this->app->loadConfig('execution');
        if(!$product->shadow) $this->product->setMenu($productID, $branch);
        $this->session->set('currentProductType', $product->type);

        $branches = $this->loadModel('branch')->getList($productID, 0, 'all');
        $branchOption    = array();
        $branchTagOption = array();
        foreach($branches as $branchInfo)
        {
            $branchOption[$branchInfo->id]    = $branchInfo->name;
            $branchTagOption[$branchInfo->id] = $branchInfo->name . ($branchInfo->status == 'closed' ? ' (' . $this->lang->branch->statusList['closed'] . ')' : '');
        }

        if($product->shadow)
        {
            $projectList = $this->product->getProjectPairsByProductIdList(array($productID));
            $projectID   = (int) key($projectList);
            $this->loadModel('project')->setMenu($projectID);
        }

        $this->view->product         = $product;
        $this->view->projectID       = isset($projectID) ? $projectID : 0;
        $this->view->branch          = $branch;
        $this->view->branchOption    = $branchOption;
        $this->view->branchTagOption = $branchTagOption;
    }

    /**
     * 创建一个计划。
     * Create a plan.
     *
     * @param  int    $productID
     * @param  int    $branchID
     * @param  int    $parent
     * @access public
     * @return void
     */
    public function create(int $productID = 0, int $branchID = 0, int $parent = 0)
    {
        if(!empty($_POST))
        {
            $planData = form::data()->get();
            $planID   = $this->productplan->create($planData, (int)$this->post->future);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->loadModel('action')->create('productplan', $planID, 'opened');

            $message = $this->executeHooks($planID);
            if($message) $this->lang->saveSuccess = $message;

            if($parent > 0) $this->productplan->updateParentStatus($parent);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $planID));
            return $this->sendSuccess(array('load' => $this->createLink($this->app->rawModule, 'browse', "productID=$productID")));
        }

        $this->commonAction($productID, $branchID);
        $lastPlan = $this->productplan->getLast($productID, '', $parent);
        $product  = $this->loadModel('product')->getByID($productID);
        if($lastPlan)
        {
            $timestamp = strtotime($lastPlan->end);
            $weekday   = date('w', $timestamp);
            $delta     = 1;
            if($weekday == '5' || $weekday == '6') $delta = 8 - $weekday;

            $begin = date('Y-m-d', strtotime("+$delta days", $timestamp));
        }
        $this->view->begin = $lastPlan ? $begin : date('Y-m-d');
        if($parent) $this->view->parentPlan = $this->productplan->getByID($parent);

        $this->view->title           = $this->view->product->name . $this->lang->colon . $this->lang->productplan->create;
        $this->view->product         = $product;
        $this->view->lastPlan        = $lastPlan;
        $this->view->branch          = $branchID;
        $this->view->branches        = $product->type == 'normal' ? array() : $this->loadModel('branch')->getPairs($productID, 'active');
        $this->view->parent          = $parent;
        $this->view->parentPlanPairs = $this->productplan->getTopPlanPairs($productID, 'done,closed');
        $this->display();
    }

    /**
     * 编辑一个计划。
     * Edit a plan.
     *
     * @param  int    $planID
     * @access public
     * @return void
     */
    public function edit(int $planID)
    {
        $plan = $this->productplan->getByID($planID);
        if(!empty($_POST))
        {
            $planData = form::data($this->config->productplan->form->edit)
                ->setIF($this->post->future || empty($_POST['begin']), 'begin', $this->config->productplan->future)
                ->setIF($this->post->future || empty($_POST['end']), 'end', $this->config->productplan->future)
                ->get();
            $changes  = $this->productplan->update($planData, $plan);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->productplan->unlinkOldBranch(array($planID => $changes));
            if($changes)
            {
                $actionID = $this->loadModel('action')->create('productplan', $planID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }
            $message = $this->executeHooks($planID);
            if($message) $this->lang->saveSuccess = $message;
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->createLink($this->app->rawModule, 'view', "planID=$planID")));
        }

        $oldBranch = array($planID => $plan->branch);

        /* Get the parent plan pair exclusion itself. */
        $parentPlanPairs = $this->productplan->getTopPlanPairs($plan->product);
        unset($parentPlanPairs[$planID]);
        $this->view->parentPlanPairs = $parentPlanPairs;

        $this->commonAction((int)$plan->product, (int)$plan->branch);

        if($plan->parent > 0)
        {
            $parentPlan  = $this->productplan->getByID($plan->parent);
            $branchPairs = array();
            foreach(explode(',', $parentPlan->branch) as $parentBranchID) $branchPairs[$parentBranchID] = $this->view->branchTagOption[$parentBranchID];
            $this->view->branchTagOption = $branchPairs;
        }
        $this->view->title     = $this->view->product->name . $this->lang->colon . $this->lang->productplan->edit;
        $this->view->productID = $plan->product;
        $this->view->oldBranch = $oldBranch;
        $this->view->plan      = $plan;
        $this->display();
    }

    /**
     * 批量编辑计划。
     * Batch edit plan.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @access public
     * @return void
     */
    public function batchEdit(int $productID, int $branch = 0)
    {
        if(!empty($_POST['title']))
        {
            /* 从POST中获取数据。 */
            $plans = $this->productplanZen->buildPlansForBatchEdit();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->productplan->batchUpdate($productID, $plans);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('score')->create('ajax', 'batchOther');
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->session->productPlanList));
        }

        if(!$this->post->planIdList) return $this->send(array('result' => 'success', 'load' => $this->session->productPlanList));

        $this->commonAction($productID, $branch);

        $plans        = $this->productplan->getByIDList($this->post->planIdList);
        $oldBranch    = array();
        $parentIdList = array();
        foreach($plans as $plan)
        {
            $oldBranch[$plan->id]        = $plan->branch;
            $parentIdList[$plan->parent] = $plan->parent;
        }

        $this->view->title      = $this->lang->productplan->batchEdit;
        $this->view->plans      = $plans;
        $this->view->oldBranch  = $oldBranch;
        $this->view->product    = $this->loadModel('product')->getByID($productID);
        $this->view->parentList = $this->productplan->getByIDList($parentIdList);

        $this->display();
    }

    /**
     * 批量更新计划的状态。
     * Batch change the status of productplan.
     *
     * @param  string $status
     * @access public
     * @return void
     */
    public function batchChangeStatus(string $status, int $productID)
    {
        $planIdList = $this->post->planIdList;

        if($status !== 'closed' || $this->post->comment)
        {
            $this->productplan->batchChangeStatus($planIdList, $status);
            if(dao::isError()) return $this->sendError(dao::getError());

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => inlink('browse', "product=$productID")));
        }

        $this->commonAction($productID);

        $this->view->plans     = $this->productplan->getByIDList($planIdList);
        $this->view->productID = $productID;
        $this->display();
    }

    /**
     * 删除一个计划。
     * Delete a plan.
     *
     * @param  int    $planID
     * @access public
     * @return void
     */
    public function delete(int $planID)
    {
        $plan = $this->productplan->getByID($planID);
        if(!$plan || $plan->parent < 0) return $this->sendError($this->lang->productplan->cannotDeleteParent);

        $this->productplan->delete(TABLE_PRODUCTPLAN, $planID);
        if($plan->parent > 0) $this->productplan->changeParentField($planID);

        $message = $this->executeHooks($planID);
        if($message) $this->lang->saveSuccess = $message;

        /* if ajax request, send result. */
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
        return $this->sendSuccess(array('message' => $message, 'load' => true));
    }

    /**
     * 计划列表。
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
    public function browse(int $productID = 0, string $branch = '', string $browseType = 'undone', int $queryID = 0, string $orderBy = 'begin_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $branchID = $branch === '' ? 'all' : $branch;
        if(!$branch) $branch = 0;

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Append id for second sort. */
        $sort = common::appendOrder($orderBy);
        if(strpos($sort, 'branchName_') !== false) $sort = str_replace('branchName_', 'branch_', $sort);
        $this->session->set('productPlanList', $this->app->getURI(true), 'product');

        $viewType = $this->cookie->viewType ? $this->cookie->viewType : 'list';

        $this->commonAction($productID, (int)$branch);
        $product     = $this->view->product;
        $productName = empty($product) ? '' : $product->name;
        if($product->type != 'normal') $this->config->productplan->dtable->fieldList['branch']['title'] = $this->lang->product->branch;
        if($product->type == 'normal') unset($this->config->productplan->dtable->fieldList['branch']);

        /* Build the search form. */
        $queryID   = $browseType == 'bySearch' ? (int)$queryID : 0;
        $actionURL = $this->createLink($this->app->rawModule, 'browse', "productID=$productID&branch=$branch&browseType=bySearch&queryID=myQueryID&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
        $this->productplan->buildSearchForm($queryID, $actionURL, $product);

        if($viewType == 'kanban') $this->productplanZen->assignKanbanData($product, $branchID, $orderBy);

        $plans = $this->productplan->getList($productID, (string)$branch, $browseType, $pager, $sort, "", $queryID);
        $plans = $this->productplanZen->buildDataForBrowse($plans, $this->view->branchOption);

        $this->view->title      = $productName . $this->lang->colon . $this->lang->productplan->browse;
        $this->view->productID  = $productID;
        $this->view->branchID   = $branchID;
        $this->view->browseType = $browseType;
        $this->view->viewType   = $viewType;
        $this->view->orderBy    = $orderBy;
        $this->view->plans      = $plans;
        $this->view->pager      = $pager;
        $this->view->queryID    = $queryID;
        $this->view->summary    = $this->productplanZen->getSummary($plans);
        $this->view->projects   = $this->product->getProjectPairsByProduct($productID, (string)$branch, '', 'closed', 'multiple');
        $this->display();
    }

    /**
     * 查看计划详情。
     * View a plan.
     *
     * @param  int    $planID
     * @param  string $type
     * @param  string $orderBy
     * @param  string $link
     * @param  string $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function view(int $planID = 0, string $type = 'story', string $orderBy = 'order_desc', string $link = 'false', string $param = '', int $recTotal = 0, int $recPerPage = 100, int $pageID = 1)
    {
        $plan = $this->productplan->getByID($planID, true);
        if(!$plan)
        {
            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'fail', 'code' => 404, 'message' => '404 Not found'));
            return $this->sendError($this->lang->notFound, $this->createLink('product', 'index'));
        }

        /* Append id for second sort. */
        $orderBy = ($type == 'bug' && $orderBy == 'order_desc') ? 'id_desc' : $orderBy;
        $sort    = common::appendOrder($orderBy);
        if(strpos($sort, 'pri_') !== false) $sort = str_replace('pri_', 'priOrder_', $sort);

        $this->commonAction($plan->product, (int)$plan->branch);
        $products = $this->product->getProductPairsByProject((int)$this->session->project);

        /* Load pager. */
        $this->app->loadClass('pager', true);
        if(in_array($this->app->getViewType(), array('mhtml', 'xhtml'))) $recPerPage = 10;
        $bugPager   = new pager(0, $recPerPage, $type == 'bug' ? $pageID : 1);
        $storyPager = new pager(0, $recPerPage, $type == 'story' ? $pageID : 1);

        /* Get stories of plan. */
        $modulePairs = $this->loadModel('tree')->getOptionMenu($plan->product, 'story', 0, 'all');
        $planStories = $this->loadModel('story')->getPlanStories($planID, 'all', $type == 'story' ? $sort : 'id_desc', $storyPager);
        foreach($planStories as $story)
        {
            if(!isset($modulePairs[$story->module])) $modulePairs += $this->tree->getModulesName((array)$story->module);
        }

        $this->executeHooks($planID);
        $this->productplanZen->setSessionForViewPage($planID, $type, $orderBy, $pageID, $recTotal);
        $this->productplanZen->assignViewData($plan);

        $this->view->title        = "PLAN #$plan->id $plan->title/" . zget($products, $plan->product, '');
        $this->view->modulePairs  = $modulePairs;
        $this->view->planStories  = $planStories;
        $this->view->planBugs     = $this->loadModel('bug')->getPlanBugs($planID, 'all', $type == 'bug' ? $sort : 'id_desc', $bugPager);
        $this->view->summary      = $this->product->summary($planStories);
        $this->view->actionMenus  = $this->productplanZen->buildViewActions($plan);
        $this->view->type         = $type;
        $this->view->orderBy      = $orderBy;
        $this->view->link         = $link;
        $this->view->param        = $param;
        $this->view->storyPager   = $storyPager;
        $this->view->bugPager     = $bugPager;
        $this->display();
    }

    /**
     * 开始计划。
     * Start a plan.
     *
     * @param  int    $planID
     * @access public
     * @return void
     */
    public function start(int $planID)
    {
        $this->productplan->updateStatus($planID, 'doing', 'started');
        if(dao::isError()) return $this->sendError(dao::getError());

        return $this->sendSuccess(array('load' => true, 'closeModal' => true));
    }

    /**
     * 完成一个计划。
     * Finish a plan.
     *
     * @param  int    $planID
     * @access public
     * @return void
     */
    public function finish(int $planID)
    {
        $this->productplan->updateStatus($planID, 'done', 'finished');
        if(dao::isError()) return $this->sendError(dao::getError());

        return $this->send(array('result' => 'success', 'load' => true, 'closeModal' => true));
    }

    /**
     * 关闭一个计划。
     * Close a plan.
     *
     * @param  int    $planID
     * @access public
     * @return void
     */
    public function close(int $planID)
    {
        if(!empty($_POST))
        {
            $this->productplan->updateStatus($planID, 'closed', 'closed');
            if(dao::isError()) return $this->sendError(dao::getError());

            return $this->send(array('result' => 'success', 'load' => true, 'closeModal' => true));
        }

        $this->view->productplan = $this->productplan->getById($planID);
        $this->view->actions     = $this->loadModel('action')->getList('productplan', $planID);
        $this->view->users       = $this->loadModel('user')->getPairs();

        $this->display();
    }

    /**
     * 激活一个计划。
     * Activate a plan.
     *
     * @param  int    $planID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function activate(int $planID)
    {
        $this->productplan->updateStatus($planID, 'doing', 'activated');
        if(dao::isError()) return $this->sendError(dao::getError());

        return $this->sendSuccess(array('load' => true, 'closeModal' => true));
    }

    /**
     * 根据产品获取计划列表。
     * Ajax: Get product plans.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @access public
     * @return void
     */
    public function ajaxGetProductplans(int $productID, int $branch = 0)
    {
        $plans = $this->productplan->getPairs($productID, empty($branch) ? '' : $branch, '', true);

        $items = array();
        foreach($plans as $planID => $planName) $items[] = array('text' => $planName, 'value' => $planID, 'keys' => $planName);
        return print(json_encode($items));
    }

    /**
     * 设置需求排序。
     * Sort story for productplan.
     *
     * @param  int    $planID
     * @access public
     * @return bool
     */
    public function ajaxStorySort(int $planID = 0)
    {
        if(empty($planID)) return true;

        /* Get story id list. */
        $storyIDList = explode(',', trim($this->post->stories, ','));

        /* Update the story order according to the plan. */
        $this->loadModel('story')->sortStoriesOfPlan($planID, $storyIDList, $this->post->orderBy, $this->post->pageID, $this->post->recPerPage);
    }

    /**
     * 根据产品和分支获取项目。
     * Get projects by product id.
     *
     * @param  int    $productID
     * @param  string $branch
     * @access public
     * @return void
     */
    public function ajaxGetProjects(int $productID, string $branch = '0')
    {
        $projects = $this->loadModel('product')->getProjectPairsByProduct($productID, $branch, '', $status = 'noclosed', 'multiple');

        $items = array();
        foreach($projects as $projectID => $projectName) $items[] = array('text' => $projectName, 'value' => $projectID, 'keys' => $projectName);
        return print(json_encode($items));
    }

    /**
     * 关联需求。
     * Link stories.
     *
     * @param  int    $planID
     * @param  string $browseType
     * @param  int    $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function linkStory(int $planID = 0, string $browseType = '', int $param = 0, string $orderBy = 'order_desc', int $recTotal = 0, int $recPerPage = 100, int $pageID = 1)
    {
        if(!empty($_POST['stories']))
        {
            $this->productplan->linkStory($planID, $this->post->stories);
            if(dao::isError()) return $this->sendError(dao::getError());

            return $this->send(array('result' => 'success', 'load' => inlink('view', "planID=$planID&type=story&orderBy=$orderBy")));
        }

        $this->session->set('storyList', inlink('view', "planID=$planID&type=story&orderBy=$orderBy&link=true&param=" . helper::safe64Encode("&browseType=$browseType&queryID=$param")), 'product');

        $plan = $this->productplan->getByID($planID);
        if(!$plan) return $this->sendError($this->lang->notFound, true);

        $this->commonAction($plan->product, (int)$plan->branch);

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Build search form. */
        $this->productplanZen->buildLinkStorySearchForm($plan, $browseType == 'bySearch' ? (int)$param : 0, $orderBy);

        $planStories = $this->loadModel('story')->getPlanStories($planID);
        if($browseType == 'bySearch')
        {
            $allStories = $this->story->getBySearch($plan->product, "0,{$plan->branch}", (int)$param, 'id', 0, 'story', array_keys($planStories), $pager);
        }
        else
        {
            $allStories = $this->story->getProductStories($this->view->product->id, $plan->branch ? "0,{$plan->branch}" : 0, '0', 'draft,reviewing,active,changing', 'story', 'id_desc', $hasParent = false, array_keys($planStories), $pager);
        }

        $modules = $this->loadModel('tree')->getOptionMenu($plan->product, 'story', 0, 'all');
        foreach($allStories as $story)
        {
            if(!isset($modules[$story->module])) $modules += $this->tree->getModulesName(array($story->module));
        }

        $this->view->allStories = $allStories;
        $this->view->plan       = $plan;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->browseType = $browseType;
        $this->view->modules    = $modules;
        $this->view->param      = $param;
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->display();
    }

    /**
     * 移除计划中的需求。
     * Unlink story.
     *
     * @param  int    $storyID
     * @param  int    $planID
     * @access public
     * @return void
     */
    public function unlinkStory(int $storyID, int $planID)
    {
        $this->productplan->unlinkStory($storyID, $planID);
        $this->loadModel('action')->create('productplan', $planID, 'unlinkstory', '', $storyID);

        if($this->session->storyList) return $this->sendSuccess(array('load' => $this->session->storyList));
        return $this->sendSuccess(array('load' => $this->createLink('productplan', 'view', "planID=$planID&type=story")));
    }

    /**
     * 批量移除计划中的需求。
     * Batch unlink story.
     *
     * @param  int    $planID
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function batchUnlinkStory(int $planID, string $orderBy = 'id_desc')
    {
        if($this->post->storyIdList)
        {
            foreach($this->post->storyIdList as $storyID) $this->productplan->unlinkStory((int)$storyID, $planID);

            $this->loadModel('action')->create('productplan', $planID, 'unlinkstory', '', implode(',', $this->post->storyIdList));
        }
        return $this->sendSuccess(array('load' => $this->createLink('productplan', 'view', "planID=$planID&type=story&orderBy=$orderBy")));
    }

    /**
     * 计划管理Bug列表。
     * Link bug list.
     *
     * @param  int    $planID
     * @param  string $browseType
     * @param  string $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function linkBug(int $planID = 0, string $browseType = '', string $param = '0', string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 100, int $pageID = 1)
    {
        if(!empty($_POST['bugs']))
        {
            $this->productplan->linkBug($planID, $this->post->bugs);
            if($this->viewType == 'json') return $this->send(array('result' => 'success'));
            return $this->send(array('result' => 'success', 'load' => inlink('view', "planID={$planID}&type=bug&orderBy={$orderBy}")));
        }

        /* Set session. */
        $this->session->set('bugList', inlink('view', "planID=$planID&type=bug&orderBy=$orderBy&link=true&param=" . helper::safe64Encode("&browseType=$browseType&queryID=$param")), 'qa');

        /* Init vars. */
        $executions = $this->app->user->view->sprints . ',0';
        $plan       = $this->productplan->getByID($planID);
        $productID  = $plan->product;
        $queryID    = $browseType == 'bysearch' ? (int)$param : 0;

        /* Set drop menu. */
        $this->commonAction($productID);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->productplanZen->buildBugSearchForm($plan, $queryID, $orderBy);
        $planBugs = $this->loadModel('bug')->getPlanBugs($planID);
        if($browseType == 'bySearch')
        {
            $allBugs = $this->bug->getBySearch('bug', array($productID), $plan->branch, 0, 0, $queryID, implode(',', array_keys($planBugs)), 'id_desc', $pager);
        }
        else
        {
            $allBugs = $this->bug->getActiveBugs($productID, $plan->branch, $executions, array_keys($planBugs), $pager);
        }

        $this->view->allBugs    = $allBugs;
        $this->view->planBugs   = $planBugs;
        $this->view->plan       = $plan;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->browseType = $browseType;
        $this->view->param      = $param;
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->display();
    }

    /**
     * 移除计划中的Bug。
     * Remove bug from plan.
     *
     * @param  int    $bugID
     * @param  int    $planID
     * @access public
     * @return void
     */
    public function unlinkBug(int $bugID, int $planID)
    {
        $this->productplan->unlinkBug($bugID);
        $this->loadModel('action')->create('productplan', $planID, 'unlinkbug', '', $bugID);

        return $this->sendSuccess(array('load' => true));
    }

    /**
     * 批量移除计划中的Bug。
     * Batch unlink bug.
     *
     * @param  int    $planID
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function batchUnlinkBug(int $planID, string $orderBy = 'id_desc')
    {
        if($this->post->bugIdList)
        {
            foreach($this->post->bugIdList as $bugID) $this->productplan->unlinkBug((int)$bugID);

            $this->loadModel('action')->create('productplan', $planID, 'unlinkbug', '', implode(',', $this->post->bugIdList));
        }

        return $this->sendSuccess(array('load' => $this->createLink('productplan', 'view', "planID=$planID&type=bug&orderBy=$orderBy")));
    }

    /**
     * 获取分支冲突的需求和Bug。
     * AJAX: Get conflict story and bug.
     *
     * @param  int    $planID
     * @param  int    $newBranch
     * @access public
     * @return void
     */
    public function ajaxGetConflict(int $planID, int $newBranch)
    {
        $plan        = $this->productplan->getByID($planID);
        $oldBranch   = $plan->branch;
        $planStories = $this->loadModel('story')->getPlanStories($planID, 'all');
        $planBugs    = $this->loadModel('bug')->getPlanBugs($planID, 'all');
        $branchPairs = $this->loadModel('branch')->getPairs($plan->product);

        $removeBranches = '';
        foreach(explode(',', $oldBranch) as $oldBranchID)
        {
            if($oldBranchID and strpos(",$newBranch,", ",$oldBranchID,") === false) $removeBranches .= "{$branchPairs[$oldBranchID]},";
        }

        $conflictStoryCounts = 0;
        $conflictBugCounts   = 0;
        if($oldBranch)
        {
            foreach($planStories as $story)
            {
                if($story->branch and strpos(",$newBranch,", ",$story->branch,") === false) $conflictStoryCounts ++;
            }

            foreach($planBugs as $bug)
            {
                if($bug->branch and strpos(",$newBranch,", ",$bug->branch,") === false) $conflictBugCounts ++;
            }
        }

        if($conflictStoryCounts and $conflictBugCounts)
        {
            printf($this->lang->productplan->confirmChangePlan, trim($removeBranches, ','), $conflictStoryCounts, $conflictBugCounts);
        }
        elseif($conflictStoryCounts)
        {
            printf($this->lang->productplan->confirmRemoveStory, trim($removeBranches, ','), $conflictStoryCounts);
        }
        elseif($conflictBugCounts)
        {
            printf($this->lang->productplan->confirmRemoveBug, trim($removeBranches, ','), $conflictBugCounts);
        }
    }

    /**
     * 获取最近一次创建的计划。
     * AJAX: Get last plan.
     *
     * @param  int    $productID
     * @param  string $branch
     * @param  int    $parent
     * @access public
     * @return string
     */
    public function ajaxGetLast(int $productID, string $branch = '', int $parent = 0)
    {
        $lastPlan = $this->productplan->getLast($productID, $branch, $parent);
        echo json_encode($lastPlan);
    }

    /**
     * 获取父计划的分支。
     * AJAX: Get parent branches.
     *
     * @param  int    $productID
     * @param  int    $parentID
     * @access public
     * @return void
     */
    public function ajaxGetParentBranches(int $productID = 0, int $parentID = 0)
    {
        $branchPairs = $this->loadModel('branch')->getPairs($productID, 'active');
        if(!empty($parentID))
        {
            $parentBranches = array();
            $parentPlan     = $this->productplan->getByID($parentID);
            foreach(explode(',', $parentPlan->branch) as $parentBranchID)
            {
                if(!isset($branchPairs[$parentBranchID])) continue;
                $parentBranches[$parentBranchID] = $branchPairs[$parentBranchID];
            }
        }

        $branches = empty($parentID) ? $branchPairs : $parentBranches;
        $items    = array();
        foreach($branches as $id => $name)
        {
            if($id == '') continue;
            $items[] = array('text' => $name, 'value' => $id, 'keys' => $name);
        }
        return print(json_encode($items));
    }

    /**
     * 获取未关联计划的分支的提示信息。
     * AJAX: Get diff branches tips.
     *
     * @param  int    $productID
     * @param  int    $parentID
     * @param  string $branches
     * @access public
     * @return void
     */
    public function ajaxGetDiffBranchesTip(int $productID = 0, int $parentID = 0, string $branches = '')
    {
        if(empty($parentID) || empty($productID)) return;

        /* If it has children, return. */
        $parentBranch = $this->productplan->getByID($parentID);
        if($parentBranch->parent == '-1') return;

        /* Find diff branches between parent plan and child plan. */
        $diffBranches    = array();
        $diffBranchesTip = '';
        $product         = $this->loadModel('product')->getByID($productID);
        $branchPairs     = $this->loadModel('branch')->getPairs($productID);
        foreach(explode(',', $parentBranch->branch) as $parentBranchID)
        {
            if(empty($parentBranchID)) continue;
            if(strpos(",$branches,", ",$parentBranchID,") === false)
            {
                $diffBranches[$parentBranchID] = $parentBranchID;
                $diffBranchesTip .= "{$branchPairs[$parentBranchID]},";
            }
        }
        if(empty($diffBranchesTip)) return;

        /* Find stories and bugs in diff branches. */
        $unlinkStories = $this->productplan->checkUnlinkObjects($diffBranches, $parentID, 'story');
        $unlinkBugs    = $this->productplan->checkUnlinkObjects($diffBranches, $parentID, 'bug');
        if(empty($unlinkStories) && empty($unlinkBugs)) return;

        $this->lang->productplan->diffBranchesTip = str_replace('@branch@', $this->lang->product->branchName[$product->type], $this->lang->productplan->diffBranchesTip);
        printf($this->lang->productplan->diffBranchesTip, trim($diffBranchesTip, ','));
    }
}
