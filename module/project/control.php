<?php
/**
 * The control file of project module of ZenTaoMS.
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
 * @package     project
 * @version     $Id: control.php 1459 2009-10-23 05:50:21Z wwccss $
 * @link        http://www.zentao.cn
 */
class project extends control
{
    private $projects;

    /* 构造函数，加载product, task, story等模块。*/
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('product');
        $this->loadModel('task');
        $this->loadModel('story');
        $this->projects = $this->project->getPairs();
        if(empty($this->projects)) $this->locate($this->createLink('project', 'create'));
    }

    /* 项目视图首页，暂时跳转到浏览页面。*/
    public function index()
    {
        $this->locate($this->createLink($this->moduleName, 'browse'));
    }

    /* 浏览某一个项目。*/
    public function browse($projectID = 0, $tabID = 'task')
    {
        /* 获取当前项目的详细信息，相关产品，子项目以及团队成员。*/
        $projectID     = common::saveProjectState($projectID, key($this->projects));
        $project       = $this->project->getById($projectID);
        $products      = $this->project->getProducts($project->id);
        $childProjects = $this->project->getChildProjects($project->id);
        $teamMembers   = $this->project->getTeamMembers($project->id);

        /* 设定header和position信息。*/
        $header['title'] = $this->lang->project->browse . $this->lang->colon . $project->name;
        $position[]      = $project->name;

        /* 赋值。*/
        $this->assign('header',        $header);
        $this->assign('position',      $position);
        $this->assign('projects',      $this->projects);
        $this->assign('project',       $project);
        $this->assign('childProjects', $childProjects);
        $this->assign('products',      $products);
        $this->assign('teamMembers',   $teamMembers);
        $this->assign('tabID',         $tabID);

        /* 处理Tab。*/
        if($tabID == 'task')
        {
            $tasks = $this->task->getProjectTasks($projectID);
            $this->assign('tasks', $tasks);
        }
        elseif($tabID == 'story')
        {
            $stories = $this->story->getProjectStories($projectID);
            $this->assign('stories', $stories);
        }

        $this->display();
    }

    /* 创建一个项目。*/
    public function create()
    {
        if(!empty($_POST))
        {
            $projectID = $this->project->create($_POST);
            $this->locate($this->createLink('project', 'browse', "projectID=$projectID"));
        }
        $header['title'] = $this->lang->project->create;
        $position[]      = $header['title'];
        $projects        = array('' => '') + $this->projects;

        $this->assign('header',   $header);
        $this->assign('position', $position);
        $this->assign('projects', $projects);
        $this->display();
    }

    /* 编辑一个项目。*/
    public function edit($projectID)
    {
        $browseProjectLink = $this->createLink('project', 'browse', "projectID=$projectID");
        if(!empty($_POST))
        {
            $this->project->update($projectID);
            die(js::locate($browseProjectLink, 'parent'));
        }
        $projects = array('' => '') + $this->projects;
        $project  = $this->project->getById($projectID);

        /* 从列表中删除当前项目。*/
        unset($projects[$projectID]);

        /* 标题和位置信息。*/
        $header['title'] = $this->lang->project->edit . $this->lang->colon . $project->name;
        $position[]      = html::a($browseProjectLink, $project->name);
        $position[]      = $this->lang->project->edit;

        /* 赋值。*/
        $this->assign('header',   $header);
        $this->assign('position', $position);
        $this->assign('projects', $projects);
        $this->assign('project',  $project);

        $this->display();
    }

    /* 删除一个项目。*/
    public function delete($projectID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            echo js::confirm(sprintf($this->lang->project->confirmDelete, $this->projects[$projectID]), $this->createLink('project', 'delete', "projectID=$projectID&confirm=yes"));
            exit;
        }
        else
        {
            $this->project->delete($projectID);
            echo js::locate($this->createLink('project', 'browse'), 'parent');
            exit;
        }
    }

    /* 维护相关的产品。*/
    public function manageProducts($projectID)
    {
        $browseProjectLink = $this->createLink('project', 'browse', "projectID=$projectID");
        if(!empty($_POST))
        {
            $this->project->updateProducts($projectID);
            die(js::locate($browseProjectLink));
        }
        $project  = $this->project->getById($projectID);

        /* 标题和位置信息。*/
        $header['title'] = $this->lang->project->manageProducts . $this->lang->colon . $project->name;
        $position[]      = html::a($browseProjectLink, $project->name);
        $position[]      = $this->lang->project->manageProducts;

        $allProducts    = $this->product->getPairs();
        $linkedProducts = $this->project->getProducts($project->id);
        $linkedProducts = join(',', array_keys($linkedProducts));

        /* 赋值。*/
        $this->assign('header',         $header);
        $this->assign('position',       $position);
        $this->assign('allProducts',    $allProducts);
        $this->assign('linkedProducts', $linkedProducts);

        $this->display();
    }

    /* 维护子项目。*/
    public function manageChilds($projectID)
    {
        $browseProjectLink = $this->createLink('project', 'browse', "projectID=$projectID");
        if(!empty($_POST))
        {
            $this->project->updateChilds($projectID);
            die(js::locate($browseProjectLink));
        }
        $project  = $this->project->getById($projectID);
        $projects = $this->projects;
        unset($projects[$projectID]);
        unset($projects[$project->parent]);
        if(empty($projects)) $this->locate($browseProjectLink);

        /* 标题和位置信息。*/
        $header['title'] = $this->lang->project->manageChilds . $this->lang->colon . $project->name;
        $position[]      = html::a($browseProjectLink, $project->name);
        $position[]      = $this->lang->project->manageChilds;

        $childProjects = $this->project->getChildProjects($project->id);
        $childProjects = join(",", array_keys($childProjects));

        /* 赋值。*/
        $this->assign('header',        $header);
        $this->assign('position',      $position);
        $this->assign('projects',      $projects);
        $this->assign('childProjects', $childProjects);

        $this->display();
    }
    
    /* 维护团队成员。*/
    public function manageMembers($projectID = 0)
    {
        $browseProjectLink = $this->createLink('project', 'browse', "projectID=$projectID");
        if(!empty($_POST))
        {
            $this->project->manageMembers($projectID);
            $this->locate($browseProjectLink);
            exit;
        }
        $this->loadModel('user');

        $project = $this->project->getById($projectID);
        $users   = $this->user->getPairs($this->app->company->id);
        $users   = array('' => '') + $users;
        $members = $this->project->getTeamMembers($projectID);

        $header['title'] = $this->lang->project->manageMembers . $this->lang->colon . $project->name;
        $position[]      = html::a($browseProjectLink, $project->name);
        $position[]      = $this->lang->project->manageMembers;
        $this->assign('header',   $header);
        $this->assign('position', $position);

        $this->assign('project', $project);
        $this->assign('users',   $users);
        $this->assign('members', $members);
        $this->display();
    }

    /* 移除一个成员。*/
    public function unlinkMember($projectID, $account, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            echo js::confirm($this->lang->project->confirmUnlinkMember, $this->createLink('project', 'unlinkMember', "projectID=$projectID&account=$account&confirm=yes"));
            exit;
        }
        else
        {
            $this->project->unlinkMember($projectID, $account);
            echo js::locate($this->createLink('project', 'browse', "projectID=$projectID"), 'parent');
            exit;
        }
    }

    /* 关联需求。*/
    public function linkStory($projectID = 0)
    {
        /* 获得项目和相关产品信息。如果没有相关产品，则跳转到产品关联页面。*/
        $project    = $this->project->getById($projectID);
        $products   = $this->project->getProducts($projectID);
        $browseLink = $this->createLink('project', 'browse', "projectID=$projectID&tab=story");

        if(empty($products))
        {
            echo js::alert($this->lang->project->errorNoLinkedProducts);
            die(js::locate($this->createLink('project', 'manageproducts', "projectID=$projectID")));
        }

        /* 更新数据库。*/
        if(!empty($_POST))
        {
            $this->project->linkStory($projectID);
            die(js::locate($browseLink, 'parent'));
            exit;
        }

        /* 加载数据。*/
        $this->loadModel('story');

        $header['title'] = $project->name . $this->lang->colon . $this->lang->project->linkStory;
        $position[]      = html::a($browseLink, $project->name);
        $position[]      = $this->lang->project->linkStory;

        $allStories = $this->story->getProductStories(array_keys($products));
        $prjStories = $this->story->getProjectStoryPair($projectID);

        $this->assign('header',     $header);
        $this->assign('position',   $position);
        $this->assign('project',    $project);
        $this->assign('products',   $products);
        $this->assign('allStories', $allStories);
        $this->assign('prjStories', $prjStories);
        $this->display();
    }

    /* 移除一个需求。*/
    public function unlinkStory($projectID, $storyID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            echo js::confirm($this->lang->project->confirmUnlinkStory, $this->createLink('project', 'unlinkstory', "projectID=$projectID&storyID=$storyID&confirm=yes"));
            exit;
        }
        else
        {
            $this->project->unlinkStory($projectID, $storyID);
            echo js::locate($this->createLink('project', 'browse', "projectID=$projectID&tab=story"), 'parent');
            exit;
        }
    }
}
