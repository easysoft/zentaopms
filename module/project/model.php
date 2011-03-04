<?php
/**
 * The model file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
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
    const LINK_MEMBERS_ONE_TIME = 10;

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
        $moduleName = $this->app->getModuleName();
        $methodName = $this->app->getMethodName();
        $selectHtml = html::select('projectID', $projects, $projectID, "onchange=\"switchProject(this.value, '$moduleName', '$methodName');\"");
        foreach($this->lang->project->menu as $key => $menu)
        {
            $replace = $key == 'list' ? $selectHtml . $this->lang->arrow : $projectID;
            common::setMenuVars($this->lang->project->menu, $key,  $replace);
        }
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
            ->check('name', 'unique')
            ->check('code', 'unique')
            ->exec();

        /* Add the creater to the team. */
        if(!dao::isError())
        {
            $projectID = $this->dao->lastInsertId();
            $member->project  = $projectID;
            $member->account  = $this->app->user->account;
            $member->joinDate = helper::today();
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
                    $member->project     = (int)$projectID;
                    $member->account     = $value;
                    $member->joinDate    = helper::today();
                    $member->role        = $fieldName;
                    $member->workingHour = '';
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
        if($mode == '') $mode = $this->cookie->projectMode ? $this->cookie->projectMode : 'noclosed';
        $projects = $this->dao->select('*')->from(TABLE_PROJECT)
            ->where('iscat')->eq(0)
            ->andWhere('deleted')->eq(0)
            ->beginIF($mode == 'noclosed')->andWhere('status')->ne('done')->fi()
            ->orderBy('status, end desc')->fetchAll();
        $pairs = array();
        foreach($projects as $project)
        {
            if($this->checkPriv($project)) $pairs[$project->id] = $project->name;
        }
        return $pairs;
    }

    /**
     * Get project lists.
     * 
     * @param  string $status 
     * @access public
     * @return array
     */
    public function getList($status = 'all')
    {
        return $this->dao->select('*')->from(TABLE_PROJECT)->where('iscat')->eq(0)
            ->beginIF($status != 'all')->andWhere('status')->in($status)->fi()
            ->andWhere('deleted')->eq(0)
            ->orderBy('status, end DESC')
            ->fetchAll();
    }

    /**
     * Get projects lists grouped by product.
     * 
     * @access public
     * @return array
     */
    public function getProductGroupList()
    {
        return $this->dao->select('t1.id, t1.name, t2.product')->from(TABLE_PROJECT)->alias('t1')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')->on('t1.id = t2.project')
            ->where('t1.deleted')->eq(0)
            ->orderBy('t1.id')
            ->fetchGroup('product');
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
            ->andWhere('status')->notin('cancel,closed')
            ->andWhere('deleted')->eq(0)
            ->fetch();
        $project->totalEstimate = round($total->totalEstimate, 1);
        $project->totalConsumed = round($total->totalConsumed, 1);
        $project->totalLeft     = round($total->totalLeft, 1);
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
        $tasks = $this->dao->select('id, project, assignedTo, story, consumed')->from(TABLE_TASK)->where('id')->in($this->post->tasks)->fetchAll('id');

        /* Update tasks. */
        foreach($tasks as $task)
        {
            /* Save the assignedToes and stories, should linked to project. */
            $assignedToes[$task->assignedTo]  = $task->project;
            $stories[$task->story] = $task->story;

            $status = $task->consumed > 0 ? 'doing' : 'wait';
            $this->dao->update(TABLE_TASK)->set('project')->eq($projectID)->set('status')->eq($status)->where('id')->in($this->post->tasks)->exec();
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
                $role->project  = $projectID;
                $role->joinDate = helper::today();
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
        return $this->dao->select('t1.*, t2.realname')->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account = t2.account')
            ->where('t1.project')->eq((int)$projectID)
            ->andWHere('t2.company')->eq($this->app->company->id)
            ->fetchAll();
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
            $role        = $roles[$key];
            $workingHour = $workingHours[$key];
            $mode        = $modes[$key];

            if($mode == 'update')
            {
                $this->dao->update(TABLE_TEAM)
                    ->set('role')->eq($role)
                    ->set('workingHour')->eq($workingHour)
                    ->where('project')->eq((int)$projectID)
                    ->andWhere('account')->eq($account)
                    ->exec();
            }
            else
            {
                $member->project     = (int)$projectID;
                $member->account     = $account;
                $member->joinDate    = helper::today();
                $member->role        = $role;
                $member->workingHour = $workingHour;
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
}
