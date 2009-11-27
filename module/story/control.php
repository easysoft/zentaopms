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
 * @copyright   Copyright: 2009 Chunsheng Wang
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
            $this->action->create('story', $storyID, 'Opened', '');

            die(js::locate($this->createLink('story', 'view', "storyID=$storyID"), 'parent'));
        }

        $product  = $this->product->findByID($productID);
        $products = $this->product->getPairs();
        $users    = $this->user->getPairs();
        $moduleOptionMenu = $this->tree->getOptionMenu($productID, $viewType = 'product');

        $header['title'] = $product->name . $this->lang->colon . $this->lang->story->create;
        $position[]      = html::a($this->createLink('product', 'browse', "product=$productID"), $product->name);
        $position[]      = $this->lang->story->create;

        $this->assign('header',           $header);
        $this->assign('position',         $position);
        $this->assign('product',          $product);
        $this->assign('products',         $products);
        $this->assign('users',            $users);
        $this->assign('moduleID',         $moduleID);
        $this->assign('moduleOptionMenu', $moduleOptionMenu);
        $this->display();
    }

    /* 编辑需求：生成表单。*/
    public function edit($storyID)
    {
        if(!empty($_POST))
        {
            $this->loadModel('action');
            $changes = $this->story->update($storyID);
            if(dao::isError()) die(js::error(dao::getError()));
            if($this->post->comment != '' or !empty($changes))
            {
                $action = !empty($changes) ? 'Edited' : 'Commented';
                $actionID = $this->action->create('story', $storyID, $action, $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }
            die(js::locate($this->createLink('story', 'view', "storyID=$storyID"), 'parent'));
        }

        $story    = $this->story->findByID($storyID);
        $product  = $this->product->findByID($story->product);
        $products = $this->product->getPairs();
        $users    = $this->user->getPairs();
        $moduleOptionMenu = $this->tree->getOptionMenu($product->id, $viewType = 'product');

        $header['title'] = $product->name . $this->lang->colon . $this->lang->story->edit . $this->lang->colon . $story->title;
        $position[]      = html::a($this->createLink('product', 'browse', "product=$product->id"), $product->name);
        $position[]      = $this->lang->story->edit;

        $this->assign('header',           $header);
        $this->assign('position',         $position);
        $this->assign('product',          $product);
        $this->assign('products',         $products);
        $this->assign('story',            $story);
        $this->assign('moduleOptionMenu', $moduleOptionMenu);
        $this->assign('users',            $users);
        $this->display();
    }

    /* 需求详情。*/
    public function view($storyID)
    {
        $this->loadModel('action');
        $storyID    = (int)$storyID;
        $story      = $this->dao->findByID((int)$storyID)->from(TABLE_STORY)->fetch();
        $product    = $this->dao->findById($story->product)->from(TABLE_PRODUCT)->fields('name, id')->fetch();
        $modulePath = $this->tree->getParents($story->module);
        $users      = $this->user->getPairs();

        $header['title'] = $product->name . $this->lang->colon . $this->lang->story->view . $this->lang->colon . $story->title;
        $position[]      = html::a($this->createLink('product', 'browse', "product=$product->id"), $product->name);
        $position[]      = $this->lang->story->view;

        $this->assign('header',     $header);
        $this->assign('position',   $position);
        $this->assign('product',    $product);
        $this->assign('story',      $story);
        $this->assign('users',      $users);
        $this->assign('actions',    $this->action->getList('story', $storyID));
        $this->assign('modulePath', $modulePath);
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
            $story = $this->story->findById($storyID);
            $this->story->delete($storyID);
            echo js::locate($this->createLink('product', 'browse', "productID=$story->product"), 'parent');
            exit;
        }
    }

    /* 需求的任务列表。*/
    public function tasks($storyID, $projectID = 0)
    {
        $this->loadModel('task');
        $tasks = $this->task->getStoryTaskPairs($storyID, $projectID);
        $this->assign('tasks', $tasks);
        $this->display();
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
}
