<?php
/**
 * The model file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id: model.php 5118 2013-07-12 07:41:41Z chencongzhi520@gmail.com $
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

        /* Get all teams of all projects and group by projects, save it as static. */
        static $teams;
        if(empty($teams)) $teams = $this->dao->select('project, account')->from(TABLE_TEAM)->fetchGroup('project', 'account');
        $currentTeam = isset($teams[$project->id]) ? $teams[$project->id] : array();

        /* If project is private, only members can access. */
        if($project->acl == 'private')
        {
            return isset($currentTeam[$this->app->user->account]);
        }

        /* Project's acl is custom, check the groups. */
        if($project->acl == 'custom')
        {
            if(isset($currentTeam[$this->app->user->account])) return true;
            $userGroups    = $this->app->user->groups;
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
     * @param  string $extra
     * @access public
     * @return void
     */
    public function setMenu($projects, $projectID, $extra = '')
    {
        /* Check the privilege. */
        $project = $this->getById($projectID);

        /* Unset story, bug, build and testtask if type is ops. */
        if($project and $project->type == 'ops') 
        {
            unset($this->lang->project->menu->story);
            unset($this->lang->project->menu->bug);
            unset($this->lang->project->menu->build);
            unset($this->lang->project->menu->testtask);
        }

        if($projects and !isset($projects[$projectID]) and !$this->checkPriv($project))
        {
            echo(js::alert($this->lang->project->accessDenied));
            die(js::locate('back'));
        }

        $moduleName = $this->app->getModuleName();
        $methodName = $this->app->getMethodName();

        if($this->cookie->projectMode == 'noclosed' and $project->status == 'done') 
        {
            setcookie('projectMode', 'all');
            $this->cookie->projectMode = 'all';
        }

        $selectHtml = $this->select($projects, $projectID, $moduleName, $methodName, $extra);
        foreach($this->lang->project->menu as $key => $menu)
        {
            $replace = $key == 'list' ? $selectHtml : $projectID;
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
     * @param  string    $extra
     * @access public
     * @return string
     */
    public function select($projects, $projectID, $currentModule, $currentMethod, $extra = '')
    {
        if(!$projectID) return;

        setCookie("lastProject", $projectID, $this->config->cookieLife, $this->config->webRoot);
        $currentProject = $this->getById($projectID);
        $output = "<a id='currentItem' href=\"javascript:showDropMenu('project', '$projectID', '$currentModule', '$currentMethod', '$extra')\">{$currentProject->name} <span class='icon-caret-down'></span></a><div id='dropMenu'></div>";
        return $output;
    }

    /**
     * Get project tree menu.
     * 
     * @access public
     * @return void
     */
    public function tree()
    {
        $products     = $this->loadModel('product')->getPairs('nocode');
        $productGroup = $this->getProductGroupList();
        $projectTree  = "<ul class='tree'>";
        foreach($productGroup as $productID => $projects)
        {
            if(!isset($products[$productID]) and $productID != '')  continue;
            if(!isset($products[$productID]) and !count($projects)) continue;

            $productName  = isset($products[$productID]) ? $products[$productID] : $this->lang->project->noProduct;

            $projectTree .= "<li>$productName<ul>";
           
            foreach($projects as $project)
            {
                if($project->status != 'done')
                {
                    $projectTree .= "<li>" . html::a(inlink('task', "projectID=$project->id"), $project->name, '', "id='project$project->id'") . "</li>";
                }
            }


            $hasDone = false;
            foreach($projects as $project) 
            {
                if($project->status == 'done') 
                {
                    $hasDone = true;
                    break;
                }
            }
            if($hasDone)
            {
                $projectTree .= "<li>{$this->lang->project->selectGroup->done}<ul>";
                foreach($projects as $project)
                {
                    if($project->status == 'done')
                    {
                        $projectTree .= "<li>" . html::a(inlink('task', "projectID=$project->id"), $project->name, '', "id='project$project->id'") . "</li>";
                    }
                }
                $projectTree .= "</ul></li>";
            }

            $projectTree .= "</ul></li>";
        }

        $projectTree .= "</ul>";

        return $projectTree;
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
    public function create($copyProjectID = '')
    {
        $this->lang->project->team = $this->lang->project->teamname;
        $project = fixer::input('post')
            ->setDefault('status', 'wait')
            ->setIF($this->post->acl != 'custom', 'whitelist', '')
            ->setDefault('openedVersion', $this->config->version)
            ->setDefault('team', $this->post->name)
            ->join('whitelist', ',')
            ->stripTags($this->config->project->editor->create['id'], $this->config->allowedTags)
            ->remove('products, workDays, delta')
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
            $projectID     = $this->dao->lastInsertId();
            $today         = helper::today();
            $creatorExists = false;

            /* Copy team of project. */
            if($copyProjectID != '') 
            {
                $members = $this->dao->select('*')->from(TABLE_TEAM)->where('project')->eq($copyProjectID)->fetchAll();
                foreach($members as $member)
                {
                    $member->project = $projectID;
                    $member->join    = $today;
                    $member->days    = $project->days;
                    $this->dao->insert(TABLE_TEAM)->data($member)->exec();
                    if($member->account == $this->app->user->account) $creatorExists = true;
                }
            }

            /* Add the creator to team. */
            if($copyProjectID == '' or !$creatorExists)
            {
                $member = new stdclass();
                $member->project  = $projectID;
                $member->account  = $this->app->user->account;
                $member->role     = $this->lang->user->roleList[$this->app->user->role];
                $member->join     = $today;
                $member->days     = $project->days;
                $member->hours    = $this->config->project->defaultWorkhours;
                $this->dao->insert(TABLE_TEAM)->data($member)->exec();
            }

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
            ->setIF($this->post->begin == '0000-00-00', 'begin', '')
            ->setIF($this->post->end   == '0000-00-00', 'end', '')
            ->setIF($this->post->acl != 'custom', 'whitelist', '')
            ->setDefault('team', $this->post->name)
            ->join('whitelist', ',')
            ->stripTags($this->config->project->editor->create['id'], $this->config->allowedTags)
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
            if($fieldName == 'PO' or $fieldName == 'PM' or $fieldName == 'QD' or $fieldName == 'RD' )
            {
                if(!empty($value) and !isset($team[$value]))
                {
                    $member->project = (int)$projectID;
                    $member->account = $value;
                    $member->join    = helper::today();
                    $member->role    = $this->lang->project->$fieldName;
                    $member->days    = $project->days;
                    $member->hours   = $this->config->project->defaultWorkhours;
                    $this->dao->insert(TABLE_TEAM)->data($member)->exec();
                }
            }
        }
        if(!dao::isError()) return common::createChanges($oldProject, $project);
    }

    /**
     * Batch update. 
     * 
     * @access public
     * @return void
     */
    public function batchUpdate()
    {
        $projects   = array();
        $allChanges = array();
        foreach($this->post->projectIDList as $projectID)
        {
            $projects[$projectID] = new stdClass();
            $projects[$projectID]->name   = $this->post->names[$projectID];
            $projects[$projectID]->code   = $this->post->codes[$projectID];
            $projects[$projectID]->PM     = $this->post->PMs[$projectID];
            $projects[$projectID]->status = $this->post->statuses[$projectID];
            $projects[$projectID]->begin  = $this->post->begins[$projectID];
            $projects[$projectID]->end    = $this->post->ends[$projectID];
            $projects[$projectID]->days   = $this->post->dayses[$projectID];
        }

        foreach($projects as $projectID => $project)
        {
            $oldProject = $this->getById($projectID);
            $team       = $this->getTeamMemberPairs($projectID);

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

            if($project->PM and !isset($team[$project->PM]))
            {
                $member = new stdClass();
                $member->project = (int)$projectID;
                $member->account = $project->PM;
                $member->join    = helper::today();
                $member->role    = $this->lang->project->PM;
                $member->days    = 0;
                $member->hours   = $this->config->project->defaultWorkhours;
                $this->dao->insert(TABLE_TEAM)->data($member)->exec();
            }

            if(dao::isError()) die(js::error('project#' . $projectID . dao::getError(true)));
            $allChanges[$projectID] = common::createChanges($oldProject, $project);
        }
        return $allChanges;
    }

    /**
     * Start project.
     * 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function start($projectID)
    {
        $oldProject = $this->getById($projectID);
        $now        = helper::now();
        $project = fixer::input('post')
            ->setDefault('status', 'doing')
            ->remove('comment')->get();

        $this->dao->update(TABLE_PROJECT)->data($project)
            ->autoCheck()
            ->where('id')->eq((int)$projectID)
            ->exec();

        if(!dao::isError()) return common::createChanges($oldProject, $project);
    }

    /**
     * Put project off.
     * 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function putoff($projectID)
    {
        $oldProject = $this->getById($projectID);
        $now        = helper::now();
        $project = fixer::input('post')->remove('comment')->get();

        $this->dao->update(TABLE_PROJECT)->data($project)
            ->autoCheck()
            ->where('id')->eq((int)$projectID)
            ->exec();

        if(!dao::isError()) return common::createChanges($oldProject, $project);
    }

    /**
     * Suspend project.
     * 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function suspend($projectID)
    {
        $oldProject = $this->getById($projectID);
        $now        = helper::now();
        $project = fixer::input('post')
            ->setDefault('status', 'suspended')
            ->remove('comment')->get();

        $this->dao->update(TABLE_PROJECT)->data($project)
            ->autoCheck()
            ->where('id')->eq((int)$projectID)
            ->exec();

        if(!dao::isError()) return common::createChanges($oldProject, $project);
    }

    /**
     * Activate project.
     * 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function activate($projectID)
    {
        $oldProject = $this->getById($projectID);
        $now        = helper::now();
        $project = fixer::input('post')
            ->setDefault('status', 'doing')
            ->remove('comment')->get();

        $this->dao->update(TABLE_PROJECT)->data($project)
            ->autoCheck()
            ->where('id')->eq((int)$projectID)
            ->exec();

        if(!dao::isError()) return common::createChanges($oldProject, $project);
    }

    /**
     * Close project.
     * 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function close($projectID)
    {
        $oldProject = $this->getById($projectID);
        $now        = helper::now();
        $project = fixer::input('post')
            ->setDefault('status', 'done')
            ->remove('comment')->get();

        $this->dao->update(TABLE_PROJECT)->data($project)
            ->autoCheck()
            ->where('id')->eq((int)$projectID)
            ->exec();

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
        $orderBy  = !empty($this->config->project->orderBy) ? $this->config->project->orderBy : 'isDone, status';
        $mode    .= $this->cookie->projectMode;
        /* Order by status's content whether or not done */
        $projects = $this->dao->select('*, IF(INSTR(" done", status) < 2, 0, 1) AS isDone')->from(TABLE_PROJECT)
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
     * @param  int    $productID 
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
                ->beginIF($status == 'isdoing')->andWhere('t2.status')->ne('done')->andWhere('t2.status')->ne('suspended')->fi()
                ->beginIF($status != 'all' and $status != 'isdoing' and $status != 'undone')->andWhere('status')->in($status)->fi()
                ->orderBy('code')
                ->beginIF($limit)->limit($limit)->fi()
                ->fetchAll('id');
        }
        else
        {
            return $this->dao->select('*, IF(INSTR(" done", status) < 2, 0, 1) AS isDone')->from(TABLE_PROJECT)->where('iscat')->eq(0)
                ->beginIF($status == 'undone')->andWhere('status')->ne('done')->fi()
                ->beginIF($status == 'isdoing')->andWhere('status')->ne('done')->andWhere('status')->ne('suspended')->fi()
                ->beginIF($status != 'all' and $status != 'isdoing' and $status != 'undone')->andWhere('status')->in($status)->fi()
                ->andWhere('deleted')->eq(0)
                ->orderBy('code')
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
        $list = $this->dao->select('t1.id, t1.name,t1.status, t2.product')->from(TABLE_PROJECT)->alias('t1')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')->on('t1.id = t2.project')
            ->where('t1.deleted')->eq(0)
            ->fetchGroup('product');

        $noProducts = array();
        $projects   = $this->getList();

        foreach($list as $id => $product)
        {
            foreach($product as $ID => $project)
            {
                if(!$this->checkPriv($projects[$project->id])) 
                {
                    unset($list[$id][$ID]);
                }
                if(!$project->product)
                {
                    if($this->checkPriv($projects[$project->id])) $noProducts[] = $project;
                    unset($list[$id][$ID]); 
                }
            }
        }
        unset($list['']);
        $list[''] = $noProducts;

        return $list;
    }

    /**
     * Get project stats.
     * 
     * @param  string $status 
     * @param  int    $productID 
     * @param  int    $itemCounts 
     * @param  string $orderBy 
     * @param  int    $pager 
     * @access public
     * @return void
     */
    public function getProjectStats($status = 'undone', $productID = 0, $itemCounts = 30, $orderBy = 'code', $pager = null)
    {
        /* Init vars. */
        $projects    = $this->getList($status, 0, $productID);
        foreach($projects as $projectID => $project)
        {
            if(!$this->checkPriv($project)) unset($projects[$projectID]);
        }
        $projects = $this->dao->select('*')->from(TABLE_PROJECT)
            ->where('id')->in(array_keys($projects))
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        $projectKeys = array_keys($projects);
        $stats       = array();
        $hours       = array();
        $emptyHour   = array('totalEstimate' => 0, 'totalConsumed' => 0, 'totalLeft' => 0, 'progress' => 0);

        /* Get all tasks and compute totalEstimate, totalConsumed, totalLeft, progress according to them. */
        $tasks = $this->dao->select('id, project, estimate, consumed, `left`, status, closedReason')
            ->from(TABLE_TASK)
            ->where('project')->in($projectKeys)
            ->andWhere('deleted')->eq(0)
            ->fetchGroup('project', 'id');

        /* Compute totalEstimate, totalConsumed, totalLeft. */
        foreach($tasks as $projectID => $projectTasks)
        {
            $hour = (object)$emptyHour;
            foreach($projectTasks as $task)
            {
                $hour->totalEstimate += $task->estimate;
                $hour->totalConsumed += $task->consumed;
                $hour->totalLeft     += ($task->status != 'cancel' and $task->closedReason != 'cancel') ? $task->left : 0;
            }
            $hours[$projectID] = $hour;
        }

        /* Compute totalReal and progress. */
        foreach($hours as $hour)
        {
            $hour->totalEstimate = round($hour->totalEstimate, 1) ;
            $hour->totalConsumed = round($hour->totalConsumed, 1);
            $hour->totalLeft     = round($hour->totalLeft, 1);
            $hour->totalReal     = $hour->totalConsumed + $hour->totalLeft;
            $hour->progress      = $hour->totalReal ? round($hour->totalConsumed / $hour->totalReal, 3) * 100 : 0;
        }

        /* Get burndown charts datas. */
        $burns = $this->dao->select('project, date AS name, `left` AS value')
            ->from(TABLE_BURN)
            ->where('project')->in($projectKeys)
            ->orderBy('date desc')
            ->fetchGroup('project', 'name');

        foreach($burns as $projectID => $projectBurns)
        {
            /* If projectBurns > $itemCounts, split it, else call processBurnData() to pad burns. */
            $begin = $projects[$projectID]->begin;
            $end   = $projects[$projectID]->end;
            if(count($projectBurns) >= $itemCounts) $projectBurns = array_slice($projectBurns, 0, $itemCounts);
            if(count($projectBurns) < $itemCounts)  $projectBurns = $this->processBurnData($projectBurns, $itemCounts, $begin, $end);

            /* Shorter names.  */
            foreach($projectBurns as $projectBurn)
            {
                $projectBurn->name = substr($projectBurn->name, 5);
                unset($projectBurn->project);
            }

            ksort($projectBurns);
            $burns[$projectID] = $projectBurns;
        }

        /* Process projects. */
        foreach($projects as $key => $project)
        {
            // Process the end time.
            $project->end = date("Y-m-d", strtotime($project->end));

            /* Process the burns. */
            $project->burns = array();
            $burnData = isset($burns[$project->id]) ? $burns[$project->id] : array();
            foreach($burnData as $data) $project->burns[] = $data->value;

            /* Process the hours. */
            $project->hours = isset($hours[$project->id]) ? $hours[$project->id] : (object)$emptyHour;

            $stats[] = $project;
        }

        return $stats;
    }

    /**
     * Get project by id.
     * 
     * @param  int    $projectID 
     * @param  bool   $setImgSize
     * @access public
     * @return void
     */
    public function getById($projectID, $setImgSize = false)
    {
        $project = $this->dao->findById((int)$projectID)->from(TABLE_PROJECT)->fetch();
        if(!$project) return false;

        $total = $this->dao->select('
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

        if($setImgSize) $project->desc = $this->loadModel('file')->setImgSize($project->desc);

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
        $managers = $this->dao->select('PO,QD,RD')->from(TABLE_PRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')->on('t1.id = t2.product')
            ->where('t2.project')->eq($projectID)
            ->fetch();
        if($managers) return $managers;

        $managers = new stdclass();
        $managers->PO = '';
        $managers->QD = '';
        $managers->RD = '';
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
     * Get projects to import 
     * 
     * @access public
     * @return void
     */
    public function getProjectsToImport()
    {
        $projects = $this->dao->select('distinct t1.*')->from(TABLE_PROJECT)->alias('t1')
            ->leftJoin(TABLE_TASK)->alias('t2')->on('t1.id=t2.project')
            ->where('t2.status')->notIN('done,closed')
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t1.deleted')->eq(0)
            ->orderBy('id desc')
            ->fetchAll('id');

        $pairs = array();
        $now   = date('Y-m-d');
        foreach($projects as $id => $project)
        {
            if($this->checkPriv($project) and ($project->status == 'done' or $project->end < $now)) $pairs[$id] = ucfirst(substr($project->code, 0, 1)) . ':' . $project->name;
        }
        return $pairs;
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
            $data = new stdclass();
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
        // $products   = $this->dao->select('product')->from(TABLE_PROJECTPRODUCT)->fetchAll('product');
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
    public function getTasks2Imported($fromProject)
    {
        $this->loadModel('task');
        $tasks        = array();
        $projectTasks = $this->task->getProjectTasks($fromProject, 'wait,doing,pause,cancel');
        $tasks        = array_merge($tasks, $projectTasks); 
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
        $this->loadModel('task');
        $this->loadModel('story');

        $now     = helper::now();
        $modules = $this->loadModel('tree')->getTaskOptionMenu($projectID);

        $bugToTasks = fixer::input('post')->get();
        $bugs       = $this->bug->getByList(array_keys($bugToTasks->import));
        foreach($bugToTasks->import as $key => $value)
        {
            $bug  = $bugs[$key];
            $task = new stdClass();
            $task->project      = $projectID;
            $task->story        = $bug->story;
            $task->storyVersion = $bug->storyVersion;
            $task->module       = isset($modules[$bug->module]) ? $bug->module : 0;
            $task->fromBug      = $key;
            $task->name         = $bug->title;
            $task->type         = 'devel';
            $task->pri          = $bugToTasks->pri[$key];
            $task->consumed     = 0;
            $task->status       = 'wait';
            $task->desc         = $this->lang->bug->resolve . ':' . '#' . html::a(helper::createLink('bug', 'view', "bugID=$key"), sprintf('%03d', $key));
            $task->openedDate   = $now;
            $task->openedBy     = $this->app->user->account;
            if(!empty($bugToTasks->estimate[$key]))
            {
                $task->estimate     = $bugToTasks->estimate[$key];
                $task->left         = $task->estimate;
            }
            if(!empty($bugToTasks->assignedTo[$key]))
            {
                $task->assignedTo   = $bugToTasks->assignedTo[$key];
                $task->assignedDate = $now;
            }
            if(!$bug->confirmed) $this->dao->update(TABLE_BUG)->set('confirmed')->eq(1)->where('id')->eq($bug->id)->exec();
            $this->dao->insert(TABLE_TASK)->data($task)->checkIF($bugToTasks->estimate[$key] != '', 'estimate', 'float')->exec();

            if(dao::isError()) 
            {
                echo js::error(dao::getError());
                die(js::reload('parent'));
            }

            $taskID = $this->dao->lastInsertID();
            if($task->story != false) $this->story->setStage($task->story);
            $actionID = $this->loadModel('action')->create('task', $taskID, 'Opened', '');
            $mails[$key] = new stdClass();
            $mails[$key]->taskID  = $taskID;
            $mails[$key]->actionID = $actionID;

            $this->action->create('bug', $key, 'Totask', '', $taskID);
            $this->dao->update(TABLE_BUG)->set('toTask')->eq($taskID)->where('id')->eq($key)->exec();

            /* activate bug if bug postponed. */
            if($bug->status == 'resolved' && $bug->resolution == 'postponed')
            {
                $newBug = new stdclass();
                $newBug->lastEditedBy   = $this->app->user->account;
                $newBug->lastEditedDate = $now;
                $newBug->assignedDate   = $now;
                $newBug->status         = 'active';
                $newBug->resolvedDate   = '0000-00-00';
                $newBug->resolution     = '';
                $newBug->resolvedBy     = '';
                $newBug->resolvedBuild  = '';
                $newBug->closedBy       = '';
                $newBug->closedDate     = '0000-00-00';
                $newBug->duplicateBug   = '0';

                $this->dao->update(TABLE_BUG)->data($newBug)->autoCheck()->where('id')->eq($key)->exec();
                $this->dao->update(TABLE_BUG)->set('activatedCount = activatedCount + 1')->where('id')->eq($key)->exec();

                $actionID = $this->action->create('bug', $key, 'Activated');
                $changes  = common::createChanges($bug, $newBug);
                $this->action->logHistory($actionID, $changes);
            }

            if(isset($task->assignedTo) and $task->assignedTo and $task->assignedTo != $bug->assignedTo)
            {
                $newBug = new stdClass();
                $newBug->lastEditedBy   = $this->app->user->account;
                $newBug->lastEditedDate = $now;
                $newBug->assignedTo     = $task->assignedTo;
                $newBug->assignedDate   = $now;
                $this->dao->update(TABLE_BUG)->data($newBug)->where('id')->eq($key)->exec();
                if(dao::isError()) die(js::error(dao::getError()));
                $changes = common::createChanges($bug, $newBug);

                $actionID = $this->action->create('bug', $key, 'Assigned', '', $newBug->assignedTo);
                $this->action->logHistory($actionID, $changes);
            }
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
            $data = new stdclass();
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
     * Get teams which can be imported.
     * 
     * @param  string $account 
     * @param  int    $currentProject 
     * @access public
     * @return array
     */
    public function getTeams2Import($account, $currentProject)
    {
        return $this->dao->select('t1.project, t2.name as projectName')
            ->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->where('t1.account')->eq($account)
            ->andWhere('t1.project')->ne($currentProject)
            ->groupBy('t1.project')
            ->orderBy('t1.project DESC')
            ->fetchPairs();
    }

    /**
     * Get members of a project who can be imported.
     * 
     * @param  int    $project 
     * @param  array  $currentMembers 
     * @access public
     * @return array
     */
    public function getMembers2Import($project, $currentMembers)
    {
        if($project == 0) return array();

        return $this->dao->select('account, role, hours')
            ->from(TABLE_TEAM)
            ->where('project')->eq($project)
            ->andWhere('account')->notIN($currentMembers)
            ->fetchAll('account');
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

        $accounts = array_unique($accounts);
        foreach($accounts as $key => $account)
        {
            if(empty($account)) continue;

            $member = new stdclass();
            $member->role  = $roles[$key];
            $member->days  = $days[$key];
            $member->hours = $hours[$key];

            $mode = $modes[$key];
            if($mode == 'update')
            {
                $this->dao->update(TABLE_TEAM)
                    ->data($member)
                    ->where('project')->eq((int)$projectID)
                    ->andWhere('account')->eq($account)
                    ->exec();
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
        $today = helper::today();
        $burns = array();

        $projects = $this->dao->select('id, code')->from(TABLE_PROJECT)
            ->where("end >= '$today'")
            ->andWhere('status')->notin('done,suspended')
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
     * @param  string $mode        noempty: skip the dates without burn down data.
     * @access public
     * @return array
     */
    public function getBurnData($projectID = 0, $itemCounts = 30, $mode = 'noempty')
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
            $sets = $sql->orderBy('date ASC')->fetchAll('name');
            $this->processBurnData($sets, $itemCounts, $project->begin, $project->end, $mode);
        }

        foreach($sets as $set) $set->name = substr($set->name, 5);
        return $sets;
    }

    /**
     * Get burn data for flot 
     * 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function getBurnDataFlot($projectID = 0)
    {
        /* Get project and burn counts. */
        $project    = $this->getById($projectID);

        /* If the burnCounts > $itemCounts, get the latest $itemCounts records. */
        $sets = $this->dao->select('date AS name, `left` AS value')->from(TABLE_BURN)->where('project')->eq((int)$projectID)->orderBy('date DESC')->fetchAll('name');

        $count    = 0;
        $burnData = array();
        foreach($sets as $date => $set) 
        {
            if($date > $project->end) continue;

            $burnData[$date] = $set;
            $count++;
        }
        $burnData = array_reverse($burnData);

        return $burnData;
    }

    /**
     * Process burndown datas when the sets is smaller than the itemCounts.
     * 
     * @param  array   $sets 
     * @param  int     $itemCounts 
     * @param  date    $begin 
     * @param  date    $end 
     * @param  string  $mode 
     * @access public
     * @return array
     */
    public function processBurnData($sets, $itemCounts, $begin, $end, $mode = 'noempty')
    {
        $burnCounts = count($sets);
        $current    = helper::today();

        if($end != '0000-00-00')
        {
            $period = helper::diffDate($end, $begin) + 1;
            $counts = $period > $itemCounts ? $itemCounts : $period;
        }
        else
        {
            $counts = $itemCounts;
        }

        for($i = 0; $i < $counts - $burnCounts; $i ++)
        {
            if(helper::diffDate($current, $end) > 0) break;
            if(!isset($sets[$current]) and $mode != 'noempty')
            {
                $sets[$current]->name = $current;
                $sets[$current]->value = '';
            }
            $nextDay = date(DT_DATE1, strtotime('next day', strtotime($current)));
            $current = $nextDay;
        }
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
        $this->loadModel('task')->processTasks($tasks);
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
            ->beginIF(!empty($products))->andWhere('product')->in(array_keys($products))->fi()
            ->beginIF(empty($products))->andWhere('project')->eq($projectID)->fi()
            ->andWhere('deleted')->eq(0)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
    }

    /**
     * Get the summary of project.
     * 
     * @param  array    $tasks 
     * @access public
     * @return string
     */
    public function summary($tasks)
    {
        $taskSum = $statusWait = $statusDone = $statusDoing = $statusClosed = $statusCancel = $statusPause = 0;  
        $totalEstimate = $totalConsumed = $totalLeft = 0.0;
        foreach($tasks as $task)
        {
            $totalEstimate  += $task->estimate;
            $totalConsumed  += $task->consumed;
            $totalLeft      += (($task->status == 'cancel' or $task->closedReason == 'cancel') ? 0 : $task->left);
            $statusVar       = 'status' . ucfirst($task->status);
            $$statusVar ++;
        }

        return sprintf($this->lang->project->taskSummary, count($tasks), $statusWait, $statusDoing, $totalEstimate, round($totalConsumed, 1), $totalLeft);
    }

    /**
     * Judge an action is clickable or not.
     * 
     * @param  object    $project
     * @param  string    $action 
     * @access public
     * @return bool
     */
    public static function isClickable($project, $action)
    {
        $action = strtolower($action);

        if($action == 'start')    return $project->status == 'wait';
        if($action == 'close')    return $project->status != 'done';
        if($action == 'suspend')  return $project->status == 'wait' or $project->status == 'doing';
        if($action == 'putoff')   return $project->status == 'wait' or $project->status == 'doing';
        if($action == 'activate') return $project->status == 'suspended' or $project->status == 'done';

        return true;
    }

    /**
     * Create the link from module,method,extra
     * 
     * @param  string  $module 
     * @param  string  $method 
     * @param  mix     $extra 
     * @access public
     * @return void
     */
    public function getProjectLink($module, $method, $extra)
    {
        $link = '';
        if($module == 'task' && ($method == 'view' || $method == 'edit' || $method == 'batchedit'))
        {   
            $module = 'project';
            $method = 'task';
        }   
        if($module == 'build' && $method == 'edit')
        {   
            $module = 'project';
            $method = 'build';
        }   

        if($module == 'project' && $method == 'create') return;
        if($extra != '')
        {
            $link = helper::createLink($module, $method, "projectID=%s&type=$extra");
        }
        elseif($module == 'project' && $method == 'index')
        {
            $link = helper::createLink($module, $method, "locate=no&status=undone&projectID=%s");
        }
        else
        {
            $link = helper::createLink($module, $method, "projectID=%s");
        }
        return $link;
    }

    /**
     * Get no weekend date 
     * 
     * @param  string     $begin 
     * @param  string     $end 
     * @param  string     $type 
     * @param  string|int $interval 
     * @access public
     * @return array
     */
    public function getDateList($begin, $end, $type, $interval = '')
    {
        $begin    = strtotime($begin);
        $end      = strtotime($end);

        $days = ($end - $begin) / 3600 / 24;
        if($type == 'noweekend')
        {
            $mod   = $days % 7;
            $days  = $days - floor($days / 7) * 2;
            $days  = $mod == 6 ? $days - 1 : $days;
        }

        if(!$interval) $interval = floor($days / $this->config->project->maxBurnDay);

        $dateList = array();
        $date     = $begin;
        $spaces   = (int)$interval;
        $counter  = $spaces;
        while($date <= $end)
        {
            /* Remove weekend when type is noweekend.*/
            if($type == 'noweekend')
            {
                $weekDay = date('w', $date);
                if($weekDay == 6 or $weekDay == 0)
                {
                    $date += 24 * 3600;
                    continue;
                }
            }

            $counter ++;
            if($counter <= $spaces)
            {
                $date += 24 * 3600;
                continue;
            }

            $counter    = 0;
            $dateList[] = date('m/d/Y', $date);
            $date += 24 * 3600;
        }

        return array($dateList, $interval);
    }
}
