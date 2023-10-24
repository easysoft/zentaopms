<?php
/**
 * The control file of productplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     productplan
 * @version     $Id: control.php 4659 2013-04-17 06:45:08Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
class productplan extends control
{
    /**
     * Common actions.
     *
     * @param  int $productID
     * @param  int $branch
     *
     * @access public
     * @return void
     */
    public function commonAction($productID, $branch = 0)
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
            $projectID = $this->dao->select('project')->from(TABLE_PROJECTPRODUCT)->where('product')->eq($productID)->fetch('project');
            $this->loadModel('project')->setMenu($projectID);
        }

        $this->view->product         = $product;
        $this->view->projectID       = isset($projectID) ? $projectID : 0;
        $this->view->branch          = $branch;
        $this->view->branchOption    = $branchOption;
        $this->view->branchTagOption = $branchTagOption;
        $this->view->position[]      = html::a($this->createLink('product', 'browse', "productID={$productID}&branch=$branch"), $product->name);
    }

    /**
     * Create a plan.
     *
     * @param string $productID
     * @param int    $branchID
     * @param int    $parent
     *
     * @access public
     * @return void
     */
    public function create($productID = '', $branchID = 0, $parent = 0)
    {
        if(!empty($_POST))
        {
            $planID = $this->productplan->create();
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->loadModel('action')->create('productplan', $planID, 'opened');

            $message = $this->executeHooks($planID);
            if($message) $this->lang->saveSuccess = $message;

            if($parent > 0) $this->productplan->updateParentStatus($parent);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $planID));
            if(isonlybody()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => 'parent.refreshPlan()'));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink($this->app->rawModule, 'browse', "productID=$productID")));
        }

        $this->commonAction($productID, $branchID);
        $lastPlan = $this->productplan->getLast($productID, '', $parent);
        $product  = $this->loadModel('product')->getById($productID);

        if($lastPlan)
        {
            $timestamp = strtotime($lastPlan->end);
            $weekday   = date('w', $timestamp);
            $delta     = 1;
            if($weekday == '5' or $weekday == '6') $delta = 8 - $weekday;

            $begin = date('Y-m-d', strtotime("+$delta days", $timestamp));
        }
        $this->view->begin = $lastPlan ? $begin : date('Y-m-d');
        if($parent) $this->view->parentPlan = $this->productplan->getById($parent);
        $branchPairs = $product->type == 'normal' ? array() : $this->loadModel('branch')->getPairs($productID, 'active');

        /*Get default branch.*/
        $branchList = $this->loadModel('branch')->getList($productID);
        foreach($branchList as $branch)
        {
            if($branch->default) $defaultBranch = $branch->id;
        }

        $this->view->title      = $this->view->product->name . $this->lang->colon . $this->lang->productplan->create;
        $this->view->position[] = $this->lang->productplan->common;
        $this->view->position[] = $this->lang->productplan->create;

        $this->view->productID       = $productID;
        $this->view->lastPlan        = $lastPlan;
        $this->view->branch          = $branchID;
        $this->view->branches        = $branchPairs;
        $this->view->defaultBranch   = $defaultBranch;
        $this->view->parent          = $parent;
        $this->view->parentPlanPairs = $this->productplan->getTopPlanPairs($productID, 'done,closed');
        $this->display();
    }

    /**
     * Edit a plan.
     *
     * @param int $planID
     *
     * @access public
     * @return void
     */
    public function edit($planID)
    {
        if(!empty($_POST))
        {
            $changes = $this->productplan->update($planID);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $change[$planID] = $changes;
            $this->unlinkOldBranch($change);
            if($changes)
            {
                $actionID = $this->loadModel('action')->create('productplan', $planID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }
            $message = $this->executeHooks($planID);
            if($message) $this->lang->saveSuccess = $message;
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink($this->app->rawModule, 'view', "planID=$planID")));
        }

        $plan = $this->productplan->getByID($planID);
        $oldBranch = array($planID => $plan->branch);

        /* Get the parent plan pair exclusion itself. */
        $parentPlanPairs = $this->productplan->getTopPlanPairs($plan->product);
        unset($parentPlanPairs[$planID]);
        $this->view->parentPlanPairs = $parentPlanPairs;

        $this->commonAction($plan->product, $plan->branch);

        if($plan->parent > 0)
        {
            $parentPlan  = $this->productplan->getByID($plan->parent);
            $branchPairs = array();
            foreach(explode(',', $parentPlan->branch) as $parentBranchID) $branchPairs[$parentBranchID] = $this->view->branchTagOption[$parentBranchID];
            $this->view->branchTagOption = $branchPairs;
        }
        $this->view->title           = $this->view->product->name . $this->lang->colon . $this->lang->productplan->edit;
        $this->view->position[]      = $this->lang->productplan->edit;
        $this->view->productID       = $plan->product;
        $this->view->oldBranch       = $oldBranch;
        $this->view->plan            = $plan;
        $this->display();
    }

    /**
     * Batch edit plan.
     *
     * @param int $productID
     * @param int $branch
     *
     * @access public
     * @return void
     */
    public function batchEdit($productID, $branch = 0)
    {
        if(!empty($_POST['title']))
        {
            $changes = $this->productplan->batchUpdate($productID);
            $this->unlinkOldBranch($changes);
            $this->loadModel('action');
            foreach($changes as $planID => $change)
            {
                $actionID = $this->action->create('productplan', $planID, 'Edited');
                $this->action->logHistory($actionID, $change);
            }

            $this->loadModel('score')->create('ajax', 'batchOther');
            return print(js::locate($this->session->productPlanList, 'parent'));
        }

        if(!$this->post->planIDList) return print(js::locate($this->session->productPlanList, 'parent'));

        $this->commonAction($productID, $branch);

        $plans        = $this->productplan->getByIDList($this->post->planIDList);
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
        $this->view->product    = $this->loadModel('product')->getById($productID);;
        $this->view->parentList = $this->productplan->getByIDList($parentIdList);

        $this->display();
    }

    /**
     * Batch change the status of productplan.
     *
     * @param  string $status
     * @access public
     * @return void
     */
    public function batchChangeStatus($status, $productID)
    {
        $this->loadModel('product')->setMenu($productID);
        $this->loadModel('action');
        $planIDList = $this->post->planIDList;

        if($status !== 'closed')
        {
            $this->productplan->batchChangeStatus($status);
            return print(js::reload('parent'));
        }
        else
        {
            if($this->post->comments)
            {
                $allChanges = $this->productplan->batchChangeStatus($status);
                return print(js::locate(inlink('browse', "product=$productID")));
            }

            $plans = $this->dao->select('*')->from(TABLE_PRODUCTPLAN)->where('id')->in($planIDList)->fetchAll('id');

            $this->view->reasonList  = $this->lang->productplan->closedReasonList;
            $this->view->plans       = $plans;
            $this->view->productID   = $productID;
            $this->display();
        }
    }

    /**
     * Delete a plan.
     *
     * @param  int    $planID
     * @param  string $confirm  yes|no
     * @access public
     * @return void
     */
    public function delete($planID, $confirm = 'no')
    {
        $plan = $this->productplan->getById($planID);
        if($plan->parent < 0) return print(js::alert($this->lang->productplan->cannotDeleteParent));

        if($confirm == 'no')
        {
            return print(js::confirm($this->lang->productplan->confirmDelete, $this->createLink('productPlan', 'delete', "planID=$planID&confirm=yes")));
        }
        else
        {
            $this->productplan->delete(TABLE_PRODUCTPLAN, $planID);
            if($plan->parent > 0) $this->productplan->changeParentField($planID);
            $message = $this->executeHooks($planID);
            if($message) $this->lang->saveSuccess = $message;

            /* if ajax request, send result. */
            if($this->server->ajax)
            {
                if(dao::isError())
                {
                    $response['result']  = 'fail';
                    $response['message'] = dao::getError();
                }
                else
                {
                    $response['result']  = 'success';
                    $response['message'] = '';
                }
                return $this->send($response);
            }

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess));
            return print(js::reload('parent'));
        }
    }

    /**
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
    public function browse($productID = 0, $branch = '', $browseType = 'undone', $queryID = 0, $orderBy = 'begin_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $branchID = $branch === '' ? 'all' : $branch;
        if(!$branch) $branch = 0;

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Append id for secend sort. */
        $sort = common::appendOrder($orderBy);
        $this->session->set('productPlanList', $this->app->getURI(true), 'product');

        $viewType = $this->cookie->viewType ? $this->cookie->viewType : 'list';

        $this->commonAction($productID, $branch);
        $product          = $this->product->getById($productID);
        $productName      = empty($product) ? '' : $product->name;
        $branchList       = $this->branch->getList($productID, 0, 'all');
        $branchStatusList = array();
        foreach($branchList as $productBranch) $branchStatusList[$productBranch->id] = $productBranch->status;

        /* Build the search form. */
        $queryID   = $browseType == 'bySearch' ? (int)$queryID : 0;
        $actionURL = $this->createLink($this->app->rawModule, 'browse', "productID=$productID&branch=$branch&browseType=bySearch&queryID=myQueryID&orderBy=$orderBy&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID");
        $this->productplan->buildSearchForm($queryID, $actionURL, $product);

        if($viewType == 'kanban')
        {
            $branches    = array();
            $branchPairs = array();
            $planCount   = 0;
            if(!in_array($orderBy, array_keys($this->lang->productplan->orderList))) $orderBy = key($this->lang->productplan->orderList);

            if($product->type == 'normal')
            {
                $planGroup = $this->productplan->getList($product->id, 0, 'all', '', $orderBy, 'skipparent');

                $this->view->planCount  = count(array_filter($planGroup));
            }
            else
            {
                $planGroup = $this->productplan->getGroupByProduct($product->id, 'skipParent', '', $orderBy);
                $branches  = $this->branch->getPairs($product->id, 'active');

                foreach($branches as $id => $name)
                {
                    $plans            = isset($planGroup[$product->id][$id]) ? array_filter($planGroup[$product->id][$id]) : array();
                    $branchPairs[$id] = $name . ' ' . count($plans);
                    $planCount       += count($plans);
                }

                $this->view->branches = array('all' => $this->lang->productplan->allAB . ' ' . $planCount) + $branchPairs;
            }

            $this->view->branchID   = $branchID;
            $this->view->kanbanData = $this->loadModel('kanban')->getPlanKanban($product, $branchID, $planGroup);
        }

        $this->view->title            = $productName . $this->lang->colon . $this->lang->productplan->browse;
        $this->view->position[]       = $this->lang->productplan->browse;
        $this->view->productID        = $productID;
        $this->view->branch           = $branch;
        $this->view->branchStatusList = $branchStatusList;
        $this->view->productPlansNum  = $this->productplan->getList($productID, $branch, 'all');
        $this->view->browseType       = $browseType;
        $this->view->viewType         = $viewType;
        $this->view->orderBy          = $orderBy;
        $this->view->users            = $this->loadModel('user')->getPairs('noletter|nodeleted');
        $this->view->plans            = $this->productplan->getList($productID, $branch, $browseType, $pager, $sort, "", $queryID);
        $this->view->pager            = $pager;
        $this->view->projects         = $this->product->getProjectPairsByProduct($productID, $branch, '', $status = 'closed', 'multiple');
        $this->view->statusList       = $this->lang->productplan->featureBar['browse'];
        $this->view->queryID          = $queryID;
        $this->display();
    }

    /**
     * View plan.
     *
     * @param  int    $planID
     * @param  string $type
     * @param  string $orderBy
     * @param  string $link
     * @param  string $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     *
     * @access public
     * @return void
     */
    public function view($planID = 0, $type = 'story', $orderBy = 'order_desc', $link = 'false', $param = '', $recTotal = 0, $recPerPage = 100, $pageID = 1)
    {
        $planID = (int)$planID;
        $plan   = $this->productplan->getByID($planID, true);
        if(!$plan)
        {
            if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'fail', 'code' => 404, 'message' => '404 Not found'));
            return print(js::error($this->lang->notFound) . js::locate($this->createLink('product', 'index')));
        }

        if($type == 'story' and ($orderBy != 'order_desc' or $pageID != 1 or $recPerPage != 100))
        {
            $this->session->set('storyList', $this->app->getURI(true), 'product');
        }
        else
        {
            $this->session->set('storyList', $this->createLink('productplan', 'view', "planID=$planID&type=story"), 'product');
        }
        if($type == 'bug' and ($orderBy != 'order_desc' or $pageID != 1 or $recPerPage != 100))
        {
            $this->session->set('bugList', $this->app->getURI(true), 'qa');
        }
        else
        {
            $this->session->set('bugList', $this->createLink('productplan', 'view', "planID=$planID&type=bug"), 'qa');
        }

        /* Determines whether an object is editable. */
        $canBeChanged = common::canBeChanged('plan', $plan);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;
        if($this->app->getViewType() == 'xhtml') $recPerPage = 10;

        /* Append id for secend sort. */
        $orderBy = ($type == 'bug' and $orderBy == 'order_desc') ? 'id_desc' : $orderBy;
        $sort    = common::appendOrder($orderBy);
        if(strpos($sort, 'pri_') !== false) $sort = str_replace('pri_', 'priOrder_', $sort);

        $this->commonAction($plan->product, $plan->branch);
        $products = $this->product->getProductPairsByProject($this->session->project);

        $bugPager   = new pager(0, $recPerPage, $type == 'bug' ? $pageID : 1);
        $storyPager = new pager(0, $recPerPage, $type == 'story' ? $pageID : 1);

        /* Get stories of plan. */
        $this->loadModel('story');
        $planStories = $this->story->getPlanStories($planID, 'all', $type == 'story' ? $sort : 'id_desc', $storyPager);

        $this->executeHooks($planID);

        if($plan->parent > 0)     $this->view->parentPlan    = $this->productplan->getById($plan->parent);
        if($plan->parent == '-1') $this->view->childrenPlans = $this->productplan->getChildren($plan->id);

        $storyIdList = array();
        $modulePairs = $this->loadModel('tree')->getOptionMenu($plan->product, 'story', 0, 'all');
        foreach($planStories as $story)
        {
            if(!isset($modulePairs[$story->module])) $modulePairs += $this->tree->getModulesName($story->module);
            $storyIdList[] = $story->id;
        }

        $this->loadModel('datatable');
        $this->view->modulePairs  = $modulePairs;
        $this->view->title        = "PLAN #$plan->id $plan->title/" . zget($products, $plan->product, '');
        $this->view->position[]   = $this->lang->productplan->view;
        $this->view->planStories  = $planStories;
        $this->view->planBugs     = $this->loadModel('bug')->getPlanBugs($planID, 'all', $type == 'bug' ? $sort : 'id_desc', $bugPager);
        $this->view->products     = $products;
        $this->view->summary      = $this->product->summary($this->view->planStories);
        $this->view->plan         = $plan;
        $this->view->actions      = $this->loadModel('action')->getList('productplan', $planID);
        $this->view->users        = $this->loadModel('user')->getPairs('noletter');
        $this->view->plans        = $this->productplan->getPairs($plan->product, $plan->branch, '', true);
        $this->view->modules      = $this->loadModel('tree')->getOptionMenu($plan->product);
        $this->view->type         = $type;
        $this->view->orderBy      = $orderBy;
        $this->view->link         = $link;
        $this->view->param        = $param;
        $this->view->storyPager   = $storyPager;
        $this->view->bugPager     = $bugPager;
        $this->view->canBeChanged = $canBeChanged;
        $this->view->storyCases   = $storyCases = $this->loadModel('testcase')->getStoryCaseCounts($storyIdList);;

        if($this->app->getViewType() == 'json')
        {
            unset($this->view->storyPager);
            unset($this->view->bugPager);
        }
        $this->display();
    }

    /**
     * Start a plan.
     *
     * @param  int    $planID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function start($planID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            return print(js::confirm($this->lang->productplan->confirmStart, $this->createLink('productplan', 'start', "planID=$planID&confirm=yes")));
        }
        else
        {
            $this->productplan->updateStatus($planID, 'doing', 'started');

            if(dao::isError()) return print(js::error(dao::getError()));
            return print(js::reload('parent'));
        }
    }

    /**
     * Finish a plan.
     *
     * @param  int    $planID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function finish($planID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            return print(js::confirm($this->lang->productplan->confirmFinish, $this->createLink('productplan', 'finish', "planID=$planID&confirm=yes")));
        }
        else
        {
            $this->productplan->updateStatus($planID, 'done', 'finished');

            if(dao::isError()) return print(js::error(dao::getError()));
            return print(js::reload('parent'));
        }
    }

    /**
     * Close a plan.
     *
     * @param  int    $planID
     * @access public
     * @return void
     */
    public function close($planID)
    {
        $plan = $this->productplan->getById($planID);

        if(!empty($_POST))
        {
            $this->productplan->updateStatus($planID, 'closed', 'closed');
            if(dao::isError()) return print(js::error(dao::getError()));
            return print(js::reload('parent.parent'));
        }

        $this->view->productplan = $plan;
        $this->view->reasonList  = $this->lang->productplan->closedReasonList;
        $this->view->actions     = $this->loadModel('action')->getList('productplan', $planID);
        $this->view->users       = $this->loadModel('user')->getPairs();
        $this->display();
    }

    /**
     * Activate a plan.
     *
     * @param  int    $planID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function activate($planID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            return print(js::confirm($this->lang->productplan->confirmActivate, $this->createLink('productplan', 'activate', "planID=$planID&confirm=yes"), 'parent'));
        }
        else
        {
            $this->productplan->updateStatus($planID, 'doing', 'activated');

            if(dao::isError()) return print(js::error(dao::getError()));
            return print(js::reload('parent'));
        }
    }

    /**
     * Ajax: Get product plans.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  string $number
     * @param  string $expired
     * @access public
     * @return void
     */
    public function ajaxGetProductplans($productID, $branch = 0, $number = '', $expired = '')
    {
        $plans    = $this->productplan->getPairs($productID, empty($branch) ? '' : $branch, $expired, true);
        $planName = $number === '' ? 'plan' : "plan[$number]";
        $plans    = empty($plans) ? array('' => '') : $plans;
        echo html::select($planName, $plans, '', "class='form-control'");
    }

    /**
     * Sort story for productplan.
     *
     * @param int $planID
     *
     * @access public
     * @return bool
     */
    public function ajaxStorySort($planID = 0)
    {
        if(empty($planID)) return true;

        /* Get story id list. */
        $storyIDList = explode(',', trim($this->post->stories, ','));

        /* Update the story order according to the plan. */
        $this->loadModel('story')->sortStoriesOfPlan($planID, $storyIDList, $this->post->orderBy, $this->post->pageID, $this->post->recPerPage);
    }

    /**
     * Get projects by product id.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @access public
     * @return void
     */
    public function ajaxGetProjects($productID, $branch = 0)
    {
        $projects = $this->loadModel('product')->getProjectPairsByProduct($productID, $branch, '', $status = 'closed', 'multiple');
        echo html::select('project', $projects, key($projects), "class='form-control chosen'");
    }

    /**
     * Link stories.
     *
     * @param int    $planID
     * @param string $browseType
     * @param int    $param
     * @param string $orderBy
     * @param int    $recTotal
     * @param int    $recPerPage
     * @param int    $pageID
     *
     * @access public
     * @return void
     */
    public function linkStory($planID = 0, $browseType = '', $param = 0, $orderBy = 'order_desc', $recTotal = 0, $recPerPage = 100, $pageID = 1)
    {
        if(!empty($_POST['stories']))
        {
            $this->productplan->linkStory($planID);
            if($this->viewType == 'json') return $this->send(array('result' => 'success'));
            return print(js::locate(inlink('view', "planID=$planID&type=story&orderBy=$orderBy"), 'parent'));
        }

        $this->session->set('storyList', inlink('view', "planID=$planID&type=story&orderBy=$orderBy&link=true&param=" . helper::safe64Encode("&browseType=$browseType&queryID=$param")), 'product');

        $this->loadModel('story');
        $this->loadModel('tree');
        $plan = $this->productplan->getByID($planID);
        $this->commonAction($plan->product, $plan->branch);
        $products = $this->product->getProductPairsByProject($this->session->project);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Build search form. */
        $queryID = ($browseType == 'bySearch') ? (int)$param : 0;
        unset($this->config->product->search['fields']['product']);
        $this->config->product->search['actionURL'] = $this->createLink('productplan', 'view', "planID=$planID&type=story&orderBy=$orderBy&link=true&param=" . helper::safe64Encode('&browseType=bySearch&queryID=myQueryID'));
        $this->config->product->search['queryID']   = $queryID;
        $this->config->product->search['style']     = 'simple';
        $this->config->product->search['params']['product']['values'] = $products + array('all' => $this->lang->product->allProductsOfProject);
        $this->config->product->search['params']['plan']['values'] = $this->productplan->getPairsForStory($plan->product, $plan->branch, 'skipParent|withMainPlan');
        $this->config->product->search['params']['module']['values'] = $this->loadModel('tree')->getOptionMenu($plan->product, 'story', 0, 'all');
        $storyStatusList = $this->lang->story->statusList;
        unset($storyStatusList['closed']);
        $this->config->product->search['params']['status'] = array('operator' => '=', 'control' => 'select', 'values' => $storyStatusList);
        if($this->session->currentProductType == 'normal')
        {
            unset($this->config->product->search['fields']['branch']);
            unset($this->config->product->search['params']['branch']);
        }
        else
        {
            $this->config->product->search['fields']['branch'] = $this->lang->product->branch;

            $branchPairs = $this->dao->select('id, name')->from(TABLE_BRANCH)->where('id')->in($plan->branch)->fetchPairs();
            $branches   = array('' => '', BRANCH_MAIN => $this->lang->branch->main) + $branchPairs;
            $this->config->product->search['params']['branch']['values'] = $branches;
        }
        $this->loadModel('search')->setSearchParams($this->config->product->search);

        $planStories = $this->story->getPlanStories($planID);

        if($browseType == 'bySearch')
        {
            $allStories = $this->story->getBySearch($plan->product, "0,{$plan->branch}", $queryID, 'id', '', 'story', array_keys($planStories), '', $pager);
        }
        else
        {
            $allStories = $this->story->getProductStories($this->view->product->id, $plan->branch ? "0,{$plan->branch}" : 0, '0', 'draft,reviewing,active,changing', 'story', 'id_desc', $hasParent = false, array_keys($planStories), $pager);
        }

        $modules = $this->loadModel('tree')->getOptionMenu($plan->product, 'story', 0, 'all');
        foreach($allStories as $story)
        {
            if(!isset($modules[$story->module])) $modules += $this->tree->getModulesName($story->module);
        }

        $this->view->allStories  = $allStories;
        $this->view->planStories = $planStories;
        $this->view->products    = $products;
        $this->view->plan        = $plan;
        $this->view->plans       = $this->dao->select('id, end')->from(TABLE_PRODUCTPLAN)->fetchPairs();
        $this->view->users       = $this->loadModel('user')->getPairs('noletter');
        $this->view->browseType  = $browseType;
        $this->view->modules     = $modules;
        $this->view->param       = $param;
        $this->view->orderBy     = $orderBy;
        $this->view->pager       = $pager;
        $this->display();
    }

    /**
     * Unlink story
     *
     * @param  int    $storyID
     * @param  int    $planID
     * @param  string $confirm  yes|no
     * @access public
     * @return void
     */
    public function unlinkStory($storyID, $planID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            return print(js::confirm($this->lang->productplan->confirmUnlinkStory, inlink('unlinkstory', "storyID=$storyID&planID=$planID&confirm=yes")));
        }
        else
        {
            $this->productplan->unlinkStory($storyID, $planID);
            $this->loadModel('action')->create('productplan', $planID, 'unlinkstory', '', $storyID);

            if($this->session->storyList)
            {
                return print(js::locate($this->session->storyList, 'parent'));
            }
            else
            {
                return print(js::locate($this->createLink('productplan', 'view', "planID=$planID&type=story"), 'parent'));
            }
        }
    }

    /**
     * Batch unlink story.
     *
     * @param int    $planID
     * @param string $orderBy
     *
     * @access public
     * @return void
     */
    public function batchUnlinkStory($planID, $orderBy = 'id_desc')
    {
        foreach($this->post->storyIdList as $storyID) $this->productplan->unlinkStory($storyID, $planID);
        $this->loadModel('action')->create('productplan', $planID, 'unlinkstory', '', implode(',', $this->post->storyIdList));
        return print(js::locate($this->createLink('productplan', 'view', "planID=$planID&type=story&orderBy=$orderBy"), 'parent'));
    }

    /**
     * Link bugs.
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
    public function linkBug($planID = 0, $browseType = '', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 100, $pageID = 1)
    {
        if(!empty($_POST['bugs']))
        {
            $this->productplan->linkBug($planID);
            if($this->viewType == 'json') return $this->send(array('result' => 'success'));
            return print(js::locate(inlink('view', "planID=$planID&type=bug&orderBy=$orderBy"), 'parent'));
        }

        /* Load module and set session. */
        $this->loadModel('bug');
        $this->session->set('bugList', inlink('view', "planID=$planID&type=bug&orderBy=$orderBy&link=true&param=" . helper::safe64Encode("&browseType=$browseType&queryID=$param")), 'qa');

        /* Init vars. */
        $executions = $this->app->user->view->sprints . ',0';
        $plan       = $this->productplan->getByID($planID);
        $productID  = $plan->product;
        $queryID    = ($browseType == 'bysearch') ? (int)$param : 0;

        /* Set drop menu. */
        $this->commonAction($productID, $plan->branch);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Build the search form. */
        $this->config->bug->search['actionURL'] = $this->createLink('productplan', 'view', "planID=$planID&type=bug&orderBy=$orderBy&link=true&param=" . helper::safe64Encode('&browseType=bySearch&queryID=myQueryID'));
        $this->config->bug->search['queryID']   = $queryID;
        $this->config->bug->search['style']     = 'simple';
        $this->config->bug->search['params']['plan']['values']          = $this->productplan->getPairsForStory($productID, $plan->branch, 'skipParent|withMainPlan');
        $this->config->bug->search['params']['execution']['values']     = $this->loadModel('product')->getExecutionPairsByProduct($plan->product, $plan->branch);
        $this->config->bug->search['params']['openedBuild']['values']   = $this->loadModel('build')->getBuildPairs($productID, $branch = 'all', $params = '');
        $this->config->bug->search['params']['resolvedBuild']['values'] = $this->build->getBuildPairs($productID, $branch = 'all', $params = '');
        $this->config->bug->search['params']['module']['values']        = $this->loadModel('tree')->getOptionMenu($plan->product, 'bug', 0, 'all');
        $this->config->bug->search['params']['openedBuild']['values']   = $this->loadModel('build')->getBuildPairs($productID, $branch = 'all', $params = 'releasetag');
        $this->config->bug->search['params']['resolvedBuild']['values'] = $this->config->bug->search['params']['openedBuild']['values'];
        $this->config->bug->search['params']['module']['values']        = $this->loadModel('tree')->getOptionMenu($plan->product, 'bug', 0, 'all');
        $this->config->bug->search['params']['project']['values']       = $this->product->getProjectPairsByProduct($productID, $plan->branch);

        unset($this->config->bug->search['fields']['product']);
        if($this->session->currentProductType == 'normal')
        {
            unset($this->config->bug->search['fields']['branch']);
            unset($this->config->bug->search['params']['branch']);
        }
        else
        {
            $this->config->bug->search['fields']['branch'] = $this->lang->product->branch;

            $branchPairs = $this->dao->select('id, name')->from(TABLE_BRANCH)->where('id')->in($plan->branch)->fetchPairs();
            $branches   = array('' => '', BRANCH_MAIN => $this->lang->branch->main) + $branchPairs;
            $this->config->bug->search['params']['branch']['values'] = $branches;
        }
        $this->loadModel('search')->setSearchParams($this->config->bug->search);

        $planBugs = $this->bug->getPlanBugs($planID);

        if($browseType == 'bySearch')
        {
            $allBugs = $this->bug->getBySearch($productID, $plan->branch, $queryID, 'id_desc', array_keys($planBugs), $pager);
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
     * Unlink story
     *
     * @param  int    $bugID
     * @param  string $confirm  yes|no
     * @access public
     * @return void
     */
    public function unlinkBug($bugID, $planID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            return print(js::confirm($this->lang->productplan->confirmUnlinkBug, inlink('unlinkbug', "bugID=$bugID&planID=$planID&confirm=yes")));
        }
        else
        {
            $this->productplan->unlinkBug($bugID);
            $this->loadModel('action')->create('productplan', $planID, 'unlinkbug', '', $bugID);

            return print(js::reload('parent'));
        }
    }

    /**
     * Batch unlink story.
     *
     * @param        $planID
     * @param string $orderBy
     *
     * @access public
     * @return void
     */
    public function batchUnlinkBug($planID, $orderBy = 'id_desc')
    {
        foreach($this->post->bugIDList as $bugID) $this->productplan->unlinkBug($bugID);
        $this->loadModel('action')->create('productplan', $planID, 'unlinkbug', '', implode(',', $this->post->bugIDList));
        return print(js::locate($this->createLink('productplan', 'view', "planID=$planID&type=bug&orderBy=$orderBy"), 'parent'));
    }

    /**
     * Unlink story and bug when edit branch of plan.
     * @param  int    $planID
     * @param  int    $oldBranch
     * @access protected
     * @return void
     */
    protected function unlinkOldBranch($changes)
    {
        foreach($changes as $planID => $changes)
        {
            $oldBranch = '';
            $newBranch = '';
            foreach($changes as $changeId => $change)
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
                    if($story->branch and strpos(",$newBranch,", ",$story->branch,") === false) $this->productplan->unlinkStory($storyID, $planID);
                }

                foreach($planBugs as $bugID => $bug)
                {
                    if($bug->branch and strpos(",$newBranch,", ",$bug->branch,") === false) $this->productplan->unlinkBug($bugID, $planID);
                }
            }
        }
    }

    /**
     * AJAX: Get conflict story and bug.
     *
     * @param  int    $planID
     * @param  int    $branch
     * @access public
     * @return void
     */
    public function ajaxGetConflict($planID, $newBranch)
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
            foreach($planStories as $storyID => $story)
            {
                if($story->branch and strpos(",$newBranch,", ",$story->branch,") === false) $conflictStoryCounts ++;
            }

            foreach($planBugs as $bugID => $bug)
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
     * AJAX: Get last plan.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  int    $parent
     * @access public
     * @return object
     */
    public function ajaxGetLast($productID, $branch = 0, $parent = 0)
    {
        $lastPlan = $this->productplan->getLast($productID, $branch, $parent);
        echo json_encode($lastPlan);
    }

    /**
     * AJAX: Get parent branches.
     *
     * @param  int    $productID
     * @param  int    $parentID
     * @param  string $currentBranches
     * @access public
     * @return void
     */
    public function ajaxGetParentBranches($productID = 0, $parentID = 0, $currentBranches = '')
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
                if(!empty($currentBranches) and strpos(",$currentBranches,", ",$parentBranchID,") === false) $currentBranches = str_replace(",$parentBranchID,", ',', $currentBranches);
            }
        }
        return print(html::select('branch[]', empty($parentID) ? $branchPairs : $parentBranches, trim($currentBranches, ','), "class='form-control chosen' multiple"));
    }

    /**
     * AJAX: Get diff branches tips.
     *
     * @param  int    $productID
     * @param  int    $parentID
     * @param  string $branches
     * @access public
     * @return void
     */
    public function ajaxGetDiffBranchesTip($productID = 0, $parentID = 0, $branches = '')
    {
        if(empty($parentID) or empty($productID)) return;

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
        $unlinkStories = $this->dao->select('*')->from(TABLE_STORY)->where('branch')->in($diffBranches)->andWhere("CONCAT(',', plan, ',')")->like("%,{$parentID},%")->fetchAll('id');
        $unlinkBugs    = $this->dao->select('*')->from(TABLE_BUG)->where('branch')->in($diffBranches)->andWhere('plan')->eq($parentID)->fetchAll('id');
        if(empty($unlinkStories) and empty($unlinkBugs)) return;

        $this->lang->productplan->diffBranchesTip = str_replace('@branch@', $this->lang->product->branchName[$product->type], $this->lang->productplan->diffBranchesTip);
        printf($this->lang->productplan->diffBranchesTip, trim($diffBranchesTip, ','));
    }
}
