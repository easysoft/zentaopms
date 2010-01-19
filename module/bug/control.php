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
    public function browse($productID = 0, $browseType = 'byModule', $param = 0, $orderBy = 'id|desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* 设置产品id和模块id。*/
        $browseType = strtolower($browseType);
        $productID  = common::saveProductState($productID, key($this->products));
        $moduleID   = ($browseType == 'bymodule') ? (int)$param : 0;

        /* 设置搜索表单。*/
        $this->config->bug->search['actionURL'] = $this->createLink('bug', 'browse', "productID=$productID&browseType=bySearch");
        $this->view->searchForm = $this->fetch('search', 'buildForm', $this->config->bug->search);

        /* 设置菜单，登记session。*/
        $this->bug->setMenu($this->products, $productID);
        $this->session->set('bugList', $this->app->getURI(true));

        /* 加载分页类。*/
        $this->app->loadClass('pager', $static = true);
        $pager = pager::init($recTotal, $recPerPage, $pageID);

        $bugs = array();
        if($browseType == 'all')
        {
            $bugs = $this->dao->select('*')->from(TABLE_BUG)->where('product')->eq($productID)->orderBy($orderBy)->page($pager)->fetchAll();
        }
        elseif($browseType == "bymodule")
        {
            $childModuleIds = $this->tree->getAllChildId($moduleID);
            $bugs = $this->bug->getModuleBugs($productID, $childModuleIds, $orderBy, $pager);
        }
        elseif($browseType == 'assigntome')
        {
            $bugs = $this->dao->findByAssignedTo($this->app->user->account)->from(TABLE_BUG)->andWhere('product')->eq($productID)->orderBy($orderBy)->page($pager)->fetchAll();
        }
        elseif($browseType == 'openedbyme')
        {
            $bugs = $this->dao->findByOpenedBy($this->app->user->account)->from(TABLE_BUG)->andWhere('product')->eq($productID)->orderBy($orderBy)->page($pager)->fetchAll();
        }
        elseif($browseType == 'resolvedbyme')
        {
            $bugs = $this->dao->findByResolvedBy($this->app->user->account)->from(TABLE_BUG)->andWhere('product')->eq($productID)->orderBy($orderBy)->page($pager)->fetchAll();
        }
        elseif($browseType == 'assigntonull')
        {
            $bugs = $this->dao->findByAssignedTo('')->from(TABLE_BUG)->andWhere('product')->eq($productID)->orderBy($orderBy)->page($pager)->fetchAll();
        }
        elseif($browseType == 'longlifebugs')
        {
            $bugs = $this->dao->findByLastEditedDate("<", date('Y-m-d', strtotime('-7 days')))->from(TABLE_BUG)->andWhere('product')->eq($productID)->andWhere('status')->ne('closed')->orderBy($orderBy)->page($pager)->fetchAll();
        }
        elseif($browseType == 'postponedbugs')
        {
            $bugs = $this->dao->findByResolution('postponed')->from(TABLE_BUG)->andWhere('product')->eq($productID)->orderBy($orderBy)->page($pager)->fetchAll();
        }
        elseif($browseType == 'bysearch')
        {
            if($this->session->bugQuery == false) $this->session->set('bugQuery', ' 1 = 1');
            $bugs = $this->dao->select('*')->from(TABLE_BUG)->where($this->session->bugQuery)->andWhere('product')->eq($productID)->orderBy($orderBy)->page($pager)->fetchAll();
        }

        $users = $this->user->getPairs('noletter');
        
        $header['title'] = $this->products[$productID] . $this->lang->colon . $this->lang->bug->common;
        $position[]      = html::a($this->createLink('bug', 'browse', "productID=$productID"), $this->products[$productID]);
        $position[]      = $this->lang->bug->common;

        $this->assign('header',        $header);
        $this->assign('position',      $position);
        $this->assign('productID',     $productID);
        $this->assign('productName',   $this->products[$productID]);
        $this->assign('moduleTree',    $this->tree->getTreeMenu($productID, $viewType = 'bug', $rooteModuleID = 0, array('treeModel', 'createBugLink')));
        $this->assign('browseType',    $browseType);
        $this->assign('bugs',          $bugs);
        $this->assign('users',         $users);
        $this->assign('pager',         $pager);
        $this->assign('param',         $param);
        $this->assign('orderBy',       $orderBy);
        $this->assign('moduleID',      $moduleID);

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
            $actionID = $this->action->create('bug', $bugID, 'Opened');
            $this->sendmail($bugID, $actionID);
            die(js::locate($this->createLink('bug', 'browse', "productID={$this->post->product}&type=byModule&param={$this->post->module}"), 'parent'));
        }

        /* 设置当前的产品和模块。*/
        $productID       = common::saveProductState($productID, key($this->products));
        $currentModuleID = (int)$moduleID;

        /* 设置菜单。*/
        $this->bug->setMenu($this->products, $productID);

        /* 位置信息。*/
        $this->view->header->title = $this->products[$productID] . $this->lang->colon . $this->lang->bug->create;
        $this->view->position[]    = html::a($this->createLink('bug', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[]    = $this->lang->bug->create;

        $this->view->productID        = $productID;
        $this->view->productName      = $this->products[$productID];
        $this->view->moduleOptionMenu = $this->tree->getOptionMenu($productID, $viewType = 'bug', $rooteModuleID = 0);
        $this->view->currentModuleID  = $currentModuleID;
        $this->view->stories          = $this->story->getProductStoryPairs($productID);
        $this->view->users            = $this->user->getPairs('noclosed');
        $this->view->projects         = $this->product->getProjectPairs($productID);
        $this->view->builds           = $this->loadModel('build')->getProductBuildPairs($productID);
        $this->display();
    }

    /* 查看一个bug。*/
    public function view($bugID)
    {
        /* 查找bug信息及相关产品信息。*/
        $bug         = $this->bug->getById($bugID);
        $productID   = $bug->product;
        $productName = $this->products[$productID];
        
        /* 设置菜单。*/
        $this->bug->setMenu($this->products, $productID);

        /* 位置信息。*/
        $this->view->header->title = $this->products[$productID] . $this->lang->colon . $this->lang->bug->view;
        $this->view->position[]    = html::a($this->createLink('bug', 'browse', "productID=$productID"), $productName);
        $this->view->position[]    = $this->lang->bug->view;

        /* 赋值。*/
        $this->view->productName = $productName;
        $this->view->modulePath  = $this->tree->getParents($bug->module);
        $this->view->bug         = $bug;
        $this->view->users       = $this->user->getPairs('noletter');
        $this->view->actions     = $this->action->getList('bug', $bugID);
        $this->view->builds      = $this->loadModel('build')->getProductBuildPairs($productID);

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
            $files = $this->loadModel('file')->saveUpload('bug', $bugID);
            if($this->post->comment != '' or !empty($changes) or !empty($files))
            {
                $action = !empty($changes) ? 'Edited' : 'Commented';
                $fileAction = '';
                if(!empty($files)) $fileAction = "Add Files " . join(',', $files) . "\n" ;
                $actionID = $this->action->create('bug', $bugID, $action, $fileAction . $this->post->comment);
                $this->action->logHistory($actionID, $changes);
                $this->sendmail($bugID, $actionID);
            }
            die(js::locate($this->createLink('bug', 'view', "bugID=$bugID"), 'parent'));
        }

        /* 查找当前bug信息和产品模块信息。*/
        $bug             = $this->bug->getById($bugID);
        $productID       = $bug->product;
        $currentModuleID = $bug->module;

        /* 设置菜单。*/
        $this->bug->setMenu($this->products, $productID);

        /* 位置。*/
        $this->view->header->title = $this->products[$productID] . $this->lang->colon . $this->lang->bug->edit;
        $this->view->position[]    = html::a($this->createLink('bug', 'browse', "productID=$productID"), $this->products[$productID]);
        $this->view->position[]    = $this->lang->bug->edit;

        /* 赋值。*/
        $this->view->bug              = $bug;
        $this->view->productID        = $productID;
        $this->view->productName      = $this->products[$productID];
        $this->view->moduleOptionMenu = $this->tree->getOptionMenu($productID, $viewType = 'bug', $rooteModuleID = 0);
        $this->view->currentModuleID  = $currentModuleID;
        $this->view->projects         = $this->product->getProjectPairs($bug->product);
        $this->view->stories          = $bug->project ? $this->story->getProjectStoryPairs($bug->project) : $this->story->getProductStoryPairs($bug->product);
        $this->view->tasks            = $this->task->getProjectTaskPairs($bug->project);
        $this->view->users            =  $this->user->getPairs();
        $this->view->builds           = $this->loadModel('build')->getProductBuildPairs($productID);
        $this->view->actions          = $this->action->getList('bug', $bugID);

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
            $actionID = $this->action->create('bug', $bugID, "Resolved as {$this->post->resolution}", $this->post->comment);
            $this->sendmail($bugID, $actionID);
            die(js::locate($this->createLink('bug', 'view', "bugID=$bugID"), 'parent'));
        }

        /* 生成表单。*/
        $bug             = $this->bug->getById($bugID);
        $productID       = $bug->product;
        $header['title'] = $this->products[$productID] . $this->lang->colon . $this->lang->bug->resolve;
        $position[]      = html::a($this->createLink('bug', 'browse', "productID=$productID"), $this->products[$productID]);
        $position[]      = $this->lang->bug->resolve;

        /* 设置菜单。*/
        $this->bug->setMenu($this->products, $productID);

        $users = $this->user->getPairs();
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
            $files = $this->loadModel('file')->saveUpload('files', 'bug', $bugID);
            $actionID = $this->action->create('bug', $bugID, 'Activated', $this->post->comment);
            $this->sendmail($bugID, $actionID);
            die(js::locate($this->createLink('bug', 'view', "bugID=$bugID"), 'parent'));
        }

        /* 生成表单。*/
        $bug        = $this->bug->getById($bugID);
        $productID  = $bug->product;
        $users      = $this->user->getPairs();

        /* 设置菜单。*/
        $this->bug->setMenu($this->products, $productID);

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
            $actionID = $this->action->create('bug', $bugID, 'Closed', $this->post->comment);
            $this->sendmail($bugID, $actionID);
            die(js::locate($this->createLink('bug', 'view', "bugID=$bugID"), 'parent'));
        }

        /* 生成表单。*/
        $bug        = $this->bug->getById($bugID);
        $productID  = $bug->product;

        /* 设置菜单。*/
        $this->bug->setMenu($this->products, $productID);

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

    /* 发送变量。*/
    private function sendmail($bugID, $actionID)
    {
        /* 设定toList和ccList。*/
        $bug     = $this->bug->getByID($bugID);
        $toList = $bug->assignedTo;
        $ccList = trim($bug->mailto, ',');
        if($toList == '')
        {
            if($ccList == '') return;
            if(strpos($ccList, ',') === false)
            {
                $toList = $ccList;
                $ccList = '';
            }
            else
            {
                $commaPos = strpos($ccList, ',');
                $toList = substr($ccList, 0, $commaPos);
                $ccList = substr($ccList, $commaPos + 1);
            }
        }
        elseif(strtolower($toList) == 'closed')
        {
            $toList = $bug->resolvedBy;
        }

        /* 获得action信息。*/
        $action    = $this->action->getById($actionID);
        $histories = $this->action->getHistory($actionID);
        if(strtolower($action->action) == 'opened') $action->comment = $bug->steps;

        /* 赋值，获得邮件内容。*/
        $this->assign('bug', $bug);
        $this->assign('action', $action);
        $this->assign('histories', $histories);
        $mailContent = $this->parse($this->moduleName, 'sendmail');

        /* 发信。*/
        $this->loadModel('mail')->send($toList, 'BUG #' . $bug->id . $this->lang->colon . $bug->title, $mailContent, $ccList);
        if($this->mail->isError()) echo js::error($this->mail->getError());
    }
}
