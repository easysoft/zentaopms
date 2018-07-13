<?php
/**
 * The model file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
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
        if($this->app->user->admin) return true;

        $acls = $this->app->user->rights['acls'];
        if(!empty($acls['projects']) and !in_array($project->id, $acls['projects'])) return false;

        /* If project is open, return true. */
        if($project->acl == 'open') return true;

        /* Get all teams of all projects and group by projects, save it as static. */
        static $teams;
        if(empty($teams)) $teams = $this->dao->select('root, account')->from(TABLE_TEAM)->where('type')->eq('project')->fetchGroup('root', 'account');
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
     * @param  int    $buildID
     * @param  string $extra
     * @access public
     * @return void
     */
    public function setMenu($projects, $projectID, $buildID = 0, $extra = '')
    {
        /* Check the privilege. */
        $project = $this->getById($projectID);

        /* Unset story, bug, build and testtask if type is ops. */
        if($project and $project->type == 'ops')
        {
            unset($this->lang->project->menu->story);
            unset($this->lang->project->menu->qa);
            unset($this->lang->project->subMenu->qa->bug);
            unset($this->lang->project->subMenu->qa->build);
            unset($this->lang->project->subMenu->qa->testtask);
        }

        if($projects and !isset($projects[$projectID]) and !$this->checkPriv($project))
        {
            echo(js::alert($this->lang->project->accessDenied));
            $loginLink = $this->config->requestType == 'GET' ? "?{$this->config->moduleVar}=user&{$this->config->methodVar}=login" : "user{$this->config->requestFix}login";
            if(strpos($this->server->http_referer, $loginLink) !== false) die(js::locate(inlink('index')));
            die(js::locate('back'));
        }

        $moduleName = $this->app->getModuleName();
        $methodName = $this->app->getMethodName();

        if($this->cookie->projectMode == 'noclosed' and ($project->status == 'done' or $project->status == 'closed'))
        {
            setcookie('projectMode', 'all');
            $this->cookie->projectMode = 'all';
        }

        $selectHtml = $this->select($projects, $projectID, $buildID, $moduleName, $methodName, $extra);

        $label = $this->lang->project->index;
        if($moduleName == 'project' && $methodName == 'all')    $label = $this->lang->project->allProjects;
        if($moduleName == 'project' && $methodName == 'create') $label = $this->lang->project->create;

        $projectIndex = '';
        $isMobile     = $this->app->viewType == 'mhtml';
        if($isMobile)
        {
            $projectIndex  = html::a(helper::createLink('project', 'index'), $this->lang->project->index) . $this->lang->colon;
            $projectIndex .= $selectHtml;
        }
        else
        {
            $projectIndex  = '<div class="btn-group angle-btn"><div class="btn-group"><button data-toggle="dropdown" type="button" class="btn">' . $label . ' <span class="caret"></span></button>';
            $projectIndex .= '<ul class="dropdown-menu">';
            if(common::hasPriv('project', 'index'))  $projectIndex .= '<li>' . html::a(helper::createLink('project', 'index', 'locate=no'), '<i class="icon icon-home"></i> ' . $this->lang->project->index) . '</li>';
            if(common::hasPriv('project', 'all'))    $projectIndex .= '<li>' . html::a(helper::createLink('project', 'all', 'status=all'), '<i class="icon icon-cards-view"></i> ' . $this->lang->project->allProjects) . '</li>';

            if(common::isTutorialMode())
            {
                $wizardParams = helper::safe64Encode('');
                $link = helper::createLink('tutorial', 'wizard', "module=project&method=create&params=$wizardParams");
                $projectIndex .= '<li>' . html::a($link, "<i class='icon icon-plus'></i> {$this->lang->project->create}", '', "class='create-project-btn'") . '</li>';
            }
            else
            {
                if(common::hasPriv('project', 'create')) $projectIndex .= '<li>' . html::a(helper::createLink('project', 'create'), '<i class="icon icon-plus"></i> ' . $this->lang->project->create) . '</li>';
            }

            $projectIndex .= '</ul></div></div>';
            $projectIndex .= $selectHtml;
        }

        $this->lang->modulePageNav = $projectIndex;
        if($moduleName != 'project') $this->lang->$moduleName->dividerMenu = $this->lang->project->dividerMenu;

        foreach($this->lang->project->menu as $key => $menu)
        {
            common::setMenuVars($this->lang->project->menu, $key, $projectID);

            /* Replace for dropdown submenu. */
            if(isset($this->lang->project->subMenu->$key))
            {
                $subMenu = common::createSubMenu($this->lang->project->subMenu->$key, $projectID);

                if(!empty($subMenu)) $this->lang->project->menu->{$key}['subMenu'] = $subMenu;
            }
        }
    }

    /**
     * Create the select code of projects.
     *
     * @param  array     $projects
     * @param  int       $projectID
     * @param  int       $buildID
     * @param  string    $currentModule
     * @param  string    $currentMethod
     * @param  string    $extra
     * @access public
     * @return string
     */
    public function select($projects, $projectID, $buildID, $currentModule, $currentMethod, $extra = '')
    {
        if(!$projectID) return;

        $isMobile = $this->app->viewType == 'mhtml';

        setCookie("lastProject", $projectID, $this->config->cookieLife, $this->config->webRoot);
        $currentProject = $this->getById($projectID);

        $dropMenuLink = helper::createLink('project', 'ajaxGetDropMenu', "objectID=$projectID&module=$currentModule&method=$currentMethod&extra=$extra");
        $output  = "<div class='btn-group angle-btn'><div class='btn-group'><button data-toggle='dropdown' type='button' class='btn btn-limit' id='currentItem' title='{$currentProject->name}'>{$currentProject->name} <span class='caret'></span></button><div id='dropMenu' class='dropdown-menu search-list' data-ride='searchList' data-url='$dropMenuLink'>";
        $output .= '<div class="input-control search-box has-icon-left has-icon-right search-example"><input type="search" class="form-control search-input" /><label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label><a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a></div>';
        $output .= "</div></div></div>";
        if($isMobile) $output  = "<a id='currentItem' href=\"javascript:showSearchMenu('project', '$projectID', '$currentModule', '$currentMethod', '$extra')\">{$currentProject->name} <span class='icon-caret-down'></span></a><div id='currentItemDropMenu' class='hidden affix enter-from-bottom layer'></div>";

        if($buildID and !$isMobile)
        {
            setCookie('lastBuild', $buildID, $this->config->cookieLife, $this->config->webRoot);
            $currentBuild = $this->loadModel('build')->getById($buildID);

            if($currentBuild)
            {
                $dropMenuLink = helper::createLink('build', 'ajaxGetProjectBuilds', "projectID=$projectID&productID=&varName=dropdownList");
                $output .= "<div class='btn-group angle-btn'><div class='btn-group'><button data-toggle='dropdown' type='button' class='btn btn-limit' id='currentItem'>{$currentBuild->name} <span class='caret'></span></button><div id='dropMenu' class='dropdown-menu search-list' data-ride='searchList' data-url='$dropMenuLink'>";
                $output .= '<div class="input-control search-box has-icon-left has-icon-right search-example"><input type="search" class="form-control search-input" /><label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label><a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a></div>';
                $output .= "</div></div></div>";
            }
        }

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
        $projectTree  = "<ul class='tree tree-lines'>";
        foreach($productGroup as $productID => $projects)
        {
            if(!isset($products[$productID]) and $productID != '')  continue;
            if(!isset($products[$productID]) and !count($projects)) continue;

            $productName  = isset($products[$productID]) ? $products[$productID] : $this->lang->project->noProduct;

            $projectTree .= "<li>$productName<ul>";

            foreach($projects as $project)
            {
                if($project->status != 'done' or $project->status != 'closed')
                {
                    $projectTree .= "<li>" . html::a(inlink('task', "projectID=$project->id"), $project->name, '', "id='project$project->id'") . "</li>";
                }
            }

            $hasDone = false;
            foreach($projects as $project)
            {
                if($project->status == 'done' or $project->status == 'closed')
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
                    if($project->status == 'done' or $project->status == 'closed')
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
        if($projectID == 0 and $this->cookie->lastProject) $this->session->set('project', (int)$this->cookie->lastProject);
        if($projectID == 0 and $this->session->project == '') $this->session->set('project', key($projects));
        if(!isset($projects[$this->session->project]))
        {
            $this->session->set('project', key($projects));
            if($projectID > 0)
            {
                echo(js::alert($this->lang->project->accessDenied));
                $loginLink = $this->config->requestType == 'GET' ? "?{$this->config->moduleVar}=user&{$this->config->methodVar}=login" : "user{$this->config->requestFix}login";
                if(strpos($this->server->http_referer, $loginLink) !== false) die(js::locate(inlink('index')));
                die(js::locate('back'));
            }
        }
        return $this->session->project;
    }

    /**
     * Create a project.
     *
     * @param string $copyProjectID
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
            ->setDefault('openedBy', $this->app->user->account)
            ->setDefault('openedDate', helper::now())
            ->setDefault('openedVersion', $this->config->version)
            ->setDefault('team', substr($this->post->name,0, 30))
            ->join('whitelist', ',')
            ->stripTags($this->config->project->editor->create['id'], $this->config->allowedTags)
            ->remove('products, workDays, delta, branch, uid, plans')
            ->get();
        $project = $this->loadModel('file')->processImgURL($project, $this->config->project->editor->create['id'], $this->post->uid);
        $this->dao->insert(TABLE_PROJECT)->data($project)
            ->autoCheck($skipFields = 'begin,end')
            ->batchcheck($this->config->project->create->requiredFields, 'notempty')
            ->checkIF($project->begin != '', 'begin', 'date')
            ->checkIF($project->end != '', 'end', 'date')
            ->checkIF($project->end != '', 'end', 'gt', $project->begin)
            ->check('name', 'unique', "deleted='0'")
            ->check('code', 'unique', "deleted='0'")
            ->exec();

        /* Add the creater to the team. */
        if(!dao::isError())
        {
            $projectID     = $this->dao->lastInsertId();
            $today         = helper::today();
            $creatorExists = false;

            /* Save order. */
            $this->dao->update(TABLE_PROJECT)->set('`order`')->eq($projectID * 5)->where('id')->eq($projectID)->exec();
            $this->file->updateObjectID($this->post->uid, $projectID, 'project');

            /* Copy team of project. */
            if($copyProjectID != '')
            {
                $members = $this->dao->select('*')->from(TABLE_TEAM)->where('root')->eq($copyProjectID)->andWhere('type')->eq('project')->fetchAll();
                foreach($members as $member)
                {
                    unset($member->id);
                    $member->root = $projectID;
                    $member->join = $today;
                    $member->days = $project->days;
                    $member->type = 'project';
                    $this->dao->insert(TABLE_TEAM)->data($member)->exec();
                    if($member->account == $this->app->user->account) $creatorExists = true;
                }
            }

            /* Add the creator to team. */
            if($copyProjectID == '' or !$creatorExists)
            {
                $member = new stdclass();
                $member->root    = $projectID;
                $member->account = $this->app->user->account;
                $member->role    = $this->lang->user->roleList[$this->app->user->role];
                $member->join    = $today;
                $member->type    = 'project';
                $member->days    = $project->days;
                $member->hours   = $this->config->project->defaultWorkhours;
                $this->dao->insert(TABLE_TEAM)->data($member)->exec();
            }

            /* Create doc lib. */
            $this->app->loadLang('doc');
            $lib = new stdclass();
            $lib->project = $projectID;
            $lib->name    = $this->lang->doclib->main['project'];
            $lib->main    = '1';
            $lib->acl     = $project->acl == 'open' ? 'open' : 'private';
            $this->dao->insert(TABLE_DOCLIB)->data($lib)->exec();
            if(!dao::isError()) $this->loadModel('score')->create('project', 'create', $projectID);
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
        $oldProject = $this->dao->findById((int)$projectID)->from(TABLE_PROJECT)->fetch();
        $team = $this->getTeamMemberPairs($projectID);
        $this->lang->project->team = $this->lang->project->teamname;
        $projectID = (int)$projectID;
        $project = fixer::input('post')
            ->setIF($this->post->begin == '0000-00-00', 'begin', '')
            ->setIF($this->post->end   == '0000-00-00', 'end', '')
            ->setIF($this->post->acl != 'custom', 'whitelist', '')
            ->setDefault('team', $this->post->name)
            ->join('whitelist', ',')
            ->stripTags($this->config->project->editor->edit['id'], $this->config->allowedTags)
            ->remove('products, branch, uid, plans')
            ->get();
        $project = $this->loadModel('file')->processImgURL($project, $this->config->project->editor->edit['id'], $this->post->uid);
        $this->dao->update(TABLE_PROJECT)->data($project)
            ->autoCheck($skipFields = 'begin,end')
            ->batchcheck($this->config->project->edit->requiredFields, 'notempty')
            ->checkIF($project->begin != '', 'begin', 'date')
            ->checkIF($project->end != '', 'end', 'date')
            ->checkIF($project->end != '', 'end', 'gt', $project->begin)
            ->check('name', 'unique', "id!=$projectID and deleted='0'")
            ->check('code', 'unique', "id!=$projectID and deleted='0'")
            ->where('id')->eq($projectID)
            ->limit(1)
            ->exec();
        foreach($project as $fieldName => $value)
        {
            if($fieldName == 'PO' or $fieldName == 'PM' or $fieldName == 'QD' or $fieldName == 'RD' )
            {
                if(!empty($value) and !isset($team[$value]))
                {
                    $member->root    = (int)$projectID;
                    $member->account = $value;
                    $member->join    = helper::today();
                    $member->role    = $this->lang->project->$fieldName;
                    $member->days    = $project->days;
                    $member->type    = 'project';
                    $member->hours   = $this->config->project->defaultWorkhours;
                    $this->dao->replace(TABLE_TEAM)->data($member)->exec();
                }
            }
        }
        if(!dao::isError())
        {
            if($project->acl != $oldProject->acl) $this->dao->update(TABLE_DOCLIB)->set('acl')->eq($project->acl == 'open' ? 'open' : 'private')->where('project')->eq($projectID)->exec();

            $this->file->updateObjectID($this->post->uid, $projectID, 'project');
            return common::createChanges($oldProject, $project);
        }
    }

    /**
     * Batch update.
     *
     * @access public
     * @return void
     */
    public function batchUpdate()
    {
        $projects    = array();
        $allChanges  = array();
        $data        = fixer::input('post')->get();
        $oldProjects = $this->getByIdList($this->post->projectIDList);
        foreach($data->projectIDList as $projectID)
        {
            $projects[$projectID] = new stdClass();
            $projects[$projectID]->name   = $data->names[$projectID];
            $projects[$projectID]->code   = $data->codes[$projectID];
            $projects[$projectID]->PM     = $data->PMs[$projectID];
            $projects[$projectID]->PO     = $data->POs[$projectID];
            $projects[$projectID]->QD     = $data->QDs[$projectID];
            $projects[$projectID]->RD     = $data->RDs[$projectID];
            $projects[$projectID]->type   = $data->types[$projectID];
            $projects[$projectID]->status = $data->statuses[$projectID];
            $projects[$projectID]->begin  = $data->begins[$projectID];
            $projects[$projectID]->end    = $data->ends[$projectID];
            $projects[$projectID]->team   = $data->teams[$projectID];
            $projects[$projectID]->desc   = htmlspecialchars_decode($data->descs[$projectID]);
            $projects[$projectID]->days   = $data->dayses[$projectID];
            $projects[$projectID]->order  = $data->orders[$projectID];
        }

        foreach($projects as $projectID => $project)
        {
            $oldProject = $oldProjects[$projectID];
            $team       = $this->getTeamMemberPairs($projectID);

            $this->dao->update(TABLE_PROJECT)->data($project)
                ->autoCheck($skipFields = 'begin,end')
                ->batchcheck($this->config->project->edit->requiredFields, 'notempty')
                ->checkIF($project->begin != '', 'begin', 'date')
                ->checkIF($project->end != '', 'end', 'date')
                ->checkIF($project->end != '', 'end', 'gt', $project->begin)
                ->check('name', 'unique', "id!=$projectID and deleted='0'")
                ->check('code', 'unique', "id!=$projectID and deleted='0'")
                ->where('id')->eq($projectID)
                ->limit(1)
                ->exec();

            foreach($project as $fieldName => $value)
            {
                if($fieldName == 'PO' or $fieldName == 'PM' or $fieldName == 'QD' or $fieldName == 'RD' )
                {
                    if(!empty($value) and !isset($team[$value]))
                    {
                        $member = new stdClass();
                        $member->root    = (int)$projectID;
                        $member->type    = 'project';
                        $member->account = $value;
                        $member->join    = helper::today();
                        $member->role    = $this->lang->project->$fieldName;
                        $member->days    = 0;
                        $member->hours   = $this->config->project->defaultWorkhours;
                        $this->dao->replace(TABLE_TEAM)->data($member)->exec();
                    }
                }
            }

            if(dao::isError()) die(js::error('project#' . $projectID . dao::getError(true)));
            $allChanges[$projectID] = common::createChanges($oldProject, $project);
        }
        $this->fixOrder();
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
            ->remove('comment,readjustTime,readjustTask')
            ->get();

        if(!$this->post->readjustTime)
        {
            unset($project->begin);
            unset($project->end);
        }

        $this->dao->update(TABLE_PROJECT)->data($project)
            ->autoCheck()
            ->where('id')->eq((int)$projectID)
            ->exec();

        /* Readjust task. */
        if($this->post->readjustTime and $this->post->readjustTask)
        {
            $beginTimeStamp = strtotime($project->begin);
            $tasks = $this->dao->select('id,estStarted,deadline,status')->from(TABLE_TASK)
                ->where('deadline')->ne('0000-00-00')
                ->andWhere('status')->in('wait,doing')
                ->fetchAll();
            foreach($tasks as $task)
            {
                if($task->status == 'wait' and $task->estStarted != '0000-00-00')
                {
                    $taskDays   = helper::diffDate($task->deadline, $task->estStarted);
                    $taskOffset = helper::diffDate($task->estStarted, $oldProject->begin);

                    $estStartedTimeStamp = $beginTimeStamp + $taskOffset * 24 * 3600;
                    $estStarted = date('Y-m-d', $estStartedTimeStamp);
                    $deadline   = date('Y-m-d', $estStartedTimeStamp + $taskDays * 24 * 3600);

                    if($estStarted > $project->end) $estStarted = $project->end;
                    if($deadline > $project->end)   $deadline   = $project->end;
                    $this->dao->update(TABLE_TASK)->set('estStarted')->eq($estStarted)->set('deadline')->eq($deadline)->where('id')->eq($task->id)->exec();
                }
                else
                {
                    $taskOffset = helper::diffDate($task->deadline, $oldProject->begin);
                    $deadline   = date('Y-m-d', $beginTimeStamp + $taskOffset * 24 * 3600);

                    if($deadline > $project->end) $deadline = $project->end;
                    $this->dao->update(TABLE_TASK)->set('deadline')->eq($deadline)->where('id')->eq($task->id)->exec();
                }
            }
        }

        if(!dao::isError()) return common::createChanges($oldProject, $project);
    }

    /**
     * Close project.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function close($projectID)
    {
        $oldProject = $this->getById($projectID);
        $now        = helper::now();
        $project = fixer::input('post')
            ->setDefault('status', 'closed')
            ->setDefault('closedBy', $this->app->user->account)
            ->setDefault('closedDate', $now)
            ->remove('comment')
            ->get();

        $this->dao->update(TABLE_PROJECT)->data($project)
            ->autoCheck()
            ->where('id')->eq((int)$projectID)
            ->exec();
        if(!dao::isError())
        {
            $this->loadModel('score')->create('project', 'close', $oldProject);
            return common::createChanges($oldProject, $project);
        }
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
        if(defined('TUTORIAL')) return $this->loadModel('tutorial')->getProjectPairs();

        $orderBy  = !empty($this->config->project->orderBy) ? $this->config->project->orderBy : 'isDone, status';
        $mode    .= $this->cookie->projectMode;
        /* Order by status's content whether or not done */
        $projects = $this->dao->select('*, IF(INSTR(" done,closed", status) < 2, 0, 1) AS isDone')->from(TABLE_PROJECT)
            ->where('iscat')->eq(0)
            ->beginIF(strpos($mode, 'withdelete') === false)->andWhere('deleted')->eq(0)->fi()
            ->orderBy($orderBy)
            ->fetchAll();
        $pairs = array();
        foreach($projects as $project)
        {
            if(strpos($mode, 'noclosed') !== false and ($project->status == 'done' or $project->status == 'closed')) continue;
            if($this->checkPriv($project)) $pairs[$project->id] = $project->name;
        }
        if(strpos($mode, 'empty') !== false) $pairs[0] = '';

        /* If the pairs is empty, to make sure there's an project in the pairs. */
        if(empty($pairs) and isset($projects[0]) and $this->checkPriv($projects[0]))
        {
            $firstProject = $projects[0];
            $pairs[$firstProject->id] = $firstProject->name;
        }

        return $pairs;
    }

    /**
     * Get by idList.
     *
     * @param  array    $projectIDList
     * @access public
     * @return array
     */
    public function getByIdList($projectIDList)
    {
        return $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->in($projectIDList)->fetchAll('id');
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
    public function getList($status = 'all', $limit = 0, $productID = 0, $branch = 0)
    {
        if($status == 'involved') return $this->getInvolvedList($status, $limit, $productID, $branch);

        if($productID != 0)
        {
            return $this->dao->select('t2.*')->from(TABLE_PROJECTPRODUCT)->alias('t1')
                ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
                ->where('t1.product')->eq($productID)
                ->andWhere('t2.deleted')->eq(0)
                ->andWhere('t2.iscat')->eq(0)
                ->beginIF($status == 'undone')->andWhere('t2.status')->ne('done')->andWhere('t2.status')->ne('closed')->fi()
                ->beginIF($branch)->andWhere('t1.branch')->eq($branch)->fi()
                ->beginIF($status == 'isdoing')->andWhere('t2.status')->ne('done')->andWhere('t2.status')->ne('suspended')->andWhere('t2.status')->ne('closed')->fi()
                ->beginIF($status != 'all' and $status != 'isdoing' and $status != 'undone')->andWhere('status')->in($status)->fi()
                ->orderBy('order_desc')
                ->beginIF($limit)->limit($limit)->fi()
                ->fetchAll('id');
        }
        else
        {
            return $this->dao->select('*, IF(INSTR(" done,closed", status) < 2, 0, 1) AS isDone')->from(TABLE_PROJECT)->where('iscat')->eq(0)
                ->beginIF($status == 'undone')->andWhere('status')->ne('done')->andWhere('status')->ne('closed')->fi()
                ->beginIF($status == 'isdoing')->andWhere('status')->ne('done')->andWhere('status')->ne('suspended')->andWhere('status')->ne('closed')->fi()
                ->beginIF($status != 'all' and $status != 'isdoing' and $status != 'undone')->andWhere('status')->in($status)->fi()
                ->andWhere('deleted')->eq(0)
                ->orderBy('order_desc')
                ->beginIF($limit)->limit($limit)->fi()
                ->fetchAll('id');
        }
    }

    /**
     * Get project lists.
     *
     * @param  string $status  involved
     * @param  int    $limit
     * @param  int    $productID
     * @param  int    $branch
     * @access public
     * @return array
     */
    public function getInvolvedList($status = 'involved', $limit = 0, $productID = 0, $branch = 0)
    {
        if($productID != 0)
        {
            return $this->dao->select('t2.*')->from(TABLE_PROJECTPRODUCT)->alias('t1')
                ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
                ->leftJoin(TABLE_TEAM)->alias('t3')->on('t3.root=t2.id')
                ->where('t1.product')->eq($productID)
                ->andWhere('t2.deleted')->eq(0)
                ->andWhere('t2.iscat')->eq(0)
                ->beginIF($branch)->andWhere('t1.branch')->eq($branch)->fi()
                ->andWhere('t2.openedBy', true)->eq($this->app->user->account)
                ->orWhere('t3.account')->eq($this->app->user->account)
                ->markRight(1)
                ->andWhere('t3.type')->eq('project')
                ->orderBy('order_desc')
                ->beginIF($limit)->limit($limit)->fi()
                ->fetchAll('id');
        }
        else
        {
            return $this->dao->select('t1.*, IF(INSTR(" done,closed", t1.status) < 2, 0, 1) AS isDone')->from(TABLE_PROJECT)->alias('t1')
                ->leftJoin(TABLE_TEAM)->alias('t2')->on('t2.root=t1.id')
                ->where('t1.iscat')->eq(0)
                ->andWhere('t1.openedBy', true)->eq($this->app->user->account)
                ->orWhere('t2.account')->eq($this->app->user->account)
                ->markRight(1)
                ->andWhere('t1.deleted')->eq(0)
                ->andWhere('t2.type')->eq('project')
                ->orderBy('t1.order_desc')
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
    public function getProjectStats($status = 'undone', $productID = 0, $branch = 0, $itemCounts = 30, $orderBy = 'order_desc', $pager = null)
    {
        /* Init vars. */
        $projects = $this->getList($status, 0, $productID, $branch);
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
            ->andWhere('parent')->eq(0)
            ->andWhere('deleted')->eq(0)
            ->fetchGroup('project', 'id');

        /* Compute totalEstimate, totalConsumed, totalLeft. */
        foreach($tasks as $projectID => $projectTasks)
        {
            $hour = (object)$emptyHour;
            foreach($projectTasks as $task)
            {
                if($task->status != 'cancel')
                {
                    $hour->totalEstimate += $task->estimate;
                    $hour->totalConsumed += $task->consumed;
                }
                if($task->status != 'cancel' and $task->status != 'closed') $hour->totalLeft += $task->left;
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
            $projectBurns = $this->processBurnData($projectBurns, $itemCounts, $begin, $end);

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
            $project->end = date(DT_DATE1, strtotime($project->end));

            /* Judge whether the project is delayed. */
            if($project->status != 'done' and $project->status != 'closed' and $project->status != 'suspended')
            {
                $delay = helper::diffDate(helper::today(), $project->end);
                if($delay > 0) $project->delay = $delay;
            }

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
     * Get tasks.
     *
     * @param  int    $productID
     * @param  int    $projectID
     * @param  array  $projects
     * @param  string $browseType
     * @param  int    $queryID
     * @param  int    $moduleID
     * @param  string $sort
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getTasks($productID, $projectID, $projects, $browseType, $queryID, $moduleID, $sort, $pager)
    {
        $this->loadModel('task');

        /* Set modules and $browseType. */
        $modules = array();
        if($moduleID) $modules = $this->loadModel('tree')->getAllChildID($moduleID);
        if($browseType == 'bymodule' or $browseType == 'byproduct')
        {
            if(($this->session->taskBrowseType) and ($this->session->taskBrowseType != 'bysearch')) $browseType = $this->session->taskBrowseType;
        }

        $this->session->set('taskWithChildren', in_array($browseType, array('unclosed', 'byproject', 'all')) ? false : true);

        /* Get tasks. */
        $tasks = array();
        if($browseType != "bysearch")
        {
            $queryStatus = $browseType == 'byproject' ? 'all' : $browseType;
            if($queryStatus == 'unclosed')
            {
                $queryStatus = $this->lang->task->statusList;
                unset($queryStatus['closed']);
                $queryStatus = array_keys($queryStatus);
            }
            $tasks = $this->task->getProjectTasks($projectID, $productID, $queryStatus, $modules, $sort, $pager);
        }
        else
        {
            if($queryID)
            {
                $query = $this->loadModel('search')->getQuery($queryID);
                if($query)
                {
                    $this->session->set('taskQuery', $query->sql);
                    $this->session->set('taskForm', $query->form);
                }
                else
                {
                    $this->session->set('taskQuery', ' 1 = 1');
                }
            }
            else
            {
                if($this->session->taskQuery == false) $this->session->set('taskQuery', ' 1 = 1');
            }

            if(strpos($this->session->taskQuery, "deleted =") === false)   $this->session->set('taskQuery', $this->session->taskQuery . " AND deleted = '0'");

            $taskQuery = $this->session->taskQuery;
            /* Limit current project when no project. */
            if(strpos($taskQuery, "`project` =") === false) $taskQuery = $taskQuery . " AND `project` = $projectID";
            $projectQuery = "`project` " . helper::dbIN(array_keys($projects));
            $taskQuery    = str_replace("`project` = 'all'", $projectQuery, $taskQuery); // Search all project.
            $this->session->set('taskQueryCondition', $taskQuery);
            $this->session->set('taskOnlyCondition', true);
            $this->session->set('taskOrderBy', $sort);

            $tasks = $this->getSearchTasks($taskQuery, $pager, $sort);
        }

        return $tasks;
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
        if(defined('TUTORIAL')) return $this->loadModel('tutorial')->getProject();

        $project = $this->dao->findById((int)$projectID)->from(TABLE_PROJECT)->fetch();
        if(!$project) return false;

        /* Judge whether the project is delayed. */
        if($project->status != 'done' and $project->status != 'closed' and $project->status != 'suspended')
        {
            $delay = helper::diffDate(helper::today(), $project->end);
            if($delay > 0) $project->delay = $delay;
        }

        $total = $this->dao->select('
            SUM(estimate) AS totalEstimate,
            SUM(consumed) AS totalConsumed,
            SUM(`left`) AS totalLeft')
            ->from(TABLE_TASK)
            ->where('project')->eq((int)$projectID)
            ->andWhere('status')->ne('cancel')
            ->andWhere('deleted')->eq(0)
            ->andWhere('parent')->eq(0)
            ->fetch();
        $closedTotalLeft= (int)$this->dao->select('SUM(`left`) AS totalLeft')->from(TABLE_TASK)
            ->where('project')->eq((int)$projectID)
            ->andWhere('status')->eq('closed')
            ->andWhere('deleted')->eq(0)
            ->andWhere('parent')->eq(0)
            ->fetch('totalLeft');

        $project->days          = $project->days ? $project->days : '';
        $project->totalHours    = $this->dao->select('sum(days * hours) AS totalHours')->from(TABLE_TEAM)->where('root')->eq($project->id)->andWhere('type')->eq('project')->fetch('totalHours');
        $project->totalEstimate = round($total->totalEstimate, 1);
        $project->totalConsumed = round($total->totalConsumed, 1);
        $project->totalLeft     = round($total->totalLeft - $closedTotalLeft, 1);

        $project = $this->loadModel('file')->replaceImgURL($project, 'desc');
        if($setImgSize) $project->desc = $this->file->setImgSize($project->desc);

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
    public function getProducts($projectID, $withBranch = true)
    {
        if($this->config->global->flow == 'onlyTask') return array();

        if(defined('TUTORIAL'))
        {
            if(!$withBranch) return $this->loadModel('tutorial')->getProductPairs();
            return $this->loadModel('tutorial')->getProjectProducts();
        }
        $query = $this->dao->select('t2.id, t2.name, t2.type, t1.branch, t1.plan')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')
            ->on('t1.product = t2.id')
            ->where('t1.project')->eq((int)$projectID)
            ->andWhere('t2.deleted')->eq(0);
        if(!$withBranch) return $query->fetchPairs('id', 'name');
        return $query->fetchAll('id');
    }

    /**
     * Build story search form.
     *
     * @param  array  $products
     * @param  array  $branchGroups
     * @param  array  $modules
     * @param  int    $queryID
     * @param  string $actionURL
     * @param  string $type
     * @access public
     * @return void
     */
    public function buildStorySearchForm($products, $branchGroups, $modules, $queryID, $actionURL, $type = 'projectStory')
    {
        $branchPairs  = array();
        $productType  = 'normal';
        $productNum   = count($products);
        $productPairs = array(0 => '');
        foreach($products as $product)
        {
            $productPairs[$product->id] = $product->name;
            if($product->type != 'normal')
            {
                $productType = $product->type;
                if($product->branch)
                {
                    if(!isset($branchGroups[$product->id][$product->branch])) continue;
                    $branchPairs[$product->branch] = (count($products) > 1 ? $product->name . '/' : '') . $branchGroups[$product->id][$product->branch];
                }
                else
                {
                    $productBranches = isset($branchGroups[$product->id]) ? $branchGroups[$product->id] : array(0);
                    if(count($products) > 1)
                    {
                        foreach($productBranches as $branchID => $branchName) $productBranches[$branchID] = $product->name . '/' . $branchName;
                    }
                    $branchPairs += $productBranches;
                }
            }
        }

        /* Build search form. */
        if($type == 'projectStory') $this->config->product->search['module'] = 'projectStory';
        $this->config->product->search['actionURL'] = $actionURL;
        $this->config->product->search['queryID']   = $queryID;
        $this->config->product->search['params']['product']['values'] = $productPairs + array('all' => $this->lang->product->allProductsOfProject);
        $this->config->product->search['params']['plan']['values'] = $this->loadModel('productplan')->getForProducts($products);
        $this->config->product->search['params']['module']['values'] = $modules;
        unset($this->lang->story->statusList['draft']);
        if($productType == 'normal')
        {
            unset($this->config->product->search['fields']['branch']);
            unset($this->config->product->search['params']['branch']);
        }
        else
        {
            $this->config->product->search['fields']['branch'] = sprintf($this->lang->product->branch, $this->lang->product->branchName[$productType]);
            $this->config->product->search['params']['branch']['values'] = array('' => '') + $branchPairs;
            unset($this->config->product->search['fields']['stage']);
            unset($this->config->product->search['params']['stage']);
        }
        $this->config->product->search['params']['status'] = array('operator' => '=', 'control' => 'select', 'values' => $this->lang->story->statusList);

        $this->loadModel('search')->setSearchParams($this->config->product->search);
    }

    /**
     * Get projects to import
     *
     * @param  array  $projectIds
     * @access public
     * @return array
     */
    public function getProjectsToImport($projectIds)
    {
        $projects = $this->dao->select('*')->from(TABLE_PROJECT)
            ->where('id')->in($projectIds)
            ->andWhere('deleted')->eq(0)
            ->orderBy('id desc')
            ->fetchAll('id');

        $pairs = array();
        $now   = date('Y-m-d');
        foreach($projects as $id => $project)
        {
            if($this->checkPriv($project)) $pairs[$id] = ucfirst(substr($project->code, 0, 1)) . ':' . $project->name;
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
        $oldProjectProducts = $this->dao->select('*')->from(TABLE_PROJECTPRODUCT)->where('project')->eq((int)$projectID)->fetchGroup('product', 'branch');
        $this->dao->delete()->from(TABLE_PROJECTPRODUCT)->where('project')->eq((int)$projectID)->exec();
        if(!isset($_POST['products'])) return;
        $products = $_POST['products'];
        $branches = isset($_POST['branch']) ? $_POST['branch'] : array();
        $plans    = isset($_POST['plans']) ? $_POST['plans'] : array();;

        $existedProducts = array();
        foreach($products as $i => $productID)
        {
            if(empty($productID)) continue;
            if(isset($existedProducts[$productID])) continue;

            $oldPlan = 0;
            $branch  = isset($branches[$i]) ? $branches[$i] : 0;
            if(isset($oldProjectProducts[$productID][$branch]))
            {
                $oldProjectProduct = $oldProjectProducts[$productID][$branch];
                $oldPlan           = $oldProjectProduct->plan;
            }

            $data = new stdclass();
            $data->project = $projectID;
            $data->product = $productID;
            $data->branch  = $branch;
            $data->plan    = isset($plans[$productID]) ? $plans[$productID] : $oldPlan;
            $this->dao->insert(TABLE_PROJECTPRODUCT)->data($data)->exec();
            $existedProducts[$productID] = true;
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
     * Get tasks can be imported.
     *
     * @param  int    $toProject
     * @param  array  $branches
     * @access public
     * @return array
     */
    public function getTasks2Imported($toProject, $branches)
    {
        $this->loadModel('task');

        $products = $this->getProducts($toProject);
        $projects = $this->dao->select('product, project')->from(TABLE_PROJECTPRODUCT)->where('product')->in(array_keys($products))->fetchGroup('project');
        $branches = str_replace(',', "','", $branches);

        $tasks = $this->dao->select('t1.*, t2.id AS storyID, t2.title AS storyTitle, t2.version AS latestStoryVersion, t2.status AS storyStatus, t3.realname AS assignedToRealName')->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->leftJoin(TABLE_USER)->alias('t3')->on('t1.assignedTo = t3.account')
            ->where('t1.status')->in('wait, doing, pause, cancel')
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t1.parent')->eq(0)
            ->andWhere('t1.project')->in(array_keys($projects))
            ->andWhere("(t1.story = 0 OR (t2.branch in ('0','" . join("','", $branches) . "') and t2.product " . helper::dbIN(array_keys($branches)) . "))")
            ->fetchGroup('project', 'id');
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
            $assignedToes[$task->assignedTo] = $task->project;
            $stories[$task->story]           = $task->story;

            $data = new stdclass();
            $data->project = $projectID;

            if($task->status == 'cancel')
            {
                $data->canceledBy   = '';
                $data->canceledDate = null;
            }

            $data->status = $task->consumed > 0 ? 'doing' : 'wait';
            $this->dao->update(TABLE_TASK)->data($data)->where('id')->in($this->post->tasks)->orWhere('parent')->in($this->post->tasks)->exec();
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
                $role = $this->dao->select('*')->from(TABLE_TEAM)
                    ->where('root')->eq($preProjectID)
                    ->andWhere('type')->eq('project')
                    ->andWhere('account')->eq($account)
                    ->fetch();

                $role->root = $projectID;
                $role->join = helper::today();
                $this->dao->replace(TABLE_TEAM)->data($role)->exec();
            }
        }

        /* Link stories. */
        $projectStories = $this->loadModel('story')->getProjectStoryPairs($projectID);
        $lastOrder      = (int)$this->dao->select('*')->from(TABLE_PROJECTSTORY)->where('project')->eq($projectID)->orderBy('order_desc')->limit(1)->fetch('order');
        foreach($stories as $storyID)
        {
            if(!isset($projectStories[$storyID]))
            {
                $story = $this->dao->findById($storyID)->fields("$projectID as project, id as story, product, version")->from(TABLE_STORY)->fetch();
                $story->order = ++$lastOrder;
                $this->dao->insert(TABLE_PROJECTSTORY)->data($story)->exec();
            }
        }
    }

    /**
     * Stat story, task, bug data for project.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function statRelatedData($projectID)
    {
        $storyCount = $this->dao->select('count(t2.story) as storyCount')->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PROJECTSTORY)->alias('t2')->on('t1.id = t2.story')
            ->where('project')->eq($projectID)
            ->andWhere('t1.deleted')->eq(0)
            ->fetch('storyCount');

        $taskCount = $this->dao->select('count(id) as taskCount')->from(TABLE_TASK)->where('project')->eq($projectID)->andWhere('parent')->eq(0)->andWhere('deleted')->eq(0)->fetch('taskCount');
        $bugCount  = $this->dao->select('count(id) as bugCount')->from(TABLE_BUG)->where('project')->eq($projectID)->andWhere('deleted')->eq(0)->fetch('bugCount');

        $statData = new stdclass();
        $statData->storyCount = $storyCount;
        $statData->taskCount  = $taskCount;
        $statData->bugCount   = $bugCount;

        return $statData;
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
            $task->deadline     = $bugToTasks->deadline[$key];
            $task->consumed     = 0;
            $task->status       = 'wait';
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
     * @param int   $projectID
     * @param array $stories
     * @param array $products
     *
     * @access public
     * @return mixed
     */
    public function linkStory($projectID, $stories = array(), $products = array())
    {
        if(empty($stories)) $stories = $this->post->stories;
        if(empty($stories)) return false;
        if(empty($products)) $products = $this->post->products;

        $this->loadModel('action');
        $versions      = $this->loadModel('story')->getVersions($stories);
        $linkedStories = $this->dao->select('*')->from(TABLE_PROJECTSTORY)->where('project')->eq($projectID)->orderBy('order_desc')->fetchPairs('story', 'order');
        $lastOrder     = reset($linkedStories);
        foreach($stories as $key => $storyID)
        {
            if(isset($linkedStories[$storyID])) continue;

            $productID = (int)$products[$storyID];
            $data = new stdclass();
            $data->project = $projectID;
            $data->product = $productID;
            $data->story   = $storyID;
            $data->version = $versions[$storyID];
            $data->order   = ++$lastOrder;
            $this->dao->insert(TABLE_PROJECTSTORY)->data($data)->exec();
            $this->story->setStage($storyID);
            $this->action->create('story', $storyID, 'linked2project', '', $projectID);
        }
    }

    /**
     * Link all stories by project.
     *
     * @param $projectID
     */
    public function linkStories($projectID)
    {
        $plans = $this->dao->select('plan,product')->from(TABLE_PROJECTPRODUCT)
            ->where('project')->eq($projectID)
            ->fetchPairs('plan', 'product');

        $planStories  = array();
        $planProducts = array();
        if(!empty($plans))
        {
            foreach($plans as $planID => $productID)
            {
                $planStory = $this->loadModel('story')->getPlanStories($planID);
                if(!empty($planStory))
                {
                    $count = 0;
                    foreach($planStory as $id => $story) 
                    {
                        if($story->status == 'draft') 
                        {
                            $count++;
                            unset($planStory[$id]);
                            continue;
                        }
                        $planProducts[$story->id] = $story->product;
                    }
                    $planStories = array_merge($planStories, array_keys($planStory));
                }
            }
        }
        $this->linkStory($projectID, $planStories, $planProducts);
        if($count != 0) echo js::alert(sprintf($this->lang->project->haveDraft, $count)) . js::locate(helper::createLink('project', 'create', "projectID=$projectID"));
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

        $order  = 1;
        $storys = $this->dao->select('*')->from(TABLE_PROJECTSTORY)->where('project')->eq($projectID)->orderBy('order')->fetchAll();
        foreach($storys as $projectstory)
        {
            if($projectstory->order != $order) $this->dao->update(TABLE_PROJECTSTORY)->set('`order`')->eq($order)->where('project')->eq($projectID)->andWhere('story')->eq($projectstory->story)->exec();
            $order++;
        }

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
        if(defined('TUTORIAL')) return $this->loadModel('tutorial')->getTeamMembers();
        return $this->dao->select("t1.*, t1.hours * t1.days AS totalHours, if(t2.deleted='0', t2.realname, t1.account) as realname")->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account = t2.account')
            ->where('t1.root')->eq((int)$projectID)
            ->andWhere('t1.type')->eq('project')
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
        if(defined('TUTORIAL')) return $this->loadModel('tutorial')->getTeamMembersPairs();
        $users = $this->dao->select('t1.account, t2.realname')->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account = t2.account')
            ->where('t1.root')->eq((int)$projectID)
            ->andWhere('t1.type')->eq('project')
            ->beginIF($params == 'nodeleted' or empty($this->config->user->showDeleted))
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
        return $this->dao->select('t1.root, t2.name as projectName')
            ->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.root = t2.id')
            ->where('t1.account')->eq($account)
            ->andWhere('t1.root')->ne($currentProject)
            ->andWhere('t1.type')->eq('project')
            ->groupBy('t1.root')
            ->orderBy('t1.root DESC')
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
            ->where('root')->eq($project)
            ->andWhere('type')->eq('project')
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
        $data = (array)fixer::input('post')->get();
        extract($data);

        $accounts = array_unique($accounts);
        foreach($accounts as $key => $account)
        {
            if(empty($account)) continue;

            $member = new stdclass();
            $member->role    = $roles[$key];
            $member->days    = $days[$key];
            $member->hours   = $hours[$key];
            $member->limited = $limited[$key];

            $mode = $modes[$key];
            if($mode == 'update')
            {
                $this->dao->update(TABLE_TEAM)
                    ->data($member)
                    ->where('root')->eq((int)$projectID)
                    ->andWhere('type')->eq('project')
                    ->andWhere('account')->eq($account)
                    ->exec();
            }
            else
            {
                $member->root    = (int)$projectID;
                $member->account = $account;
                $member->join    = helper::today();
                $member->type    = 'project';
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
        $this->dao->delete()->from(TABLE_TEAM)->where('root')->eq((int)$projectID)->andWhere('type')->eq('project')->andWhere('account')->eq($account)->exec();
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
            ->where("end")->ge($today)
            ->andWhere('type')->ne('ops')
            ->andWhere('status')->notin('done,closed,suspended')
            ->fetchPairs();
        if(!$projects) return $burns;

        $burns = $this->dao->select("project, '$today' AS date, sum(estimate) AS `estimate`, sum(`left`) AS `left`, SUM(consumed) AS `consumed`")
            ->from(TABLE_TASK)
            ->where('project')->in(array_keys($projects))
            ->andWhere('deleted')->eq('0')
            ->andWhere('parent')->eq('0')
            ->andWhere('status')->ne('cancel')
            ->groupBy('project')
            ->fetchAll('project');
        $closedLefts = $this->dao->select("project, sum(`left`) AS `left`")->from(TABLE_TASK)
            ->where('project')->in(array_keys($projects))
            ->andWhere('deleted')->eq('0')
            ->andWhere('parent')->eq('0')
            ->andWhere('status')->eq('closed')
            ->groupBy('project')
            ->fetchAll('project');

        foreach($burns as $projectID => $burn)
        {
            if(isset($closedLefts[$projectID]))
            {
                $closedLeft  = $closedLefts[$projectID];
                $burn->left -= (int)$closedLeft->left;
            }

            $this->dao->replace(TABLE_BURN)->data($burn)->exec();
            $burn->projectName = $projects[$burn->project];
        }
        return $burns;
    }

    /**
     * Fix burn for first day.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function fixFirst($projectID)
    {
        $project  = $this->getById($projectID);
        $burn     = $this->dao->select('*')->from(TABLE_BURN)->where('project')->eq($projectID)->andWhere('date')->eq($project->begin)->fetch();
        $withLeft = $this->post->withLeft ? $this->post->withLeft : 0;

        $data = fixer::input('post')
            ->add('project', $projectID)
            ->add('date', $project->begin)
            ->add('left', $withLeft ? $this->post->estimate : $burn->left)
            ->add('consumed', empty($burn) ? 0 : $burn->consumed)
            ->remove('withLeft')
            ->get();
        if(!is_numeric($data->estimate)) return false;

        $this->dao->replace(TABLE_BURN)->data($data)->exec();
    }

    /**
     * Get burn data for flot
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getBurnDataFlot($projectID = 0)
    {
        /* Get project and burn counts. */
        $project    = $this->getById($projectID);

        /* If the burnCounts > $itemCounts, get the latest $itemCounts records. */
        $sets = $this->dao->select('date AS name, `left` AS value, estimate')->from(TABLE_BURN)->where('project')->eq((int)$projectID)->orderBy('date DESC')->fetchAll('name');

        $count    = 0;
        $burnData = array();
        foreach($sets as $date => $set)
        {
            if($date < $project->begin) continue;
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
        if($end != '0000-00-00')
        {
            $period = helper::diffDate($end, $begin) + 1;
            $counts = $period > $itemCounts ? $itemCounts : $period;
        }
        else
        {
            $counts = $itemCounts;
            $period = $itemCounts;
            $end    = date(DT_DATE1, strtotime("+$counts days", strtotime($begin)));
        }

        $current  = $begin;
        $endTime  = strtotime($end);
        $preValue = 0;
        $todayTag = 0;

        foreach($sets as $date => $set)
        {
            if($begin > $date) unset($sets[$date]);
        }

        for($i = 0; $i < $period; $i++)
        {
            $currentTime = strtotime($current);
            if($currentTime > $endTime) break;
            if(isset($sets[$current])) $preValue = $sets[$current]->value;
            if($currentTime > time() and !$todayTag)
            {
                $todayTag = $i + 1;
                break;
            }

            if(!isset($sets[$current]) and $mode == 'noempty')
            {
                $sets[$current]  = new stdclass();
                $sets[$current]->name  = $current;
                $sets[$current]->value = $preValue;
            }
            $nextDay = date(DT_DATE1, $currentTime + 24 * 3600);
            $current = $nextDay;
        }
        ksort($sets);

        if(count($sets) <= $counts) return $sets;
        if($endTime <= time()) return array_slice($sets, -$counts, $counts);
        if($todayTag <= $counts) return array_slice($sets, 0, $counts);
        if($todayTag > $counts) return array_slice($sets, $todayTag - $counts, $counts);
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

        $tasks = $this->dao->select('t1.*, t2.id AS storyID, t2.title AS storyTitle, t2.product, t2.branch, t2.version AS latestStoryVersion, t2.status AS storyStatus, t3.realname AS assignedToRealName')
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

            if($task->status != 'cancel' and $task->status != 'closed') $totalLeft += $task->left;

            $statusVar = 'status' . ucfirst($task->status);
            $$statusVar ++;
            $taskSum ++;
        }

        return sprintf($this->lang->project->taskSummary, $taskSum, $statusWait, $statusDoing, $totalEstimate, round($totalConsumed, 1), round($totalLeft, 1));
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
        if($action == 'close')    return $project->status != 'closed';
        if($action == 'suspend')  return $project->status == 'wait' or $project->status == 'doing';
        if($action == 'putoff')   return $project->status == 'wait' or $project->status == 'doing';
        if($action == 'activate') return $project->status == 'suspended' or $project->status == 'closed';

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
        if($module == 'task' and ($method == 'view' || $method == 'edit' || $method == 'batchedit'))
        {
            $module = 'project';
            $method = 'task';
        }
        if($module == 'build' and ($method == 'edit' || $method= 'view'))
        {
            $module = 'project';
            $method = 'build';
        }

        if($module == 'project' and $method == 'create') return;
        if($extra != '')
        {
            $link = helper::createLink($module, $method, "projectID=%s&type=$extra");
        }
        elseif($module == 'project' && ($method == 'index' or $method == 'all'))
        {
            $link = helper::createLink($module, 'task', "projectID=%s");
        }
        else
        {
            $link = helper::createLink($module, $method, "projectID=%s");
        }

        if($module == 'doc') $link = helper::createLink('doc', 'objectLibs', "type=project&objectID=%s&from=project");
        return $link;
    }

    /**
     * Get no weekend date
     *
     * @param  string     $begin
     * @param  string     $end
     * @param  string     $type
     * @param  string|int $interval
     * @param  string     $format
     * @access public
     * @return array
     */
    public function getDateList($begin, $end, $type, $interval = '', $format = 'm/d/Y')
    {
        $begin = strtotime($begin);
        $end   = strtotime($end);

        $beginWeekDay = date('w', $begin);
        $days = ($end - $begin) / 3600 / 24;
        if($type == 'noweekend')
        {
            $allDays = $days;
            $weekDay = $beginWeekDay;
            for($i = 0; $i < $allDays; $i++, $weekDay++)
            {
                $weekDay = $weekDay % 7;
                if(($this->config->project->weekend == 2 and $weekDay == 6) or $weekDay == 0) $days--;
            }
        }

        if(!$interval) $interval = floor($days / $this->config->project->maxBurnDay);

        $dateList = array();
        $spaces   = (int)$interval;
        $counter  = $spaces;
        $weekDay  = $beginWeekDay;
        for($date = $begin; $date <= $end; $date += 24 * 3600, $weekDay++)
        {
            /* Remove weekend when type is noweekend.*/
            if($type == 'noweekend')
            {
                $weekDay = $weekDay % 7;
                if(($this->config->project->weekend == 2 and $weekDay == 6) or $weekDay == 0) continue;
            }

            $counter ++;
            if($counter <= $spaces) continue;

            $counter    = 0;
            $dateList[] = date($format, $date);
        }

        return array($dateList, $interval);
    }

    /**
     * Get total estimate.
     *
     * @param  int    $projectID
     * @access public
     * @return float
     */
    public function getTotalEstimate($projectID)
    {
        $estimate = $this->dao->select('SUM(estimate) as estimate')->from(TABLE_TASK)->where('deleted')->eq('0')->andWhere('project')->eq($projectID)->fetch('estimate');
        return round($estimate);
    }

    /**
     * Check the privilege.
     *
     * @param  object    $project
     * @access public
     * @return bool
     */
    public function getLimitedProject()
    {
        /* If is admin, return true. */
        if($this->app->user->admin) return true;

        /* Get all teams of all projects and group by projects, save it as static. */
        $projects = $this->dao->select('root, limited')->from(TABLE_TEAM)->where('type')->eq('project')->andWhere('account')->eq($this->app->user->account)->andWhere('limited')->eq('yes')->orderBy('root asc')->fetchPairs('root', 'root');
        $_SESSION['limitedProjects'] = join(',', $projects);
    }

    /**
     * Fix order.
     *
     * @access public
     * @return void
     */
    public function fixOrder()
    {
        $projects = $this->dao->select('id,`order`')->from(TABLE_PROJECT)->orderBy('order')->fetchPairs('id', 'order');

        $i = 0;
        foreach($projects as $id => $order)
        {
            $i++;
            $newOrder = $i * 5;
            if($order == $newOrder) continue;
            $this->dao->update(TABLE_PROJECT)->set('`order`')->eq($newOrder)->where('id')->eq($id)->exec();
        }
    }

    /**
     * Get branches of project.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getProjectBranches($projectID)
    {
        $productBranchPairs = $this->dao->select('product, branch')->from(TABLE_PROJECTPRODUCT)
            ->where('project')->eq($projectID)
            ->fetchPairs();
        $branches = $this->loadModel('branch')->getByProducts(array_keys($productBranchPairs));
        foreach($productBranchPairs as $product => $branch)
        {
            if($branch == 0 and isset($branches[$product])) $productBranchPairs[$product] = join(',', array_keys($branches[$product]));
        }

        return $productBranchPairs;
    }

    /**
     * Build bug search form.
     *
     * @param  int    $products
     * @param  int    $queryID
     * @param  int    $actionURL
     * @access public
     * @return void
     */
    public function buildBugSearchForm($products, $queryID, $actionURL)
    {
        $modules = array();
        $builds  = array('' => '', 'trunk' => $this->lang->trunk);
        foreach($products as $product)
        {
            $productModules = $this->loadModel('tree')->getOptionMenu($product->id);
            $productBuilds  = $this->loadModel('build')->getProductBuildPairs($product->id, 0, $params = 'noempty|notrunk');
            foreach($productModules as $moduleID => $moduleName)
            {
                $modules[$moduleID] = ((count($products) >= 2 and $moduleID) ? $product->name : '') . $moduleName;
            }
            foreach($productBuilds as $buildID => $buildName)
            {
                $builds[$buildID] = ((count($products) >= 2 and $buildID) ? $product->name . '/' : '') . $buildName;
            }
        }

        $branchGroups = $this->loadModel('branch')->getByProducts(array_keys($products), 'noempty');
        $branchPairs  = array();
        $productType  = 'normal';
        $productNum   = count($products);
        $productPairs = array(0 => '');
        foreach($products as $product)
        {
            $productPairs[$product->id] = $product->name;
            if($product->type != 'normal')
            {
                $productType = $product->type;
                if($product->branch)
                {
                    $branchPairs[$product->branch] = (count($products) > 1 ? $product->name . '/' : '') . $branchGroups[$product->id][$product->branch];
                }
                else
                {
                    $productBranches = isset($branchGroups[$product->id]) ? $branchGroups[$product->id] : array(0);
                    if(count($products) > 1)
                    {
                        foreach($productBranches as $branchID => $branchName) $productBranches[$branchID] = $product->name . '/' . $branchName;
                    }
                    $branchPairs += $productBranches;
                }
            }
        }

        $this->config->bug->search['module']    = 'projectBug';
        $this->config->bug->search['actionURL'] = $actionURL;
        $this->config->bug->search['queryID']   = $queryID;
        unset($this->config->bug->search['fields']['project']);
        $this->config->bug->search['params']['product']['values']       = $productPairs + array('all' => $this->lang->product->allProductsOfProject);
        $this->config->bug->search['params']['plan']['values']          = $this->loadModel('productplan')->getForProducts($products);
        $this->config->bug->search['params']['module']['values']        = $modules;
        $this->config->bug->search['params']['openedBuild']['values']   = $builds;
        $this->config->bug->search['params']['resolvedBuild']['values'] = $this->config->bug->search['params']['openedBuild']['values'];
        if($productType == 'normal')
        {
            unset($this->config->bug->search['fields']['branch']);
            unset($this->config->bug->search['params']['branch']);
        }
        else
        {
            $this->config->bug->search['fields']['branch']           = sprintf($this->lang->product->branch, $this->lang->product->branchName[$productType]);
            $this->config->bug->search['params']['branch']['values'] = array('' => '') + $branchPairs;
        }
        $this->config->bug->search['params']['status'] = array('operator' => '=', 'control' => 'select', 'values' => $this->lang->bug->statusList);

        $this->loadModel('search')->setSearchParams($this->config->bug->search);
    }

    /**
     * Build task search form.
     *
     * @param  int    $projectID
     * @param  array  $projects
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return void
     */
    public function buildTaskSearchForm($projectID, $projects, $queryID, $actionURL)
    {
        $this->config->project->search['actionURL'] = $actionURL;
        $this->config->project->search['queryID']   = $queryID;
        $this->config->project->search['params']['project']['values'] = array(''=>'', $projectID => $projects[$projectID], 'all' => $this->lang->project->allProject);
        $this->config->project->search['params']['module']['values']  = $this->loadModel('tree')->getTaskOptionMenu($projectID, $startModuleID = 0);

        $this->loadModel('search')->setSearchParams($this->config->project->search);
    }

    /**
     * Get Kanban tasks
     *
     * @param  int    $projectID
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return void
     */
    public function getKanbanTasks($projectID, $orderBy = 'status_asc, id_desc', $pager = null)
    {
        $parents = $this->dao->select('parent')->from(TABLE_TASK)
            ->where('project')->eq((int)$projectID)
            ->andWhere('deleted')->eq(0)
            ->andWhere('parent')->ne(0)
            ->fetchPairs('parent', 'parent');

        $tasks = $this->dao->select('t1.*, t2.id AS storyID, t2.title AS storyTitle, t2.version AS latestStoryVersion, t2.status AS storyStatus, t3.realname AS assignedToRealName')
            ->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->leftJoin(TABLE_USER)->alias('t3')->on('t1.assignedTo = t3.account')
            ->where('t1.project')->eq((int)$projectID)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t1.id')->notin($parents)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'task');

        if($tasks) return $this->loadModel('task')->processTasks($tasks);
        return array();
    }

    /**
     * Get kanban group data.
     *
     * @param  array  $tasks
     * @param  array  $bugs
     * @access public
     * @return array
     */
    public function getKanbanGroupData($stories, $tasks, $bugs, $type = 'story')
    {
        $kanbanGroup = array();
        if($type == 'story') $kanbanGroup = $stories;

        foreach($tasks as $task)
        {
            $groupKey = $type == 'story' ? $task->storyID : $task->$type;

            $status   = $task->status;
            if(!empty($groupKey) and (($type == 'story' and isset($stories[$groupKey])) or $type != 'story'))
            {
                if(!isset($kanbanGroup[$groupKey])) $kanbanGroup[$groupKey] = new stdclass();
                $kanbanGroup[$groupKey]->tasks[$status][] = $task;
            }
            else
            {
                $noKeyTasks[$status][] = $task;
            }
        }

        foreach($bugs as $bug)
        {
            $groupKey = $type == 'finishedBy' ? $bug->resolvedBy : $bug->$type;

            $status  = $bug->status;
            $status  = $status == 'active' ? 'wait' : ($status == 'resolved' ? ($bug->resolution == 'postponed' ? 'cancel' : 'done') : $status);
            if(!empty($groupKey) and (($type == 'story' and isset($stories[$groupKey])) or $type != 'story'))
            {
                if(!isset($kanbanGroup[$groupKey])) $kanbanGroup[$groupKey] = new stdclass();
                $kanbanGroup[$groupKey]->bugs[$status][] = $bug;
            }
            else
            {
                $noKeyBugs[$status][] = $bug;
            }
        }

        $kanbanGroup['nokey'] = new stdclass();
        if(isset($noKeyTasks)) $kanbanGroup['nokey']->tasks = $noKeyTasks;
        if(isset($noKeyBugs))  $kanbanGroup['nokey']->bugs = $noKeyBugs;

        return $kanbanGroup;
    }

    /**
     * Save Kanban Data.
     *
     * @param  int    $projectID
     * @param  array  $kanbanDatas
     * @access public
     * @return void
     */
    public function saveKanbanData($projectID, $kanbanDatas)
    {
        $data = array();
        foreach($kanbanDatas as $type => $kanbanData) $data[$type] = array_keys($kanbanData);
        $this->loadModel('setting')->setItem("null.project.kanban.project$projectID", json_encode($data));

    }

    /**
     * Get Prev Kanban.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getPrevKanban($projectID)
    {
        $prevKanbans = $this->loadModel('setting')->getItem("owner=null&module=project&section=kanban&key=project$projectID");
        return json_decode($prevKanbans, true);
    }

    /**
     * Get kanban setting.
     *
     * @param  int    $projectID
     * @access public
     * @return object
     */
    public function getKanbanSetting($projectID)
    {
        $allCols    = '1';
        $showOption = '0';
        if(isset($this->config->project->kanbanSetting->allCols)) $allCols = $this->config->project->kanbanSetting->allCols;

        $colorList = $this->config->project->kanbanSetting->colorList;
        if(!is_array($colorList)) $colorList = json_decode($colorList, true);

        $kanbanSetting = new stdclass();
        $kanbanSetting->allCols    = $allCols;
        $kanbanSetting->showOption = $showOption;
        $kanbanSetting->colorList  = $colorList;

        return $kanbanSetting;
    }

    /**
     * Build burn data.
     *
     * @param  int    $projectID
     * @param  array  $dateList
     * @param  string $type
     * @access public
     * @return array
     */
    public function buildBurnData($projectID, $dateList, $type)
    {
        $this->loadModel('report');

        $sets          = $this->getBurnDataFlot($projectID);
        $limitJSON     = '[]';
        $baselineJSON  = '[]';

        $firstBurn    = empty($sets) ? 0 : reset($sets);
        $firstTime    = !empty($firstBurn->estimate) ? $firstBurn->estimate : (!empty($firstBurn->value) ? $firstBurn->value : 0);
        $days         = count($dateList) - 1;
        $rate         = $firstTime / $days;
        $baselineJSON = '[';
        foreach($dateList as $i => $date) $baselineJSON .= round(($days - $i) * $rate, 1) . ',';
        $baselineJSON = rtrim($baselineJSON, ',') . ']';

        $chartData['labels']   = $this->report->convertFormat($dateList, 'j/n');
        $chartData['burnLine'] = $this->report->createSingleJSON($sets, $dateList);
        $chartData['baseLine'] = $baselineJSON;

        return $chartData;
    }

    /**
     * Fill tasks in tree.
     * @param  object $tree
     * @param  int    $projectID
     * @access public
     * @return object
     */
    public function fillTasksInTree($node, $projectID)
    {
        $node = (object)$node;
        static $storyGroups, $taskGroups;
        if(empty($storyGroups))
        {
            $stories = $this->dao->select('t2.*, t1.version as taskVersion')->from(TABLE_PROJECTSTORY)->alias('t1')
                ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
                ->where('t1.project')->eq((int)$projectID)
                ->andWhere('t2.deleted')->eq(0)
                ->orderBy('t1.`order`_desc')
                ->fetchAll();
            $storyGroups = array();
            foreach($stories as $story) $storyGroups[$story->product][$story->module][$story->id] = $story;
        }
        if(empty($taskGroups))
        {
            $tasks = $this->dao->select('*')->from(TABLE_TASK)
                ->where('project')->eq((int)$projectID)
                ->andWhere('deleted')->eq(0)
                ->andWhere('parent')->eq(0)
                ->orderBy('id_desc')
                ->fetchAll();
            $childTasks = $this->dao->select('*')->from(TABLE_TASK)
                ->where('project')->eq((int)$projectID)
                ->andWhere('deleted')->eq(0)
                ->andWhere('parent')->ne(0)
                ->orderBy('id_desc')
                ->fetchGroup('parent');
            $taskGroups = array();
            foreach($tasks as $task)
            {
                $taskGroups[$task->module][$task->story][$task->id] = $task;
                if(!empty($childTasks[$task->id]))
                {
                    $taskGroups[$task->module][$task->story][$task->id]->children = $childTasks[$task->id];
                }
            }
        }

        if(!empty($node->children))
        {
            foreach($node->children as $i => $child)
            {
                $subNode = $this->fillTasksInTree($child, $projectID);
                /* Remove no children node. */
                if($subNode->type != 'story' and $subNode->type != 'task' and empty($subNode->children))
                {
                    unset($node->children[$i]);
                }
                else
                {
                    $node->children[$i] = $subNode;
                }
            }
        }

        if(!isset($node->id))$node->id = 0;
        if($node->type == 'story')
        {
            $node->type = 'module';
            $stories = isset($storyGroups[$node->root][$node->id]) ? $storyGroups[$node->root][$node->id] : array();
            foreach($stories as $story)
            {
                $storyItem = new stdclass();
                $storyItem->type          = 'story';
                $storyItem->id            = 'story' . $story->id;
                $storyItem->title         = $story->title;
                $storyItem->color         = $story->color;
                $storyItem->pri           = $story->pri;
                $storyItem->storyId       = $story->id;
                $storyItem->openedBy      = $story->openedBy;
                $storyItem->assignedTo    = $story->assignedTo;
                $storyItem->url           = helper::createLink('story', 'view', "storyID=$story->id&version=$story->version&from=project&param=$projectID");
                $storyItem->taskCreateUrl = helper::createLink('task', 'batchCreate', "projectID={$projectID}&story={$story->id}");

                $storyTasks = isset($taskGroups[$node->id][$story->id]) ? $taskGroups[$node->id][$story->id] : array();
                if(!empty($storyTasks))
                {
                    $taskItems             = $this->formatTasksForTree($storyTasks, $story);
                    $storyItem->tasksCount = count($taskItems);
                    $storyItem->children   = $taskItems;
                }

                $node->children[] = $storyItem;
            }

            /* Append for task of no story and node is not root. */
            if($node->id and isset($taskGroups[$node->id][0]))
            {
                $taskItems = $this->formatTasksForTree($taskGroups[$node->id][0]);
                $node->tasksCount = count($taskItems);
                foreach($taskItems as $taskItem) $node->children[] = $taskItem;
            }
        }
        elseif($node->type == 'task')
        {
            $node->type       = 'module';
            $node->tasksCount = 0;
            if(isset($taskGroups[$node->id]))
            {
                foreach($taskGroups[$node->id] as $tasks)
                {
                    $taskItems = $this->formatTasksForTree($tasks);
                    $node->tasksCount += count($taskItems);
                    foreach($taskItems as $taskItem)
                    {
                        $node->children[$taskItem->id] = $taskItem;
                        if(!empty($tasks[$taskItem->id]->children))
                        {
                            $task = $this->formatTasksForTree($tasks[$taskItem->id]->children);
                            $node->children[$taskItem->id]->children=$task;
                            $node->tasksCount += count($task);
                        }
                    }
                }
                $node->children = array_values($node->children);
            }
        }
        elseif($node->type == 'product')
        {
            $node->title = $node->name;
            if(isset($node->children[0]) and empty($node->children[0]->children)) array_shift($node->children);
        }

        $node->actions = false;
        if(!empty($node->children)) $node->children = array_values($node->children);
        return $node;
    }

    /**
     * Format tasks for tree.
     *
     * @param  array  $tasks
     * @param  object $story
     * @access public
     * @return array
     */
    public function formatTasksForTree($tasks, $story = '')
    {
        static $users;
        if(empty($users)) $users = $this->loadModel('user')->getPairs('noletter');

        $taskItems = array();
        foreach($tasks as $task)
        {
            $taskItem = new stdclass();
            $taskItem->type         = 'task';
            $taskItem->id           = $task->id;
            $taskItem->title        = $task->name;
            $taskItem->color        = $task->color;
            $taskItem->pri          = (int)$task->pri;
            $taskItem->status       = $task->status;
            $taskItem->parent       = $task->parent;
            $taskItem->estimate     = $task->estimate;
            $taskItem->consumed     = $task->consumed;
            $taskItem->left         = $task->left;
            $taskItem->openedBy     = $users[$task->openedBy];
            $taskItem->assignedTo   = zget($users, $task->assignedTo);
            $taskItem->url          = helper::createLink('task', 'view', "task=$task->id");
            $taskItem->storyChanged = $story and $story->status == 'active' and $story->version > $story->taskVersion;

            $buttons  = '';
            $buttons .= common::buildIconButton('task', 'assignTo', "projectID=$task->project&taskID=$task->id", $task, 'list', '', '', 'iframe', true);
            $buttons .= common::buildIconButton('task', 'start',    "taskID=$task->id", $task, 'list', '', '', 'iframe', true);
            $buttons .= common::buildIconButton('task', 'recordEstimate', "taskID=$task->id", $task, 'list', 'time', '', 'iframe', true);

            if($taskItem->storyChanged)
            {
                $this->lang->task->confirmStoryChange = $this->lang->confirm;
                $buttons .= common::buildIconButton('task', 'confirmStoryChange', "taskid=$task->id", '', 'list', '', 'hiddenwin');
            }

            $buttons .= common::buildIconButton('task', 'finish',  "taskID=$task->id", $task, 'list', '', '', 'iframe', true);
            $buttons .= common::buildIconButton('task', 'close',   "taskID=$task->id", $task, 'list', '', '', 'iframe', true);
            $buttons .= common::buildIconButton('task', 'edit',    "taskID=$task->id", '', 'list');
            $taskItem->buttons = $buttons;
            $taskItem->actions = false;
            $taskItems[] = $taskItem;
        }

        return $taskItems;
    }

    /**
     * Get projects tree data
     * @param  int     $projectID
     * @access public
     * @return array
     */
    public function getProjectTree($projectID)
    {
        $fullTrees = $this->loadModel('tree')->getTaskStructure($projectID, 0, $manage = false);
        array_unshift($fullTrees, array('id' => 0, 'name' => '/', 'type' => 'task', 'actions' => false, 'root' => $projectID));
        foreach($fullTrees as $i => $tree)
        {
            $tree = (object)$tree;
            if($tree->type == 'product') array_unshift($tree->children, array('id' => 0, 'name' => '/', 'type' => 'story', 'actions' => false, 'root' => $tree->root));
            $fullTree = $this->fillTasksInTree($tree, $projectID);
            if(empty($fullTree->children))
            {
                unset($fullTrees[$i]);
            }
            else
            {
                $fullTrees[$i] = $fullTree;
            }
        }
        if(isset($fullTrees[0]) and empty($fullTrees[0]->children)) array_shift($fullTrees);
        return array_values($fullTrees);
    }

    /**
     * Get plans by $productID.
     *
     * @param int|array $productID
     *
     * @return mixed
     */
    public function getPlans($products)
    {
        $this->loadModel('productplan');
        $productPlans = array();
        foreach($products as $productID => $product)
        {
            $productPlans[$productID] = $this->productplan->getPairs($product->id, isset($product->branch) ? $product->branch : '');
        }
        return $productPlans;
    }

    /**
     * Print html for tree.
     *
     * @param object $trees
     * @access pubic
     * @return string
     */
    public function printTree($trees)
    {
        $html = '';
        foreach($trees as $tree)
        {
            if(is_array($tree)) $tree = (object)$tree;
            switch($tree->type)
            {
                case 'module':
                    $this->app->loadLang('tree');
                    $html .= "<li class='item-module'>";
                    $html .= '<a class="tree-toggle"><span class="label label-type">' . (empty($tree->parent) ? $this->lang->tree->module : $this->lang->tree->child) . "</span><span class='title' title='{$tree->name}'>" . $tree->name . '</span></a>';
                    break;
                case 'task':
                    $link = helper::createLink('project', 'treeTask', "taskID={$tree->id}");
                    $html .= '<li class="item-task">';
                    $html .= '<a class="tree-link" href="' . $link . '"><span class="label label-id">' . $tree->id . '</span><span class="label label-type">' . (empty($tree->parent) ? $this->lang->task->common : $this->lang->task->children) . "</span><span class='title' title='{$tree->title}'>" . $tree->title . '</span> <span class="user"><i class="icon icon-person"></i> ' . (empty($tree->assignedTo) ? $tree->openedBy : $tree->assignedTo) . '</span></a>';
                    break;
                case 'product':
                    $this->app->loadLang('product');
                    $html .= '<li class="item-product">';
                    $html .= '<a class="tree-toggle"><span class="label label-type">' . $this->lang->productCommon . "</span><span class='title' title='{$tree->name}'>" . $tree->name . '</span></a>';
                    break;
                case 'story':
                    $this->app->loadLang('story');
                    $link = helper::createLink('project', 'treeStory', "storyID={$tree->storyId}");
                    $html .= '<li class="item-story">';
                    $html .= '<a class="tree-link" href="' . $link . '"><span class="label label-id">' . $tree->storyId . '</span><span class="label label-type">' . $this->lang->story->common . "</span><span class='title' title='{$tree->title}'>" . $tree->title . '</span> <span class="user"><i class="icon icon-person"></i> ' . (empty($tree->assignedTo) ? $tree->openedBy : $tree->assignedTo) . '</span></a>';
                    break;
            }
            if(isset($tree->children))
            {
                $html .= '<ul>';
                $html .= $this->printTree($tree->children);
                $html .= '</ul>';
            }
            $html .= '</li>';
        }
        return $html;
    }
}
