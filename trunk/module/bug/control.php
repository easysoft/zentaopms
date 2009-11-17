<?php
/**
 * The control file of bug currentModule of ZenTaoMS.
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
 * @package     bug
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
class bug extends control
{
    private $products = array();

    /* 构造函数，加载story, release, tree等模块。*/
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('product');
        $this->loadModel('tree');
        $this->loadModel('user');
        $this->loadModel('action');
        $this->loadModel('story');
        $this->loadModel('task');
        $this->products = $this->product->getPairs();
        if(empty($this->products)) $this->locate($this->createLink('product', 'create'));
        $this->assign('products', $this->products);
    }

    /* bug首页。*/
    public function index()
    {
        $this->locate($this->createLink('bug', 'browse'));
    }

    /* 浏览一个产品下面的bug。*/
    public function browse($productID = 0, $type = 'byModule', $param = 0, $orderBy = 'id|desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $type = strtolower($type);
        $this->session->set('bugList', $this->app->getURI(true));

        $productID       = common::saveProductState($productID, key($this->products));
        $currentModuleID = ($type == 'bymodule') ? (int)$param : 0;
        if($currentModuleID == 0)
        {
            $currentModuleName = $this->lang->bug->allBugs;
        }
        else
        {
            $currentModule = $this->tree->getById($currentModuleID);
            $currentModuleName = sprintf($this->lang->bug->moduleBugs, $currentModule->name);
        }

        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $bugs = array();
        if($type == "bymodule")
        {
            $childModuleIds = $this->tree->getAllChildId($currentModuleID);
            $bugs = $this->bug->getModuleBugs($productID, $childModuleIds, $orderBy, $pager);
        }
        elseif($type == 'assigntome')
        {
            $bugs = $this->dao->findByAssignedTo($this->app->user->account)->from(TABLE_BUG)->orderBy($orderBy)->page($pager)->fetchAll();
        }
        elseif($type == 'openedbyme')
        {
            $bugs = $this->dao->findByOpenedBy($this->app->user->account)->from(TABLE_BUG)->orderBy($orderBy)->page($pager)->fetchAll();
        }
        elseif($type == 'resolvedbyme')
        {
            $bugs = $this->dao->findByResolvedBy($this->app->user->account)->from(TABLE_BUG)->orderBy($orderBy)->page($pager)->fetchAll();
        }
        elseif($type == 'assigntonull')
        {
            $bugs = $this->dao->findByAssignedTo('')->from(TABLE_BUG)->orderBy($orderBy)->page($pager)->fetchAll();
        }
        elseif($type == 'longlifebugs')
        {
            $bugs = $this->dao->findByLastEditedDate("<", date('Y-m-d', strtotime('-7 days')))->from(TABLE_BUG)->orderBy($orderBy)->page($pager)->fetchAll();
        }
        elseif($type == 'postponedbugs')
        {
            $bugs = $this->dao->findByResolution('postponed')->from(TABLE_BUG)->orderBy($orderBy)->page($pager)->fetchAll();
        }

        $users = $this->user->getPairs($this->app->company->id, 'noletter');
        
        $header['title'] = $this->products[$productID] . $this->lang->colon . $this->lang->bug->common;
        $position[]      = html::a($this->createLink('bug', 'browse', "productID=$productID"), $this->products[$productID]);
        $position[]      = $this->lang->bug->common;

        $this->assign('header',        $header);
        $this->assign('position',      $position);
        $this->assign('productID',     $productID);
        $this->assign('productName',   $this->products[$productID]);
        $this->assign('moduleTree',    $this->tree->getTreeMenu($productID, $viewType = 'bug', $rooteModuleID = 0, array('treeModel', 'createBugLink')));
        $this->assign('type',          $type);
        $this->assign('bugs',          $bugs);
        $this->assign('users',         $users);
        $this->assign('recTotal',      $pager->recTotal);
        $this->assign('recPerPage',    $pager->recPerPage);
        $this->assign('pager',         $pager->get());
        $this->assign('param',         $param);
        $this->assign('orderBy',       $orderBy);
        $this->assign('currentModuleID',   $currentModuleID);
        $this->assign('currentModuleName', $currentModuleName);

        $this->display();
    }

    /* 创建Bug。*/
    public function create($productID, $moduleID = 0)
    {
        if(empty($this->products)) $this->locate($this->createLink('product', 'create'));

        if(!empty($_POST))
        {
            $bugID = $this->bug->create();
            if(dao::isError()) die(js::error(dao::getError()));
            $this->action->create('bug', $bugID, 'Opened');
            die(js::locate($this->createLink('bug', 'browse', "productID={$this->post->product}&type=byModule&param={$this->post->module}"), 'parent'));
        }

        $productID       = common::saveProductState($productID, key($this->products));
        $currentModuleID = (int)$moduleID;

        $header['title'] = $this->products[$productID] . $this->lang->colon . $this->lang->bug->create;
        $position[]      = html::a($this->createLink('bug', 'browse', "productID=$productID"), $this->products[$productID]);
        $position[]      = $this->lang->bug->create;

        $this->assign('header',            $header);
        $this->assign('position',          $position);
        $this->assign('productID',         $productID);
        $this->assign('productName',       $this->products[$productID]);
        $this->assign('moduleOptionMenu',  $this->tree->getOptionMenu($productID, $viewType = 'bug', $rooteModuleID = 0));
        $this->assign('currentModuleID',   $currentModuleID);
        $this->assign('stories',           $this->story->getProductStoryPairs($productID));
        $this->assign('users',             $this->user->getPairs($this->app->company->id, 'noclosed'));
        $this->assign('projects',          $this->product->getProjectPairs($productID));
        $this->display();
    }

    /* 查看一个bug。*/
    public function view($bugID)
    {
        $bug         = $this->bug->getById($bugID);
        $productID   = $bug->product;
        $productName = $this->products[$productID];

        $header['title'] = $this->products[$productID] . $this->lang->colon . $this->lang->bug->view;
        $position[]      = html::a($this->createLink('bug', 'browse', "productID=$productID"), $productName);
        $position[]      = $this->lang->bug->view;

        $users   = $this->user->getPairs($this->app->company->id, 'noletter');
        $actions = $this->action->getList('bug', $bugID);
        $this->assign('header',      $header);
        $this->assign('position',    $position);
        $this->assign('productName', $productName);
        $this->assign('modulePath',  $this->tree->getParents($bug->module));
        $this->assign('bug',         $bug);
        $this->assign('users',       $users);
        $this->assign('actions',     $actions);

        $this->display();
    }

    /* 编辑一个Bug。*/
    public function edit($bugID)
    {
        /* 更新bug信息。*/
        if(!empty($_POST))
        {
            $changes  = $this->bug->update($bugID);
            if(dao::isError()) die(js::error(dao::getError()));
            $this->loadModel('file');
            $files = $this->file->saveUpload('files', 'bug', $bugID);
            if($this->post->comment != '' or !empty($changes) or !empty($files))
            {
                $action = !empty($changes) ? 'Edited' : 'Commented';
                $fileAction = '';
                if(!empty($files)) $fileAction = "Add Files " . join(',', $files) . "\n" ;
                $actionID = $this->action->create('bug', $bugID, $action, $fileAction . $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }
            die(js::locate($this->createLink('bug', 'view', "bugID=$bugID"), 'parent'));
        }

        /* 生成表单。*/
        $bug             = $this->bug->getById($bugID);
        $productID       = $bug->product;
        $currentModuleID = $bug->module;
        $header['title'] = $this->products[$productID] . $this->lang->colon . $this->lang->bug->edit;
        $position[]      = html::a($this->createLink('bug', 'browse', "productID=$productID"), $this->products[$productID]);
        $position[]      = $this->lang->bug->edit;

        $projects = $this->product->getProjectPairs($bug->product);
        $stories = $bug->project ? $this->story->getProjectStoryPairs($bug->project) : $this->story->getProductStoryPairs($bug->product);
        $tasks   = $this->task->getProjectTaskPairs($bug->project);

        $users = $this->user->getPairs($this->app->company->id);
        $this->assign('header',        $header);
        $this->assign('position',      $position);
        $this->assign('productID',     $productID);
        $this->assign('productName',   $this->products[$productID]);
        $this->assign('moduleOptionMenu',  $this->tree->getOptionMenu($productID, $viewType = 'bug', $rooteModuleID = 0));
        $this->assign('currentModuleID',   $currentModuleID);
        $this->assign('users',  $users);           

        $this->assign('projects', $projects);
        $this->assign('stories', $stories);
        $this->assign('tasks',   $tasks);
        
        $this->assign('header',   $header);
        $this->assign('position', $position);
        $this->assign('bug',      $bug);

        $this->display();
    }

    /* 解决bug。*/
    public function resolve($bugID)
    {
        /* 更新bug信息。*/
        if(!empty($_POST))
        {
            $this->bug->resolve($bugID);
            if(dao::isError()) die(js::error(dao::getError()));
            $actionID = $this->action->create('bug', $bugID, 'Resolved', $this->post->comment);
            die(js::locate($this->createLink('bug', 'view', "bugID=$bugID"), 'parent'));
        }

        /* 生成表单。*/
        $bug             = $this->bug->getById($bugID);
        $productID       = $bug->product;
        $header['title'] = $this->products[$productID] . $this->lang->colon . $this->lang->bug->resolve;
        $position[]      = html::a($this->createLink('bug', 'browse', "productID=$productID"), $this->products[$productID]);
        $position[]      = $this->lang->bug->resolve;

        $users = $this->user->getPairs($this->app->company->id);
        $this->assign('header',        $header);
        $this->assign('position',      $position);
        $this->assign('bug',           $bug);
        $this->display();
    }

    /* 激活bug。*/
    public function activate($bugID)
    {
        /* 更新bug信息。*/
        if(!empty($_POST))
        {
            $this->bug->activate($bugID);
            if(dao::isError()) die(js::error(dao::getError()));
            $this->loadModel('file');
            $files = $this->file->saveUpload('files', 'bug', $bugID);
            $this->action->create('bug', $bugID, 'Activated', $this->post->comment);
            die(js::locate($this->createLink('bug', 'view', "bugID=$bugID"), 'parent'));
        }

        /* 生成表单。*/
        $bug        = $this->bug->getById($bugID);
        $productID  = $bug->product;
        $users      = $this->user->getPairs($this->app->company->id);

        $header['title'] = $this->products[$productID] . $this->lang->colon . $this->lang->bug->activate;
        $position[]      = html::a($this->createLink('bug', 'browse', "productID=$productID"), $this->products[$productID]);
        $position[]      = $this->lang->bug->activate;

        $this->assign('header',    $header);
        $this->assign('position',  $position);
        $this->assign('bug',       $bug);
        $this->assign('users',     $users);
        $this->display();
    }

    /* 激活bug。*/
    public function close($bugID)
    {
        /* 更新bug信息。*/
        if(!empty($_POST))
        {
            $this->bug->close($bugID);
            if(dao::isError()) die(js::error(dao::getError()));
            $this->action->create('bug', $bugID, 'Closed', $this->post->comment);
            die(js::locate($this->createLink('bug', 'view', "bugID=$bugID"), 'parent'));
        }

        /* 生成表单。*/
        $bug        = $this->bug->getById($bugID);
        $productID  = $bug->product;

        $header['title'] = $this->products[$productID] . $this->lang->colon . $this->lang->bug->activate;
        $position[]      = html::a($this->createLink('bug', 'browse', "productID=$productID"), $this->products[$productID]);
        $position[]      = $this->lang->bug->activate;

        $this->assign('header',    $header);
        $this->assign('position',  $position);
        $this->assign('bug',       $bug);
        $this->display();
    }

    /* 获得用户的bug列表。*/
    public function ajaxGetUserBugs($account = '')
    {
        if($account == '') $account = $this->app->user->account;
        $bugs = $this->bug->getUserBugPairs($account);
        die(html::select('bug', $bugs, '', 'class=select-1'));
    }
}
