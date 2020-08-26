<?php
/**
 * The control file of productplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     productplan
 * @version     $Id: control.php 4659 2013-04-17 06:45:08Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
class productplan extends control
{
    /**
     * Common actions
     *
     * @param  int $productID
     * @param  int $branch
     *
     * @access public
     * @return void
     */
    public function commonAction($productID, $branch = 0)
    {
        $this->loadModel('product');
        $this->app->loadConfig('project');
        $product = $this->product->getById($productID);
        if(empty($product)) $this->locate($this->createLink('product', 'create'));
        $this->view->product  = $product;
        $this->view->branch   = $branch;
        $this->view->branches = $product->type == 'normal' ? array() : $this->loadModel('branch')->getPairs($productID);
        $this->view->position[] = html::a($this->createLink('product', 'browse', "productID={$productID}&branch=$branch"), $product->name);
        $this->product->setMenu($this->product->getPairs(), $productID, $branch);
    }

    /**
     * Create a plan.
     *
     * @param string $product
     * @param int    $branch
     *
     * @access public
     * @return void
     */
    public function create($product = '', $branch = 0, $parent = 0)
    {
        if(!empty($_POST))
        {
            $planID = $this->productplan->create();
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->loadModel('action')->create('productplan', $planID, 'opened');

            $this->executeHooks($planID);

            if(isonlybody()) $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'callback' => 'parent.refreshPlan()'));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('productplan', 'browse', "productID=$product&branch=$branch")));
        }

        $this->commonAction($product, $branch);
        $lastPlan = $this->productplan->getLast($product, $branch, $parent);
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

        $this->view->title      = $this->view->product->name . $this->lang->colon . $this->lang->productplan->create;
        $this->view->position[] = $this->lang->productplan->common;
        $this->view->position[] = $this->lang->productplan->create;

        $this->view->lastPlan = $lastPlan;
        $this->view->branch   = $branch;
        $this->view->parent   = $parent;
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
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            if($changes)
            {
                $actionID = $this->loadModel('action')->create('productplan', $planID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }
            $this->executeHooks($planID);
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('view', "planID=$planID")));
        }

        $plan = $this->productplan->getByID($planID);
        $this->commonAction($plan->product, $plan->branch);
        $this->view->title      = $this->view->product->name . $this->lang->colon . $this->lang->productplan->edit;
        $this->view->position[] = $this->lang->productplan->edit;
        $this->view->plan = $plan;
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
        if(isset($_POST['planIDList']))
        {
            $this->commonAction($productID, $branch);
            $this->view->title      = $this->lang->productplan->batchEdit;
            $this->view->position[] = html::a(inlink('browse', "productID=$productID&branch=$branch"), $this->lang->productplan->common);
            $this->view->position[] = $this->lang->productplan->batchEdit;

            $this->view->plans = $this->productplan->getByIDList($this->post->planIDList);
            die($this->display());
        }
        elseif($_POST)
        {
            $changes = $this->productplan->batchUpdate($productID);
            $this->loadModel('action');
            foreach($changes as $planID => $change)
            {
                $actionID = $this->action->create('productplan', $planID, 'Edited');
                $this->action->logHistory($actionID, $change);
            }
            $this->loadModel('score')->create('ajax', 'batchOther');
            die(js::locate(inlink('browse', "productID=$productID&branch=$branch"), 'parent'));
        }
        die(js::locate('back'));
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
        if($plan->parent < 0) die(js::alert($this->lang->productplan->cannotDeleteParent));

        if($confirm == 'no')
        {
            die(js::confirm($this->lang->productplan->confirmDelete, $this->createLink('productPlan', 'delete', "planID=$planID&confirm=yes")));
        }
        else
        {
            $this->productplan->delete(TABLE_PRODUCTPLAN, $planID);
            if($plan->parent > 0) $this->productplan->changeParentField($planID);
            $this->executeHooks($planID);

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
                $this->send($response);
            }
            die(js::locate(inlink('browse', "productID=$plan->product&branch=$plan->branch"), 'parent'));
        }
    }

    /**
     * Browse plans.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  string $orderBy
     * @param  string $browseType
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse($productID = 0, $branch = 0, $browseType = 'all', $orderBy = 'begin_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1 )
    {
        $this->app->loadLang('project');
        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Append id for secend sort. */
        $sort = $this->loadModel('common')->appendOrder($orderBy);

        $this->session->set('productPlanList', $this->app->getURI(true));
        $this->commonAction($productID, $branch);
        $products               = $this->product->getPairs();
        $this->view->title      = $products[$productID] . $this->lang->colon . $this->lang->productplan->browse;
        $this->view->position[] = $this->lang->productplan->browse;
        $this->view->productID  = $productID;
        $this->view->branch     = $branch;
        $this->view->browseType = $browseType;
        $this->view->orderBy    = $orderBy;
        $this->view->plans      = $this->productplan->getList($productID, $branch, $browseType, $pager, $sort);
        $this->view->pager      = $pager;
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
    public function view($planID = 0, $type = 'story', $orderBy = 'id_desc', $link = 'false', $param = '', $recTotal = 0, $recPerPage = 100, $pageID = 1)
    {
        $plan = $this->productplan->getByID($planID, true);
        if(!$plan) die(js::error($this->lang->notFound) . js::locate('back'));

        $this->session->set('storyList', $this->app->getURI(true) . '&type=' . 'story');
        $this->session->set('bugList', $this->app->getURI(true) . '&type=' . 'bug');

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'mhtml') $recPerPage = 10;

        $reSort = false;
        if($type == 'story' && strpos($orderBy, 'order') !== false)
        {
            $orderBy = str_replace('order', 'id', $orderBy);
            $reSort  = true;
        }

        /* Append id for secend sort. */
        $sort = $this->loadModel('common')->appendOrder($orderBy);

        $this->commonAction($plan->product, $plan->branch);
        $products = $this->product->getPairs();

        $bugPager    = new pager(0, $recPerPage, $type == 'bug' ? $pageID : 1);
        $storyPager  = new pager(0, $recPerPage, $type == 'story' ? $pageID : 1);
        $planStories = $this->loadModel('story')->getPlanStories($planID, 'all', $type == 'story' ? $sort : 'id_desc', $storyPager);
        if($reSort)
        {
            if(!empty($plan->order))
            {
                $stories = array();
                $order   = explode(',', $plan->order);
                if(strpos($orderBy, 'desc') !== false) $order = array_reverse($order, true);
                foreach($order as $id)
                {
                    if(empty($id)) continue;
                    if(!isset($planStories[$id])) continue;
                    $stories[$id] = $planStories[$id];
                    unset($planStories[$id]);
                }
                if($planStories) $stories += $planStories;
                $planStories = $stories;
                unset($stories);
            }
            $orderBy = str_replace('id', 'order', $orderBy);
        }

        $this->executeHooks($planID);
        if($plan->parent > 0)     $this->view->parentPlan    = $this->productplan->getById($plan->parent);
        if($plan->parent == '-1') $this->view->childrenPlans = $this->productplan->getChildren($plan->id);

        $this->loadModel('datatable');
        $this->view->modulePairs = $this->loadModel('tree')->getOptionMenu($plan->product, 'story');
        $this->view->title       = "PLAN #$plan->id $plan->title/" . $products[$plan->product];
        $this->view->position[]  = $this->lang->productplan->view;
        $this->view->planStories = $planStories;
        $this->view->planBugs    = $this->loadModel('bug')->getPlanBugs($planID, 'all', $type == 'bug' ? $sort : 'id_desc', $bugPager);
        $this->view->products    = $products;
        $this->view->summary     = $this->product->summary($this->view->planStories);
        $this->view->plan        = $plan;
        $this->view->actions     = $this->loadModel('action')->getList('productplan', $planID);
        $this->view->users       = $this->loadModel('user')->getPairs('noletter');
        $this->view->plans       = $this->productplan->getPairs($plan->product, $plan->branch);
        $this->view->modules     = $this->loadModel('tree')->getOptionMenu($plan->product);
        $this->view->type        = $type;
        $this->view->orderBy     = $orderBy;
        $this->view->link        = $link;
        $this->view->param       = $param;
        $this->view->storyPager  = $storyPager;
        $this->view->bugPager    = $bugPager;
        $this->display();
    }

    /**
     * Ajax: Get product plans.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  string $number
     *
     * @access public
     * @return void
     */
    public function ajaxGetProductplans($productID, $branch = 0, $number = '')
    {
        $plans = $this->productplan->getPairs($productID, $branch);

         $planName = $number === '' ? 'plan' : "plan[$number]";
         $plans    = empty($plans) ? array('' => '') : $plans;
         die(html::select($planName, $plans, '', "class='form-control'"));
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
        $this->dao->update(TABLE_PRODUCTPLAN)->set('`order`')->eq($this->post->storys)->where('id')->eq((int)$planID)->exec();
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
    public function linkStory($planID = 0, $browseType = '', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 100, $pageID = 1)
    {
        if(!empty($_POST['stories']))
        {
            $this->productplan->linkStory($planID);
            die(js::locate(inlink('view', "planID=$planID&type=story&orderBy=$orderBy"), 'parent'));
        }

        $this->session->set('storyList', inlink('view', "planID=$planID&type=story&orderBy=$orderBy&link=true&param=" . helper::safe64Encode("&browseType=$browseType&queryID=$param")));

        $this->loadModel('story');
        $this->loadModel('tree');
        $plan = $this->productplan->getByID($planID);
        $this->commonAction($plan->product, $plan->branch);
        $products = $this->product->getPairs();

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
        $this->config->product->search['params']['plan']['values'] = $this->productplan->getForProducts(array($plan->product => $plan->product));
        $this->config->product->search['params']['module']['values']  = $this->tree->getOptionMenu($plan->product, $viewType = 'story', $startModuleID = 0);
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
            $branches = array('' => '') + $this->loadModel('branch')->getPairs($plan->product, 'noempty');
            if($plan->branch) $branches = array('' => '', $plan->branch => $branches[$plan->branch]);
            $this->config->product->search['params']['branch']['values'] = $branches;
        }
        $this->loadModel('search')->setSearchParams($this->config->product->search);

        $planStories = $this->story->getPlanStories($planID);

        if($browseType == 'bySearch')
        {
            $allStories = $this->story->getBySearch($plan->product, $plan->branch, $queryID, 'id', '', 'story', array_keys($planStories), $pager);
        }
        else
        {
            $allStories = $this->story->getProductStories($this->view->product->id, $plan->branch ? "0,{$plan->branch}" : 0, $moduleID = '0', $status = 'draft,active,changed', 'story', 'id_desc', $hasParent = false, array_keys($planStories), $pager);
        }

        $this->view->allStories  = $allStories;
        $this->view->planStories = $planStories;
        $this->view->products    = $products;
        $this->view->plan        = $plan;
        $this->view->plans       = $this->dao->select('id, end')->from(TABLE_PRODUCTPLAN)->fetchPairs();
        $this->view->users       = $this->loadModel('user')->getPairs('noletter');
        $this->view->browseType  = $browseType;
        $this->view->modules     = $this->loadModel('tree')->getOptionMenu($plan->product);
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
            die(js::confirm($this->lang->productplan->confirmUnlinkStory, $this->createLink('productplan', 'unlinkstory', "storyID=$storyID&planID=$planID&confirm=yes")));
        }
        else
        {
            $this->productplan->unlinkStory($storyID, $planID);

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
                $this->send($response);
            }
            die(js::reload('parent'));
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
        die(js::locate($this->createLink('productplan', 'view', "planID=$planID&type=story&orderBy=$orderBy"), 'parent'));
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
     *
     * @access public
     * @return void
     */
    public function linkBug($planID = 0, $browseType = '', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 100, $pageID = 1)
    {
        $projects = $this->app->user->view->projects . ',0';

        if(!empty($_POST['bugs']))
        {
            $this->productplan->linkBug($planID);
            die(js::locate(inlink('view', "planID=$planID&type=bug&orderBy=$orderBy"), 'parent'));
        }

        $this->session->set('bugList', inlink('view', "planID=$planID&type=bug&orderBy=$orderBy&link=true&param=" . helper::safe64Encode("&browseType=$browseType&queryID=$param")));
        $this->loadModel('bug');
        $plan = $this->productplan->getByID($planID);
        $this->commonAction($plan->product, $plan->branch);
        $products  = $this->product->getPairs('nocode');
        $productID = $plan->product;
        $queryID   = ($browseType == 'bysearch') ? (int)$param : 0;

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Build the search form. */
        $this->config->bug->search['actionURL'] = $this->createLink('productplan', 'view', "planID=$planID&type=bug&orderBy=$orderBy&link=true&param=" . helper::safe64Encode('&browseType=bySearch&queryID=myQueryID'));
        $this->config->bug->search['queryID']   = $queryID;
        $this->config->bug->search['style']     = 'simple';
        $this->config->bug->search['params']['product']['values']       = array($productID => $products[$productID], 'all' => $this->lang->bug->allProduct);
        $this->config->bug->search['params']['plan']['values']          = $this->productplan->getForProducts(array($plan->product => $plan->product));
        $this->config->bug->search['params']['module']['values']        = $this->loadModel('tree')->getOptionMenu($productID, $viewType = 'bug', $startModuleID = 0);
        $this->config->bug->search['params']['project']['values']       = $this->product->getProjectPairs($productID);
        $this->config->bug->search['params']['openedBuild']['values']   = $this->loadModel('build')->getProductBuildPairs($productID, $branch = 0, $params = '');
        $this->config->bug->search['params']['resolvedBuild']['values'] = $this->build->getProductBuildPairs($productID, $branch = 0, $params = '');
        if($this->session->currentProductType == 'normal')
        {
            unset($this->config->bug->search['fields']['branch']);
            unset($this->config->bug->search['params']['branch']);
        }
        else
        {
            $this->config->bug->search['fields']['branch'] = $this->lang->product->branch;
            $branches = array('' => '') + $this->loadModel('branch')->getPairs($plan->product, 'noempty');
            if($plan->branch) $branches = array('' => '', $plan->branch => $branches[$plan->branch]);
            $this->config->bug->search['params']['branch']['values'] = $branches;
        }
        $this->loadModel('search')->setSearchParams($this->config->bug->search);

        $planBugs = $this->bug->getPlanBugs($planID);

        if($browseType == 'bySearch')
        {
            $allBugs = $this->bug->getBySearch($plan->product, $plan->branch, $queryID, 'id_desc', array_keys($planBugs), $pager);
        }
        else
        {
            $allBugs = $this->bug->getActiveBugs($this->view->product->id, $plan->branch, $projects, array_keys($planBugs), $pager);
        }

        $this->view->allBugs    = $allBugs;
        $this->view->planBugs   = $planBugs;
        $this->view->products   = $products;
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
    public function unlinkBug($bugID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->productplan->confirmUnlinkBug, $this->createLink('productplan', 'unlinkbug', "bugID=$bugID&confirm=yes")));
        }
        else
        {
            $this->productplan->unlinkBug($bugID);

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
                $this->send($response);
            }
            die(js::reload('parent'));
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
        foreach($this->post->unlinkBugs as $bugID) $this->productplan->unlinkBug($bugID);
        die(js::locate($this->createLink('productplan', 'view', "planID=$planID&type=bug&orderBy=$orderBy"), 'parent'));
    }
}
