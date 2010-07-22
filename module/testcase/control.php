<?php
/**
 * The control file of case currentModule of ZenTaoMS.
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
 * @package     case
 * @version     $Id$
 * @link        http://www.zentaoms.com
 */
class testcase extends control
{
    private $products = array();

    /* 构造函数，加载story, release, tree等模块。*/
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('product');
        $this->loadModel('tree');
        $this->loadModel('user');
        $this->view->products = $this->products = $this->product->getPairs();
    }

    /* case首页。*/
    public function index()
    {
        $this->locate($this->createLink('testcase', 'browse'));
    }

    /* 浏览一个产品下面的case。*/
    public function browse($productID = 0, $browseType = 'byModule', $param = 0, $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* 设置浏览模式，产品ID和模块ID。 */
        $browseType = strtolower($browseType);
        $productID = common::saveProductState($productID, key($this->products));
        $moduleID  = ($browseType == 'bymodule') ? (int)$param : 0;
        $queryID   = ($browseType == 'bysearch') ? (int)$param : 0;

        /* 设置菜单，登记session。*/
        $this->testcase->setMenu($this->products, $productID);
        $this->session->set('caseList', $this->app->getURI(true));

        /* 加载分页类。*/
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        /* 如果是按照模块查找，或者列出所有。*/
        if($browseType == 'bymodule' or $browseType == 'all')
        {
            $childModuleIds    = $this->tree->getAllChildId($moduleID);
            $this->view->cases = $this->testcase->getModuleCases($productID, $childModuleIds, $orderBy, $pager);
        }
        elseif($browseType == 'needconfirm')
        {
            $this->view->cases = $this->dao->select('t1.*, t2.title AS storyTitle')->from(TABLE_CASE)->alias('t1')->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
                ->where("t2.status = 'active'")
                ->andWhere('t1.deleted')->eq(0)
                ->andWhere('t2.version > t1.storyVersion')
                ->orderBy($orderBy)
                ->fetchAll();
        }
        elseif($browseType == 'bysearch')
        {
            if($queryID)
            {
                $query = $this->loadModel('search')->getQuery($queryID);
                if($query)
                {
                    $this->session->set('testcaseQuery', $query->sql);
                    $this->session->set('testcaseForm', $query->form);
                }
                else
                {
                    $this->session->set('testcaseQuery', ' 1 = 1');
                }
            }
            else
            {
                if($this->session->testcaseQuery == false) $this->session->set('testcaseQuery', ' 1 = 1');
            }

            $this->view->cases = $this->dao->select('*')->from(TABLE_CASE)->where($this->session->testcaseQuery)
                ->andWhere('product')->eq($productID)
                ->andWhere('deleted')->eq(0)
                ->orderBy($orderBy)->page($pager)->fetchAll();
        }

        /* 构造搜索表单。*/
        $this->config->testcase->search['actionURL'] = $this->createLink('testcase', 'browse', "productID=$productID&browseType=bySearch&queryID=myQueryID");
        $this->config->testcase->search['queryID']   = $queryID;
        $this->view->searchForm = $this->fetch('search', 'buildForm', $this->config->testcase->search);

        /* 赋值。*/
        $this->view->header->title = $this->products[$productID] . $this->lang->colon . $this->lang->testcase->common;
        $this->view->position[]    = html::a($this->createLink('testcase', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[]    = $this->lang->testcase->common;
        $this->view->productID     = $productID;
        $this->view->productName   = $this->products[$productID];
        $this->view->moduleTree    = $this->tree->getTreeMenu($productID, $viewType = 'case', $startModuleID = 0, array('treeModel', 'createCaseLink'));
        $this->view->moduleID      = $moduleID;
        $this->view->pager         = $pager;
        $this->view->users         = $this->user->getPairs('noletter');
        $this->view->orderBy       = $orderBy;
        $this->view->browseType    = $browseType;
        $this->view->param         = $param;

        $this->display();
    }

    /* 创建case。*/
    public function create($productID, $moduleID = 0)
    {
        $this->loadModel('story');
        if(!empty($_POST))
        {
            $caseID = $this->testcase->create();
            if(dao::isError()) die(js::error(dao::getError()));
            $this->loadModel('action');
            $this->action->create('case', $caseID, 'Opened');
            die(js::locate($this->createLink('testcase', 'browse', "productID=$_POST[product]&browseType=byModule&param=$_POST[module]"), 'parent'));
        }
        if(empty($this->products)) $this->locate($this->createLink('product', 'create'));

        $productID       = common::saveProductState($productID, key($this->products));
        $currentModuleID = (int)$moduleID;

        /* 设置菜单。*/
        $this->testcase->setMenu($this->products, $productID);

        $header['title'] = $this->products[$productID] . $this->lang->colon . $this->lang->testcase->create;
        $position[]      = html::a($this->createLink('testcase', 'browse', "productID=$productID"), $this->products[$productID]);
        $position[]      = $this->lang->testcase->create;

        $users = $this->user->getPairs();
        $this->view->header           = $header;
        $this->view->position         = $position;
        $this->view->productID        = $productID;
        $this->view->users            = $users;           
        $this->view->productName      = $this->products[$productID];
        $this->view->moduleOptionMenu = $this->tree->getOptionMenu($productID, $viewType = 'case', $startModuleID = 0);
        $this->view->currentModuleID  = $currentModuleID;
        $this->view->stories          = $this->story->getProductStoryPairs($productID);

        $this->display();
    }

    /* 查看一个case。*/
    public function view($caseID, $version = 0)
    {
        /* 获取case和产品信息，并设置菜单。*/
        $case = $this->testcase->getById($caseID, $version);
        if(!$case) die(js::error($this->lang->notFound) . js::locate('back'));

        $productID = $case->product;
        $this->testcase->setMenu($this->products, $productID);

        /* 导航信息。*/
        $this->view->header['title'] = $this->products[$productID] . $this->lang->colon . $this->lang->testcase->view;
        $this->view->position[]      = html::a($this->createLink('testcase', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[]      = $this->lang->testcase->view;

        /* 赋值。*/
        $this->view->case        = $case;
        $this->view->productName = $this->products[$productID];
        $this->view->modulePath  = $this->tree->getParents($case->module);
        $this->view->users       = $this->user->getPairs('noletter');
        $this->view->actions     = $this->loadModel('action')->getList('case', $caseID);

        $this->display();
    }

    /* 编辑一个case。*/
    public function edit($caseID)
    {
        $this->loadModel('story');

        /* 更新case信息。*/
        if(!empty($_POST))
        {
            $changes = $this->testcase->update($caseID);
            if(dao::isError()) die(js::error(dao::getError()));
            $files = $this->loadModel('file')->saveUpload('testcase', $caseID);
            if($this->post->comment != '' or !empty($changes) or !empty($files))
            {
                $this->loadModel('action');
                $action = !empty($changes) ? 'Edited' : 'Commented';
                $fileAction = '';
                if(!empty($files)) $fileAction = $this->lang->addFiles . join(',', $files) . "\n";
                $actionID = $this->action->create('case', $caseID, $action, $fileAction . $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }
            die(js::locate($this->createLink('testcase', 'view', "caseID=$caseID"), 'parent'));
        }

        /* 生成表单。*/
        $case = $this->testcase->getById($caseID);
        if(empty($case->steps))
        {
            $step->desc   = '';
            $step->expect = '';
            $case->steps[] = $step;
        }
        $productID       = $case->product;
        $currentModuleID = $case->module;
        $header['title'] = $this->products[$productID] . $this->lang->colon . $this->lang->testcase->edit;
        $position[]      = html::a($this->createLink('testcase', 'browse', "productID=$productID"), $this->products[$productID]);
        $position[]      = $this->lang->testcase->edit;

        /* 设置菜单。*/
        $this->testcase->setMenu($this->products, $productID);

        $users = $this->user->getPairs();
        $this->view->header           = $header;
        $this->view->position         = $position;
        $this->view->productID        = $productID;
        $this->view->productName      = $this->products[$productID];
        $this->view->moduleOptionMenu = $this->tree->getOptionMenu($productID, $viewType = 'case', $startModuleID = 0);
        $this->view->currentModuleID  = $currentModuleID;
        $this->view->users            = $users;
        $this->view->stories          = $this->story->getProductStoryPairs($productID);

        $this->view->header   = $header;
        $this->view->position = $position;
        $this->view->case     = $case;
        $this->view->actions  = $this->loadModel('action')->getList('case', $caseID);

        $this->display();
    }

    /* 删除用例。*/
    public function delete($caseID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->testcase->confirmDelete, inlink('delete', "caseID=$caseID&confirm=yes")));
        }
        else
        {
            $this->testcase->delete(TABLE_CASE, $caseID);
            die(js::locate($this->session->caseList, 'parent'));
        }
    }

    /* 确认需求变动。*/
    public function confirmStoryChange($caseID)
    {
        $case = $this->testcase->getById($caseID);
        $this->dao->update(TABLE_CASE)->set('storyVersion')->eq($case->latestStoryVersion)->where('id')->eq($caseID)->exec();
        $this->loadModel('action')->create('case', $caseID, 'confirmed', '', $case->latestStoryVersion);
        die(js::reload('parent'));
    }
}
