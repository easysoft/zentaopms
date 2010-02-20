<?php
/**
 * The control file of productplan module of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *                                                                             
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright 2009-2010 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     productplan
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
class productplan extends control
{
    /* 公共操作。*/
    public function commonAction($productID)
    {
        $this->loadModel('product');
        $this->view->product = $this->product->getById($productID);
        $this->view->position[] = html::a($this->createLink('product', 'browse', "productID={$this->view->product->id}"), $this->view->product->name);
        $this->product->setMenu($this->product->getPairs(), $productID);
    }

    /* 添加产品计划。*/
    public function create($product = '')
    {
        if(!empty($_POST))
        {
            $this->productplan->create();
            if(dao::isError()) die(js::error(dao::getError()));
            die(js::locate($this->createLink('productplan', 'browse', "product=$product"), 'parent'));
        }

        $this->commonAction($product);

        $this->view->header->title = $this->lang->productplan->create;
        $this->display();
    }

    /* 编辑产品计划。*/
    public function edit($planID)
    {
        if(!empty($_POST))
        {
            $this->productplan->update($planID);
            if(dao::isError()) die(js::error(dao::getError()));
            die(js::locate($this->createLink('productplan', 'browse', "product={$this->post->product}"), 'parent'));
        }

        $plan = $this->productplan->getByID($planID);
        $this->commonAction($plan->product);
        $this->view->header->title = $this->lang->productplan->edit;
        $this->view->position[] = $this->lang->productplan->edit;
        $this->view->plan = $plan;
        $this->display();
    }
                                                          
    /* 删除计划。*/
    public function delete($planID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->productplan->confirmDelete, $this->createLink('productPlan', 'delete', "planID=$planID&confirm=yes")));
        }
        else
        {
            $plan = $this->productplan->getById($planID);
            $this->productplan->delete($planID);
            die(js::locate($this->createLink('productplan', 'browse', "productID=$plan->product"), 'parent'));
        }
    }

    /* 浏览计划列表。*/
    public function browse($product = 0)
    {
        $this->commonAction($product);

        $this->view->header->title = $this->lang->productplan->browse;
        $this->view->position[] = $this->lang->productplan->browse;
        $this->view->plans      = $this->productplan->getList($product);
        $this->display();
    }

    /* 计划详情。*/
    public function view($planID = 0)
    {
        $plan = $this->productplan->getByID($planID);
        $this->commonAction($plan->product);
        $this->view->header->title = $this->lang->productplan->view;
        $this->view->position[] = $this->lang->productplan->view;
        $this->view->planStories= $this->loadModel('story')->getPlanStories($planID);
        $this->view->products   = $this->product->getPairs();
        $this->view->plan       = $plan;
        $this->display();
    }

    /* 关联需求。*/
    public function linkStory($planID = 0)
    {
        if(!empty($_POST)) $this->productplan->linkStory($planID);

        $plan = $this->productplan->getByID($planID);
        $this->commonAction($plan->product);
        $this->view->header->title = $this->lang->productplan->linkStory;
        $this->view->position[] = $this->lang->productplan->linkStory;
        $this->view->allStories = $this->loadModel('story')->getProductStories($this->view->product->id, $moduleID = '0', $status = 'draft,active,changed');
        $this->view->planStories= $this->story->getPlanStories($planID);
        $this->view->products   = $this->product->getPairs();
        $this->view->plan       = $plan;
        $this->display();
    }

    /* 移除一个需求。*/
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
