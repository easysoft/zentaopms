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
 * @copyright   Copyright: 2009 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     productplan
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
class productplan extends control
{
    public function commonAction($productID)
    {
        $this->loadModel('product');
        $this->view->product = $this->product->findByID($productID);
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

        $this->view->header->title = $this->lang->productPlan->create;
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
        $this->view->header->title = $this->lang->productPlan->edit;
        $this->view->position[] = $this->lang->productPlan->edit;
        $this->view->plan = $plan;
        $this->display();
    }

    /* 删除计划。*/
    public function delete($planID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->productPlan->confirmDelete, $this->createLink('productPlan', 'delete', "planID=$planID&confirm=yes")));
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

        $this->view->header->title = $this->lang->productPlan->browse;
        $this->view->position[] = $this->lang->productPlan->browse;
        $this->view->plans      = $this->productplan->getList($product);
        $this->display();
    }
}
