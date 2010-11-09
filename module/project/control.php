<?php
/**
 * The control file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class project extends control
{
    private $projects;

    /* 构造函数，加载product, task, story等模块。*/
    public function __construct()
    {
        parent::__construct();
        if($this->methodName != 'computeburn')
        {
            $this->projects = $this->project->getPairs();
            if(!$this->projects and $this->methodName != 'create') $this->locate($this->createLink('project', 'create'));
        }
    }

    /* 项目视图首页，*/
    public function index()
    {
        if(empty($this->projects)) $this->locate($this->createLink('project', 'create'));
        $this->locate($this->createLink('project', 'browse'));
    }

    /* 浏览某一个项目。*/
    public function browse($projectID = 0)
    {
        $this->locate($this->createLink($this->moduleName, 'task', "projectID=$projectID"));
    }

    /* task, story, bug等方法的一些公共操作。*/
    private function commonAction($projectID = 0)
    {
        /* 加载product模块。*/
        $this->loadModel('product');

        /* 获取当前项目的详细信息，相关产品，子项目以及团队成员。*/
        $projectID     = common::saveProjectState($projectID, array_keys($this->projects));
        $project       = $this->project->getById($projectID);
        $products      = $this->project->getProducts($project->id);
        $childProjects = $this->project->getChildProjects($project->id);
        $teamMembers   = $this->project->getTeamMembers($project->id);

        /* 设置菜单。*/
        $this->project->setMenu($this->projects, $project->id);

        /* 将其赋值到模板系统。*/
        $this->view->projects      = $this->projects;
        $this->view->project       = $project;
        $this->view->childProjects = $childProjects;
        $this->view->products      = $products;
        $this->view->teamMembers   = $teamMembers;

        /* 检查是否有访问权限。*/
        if(!$this->project->checkPriv($project))
        {
            echo(js::alert($this->lang->project->accessDenied));
            die(js::locate('back'));
        }

        return $project;
    }

    /* 浏览某一个项目下面的任务。*/
    public function task($projectID = 0, $status = 'all', $orderBy = 'status_asc,id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* 公共的操作。*/
        $project   = $this->commonAction($projectID);
        $projectID = $project->id;

        /* 记录用户当前选择的列表。*/
        $uri = $this->app->getURI(true);
        $this->app->session->set('taskList',    $uri);
        $this->app->session->set('storyList',   $uri);
        $this->app->session->set('projectList', $uri);

        /* 设定header和position信息。*/
        $this->view->header->title = $project->name . $this->lang->colon . $this->lang->project->task;
        $this->view->position[]    = html::a($this->createLink('project', 'browse', "projectID=$projectID"), $project->name);
        $this->view->position[]    = $this->lang->project->task;

        /* 分页操作。*/
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);
        $tasks = $this->loadModel('task')->getProjectTasks($projectID, $status, $orderBy, $pager);

        /* 赋值。*/
        $this->view->tasks      = $tasks ? $tasks : array();
        $this->view->tabID      = 'task';
        $this->view->pager      = $pager->get();
        $this->view->recTotal   = $pager->recTotal;
        $this->view->recPerPage = $pager->recPerPage;
        $this->view->orderBy    = $orderBy;
        $this->view->browseType = $status != 'needConfirm' ? 'list' : 'needconfirm';

        $this->display();
    }

    /* 按照tree的方式查看任务。*/
    public function grouptask($projectID = 0, $groupBy = 'story')
    {
        /* 公共的操作。*/
        $project   = $this->commonAction($projectID);
        $projectID = $project->id;

        /* 记录用户当前选择的列表。*/
        $this->app->session->set('taskList',  $this->app->getURI(true));
        $this->app->session->set('storyList', $this->app->getURI(true));

        /* 设定header和position信息。*/
        $this->view->header['title'] = $project->name . $this->lang->colon . $this->lang->project->task;
        $this->view->position[]      = html::a($this->createLink('project', 'browse', "projectID=$projectID"), $project->name);
        $this->view->position[]      = $this->lang->project->task;

        /* 获得任务列表，并将其分组。*/
        $tasks       = $this->loadModel('task')->getProjectTasks($projectID, $status = 'all', $groupBy);
        $groupBy     = strtolower(str_replace('`', '', $groupBy));
        $taskLang    = $this->lang->task;
        $groupByList = array();
        $groupTasks  = array();
        foreach($tasks as $task)
        {
            if($groupBy == 'story')
            { 
                $groupTasks[$task->story][] = $task;
                $groupByList[$task->story]  = $task->storyTitle;
            }
            elseif($groupBy == 'status')
            {
                $groupTasks[$taskLang->statusList[$task->status]][] = $task;
            }
            elseif($groupBy == 'owner')
            {
                $groupTasks[$task->ownerRealName][] = $task;
            }
            elseif($groupBy == 'type')
            {
                $groupTasks[$taskLang->typeList[$task->type]][] = $task;
            }
            else
            {
                $groupTasks[$task->$groupBy][] = $task;
            }
        }

        /* 赋值。*/
        $this->view->tasks       = $groupTasks;
        $this->view->tabID       = 'task';
        $this->view->groupByList = $groupByList;
        $this->view->browseType  = $groupBy;
        $this->display();
    }

    /* 将之前未完成的项目任务导入。*/
    public function importTask($projectID)
    {
        if(!empty($_POST))
        {
            $this->project->importTask($projectID);
            die(js::locate(inlink('task', "projectID=$projectID"), 'parent'));
        }

        $project = $this->commonAction($projectID);

        /* 登记session。*/
        $this->app->session->set('taskList',  $this->app->getURI(true));
        $this->app->session->set('storyList', $this->app->getURI(true));

        $this->view->header->title  = $project->name . $this->lang->colon . $this->lang->project->importTask;
        $this->view->position[]     = html::a(inlink('browse', "projectID=$projectID"), $project->name);
        $this->view->position[]     = $this->lang->project->importTask;
        $this->view->tasks2Imported = $this->project->getTasks2Imported($projectID);
        $this->display();
    }

    /* 浏览某一个项目下面的需求。*/
    public function story($projectID = 0)
    {
        /* 加载story, user模块，加载task模块的语言。*/
        $this->loadModel('story');
        $this->loadModel('user');
        $this->loadModel('task');

        /* 记录用户当前选择的列表。*/
        $this->app->session->set('storyList', $this->app->getURI(true));

        /* 公共的操作。*/
        $project = $this->commonAction($projectID);

        /* 设定header和position信息。*/
        $header['title'] = $project->name . $this->lang->colon . $this->lang->project->story;
        $position[]      = html::a($this->createLink('project', 'browse', "projectID=$projectID"), $project->name);
        $position[]      = $this->lang->project->story;

        /* 分页操作。*/
        $stories    = $this->story->getProjectStories($projectID);
        $storyTasks = $this->task->getStoryTaskCounts(array_keys($stories), $projectID);
        $users      = $this->user->getPairs('noletter');

        /* 赋值。*/
        $this->view->header     = $header;
        $this->view->position   = $position;
        $this->view->stories    = $stories;
        $this->view->storyTasks = $storyTasks;
        $this->view->tabID      = 'story';
        $this->view->users      = $users;

        $this->display();
    }

    /* 浏览某一个项目下面的bug。*/
    public function bug($projectID = 0, $orderBy = 'status,id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        /* 加载bug和user模块。*/
        $this->loadModel('bug');
        $this->loadModel('user');

        /* 登记session。*/
        $this->session->set('bugList', $this->app->getURI(true));

        /* 公共的操作。*/
        $project   = $this->commonAction($projectID);
        $products  = $this->project->getProducts($project->id);
        $productID = key($products);    // 取第一个产品，用来提交Bug。

        /* 设定header和position信息。*/
        $header['title'] = $project->name . $this->lang->colon . $this->lang->project->bug;
        $position[]      = html::a($this->createLink('project', 'browse', "projectID=$projectID"), $project->name);
        $position[]      = $this->lang->project->bug;

        /* 分页操作。*/
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);
        $bugs  = $this->bug->getProjectBugs($projectID, $orderBy, $pager);
        $users = $this->user->getPairs('noletter');

        /* 赋值。*/
        $this->view->header    = $header;
        $this->view->position  = $position;
        $this->view->bugs      = $bugs;
        $this->view->tabID     = 'bug';
        $this->view->pager     = $pager;
        $this->view->orderBy   = $orderBy;
        $this->view->users     = $users;
        $this->view->productID = $productID;

        $this->display();
    }

    /* 浏览某一个项目下面的build。*/
    public function build($projectID = 0)
    {
        $this->session->set('buildList', $this->app->getURI(true));

        /* 公共的操作。*/
        $project = $this->commonAction($projectID);

        /* 设定header和position信息。*/
        $this->view->header->title = $project->name . $this->lang->colon . $this->lang->project->build;
        $this->view->position[]    = html::a(inlink('browse', "projectID=$projectID"), $project->name);
        $this->view->position[]    = $this->lang->project->build;

        /* 查找build列表。*/
        $this->view->builds = $this->loadModel('build')->getProjectBuilds((int)$projectID);

        $this->display();
    }

    /* 某一个项目的燃烧图。*/
    public function burn($projectID = 0)
    {
        $this->loadModel('report');

        /* 公共的操作。*/
        $project = $this->commonAction($projectID);

        /* 设定header和position信息。*/
        $header['title'] = $project->name . $this->lang->colon . $this->lang->project->burn;
        $position[]      = html::a($this->createLink('project', 'browse', "projectID=$projectID"), $project->name);
        $position[]      = $this->lang->project->burn;

        /* 生成图表。*/
        $dataXML = $this->report->createSingleXML($this->project->getBurnData($project->id), $this->lang->project->charts->burn->graph);
        $charts  = $this->report->createJSChart('line', $dataXML, 800);

        /* 赋值。*/
        $this->view->header   = $header;
        $this->view->position = $position;
        $this->view->tabID    = 'burn';
        $this->view->charts   = $charts;

        $this->display();
    }

    /* 燃烧图所需要的数据。*/
    public function burnData($projectID = 0)
    {
        $this->loadModel('report');
        $sets = $this->project->getBurnData($projectID);
        die($this->report->createSingleXML($sets, $this->lang->project->charts->burn->graph));
    }

    /* 计算燃烧图数据。*/
    public function computeBurn($reload = 'no')
    {
        $this->view->burns = $this->project->computeBurn();
        if($reload == 'yes') die(js::reload('parent'));
        die($this->display());
    }

    /* 团队成员。*/
    public function team($projectID = 0)
    {
        /* 公共的操作。*/
        $project = $this->commonAction($projectID);

        /* 设定header和position信息。*/
        $header['title'] = $project->name . $this->lang->colon . $this->lang->project->team;
        $position[]      = html::a($this->createLink('project', 'browse', "projectID=$projectID"), $project->name);
        $position[]      = $this->lang->project->team;

        $this->view->header   = $header;
        $this->view->position = $position;

        $this->display();
    }

    /* 文档列表。*/
    public function doc($projectID)
    {
        $this->project->setMenu($this->projects, $projectID);
        $this->session->set('docList', $this->app->getURI(true));

        /* 赋值。*/
        $project = $this->dao->findById($projectID)->from(TABLE_PROJECT)->fetch();
        $this->view->header->title = $this->lang->project->doc;
        $this->view->position[]    = html::a($this->createLink($this->moduleName, 'browse'), $project->name);
        $this->view->position[]    = $this->lang->project->doc;
        $this->view->project       = $project;
        $this->view->docs          = $this->loadModel('doc')->getProjectDocs($projectID);
        $this->view->modules       = $this->doc->getProjectModulePairs();
        $this->view->users         = $this->loadModel('user')->getPairs('noletter');
        $this->display();
    }


    /* 创建一个项目。*/
    public function create()
    {
        if(!empty($_POST))
        {
            $projectID = $this->project->create();
            if(dao::isError()) die(js::error(dao::getError()));
            $this->loadModel('action')->create('project', $projectID, 'opened');
            die(js::locate($this->createLink('project', 'browse', "projectID=$projectID"), 'parent'));
        }

        /* 设置菜单。*/
        $this->project->setMenu($this->projects, '');

        $this->view->header->title = $this->lang->project->create;
        $this->view->position[]    = $this->view->header->title;
        $this->view->projects      = array('' => '') + $this->projects;
        $this->view->groups        = $this->loadModel('group')->getPairs();
        $this->display();
    }

    /* 编辑一个项目。*/
    public function edit($projectID)
    {
        $browseProjectLink = $this->createLink('project', 'browse', "projectID=$projectID");
        if(!empty($_POST))
        {
            $changes = $this->project->update($projectID);
            if(dao::isError()) die(js::error(dao::getError()));
            if($changes)
            {
                $actionID = $this->loadModel('action')->create('project', $projectID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }
            die(js::locate($this->createLink('project', 'view', "projectID=$projectID"), 'parent'));
        }

        /* 设置菜单。*/
        $this->project->setMenu($this->projects, $projectID);

        $projects = array('' => '') + $this->projects;
        $project  = $this->project->getById($projectID);

        /* 从列表中删除当前项目。*/
        unset($projects[$projectID]);

        /* 标题和位置信息。*/
        $header['title'] = $this->lang->project->edit . $this->lang->colon . $project->name;
        $position[]      = html::a($browseProjectLink, $project->name);
        $position[]      = $this->lang->project->edit;

        /* 赋值。*/
        $this->view->header   = $header;
        $this->view->position = $position;
        $this->view->projects = $projects;
        $this->view->project  = $project;
        $this->view->groups   = $this->loadModel('group')->getPairs();

        $this->display();
    }

    /* 项目基本信息。*/
    public function view($projectID)
    {
        /* 公共的操作。*/
        $project = $this->project->getById($projectID);
        if(!$project) die(js::error($this->lang->notFound) . js::locate('back'));

        /* 设置菜单。*/
        $this->project->setMenu($this->projects, $project->id);

        $this->view->header->title = $this->lang->project->view;
        $this->view->position[]    = $this->view->header->title;

        $this->view->project  = $project;
        $this->view->products = $this->project->getProducts($project->id);
        $this->view->groups   = $this->loadModel('group')->getPairs();
        $this->view->actions  = $this->loadModel('action')->getList('project', $projectID);
        $this->view->users    = $this->loadModel('user')->getPairs();

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
            $this->project->delete(TABLE_PROJECT, $projectID);
            $this->session->set('project', '');     // 清除session。
            die(js::locate(inlink('index'), 'parent'));
        }
    }

    /* 维护相关的产品。*/
    public function manageProducts($projectID)
    {
        $browseProjectLink = $this->createLink('project', 'browse', "projectID=$projectID");
        if(!empty($_POST))
        {
            $this->project->updateProducts($projectID);
            if(dao::isError()) dis(js::error(dao::getError()));
            die(js::locate($browseProjectLink));
        }

        $this->loadModel('product');
        $project  = $this->project->getById($projectID);

        /* 设置菜单。*/
        $this->project->setMenu($this->projects, $project->id);

        /* 标题和位置信息。*/
        $header['title'] = $this->lang->project->manageProducts . $this->lang->colon . $project->name;
        $position[]      = html::a($browseProjectLink, $project->name);
        $position[]      = $this->lang->project->manageProducts;

        $allProducts    = $this->product->getPairs();
        $linkedProducts = $this->project->getProducts($project->id);
        $linkedProducts = join(',', array_keys($linkedProducts));

        /* 赋值。*/
        $this->view->header         = $header;
        $this->view->position       = $position;
        $this->view->allProducts    = $allProducts;
        $this->view->linkedProducts = $linkedProducts;

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

        /* 设置菜单。*/
        $this->project->setMenu($this->projects, $project->id);

        /* 赋值。*/
        $this->view->header        = $header;
        $this->view->position      = $position;
        $this->view->projects      = $projects;
        $this->view->childProjects = $childProjects;

        $this->display();
    }
    
    /* 维护团队成员。*/
    public function manageMembers($projectID = 0)
    {
        if(!empty($_POST))
        {
            $this->project->manageMembers($projectID);
            $this->locate($this->createLink('project', 'team', "projectID=$projectID"));
            exit;
        }
        $this->loadModel('user');

        $project = $this->project->getById($projectID);
        $users   = $this->user->getPairs('noclosed, nodeleted');
        $users   = array('' => '') + $users;
        $members = $this->project->getTeamMembers($projectID);

        /* 设置已删除的团队成员。*/
        foreach($members as $member)
        {
            if(!@$users[$member->account]) $member->account .= $this->lang->user->deleted;
        }

        /* 设置菜单。*/
        $this->project->setMenu($this->projects, $project->id);

        $header['title'] = $this->lang->project->manageMembers . $this->lang->colon . $project->name;
        $position[]      = html::a($this->createLink('project', 'browse', "projectID=$projectID"), $project->name);
        $position[]      = $this->lang->project->manageMembers;
        $this->view->header   = $header;
        $this->view->position = $position;

        $this->view->project  = $project;
        $this->view->users    = $users;
        $this->view->members  = $members;
        $this->display();
    }

    /* 移除一个成员。*/
    public function unlinkMember($projectID, $account, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->project->confirmUnlinkMember, $this->inlink('unlinkMember', "projectID=$projectID&account=$account&confirm=yes")));
        }
        else
        {
            $this->project->unlinkMember($projectID, $account);
            die(js::locate($this->inlink('team', "projectID=$projectID"), 'parent'));
        }
    }

    /* 关联需求。*/
    public function linkStory($projectID = 0)
    {
        /* 获得项目和相关产品信息。如果没有相关产品，则跳转到产品关联页面。*/
        $project    = $this->project->getById($projectID);
        $products   = $this->project->getProducts($projectID);
        $browseLink = $this->createLink('project', 'story', "projectID=$projectID");

        $this->session->set('storyList', $this->app->getURI(true)); // 记录需求列表状态。
        $this->project->setMenu($this->projects, $project->id);     // 设置菜单。

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

        $allStories = $this->story->getProductStories(array_keys($products), $moduleID = '0', $status = 'active');
        $prjStories = $this->story->getProjectStoryPairs($projectID);

        $this->view->header     = $header;
        $this->view->position   = $position;
        $this->view->project    = $project;
        $this->view->products   = $products;
        $this->view->allStories = $allStories;
        $this->view->prjStories = $prjStories;
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
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
            echo js::locate($this->app->session->storyList, 'parent');
            exit;
        }
    }

    /* 获得某一项目所对应的产品。*/
    public function ajaxGetProducts($projectID)
    {
        $products = $this->project->getProducts($projectID);
        die(html::select('product', $products, '', 'class="select-3"'));
    }
}
