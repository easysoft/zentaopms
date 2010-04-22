<?php
/**
 * The control file of story module of ZenTaoMS.
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
 * @package     story
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
class story extends control
{
    /* 构造函数。*/
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('product');
        $this->loadModel('tree');
        $this->loadModel('user');
    }

    /* 新增需求。*/
    public function create($productID = 0, $moduleID = 0)
    {
        if(!empty($_POST))
        {
            $storyID = $this->story->create();
            if(dao::isError()) die(js::error(dao::getError()));
            $this->loadModel('action');
            $actionID = $this->action->create('story', $storyID, 'Opened', '');
            $this->sendMail($storyID, $actionID);
            die(js::locate($this->createLink('story', 'view', "storyID=$storyID"), 'parent'));
        }

        /* 设置产品相关数据。*/
        $product  = $this->product->getById($productID);
        $products = $this->product->getPairs();
        $users    = $this->user->getPairs();
        $moduleOptionMenu = $this->tree->getOptionMenu($productID, $viewType = 'product');

        /* 设置菜单。*/
        $this->product->setMenu($products, $product->id);

        /* 赋值到模板。*/
        $this->view->header->title    = $product->name . $this->lang->colon . $this->lang->story->create;
        $this->view->position[]       = html::a($this->createLink('product', 'browse', "product=$productID"), $product->name);
        $this->view->position[]       = $this->lang->story->create;
        $this->view->product          = $product;
        $this->view->products         = $products;
        $this->view->users            = $users;
        $this->view->moduleID         = $moduleID;
        $this->view->moduleOptionMenu = $moduleOptionMenu;
        $this->view->plans            = $this->loadModel('productplan')->getPairs($productID);
        $this->display();
    }

    /* 变更、编辑时的共同操作。 */
    private function commonAction($storyID)
    {
        /* 获取数据。*/
        $story    = $this->story->getById($storyID);
        $product  = $this->product->getById($story->product);
        $products = $this->product->getPairs();
        $users    = $this->user->getPairs();
        $moduleOptionMenu = $this->tree->getOptionMenu($product->id, $viewType = 'product');

        /* 设置菜单。*/
        $this->product->setMenu($products, $product->id);

        /* 赋值到模板。*/
        $this->view->position[]       = html::a($this->createLink('product', 'browse', "product=$product->id"), $product->name);
        $this->view->product          = $product;
        $this->view->products         = $products;
        $this->view->story            = $story;
        $this->view->users            = $users;
        $this->view->moduleOptionMenu = $moduleOptionMenu;
        $this->view->plans            = $this->loadModel('productplan')->getPairs($product->id);
        $this->view->actions          = $this->action->getList('story', $storyID);
    }

    /* 编辑需求。*/
    public function edit($storyID)
    {
        $this->loadModel('action');
        if(!empty($_POST))
        {
            $changes = $this->story->update($storyID);
            if(dao::isError()) die(js::error(dao::getError()));
            if($this->post->comment != '' or !empty($changes))
            {
                $action   = !empty($changes) ? 'Edited' : 'Commented';
                $actionID = $this->action->create('story', $storyID, $action, $this->post->comment);
                $this->action->logHistory($actionID, $changes);
                $this->sendMail($storyID, $actionID);
            }
            die(js::locate($this->createLink('story', 'view', "storyID=$storyID"), 'parent'));
        }

        $this->commonAction($storyID);

        /* 赋值到模板。*/
        $this->view->header->title = $this->view->product->name . $this->lang->colon . $this->lang->story->edit . $this->lang->colon . $this->view->story->title;
        $this->view->position[]    = $this->lang->story->edit;
        $this->display();
    }

    /* 变更需求。*/
    public function change($storyID)
    {
        $this->loadModel('action');
        if(!empty($_POST))
        {
            $changes = $this->story->change($storyID);
            if(dao::isError()) die(js::error(dao::getError()));
            $version = $this->dao->findById($storyID)->from(TABLE_STORY)->fetch('version');
            $files = $this->loadModel('file')->saveUpload('story', $storyID, $version);
            if($this->post->comment != '' or !empty($changes) or !empty($files))
            {
                $action = (!empty($changes) or !empty($files)) ? 'Changed' : 'Commented';
                $fileAction = '';
                if(!empty($files)) $fileAction = $this->lang->addFiles . join(',', $files) . "\n" ;
                $actionID = $this->action->create('story', $storyID, $action, $fileAction . $this->post->comment);
                $this->action->logHistory($actionID, $changes);
                $this->sendMail($storyID, $actionID);
            }
            die(js::locate($this->createLink('story', 'view', "storyID=$storyID"), 'parent'));
        }

        $this->commonAction($storyID);
        $this->story->getAffectedScope($this->view->story);
        $this->app->loadLang('task');
        $this->app->loadLang('bug');
        $this->app->loadLang('testcase');
        $this->app->loadLang('project');

        /* 赋值到模板。*/
        $this->view->header->title = $this->view->product->name . $this->lang->colon . $this->lang->story->change . $this->lang->colon . $this->view->story->title;
        $this->view->position[]    = $this->lang->story->change;
        $this->display();
    }

    /* 激活需求。*/
    public function activate($storyID)
    {
        $this->loadModel('action');
        if(!empty($_POST))
        {
            $this->story->activate($storyID);
            if(dao::isError()) die(js::error(dao::getError()));
            $actionID = $this->action->create('story', $storyID, 'Activated', $this->post->comment);
            $this->action->logHistory($actionID, $changes);
            $this->sendMail($storyID, $actionID);
            die(js::locate($this->createLink('story', 'view', "storyID=$storyID"), 'parent'));
        }

        $this->commonAction($storyID);

        /* 赋值到模板。*/
        $this->view->header->title = $this->view->product->name . $this->lang->colon . $this->lang->story->activate . $this->lang->colon . $this->view->story->title;
        $this->view->position[]    = $this->lang->story->activate;
        $this->display();
    }

    /* 需求详情。*/
    public function view($storyID, $version = 0)
    {
        $this->loadModel('action');
        $storyID = (int)$storyID;
        $story   = $this->story->getById($storyID, $version);
        if(!$story) die(js::error($this->lang->notFound) . js::locate('back'));

        $story->files = $this->loadModel('file')->getByObject('story', $storyID);
        $product      = $this->dao->findById($story->product)->from(TABLE_PRODUCT)->fields('name, id')->fetch();
        $plan         = $this->dao->findById($story->plan)->from(TABLE_PRODUCTPLAN)->fetch('title');
        $modulePath   = $this->tree->getParents($story->module);
        $users        = $this->user->getPairs('noletter');

        /* 设置菜单。*/
        $this->product->setMenu($this->product->getPairs(), $product->id);

        $header['title'] = $product->name . $this->lang->colon . $this->lang->story->view . $this->lang->colon . $story->title;
        $position[]      = html::a($this->createLink('product', 'browse', "product=$product->id"), $product->name);
        $position[]      = $this->lang->story->view;

        $this->assign('header',     $header);
        $this->assign('position',   $position);
        $this->assign('product',    $product);
        $this->assign('plan',       $plan);
        $this->assign('story',      $story);
        $this->assign('users',      $users);
        $this->assign('actions',    $this->action->getList('story', $storyID));
        $this->assign('modulePath', $modulePath);
        $this->assign('version',    $version == 0 ? $story->version : $version);
        $this->display();
    }

    /* 删除一条story。*/
    public function delete($storyID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            echo js::confirm($this->lang->story->confirmDelete, $this->createLink('story', 'delete', "story=$storyID&confirm=yes"), '');
            exit;
        }
        else
        {
            $this->story->delete(TABLE_STORY, $storyID);
            die(js::locate($this->session->storyList, 'parent'));
        }
    }

    /* 评审一条需求。*/
    public function review($storyID)
    {
        $this->loadModel('action');

        if(!empty($_POST))
        {
            $this->story->review($storyID);
            if(dao::isError()) die(js::error(dao::getError()));
            $result = $this->post->result;
            if(strpos('done,postponed,subdivided', $this->post->closedReason) !== false) $result = 'pass';
            $actionID = $this->action->create('story', $storyID, 'Reviewed', $this->post->comment, ucfirst($result));
            $this->action->logHistory($actionID);
            $this->sendMail($storyID, $actionID);
            if($this->post->result == 'reject')
            {
                $this->action->create('story', $storyID, 'Closed', '', ucfirst($this->post->closedReason));
            }
            die(js::locate(inlink('view', "storyID=$storyID"), 'parent'));
        }

        /* 获取需求和产品信息。*/
        $story    = $this->story->getById($storyID);
        $product  = $this->dao->findById($story->product)->from(TABLE_PRODUCT)->fields('name, id')->fetch();

        /* 设置菜单。*/
        $this->product->setMenu($this->product->getPairs(), $product->id);

        /* 设置评审结果可选值。*/
        if($story->status == 'draft' and $story->version == 1) unset($this->lang->story->reviewResultList['revert']);
        if($story->status == 'changed') unset($this->lang->story->reviewResultList['reject']);

        /* 导航信息。*/
        $this->view->header->title = $product->name . $this->lang->colon . $this->lang->story->view . $this->lang->colon . $story->title;
        $this->view->position[]    = html::a($this->createLink('product', 'browse', "product=$product->id"), $product->name);
        $this->view->position[]    = $this->lang->story->view;

        /* 赋值。*/
        $this->view->product = $product;
        $this->view->story   = $story;
        $this->view->actions = $this->action->getList('story', $storyID);
        $this->view->users   = $this->loadModel('user')->getPairs();

        /* 影响范围。*/
        $this->story->getAffectedScope($this->view->story);
        $this->app->loadLang('task');
        $this->app->loadLang('bug');
        $this->app->loadLang('testcase');
        $this->app->loadLang('project');

        $this->display();
    }

    /* 关闭一条需求。*/
    public function close($storyID)
    {
        $this->loadModel('action');

        if(!empty($_POST))
        {
            $this->story->close($storyID);
            if(dao::isError()) die(js::error(dao::getError()));
            $actionID = $this->action->create('story', $storyID, 'Closed', $this->post->comment, ucfirst($this->post->closedReason));
            $this->action->logHistory($actionID);
            $this->sendMail($storyID, $actionID);
            die(js::locate(inlink('view', "storyID=$storyID"), 'parent'));
        }

        /* 获取需求和产品信息。*/
        $story    = $this->story->getById($storyID);
        $product  = $this->dao->findById($story->product)->from(TABLE_PRODUCT)->fields('name, id')->fetch();

        /* 设置菜单。*/
        $this->product->setMenu($this->product->getPairs(), $product->id);

        /* 设置评审结果可选值。*/
        if($story->status == 'draft') unset($this->lang->story->reasonList['cancel']);

        /* 导航信息。*/
        $this->view->header->title = $product->name . $this->lang->colon . $this->lang->close . $this->lang->colon . $story->title;
        $this->view->position[]    = html::a($this->createLink('product', 'browse', "product=$product->id"), $product->name);
        $this->view->position[]    = $this->lang->close;

        /* 赋值。*/
        $this->view->product = $product;
        $this->view->story   = $story;
        $this->view->actions = $this->action->getList('story', $storyID);
        $this->view->users   = $this->loadModel('user')->getPairs();
        $this->display();
    }

    /* 需求的任务列表。*/
    public function tasks($storyID, $projectID = 0)
    {
        $this->loadModel('task');
        $tasks = $this->task->getStoryTaskPairs($storyID, $projectID);
        $this->assign('tasks', $tasks);
        $this->display();
        exit;
    }

    /* Ajax: 获取某一个项目的需求列表。*/
    public function ajaxGetProjectStories($projectID, $productID = 0, $storyID = 0)
    {
        $stories = $this->story->getProjectStoryPairs($projectID, $productID);
        die(html::select('story', $stories, $storyID));
    }

    /* Ajax: 获取某一个产品的需求列表。*/
    public function ajaxGetProductStories($productID, $moduleID = 0, $storyID = 0)
    {
        $stories = $this->story->getProductStoryPairs($productID, $moduleID);
        die(html::select('story', $stories, $storyID, "class=''"));
    }

    /* 发送邮件。*/
    private function sendmail($storyID, $actionID)
    {
        /* 获得action信息。*/
        $action          = $this->action->getById($actionID);
        $history         = $this->action->getHistory($actionID);
        $action->history = isset($history[$actionID]) ? $history[$actionID] : array();
        if(strtolower($action->action) == 'opened') $action->comment = $story->spec;

        /* 设定toList和ccList。*/
        $story  = $this->story->getById($storyID);
        $toList = $story->assignedTo;
        $ccList = str_replace(' ', '', trim($story->mailto, ','));

        /* 当需求的操作是变更或者评审的时候，抄送给项目中成员。*/
        if(strtolower($action->action) == 'changed' or strtolower($action->action) == 'reviewed')
        {
            $prjMembers = $this->story->getProjectMembers($storyID);
            $ccList .= ',' . join(',', $prjMembers);
            $ccList = ltrim(',', $ccList);
        }

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
                $toList   = substr($ccList, 0, $commaPos);
                $ccList   = substr($ccList, $commaPos + 1);
            }
        }
        elseif($toList = 'closed')
        {
            $toList = $story->openedBy;
        }

        /* 赋值，获得邮件内容。*/
        $this->view->story  = $story;
        $this->view->action = $action;
        $this->view->users  = $this->user->getPairs('noletter');
        $mailContent = $this->parse($this->moduleName, 'sendmail');

        /* 发信。*/
        $this->loadModel('mail')->send($toList, 'STORY #' . $story->id . $this->lang->colon . $story->title, $mailContent, $ccList);
        if($this->mail->isError()) echo js::error($this->mail->getError());
    }
}
