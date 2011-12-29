<?php
/**
 * The model file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php
class projectModel extends model
{
    /* The members every linking. */
    const LINK_MEMBERS_ONE_TIME = 20;

    /**
     * Check the privilege. 
     * 
     * @param  object    $project 
     * @access public
     * @return bool
     */
    public function checkPriv($project)
    {
        /* If is admin, return true. */
        $account = ',' . $this->app->user->account . ',';
        if(strpos($this->app->company->admins, $account) !== false) return true; 

        /* If project is open, return true. */
        if($project->acl == 'open') return true;

        /* Get team members. */
        $teamMembers = $this->getTeamMemberPairs($project->id);

        /* If project is private, only members can access. */
        if($project->acl == 'private')
        {
            return isset($teamMembers[$this->app->user->account]);
        }

        /* Project's acl is custom, check the groups. */
        if($project->acl == 'custom')
        {
            if(isset($teamMembers[$this->app->user->account])) return true;
            $userGroups    = $this->loadModel('user')->getGroups($this->app->user->account);
            $projectGroups = explode(',', $project->whitelist);
            foreach($userGroups as $groupID)
            {
                if(in_array($groupID, $projectGroups)) return true;
            }
            return false;
        }
    }

    /**
     * Set menu.
     * 
     * @param  array  $projects 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function setMenu($projects, $projectID)
    {
        /* Check the privilege. */
        if($projects and !isset($projects[$projectID]) and !$this->checkPriv($this->getById($projectID)))
        {
            echo(js::alert($this->lang->project->accessDenied));
            die(js::locate('back'));
        }

        $moduleName = $this->app->getModuleName();
        $methodName = $this->app->getMethodName();
        $selectHtml = $this->select($projects, $projectID, $moduleName, $methodName);
        foreach($this->lang->project->menu as $key => $menu)
        {
            $replace = $key == 'list' ? $selectHtml . $this->lang->arrow : $projectID;
            common::setMenuVars($this->lang->project->menu, $key,  $replace);
        }
    }

    /**
     * Create the select code of projects. 
     * 
     * @param  array     $projects 
     * @param  int       $projectID 
     * @param  string    $currentModule 
     * @param  string    $currentMethod 
     * @access public
     * @return string
     */
    public function select($projects, $projectID, $currentModule, $currentMethod)
    {
        /* See product's model method:select. */
        $switchCode  = "switchProject($('#projectID').val(), '$currentModule', '$currentMethod');";
        $onchange    = "onchange=\"$switchCode\""; 
        $onkeypress  = "onkeypress=\"eventKeyCode=event.keyCode; if(eventKeyCode == 13) $switchCode\""; 
        $onclick     = "onclick=\"eventKeyCode = 13; $switchCode\""; 
        $selectHtml  = html::select('projectID', $projects, $projectID, "tabindex=2 $onchange $onkeypress");
        $selectHtml .= html::commonButton($this->lang->go, "id='projectSwitcher' tabindex=3 $onclick");
        return $selectHtml;
    }

    /**
     * Save the project id user last visited to session.
     * 
     * @param  int   $projectID 
     * @param  array $projects 
     * @access public
     * @return int
     */
    public function saveState($projectID, $projects)
    {
        if($projectID > 0) $this->session->set('project', (int)$projectID);
        if($projectID == 0 and $this->cookie->lastProject)    $this->session->set('project', (int)$this->cookie->lastProject);
        if($projectID == 0 and $this->session->project == '') $this->session->set('project', $projects[0]);
        if(!in_array($this->session->project, $projects)) $this->session->set('project', $projects[0]);
        return $this->session->project;
    }

    /**
     * Create a project. 
     * 
     * @access public
     * @return void
     */
    public function create()
    {
        $this->lang->project->team = $this->lang->project->teamname;
        $project = fixer::input('post')
            ->stripTags('name, code, team')
            ->setIF($this->post->acl != 'custom', 'whitelist', '')
            ->join('whitelist', ',')
            ->remove('products')
            ->get();
        $this->dao->insert(TABLE_PROJECT)->data($project)
            ->autoCheck($skipFields = 'begin,end')
            ->batchcheck($this->config->project->create->requiredFields, 'notempty')
            ->checkIF($project->begin != '', 'begin', 'date')
            ->checkIF($project->end != '', 'end', 'date')
            ->checkIF($project->end != '', 'end', 'gt', $project->begin)
            ->check('name', 'unique')
            ->check('code', 'unique')
            ->exec();

        /* Add the creater to the team. */
        if(!dao::isError())
        {
            $projectID = $this->dao->lastInsertId();
            $member->project  = $projectID;
            $member->account  = $this->app->user->account;
            $member->join     = helper::today();
            $member->days     = $project->days;
            $member->hours    = $this->config->project->defaultWorkhours;
            $this->dao->insert(TABLE_TEAM)->data($member)->exec();
            return $projectID;
        } 
    }

    /**
     * Update a project.
     * 
     * @param  int    $projectID 
     * @access public
     * @return array
     */
    public function update($projectID)
    {
        $oldProject = $this->getById($projectID);
        $team = $this->getTeamMemberPairs($projectID);
        $this->lang->project->team = $this->lang->project->teamname;
        $projectID = (int)$projectID;
        $project = fixer::input('post')
            ->stripTags('name, code, team')
            ->setIF($this->post->begin == '0000-00-00', 'begin', '')
            ->setIF($this->post->end   == '0000-00-00', 'end', '')
            ->setIF($this->post->acl != 'custom', 'whitelist', '')
            ->join('whitelist', ',')
            ->remove('products')
            ->get();
        $this->dao->update(TABLE_PROJECT)->data($project)
            ->autoCheck($skipFields = 'begin,end')
            ->batchcheck($this->config->project->edit->requiredFields, 'notempty')
            ->checkIF($project->begin != '', 'begin', 'date')
            ->checkIF($project->end != '', 'end', 'date')
            ->checkIF($project->end != '', 'end', 'gt', $project->begin)
            ->check('name', 'unique', "id!=$projectID")
            ->check('code', 'unique', "id!=$projectID")
            ->where('id')->eq($projectID)
            ->limit(1)
            ->exec();
        foreach($project as $fieldName => $value)
        {
            if($fieldName == 'PO' or $fieldName == 'PM' or $fieldName == 'QM' or $fieldName == 'RM' )
            {
                if(!empty($value) and !isset($team[$value]))
                {
                    $member->project = (int)$projectID;
                    $member->account = $value;
                    $member->join    = helper::today();
                    $member->role    = $fieldName;
                    $member->days    = $project->days;
                    $member->hours   = $this->config->project->defaultWorkhours;
                    $this->dao->insert(TABLE_TEAM)->data($member)->exec();
                }
            }
        }
        if(!dao::isError()) return common::createChanges($oldProject, $project);
    }

    /**
     * Get project pairs.
     * 
     * @param  string $mode     all|noclosed or empty 
     * @access public
     * @return array
     */
    public function getPairs($mode = '')
    {
        $orderBy  = !empty($this->config->project->orderBy) ? $this->config->project->orderBy : 'status, id desc';
        $mode    .= $this->cookie->projectMode;
        $projects = $this->dao->select('*')->from(TABLE_PROJECT)
            ->where('iscat')->eq(0)
            ->andWhere('deleted')->eq(0)
            ->orderBy($orderBy)
            ->fetchAll();
        $pairs = array();
        foreach($projects as $project)
        {
            if(strpos($mode, 'noclosed') !== false and $project->status == 'done') continue;
            if($this->checkPriv($project))
            {
                if(strpos($mode, 'nocode') === false and $project->code)
                {
                    $firstChar = strtoupper(substr($project->code, 0, 1));
                    if(ord($firstChar) < 127) $project->name =  $firstChar . ':' . $project->name;
                }
                $pairs[$project->id] = $project->name;
            }
        }

        /* If the pairs is empty, to make sure there's an project in the pairs. */
        if(empty($pairs) and isset($projects[0]) and $this->checkPriv($projects[0]))
        {
            $firstProject = $projects[0];
            $pairs[$firstProject->id] = $firstProject->name;
        }
        return $pairs;
    }

    /**
     * Get project lists.
     * 
     * @param  string $status  all|undone|wait|running
     * @param  int    $limit 
     * @access public
     * @return array
     */
    public function getList($status = 'all', $limit = 0, $productID = 0)
    {
        if($productID != 0)
        {
            return $this->dao->select('t2.*')
                ->from(TABLE_PROJECTPRODUCT)->alias('t1')
                ->leftJoin(TABLE_PROJECT)->alias('t2')
                ->on('t1.project = t2.id')
                ->where('t1.product')->eq($productID)
                ->andWhere('t2.deleted')->eq(0)
                ->andWhere('t2.iscat')->eq(0)
                ->beginIF($status == 'undone')->andWhere('t2.status')->ne('done')->fi()
                ->beginIF($status != 'all' and $status != 'undone')->andWhere('status')->in($status)->fi()
                ->orderBy('status, id desc')
                ->beginIF($limit)->limit($limit)->fi()
                ->fetchAll('id');
        }
        else
        {
            return $this->dao->select('*')->from(TABLE_PROJECT)->where('iscat')->eq(0)
                ->beginIF($status == 'undone')->andWhere('status')->ne('done')->fi()
                ->beginIF($status != 'all' and $status != 'undone')->andWhere('status')->in($status)->fi()
                ->andWhere('deleted')->eq(0)
                ->orderBy('status, id')
                ->beginIF($limit)->limit($limit)->fi()
                ->fetchAll('id');
        }
    }

    /**
     * Get projects lists grouped by product.
     * 
     * @access public
     * @return array
     */
    public function getProductGroupList()
    {
        $list = $this->dao->select('t1.id, t1.name, t2.product')->from(TABLE_PROJECT)->alias('t1')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')->on('t1.id = t2.project')
            ->where('t1.deleted')->eq(0)
            ->orderBy('t1.id')
            ->fetchGroup('product');

        foreach($list as $id => $product)
        {
            foreach($product as $ID => $project)
            {
                if(!$this->checkPriv($this->getById($project->id))) 
                {
                    unset($list[$id][$ID]);
                }
            }
        }

        return $list;
    }

    /**
     * Get project stats.
     * 
     * @param  int    $counts 
     * @param  string $status 
     * @access public
     * @return array
     */
    public function getProjectStats($counts, $status = 'undone', $productID = 0)
    {
        $this->loadModel('report');

        $projects = $this->getList($status, 0, $productID);
        $stats    = array();
        $i = 1;

        /* Get total estimate, consumed and left hours of project. */
        $emptyHour = (object)array('totalEstimate' => 0, 'totalConsumed' => 0, 'totalLeft' => 0, 'progress' => 0);
        $hours = $this->dao->select('project, SUM(estimate) AS totalEstimate, SUM(consumed) AS totalConsumed')
            ->from(TABLE_TASK)
            ->where('project')->in(array_keys($projects))
            ->andWhere('deleted')->eq(0)
            ->groupBy('project')
            ->fetchAll('project');

        $lefts = $this->dao->select('project, SUM(`left`) AS totalLeft')
            ->from(TABLE_TASK)
            ->where('project')->in(array_keys($projects))
            ->andWhere('closedReason')->ne('cancel')
            ->andWhere('status')->ne('cancel')
            ->andWhere('deleted')->eq(0)
            ->groupBy('project')
            ->fetchAll('project');
        foreach($lefts as $projectID => $projectLefts) $hours[$projectID]->totalLeft = $projectLefts->totalLeft;

        /* Round them. */
        foreach($hours as $hour)
        {
            $hour->totalEstimate = round($hour->totalEstimate, 1);
            $hour->totalConsumed = round($hour->totalConsumed, 1);
            $hour->totalLeft     = round($hour->totalLeft, 1);
            $hour->totalReal     = $hour->totalConsumed + $hour->totalLeft;
            $hour->progress      = $hour->totalReal ? round($hour->totalConsumed / $hour->totalReal, 3) * 100 : 0;
        }

        /* Get tasks stats group by status. */
        $tasks = $this->dao->select('project, status, count(status) AS count')
            ->from(TABLE_TASK)
            ->where('project')->in(array_keys($projects))
            ->andWhere('deleted')->eq(0)
            ->groupBy('project, status')
            ->fetchGroup('project', 'status');

        /* Process projects. */
        foreach($projects as $key => $project)
        {
            if($this->checkPriv($project))
            {
                if($i <= $counts)
                {
                    // Process the end time.
                    $project->end = date(DT_DATE4, strtotime($project->end));

                    /* Process the burns. */
                    $project->burns = array();
                    $burnData       = $this->getBurnData($project->id);
                    foreach($burnData as $data) $project->burns[] = $data->value;
                    $stats[] = $project;

                    /* Process the hours. */
                    $project->hours = isset($hours[$project->id]) ? $hours[$project->id] : $emptyHour;

                    /* Process the tasks. */
                    $project->tasks = isset($tasks[$project->id]) ? $tasks[$project->id] : array();
                }
            }
            else
            {
                unset($projects[$key]);
            }

            $i ++;
        }

        return $stats;
    }

    /**
     * Get project by id.
     * 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function getById($projectID)
    {
        $project = $this->dao->findById((int)$projectID)->from(TABLE_PROJECT)->fetch();
        if(!$project) return false;
        $total   = $this->dao->select('
            SUM(estimate) AS totalEstimate, 
            SUM(consumed) AS totalConsumed, 
            SUM(`left`) AS totalLeft')
            ->from(TABLE_TASK)
            ->where('project')->eq((int)$projectID)
            ->andWhere('status')->ne('cancel')
            ->andWhere('deleted')->eq(0)
            ->fetch();
        $project->days          = $project->days ? $project->days : '';
        $project->totalHours    = $this->dao->select('sum(days * hours) AS totalHours')->from(TABLE_TEAM)->where('project')->eq($project->id)->fetch('totalHours');
        $project->totalEstimate = round($total->totalEstimate, 1);
        $project->totalConsumed = round($total->totalConsumed, 1);
        $project->totalLeft     = round($total->totalLeft, 1);
        $project->desc = $this->loadModel('file')->setImgSize($project->desc);
        $project->goal = $this->loadModel('file')->setImgSize($project->goal);
        return $project;
    }

    /**
     * Get the default managers for a project from it's related products. 
     * 
     * @param  int    $projectID 
     * @access public
     * @return object
     */
    public function getDefaultManagers($projectID)
    {
        $managers = $this->dao->select('PO,QM,RM')->from(TABLE_PRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')->on('t1.id = t2.product')
            ->where('t2.project')->eq($projectID)
            ->fetch();
        if($managers) return $managers;

        $managers->PO = '';
        $managers->QM = '';
        $managers->RM = '';
        return $managers;
    }

    /**
     * Get products of a project.
     * 
     * @param  int    $projectID 
     * @access public
     * @return array
     */
    public function getProducts($projectID)
    {
        return $this->dao->select('t2.id, t2.name')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')
            ->on('t1.product = t2.id')
            ->where('t1.project')->eq((int)$projectID)
            ->fetchPairs();
    }

    /**
     * Update products of a project.
     * 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function updateProducts($projectID)
    {
        $this->dao->delete()->from(TABLE_PROJECTPRODUCT)->where('project')->eq((int)$projectID)->exec();
        if(!isset($_POST['products'])) return;
        $products = array_unique($_POST['products']);
        foreach($products as $productID)
        {
            $data->project = $projectID;
            $data->product = $productID;
            $this->dao->insert(TABLE_PROJECTPRODUCT)->data($data)->exec();
        }
    }

    /**
     * Get related projects 
     * 
     * @param  int    $projectID 
     * @access public
     * @return array
     */
    public function getRelatedProjects($projectID)
    {
        $products = $this->dao->select('product')->from(TABLE_PROJECTPRODUCT)->where('project')->eq((int)$projectID)->fetchAll('product');
        if(!$products) return array();
        $products = array_keys($products);
        return $this->dao->select('t1.id, t1.name')->from(TABLE_PROJECT)->alias('t1')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')
            ->on('t1.id = t2.project')
            ->where('t2.product')->in($products)
            ->andWhere('t1.id')->ne((int)$projectID)
            ->andWhere('t1.deleted')->eq(0)
            ->orderBy('t1.id')
            ->fetchPairs();
    }

    /**
     * Get rasks can be imported.
     * 
     * @param  int    $projectID 
     * @access public
     * @return array
     */
    public function getTasks2Imported($projectID)
    {
        $this->loadModel('task');
        $releatedProjects = $this->getRelatedProjects($projectID);
        if(!$releatedProjects) return array();
        $tasks = array();
        foreach($releatedProjects as $releatedProjectID => $releatedProjectName)
        {
            $projectTasks = $this->task->getProjectTasks($releatedProjectID, 'wait,doing,cancel');
            if(!$projectTasks) continue;
            $tasks = array_merge($tasks, $projectTasks); 
        }
        return $tasks;
    }

    /**
     * Import tasks.
     * 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function importTask($projectID)
    {
        $this->loadModel('task');

        /* Update tasks. */
        $tasks = $this->dao->select('id, project, assignedTo, story, consumed,status')->from(TABLE_TASK)->where('id')->in($this->post->tasks)->fetchAll('id');
        foreach($tasks as $task)
        {
            /* Save the assignedToes and stories, should linked to project. */
            $assignedToes[$task->assignedTo]  = $task->project;
            $stories[$task->story] = $task->story;

            $data = new stdclass();
            $data->project = $projectID;

            if($task->status == 'cancel')
            {
                $data->canceledBy = '';
                $data->canceledDate = NULL;
            }

            $data->status       = $task->consumed > 0 ? 'doing' : 'wait';
            $data->statusCustom = strpos(TASKMODEL::CUSTOM_STATUS_ORDER, $data->status) + 1;
            $this->dao->update(TABLE_TASK)->data($data)->where('id')->in($this->post->tasks)->exec();
            $this->loadModel('action')->create('task', $task->id, 'moved', '', $task->project);
        }

        /* Remove empty story. */
        unset($stories[0]);

        /* Add members to project team. */
        $teamMembers = $this->getTeamMemberPairs($projectID);
        foreach($assignedToes as $account => $preProjectID)
        {
            if(!isset($teamMembers[$account]))
            {
                $role = $this->dao->select('*')->from(TABLE_TEAM)->where('project')->eq($preProjectID)->andWhere('account')->eq($account)->fetch();
                $role->project = $projectID;
                $role->join    = helper::today();
                $this->dao->insert(TABLE_TEAM)->data($role)->exec();
            }
        }

        /* Link stories. */
        $projectStories = $this->loadModel('story')->getProjectStoryPairs($projectID);
        foreach($stories as $storyID)
        {
            if(!isset($projectStories[$storyID]))
            {
                $story = $this->dao->findById($storyID)->fields("$projectID as project, id as story, product, version")->from(TABLE_STORY)->fetch();
                $this->dao->insert(TABLE_PROJECTSTORY)->data($story)->exec();
            }
        }
    }

    /**
     * Import task from Bug. 
     * 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function importBug($projectID)
    {
        $this->loadModel('bug');
        $bugLang = $this->app->loadLang('bug');
        $this->loadModel('task');
        $this->loadModel('story');

        $now = helper::now();
        $BugToTasks = fixer::input('post')->get();
        foreach($BugToTasks->import as $key => $value)
        {
            $bug = $this->bug->getById($key);
            $task->project      = $projectID;
            $task->story        = $bug->story;
            $task->storyVersion = $bug->story;
            $task->fromBug      = $key;
            $task->name         = $bug->title;
            $task->type         = 'devel';
            $task->pri          = $BugToTasks->pri[$key];
            $task->consumed     = 0;
            $task->status       = 'wait';
            $task->statusCustom = strpos(taskModel::CUSTOM_STATUS_ORDER, 'wait') + 1;
            $task->desc         = $bugLang->bug->resolve . ':' . '#' . html::a(helper::createLink('bug', 'view', "bugID=$key"), sprintf('%03d', $key));
            $task->openedDate   = $now;
            $task->openedBy     = $this->app->user->account;
            if(!empty($BugToTasks->estimate[$key]))
            {
                $task->estimate     = $BugToTasks->estimate[$key];
                $task->left         = $task->estimate;
            }
            if(!empty($BugToTasks->assignedTo[$key]))
            {
                $task->assignedTo   = $BugToTasks->assignedTo[$key];
                $task->assignedDate = $now;
            }
            $this->dao->insert(TABLE_TASK)->data($task)->checkIF($BugToTasks->estimate[$key] != '', 'estimate', 'float')->exec();

            if(dao::isError()) 
            {
                echo js::error(dao::getError());
                die(js::reload('parent'));
            }

            $taskID = $this->dao->lastInsertID();
            if($task->story != false) $this->story->setStage($task->story);
            $actionID = $this->loadModel('action')->create('task', $taskID, 'Opened', '');
            $this->action->create('bug', $key, 'Totask', '', $taskID);
            $this->dao->update(TABLE_BUG)->set('toTask')->eq($taskID)->where('id')->eq($key)->exec();
            $mails[$key]->taskID  = $taskID;
            $mails[$key]->actionID = $actionID;
        }
        return $mails;
    }

    /**
     * Get child projects.
     * 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function getChildProjects($projectID)
    {
        return $this->dao->select('id, name')->from(TABLE_PROJECT)->where('parent')->eq((int)$projectID)->fetchPairs();
    }

    /**
     * Update childs.
     * 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function updateChilds($projectID)
    {
        $sql = "UPDATE " . TABLE_PROJECT . " SET parent = 0 WHERE parent = '$projectID'";
        $this->dbh->exec($sql);
        if(!isset($_POST['childs'])) return;
        $childs = array_unique($_POST['childs']);
        foreach($childs as $childProjectID)
        {
            $sql = "UPDATE " . TABLE_PROJECT . " SET parent = '$projectID' WHERE id = '$childProjectID'";
            $this->dbh->query($sql);
        }
    }

    /**
     * Link story.
     * 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function linkStory($projectID)
    {
        if($this->post->stories == false) return false;
        $this->loadModel('action');
        $versions = $this->loadModel('story')->getVersions($this->post->stories);
        foreach($this->post->stories as $key => $storyID)
        {
            $productID = $this->post->products[$key];
            $data->project = $projectID;
            $data->product = $productID;
            $data->story   = $storyID;
            $data->version = $versions[$storyID];
            $this->dao->insert(TABLE_PROJECTSTORY)->data($data)->exec();
            $this->story->setStage($storyID);
            $this->action->create('story', $storyID, 'linked2project', '', $projectID);
        }        
    }

    /**
     * Unlink story. 
     * 
     * @param  int    $projectID 
     * @param  int    $storyID 
     * @access public
     * @return void
     */
    public function unlinkStory($projectID, $storyID)
    {
        $this->dao->delete()->from(TABLE_PROJECTSTORY)->where('project')->eq($projectID)->andWhere('story')->eq($storyID)->limit(1)->exec();
        $this->loadModel('story')->setStage($storyID);
        $this->loadModel('action')->create('story', $storyID, 'unlinkedfromproject', '', $projectID);
        $tasks = $this->dao->select('id')->from(TABLE_TASK)->where('story')->eq($storyID)->andWhere('project')->eq($projectID)->andWhere('status')->in('wait,doing')->fetchPairs('id');
        $this->dao->update(TABLE_TASK)->set('status')->eq('cancel')->where('id')->in($tasks)->exec();
        foreach($tasks as $taskID)
        {
            $changes  = $this->loadModel('task')->cancel($taskID);
            $actionID = $this->action->create('task', $taskID, 'Canceled');
            $this->action->logHistory($actionID, $changes);
        }
    }

    /**
     * Get team members. 
     * 
     * @param  int    $projectID 
     * @access public
     * @return array
     */
    public function getTeamMembers($projectID)
    {
        return $this->dao->select('t1.*, t1.hours * t1.days AS totalHours, t2.realname')->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account = t2.account')
            ->where('t1.project')->eq((int)$projectID)
            ->andWHere('t2.company')->eq($this->app->company->id)
            ->fetchAll('account');
    }

    /**
     * Get team members in pair.
     * 
     * @param  int    $projectID 
     * @param  string $params 
     * @access public
     * @return array
     */
    public function getTeamMemberPairs($projectID, $params = '')
    {
        $users = $this->dao->select('t1.account, t2.realname')->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account = t2.account')
            ->where('t1.project')->eq((int)$projectID)
            ->andWHere('t2.company')->eq($this->app->company->id)
            ->beginIF($params == 'nodeleted')
            ->andWhere('t2.deleted')->eq(0)
            ->fi()
            ->fetchPairs();
        if(!$users) return array();
        foreach($users as $account => $realName)
        {
            $firstLetter = ucfirst(substr($account, 0, 1)) . ':';
            $users[$account] =  $firstLetter . ($realName ? $realName : $account);
        }
        return array('' => '') + $users;
    }

    /**
     * Manage team members.
     * 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function manageMembers($projectID)
    {
        extract($_POST);

        foreach($accounts as $key => $account)
        {
            if(empty($account)) continue;

            $member->role  = $roles[$key];
            $member->days  = $days[$key];
            $member->hours = $hours[$key];
            $mode        = $modes[$key];

            if($mode == 'update')
            {
                $this->dao->update(TABLE_TEAM)->data($member)->where('project')->eq((int)$projectID)->andWhere('account')->eq($account)->exec();
            }
            else
            {
                $member->project = (int)$projectID;
                $member->account = $account;
                $member->join    = helper::today();
                $this->dao->insert(TABLE_TEAM)->data($member)->exec();
            }
        }        
    }

    /**
     * Unlink a member.
     * 
     * @param  int    $projectID 
     * @param  string $account 
     * @access public
     * @return void
     */
    public function unlinkMember($projectID, $account)
    {
        $this->dao->delete()->from(TABLE_TEAM)->where('project')->eq((int)$projectID)->andWhere('account')->eq($account)->exec();
    }

    /**
     * Compute burn of a project.
     * 
     * @access public
     * @return array
     */
    public function computeBurn()
    {
        $today    = helper::today();
        $burns    = array();

        $projects = $this->dao->select('id, name')->from(TABLE_PROJECT)
            ->where("end >= '$today'")
            ->orWhere('end')->eq('0000-00-00')
            ->fetchPairs();
        if(!$projects) return $burns;

        $burns = $this->dao->select("project, '$today' AS date, sum(`left`) AS `left`, SUM(consumed) AS `consumed`")
            ->from(TABLE_TASK)
            ->where('project')->in(array_keys($projects))
            ->andWhere('deleted')->eq('0')
            ->andWhere('status')->notin('cancel,closed')
            ->groupBy('project')
            ->fetchAll();

        foreach($burns as $Key => $burn)
        {
            $this->dao->replace(TABLE_BURN)->data($burn)->exec();
            $burn->projectName = $projects[$burn->project];
        }
        return $burns;
    }

    /**
     * Get data of burn down chart.
     * 
     * @param  int    $projectID 
     * @param  int    $itemCounts 
     * @access public
     * @return array
     */
    public function getBurnData($projectID = 0, $itemCounts = 30)
    {
        /* Get project and burn counts. */
        $project    = $this->getById($projectID);
        $burnCounts = $this->dao->select('count(*) AS counts')->from(TABLE_BURN)->where('project')->eq($projectID)->fetch('counts');

        /* If the burnCounts > $itemCounts, get the latest $itemCounts records. */
        $sql = $this->dao->select('date AS name, `left` AS value')->from(TABLE_BURN)->where('project')->eq((int)$projectID);
        if($burnCounts > $itemCounts)
        {
            $sets = $sql->orderBy('date DESC')->limit($itemCounts)->fetchAll('name');
            $sets = array_reverse($sets);
        }
        else
        {
            /* The burnCounts < itemCounts, after getting from the db, padding left dates. */
            $sets    = $sql->orderBy('date ASC')->fetchAll('name');
            $current = helper::today();
            if($project->end != '0000-00-00')
            {
                $period = helper::diffDate($project->end, $project->begin) + 1;
                $counts = $period > $itemCounts ? $itemCounts : $period;
            }
            else
            {
                $counts = $itemCounts;
            }

            for($i = 0; $i < $counts - $burnCounts; $i ++)
            {
                if(helper::diffDate($current, $project->end) > 0) break;
                if(!isset($sets[$current]))
                {
                    $sets[$current]->name = $current;
                    $sets[$current]->value = '';
                }
                $nextDay = date(DT_DATE1, strtotime('next day', strtotime($current)));
                $current = $nextDay;
            }
        }
        foreach($sets as $set) $set->name = substr($set->name, 5);
        return $sets;
    }

    public function getBurnDataFlot($projectID = 0, $itemCounts = 30)
    {
        /* Get project and burn counts. */
        $project    = $this->getById($projectID);
        $burnCounts = $this->dao->select('count(*) AS counts')->from(TABLE_BURN)->where('project')->eq($projectID)->fetch('counts');

        /* If the burnCounts > $itemCounts, get the latest $itemCounts records. */
        $sql = $this->dao->select('date AS name, `left` AS value')->from(TABLE_BURN)->where('project')->eq((int)$projectID);
        if($burnCounts > $itemCounts)
        {
            $sets = $sql->orderBy('date DESC')->limit($itemCounts)->fetchAll('name');
            $sets = array_reverse($sets);
        }
        else
        {
            /* The burnCounts < itemCounts, after getting from the db, padding left dates. */
            $sets    = $sql->orderBy('date ASC')->fetchAll('name');
            $current = helper::today();
            if($project->end != '0000-00-00')
            {
                $period = helper::diffDate($project->end, $project->begin) + 1;
                $counts = $period > $itemCounts ? $itemCounts : $period;
            }
            else
            {
                $counts = $itemCounts;
            }

            for($i = 0; $i < $counts - $burnCounts; $i ++)
            {
                if(helper::diffDate($current, $project->end) > 0) break;
                if(!isset($sets[$current]))
                {
                    $sets[$current]->name = $current;
                    $sets[$current]->value = '';
                }
                $nextDay = date(DT_DATE1, strtotime('next day', strtotime($current)));
                $current = $nextDay;
            }
        }
        $count = 0;
        foreach($sets as $set) 
        {
            $set->name = (string)strtotime("$set->name UTC") . '000';
            $count ++;
        }
        $sets['count'] = $count;
        return $sets;
    }

    /**
     * Get taskes by search.
     * 
     * @param  string $condition 
     * @param  object $pager 
     * @param  string $orderBy 
     * @access public
     * @return array
     */
    public function getSearchTasks($condition, $pager, $orderBy)
    {
        $taskIdList = $this->dao->select('id')
            ->from(TABLE_TASK)
            ->where($condition)
            ->andWhere('deleted')->eq(0)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        $tasks = $this->dao->select('t1.*, t2.id AS storyID, t2.title AS storyTitle, t2.version AS latestStoryVersion, t2.status AS storyStatus, t3.realname AS assignedToRealName')
             ->from(TABLE_TASK)->alias('t1')
             ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
             ->leftJoin(TABLE_USER)->alias('t3')->on('t1.assignedTo = t3.account')
             ->where('t1.deleted')->eq(0)
             ->andWhere('t1.id')->in(array_keys($taskIdList))
             ->orderBy($orderBy)
             ->fetchAll();
        return $tasks;
    }

    /**
     * Get bugs by search in project. 
     * 
     * @param  int    $products 
     * @param  int    $projectID 
     * @param  int    $sql 
     * @param  int    $pager 
     * @param  int    $orderBy 
     * @access public
     * @return void
     */
    public function getSearchBugs($products, $projectID, $sql, $pager, $orderBy)
    {
        return $this->dao->select('*')->from(TABLE_BUG)
            ->where($sql)
            ->andWhere('status')->eq('active')
            ->andWhere('toTask')->eq(0)
            ->andWhere('tostory')->eq(0)
            ->beginIF(!empty($products))->andWhere('product')->in(array_keys($products))
            ->beginIF(empty($products))->andWhere('project')->eq($projectID)
            ->andWhere('deleted')->eq(0)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
    }

    /**
     * Get resolved bugs of a project
     * 
     * @param  int    $projectID 
     * @access public
     * @return array
     */
    public function getResolvedBugs($projectID)
    {
        $project = $this->findById($projectID);
        return $this->dao->select('id, title, status')->from(TABLE_BUG)
            ->where('status')->eq('resovled')
            ->andWhere('resovledDate')->ge($project->begin)
            ->fetchAll();
    }
}
