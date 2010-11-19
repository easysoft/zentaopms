<?php
/**
 * The control file of productplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     productplan
 * @version     $Id$
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
    public function commonAction($productID)
    {
        $this->loadModel('product');
        $this->view->product = $this->product->getById($productID);
        $this->view->position[] = html::a($this->createLink('product', 'browse', "productID={$this->view->product->id}"), $this->view->product->name);
        $this->product->setMenu($this->product->getPairs(), $productID);
    }

    /**
     * Create a plan.
     * 
     * @param  int    $product 
     * @access public
     * @return void
     */
    public function create($product = '')
    {
        if(!empty($_POST))
        {
            $planID = $this->productplan->create();
            if(dao::isError()) die(js::error(dao::getError()));
            $this->loadModel('action')->create('productplan', $planID, 'opened');
            die(js::locate($this->createLink('productplan', 'browse', "product=$product"), 'parent'));
        }

        $this->commonAction($product);

        $this->view->header->title = $this->lang->productplan->create;
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
        $this->commonAction($plan->product);
        $this->view->header->title = $this->lang->productplan->edit;
        $this->view->position[] = $this->lang->productplan->edit;
        $this->view->plan = $plan;
        $this->display();
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
            die(js::locate(inlink('browse', "productID=$plan->product"), 'parent'));
        }
    }

    /**
     * Browse plans.
     * 
     * @param  int    $product 
     * @access public
     * @return void
     */
    public function browse($product = 0)
    {
        $this->session->set('productPlanList', $this->app->getURI(true));
        $this->commonAction($product);
        $this->view->header->title = $this->lang->productplan->browse;
        $this->view->position[] = $this->lang->productplan->browse;
        $this->view->plans      = $this->productplan->getList($product);
        $this->display();
    }

    /**
     * View plan.
     * 
     * @param  int    $planID 
     * @access public
     * @return void
     */
    public function view($planID = 0)
    {
        $this->session->set('storyList', $this->app->getURI(true));

        $plan = $this->productplan->getByID($planID);
        if(!$plan) die(js::error($this->lang->notFound) . js::locate('back'));

        $this->commonAction($plan->product);

        $this->view->header->title = $this->lang->productplan->view;
        $this->view->position[] = $this->lang->productplan->view;
        $this->view->planStories= $this->loadModel('story')->getPlanStories($planID);
        $this->view->products   = $this->product->getPairs();
        $this->view->plan       = $plan;
        $this->view->actions    = $this->loadModel('action')->getList('productplan', $planID);
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->display();
    }

    /**
     * Link stories.
     * 
     * @param  int    $planID 
     * @access public
     * @return void
     */
    public function linkStory($planID = 0)
    {
        $this->session->set('storyList', $this->app->getURI(true));

        if(!empty($_POST)) $this->productplan->linkStory($planID);

        $plan = $this->productplan->getByID($planID);
        $this->commonAction($plan->product);
        $this->view->header->title = $this->lang->productplan->linkStory;
        $this->view->position[] = $this->lang->productplan->linkStory;
        $this->view->allStories = $this->loadModel('story')->getProductStories($this->view->product->id, $moduleID = '0', $status = 'draft,active,changed');
        $this->view->planStories= $this->story->getPlanStories($planID);
        $this->view->products   = $this->product->getPairs();
        $this->view->plan       = $plan;
        $this->view->plans      = $this->dao->select('id, end')->from(TABLE_PRODUCTPLAN)->fetchPairs();
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
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
    public function unlinkStory($storyID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->productplan->confirmUnlinkStory, $this->createLink('productplan', 'unlinkstory', "storyID=$storyID&confirm=yes")));
        }
        else
        {
            $this->productplan->unlinkStory($storyID);
            die(js::reload('parent'));
        }
    }
}
