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
     * @param  int    $productID 
     * @access public
     * @return void
     */
    public function commonAction($productID, $branch = 0)
    {
        $this->loadModel('product');
        $this->app->loadConfig('project');
        $product = $this->product->getById($productID);
        $this->view->product  = $product;
        $this->view->branch   = $branch;
        $this->view->branches = $product->type == 'normal' ? array() : $this->loadModel('branch')->getPairs($productID);
        $this->view->position[] = html::a($this->createLink('product', 'browse', "productID={$this->view->product->id}&branch=$branch"), $this->view->product->name);
        $this->product->setMenu($this->product->getPairs(), $productID, $branch);
    }

    /**
     * Create a plan.
     * 
     * @param  int    $product 
     * @access public
     * @return void
     */
    public function create($product = '', $branch = 0)
    {
        if(!empty($_POST))
        {
            $planID = $this->productplan->create();
            if(dao::isError()) die(js::error(dao::getError()));
            $this->loadModel('action')->create('productplan', $planID, 'opened');
            die(js::locate($this->createLink('productplan', 'browse', "productID=$product&branch=$branch"), 'parent'));
        }

        $this->commonAction($product, $branch);
        $lastPlan = $this->productplan->getLast($product);
        if($lastPlan)
        {
            $timestamp = strtotime($lastPlan->end);
            $weekday   = date('w', $timestamp);
            $delta     = 1;
            if($weekday == '5' or $weekday == '6') $delta = 8 - $weekday;

            $begin = date('Y-m-d', strtotime("+$delta days", $timestamp));
        }
        $this->view->begin = $lastPlan ? $begin : '';

        $this->view->title = $this->view->product->name . $this->lang->colon . $this->lang->productplan->create;
        $this->view->lastPlan = $lastPlan;
        $this->view->position[] = $this->lang->productplan->common;
        $this->view->position[] = $this->lang->productplan->create;
        $this->display();
    }

    /**
     * Edit a plan.
     * 
     * @param  int    $planID 
     * @access public
     * @return void
     */
    public function edit($planID)
    {
        if(!empty($_POST))
        {
            $changes = $this->productplan->update($planID);
            if(dao::isError()) die(js::error(dao::getError()));
            if($changes)
            {
                $actionID = $this->loadModel('action')->create('productplan', $planID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }
            die(js::locate(inlink('view', "planID=$planID"), 'parent'));
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
     * @param  int    $productID 
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
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->productplan->confirmDelete, $this->createLink('productPlan', 'delete', "planID=$planID&confirm=yes")));
        }
        else
        {
            $plan = $this->productplan->getById($planID);
            $this->productplan->delete(TABLE_PRODUCTPLAN, $planID);

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
     * @param  int    $product 
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
     * @access public
     * @return void
     */
    public function view($planID = 0, $type = 'story', $orderBy = 'id_desc', $link = 'false', $param = '')
    {
        $this->session->set('storyList', $this->app->getURI(true) . '&type=' . 'story');
        $this->session->set('bugList', $this->app->getURI(true) . '&type=' . 'bug');

        /* Append id for secend sort. */
        $sort = $this->loadModel('common')->appendOrder($orderBy);

        $plan = $this->productplan->getByID($planID, true);
        if(!$plan) die(js::error($this->lang->notFound) . js::locate('back'));
        $this->commonAction($plan->product, $plan->branch);
        $products                = $this->product->getPairs();

        $this->loadModel('datatable');
        $showModule = !empty($this->config->datatable->productBrowse->showModule) ? $this->config->datatable->productBrowse->showModule : '';
        $this->view->modulePairs = $showModule ? $this->loadModel('tree')->getModulePairs($plan->product, 'story', $showModule) : array();

        $this->view->title       = "PLAN #$plan->id $plan->title/" . $products[$plan->product];
        $this->view->position[]  = $this->lang->productplan->view;
        $this->view->planStories = $this->loadModel('story')->getPlanStories($planID, 'all', $type == 'story' ? $sort : 'id_desc');
        $this->view->planBugs    = $this->loadModel('bug')->getPlanBugs($planID, 'all', $type == 'bug' ? $sort : 'id_desc');
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
        $this->display();
    }

    /**
     * Ajax: Get product plans. 
     * 
     * @param  int    $productID 
     * @param  string $number
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
     * Link stories.
     * 
     * @param  int    $planID 
     * @access public
     * @return void
     */
    public function linkStory($planID = 0, $browseType = '', $param = 0, $orderBy = 'id_desc')
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

        if($browseType == 'bySearch')
        {
            $allStories = $this->story->getBySearch($plan->product, $queryID, 'id', $pager = null, $projectID = '', $plan->branch);
            foreach($allStories as $key => $story)
            {
                if($story->status == 'closed') unset($allStories[$key]);
            }
        }
        else
        {
            $allStories = $this->story->getProductStories($this->view->product->id, $plan->branch ? "0,{$plan->branch}" : 0, $moduleID = '0', $status = 'draft,active,changed');
        }

        $this->view->allStories = $allStories;
        $this->view->planStories= $this->story->getPlanStories($planID);
        $this->view->products   = $products;
        $this->view->plan       = $plan;
        $this->view->plans      = $this->dao->select('id, end')->from(TABLE_PRODUCTPLAN)->fetchPairs();
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->browseType = $browseType;
        $this->view->modules    = $this->loadModel('tree')->getOptionMenu($plan->product);
        $this->view->param      = $param;
        $this->view->orderBy    = $orderBy;
        $this->display();
    }

    /**
     * Unlink story 
     * 
     * @param  int    $storyID 
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
     * @param  string $confirm 
     * @access public
     * @return void
     */
    public function batchUnlinkStory($planID, $orderBy = 'id_desc')
    {
        foreach($this->post->storyIDList as $storyID) $this->productplan->unlinkStory($storyID, $planID);
        die(js::locate($this->createLink('productplan', 'view', "planID=$planID&type=story&orderBy=$orderBy"), 'parent'));
    }

    /**
     * Link bugs.
     * 
     * @param  int    $planID 
     * @param  string $browseType 
     * @param  int    $param 
     * @access public
     * @return void
     */
    public function linkBug($planID = 0, $browseType = '', $param = 0, $orderBy = 'id_desc')
    {
        $projects = $this->loadModel('project')->getPairs();
        $projects[0] = '';

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

        if($browseType == 'bySearch')
        {
            $allBugs = $this->bug->getBySearch($plan->product, $queryID, 'id_desc', null, $plan->branch);
            foreach($allBugs as $key => $bug)
            {
                if($bug->status != 'active') unset($allBugs[$key]);
            }
        }
        else
        {
            $projects = $this->loadModel('project')->getPairs();
            $projects[0] = '';
            $allBugs= $this->bug->getActiveBugs($this->view->product->id, $plan->branch, $projects);
        }

        $this->view->allBugs    = $allBugs;
        $this->view->planBugs   = $this->bug->getPlanBugs($planID);
        $this->view->products   = $products;
        $this->view->plan       = $plan;
        $this->view->plans      = $this->dao->select('id, end')->from(TABLE_PRODUCTPLAN)->fetchPairs();
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->browseType = $browseType;
        $this->view->param      = $param;
        $this->view->orderBy    = $orderBy;
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
     * @param  string $confirm 
     * @access public
     * @return void
     */
    public function batchUnlinkBug($planID, $orderBy = 'id_desc')
    {
        foreach($this->post->unlinkBugs as $bugID) $this->productplan->unlinkBug($bugID);
        die(js::locate($this->createLink('productplan', 'view', "planID=$planID&type=bug&orderBy=$orderBy"), 'parent'));
    }
}
