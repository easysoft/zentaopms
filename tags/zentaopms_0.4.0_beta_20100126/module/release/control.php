<?php
/**
 * The control file of release module of ZenTaoMS.
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
 * @package     release
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
class release extends control
{
   /* 公共操作。*/
    public function commonAction($productID)
    {
        $this->loadModel('product');
        $this->view->product = $this->product->findByID($productID);
        $this->view->position[] = html::a($this->createLink('product', 'browse', "productID={$this->view->product->id}"), $this->view->product->name);
        $this->product->setMenu($this->product->getPairs(), $productID);
    }

    /* 浏览发布列表。*/
    public function browse($productID)
    {
        $this->commonAction($productID);
        $this->view->header->title = $this->lang->release->browse;
        $this->view->position[]    = $this->lang->release->browse;
        $this->view->releases      = $this->release->getList($productID);
        $this->display();
    }

    /* 添加release。*/
    public function create($productID)
    {
        if(!empty($_POST))
        {
            $this->release->create($productID);
            if(dao::isError()) die(js::error(dao::getError()));
            die(js::locate($this->createLink('release', 'browse', "productID=$productID"), 'parent'));
        }

        $this->commonAction($productID);
        $this->view->header->title = $this->lang->release->create;
        $this->view->position[]    = $this->lang->release->create;
        $this->view->builds = $this->loadModel('build')->getProductBuildPairs($productID);
        $this->display();
    }

    /* 编辑release。*/
    public function edit($releaseID)
    {
        if(!empty($_POST))
        {
            $this->release->update($releaseID);
            if(dao::isError()) die(js::error(dao::getError()));
            die(js::locate($this->createLink('release', 'browse', "productID={$this->post->product}"), 'parent'));
        }

        $release = $this->release->getById((int)$releaseID);
        $this->commonAction($release->product);

        $this->view->header->title = $this->lang->release->edit;
        $this->view->position[]    = $this->lang->release->edit;
        $this->view->release       = $release;
        $this->view->builds        = $this->loadModel('build')->getProductBuildPairs($release->product);
        $this->display();
    }
                                                          
    /* 查看release。*/
    public function view($releaseID)
    {
        $release = $this->release->getById((int)$releaseID);
        $this->commonAction($release->product);

        /* 赋值。*/
        $this->view->header->title = $this->lang->release->view;
        $this->view->position[]    = $this->lang->release->view;
        $this->view->release       = $release;
        $this->display();
    }
 
    /* 删除release。*/
    public function delete($releaseID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->release->confirmDelete, $this->createLink('release', 'delete', "releaseID=$releaseID&confirm=yes")));
        }
        else
        {
            $release = $this->release->getById($releaseID);
            $this->release->delete($releaseID);
            die(js::locate($this->createLink('release', 'browse', "productID=$release->product"), 'parent'));
        }
    }
}
