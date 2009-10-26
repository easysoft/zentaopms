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
    public function browse($productID = 0, $type = 'byModule', $param = 0)
    {
        $productID       = common::saveProductState($productID, key($this->products));
        $currentModuleID = ($type == 'byModule') ? (int)$param : 0;
        if($currentModuleID == 0)
        {
            $currentModuleName = $this->lang->bug->allBugs;
        }
        else
        {
            $currentModule = $this->tree->getById($currentModuleID);
            $currentModuleName = sprintf($this->lang->bug->moduleBugs, $currentModule->name);
        }

        if($type == "byModule")
        {
            $childModuleIds = $this->tree->getAllChildId($currentModuleID);
            $bugs = $this->bug->getModuleBugs($productID, $childModuleIds);
        }

        $users = array('' => '', 'Closed' => 'Closed') + $this->user->getRealNames($this->bug->extractAccountsFromList($bugs));
        
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
        $this->assign('currentModuleID',   $currentModuleID);
        $this->assign('currentModuleName', $currentModuleName);

        $this->display();
    }

    /* 创建Bug。*/
    public function create($productID, $moduleID = 0)
    {
        if(!empty($_POST))
        {
            $_POST['severity'] = str_replace('item', '', $_POST['severity']);
            $bugID = $this->bug->create();
            $this->action->create('bug', $bugID, 'Opened');
            die(js::locate($this->createLink('bug', 'browse', "productID=$_POST[productID]&type=byModule&param=$_POST[moduleID]"), 'parent'));
        }

        if(empty($this->products)) $this->locate($this->createLink('product', 'create'));

        $productID = (int)$productID;
        if($productID == 0) $productID = key($this->products);
        $currentModuleID = (int)$moduleID;

        $header['title'] = $this->products[$productID] . $this->lang->colon . $this->lang->bug->create;
        $position[]      = html::a($this->createLink('bug', 'browse', "productID=$productID"), $this->products[$productID]);
        $position[]      = $this->lang->bug->create;

        $users = array('' => '') + $this->user->getPairs($this->app->company->id);
        $this->assign('header',        $header);
        $this->assign('position',      $position);
        $this->assign('productID',     $productID);
        $this->assign('productName',   $this->products[$productID]);
        $this->assign('moduleOptionMenu',  $this->tree->getOptionMenu($productID, $viewType = 'bug', $rooteModuleID = 0));
        $this->assign('currentModuleID',   $currentModuleID);
        $this->assign('users',  $users);           

        $this->display();
    }

    /* 查看一个bug。*/
    public function view($bugID)
    {
        $bug = $this->bug->getById($bugID);
        $productID   = $bug->product;
        $productName = $this->products[$productID];
        $header['title'] = $this->products[$productID] . $this->lang->colon . $this->lang->bug->view;
        $position[]      = html::a($this->createLink('bug', 'browse', "productID=$productID"), $productName);
        $position[]      = $this->lang->bug->view;

        $users   = array('' => '') + $this->user->getRealNames($this->bug->extractAccountsFromSingle($bug));
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
            $actionID = $this->action->create('bug', $bugID, 'Edited', $_POST['comment']);
            $this->action->logHistory($actionID, $changes);
            die(js::locate($this->createLink('bug', 'view', "bugID=$bugID"), 'parent'));
        }

        /* 生成表单。*/
        $bug             = $this->bug->getById($bugID);
        $productID       = $bug->product;
        $currentModuleID = $bug->module;
        $header['title'] = $this->products[$productID] . $this->lang->colon . $this->lang->bug->edit;
        $position[]      = html::a($this->createLink('bug', 'browse', "productID=$productID"), $this->products[$productID]);
        $position[]      = $this->lang->bug->edit;

        $users = array('' => '') + $this->user->getPairs($this->app->company->id);
        $this->assign('header',        $header);
        $this->assign('position',      $position);
        $this->assign('productID',     $productID);
        $this->assign('productName',   $this->products[$productID]);
        $this->assign('moduleOptionMenu',  $this->tree->getOptionMenu($productID, $viewType = 'bug', $rooteModuleID = 0));
        $this->assign('currentModuleID',   $currentModuleID);
        $this->assign('users',  $users);           

        $this->assign('header',   $header);
        $this->assign('position', $position);
        $this->assign('bug',      $bug);

        $this->display();
    }

    public function delete($id)
    {
        $header['title'] = $this->lang->page->delete;
        $this->assign('header', $header);
        $this->display();
    }

    public function ajaxGetUserBugs($account = '')
    {
        if($account == '') $account = $this->app->user->account;
        $bugs = $this->bug->getUserBugPairs($account);
        die(html::select('bug', $bugs, '', 'class=select-1'));
    }
}
