<?php
/**
 * The control file of testtask module of ZenTaoMS.
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
 * @package     testtask
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
class testtask extends control
{
    private $products = array();

    /* 构造函数，加载story, release, tree等模块。*/
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('product');
        $this->view->products = $this->products = $this->product->getPairs();
    }

    /* task首页。*/
    public function index()
    {
        $this->locate($this->createLink('testtask', 'browse'));
    }

    /* 浏览一个产品下面的task。*/
    public function browse($productID = 0, $orderBy = 'id|desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* 设置产品和菜单。*/
        $productID = common::saveProductState($productID, key($this->products));
        $this->testtask->setMenu($this->products, $productID);

        /* 加载分页类。*/
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* 赋值。*/
        $this->view->header->title = $this->products[$productID] . $this->lang->colon . $this->lang->testtask->common;
        $this->view->position[]    = html::a($this->createLink('testtask', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[]    = $this->lang->testtask->common;
        $this->view->productID     = $productID;
        $this->view->productName   = $this->products[$productID];
        $this->view->pager         = $pager;
        $this->view->orderBy       = $orderBy;
        $this->view->tasks         = $this->testtask->getProductTasks($productID);

        $this->display();
    }

    /* 创建task。*/
    public function create($productID)
    {
        if(!empty($_POST))
        {
            $taskID = $this->testtask->create($productID);
            if(dao::isError()) die(js::error(dao::getError()));
            die(js::locate($this->createLink('testtask', 'browse', "productID=$productID"), 'parent'));
        }

        /* 设置菜单。*/
        $productID  = common::saveProductState($productID, key($this->products));
        $this->testtask->setMenu($this->products, $productID);

        /* 导航信息。*/
        $this->view->header['title'] = $this->products[$productID] . $this->lang->colon . $this->lang->testtask->create;
        $this->view->position[]      = html::a($this->createLink('testtask', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[]      = $this->lang->testtask->create;

        $this->view->projects  = $this->product->getProjectPairs($productID);
        $this->view->builds    = $this->loadModel('build')->getProductBuildPairs($productID);

        $this->display();
    }

    /* 查看一个task。*/
    public function view($taskID)
    {
        /* 获取task和产品信息，并设置菜单。*/
        $task      = $this->testtask->getById($taskID);
        $productID = $task->product;
        $this->testtask->setMenu($this->products, $productID);

        /* 导航信息。*/
        $this->view->header['title'] = $this->products[$productID] . $this->lang->colon . $this->lang->testtask->view;
        $this->view->position[]      = html::a($this->createLink('testtask', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[]      = $this->lang->testtask->view;

        /* 赋值。*/
        $this->view->task   = $task;
        $this->view->users  = $this->loadModel('user')->getPairs('noletter');
        $this->view->cases  = $this->testtask->getCases($taskID);

        $this->display();
    }

    /* 编辑一个Bug。*/
    public function edit($taskID)
    {
        /* 获得task信息。*/
        $task      = $this->testtask->getById($taskID);
        $productID = common::saveProductState($task->product, key($this->products));

        /* 更新task信息。*/
        if(!empty($_POST))
        {
            $this->testtask->update($taskID);
            if(dao::isError()) die(js::error(dao::getError()));
            die(js::locate(inlink('browse', "productID=$productID"), 'parent'));
        }

        /* 设置菜单。*/
        $this->testtask->setMenu($this->products, $productID);

        /* 导航信息。*/
        $this->view->header['title'] = $this->products[$productID] . $this->lang->colon . $this->lang->testtask->edit;
        $this->view->position[]      = html::a($this->createLink('testtask', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[]      = $this->lang->testtask->edit;

        $this->view->task      = $task;
        $this->view->projects  = $this->product->getProjectPairs($productID);
        $this->view->builds    = $this->loadModel('build')->getProductBuildPairs($productID);

        $this->display();
    }

    /* 删除一个任务。*/
    public function delete($taskID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->testtask->confirmDelete, inlink('delete', "taskID=$taskID&confirm=yes")));
        }
        else
        {
            $task = $this->testtask->getByID($taskID);
            $this->testtask->delete($taskID);
            die(js::locate(inlink('browse', "product=$task->product"), 'parent'));
        }
    }

    /* 关联用例。*/
    public function linkCase($taskID)
    {
        if(!empty($_POST))
        {
            $this->testtask->linkCase($taskID);
            $this->locate(inlink('view', "taskID=$taskID"));
        }

        /* 构造搜索表单。*/
        $this->config->testcase->search['actionURL'] = inlink('linkcase', "taskID=$taskID");
        $this->view->searchForm = $this->fetch('search', 'buildForm', $this->config->testcase->search);

        /* 获得task信息。*/
        $task      = $this->testtask->getById($taskID);
        $productID = common::saveProductState($task->product, key($this->products));

        /* 设置菜单。*/
        $this->testtask->setMenu($this->products, $productID);

        /* 导航信息。*/
        $this->view->header['title'] = $this->products[$productID] . $this->lang->colon . $this->lang->testtask->linkCase;
        $this->view->position[]      = html::a($this->createLink('testtask', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[]      = $this->lang->testtask->linkCase;

        /* 获得用例列表。*/
        if($this->session->testcaseQuery == false) $this->session->set('testcaseQuery', ' 1 = 1');
        $this->view->cases = $this->dao->select('*')->from(TABLE_CASE)->where($this->session->testcaseQuery)->andWhere('product')->eq($productID)->orderBy('id desc')->fetchAll();

        $this->view->users = $this->loadModel('user')->getPairs('noletter');

        $this->display();
    }
}
