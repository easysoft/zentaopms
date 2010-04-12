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
 * @copyright   Copyright 2009-2010 Chunsheng Wang
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
        $this->view->product = $this->product->getById($productID);
        $this->view->position[] = html::a($this->createLink('product', 'browse', "productID={$this->view->product->id}"), $this->view->product->name);
        $this->product->setMenu($this->product->getPairs(), $productID);
    }

    /* 浏览发布列表。*/
    public function browse($productID)
    {
        $this->commonAction($productID);
        $this->session->set('releaseList', $this->app->getURI(true));
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
            $releaseID = $this->release->create($productID);
            if(dao::isError()) die(js::error(dao::getError()));
            $this->loadModel('action')->create('release', $releaseID, 'opened');
            die(js::locate(inlink('view', "releaseID=$releaseID"), 'parent'));
        }

        $this->commonAction($productID);
        $this->view->header->title = $this->lang->release->create;
        $this->view->position[]    = $this->lang->release->create;
        $this->view->builds = $this->loadModel('build')->getProductBuildPairs($productID);
        unset($this->view->builds['trunk']);
        $this->display();
    }

    /* 编辑release。*/
    public function edit($releaseID)
    {
        if(!empty($_POST))
        {
            $changes = $this->release->update($releaseID);
            if(dao::isError()) die(js::error(dao::getError()));
            $actionID = $this->loadModel('action')->create('release', $releaseID, 'edited');
            $this->action->logHistory($actionID, $changes);
            die(js::locate(inlink('view', "releaseID=$releaseID"), 'parent'));
        }

        $release = $this->release->getById((int)$releaseID);
        $this->commonAction($release->product);

        $this->view->header->title = $this->lang->release->edit;
        $this->view->position[]    = $this->lang->release->edit;
        $this->view->release       = $release;
        $this->view->builds        = $this->loadModel('build')->getProductBuildPairs($release->product);
        unset($this->view->builds['trunk']);
        $this->display();
    }
                                                          
    /* 查看release。*/
    public function view($releaseID)
    {
        $release = $this->release->getById((int)$releaseID);
        if(!$release) die(js::error($this->lang->notFound) . js::locate('back'));

        $this->commonAction($release->product);

        /* 赋值。*/
        $this->view->header->title = $this->lang->release->view;
        $this->view->position[]    = $this->lang->release->view;
        $this->view->release       = $release;
        $this->view->actions       = $this->loadModel('action')->getList('release', $releaseID);
        $this->view->users         = $this->loadModel('user')->getPairs('noletter');
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
            $this->release->delete(TABLE_RELEASE, $releaseID);
            die(js::locate($this->session->releaseList, 'parent'));
        }
    }
}
