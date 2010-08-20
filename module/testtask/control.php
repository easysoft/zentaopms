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
 * @copyright   Copyright 2009-2010 青岛易软天创网络科技有限公司(www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testtask
 * @version     $Id$
 * @link        http://www.zentaoms.com
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
    public function browse($productID = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* 登记session。*/
        $this->session->set('testtaskList', $this->app->getURI(true));

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
            $this->loadModel('action')->create('testtask', $taskID, 'opened');
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
        $task = $this->testtask->getById($taskID);
        if(!$task) die(js::error($this->lang->notFound) . js::locate('back'));
        $productID = $task->product;
        $this->testtask->setMenu($this->products, $productID);

        /* 导航信息。*/
        $this->view->header['title'] = $this->products[$productID] . $this->lang->colon . $this->lang->testtask->view;
        $this->view->position[]      = html::a($this->createLink('testtask', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[]      = $this->lang->testtask->view;

        /* 赋值。*/
        $this->view->productID = $productID;
        $this->view->task      = $task;
        $this->view->users     = $this->loadModel('user')->getPairs('noclosed');
        $this->view->actions   = $this->loadModel('action')->getList('testtask', $taskID);

        $this->display();
    }

    /* 查看任务的用例列表。*/
    public function cases($taskID, $browseType = 'byModule', $param = 0)
    {
        $this->app->loadLang('testcase');
        $this->session->set('caseList', $this->app->getURI(true));

        /* 设置浏览模式，产品ID和模块ID。 */
        $browseType = strtolower($browseType);
        $moduleID  = ($browseType == 'bymodule') ? (int)$param : 0;

        /* 获取task和产品信息，并设置菜单。*/
        $task = $this->testtask->getById($taskID);
        if(!$task) die(js::error($this->lang->notFound) . js::locate('back'));
        $productID = $task->product;
        $this->testtask->setMenu($this->products, $productID);

        /* 如果是按照模块查找，或者列出所有。*/
        if($browseType == 'bymodule' or $browseType == 'all')
        {
            $modules = '';
            if($moduleID) $modules = $this->loadModel('tree')->getAllChildID($moduleID);
            $this->view->runs      = $this->testtask->getRuns($taskID, $modules);
        }
        elseif($browseType == 'assignedtome')
        {
            $this->view->runs = $this->testtask->getUserRuns($taskID, $this->session->user->account);
        }

        /* 导航信息。*/
        $this->view->header['title'] = $this->products[$productID] . $this->lang->colon . $this->lang->testtask->cases;
        $this->view->position[]      = html::a($this->createLink('testtask', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[]      = $this->lang->testtask->cases;

        /* 赋值。*/
        $this->view->productID   = $productID;
        $this->view->productName = $this->products[$productID];
        $this->view->task        = $task;
        $this->view->users       = $this->loadModel('user')->getPairs('noclosed');
        $this->view->moduleTree  = $this->loadModel('tree')->getTreeMenu($productID, $viewType = 'case', $startModuleID = 0, array('treeModel', 'createTestTaskLink'), $extra = $taskID);
        $this->view->browseType  = $browseType;
        $this->view->taskID      = $taskID;
        $this->view->moduleID    = $moduleID;

        $this->display();
    }

    /* 编辑一个Bug。*/
    public function edit($taskID)
    {
        /* 更新task信息。*/
        if(!empty($_POST))
        {
            $changes = $this->testtask->update($taskID);
            if(dao::isError()) die(js::error(dao::getError()));
            if($changes)
            {
                $actionID = $this->loadModel('action')->create('testtask', $taskID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }
            die(js::locate(inlink('view', "taskID=$taskID"), 'parent'));
        }

        /* 获得task信息。*/
        $task      = $this->testtask->getById($taskID);
        $productID = common::saveProductState($task->product, key($this->products));

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
            $this->testtask->delete(TABLE_TESTTASK, $taskID);
            die(js::locate(inlink('browse', "product=$task->product"), 'parent'));
        }
    }

    /* 关联用例。*/
    public function linkCase($taskID)
    {
        if(!empty($_POST))
        {
            $this->testtask->linkCase($taskID);
            $this->locate(inlink('cases', "taskID=$taskID"));
        }

        $this->session->set('caseList', $this->app->getURI(true));

        /* 获得task信息。*/
        $task      = $this->testtask->getById($taskID);
        $productID = common::saveProductState($task->product, key($this->products));

        /* 构造搜索表单。*/
        $this->loadModel('testcase');
        $this->config->testcase->search['params']['product']['values']= array($productID => $this->products[$productID], 'all' => $this->lang->testcase->allProduct);
        $this->config->testcase->search['params']['module']['values'] = $this->loadModel('tree')->getOptionMenu($productID, $viewType = 'case');
        $this->config->testcase->search['actionURL'] = inlink('linkcase', "taskID=$taskID");
        $this->view->searchForm = $this->fetch('search', 'buildForm', $this->config->testcase->search);

        /* 设置菜单。*/
        $this->testtask->setMenu($this->products, $productID);

        /* 导航信息。*/
        $this->view->header['title'] = $this->products[$productID] . $this->lang->colon . $this->lang->testtask->linkCase;
        $this->view->position[]      = html::a($this->createLink('testtask', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[]      = $this->lang->testtask->linkCase;

        /* 获得用例列表。*/
        if($this->session->testcaseQuery == false) $this->session->set('testcaseQuery', ' 1 = 1');
        $query = str_replace("`product` = 'all'", '1', $this->session->testcaseQuery); // 如果指定了搜索所有的产品，去掉这个查询条件。
        $linkedCases = $this->dao->select('`case`')->from(TABLE_TESTRUN)->where('task')->eq($taskID)->fetchPairs('case');
        $this->view->cases = $this->dao->select('*')->from(TABLE_CASE)->where($query)
            ->andWhere('product')->eq($productID)
            ->andWhere('id')->notIN($linkedCases)
            ->andWhere('deleted')->eq(0)
            ->orderBy('id desc')->fetchAll();
        $this->view->users = $this->loadModel('user')->getPairs('noletter');

        $this->display();
    }

    /* 移除用例。*/
    public function unlinkCase($rowID)
    {
        $this->dao->delete()->from(TABLE_TESTRUN)->where('id')->eq((int)$rowID)->exec();
        die(js::reload('parent'));
    }

    /* 执行用例。*/
    public function runCase($runID)
    {
        if(!empty($_POST))
        {
            $this->testtask->createResult($runID);
            if(dao::isError()) die(js::error(dao::getError()));
            echo js::reload('parent');
            die(js::closeWindow());
        }

        $this->view->run = $this->testtask->getRunById($runID);
        die($this->display());
    }

    /* 查看结果列表。*/
    public function results($runID)
    {
        $this->view->run     = $this->testtask->getRunById($runID);
        $this->view->results = $this->testtask->getRunResults($runID);
        die($this->display());
    }

    /* 批量指派。*/
    public function batchAssign($taskID)
    {
        $this->dao->update(TABLE_TESTRUN)
            ->set('assignedTo')->eq($this->post->assignedTo)
            ->where('task')->eq((int)$taskID)
            ->andWhere('`case`')->in($this->post->cases)
            ->exec();
        die(js::reload('parent'));
    }
}
