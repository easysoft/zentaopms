<?php
/**
 * The model file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     execution
 * @version     $Id: model.php 5118 2013-07-12 07:41:41Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
class executionModel extends model
{
    /* The members every linking. */
    const LINK_MEMBERS_ONE_TIME = 20;

    /**
     * Check the privilege.
     *
     * @param  int    $executionID
     * @access public
     * @return bool
     */
    public function checkPriv($executionID)
    {
        return !empty($executionID) && ($this->app->user->admin || (strpos(",{$this->app->user->view->sprints},", ",{$executionID},") !== false));
    }

    /**
     * Show accessDenied response.
     *
     * @access private
     * @return void
     */
    public function accessDenied()
    {
        if(defined('TUTORIAL')) return true;

        echo(js::alert($this->lang->execution->accessDenied));

        if(!$this->server->http_referer) return print(js::locate(helper::createLink('execution', 'all')));

        $loginLink = $this->config->requestType == 'GET' ? "?{$this->config->moduleVar}=user&{$this->config->methodVar}=login" : "user{$this->config->requestFix}login";
        if(strpos($this->server->http_referer, $loginLink) !== false) return print(js::locate(helper::createLink('execution', 'all')));

        return print(js::locate(helper::createLink('execution', 'all')));
    }

    /**
     * Get features of execution.
     *
     * @param object $execution
     * @access public
     * @return array
     */
    public function getExecutionFeatures($execution)
    {
        $features = array('story' => true, 'task' => true, 'qa' => true, 'devops' => true, 'burn' => true, 'build' => true, 'other' => true, 'plan' => true);

        /* Unset story, bug, build and testtask if type is ops. */
        if($execution->lifetime == 'ops')
        {
            $features['story']  = false;
            $features['qa']     = false;
            $features['build']  = false;
            $features['burn']   = false;
        }
        elseif(!empty($execution->attribute))
        {
            $features['other'] = false;
            if(in_array($execution->attribute, array('request', 'design', 'review')))
            {
                $features['qa']     = false;
                $features['devops'] = false;
                $features['build']  = false;

                if(in_array($execution->attribute, array('request', 'review')))
                {
                    $features['story'] = false;
                    $features['plan'] = false;
                }
            }
        }

        if(isset($execution->projectInfo) and in_array($execution->projectInfo->model, array('waterfall', 'kanban', 'waterfallplus')) and empty($execution->projectInfo->hasProduct))
        {
            $features['plan'] = false;
        }

        return $features;
    }

    /**
     * Set menu.
     *
     * @param  int    $executionID
     * @param  int    $buildID
     * @param  string $extra
     * @access public
     * @return void
     */
    public function setMenu($executionID, $buildID = 0, $extra = '')
    {
        $execution = $this->getByID($executionID);
        if(!$execution) return;

        if($execution and $execution->type == 'kanban')
        {
            global $lang;
            $lang->executionCommon = $lang->execution->kanban;
            include $this->app->getModulePath('', 'execution') . 'lang/' . $this->app->getClientLang() . '.php';

            $this->lang->execution->menu           = new stdclass();
            $this->lang->execution->menu->kanban   = array('link' => "{$this->lang->kanban->common}|execution|kanban|executionID=%s", 'subModule' => 'task');
            $this->lang->execution->menu->CFD      = array('link' => "{$this->lang->execution->CFD}|execution|cfd|executionID=%s");
            $this->lang->execution->menu->build    = array('link' => "{$this->lang->build->common}|execution|build|executionID=%s");
            $this->lang->execution->menu->settings = array('link' => "{$this->lang->settings}|execution|view|executionID=%s", 'subModule' => 'personnel', 'alias' => 'edit,manageproducts,team,whitelist,addwhitelist,managemembers', 'class' => 'dropdown dropdown-hover');
            $this->lang->execution->dividerMenu    = '';

            $this->lang->execution->menu->settings['subMenu']            = new stdclass();
            $this->lang->execution->menu->settings['subMenu']->view      = array('link' => "{$this->lang->overview}|execution|view|executionID=%s", 'subModule' => 'view', 'alias' => 'edit,start,suspend,putoff,close');
            $this->lang->execution->menu->settings['subMenu']->products  = array('link' => "{$this->lang->productCommon}|execution|manageproducts|executionID=%s");
            $this->lang->execution->menu->settings['subMenu']->team      = array('link' => "{$this->lang->team->common}|execution|team|executionID=%s", 'alias' => 'managemembers');
            $this->lang->execution->menu->settings['subMenu']->whitelist = array('link' => "{$this->lang->whitelist}|execution|whitelist|executionID=%s", 'subModule' => 'personnel', 'alias' => 'addwhitelist');
        }

        $project = $this->loadModel('project')->getByID($execution->project);

        if($execution->type == 'stage' or (!empty($project) and $project->model == 'waterfallplus')) unset($this->lang->execution->menu->settings['subMenu']->products);

        if(!$this->app->user->admin and strpos(",{$this->app->user->view->sprints},", ",$executionID,") === false and !defined('TUTORIAL') and $executionID != 0) return print(js::error($this->lang->execution->accessDenied) . js::locate('back'));

        $executions = $this->fetchPairs($execution->project, 'all');
        if(!$executionID and $this->session->execution) $executionID = $this->session->execution;
        if(!$executionID) $executionID = key($executions);
        if($execution->multiple and !isset($executions[$executionID])) $executionID = key($executions);
        if($execution->multiple and $executions and (!isset($executions[$executionID]) or !$this->checkPriv($executionID))) $this->accessDenied();
        $this->session->set('execution', $executionID, $this->app->tab);

        if($execution and $execution->type == 'stage')
        {
            global $lang;
            $this->app->loadLang('project');
            $lang->executionCommon = $lang->project->stage;
            include $this->app->getModulePath('', 'execution') . 'lang/' . $this->app->getClientLang() . '.php';
        }

        if(isset($execution->acl) and $execution->acl != 'private') unset($this->lang->execution->menu->settings['subMenu']->whitelist);

        /* Redjust menus. */
        $features = $this->getExecutionFeatures($execution);
        if(!$features['story'])  unset($this->lang->execution->menu->story);
        if(!$features['story'])  unset($this->lang->execution->menu->view['subMenu']->groupTask);
        if(!$features['story'])  unset($this->lang->execution->menu->view['subMenu']->tree);
        if(!$features['qa'])     unset($this->lang->execution->menu->qa);
        if(!$features['devops']) unset($this->lang->execution->menu->devops);
        if(!$features['build'])  unset($this->lang->execution->menu->build);
        if(!$features['burn'])   unset($this->lang->execution->menu->burn);
        if(!$features['other'])  unset($this->lang->execution->menu->other);
        if(!$features['story'] and $this->config->edition == 'open') unset($this->lang->execution->menu->view);

        $moduleName = $this->app->getModuleName();
        $methodName = $this->app->getMethodName();
        if($moduleName == 'repo' || $moduleName == 'mr')
        {
            $repoPairs = $this->loadModel('repo')->getRepoPairs('execution', $executionID);

            $showMR = false;
            if(common::hasPriv('mr', 'browse'))
            {
                foreach($repoPairs as $repoName)
                {
                    preg_match('/^\[(\w+)\]/', $repoName, $matches);
                    if(isset($matches[1]) && in_array($matches[1], $this->config->repo->gitServiceList)) $showMR = true;
                }
            }
            if(!$showMR) unset($this->lang->execution->menu->devops['subMenu']->mr);
            if(!$repoPairs || !common::hasPriv('repo', 'review')) unset($this->lang->execution->menu->devops['subMenu']->review);

            if(empty($this->lang->execution->menu->devops['subMenu']->mr) && empty($this->lang->execution->menu->devops['subMenu']->review)) unset($this->lang->execution->menu->devops['subMenu']);
        }

        if($this->cookie->executionMode == 'noclosed' and $execution and ($execution->status == 'done' or $execution->status == 'closed'))
        {
            setcookie('executionMode', 'all');
            $this->cookie->executionMode = 'all';
        }

        if(empty($execution->hasProduct)) unset($this->lang->execution->menu->settings['subMenu']->products);

        $this->lang->switcherMenu = $this->getSwitcher($executionID, $this->app->rawModule, $this->app->rawMethod);
        common::setMenuVars('execution', $executionID);

        $this->loadModel('project')->setNoMultipleMenu($executionID);

        if(isset($this->lang->execution->menu->storyGroup)) unset($this->lang->execution->menu->storyGroup);
        if(isset($this->lang->execution->menu->story['dropMenu']) and $methodName == 'storykanban')
        {
            $this->lang->execution->menu->story['link']            = str_replace(array($this->lang->common->story, 'story'), array($this->lang->SRCommon, 'storykanban'), $this->lang->execution->menu->story['link']);
            $this->lang->execution->menu->story['dropMenu']->story = str_replace('execution|story', 'execution|storykanban', $this->lang->execution->menu->story['dropMenu']->story);
        }
    }

    /**
     * Create the select code of executions.
     *
     * @param  array     $executions
     * @param  int       $executionID
     * @param  int       $buildID
     * @param  string    $currentModule
     * @param  string    $currentMethod
     * @param  string    $extra
     * @access public
     * @return string
     */
    public function select($executions, $executionID, $buildID, $currentModule, $currentMethod, $extra = '')
    {
        if(!$executions or !$executionID) return false;

        $isMobile = $this->app->viewType == 'mhtml';

        setCookie("lastExecution", $executionID, $this->config->cookieLife, $this->config->webRoot, '', false, true);
        $currentExecution = $this->getById($executionID);

        if(isset($currentExecution->type) and $currentExecution->type == 'program') return;

        if($currentExecution->project) $project = $this->loadModel('project')->getByID($currentExecution->project);

        if(isset($project) and $project->model == 'waterfall')
        {
            $productID   = $this->loadModel('product')->getProductIDByProject($project->id);
            $productName = $this->dao->findByID($productID)->from(TABLE_PRODUCT)->fetch('name');
            $currentExecution->name = $productName . '/' . $currentExecution->name;
        }

        $dropMenuLink = helper::createLink('execution', 'ajaxGetDropMenu', "executionID=$executionID&module=$currentModule&method=$currentMethod&extra=$extra");
        $currentExecutionName = '';
        if(isset($currentExecution->name)) $currentExecutionName = $currentExecution->name;

        $output  = "<div class='btn-group angle-btn'><div class='btn-group'><button data-toggle='dropdown' type='button' class='btn btn-limit' id='currentItem' title='{$currentExecutionName}'><span class='text'><i class='icon icon-{$this->lang->icons[$currentExecution->type]}'></i> {$currentExecutionName}</span> <span class='caret'></span></button><div id='dropMenu' class='dropdown-menu search-list' data-ride='dropmenu' data-url='$dropMenuLink'>";
        $output .= '<div class="input-control search-box has-icon-left has-icon-right search-example"><input type="search" class="form-control search-input" /><label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label><a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a></div>';
        $output .= "</div></div></div>";
        if($isMobile) $output  = "<a id='currentItem' href=\"javascript:showSearchMenu('execution', '$executionID', '$currentModule', '$currentMethod', '$extra')\"><span class='text'>{$currentExecution->name}</span> <span class='icon-caret-down'></span></a><div id='currentItemDropMenu' class='hidden affix enter-from-bottom layer'></div>";

        return $output;
    }

    /**
     * Get execution tree menu.
     *
     * @access public
     * @return void
     */
    public function tree()
    {
        $products     = $this->loadModel('product')->getPairs('nocode', 0);
        $productGroup = $this->getProductGroupList();
        $executionTree  = "<ul class='tree tree-lines'>";
        foreach($productGroup as $productID => $executions)
        {
            if(!isset($products[$productID]) and $productID != '')  continue;
            if(!isset($products[$productID]) and !count($executions)) continue;

            $productName  = isset($products[$productID]) ? $products[$productID] : $this->lang->execution->noProduct;

            $executionTree .= "<li>$productName<ul>";

            foreach($executions as $execution)
            {
                if($execution->status != 'done' or $execution->status != 'closed')
                {
                    $executionTree .= "<li>" . html::a(inlink('task', "executionID=$execution->id"), $execution->name, '', "id='execution$execution->id'") . "</li>";
                }
            }

            $hasDone = false;
            foreach($executions as $execution)
            {
                if($execution->status == 'done' or $execution->status == 'closed')
                {
                    $hasDone = true;
                    break;
                }
            }
            if($hasDone)
            {
                $executionTree .= "<li>{$this->lang->execution->selectGroup->done}<ul>";
                foreach($executions as $execution)
                {
                    if($execution->status == 'done' or $execution->status == 'closed')
                    {
                        $executionTree .= "<li>" . html::a(inlink('task', "executionID=$execution->id"), $execution->name, '', "id='execution$execution->id'") . "</li>";
                    }
                }
                $executionTree .= "</ul></li>";
            }

            $executionTree .= "</ul></li>";
        }

        $executionTree .= "</ul>";

        return $executionTree;
    }

    /**
     * Save the execution id user last visited to session.
     *
     * @param  int   $executionID
     * @param  array $executions
     * @access public
     * @return int
     */
    public function saveState($executionID, $executions)
    {
        if(defined('TUTORIAL')) return $executionID;

        /* When the cookie and session do not exist, get it from the database. */
        if(empty($executionID) and isset($this->config->execution->lastExecution) and isset($executions[$this->config->execution->lastExecution]))
        {
            $this->session->set('execution', $this->config->execution->lastExecution, $this->app->tab);
            $this->setProjectSession($this->session->execution);
            return $this->session->execution;
        }

        if($executionID == 0 and $this->cookie->lastExecution)
        {
            /* Execution link is execution-task. */
            $executionID = (int)$this->cookie->lastExecution;
            $executionID = in_array($executionID, array_keys($executions)) ? $executionID : key($executions);
        }

        if($executionID == 0 and $this->session->execution) $executionID = $this->session->execution;
        if($executionID == 0) $executionID = key($executions);

        $this->session->set('execution', (int)$executionID, $this->app->tab);

        if(!isset($executions[$executionID]))
        {
            $this->session->set('execution', key($executions), $this->app->tab);

            if($executionID)
            {
                $execution = $this->dao->select('*')->from(TABLE_EXECUTION)->where('id')->eq($executionID)->andWhere('type')->in('sprint,stage,kanban')->fetch();
                if(empty($execution)) return js::error($this->lang->notFound);
                if(strpos(",{$this->app->user->view->sprints},", ",{$executionID},") === false) $this->accessDenied();
            }
        }

        $this->setProjectSession($this->session->execution);
        return $this->session->execution;
    }

    /**
     * Set project into session.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function setProjectSession($executionID)
    {
        $execution = $this->getByID($executionID);
        if(!empty($execution)) $this->session->set('project', $execution->project, $this->app->tab);
    }

    /**
     * Create a execution.
     *
     * @param int $copyExecutionID
     *
     * @access public
     * @return bool|int
     */
    public function create($copyExecutionID = '')
    {
        $this->lang->execution->team = $this->lang->execution->teamname;

        if(empty($_POST['project']))
        {
            dao::$errors['message'][] = $this->lang->execution->projectNotEmpty;
            return false;
        }

        $this->checkBeginAndEndDate($_POST['project'], $_POST['begin'], $_POST['end']);
        if(dao::isError()) return false;

        if($_POST['products'])
        {
            $this->app->loadLang('project');
            $multipleProducts = $this->loadModel('product')->getMultiBranchPairs();
            foreach($_POST['products'] as $index => $productID)
            {
                if(isset($multipleProducts[$productID]) and !isset($_POST['branch'][$index]))
                {
                    dao::$errors[] = $this->lang->project->emptyBranch;
                    return false;
                }
            }
        }

        /* Determine whether to add a sprint or a stage according to the model of the execution. */
        $project = $this->loadModel('project')->getByID($_POST['project']);
        $type    = 'sprint';
        if($project) $type = zget($this->config->execution->modelList, $project->model, 'sprint');

        if($project->model == 'waterfall' or $project->model == 'waterfallplus')
        {
            $_POST['products'] = array_filter($_POST['products']);
            if(empty($_POST['products'])) dao::$errors['products0'] = $this->lang->project->errorNoProducts;
            if(isset($this->config->setPercent) and $this->config->setPercent == 1) $this->checkWorkload('create', $_POST['percent'], $project);
            if(dao::isError()) return false;
        }

        $this->config->execution->create->requiredFields .= ',project';

        /* Judge workdays is legitimate. */
        $workdays = helper::diffDate($_POST['end'], $_POST['begin']) + 1;
        if(isset($_POST['days']) and $_POST['days'] > $workdays)
        {
            dao::$errors['days'] = sprintf($this->lang->project->workdaysExceed, $workdays);
            return false;
        }

        /* Get the data from the post. */
        $sprint = fixer::input('post')
            ->setDefault('status', 'wait')
            ->setDefault('openedBy', $this->app->user->account)
            ->setDefault('openedDate', helper::now())
            ->setDefault('openedVersion', $this->config->version)
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', helper::now())
            ->setDefault('days', '0')
            ->setDefault('team', $this->post->name)
            ->setDefault('parent', $this->post->project)
            ->cleanINT('project')
            ->setIF($this->post->parent, 'parent', $this->post->parent)
            ->setIF($this->post->heightType == 'auto', 'displayCards', 0)
            ->setIF(!isset($_POST['whitelist']), 'whitelist', '')
            ->setIF($this->post->acl == 'open', 'whitelist', '')
            ->join('whitelist', ',')
            ->setDefault('type', $type)
            ->stripTags($this->config->execution->editor->create['id'], $this->config->allowedTags)
            ->remove('products, workDays, delta, branch, uid, plans, teams, teamMembers, contactListMenu, heightType')
            ->get();

        if(!empty($sprint->parent) and ($sprint->project == $sprint->parent))
        {
            $project = $this->loadModel('project')->getByID($sprint->parent);
            $sprint->hasProduct = $project->hasProduct;
        }

        if(isset($_POST['heightType']) and $this->post->heightType == 'custom')
        {
            if(!$this->loadModel('kanban')->checkDisplayCards($sprint->displayCards)) return;
        }

        /* Check the workload format and total. */
        if(!empty($sprint->percent) and isset($this->config->setPercent) and $this->config->setPercent == 1) $this->checkWorkload('create', $sprint->percent, $sprint->project);

        /* Set planDuration and realDuration. */
        if($this->config->edition == 'max' or $this->config->edition == 'ipd')
        {
            $sprint->planDuration = $this->loadModel('programplan')->getDuration($sprint->begin, $sprint->end);
            if(!empty($sprint->realBegan) and !empty($sprint->realEnd)) $sprint->realDuration = $this->loadModel('programplan')->getDuration($sprint->realBegan, $sprint->realEnd);
        }

        $sprint = $this->loadModel('file')->processImgURL($sprint, $this->config->execution->editor->create['id'], $this->post->uid);

        /* Redefines the language entries for the fields in the project table. */
        foreach(explode(',', $this->config->execution->create->requiredFields) as $field)
        {
            if(isset($this->lang->execution->$field)) $this->lang->project->$field = $this->lang->execution->$field;
        }

        /* Replace required language. */
        if($this->app->tab == 'project')
        {
            $this->lang->project->name = $this->lang->execution->name;
            $this->lang->project->code = $this->lang->execution->code;
        }
        else
        {
            $this->lang->project->name = $this->lang->execution->execName;
            $this->lang->project->code = $this->lang->execution->execCode;
        }

        $this->lang->error->unique = $this->lang->error->repeat;
        $sprintProject = isset($sprint->project) ? (int)$sprint->project : '0';
        $this->dao->insert(TABLE_EXECUTION)->data($sprint)
            ->autoCheck($skipFields = 'begin,end')
            ->batchcheck($this->config->execution->create->requiredFields, 'notempty')
            ->checkIF(!empty($sprint->name), 'name', 'unique', "`type` in ('sprint','stage', 'kanban') and `project` = $sprintProject and `deleted` = '0'")
            ->checkIF(!empty($sprint->code), 'code', 'unique', "`type` in ('sprint','stage', 'kanban') and `deleted` = '0'")
            ->checkIF($sprint->begin != '', 'begin', 'date')
            ->checkIF($sprint->end != '', 'end', 'date')
            ->checkIF($sprint->end != '', 'end', 'ge', $sprint->begin)
            ->checkFlow()
            ->exec();

        /* Add the creater to the team. */
        if(!dao::isError())
        {
            $executionID   = $this->dao->lastInsertId();
            $today         = helper::today();
            $teamMembers   = array();

            if((isset($project) and $project->model != 'kanban') or empty($project)) $this->loadModel('kanban')->createExecutionLane($executionID);

            /* Api create infinitus stages. */
            if(isset($sprint->parent) and ($sprint->parent != $sprint->project) and $sprint->type == 'stage')
            {
                $parent = $this->getByID($sprint->parent);
                $grade  = $parent->grade + 1;
                $path   = rtrim($parent->path, ',') . ",{$executionID}";
                $this->dao->update(TABLE_EXECUTION)->set('path')->eq($path)->set('grade')->eq($grade)->where('id')->eq($executionID)->exec();
            }

            /* Save order. */
            $this->dao->update(TABLE_EXECUTION)->set('`order`')->eq($executionID * 5)->where('id')->eq($executionID)->exec();
            $this->file->updateObjectID($this->post->uid, $executionID, 'execution');

            /* Update the path. */
            $this->setTreePath($executionID);

            $this->updateProducts($executionID);

            /* Set team of execution. */
            $members = isset($_POST['teamMembers']) ? $_POST['teamMembers'] : array();
            array_push($members, $sprint->PO, $sprint->QD, $sprint->PM, $sprint->RD, $sprint->openedBy);
            $members = array_unique($members);
            $roles   = $this->loadModel('user')->getUserRoles(array_values($members));
            foreach($members as $account)
            {
                if(empty($account)) continue;

                $member = new stdClass();
                $member->root    = $executionID;
                $member->type    = 'execution';
                $member->account = $account;
                $member->role    = zget($roles, $account, '');
                $member->join    = $today;
                $member->days    = $sprint->days;
                $member->hours   = $this->config->execution->defaultWorkhours;
                $this->dao->insert(TABLE_TEAM)->data($member)->exec();
                $teamMembers[$account] = $member;
            }
            $this->addProjectMembers($sprint->project, $teamMembers);

            /* Create doc lib. */
            $this->app->loadLang('doc');
            $lib = new stdclass();
            $lib->project   = $sprintProject;
            $lib->execution = $executionID;
            $lib->name      = $type == 'stage' ? str_replace($this->lang->executionCommon, $this->lang->project->stage, $this->lang->doclib->main['execution']) : $this->lang->doclib->main['execution'];
            $lib->type      = 'execution';
            $lib->main      = '1';
            $lib->acl       = 'default';
            $lib->addedBy   = $this->app->user->account;
            $lib->addedDate = helper::now();
            $this->dao->insert(TABLE_DOCLIB)->data($lib)->exec();

            $whitelist = explode(',', $sprint->whitelist);
            $this->loadModel('personnel')->updateWhitelist($whitelist, 'sprint', $executionID);
            if($sprint->acl != 'open') $this->updateUserView($executionID);

            if(!dao::isError()) $this->loadModel('score')->create('program', 'createguide', $executionID);
            return $executionID;
        }
    }

    /**
     * Update a execution.
     *
     * @param  int    $executionID
     * @access public
     * @return array|bool
     */
    public function update($executionID)
    {
        /* Convert executionID format and get oldExecution. */
        $executionID  = (int)$executionID;
        $oldExecution = $this->dao->findById($executionID)->from(TABLE_EXECUTION)->fetch();

        /* Judgment of required items. */
        if($oldExecution->type != 'stage' and $this->post->code == '' and isset($this->config->setCode) and $this->config->setCode == 1)
        {
            dao::$errors['code'] = sprintf($this->lang->error->notempty, $this->lang->execution->code);
            return false;
        }

        /* Judge workdays is legitimate. */
        $this->app->loadLang('project');
        $workdays = helper::diffDate($_POST['end'], $_POST['begin']) + 1;
        if(isset($_POST['days']) and $_POST['days'] > $workdays)
        {
            dao::$errors['days'] = sprintf($this->lang->project->workdaysExceed, $workdays);
            return false;
        }

        if($_POST['products'])
        {
            $this->app->loadLang('project');
            $multipleProducts = $this->loadModel('product')->getMultiBranchPairs();
            if(isset($_POST['branch']) and is_string($_POST['branch']) !== false) $_POST['branch'] = json_decode($_POST['branch'], true);
            foreach($_POST['products'] as $index => $productID)
            {
                if(isset($multipleProducts[$productID]) and !isset($_POST['branch'][$index]))
                {
                    dao::$errors[] = $this->lang->project->emptyBranch;
                    return false;
                }
            }
        }

        /* Get the data from the post. */
        $execution = fixer::input('post')
            ->add('id', $executionID)
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', helper::now())
            ->setIF($this->post->heightType == 'auto', 'displayCards', 0)
            ->setIF(helper::isZeroDate($this->post->begin), 'begin', '')
            ->setIF(helper::isZeroDate($this->post->end), 'end', '')
            ->setIF(!isset($_POST['whitelist']), 'whitelist', '')
            ->setIF($this->post->status == 'closed' and $oldExecution->status != 'closed', 'closedDate', helper::now())
            ->setIF($this->post->status == 'suspended' and $oldExecution->status != 'suspended', 'suspendedDate', helper::today())
            ->setIF($oldExecution->type == 'stage', 'project', $oldExecution->project)
            ->setDefault('days', '0')
            ->cleanINT('project')
            ->setDefault('team', $this->post->name)
            ->join('whitelist', ',')
            ->stripTags($this->config->execution->editor->edit['id'], $this->config->allowedTags)
            ->remove('products, branch, uid, plans, syncStories, contactListMenu, teamMembers, heightType')
            ->get();

        if(isset($_POST['heightType']) and $this->post->heightType == 'custom')
        {
            if(!$this->loadModel('kanban')->checkDisplayCards($execution->displayCards)) return;
        }

        if(in_array($execution->status, array('closed', 'suspended'))) $this->computeBurn($executionID);

        if(empty($execution->project) or $execution->project == $oldExecution->project) $this->checkBeginAndEndDate($oldExecution->project, $execution->begin, $execution->end, !empty($execution->parent) ? $execution : $oldExecution);
        if(dao::isError()) return false;

        /* Child stage inherits parent stage permissions. */
        if(!isset($execution->acl)) $execution->acl = $oldExecution->acl;

        $execution = $this->loadModel('file')->processImgURL($execution, $this->config->execution->editor->edit['id'], $this->post->uid);

        /* Check the workload format and total. */
        if(!empty($execution->percent) and isset($this->config->setPercent) and $this->config->setPercent == 1) $this->checkWorkload('update', $execution->percent, $oldExecution);

        /* Set planDuration and realDuration. */
        if($this->config->edition == 'max' or $this->config->edition == 'ipd')
        {
            $execution->planDuration = $this->loadModel('programplan')->getDuration($execution->begin, $execution->end);
            if(!empty($execution->realBegan) and !empty($execution->realEnd)) $execution->realDuration = $this->programplan->getDuration($execution->realBegan, $execution->realEnd);
        }

        /* Redefines the language entries for the fields in the project table. */
        foreach(explode(',', $this->config->execution->edit->requiredFields) as $field)
        {
            if(isset($this->lang->execution->$field)) $this->lang->project->$field = $this->lang->execution->$field;
            if($oldExecution->type == 'stage' and $field == 'name') $this->lang->project->name = str_replace($this->lang->executionCommon, $this->lang->project->stage, $this->lang->project->name);
        }

        $relatedExecutionsID = $this->getRelatedExecutions($executionID);
        $relatedExecutionsID = !empty($relatedExecutionsID) ? implode(',', array_keys($relatedExecutionsID)) : '0';

        /* Update data. */
        $this->lang->error->unique = $this->lang->error->repeat;
        $executionProject = isset($execution->project) ? (int)$execution->project : $oldExecution->project;
        $this->dao->update(TABLE_EXECUTION)->data($execution)
            ->autoCheck($skipFields = 'begin,end')
            ->batchcheck($this->config->execution->edit->requiredFields, 'notempty')
            ->checkIF($execution->begin != '', 'begin', 'date')
            ->checkIF($execution->end != '', 'end', 'date')
            ->checkIF($execution->end != '', 'end', 'ge', $execution->begin)
            ->checkIF(!empty($execution->name), 'name', 'unique', "id in ($relatedExecutionsID) and type in ('sprint','stage', 'kanban') and `project` = '$executionProject' and `deleted` = '0'")
            ->checkIF(!empty($execution->code), 'code', 'unique', "id != $executionID and type in ('sprint','stage', 'kanban') and `deleted` = '0'")
            ->checkFlow()
            ->where('id')->eq($executionID)
            ->limit(1)
            ->exec();

        if(dao::isError()) return false;

        if(isset($_POST['parent'])) $this->loadModel('programplan')->setTreePath($executionID);

        /* Get team and language item. */
        $this->loadModel('user');
        $team    = $this->user->getTeamMemberPairs($executionID, 'execution');
        $members = isset($_POST['teamMembers']) ? $_POST['teamMembers'] : array();
        array_push($members, $execution->PO, $execution->QD, $execution->PM, $execution->RD);
        $members = array_unique($members);
        $roles   = $this->user->getUserRoles(array_values($members));

        $changedAccounts = array();
        $teamMembers     = array();
        foreach($members as $account)
        {
            if(empty($account) or isset($team[$account])) continue;

            $member = new stdclass();
            $member->root    = (int)$executionID;
            $member->account = $account;
            $member->join    = helper::today();
            $member->role    = zget($roles, $account, '');
            $member->days    = zget($execution, 'days', 0);
            $member->type    = 'execution';
            $member->hours   = $this->config->execution->defaultWorkhours;
            $this->dao->replace(TABLE_TEAM)->data($member)->exec();

            $changedAccounts[$account]  = $account;
            $teamMembers[$account] = $member;
        }
        $this->dao->delete()->from(TABLE_TEAM)
            ->where('root')->eq((int)$executionID)
            ->andWhere('type')->eq('execution')
            ->andWhere('account')->in(array_keys($team))
            ->andWhere('account')->notin(array_values($members))
            ->andWhere('account')->ne($oldExecution->openedBy)
            ->exec();
        if(isset($execution->project) and $execution->project) $this->addProjectMembers($execution->project, $teamMembers);

        $whitelist = explode(',', $execution->whitelist);
        $this->loadModel('personnel')->updateWhitelist($whitelist, 'sprint', $executionID);

        /* Fix bug#3074, Update views for team members. */
        if($execution->acl != 'open') $this->updateUserView($executionID, 'sprint', $changedAccounts);

        if(isset($execution->project))
        {
            $executionProductList   = $this->loadModel('product')->getProducts($executionID);
            $projectProductList     = $this->product->getProducts($execution->project);
            $executionProductIdList = array_keys($executionProductList);
            $projectProductIdList   = array_keys($projectProductList);
            $diffProductIdList      = array_diff($executionProductIdList, $projectProductIdList);
            if(!empty($diffProductIdList))
            {
                foreach($diffProductIdList as $key => $newProductID)
                {
                    $data = $this->dao->select('*')->from(TABLE_PROJECTPRODUCT)
                        ->where('project')->eq($executionID)
                        ->andWhere('product')->eq($newProductID)
                        ->fetch();
                    $data->project = $execution->project;
                    $this->dao->insert(TABLE_PROJECTPRODUCT)->data($data)->exec();
                }
            }
        }

        if(!dao::isError())
        {
            if(isset($execution->project) and $execution->project != $oldExecution->project)
            {
                $execution->parent = $execution->project;
                $execution->path   = ",{$execution->project},{$executionID},";
                $this->changeProject($execution->project, $oldExecution->project, $executionID, $this->post->syncStories);
            }

            $this->file->updateObjectID($this->post->uid, $executionID, 'execution');
            return common::createChanges($oldExecution, $execution);
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
        $this->loadModel('user');
        $this->loadModel('project');
        $this->app->loadLang('programplan');

        $executions    = array();
        $allChanges    = array();
        $data          = fixer::input('post')->get();
        $oldExecutions = $this->getByIdList($this->post->executionIDList);
        $nameList      = array();
        $codeList      = array();
        $projectModel  = 'scrum';

        $parents = array();
        foreach($oldExecutions as $oldExecution) $parents[$oldExecution->id] = $oldExecution->parent;

        $parentAttrs = $this->dao->select('id,attribute')->from(TABLE_PROJECT)->where('id')->in($parents)->andWhere('deleted')->eq(0)->fetchPairs('id');

        /* Replace required language. */
        if($this->app->tab == 'project')
        {
            $projectModel = $this->dao->select('model')->from(TABLE_PROJECT)->where('id')->eq($this->session->project)->fetch('model');
            if($projectModel == 'scrum')
            {
                $this->lang->project->name = $this->lang->execution->name;
                $this->lang->project->code = $this->lang->execution->code;
            }
            else
            {
                $this->lang->project->name = str_replace($this->lang->project->common, $this->lang->project->stage, $this->lang->project->name);
                $this->lang->project->code = str_replace($this->lang->project->common, $this->lang->project->stage, $this->lang->project->code);
            }
        }

        $this->lang->error->unique = $this->lang->error->repeat;
        $extendFields = $this->getFlowExtendFields();
        foreach($data->executionIDList as $executionID)
        {
            $executionName = $data->names[$executionID];
            if(isset($data->codes)) $executionCode = $data->codes[$executionID];

            $executionID = (int)$executionID;
            $executions[$executionID] = new stdClass();
            $executions[$executionID]->id             = $executionID;
            $executions[$executionID]->name           = $executionName;
            $executions[$executionID]->PM             = $data->PMs[$executionID];
            $executions[$executionID]->PO             = $data->POs[$executionID];
            $executions[$executionID]->QD             = $data->QDs[$executionID];
            $executions[$executionID]->RD             = $data->RDs[$executionID];
            $executions[$executionID]->begin          = $data->begins[$executionID];
            $executions[$executionID]->end            = $data->ends[$executionID];
            $executions[$executionID]->team           = $data->teams[$executionID];
            $executions[$executionID]->desc           = htmlspecialchars_decode($data->descs[$executionID]);
            $executions[$executionID]->days           = $data->dayses[$executionID];
            $executions[$executionID]->lastEditedBy   = $this->app->user->account;
            $executions[$executionID]->lastEditedDate = helper::now();

            if(isset($data->codes))    $executions[$executionID]->code    = $executionCode;
            if(isset($data->projects)) $executions[$executionID]->project = zget($data->projects, $executionID, 0);
            if(isset($data->attributes[$executionID])) $executions[$executionID]->attribute = zget($data->attributes, $executionID, '');
            if(isset($data->lifetimes[$executionID]))  $executions[$executionID]->lifetime  = $data->lifetimes[$executionID];

            $oldExecution = $oldExecutions[$executionID];
            $projectID    = isset($executions[$executionID]->project) ? $executions[$executionID]->project : $oldExecution->project;
            $project      = $this->project->getByID($projectID);

            /* Check unique code for edited executions. */
            if(isset($data->codes) and empty($executionCode))
            {
                dao::$errors["codes$executionID"][] = sprintf($this->lang->error->notempty, $this->lang->execution->execCode);
            }
            elseif(isset($data->codes) and $executionCode)
            {
                if(isset($codeList[$executionCode]))
                {
                    dao::$errors["codes$executionID"][] = sprintf($this->lang->error->unique, $this->lang->execution->execCode, $executionCode);
                }
                $codeList[$executionCode] = $executionCode;
            }

            /* Name check. */
            $parentID = $parents[$executionID];
            if(isset($nameList[$executionName]))
            {
                foreach($nameList[$executionName] as $repeatID)
                {
                    if($parentID == $parents[$repeatID])
                    {
                        $type = $oldExecution->type == 'stage' ? 'stage' : 'agileplus';
                        dao::$errors["names$executionID"][] = sprintf($this->lang->execution->errorNameRepeat, strtolower(zget($this->lang->programplan->typeList, $type)));
                        break;
                    }
                }
            }

            $nameList[$executionName][] = $executionID;

            /* Attribute check. */
            if(isset($data->attributes))
            {
                if(isset($project->model) and ($project->model == 'waterfall' or  $project->model == 'waterfallplus'))
                {
                    $this->app->loadLang('stage');
                    $attribute  = $executions[$executionID]->attribute;
                    $parentAttr = $parentAttrs[$parentID];

                    if($parentAttr and $parentAttr != $attribute and $parentAttr != 'mix')
                    {
                        $parentAttr = zget($this->lang->stage->typeList, $parentAttr);
                        dao::$errors["attributes$executionID"][] = sprintf($this->lang->execution->errorAttrMatch, $parentAttr);
                    }

                    $attributeList[$executionID] = $attribute;
                }
            }

            /* Judge workdays is legitimate. */
            $workdays = helper::diffDate($data->ends[$executionID], $data->begins[$executionID]) + 1;
            if(isset($data->dayses[$executionID]) and $data->dayses[$executionID] > $workdays)
            {
                $this->app->loadLang('project');
                dao::$errors["dayses{$executionID}"][] = sprintf($this->lang->project->workdaysExceed, $workdays);
            }

            /* Parent stage begin and end check. */
            if(isset($executions[$parentID]))
            {
                $begin       = $executions[$executionID]->begin;
                $end         = $executions[$executionID]->end;
                $parentBegin = $executions[$parentID]->begin;
                $parentEnd   = $executions[$parentID]->end;

                if($begin < $parentBegin)
                {
                    dao::$errors["begins$executionID"][] = sprintf($this->lang->execution->errorLetterParent, $parentBegin);
                }

                if($end > $parentEnd)
                {
                    dao::$errors["ends$executionID"][] = sprintf($this->lang->execution->errorGreaterParent, $parentEnd);
                }
            }

            foreach($extendFields as $extendField)
            {
                $executions[$executionID]->{$extendField->field} = $this->post->{$extendField->field}[$executionID];
                if(is_array($executions[$executionID]->{$extendField->field})) $executions[$executionID]->{$extendField->field} = join(',', $executions[$executionID]->{$extendField->field});

                $executions[$executionID]->{$extendField->field} = htmlSpecialString($executions[$executionID]->{$extendField->field});
            }

            if(empty($executions[$executionID]->begin)) dao::$errors["begins{$executionID}"][] = sprintf($this->lang->error->notempty, $this->lang->execution->begin);
            if(empty($executions[$executionID]->end))   dao::$errors["ends{$executionID}"][]   = sprintf($this->lang->error->notempty, $this->lang->execution->end);

            /* Project begin and end check. */
            if(!empty($executions[$executionID]->begin) and !empty($executions[$executionID]->end))
            {
                if($executions[$executionID]->begin > $executions[$executionID]->end)
                {
                    dao::$errors["ends{$executionID}"][] = sprintf($this->lang->execution->errorLetterPlan, $executions[$executionID]->end, $executions[$executionID]->begin);
                }

                if($project and $executions[$executionID]->begin < $project->begin)
                {
                    dao::$errors["begins{$executionID}"][] = sprintf($this->lang->execution->errorLetterProject, $project->begin);
                }
                if($project and $executions[$executionID]->end > $project->end)
                {
                    dao::$errors["ends{$executionID}"][] = sprintf($this->lang->execution->errorGreaterProject, $project->end);
                }
            }
        }

        if(dao::isError()) return false;

        /* Update burn before close execution. */
        $closedIdList = array();
        foreach($executions as $executionID => $execution)
        {
            if(isset($execution->status) and in_array($execution->status, array('done', 'closed', 'suspended'))) $closedIdList[$executionID] = $executionID;
        }
        $this->computeBurn($closedIdList);

        foreach($executions as $executionID => $execution)
        {
            $oldExecution = $oldExecutions[$executionID];
            $team         = $this->loadModel('user')->getTeamMemberPairs($executionID, 'execution');
            $projectID    = isset($execution->project) ? (int)$execution->project : $oldExecution->project;

            if(isset($execution->project))
            {
                $executionProductList   = $this->loadModel('product')->getProducts($executionID);
                $projectProductList     = $this->product->getProducts($execution->project);
                $executionProductIdList = array_keys($executionProductList);
                $projectProductIdList   = array_keys($projectProductList);
                $diffProductIdList      = array_diff($executionProductIdList, $projectProductIdList);
                if(!empty($diffProductIdList))
                {
                    foreach($diffProductIdList as $key => $newProductID)
                    {
                        $data = $this->dao->select('*')->from(TABLE_PROJECTPRODUCT)
                            ->where('project')->eq($executionID)
                            ->andWhere('product')->eq($newProductID)
                            ->fetch();
                        $data->project = $execution->project;
                        $this->dao->insert(TABLE_PROJECTPRODUCT)->data($data)->exec();
                    }
                }
            }

            $this->dao->update(TABLE_EXECUTION)->data($execution)
                ->autoCheck($skipFields = 'begin,end')
                ->batchcheck($this->config->execution->edit->requiredFields, 'notempty')
                ->checkIF($execution->begin != '', 'begin', 'date')
                ->checkIF($execution->end != '', 'end', 'date')
                ->checkIF($execution->end != '', 'end', 'ge', $execution->begin)
                ->checkIF(!empty($execution->name), 'name', 'unique', "id != $executionID and type in ('sprint','stage','kanban') and `project` = $projectID and `deleted` = '0'")
                ->checkIF(!empty($execution->code), 'code', 'unique', "id != $executionID and type in ('sprint','stage','kanban') and `deleted` = '0'")
                ->checkFlow()
                ->where('id')->eq($executionID)
                ->limit(1)
                ->exec();

            if(dao::isError()) return false;

            if(!empty($execution->project) and $oldExecution->project != $execution->project)
            {
                $execution->parent = $execution->project;
                $execution->path   = ",{$execution->project},{$executionID},";
                $this->changeProject($execution->project, $oldExecution->project, $executionID, isset($_POST['syncStories'][$executionID]) ? $_POST['syncStories'][$executionID] : 'no');
            }

            $changedAccounts = array();
            foreach($this->config->execution->ownerFields as $ownerField)
            {
                $owner = zget($execution, $ownerField, '');
                if(empty($owner) or isset($team[$owner])) continue;

                $member = new stdclass();
                $member->root    = (int)$executionID;
                $member->account = $owner;
                $member->join    = helper::today();
                $member->role    = $this->lang->execution->$ownerField;
                $member->days    = zget($execution, 'days', 0);
                $member->type    = 'execution';
                $member->hours   = $this->config->execution->defaultWorkhours;
                $this->dao->replace(TABLE_TEAM)->data($member)->exec();

                $changedAccounts[] = $owner;
            }
            if(!empty($changedAccounts)) $this->updateUserView($executionID, 'sprint', $changedAccounts);

            $allChanges[$executionID] = common::createChanges($oldExecution, $execution);
        }
        $this->fixOrder();
        return $allChanges;
    }

    /**
     * Batch change status.
     *
     * @param  array     $executionIdList
     * @param  string    $status
     * @access public
     * @return void
     */
    public function batchChangeStatus($executionIdList, $status)
    {
        $this->loadModel('programplan');

        /* Sort the IDs, the child stage comes first, and the parent stage follows. */
        $executionIdList = $this->dao->select('id')->from(TABLE_EXECUTION)->where('id')->in($executionIdList)->orderBy('grade_desc')->fetchPairs();

        $pointOutStages = '';
        foreach($executionIdList as $executionID)
        {
            /* The state of the parent stage or the sibling stage may be affected by the child stage before the change, so it cannot be checked in advance. */
            $selfAndChildrenList = $this->programplan->getSelfAndChildrenList($executionID);
            $siblingStages       = $this->programplan->getSiblings($executionID);

            $selfAndChildren = $selfAndChildrenList[$executionID];
            $execution       = $selfAndChildren[$executionID];
            $executionType   = $execution->type;

            $siblingList = array();
            if($executionType == 'stage') $siblingList = $siblingStages[$executionID];

            if($status == 'wait' and $execution->status != 'wait')
            {
                $pointOutStages .= $this->changeStatus2Wait($executionID, $selfAndChildren, $siblingList);
            }

            if($status == 'doing' and $execution->status != 'doing')
            {
                $this->changeStatus2Doing($executionID, $selfAndChildren);
            }

            if(($status == 'suspended' and $execution->status != 'suspended') or ($status == 'closed' and $execution->status != 'closed'))
            {
                $pointOutStages .= $this->changeStatus2Inactived($executionID, $status, $selfAndChildren, $siblingList);
            }
        }

        return trim($pointOutStages, ',');
    }

    /**
     * Change status to wait.
     *
     * @param  int    $executionID
     * @param  array  $selfAndChildren
     * @param  array  $siblingStages
     * @access public
     * @return string
     */
    public function changeStatus2Wait($executionID, $selfAndChildren, $siblingStages)
    {
        $this->loadModel('programplan');
        $this->loadModel('action');

        $parentID = $selfAndChildren[$executionID]->parent;

        /* There are already tasks consuming work in this phase or its sub-phases already have start times. */
        $hasStartedChildren = $this->dao->select('id')->from(TABLE_EXECUTION)->where('deleted')->eq(0)->andWhere('realBegan')->ne('0000-00-00')->andWhere('id')->in(array_keys($selfAndChildren))->andWhere('id')->ne($executionID)->fetchPairs();
        $hasConsumedTasks   = $this->dao->select('count(consumed) as count')->from(TABLE_TASK)->where('deleted')->eq(0)->andWhere('execution')->in(array_keys($selfAndChildren))->andWhere('consumed')->gt(0)->fetch('count');
        if($hasStartedChildren or $hasConsumedTasks) return "'{$selfAndChildren[$executionID]->name}',";

        $newExecution = $this->buildExecutionByStatus('wait');
        $this->dao->update(TABLE_EXECUTION)->data($newExecution)->where('id')->eq($executionID)->exec();

        if(!dao::isError())
        {
            /* Action. */
            $changes  = common::createChanges($selfAndChildren[$executionID], $newExecution);
            $actionID = $this->action->create('execution', $executionID, 'Edited');
            $this->action->logHistory($actionID, $changes);

            /* This stage has a parent stage. */
            $checkTopStage = $this->programplan->checkTopStage($executionID);
            if(!$checkTopStage) $this->programplan->computeProgress($executionID);
        }
    }

    /**
     * Change status to doing.
     *
     * @param  int    $executionID
     * @param  array  $selfAndChildren
     * @access public
     * @return string
     */
    public function changeStatus2Doing($executionID, $selfAndChildren)
    {
        $this->loadModel('programplan');
        $this->loadModel('action');

        $type     = $selfAndChildren[$executionID]->type;
        $parentID = $selfAndChildren[$executionID]->parent;

        $newExecution = $this->buildExecutionByStatus('doing');
        $this->dao->update(TABLE_EXECUTION)->data($newExecution)->where('id')->eq($executionID)->exec();
        if(!dao::isError())
        {
            $changes  = common::createChanges($selfAndChildren[$executionID], $newExecution);
            $actionID = $this->action->create('execution', $executionID, 'Started');
            $this->action->logHistory($actionID, $changes);

            /* This stage has a parent stage. */
            $checkTopStage = $this->programplan->checkTopStage($executionID);
            if(!$checkTopStage) $this->programplan->computeProgress($executionID);
        }
    }

    /**
     * Change status to suspended or closed.
     *
     * @param  int    $executionID
     * @param  strint $status
     * @param  array  $selfAndChildren
     * @param  array  $siblingStages
     * @access public
     * @return string
     */
    public function changeStatus2Inactived($executionID, $status, $selfAndChildren, $siblingStages)
    {
        $this->loadModel('programplan');
        $this->loadModel('action');

        $type          = $selfAndChildren[$executionID]->type;
        $parentID      = $selfAndChildren[$executionID]->parent;
        $checkedStatus = $status == 'suspended' ? 'wait,doing' : 'wait,doing,suspended';

        /* If status is suspended, the rules is there are sub-stages under this stage, and not all sub-stages are suspended or closed. */
        /* If status is closed, the rules is there are sub-stages under this stage, and not all sub-stages are closed. */
        $checkLeafStage = $this->programplan->checkLeafStage($executionID);
        if(!$checkLeafStage)
        {
            foreach($selfAndChildren as $childID => $child)
            {
                if($childID == $executionID) continue;

                if(strpos($checkedStatus, $child->status) !== false) return "'{$selfAndChildren[$executionID]->name}',";
            }
        }

        $newExecution = $this->buildExecutionByStatus($status);
        $this->dao->update(TABLE_EXECUTION)->data($newExecution)->where('id')->eq($executionID)->exec();
        if(!dao::isError())
        {
            $changes  = common::createChanges($selfAndChildren[$executionID], $newExecution);
            $actionID = $this->action->create('execution', $executionID, strtoupper($status));
            $this->action->logHistory($actionID, $changes);

            /* Suspended: When all child stages at the same level are suspended or closed, the status of the parent stage becomes "suspended". */
            /* Closed: When all child stages at the same level are closed, the status of the parent stage becomes "closed". */
            $checkTopStage = $this->programplan->checkTopStage($executionID);
            if(!$checkTopStage) $this->programplan->computeProgress($executionID);
        }
    }

    /**
     * Start execution.
     *
     * @param  int    $executionID
     * @access public
     * @return array
     */
    public function start($executionID)
    {
        $oldExecution = $this->getById($executionID);
        $now          = helper::now();

        $execution = fixer::input('post')
            ->add('id', $executionID)
            ->setDefault('status', 'doing')
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', $now)
            ->stripTags($this->config->execution->editor->start['id'], $this->config->allowedTags)
            ->remove('comment')
            ->get();

        $execution = $this->loadModel('file')->processImgURL($execution, $this->config->execution->editor->start['id'], $this->post->uid);
        $this->dao->update(TABLE_EXECUTION)->data($execution)
            ->autoCheck()
            ->check($this->config->execution->start->requiredFields, 'notempty')
            ->checkIF($execution->realBegan != '', 'realBegan', 'le', helper::today())
            ->checkFlow()
            ->where('id')->eq((int)$executionID)
            ->exec();
        $this->loadModel('project')->recordFirstEnd($executionID);

        /* When it has multiple errors, only the first one is prompted */
        if(dao::isError() and count(dao::$errors['realBegan']) > 1) dao::$errors['realBegan'] = dao::$errors['realBegan'][0];

        if(!dao::isError()) return common::createChanges($oldExecution, $execution);
    }

    /**
     * Put execution off.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function putoff($executionID)
    {
        $oldExecution = $this->getById($executionID);
        $now          = helper::now();

        $execution = fixer::input('post')
            ->add('id', $executionID)
            ->stripTags($this->config->execution->editor->putoff['id'], $this->config->allowedTags)
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', $now)
            ->remove('comment')
            ->get();

        $this->checkBeginAndEndDate($oldExecution->project, $execution->begin, $execution->end);
        if(dao::isError()) return false;

        $execution = $this->loadModel('file')->processImgURL($execution, $this->config->execution->editor->putoff['id'], $this->post->uid);
        $this->dao->update(TABLE_EXECUTION)->data($execution)
            ->autoCheck()
            ->checkFlow()
            ->where('id')->eq((int)$executionID)
            ->exec();

        if(!dao::isError()) return common::createChanges($oldExecution, $execution);
    }

    /**
     * Suspend execution.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function suspend($executionID)
    {
        $oldExecution = $this->getById($executionID);
        $now          = helper::now();

        $execution = fixer::input('post')
            ->add('id', $executionID)
            ->setDefault('status', 'suspended')
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', $now)
            ->setDefault('suspendedDate', helper::today())
            ->stripTags($this->config->execution->editor->suspend['id'], $this->config->allowedTags)
            ->remove('comment')->get();

        $execution = $this->loadModel('file')->processImgURL($execution, $this->config->execution->editor->suspend['id'], $this->post->uid);
        $this->dao->update(TABLE_EXECUTION)->data($execution)
            ->autoCheck()
            ->checkFlow()
            ->where('id')->eq((int)$executionID)
            ->exec();

        if(!dao::isError()) return common::createChanges($oldExecution, $execution);
    }

    /**
     * Activate execution.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function activate($executionID)
    {
        $oldExecution = $this->getById($executionID);
        $now          = helper::now();

        $execution = fixer::input('post')
            ->add('id', $executionID)
            ->setDefault('realEnd', '')
            ->setDefault('status', 'doing')
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', $now)
            ->setDefault('closedBy', '')
            ->setDefault('closedDate', '')
            ->stripTags($this->config->execution->editor->activate['id'], $this->config->allowedTags)
            ->remove('comment,readjustTime,readjustTask')
            ->get();

        if(empty($oldExecution->totalConsumed) and helper::isZeroDate($oldExecution->realBegan)) $execution->status = 'wait';

        if(!$this->post->readjustTime)
        {
            unset($execution->begin);
            unset($execution->end);
        }

        if($this->post->readjustTime)
        {
            $begin = $execution->begin;
            $end   = $execution->end;

            if($begin > $end) dao::$errors["message"][] = sprintf($this->lang->execution->errorLetterPlan, $end, $begin);

            if($oldExecution->grade > 1)
            {
                $parent      = $this->dao->select('begin,end')->from(TABLE_PROJECT)->where('id')->eq($oldExecution->parent)->fetch();
                $parentBegin = $parent->begin;
                $parentEnd   = $parent->end;
                if($begin < $parentBegin)
                {
                    dao::$errors["message"][] = sprintf($this->lang->execution->errorLetterParent, $parentBegin);
                }

                if($end > $parentEnd)
                {
                    dao::$errors["message"][] = sprintf($this->lang->execution->errorGreaterParent, $parentEnd);
                }
            }
        }

        if(dao::isError()) return false;

        $execution = $this->loadModel('file')->processImgURL($execution, $this->config->execution->editor->activate['id'], $this->post->uid);
        $this->dao->update(TABLE_EXECUTION)->data($execution)
            ->autoCheck()
            ->checkFlow()
            ->where('id')->eq((int)$executionID)
            ->exec();

        /* Readjust task. */
        if($this->post->readjustTime and $this->post->readjustTask)
        {
            $beginTimeStamp = strtotime($execution->begin);
            $tasks = $this->dao->select('id,estStarted,deadline,status')->from(TABLE_TASK)
                ->where('deadline')->ne('0000-00-00')
                ->andWhere('status')->in('wait,doing')
                ->andWhere('execution')->eq($executionID)
                ->fetchAll();
            foreach($tasks as $task)
            {
                if($task->status == 'wait' and !helper::isZeroDate($task->estStarted))
                {
                    $taskDays   = helper::diffDate($task->deadline, $task->estStarted);
                    $taskOffset = helper::diffDate($task->estStarted, $oldExecution->begin);

                    $estStartedTimeStamp = $beginTimeStamp + $taskOffset * 24 * 3600;
                    $estStarted = date('Y-m-d', $estStartedTimeStamp);
                    $deadline   = date('Y-m-d', $estStartedTimeStamp + $taskDays * 24 * 3600);

                    if($estStarted > $execution->end) $estStarted = $execution->end;
                    if($deadline > $execution->end)   $deadline   = $execution->end;
                    $this->dao->update(TABLE_TASK)->set('estStarted')->eq($estStarted)->set('deadline')->eq($deadline)->where('id')->eq($task->id)->exec();
                }
                else
                {
                    $taskOffset = helper::diffDate($task->deadline, $oldExecution->begin);
                    $deadline   = date('Y-m-d', $beginTimeStamp + $taskOffset * 24 * 3600);

                    if($deadline > $execution->end) $deadline = $execution->end;
                    $this->dao->update(TABLE_TASK)->set('deadline')->eq($deadline)->where('id')->eq($task->id)->exec();
                }
            }
        }

        $changes = common::createChanges($oldExecution, $execution);
        if($this->post->comment != '' or !empty($changes))
        {
            $this->loadModel('action');
            $actionID = $this->action->create('execution', $executionID, 'Activated', $this->post->comment);
            $this->action->logHistory($actionID, $changes);
        }

        return $changes;
    }

    /**
     * Close execution.
     *
     * @param  int    $executionID
     * @access public
     * @return array
     */
    public function close($executionID)
    {
        $oldExecution = $this->getById($executionID);
        $now          = helper::now();

        $execution = fixer::input('post')
            ->add('id', $executionID)
            ->setDefault('status', 'closed')
            ->setDefault('closedBy', $this->app->user->account)
            ->setDefault('closedDate', $now)
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', $now)
            ->stripTags($this->config->execution->editor->close['id'], $this->config->allowedTags)
            ->remove('comment')
            ->get();

        $this->lang->error->ge = $this->lang->execution->ge;

        $execution = $this->loadModel('file')->processImgURL($execution, $this->config->execution->editor->close['id'], $this->post->uid);
        $this->dao->update(TABLE_EXECUTION)->data($execution)
            ->autoCheck()
            ->check($this->config->execution->close->requiredFields,'notempty')
            ->checkIF($execution->realEnd != '', 'realEnd', 'le', helper::today())
            ->checkIF($execution->realEnd != '', 'realEnd', 'ge', $oldExecution->realBegan)
            ->checkFlow()
            ->where('id')->eq((int)$executionID)
            ->exec();

        /* When it has multiple errors, only the first one is prompted */
        if(dao::isError() and count(dao::$errors['realEnd']) > 1) dao::$errors['realEnd'] = dao::$errors['realEnd'][0];

        if(!dao::isError())
        {

            $changes = common::createChanges($oldExecution, $execution);
            if($this->post->comment != '' or !empty($changes))
            {
                $this->loadModel('action');
                $actionID = $this->action->create('execution', $executionID, 'Closed', $this->post->comment);
                $this->action->logHistory($actionID, $changes);
            }

            $this->loadModel('score')->create('execution', 'close', $oldExecution);
            return $changes;
        }
    }

    /**
     * Set Kanban.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function setKanban($executionID)
    {
        $execution = fixer::input('post')
            ->setIF($this->post->heightType == 'auto', 'displayCards', 0)
            ->remove('heightType')
            ->get();

        if(isset($_POST['heightType']) and $this->post->heightType == 'custom' and !$this->loadModel('kanban')->checkDisplayCards($execution->displayCards)) return;

        $this->app->loadLang('kanban');
        $this->lang->project->colWidth    = $this->lang->kanban->colWidth;
        $this->lang->project->minColWidth = $this->lang->kanban->minColWidth;
        $this->lang->project->maxColWidth = $this->lang->kanban->maxColWidth;
        $this->dao->update(TABLE_EXECUTION)->data($execution)
            ->autoCheck()
            ->batchCheck($this->config->kanban->edit->requiredFields, 'notempty')
            ->checkIF(!$execution->fluidBoard, 'colWidth', 'ge', $this->config->minColWidth)
            ->batchCheckIF($execution->fluidBoard, 'minColWidth', 'ge', $this->config->minColWidth)
            ->checkIF($execution->minColWidth >= $this->config->minColWidth and $execution->fluidBoard, 'maxColWidth', 'gt', $execution->minColWidth)
            ->where('id')->eq((int)$executionID)
            ->exec();
    }

    /**
     * Check the workload format and total.
     *
     * @param  string     $type create|update
     * @param  int        $percent
     * @param  object|int $oldExecution
     * @access public
     * @return bool
     */
    public function checkWorkload($type = '', $percent = 0, $oldExecution = '')
    {
        if(!preg_match("/^[0-9]+(.[0-9]{1,3})?$/", $percent))
        {
            dao::$errors['percent'] = $this->lang->programplan->error->percentNumber;
            return false;
        }

        /* The total workload of the first stage should not exceed 100%. */
        if($type == 'create' or (!empty($oldExecution) and $oldExecution->grade == 1 and isset($this->lang->execution->typeList[$oldExecution->type])))
        {
            $oldPercentTotal = $this->dao->select('SUM(t2.percent) as total')->from(TABLE_PROJECTPRODUCT)->alias('t1')
                ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.project=t2.id')
                ->where('t1.product')->eq($this->post->products[0])
                ->beginIF(!empty($_POST['branch'][0]))->andWhere('t1.branch')->eq(current($this->post->branch[0]))->fi()
                ->andWhere('t2.type')->eq('stage')
                ->andWhere('t2.grade')->eq(1)
                ->andWhere('t2.deleted')->eq(0)
                ->beginIF($type == 'create')->andWhere('t2.parent')->eq($oldExecution->id)->fi()
                ->beginIF(!empty($oldExecution) and isset($this->lang->execution->typeList[$oldExecution->type]))->andWhere('t2.parent')->eq($oldExecution->parent)->fi()
                ->fetch('total');

            if(!$oldPercentTotal) $oldPercentTotal = 0;
            if($type == 'create') $percentTotal = $percent + $oldPercentTotal;
            if(!empty($oldExecution) and isset($this->lang->execution->typeList[$oldExecution->type])) $percentTotal = $oldPercentTotal - $oldExecution->percent + $percent;

            if($percentTotal > 100)
            {
                $printPercent = $type == 'create' ? $oldPercentTotal : $percentTotal;
                dao::$errors['percent'] = sprintf($this->lang->execution->workloadTotal, '%', $printPercent . '%');
                return false;
            }
        }

        if($type == 'update' and $oldExecution->grade > 1)
        {
            $childrenTotalPercent = $this->dao->select('SUM(percent) as total')->from(TABLE_EXECUTION)->where('parent')->eq($oldExecution->parent)->andWhere('project')->eq($oldExecution->project)->andWhere('deleted')->eq(0)->fetch('total');
            $childrenTotalPercent = $childrenTotalPercent - $oldExecution->percent + $this->post->percent;

            if($childrenTotalPercent > 100)
            {
                dao::$errors['percent'] = sprintf($this->lang->execution->workloadTotal, '%', $childrenTotalPercent . '%');
                return false;
            }
        }
    }

    /**
     * Check begin and end date.
     *
     * @param  int    $projectID
     * @param  string $begin
     * @param  string $end
     * @param  object $execution
     * @access public
     * @return void
     */
    public function checkBeginAndEndDate($projectID, $begin, $end, $execution = null)
    {
        $project = $this->loadModel('project')->getByID($projectID);
        if(empty($project)) return;

        if($begin < $project->begin) dao::$errors['begin'] = sprintf($this->lang->execution->errorCommonBegin, $project->begin);
        if($end > $project->end)     dao::$errors['end']   = sprintf($this->lang->execution->errorCommonEnd, $project->end);
        if(($project->model == 'waterfall' or $project->model == 'waterfallplus') and isset($execution) and $execution->parent != $projectID)
        {
            $this->app->loadLang('programplan');
            $parent = $this->getByID($execution->parent);
            if($begin < $parent->begin) dao::$errors['begin'] = sprintf($this->lang->programplan->error->letterParent, $parent->begin);
            if($end > $parent->end)     dao::$errors['end']   = sprintf($this->lang->programplan->error->greaterParent, $parent->end);
        }
    }

    /*
     * Get execution switcher.
     *
     * @param  int     $executionID
     * @param  string  $currentModule
     * @param  string  $currentMethod
     * @access public
     * @return string
     */
    public function getSwitcher($executionID, $currentModule, $currentMethod)
    {
        if($currentModule == 'execution' and in_array($currentMethod,  array('index', 'all', 'batchedit', 'create'))) return;

        $projectNameSpan      = '';
        $projectNameTitle     = '';
        $currentExecutionName = $this->lang->execution->common;
        if($executionID)
        {
            $currentExecution     = $this->getById($executionID);
            $currentExecutionName = $currentExecution->name;

            $project = $this->loadModel('project')->getByID($currentExecution->project);

            if($project)
            {
                $projectNameTitle = $project->name . ' / ';
                $projectNameSpan  = "<span class='text'>{$project->name}</span> / ";
            }
        }

        if($this->app->viewType == 'mhtml' and $executionID)
        {
            $output  = html::a(helper::createLink('execution', 'index'), $this->lang->executionCommon) . $this->lang->colon;
            $output .= "<a id='currentItem' href=\"javascript:showSearchMenu('execution', '$executionID', '$currentModule', '$currentMethod', '')\">{$currentExecutionName} <span class='icon-caret-down'></span></a><div id='currentItemDropMenu' class='hidden affix enter-from-bottom layer'></div>";
            return $output;
        }

        $dropMenuLink = helper::createLink('execution', 'ajaxGetDropMenu', "executionID=$executionID&module=$currentModule&method=$currentMethod&extra=");
        $output  = "<div class='btn-group header-btn' id='swapper'><button data-toggle='dropdown' type='button' class='btn' id='currentItem' title='{$projectNameTitle}{$currentExecutionName}'>{$projectNameSpan}<span class='text'>{$currentExecutionName}</span> <span class='caret' style='margin-bottom: -1px'></span></button><div id='dropMenu' class='dropdown-menu search-list' data-ride='dropmenu' data-url='$dropMenuLink'>";
        $output .= '<div class="input-control search-box has-icon-left has-icon-right search-example"><input type="search" class="form-control search-input" /><label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label><a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a></div>';
        $output .= "</div></div>";

        return $output;
    }

    /**
     * Get execution pairs.
     *
     * @param  int    $projectID
     * @param  string $type all|sprint|stage|kanban
     * @param  string $mode all|noclosed|stagefilter|withdelete|multiple|leaf|order_asc|empty|noprefix|withobject
     * @access public
     * @return array
     */
    public function getPairs($projectID = 0, $type = 'all', $mode = '')
    {
        if(defined('TUTORIAL')) return $this->loadModel('tutorial')->getExecutionPairs();

        $mode   .= $this->cookie->executionMode;
        $orderBy = $this->config->execution->orderBy;
        if($projectID)
        {
            $executionModel = $this->dao->select('model')->from(TABLE_EXECUTION)->where('id')->eq($projectID)->andWhere('deleted')->eq(0)->fetch('model');
            $orderBy = in_array($executionModel, array('waterfall', 'waterfallplus')) ? 'sortStatus_asc,begin_asc,id_asc' : 'id_desc';

            /* Waterfall execution, when all phases are closed, in reverse order of date. */
            if(in_array($executionModel, array('waterfall', 'waterfallplus')))
            {
                $summary = $this->dao->select("count(id) as executions, sum(IF(INSTR('closed', status) < 1, 0, 1)) as closedExecutions")->from(TABLE_EXECUTION)->where('project')->eq($projectID)->andWhere('deleted')->eq('0')->fetch();
                if($summary->executions == $summary->closedExecutions) $orderBy = 'sortStatus_asc,begin_desc,id_asc';
            }
        }

        /* Can not use $this->app->tab in API. */
        $filterMulti = ((defined('RUN_MODE') and RUN_MODE == 'api') or $this->app->getViewType() == 'json') ? false : (!$this->session->multiple and $this->app->tab == 'execution');
        /* Order by status's content whether or not done */
        $executions = $this->dao->select("*, IF(INSTR('done,closed', status) < 2, 0, 1) AS isDone, INSTR('doing,wait,suspended,closed', status) AS sortStatus")->from(TABLE_EXECUTION)
            ->where('deleted')->eq(0)
            ->andWhere('vision')->eq($this->config->vision)
            ->beginIF($filterMulti)->andWhere('multiple')->eq('1')->fi()
            ->beginIF(strpos($mode, 'multiple') !== false)->andWhere('multiple')->eq('1')->fi()
            ->beginIF($type == 'all')->andWhere('type')->in('stage,sprint,kanban')->fi()
            ->beginIF($projectID)->andWhere('project')->eq($projectID)->fi()
            ->beginIF($type != 'all')->andWhere('type')->in($type)->fi()
            ->beginIF(strpos($mode, 'withdelete') === false)->andWhere('deleted')->eq(0)->fi()
            ->beginIF(!$this->app->user->admin and strpos($mode, 'all') === false)->andWhere('id')->in($this->app->user->view->sprints)->fi()
            ->orderBy($orderBy)
            ->fetchAll('id');

        /* If mode == leaf, only show leaf executions. */
        $allExecutions = $this->dao->select('id,name,parent,grade')->from(TABLE_EXECUTION)
            ->where('type')->notin(array('program', 'project'))
            ->andWhere('deleted')->eq('0')
            ->beginIf($projectID)->andWhere('project')->eq($projectID)->fi()
            ->fetchAll('id');

        $parents = array();
        foreach($allExecutions as $exec) $parents[$exec->parent] = true;

        if(strpos($mode, 'order_asc') !== false) $executions = $this->resetExecutionSorts($executions);
        if(strpos($mode, 'withobject') !== false)
        {
            $projectPairs = $this->dao->select('id,name')->from(TABLE_PROJECT)->fetchPairs('id');
        }

        $pairs       = array();
        $noMultiples = array();
        foreach($executions as $execution)
        {
            if(strpos($mode, 'leaf') !== false and isset($parents[$execution->id])) continue; // Only show leaf.
            if(strpos($mode, 'noclosed') !== false and ($execution->status == 'done' or $execution->status == 'closed')) continue;
            if(strpos($mode, 'stagefilter') !== false and isset($executionModel) and in_array($executionModel, array('waterfall', 'waterfallplus')) and in_array($execution->attribute, array('request', 'design', 'review'))) continue; // Some stages of waterfall and waterfallplus not need.

            if(empty($execution->multiple)) $noMultiples[$execution->id] = $execution->project;

            /* Set execution name. */
            $paths = array_slice(explode(',', trim($execution->path, ',')), 1);
            $executionName = '';
            foreach($paths as $path)
            {
                if(isset($allExecutions[$path])) $executionName .= '/' . $allExecutions[$path]->name;
            }

            if(strpos($mode, 'withobject') !== false) $executionName = zget($projectPairs, $execution->project, '') . $executionName;
            if(strpos($mode, 'noprefix') !== false) $executionName = ltrim($executionName, '/');

            $pairs[$execution->id] = $executionName;
        }

        if($noMultiples)
        {
            if(strpos($mode, 'hideMultiple') !== false)
            {
                foreach($noMultiples as $executionID => $projectID) $pairs[$executionID] = '';
            }
            else
            {
                $this->app->loadLang('project');
                $noMultipleProjects = $this->dao->select('id, name')->from(TABLE_PROJECT)->where('id')->in($noMultiples)->fetchPairs('id', 'name');

                foreach($noMultiples as $executionID => $projectID)
                {
                    if(isset($noMultipleProjects[$projectID])) $pairs[$executionID] = $noMultipleProjects[$projectID] . "({$this->lang->project->disableExecution})";
                }
            }
        }

        if(strpos($mode, 'empty') !== false) $pairs[0] = '';

        /* If the pairs is empty, to make sure there's an execution in the pairs. */
        if(empty($pairs) and isset($executions[0]))
        {
            $firstExecution = $executions[0];
            $pairs[$firstExecution->id] = $firstExecution->name;
        }

        return $pairs;
    }

    /**
     * Get an array of execution id:name.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function fetchPairs($projectID = 0, $type = 'all', $filterMulti = true)
    {
        return $this->dao->select('id,name')->from(TABLE_EXECUTION)
            ->where('deleted')->eq(0)
            ->andWhere('vision')->eq($this->config->vision)
            ->beginIF($projectID)->andWhere('project')->eq($projectID)->fi()
            ->beginIF($type == 'all')->andWhere('type')->in('stage,sprint,kanban')->fi()
            ->beginIF($type != 'all')->andWhere('type')->in($type)->fi()
            ->beginIF($filterMulti)->andWhere('multiple')->eq('1')->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->sprints)->fi()
            ->fetchPairs();
    }

    /**
     * Get execution by idList.
     *
     * @param  array  $executionIdList
     * @param  string $mode all
     * @access public
     * @return array
     */
    public function getByIdList($executionIdList = array(), $mode = '')
    {
        return $this->dao->select('*')->from(TABLE_EXECUTION)
            ->where('id')->in($executionIdList)
            ->beginIF($mode != 'all')->andWhere('deleted')->eq(0)->fi()
            ->fetchAll('id');
    }

    /**
     * Get project lists.
     *
     * @param  int    $projectID
     * @param  string $type    all|sprint|stage|kanban
     * @param  string $status  all|undone|wait|running
     * @param  int    $limit
     * @param  int    $productID
     * @param  int    $branch
     * @param  object $pager
     * @param  bool   $withChildren
     * @access public
     * @return array
     */
    public function getList($projectID = 0, $type = 'all', $status = 'all', $limit = 0, $productID = 0, $branch = 0, $pager = null, $withChildren = true)
    {
        if($status == 'involved') return $this->getInvolvedExecutionList($projectID, $status, $limit, $productID, $branch);

        if($productID != 0)
        {
            return $this->dao->select('t2.*')->from(TABLE_PROJECTPRODUCT)->alias('t1')
                ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.project= t2.id')
                ->where('t1.product')->eq($productID)
                ->beginIF($projectID)->andWhere('t2.project')->eq($projectID)->fi()
                ->beginIF($type == 'all')->andWhere('t2.type')->in('sprint,stage,kanban')->fi()
                ->beginIF($type != 'all')->andWhere('t2.type')->eq($type)->fi()
                ->andWhere('t2.deleted')->eq(0)
                ->beginIF($status == 'undone')->andWhere('t2.status')->notIN('done,closed')->fi()
                ->beginIF($branch)->andWhere('t1.branch')->eq($branch)->fi()
                ->beginIF($status != 'all' and $status != 'undone')->andWhere('status')->in($status)->fi()
                ->beginIF(!$this->app->user->admin and isset($this->app->user->view))->andWhere('t2.id')->in($this->app->user->view->sprints)->fi()
                ->beginIF(!$withChildren)->andWhere('grade')->eq(1)->fi()
                ->orderBy('order_desc')
                ->page($pager)
                ->beginIF($limit)->limit($limit)->fi()
                ->fetchAll('id');
        }
        else
        {
            return $this->dao->select("*, IF(INSTR(' done,closed', status) < 2, 0, 1) AS isDone")->from(TABLE_EXECUTION)
                ->where('deleted')->eq(0)
                ->andWhere('vision')->eq($this->config->vision)
                ->beginIF($type == 'all')->andWhere('type')->in('sprint,stage,kanban')->fi()
                ->beginIF($type != 'all')->andWhere('type')->eq($type)->fi()
                ->beginIF($projectID)->andWhere('project')->eq($projectID)->fi()
                ->beginIF($status == 'undone')->andWhere('status')->notIN('done,closed')->fi()
                ->beginIF($status != 'all' and $status != 'undone')->andWhere('status')->in($status)->fi()
                ->beginIF(!$this->app->user->admin and isset($this->app->user->view))->andWhere('id')->in($this->app->user->view->sprints)->fi()
                ->beginIF(!$withChildren)->andWhere('grade')->eq(1)->fi()
                ->orderBy('order_desc')
                ->page($pager)
                ->beginIF($limit)->limit($limit)->fi()
                ->fetchAll('id');
        }
    }

    /**
     * Get involved execution list.
     *
     * @param  int    $projectID
     * @param  string $status  involved
     * @param  int    $limit
     * @param  int    $productID
     * @param  int    $branch
     * @access public
     * @return array
     */
    public function getInvolvedExecutionList($projectID = 0, $status = 'involved', $limit = 0, $productID = 0, $branch = 0)
    {
        if($productID != 0)
        {
            return $this->dao->select('t2.*')->from(TABLE_PROJECTPRODUCT)->alias('t1')
                ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.project= t2.id')
                ->leftJoin(TABLE_TEAM)->alias('t3')->on('t3.root=t2.id')
                ->where('t1.product')->eq($productID)
                ->andWhere('t2.deleted')->eq(0)
                ->beginIF($branch)->andWhere('t1.branch')->eq($branch)->fi()
                ->beginIF(!$this->app->user->admin)->andWhere('t2.id')->in($this->app->user->view->sprints)->fi()
                ->andWhere('t2.openedBy', true)->eq($this->app->user->account)
                ->orWhere('t3.account')->eq($this->app->user->account)
                ->markRight(1)
                ->andWhere('t2.type')->in('sprint,stage,kanban')
                ->beginIF($projectID)->andWhere('t2.project')->eq($projectID)->fi()
                ->orderBy('order_desc')
                ->beginIF($limit)->limit($limit)->fi()
                ->fetchAll('id');
        }
        else
        {
            return $this->dao->select("t1.*, IF(INSTR(' done,closed', t1.status) < 2, 0, 1) AS isDone")->from(TABLE_EXECUTION)->alias('t1')
                ->leftJoin(TABLE_TEAM)->alias('t2')->on('t2.root=t1.id')
                ->where('t1.deleted')->eq(0)
                ->beginIF(!$this->app->user->admin)->andWhere('t1.id')->in($this->app->user->view->sprints)->fi()
                ->andWhere('t1.openedBy', true)->eq($this->app->user->account)
                ->orWhere('t2.account')->eq($this->app->user->account)
                ->markRight(1)
                ->andWhere('t1.type')->in('sprint,stage,kanban')
                ->beginIF($projectID)->andWhere('t1.project')->eq($projectID)->fi()
                ->orderBy('t1.order_desc')
                ->beginIF($limit)->limit($limit)->fi()
                ->fetchAll('id');
        }
    }

    /**
     * Get execution id list by project.
     *
     * @param  int  $projectID
     * @param  int  $status all|undone|wait|doing|suspended|closed
     * @access public
     * @return array
     */
    public function getIdList($projectID, $status = 'all')
    {
        return $this->dao->select('id')->from(TABLE_EXECUTION)
            ->where('type')->in('sprint,stage,kanban')
            ->andWhere('deleted')->eq('0')
            ->beginIF($projectID)->andWhere('project')->eq($projectID)->fi()
            ->beginIF($status == 'undone')->andWhere('status')->notIN('done,closed')->fi()
            ->beginIF($status != 'all' and $status != 'undone')->andWhere('status')->in($status)->fi()
            ->fetchPairs('id', 'id');
    }

    /**
     * Get execution count.
     *
     * @param  int    $projectID
     * @param  string $browseType all|undone|wait|doing|suspended|closed|involved|review
     * @access public
     * @return int
     */
    public function getExecutionCounts($projectID = 0, $browseType = 'all')
    {
        $executions = $this->dao->select('t1.*,t2.name projectName, t2.model as projectModel')->from(TABLE_EXECUTION)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->where('t1.type')->in('sprint,stage,kanban')
            ->andWhere('t1.deleted')->eq('0')
            ->andWhere('t1.vision')->eq($this->config->vision)
            ->andWhere('t1.multiple')->eq('1')
            ->beginIF(!$this->app->user->admin)->andWhere('t1.id')->in($this->app->user->view->sprints)->fi()
            ->beginIF($projectID)->andWhere('t1.project')->eq($projectID)->fi()
            ->beginIF(!in_array($browseType, array('all', 'undone', 'involved', 'review', 'bySearch')))->andWhere('t1.status')->eq($browseType)->fi()
            ->beginIF($browseType == 'undone')->andWhere('t1.status')->notIN('done,closed')->fi()
            ->beginIF($browseType == 'review')
            ->andWhere("FIND_IN_SET('{$this->app->user->account}', t1.reviewers)")
            ->andWhere('t1.reviewStatus')->eq('doing')
            ->fi()
            ->fetchAll('id');

        return count($executions);
    }

    /**
     * Get execution stat data.

     * @param  int        $projectID
     * @param  string     $browseType all|undone|wait|doing|suspended|closed|involved|bySearch|review
     * @param  int        $productID
     * @param  int        $branch
     * @param  bool       $withTasks
     * @param  string|int $param skipParent
     * @param  string     $orderBy
     * @param  object     $pager
     * @access public
     * @return array
     */
    public function getStatData($projectID = 0, $browseType = 'undone', $productID = 0, $branch = 0, $withTasks = false, $param = '', $orderBy = 'id_asc', $pager = null)
    {
        if(defined('TUTORIAL')) return $this->loadModel('tutorial')->getExecutionStats($browseType);

        $productID = (int)$productID;

        /* Construct the query SQL at search executions. */
        $executionQuery = '';
        if($browseType == 'bySearch')
        {
            $queryID = (int)$param;
            if($queryID)
            {
                $query = $this->loadModel('search')->getQuery($queryID);
                if($query)
                {
                    $this->session->set('executionQuery', $query->sql);
                    $this->session->set('executionForm', $query->form);
                }
            }
            if($this->session->executionQuery == false) $this->session->set('executionQuery', ' 1 = 1');

            $executionQuery = $this->session->executionQuery;
            $allProject = "`project` = 'all'";

            if(strpos($executionQuery, $allProject) !== false) $executionQuery = str_replace($allProject, '1', $executionQuery);
            $executionQuery = preg_replace('/(`\w*`)/', 't1.$1',$executionQuery);
        }

        $parentExecutions = $this->dao->select('t1.*,t2.name projectName, t2.model as projectModel')->from(TABLE_EXECUTION)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->beginIF($productID)->leftJoin(TABLE_PROJECTPRODUCT)->alias('t3')->on('t1.id=t3.project')->fi()
            ->where('t1.type')->in('sprint,stage,kanban')
            ->andWhere('t1.deleted')->eq('0')
            ->andWhere('t1.vision')->eq($this->config->vision)
            ->andWhere('t1.multiple')->eq('1')
            ->andWhere('t1.grade')->eq('1')
            ->beginIF(!$this->app->user->admin)->andWhere('t1.id')->in($this->app->user->view->sprints)->fi()
            ->beginIF(!empty($executionQuery))->andWhere($executionQuery)->fi()
            ->beginIF($productID)->andWhere('t3.product')->eq($productID)->fi()
            ->beginIF($projectID)->andWhere('t1.project')->eq($projectID)->fi()
            ->beginIF(!in_array($browseType, array('all', 'undone', 'involved', 'review', 'bySearch')))->andWhere('t1.status')->eq($browseType)->fi()
            ->beginIF($browseType == 'undone')->andWhere('t1.status')->notIN('done,closed')->fi()
            ->beginIF($browseType == 'review')
            ->andWhere("FIND_IN_SET('{$this->app->user->account}', t1.reviewers)")
            ->andWhere('t1.reviewStatus')->eq('doing')
            ->fi()
            ->orderBy($orderBy)
            ->page($pager, 't1.id')
            ->fetchAll('id');

        /* Get child executions. */
        $childExecutions = $this->dao->select('t1.*,t2.name projectName, t2.model as projectModel')->from(TABLE_EXECUTION)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->beginIF($productID)->leftJoin(TABLE_PROJECTPRODUCT)->alias('t3')->on('t1.id=t3.project')->fi()
            ->where('t1.type')->in('sprint,stage,kanban')
            ->beginIF($projectID)->andWhere('t1.project')->eq($projectID)->fi()
            ->andWhere('t1.deleted')->eq('0')
            ->andWhere('t1.vision')->eq($this->config->vision)
            ->andWhere('t1.multiple')->eq('1')
            ->andWhere('t1.grade')->gt('1')
            ->beginIF(!$this->app->user->admin)->andWhere('t1.id')->in($this->app->user->view->sprints)->fi()
            ->orderBy($orderBy)
            ->fetchAll('id');

        if(empty($productID) and !empty($parentExecutions)) $projectProductIdList = $this->dao->select('project, GROUP_CONCAT(product) as product')->from(TABLE_PROJECTPRODUCT)->where('project')->in(array_keys($parentExecutions))->groupBy('project')->fetchPairs();

        $productNameList = $this->dao->select('t1.id,GROUP_CONCAT(t3.`name`) as productName')->from(TABLE_EXECUTION)->alias('t1')
            ->leftjoin(TABLE_PROJECTPRODUCT)->alias('t2')->on('t1.id=t2.project')
            ->leftjoin(TABLE_PRODUCT)->alias('t3')->on('t2.product=t3.id')
            ->where('t1.project')->eq($projectID)
            ->andWhere('t1.type')->in('kanban,sprint,stage')
            ->groupBy('t1.id')
            ->fetchPairs();

        $executions = array_replace($parentExecutions, $childExecutions);

        $burns = $this->getBurnData($executions);

        if($withTasks) $executionTasks = $this->getTaskGroupByExecution(array_keys($executions));

        /* Process executions. */
        $this->app->loadConfig('task');

        $emptyHour  = array('totalEstimate' => 0, 'totalConsumed' => 0, 'totalLeft' => 0, 'progress' => 0);
        $today      = helper::today();
        $childList  = array();
        $parentList = array();
        foreach($executions as $key => $execution)
        {
            $execution->productName = isset($productNameList[$execution->id]) ? $productNameList[$execution->id] : '';

            /* Process the end time. */
            $execution->end = date(DT_DATE1, strtotime($execution->end));

            /* Judge whether the execution is delayed. */
            if($execution->status != 'done' and $execution->status != 'closed' and $execution->status != 'suspended')
            {
                $delay = helper::diffDate($today, $execution->end);
                if($delay > 0) $execution->delay = $delay;
            }

            /* Process the burns. */
            $execution->burns = array();
            $burnData = isset($burns[$execution->id]) ? $burns[$execution->id] : array();
            foreach($burnData as $data) $execution->burns[] = $data->value;

            if(isset($executionTasks) and isset($executionTasks[$execution->id]))
            {
                $tasks = array_chunk($executionTasks[$execution->id], $this->config->task->defaultLoadCount, true);
                $execution->tasks = $tasks[0];
            }

            /* In the case of the waterfall model, calculate the sub-stage. */
            if($param === 'skipParent')
            {
                if($execution->parent) $parentList[$execution->parent] = $execution->parent;
                if($execution->projectName) $execution->name = $execution->projectName . ' / ' . $execution->name;
            }
            elseif(strpos($param, 'hasParentName') !== false)
            {
                $parents = $this->dao->select('id,name')->from(TABLE_EXECUTION)->where('id')->in(trim($execution->path, ','))->andWhere('type')->in('stage,kanban,sprint')->orderBy('grade')->fetchPairs();
                $executions[$execution->id]->title = implode('/', $parents);
                if(strpos($param, 'skipParent') !== false)
                {
                    $children = $this->getChildExecutions($execution->id);
                    if(count($children) > 0) $parentList[$execution->id] = $execution->id;
                }
            }
            elseif(isset($executions[$execution->parent]))
            {
                $executions[$execution->parent]->children[$key] = $execution;
                $childList[$key] = $key;
            }

            /* Bind execution product */
            if(!empty($projectProductIdList) and !empty($projectProductIdList[$execution->id]))
            {
                $execution->product = $projectProductIdList[$execution->id];
            }
        }

        if(strpos($param, 'withchild') === false)
        {
            foreach($childList as $childID) unset($executions[$childID]);
        }

        foreach($parentList as $parentID) unset($executions[$parentID]);

        $parentExecutions = array_intersect_key($executions, $parentExecutions);

        return array_values($parentExecutions);
    }

    /**
     * Get executions by project.
     *
     * @param  int     $projectID
     * @param  string  $status
     * @param  int     $limit
     * @param  bool    $pairs
     * @param  bool    $devel
     * @param  int     $appendedID
     * @access public
     * @return array
     */
    public function getByProject($projectID, $status = 'all', $limit = 0, $pairs = false, $devel = false, $appendedID = 0)
    {
        if(defined('TUTORIAL')) return $this->loadModel('tutorial')->getExecutionPairs();

        $project    = $this->loadModel('project')->getByID($projectID);
        $executions = $this->dao->select('*')->from(TABLE_EXECUTION)
            ->where('type')->in('stage,sprint,kanban')
            ->andWhere('deleted')->eq('0')
            ->andWhere('vision')->eq($this->config->vision)
            ->beginIF($projectID)->andWhere('project')->eq((int)$projectID)->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->sprints)->fi()
            ->beginIF($status == 'undone')->andWhere('status')->notIN('done,closed')->fi()
            ->beginIF($status != 'all' and $status != 'undone' and $status != 'noclosed')->andWhere('status')->in($status)->fi()
            ->beginIF($status == 'noclosed')->andWhere('status')->ne('closed')->fi()
            ->beginIF($devel === true)->andWhere('attribute')->in('dev,qa,release')->fi()
            ->beginIF($appendedID)->orWhere('id')->eq($appendedID)->fi()
            ->orderBy('order_asc')
            ->beginIF($limit)->limit($limit)->fi()
            ->fetchAll('id');

        /* Add product name and parent stage name to stage name. */
        if(isset($project->model) and in_array($project->model, array('waterfall', 'waterfallplus', 'research')))
        {
            $executionProducts = array();
            if($project->hasProduct and $project->division)
            {
                $executionList = array();
                $executionProducts = $this->dao->select('t1.project, t2.name')->from(TABLE_PROJECTPRODUCT)->alias('t1')
                    ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
                    ->where('project')->in(array_keys($executions))
                    ->andWhere('t2.deleted')->eq(0)
                    ->fetchPairs();
            }

            $allExecutions = $this->dao->select('id,name,parent,grade')->from(TABLE_EXECUTION)
                ->where('type')->in('stage,sprint,kanban')
                ->andWhere('deleted')->eq('0')
                ->beginIf($projectID)->andWhere('project')->eq($projectID)->fi()
                ->fetchAll('id');

            $parents = array();
            foreach($allExecutions as $id => $execution) $parents[$execution->parent] = $execution->parent;

            $executions = $this->resetExecutionSorts($executions);
            foreach($executions as $id => $execution)
            {
                if(isset($parents[$execution->id]))
                {
                    unset($executions[$id]);
                    continue;
                }

                $executionName = '';
                $paths = array_slice(explode(',', trim($execution->path, ',')), 1);
                foreach($paths as $path)
                {
                    if(isset($allExecutions[$path])) $executionName .= '/' . $allExecutions[$path]->name;
                }

                if($executionName) $execution->name = ltrim($executionName, '/');
                if(isset($executionProducts[$id])) $execution->name = $executionProducts[$id] . '/' . $execution->name;
            }
        }

        $projects = array();
        if(empty($projectID))
        {
            $projects = $this->dao->select('t1.id,t1.name')->from(TABLE_PROJECT)->alias('t1')
                ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.id=t2.project')
                ->where('t2.id')->in(array_keys($executions))
                ->fetchPairs('id', 'name');
        }
        else
        {
            $projects = $this->dao->select('id,name')->from(TABLE_PROJECT)->where('id')->eq($projectID)->fetchPairs('id', 'name');
        }

        if($pairs)
        {
            $this->app->loadLang('project');
            $executionPairs = array();
            foreach($executions as $execution)
            {
                $executionPairs[$execution->id]  = '';
                $executionPairs[$execution->id] .= isset($projects[$execution->project]) ? ($projects[$execution->project] . '/') : '';
                $executionPairs[$execution->id] .= $execution->name;

                if(empty($execution->multiple)) $executionPairs[$execution->id] = $projects[$execution->project] . "({$this->lang->project->disableExecution})";
            }
            $executions = $executionPairs;
        }

        return $executions;
    }

    /**
     * Get no multiple execution id.
     *
     * @param  int    $projectID
     * @access public
     * @return int
     */
    public function getNoMultipleID($projectID)
    {
        return $this->dao->select('id')->from(TABLE_EXECUTION)->where('project')->eq($projectID)->andWhere('multiple')->eq(0)->andWhere('deleted')->eq(0)->fetch('id');
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
    public function getLink($module, $method, $extra)
    {
        $link = '';
        if($module == 'task' and ($method == 'view' || $method == 'edit' || $method == 'batchedit'))
        {
            $module = 'execution';
            $method = 'task';
        }
        if($module == 'testcase' and ($method == 'view' or $method == 'edit' or $method == 'batchedit'))
        {
            $module = 'execution';
            $method = 'testcase';
        }
        if($module == 'testtask')
        {
            $module = 'execution';
            $method = 'testtask';
        }
        if($module == 'build' and ($method == 'edit' or $method == 'view'))
        {
            $module = 'execution';
            $method = 'build';
        }
        if($module == 'story')
        {
            $module = 'execution';
            $method = 'story';
        }
        if($module == 'product' and $method == 'showerrornone')
        {
            $module = 'execution';
            $method = 'task';
        }

        if($module == 'execution' and $method == 'create') return;
        if($extra != '')
        {
            $link = helper::createLink($module, $method, "executionID=%s&type=$extra");
        }
        elseif($module == 'execution' and $method == 'storyview')
        {
            $link = helper::createLink($module, 'story', "executionID=%s");
        }
        elseif($module == 'execution' and ($method == 'index' or $method == 'all'))
        {
            $link = helper::createLink($module, 'task', "executionID=%s");
        }
        elseif($module == 'bug' and $method == 'create' and $this->app->tab == 'execution')
        {
            $link = helper::createLink($module, $method, "productID=0&branch=0&extra=executionID=%s");
        }
        elseif(in_array($module, array('bug', 'case', 'testtask', 'testreport')) and strpos(',view,edit,', ",$method,") !== false)
        {
            $link = helper::createLink('execution', $module, "executionID=%s");
        }
        elseif($module == 'repo' && $method == 'review')
        {
            $link = helper::createLink('repo', 'review', "repoID=0&browseType=all&executionID=%s") . '#app=execution';
        }
        elseif($module == 'mr')
        {
            $link = helper::createLink('mr', 'browse', "repoID=0&mode=status&param=opened&objectID=%s") . '#app=execution';
        }
        elseif($module == 'repo')
        {
            $link = helper::createLink('repo', 'browse', "repoID=0&branchID=&executionID=%s") . '#app=execution';
        }
        elseif($module == 'doc')
        {
            $link = helper::createLink('doc', $method, "type=execution&objectID=%s&from=execution");
        }
        elseif(in_array($module, array('issue', 'risk', 'opportunity', 'pssp', 'auditplan', 'nc', 'meeting')))
        {
            $link = helper::createLink($module, 'browse', "executionID=%s&from=execution");
        }
        elseif($module == 'testreport' and $method == 'create')
        {
            $link = helper::createLink('execution', 'testtask', "executionID=%s");
        }
        else
        {
            $link = helper::createLink($module, $method, "executionID=%s");
        }

        return $link;
    }

    /**
     * Get branches of execution.
     *
     * @param  int    $executionID
     * @access public
     * @return array
     */
    public function getBranches($executionID)
    {
        $productBranchPairs = $this->dao->select('product, branch')->from(TABLE_PROJECTPRODUCT)
            ->where('project')->eq($executionID)
            ->fetchPairs();
        $branches = $this->loadModel('branch')->getByProducts(array_keys($productBranchPairs));
        foreach($productBranchPairs as $product => $branch)
        {
            if($branch == 0 and isset($branches[$product])) $productBranchPairs[$product] = join(',', array_keys($branches[$product]));
        }

        return $productBranchPairs;
    }

    /**
     * Get executions tree data
     * @param  int     $executionID
     * @access public
     * @return array
     */
    public function getTree($executionID)
    {
        $fullTrees = $this->loadModel('tree')->getTaskStructure($executionID, 0);
        array_unshift($fullTrees, array('id' => 0, 'name' => '/', 'type' => 'task', 'actions' => false, 'root' => $executionID));
        foreach($fullTrees as $i => $tree)
        {
            $tree = (object)$tree;
            if($tree->type == 'product') array_unshift($tree->children, array('id' => 0, 'name' => '/', 'type' => 'story', 'actions' => false, 'root' => $tree->root));
            $fullTree = $this->fillTasksInTree($tree, $executionID);
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
     * Get full name by path.
     * @param  string $executionPath
     * @access public
     * @return string
     */
    public function getFullNameByPath($executionPath)
    {
        $paths         = explode(',', trim($executionPath, ','));
        $executions    = $this->dao->select('id,name')->from(TABLE_EXECUTION)->where('id')->in($paths)->andWhere('type')->notin('program,project')->fetchPairs();
        $executionName = array();
        foreach($paths as $path)
        {
            if(isset($executions[$path])) $executionName[] = $executions[$path];
        }

        return implode('/', $executionName);
    }

    /**
     * Get full name of executions.
     * @param  array $executions
     * @access public
     * @return array
     */
    public function getFullNameList($executions)
    {
        $allExecutions = $this->dao->select('id,name,parent')->from(TABLE_EXECUTION)
            ->where('type')->notin(array('program', 'project'))
            ->andWhere('deleted')->eq('0')
            ->fetchAll('id');

        $nameList = array();
        foreach($executions as $executionID => $execution)
        {
            if($execution->grade <= 1)
            {
                $nameList[$executionID] = $execution->name;
                continue;
            }

            /* Set execution name. */
            $paths = array_slice(explode(',', trim($execution->path, ',')), 1);
            $executionName = array();
            foreach($paths as $path)
            {
                if(isset($allExecutions[$path])) $executionName[] = $allExecutions[$path]->name;
            }

            $nameList[$executionID] = implode('/', $executionName);
        }

        return $nameList;
    }

    /**
     * Get related executions
     *
     * @param  int    $executionID
     * @access public
     * @return array
     */
    public function getRelatedExecutions($executionID)
    {
        $products = $this->dao->select('product')->from(TABLE_PROJECTPRODUCT)->where('project')->eq((int)$executionID)->fetchAll('product');
        // $products   = $this->dao->select('product')->from(TABLE_PROJECTPRODUCT)->fetchAll('product');
        if(!$products) return array();
        $products = array_keys($products);
        return $this->dao->select('t1.id, t1.name')->from(TABLE_EXECUTION)->alias('t1')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')
            ->on('t1.id = t2.project')
            ->where('t2.product')->in($products)
            ->andWhere('t1.id')->ne((int)$executionID)
            ->andWhere('t1.deleted')->eq(0)
            ->orderBy('t1.id')
            ->fetchPairs();
    }

    /**
     * Get child executions.
     *
     * @param  int    $executionID
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getChildExecutions($executionID, $orderBy = 'id_desc')
    {
        return $this->dao->select('*')->from(TABLE_EXECUTION)
            ->where('deleted')->eq(0)
            ->andWhere('parent')->eq((int)$executionID)
            ->orderBy($orderBy)
            ->fetchAll('id');
    }

    /**
     * Check the privilege.
     *
     * @access public
     * @return bool
     */
    public function getLimitedExecution()
    {
        /* If is admin, return true. */
        if($this->app->user->admin) return true;

        /* Get all teams of all executions and group by executions, save it as static. */
        $executions = $this->dao->select('root, limited')->from(TABLE_TEAM)
            ->where('type')->eq('execution')
            ->andWhere('account')->eq($this->app->user->account)
            ->andWhere('limited')->eq('yes')
            ->orderBy('root asc')
            ->fetchPairs('root', 'root');

        $_SESSION['limitedExecutions'] = join(',', $executions);
    }

    /**
     * Get executions lists grouped by product.
     *
     * @access public
     * @return array
     */
    public function getProductGroupList()
    {
        $list = $this->dao->select('t1.id,t1.name,t1.status,t2.product')->from(TABLE_EXECUTION)->alias('t1')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')->on('t1.id = t2.project')
            ->where('t1.deleted')->eq(0)
            ->beginIF(!$this->app->user->admin)->andWhere('t1.id')->in($this->app->user->view->sprints)->fi()
            ->fetchGroup('product');

        $noProducts = array();
        foreach($list as $id => $product)
        {
            foreach($product as $ID => $execution)
            {
                if(!$execution->product)
                {
                    if($this->checkPriv($execution->id)) $noProducts[] = $execution;
                    unset($list[$id][$ID]);
                }
            }
        }
        unset($list['']);
        $list[''] = $noProducts;

        return $list;
    }

    /**
     * Get tasks.
     *
     * @param  int    $productID
     * @param  int    $executionID
     * @param  array  $executions
     * @param  string $browseType
     * @param  int    $queryID
     * @param  int    $moduleID
     * @param  string $sort
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getTasks($productID, $executionID, $executions, $browseType, $queryID, $moduleID, $sort, $pager)
    {
        $this->loadModel('task');

        /* Set modules and $browseType. */
        $modules = array();
        if($moduleID) $modules = $this->loadModel('tree')->getAllChildID($moduleID);
        if($browseType == 'bymodule' or $browseType == 'byproduct')
        {
            if(($this->session->taskBrowseType) and ($this->session->taskBrowseType != 'bysearch')) $browseType = $this->session->taskBrowseType;
        }

        /* Get tasks. */
        $tasks = array();
        if($browseType != "bysearch")
        {
            $queryStatus = $browseType == 'byexecution' ? 'all' : $browseType;
            if($queryStatus == 'unclosed')
            {
                $queryStatus = $this->lang->task->statusList;
                unset($queryStatus['closed']);
                $queryStatus = array_keys($queryStatus);
            }
            $tasks = $this->task->getExecutionTasks($executionID, $productID, $queryStatus, $modules, $sort, $pager);
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

            if(strpos($this->session->taskQuery, "deleted =") === false) $this->session->set('taskQuery', $this->session->taskQuery . " AND deleted = '0'");

            $taskQuery = $this->session->taskQuery;
            /* Limit current execution when no execution. */
            if(strpos($taskQuery, "`execution` =") === false) $taskQuery = $taskQuery . " AND `execution` = $executionID";
            $executionQuery = "`execution` " . helper::dbIN(array_keys($executions));
            $taskQuery      = str_replace("`execution` = 'all'", $executionQuery, $taskQuery); // Search all execution.
            $this->session->set('taskQueryCondition', $taskQuery, $this->app->tab);
            $this->session->set('taskOnlyCondition', true, $this->app->tab);

            $tasks = $this->getSearchTasks($taskQuery, $pager, $sort);
        }

        return $tasks;
    }

    /**
     * Get the task data group by execution id list.
     *
     * @param  array  $executionIdList
     * @access public
     * @return array
     */
    public function getTaskGroupByExecution($executionIdList = array())
    {
        if(empty($executionIdList)) return array();
        $executionTasks = $this->dao->select('*')->from(TABLE_TASK)
            ->where('deleted')->eq(0)
            ->andWhere('status')->notin('closed,cancel')
            ->andWhere('execution')->in($executionIdList)
            ->orderBy('order_asc')
            ->fetchGroup('execution', 'id');

        $taskIdList = array();
        foreach($executionTasks as $executionID => $tasks) $taskIdList = array_merge($taskIdList, array_keys($tasks));
        $taskIdList = array_unique($taskIdList);
        $teamGroups = $this->dao->select('id,task,account,status')->from(TABLE_TASKTEAM)->where('task')->in($taskIdList)->fetchGroup('task', 'id');

        foreach($executionTasks as $executionID => $tasks)
        {
            foreach($tasks as $task)
            {
                if(isset($teamGroups[$task->id])) $task->team = $teamGroups[$task->id];
                if($task->parent > 0 and isset($executionTasks[$executionID][$task->parent]))
                {
                    $executionTasks[$executionID][$task->parent]->children[$task->id] = $task;
                    unset($executionTasks[$executionID][$task->id]);
                }
            }
        }

        return $executionTasks;
    }

    /**
     * Get the execution by ID.
     *
     * @param  int    $executionID
     * @param  bool   $setImgSize
     * @access public
     * @return object
     */
    public function getByID($executionID, $setImgSize = false)
    {
        if(defined('TUTORIAL')) return $this->loadModel('tutorial')->getExecution();

        $execution = $this->dao->findById((int)$executionID)->from(TABLE_EXECUTION)->fetch();
        if(!$execution) return false;

        /* Judge whether the execution is delayed. */
        if($execution->status != 'done' and $execution->status != 'closed' and $execution->status != 'suspended')
        {
            $delay = helper::diffDate(helper::today(), $execution->end);
            if($delay > 0) $execution->delay = $delay;
        }

        $total = $this->dao->select('
            ROUND(SUM(estimate), 2) AS totalEstimate,
            ROUND(SUM(consumed), 2) AS totalConsumed,
            ROUND(SUM(`left`), 2) AS totalLeft')
            ->from(TABLE_TASK)
            ->where('execution')->eq((int)$executionID)
            ->andWhere('deleted')->eq(0)
            ->andWhere('parent')->lt(1)
            ->fetch();
        $closedTotalLeft = $this->dao->select('ROUND(SUM(`left`), 2) AS totalLeft')->from(TABLE_TASK)
            ->where('execution')->eq((int)$executionID)
            ->andWhere('deleted')->eq(0)
            ->andWhere('parent')->lt(1)
            ->andWhere('status')->in('closed,cancel')
            ->fetch('totalLeft');

        $totalHours = $this->dao->select('sum(t1.days * t1.hours) AS totalHours')->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')
            ->on('t1.account=t2.account')
            ->where('t1.root')->eq($execution->id)
            ->andWhere('t1.type')->eq('execution')
            ->andWhere('t2.deleted')->eq(0)
            ->fetch('totalHours');

        $execution->totalHours    = $totalHours;
        $execution->days          = $execution->days ? $execution->days : '';
        $execution->totalEstimate = round((float)$total->totalEstimate, 1);
        $execution->totalConsumed = round((float)$total->totalConsumed, 1);
        $execution->totalLeft     = round((float)$total->totalLeft - (float)$closedTotalLeft, 1);

        $execution = $this->loadModel('file')->replaceImgURL($execution, 'desc');
        if($setImgSize) $execution->desc = $this->file->setImgSize($execution->desc);

        return $execution;
    }

    /**
     * Get execution by build id.
     *
     * @param  int    $buildID
     * @access public
     * @return object
     */
    public function getByBuild($buildID)
    {
        $build = $this->loadModel('build')->getById($buildID);
        return $this->getById($build->execution);
    }

    /**
     * Get the default managers for a execution from it's related products.
     *
     * @param  int    $executionID
     * @access public
     * @return object
     */
    public function getDefaultManagers($executionID)
    {
        $managers = $this->dao->select('PO,QD,RD')->from(TABLE_PRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')->on('t1.id = t2.product')
            ->where('t2.project')->eq($executionID)
            ->fetch();
        if($managers) return $managers;

        $managers = new stdclass();
        $managers->PO = '';
        $managers->QD = '';
        $managers->RD = '';
        return $managers;
    }

    /**
     * Get branch pairs by product id list.
     *
     * @param  array        $products
     * @param  int          $projectID
     * @param  string       $param
     * @param  string|array $appendBranch
     * @access public
     * @return array
     */
    public function getBranchByProduct($products, $projectID = 0, $param = 'noclosed', $appendBranch = '')
    {
        $branchGroups = $this->loadModel('branch')->getByProducts($products, $param, $appendBranch);

        if($projectID)
        {
            $projectProducts = $this->loadModel('project')->getBranchesByProject($projectID);
            foreach($branchGroups as $productID => $branchPairs)
            {
                foreach($branchPairs as $branchID => $branchName)
                {
                    if(strpos($param, 'withMain') !== false and $branchID == BRANCH_MAIN) continue;
                    if(!isset($projectProducts[$productID][$branchID])) unset($branchGroups[$productID][$branchID]);
                }
            }
        }
        return $branchGroups;
    }

    /**
     * Get ordered executions.
     *
     * @param  int    $executionID
     * @param  string $status
     * @param  int    $num
     * @param  string $param
     * @access public
     * @return array
     */
    public function getOrderedExecutions($executionID, $status, $num = 0, $param = '')
    {
        $executionList = $this->getList($executionID, 'all', $status);
        if(empty($executionList)) return $executionList;

        $executions       = $mineExecutions = $otherExecutions = $closedExecutions = array();
        $param            = strtolower($param);
        if($param == 'skipparent')
        {
            $parentExecutions = array();
            foreach($executionList as $execution) $parentExecutions[$execution->parent] = $execution->parent;
        }

        foreach($executionList as $execution)
        {
            if(empty($execution->multiple)) continue;
            if(!$this->app->user->admin and !$this->checkPriv($execution->id)) continue;
            if($param == 'skipparent' and isset($parentExecutions[$execution->id])) continue;

            if($execution->status != 'done' and $execution->status != 'closed' and $execution->PM == $this->app->user->account)
            {
                $mineExecutions[$execution->id] = $execution;
            }
            elseif($execution->status != 'done' and $execution->status != 'closed' and !($execution->PM == $this->app->user->account))
            {
                $otherExecutions[$execution->id] = $execution;
            }
            elseif($execution->status == 'done' or $execution->status == 'closed')
            {
                $closedExecutions[$execution->id] = $execution;
            }
        }
        $executions = $mineExecutions + $otherExecutions + $closedExecutions;

        if(empty($num)) return $executions;
        return array_slice($executions, 0, $num, true);
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
     * @param  object $execution
     * @param  string $storyType
     * @access public
     * @return void
     */
    public function buildStorySearchForm($products, $branchGroups, $modules, $queryID, $actionURL, $type = 'executionStory', $execution = null, $storyType = 'story')
    {
        $this->app->loadLang('branch');
        $branchPairs  = array(BRANCH_MAIN => $this->lang->branch->main);
        $productType  = 'normal';
        $productNum   = count($products);
        $productPairs = array(0 => '');
        $branches     = empty($execution) ? array() : $this->loadModel('project')->getBranchesByProject($execution->id);

        foreach($products as $product)
        {
            $productPairs[$product->id] = $product->name;
            if($product->type != 'normal')
            {
                $productType = $product->type;
                if(isset($branches[$product->id]))
                {
                    foreach($branches[$product->id] as $branchID => $branch)
                    {
                        if(!isset($branchGroups[$product->id][$branchID])) continue;
                        if($branchID != BRANCH_MAIN) $branchPairs[$branchID] = ((count($products) > 1) ? $product->name . '/' : '') . $branchGroups[$product->id][$branchID];
                    }
                }
            }
        }

        /* Build search form. */
        if($type == 'executionStory') $this->config->product->search['module'] = 'executionStory';

        $this->config->product->search['fields']['title'] = str_replace($this->lang->SRCommon, $this->lang->SRCommon, $this->lang->story->title);
        $this->config->product->search['actionURL'] = $actionURL;
        $this->config->product->search['queryID']   = $queryID;
        $this->config->product->search['params']['product']['values'] = $productPairs + array('all' => $this->lang->product->allProductsOfProject);
        $this->config->product->search['params']['stage']['values']   = array('' => '') + $this->lang->story->stageList;

        $this->loadModel('productplan');
        $plans     = array();
        $planPairs = array('' => '');
        foreach($products as $productID => $product)
        {
            $plans = $this->productplan->getBranchPlanPairs($productID, array(BRANCH_MAIN) + $product->branches, 'unexpired', true);
            foreach($plans as $plan) $planPairs += $plan;
        }
        $this->config->product->search['params']['plan']['values']   = $planPairs;
        $this->config->product->search['params']['module']['values'] = array('' => '') + $modules;
        if($productType == 'normal')
        {
            unset($this->config->product->search['fields']['branch']);
            unset($this->config->product->search['params']['branch']);
        }
        else
        {
            $this->config->product->search['fields']['branch'] = sprintf($this->lang->product->branch, $this->lang->product->branchName[$productType]);
            $this->config->product->search['params']['branch']['values'] = array('' => '') + $branchPairs;
        }

        $this->config->product->search['params']['status'] = array('operator' => '=', 'control' => 'select', 'values' => $this->lang->story->statusList);

        $project = $execution;
        if(strpos('sprint,stage,kanban', $execution->type) !== false) $project = $this->loadModel('project')->getByID($execution->project);
        if(isset($project->hasProduct) && empty($project->hasProduct))
        {
            unset($this->config->product->search['fields']['product']);

            if($project->model != 'kanban') unset($this->config->product->search['fields']['plan']);
        }

        if($storyType == 'requirement')
        {
            unset($this->config->product->search['fields']['plan']);
            if($project->model == 'ipd')
            {
                unset($this->config->product->search['fields']['stage']);
                unset($this->config->product->search['fields']['status']);
            }
        }

        $this->loadModel('search')->setSearchParams($this->config->product->search);
    }

    /**
     * Get executions to import
     *
     * @param  array  $executionIds
     * @param  string $type sprint|stage|kanban
     * @param  string $model
     * @access public
     * @return array
     */
    public function getToImport($executionIds, $type, $model = '')
    {
        return $this->dao->select("t1.id,concat_ws(' / ', t2.name, t1.name) as name")->from(TABLE_EXECUTION)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t2.id=t1.project')
            ->where('t1.id')->in($executionIds)
            ->beginIF(!$this->app->user->admin)->andWhere('t1.id')->in($this->app->user->view->sprints)->fi()
            ->beginIF(empty($model) or strpos(',waterfallplus,agileplus,', ",$model,") === false)->andWhere('t1.type')->eq($type)->fi()
            ->beginIF(!empty($model) and $model == 'agileplus')->andWhere('t1.type')->in(array('sprint', 'kanban'))->fi()
            ->andWhere('t1.deleted')->eq(0)
            ->orderBy('t1.id desc')
            ->fetchPairs('id', 'name');
    }

    /**
     * Update products of a execution.
     *
     * @param  int    $executionID
     * @param  array  $products
     * @access public
     * @return void
     */
    public function updateProducts($executionID, $products = '')
    {
        $this->loadModel('user');
        $products    = isset($_POST['products']) ? $_POST['products'] : $products;
        $oldProducts = $this->dao->select('*')->from(TABLE_PROJECTPRODUCT)->where('project')->eq((int)$executionID)->fetchGroup('product', 'branch');
        $this->dao->delete()->from(TABLE_PROJECTPRODUCT)->where('project')->eq((int)$executionID)->exec();
        $members = array_keys($this->getTeamMembers($executionID));
        if(empty($products))
        {
            $this->user->updateUserView(array_keys($oldProducts), 'product', $members);
            return true;
        }

        $branches = isset($_POST['branch']) ? $_POST['branch'] : array();
        $plans    = isset($_POST['plans']) ? $_POST['plans'] : array();;

        $existedProducts = array();
        foreach($products as $i => $productID)
        {
            if(empty($productID)) continue;
            if(!isset($existedProducts[$productID])) $existedProducts[$productID] = array();

            $oldPlan = 0;
            $branch  = isset($branches[$i]) ? $branches[$i] : 0;

            if(!is_array($branch)) $branch = array($branch);

            foreach($branch as $branchID)
            {
                if(isset($existedProducts[$productID][$branchID])) continue;

                if(isset($oldProducts[$productID][$branchID]))
                {
                    $oldProduct = $oldProducts[$productID][$branchID];
                    if($this->app->rawMethod != 'edit') $oldPlan = $oldProduct->plan;
                }

                $data = new stdclass();
                $data->project = $executionID;
                $data->product = $productID;
                $data->branch  = $branchID;
                $data->plan    = isset($plans[$productID]) ? implode(',', $plans[$productID]) : $oldPlan;
                $data->plan    = trim($data->plan, ',');
                $data->plan    = empty($data->plan) ? 0 : ",$data->plan,";
                $this->dao->insert(TABLE_PROJECTPRODUCT)->data($data)->exec();
                $existedProducts[$productID][$branchID] = true;
            }
        }

        $oldProductKeys = array_keys($oldProducts);
        $needUpdate = array_merge(array_diff($oldProductKeys, $products), array_diff($products, $oldProductKeys));
        if($needUpdate) $this->user->updateUserView($needUpdate, 'product', $members);
    }

    /**
     * Get tasks can be imported.
     *
     * @param  int    $toExecution
     * @param  array  $branches
     * @access public
     * @return array
     */
    public function getTasks2Imported($toExecution, $branches)
    {
        $execution       = $this->getById($toExecution);
        $project         = $this->loadModel('project')->getById($execution->project);
        $brotherProjects = $this->project->getBrotherProjects($project);
        $executions      = $this->dao->select('id')->from(TABLE_EXECUTION)
            ->where('project')->in($brotherProjects)
            ->andWhere('multiple')->eq('1')
            ->andWhere('status')->ne('closed')
            ->andWhere('deleted')->eq(0)
            ->fetchPairs('id');

        $branches = str_replace(',', "','", $branches);
        $tasks = $this->dao->select('t1.*, t2.id AS storyID, t2.title AS storyTitle, t2.version AS latestStoryVersion, t2.status AS storyStatus, t3.realname AS assignedToRealName')->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->leftJoin(TABLE_USER)->alias('t3')->on('t1.assignedTo = t3.account')
            ->where('t1.status')->in('wait,doing,pause,cancel')
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t1.parent')->lt(1)
            ->andWhere('t1.execution')->in(array_keys($executions))
            ->andWhere("(t1.story = 0 OR (t2.branch in ('0','" . join("','", $branches) . "') and t2.product " . helper::dbIN(array_keys($branches)) . "))")
            ->fetchGroup('execution', 'id');
        return $tasks;
    }

    /**
     * Import tasks.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function importTask($executionID)
    {
        $this->loadModel('task');

        $dateExceed  = '';
        $taskStories = array();
        $parents     = array();
        $execution   = $this->getByID($executionID);
        $tasks       = $this->dao->select('id,execution,assignedTo,story,consumed,status,parent,estStarted,deadline')->from(TABLE_TASK)->where('id')->in($this->post->tasks)->fetchAll('id');
        foreach($tasks as $task)
        {
            /* Save the assignedToes and stories, should linked to execution. */
            $assignedToes[$task->assignedTo] = $task->execution;
            $taskStories[$task->story]       = $task->story;

            if($task->parent < 0) $parents[$task->id] = $task->id;

            $data = new stdclass();
            $data->project   = $execution->project;
            $data->execution = $executionID;
            $data->status    = $task->consumed > 0 ? 'doing' : 'wait';

            if($task->status == 'cancel')
            {
                $data->canceledBy   = '';
                $data->canceledDate = null;
            }

            if(!empty($this->config->limitTaskDate))
            {
                if($task->estStarted < $execution->begin or $task->estStarted > $execution->end or $task->deadline > $execution->end or $task->deadline < $execution->begin) $dateExceed .= "#{$task->id},";
                if($task->estStarted < $execution->begin or $task->estStarted > $execution->end) $data->estStarted = $execution->begin;
                if($task->deadline > $execution->end or $task->deadline < $execution->begin)     $data->deadline   = $execution->end;
            }

            /* Update tasks. */
            $this->dao->update(TABLE_TASK)->data($data)->where('id')->eq($task->id)->exec();
            unset($data->status);
            $this->dao->update(TABLE_TASK)->data($data)->where('parent')->eq($task->id)->exec();

            $this->loadModel('action')->create('task', $task->id, 'moved', '', $task->execution);
        }

        if(!empty($dateExceed))
        {
            $dateExceed = trim($dateExceed, ',');
            echo js::alert(sprintf($this->lang->task->error->dateExceed, $dateExceed));
        }

        /* Get stories of children task. */
        if(!empty($parents))
        {
            $childrens = $this->dao->select('*')->from(TABLE_TASK)->where('parent')->in($parents)->fetchAll('id');
            foreach($childrens as $children) $taskStories[$children->story] = $children->story;
        }

        /* Remove empty story. */
        unset($taskStories[0]);

        /* Add members to execution team. */
        $teamMembers = $this->loadModel('user')->getTeamMemberPairs($executionID, 'execution');
        foreach($assignedToes as $account => $preExecutionID)
        {
            if(!isset($teamMembers[$account]))
            {
                $role = $this->dao->select('*')->from(TABLE_TEAM)
                    ->where('root')->eq($preExecutionID)
                    ->andWhere('type')->eq('execution')
                    ->andWhere('account')->eq($account)
                    ->fetch();

                $role->root = $executionID;
                $role->join = helper::today();
                $this->dao->replace(TABLE_TEAM)->data($role)->exec();
            }
        }

        /* Link stories. */
        $executionStories = $this->loadModel('story')->getExecutionStoryPairs($executionID);
        $lastOrder        = (int)$this->dao->select('*')->from(TABLE_PROJECTSTORY)->where('project')->eq($executionID)->orderBy('order_desc')->limit(1)->fetch('order');
        $stories          = $this->dao->select("id as story, product, version")->from(TABLE_STORY)->where('id')->in(array_keys($taskStories))->fetchAll('story');
        foreach($taskStories as $storyID)
        {
            if(!isset($executionStories[$storyID]))
            {
                $story = zget($stories, $storyID, '');
                if(empty($story)) continue;

                $story->project = $executionID;
                $story->order   = ++$lastOrder;
                $this->dao->insert(TABLE_PROJECTSTORY)->data($story)->exec();
                if($execution->multiple or $execution->type == 'project') $this->action->create('story', $storyID, 'linked2execution', '', $executionID);
            }
        }
    }

    /**
     * Stat story, task, bug data for execution.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function statRelatedData($executionID)
    {
        $storyCount = $this->dao->select('count(t2.story) as storyCount')->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PROJECTSTORY)->alias('t2')->on('t1.id = t2.story')
            ->where('t2.project')->eq($executionID)
            ->andWhere('t1.deleted')->eq(0)
            ->fetch('storyCount');

        $taskCount = $this->dao->select('count(id) as taskCount')->from(TABLE_TASK)->where('execution')->eq($executionID)->andWhere('deleted')->eq(0)->fetch('taskCount');
        $bugCount  = $this->dao->select('count(id) as bugCount')->from(TABLE_BUG)->where('execution')->eq($executionID)->andWhere('deleted')->eq(0)->fetch('bugCount');

        $statData = new stdclass();
        $statData->storyCount = $storyCount;
        $statData->taskCount  = $taskCount;
        $statData->bugCount   = $bugCount;

        return $statData;
    }

    /**
     * Import task from Bug.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function importBug($executionID)
    {
        $this->loadModel('bug');
        $this->loadModel('task');
        $this->loadModel('story');

        $now = helper::now();

        $showAllModule = isset($this->config->execution->task->allModule) ? $this->config->execution->task->allModule : '';
        $modules       = $this->loadModel('tree')->getTaskOptionMenu($executionID, 0, 0, $showAllModule ? 'allModule' : '');

        $execution      = $this->getByID($executionID);
        $requiredFields = str_replace(',story,', ',', ',' . $this->config->task->create->requiredFields . ',');
        $requiredFields = trim($requiredFields, ',');

        $bugToTasks = fixer::input('post')->get();
        $bugs       = $this->bug->getByList(array_keys($bugToTasks->import));
        foreach($bugToTasks->import as $key => $value)
        {
            $bug = zget($bugs, $key, '');
            if(empty($bug)) continue;

            $task = new stdClass();
            $task->bug          = $bug;
            $task->project      = $execution->project;
            $task->execution    = $executionID;
            $task->story        = $bug->story;
            $task->storyVersion = $bug->storyVersion;
            $task->module       = isset($modules[$bug->module]) ? $bug->module : 0;
            $task->fromBug      = $key;
            $task->name         = $bug->title;
            $task->type         = 'devel';
            $task->pri          = $bugToTasks->pri[$key];
            $task->estStarted   = $bugToTasks->estStarted[$key];
            $task->deadline     = $bugToTasks->deadline[$key];
            $task->estimate     = $bugToTasks->estimate[$key];
            $task->consumed     = 0;
            $task->assignedTo   = '';
            $task->status       = 'wait';
            $task->openedDate   = $now;
            $task->openedBy     = $this->app->user->account;

            if($task->estimate !== '') $task->left = $task->estimate;
            if(strpos($requiredFields, 'estStarted') !== false and helper::isZeroDate($task->estStarted)) $task->estStarted = '';
            if(strpos($requiredFields, 'deadline') !== false and helper::isZeroDate($task->deadline)) $task->deadline = '';
            if(!empty($bugToTasks->assignedTo[$key]))
            {
                $task->assignedTo   = $bugToTasks->assignedTo[$key];
                $task->assignedDate = $now;
            }

            /* Check task required fields. */
            foreach(explode(',', $requiredFields) as $field)
            {
                if(empty($field))         continue;
                if(!isset($task->$field)) continue;
                if(!empty($task->$field)) continue;

                if($field == 'estimate' and strlen(trim($task->estimate)) != 0) continue;

                dao::$errors['message'][] = sprintf($this->lang->error->notempty, $this->lang->task->$field);
                return false;
            }

            if(!preg_match("/^[0-9]+(.[0-9]{1,3})?$/", $task->estimate) and !empty($task->estimate))
            {
                dao::$errors['message'][] = $this->lang->task->error->estimateNumber;
                return false;
            }

            if(!empty($this->config->limitTaskDate))
            {
                $this->task->checkEstStartedAndDeadline($executionID, $task->estStarted, $task->deadline);
                if(dao::isError()) return false;
            }

            $tasks[$key] = $task;
        }

        foreach($tasks as $key => $task)
        {
            $bug = $task->bug;
            unset($task->bug);

            if(!$bug->confirmed) $this->dao->update(TABLE_BUG)->set('confirmed')->eq(1)->where('id')->eq($bug->id)->exec();
            $this->dao->insert(TABLE_TASK)->data($task)->checkIF($task->estimate != '', 'estimate', 'float')->exec();

            if(dao::isError())
            {
                echo js::error(dao::getError());
                return print(js::reload('parent'));
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
                if(dao::isError()) return print(js::error(dao::getError()));
                $changes = common::createChanges($bug, $newBug);

                $actionID = $this->action->create('bug', $key, 'Assigned', '', $newBug->assignedTo);
                $this->action->logHistory($actionID, $changes);
            }
        }

        return $mails;
    }

    /**
     * Update childs.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function updateChilds($executionID)
    {
        $sql = "UPDATE " . TABLE_PROJECT . " SET parent = 0 WHERE parent = '$executionID'";
        $this->dbh->exec($sql);
        if(!isset($_POST['childs'])) return;
        $childs = array_unique($_POST['childs']);
        foreach($childs as $childExecutionID)
        {
            $sql = "UPDATE " . TABLE_PROJECT . " SET parent = '$executionID' WHERE id = '$childExecutionID'";
            $this->dbh->query($sql);
        }
    }

    /**
     * Change execution project.
     *
     * @param  int    $newProject
     * @param  int    $oldProject
     * @param  int    $executionID
     * @param  string $syncStories    yes|no
     * @access public
     * @return void
     */
    public function changeProject($newProject, $oldProject, $executionID, $syncStories = 'no')
    {
        if($newProject == $oldProject) return;

        $this->dao->update(TABLE_EXECUTION)->set('parent')->eq($newProject)->set('path')->eq(",$newProject,$executionID,")->where('id')->eq($executionID)->exec();

        $this->dao->update(TABLE_BUILD)->set('project')->eq($newProject)->where('project')->eq($oldProject)->andWhere('execution')->eq($executionID)->exec();
        $this->dao->update(TABLE_BUG)->set('project')->eq($newProject)->where('project')->eq($oldProject)->andWhere('execution')->eq($executionID)->exec();
        $this->dao->update(TABLE_CASE)->set('project')->eq($newProject)->where('project')->eq($oldProject)->andWhere('execution')->eq($executionID)->exec();
        $this->dao->update(TABLE_DOC)->set('project')->eq($newProject)->where('project')->eq($oldProject)->andWhere('execution')->eq($executionID)->exec();
        $this->dao->update(TABLE_DOCLIB)->set('project')->eq($newProject)->where('project')->eq($oldProject)->andWhere('execution')->eq($executionID)->exec();
        $this->dao->update(TABLE_TASK)->set('project')->eq($newProject)->where('project')->eq($oldProject)->andWhere('execution')->eq($executionID)->exec();
        $this->dao->update(TABLE_TESTREPORT)->set('project')->eq($newProject)->where('project')->eq($oldProject)->andWhere('execution')->eq($executionID)->exec();

        $executionTeam = $this->loadModel('user')->getTeamMemberPairs($executionID, 'execution');
        $projectTeam   = $this->user->getTeamMemberPairs($newProject, 'project');
        $addedAccounts = array();
        foreach($executionTeam as $account => $realname)
        {
            if(isset($projectTeam[$account])) continue;

            $member = new stdclass();
            $member->root    = (int)$newProject;
            $member->type    = 'project';
            $member->account = $account;
            $member->join    = helper::today();
            $member->days    = 0;
            $member->hours   = $this->config->execution->defaultWorkhours;
            $this->dao->replace(TABLE_TEAM)->data($member)->exec();

            $addedAccounts[$account] = $account;
        }

        $executionWhitelist = $this->loadModel('personnel')->getWhitelistAccount($executionID, 'sprint');
        $projectWhitelist   = $this->personnel->getWhitelistAccount($newProject, 'project');
        foreach($executionWhitelist as $account)
        {
            if(isset($projectWhitelist[$account])) continue;

            $whitelist = new stdclass();
            $whitelist->account    = $account;
            $whitelist->objectType = 'project';
            $whitelist->objectID   = (int)$newProject;
            $whitelist->type       = 'whitelist';
            $whitelist->source     = 'sync';
            $this->dao->replace(TABLE_ACL)->data($whitelist)->exec();

            $addedAccounts[$account] = $account;
        }

        /* Sync stories to new project. */
        if($syncStories == 'yes')
        {
            $this->loadModel('action');
            $projectLinkedStories   = $this->dao->select('*')->from(TABLE_PROJECTSTORY)->where('project')->eq($newProject)->fetchPairs('story', 'story');
            $executionLinkedStories = $this->dao->select('*')->from(TABLE_PROJECTSTORY)->where('project')->eq($executionID)->fetchAll();
            foreach($executionLinkedStories as $linkedStory)
            {
                if(isset($projectLinkedStories[$linkedStory->story])) continue;

                $linkedStory->project = $newProject;
                $this->dao->insert(TABLE_PROJECTSTORY)->data($linkedStory)->exec();
                $this->action->create('story', $linkedStory->story, 'linked2project', '', $newProject);
            }
        }

        if($addedAccounts) $this->loadModel('user')->updateUserView($newProject, 'project', $addedAccounts);
    }

    /**
     * Link story.
     *
     * @param int    $executionID
     * @param array  $stories
     * @param array  $products
     * @param string $extra
     * @param array  $lanes
     * @param string $storyType
     *
     * @access public
     * @return bool
     */
    public function linkStory($executionID, $stories = array(), $products = array(), $extra = '', $lanes = array(), $storyType = 'story')
    {
        if(empty($executionID)) return false;
        if(empty($stories)) $stories = $this->post->stories;
        if(empty($stories)) return false;
        if(empty($products)) $products = $this->post->products;

        $this->loadModel('action');
        $this->loadModel('kanban');
        $versions      = $this->loadModel('story')->getVersions($stories);
        $linkedStories = $this->dao->select('*')->from(TABLE_PROJECTSTORY)->where('project')->eq($executionID)->orderBy('order_desc')->fetchPairs('story', 'order');
        $lastOrder     = reset($linkedStories);
        $storyList     = $this->dao->select('id, status, branch')->from(TABLE_STORY)->where('id')->in(array_values($stories))->fetchAll('id');
        $execution     = $this->getById($executionID);

        $extra = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);
        foreach($stories as $key => $storyID)
        {
            $notAllowedStatus = $this->app->rawMethod == 'batchcreate' ? 'closed' : 'draft,reviewing,closed';
            if(strpos($notAllowedStatus, $storyList[$storyID]->status) !== false) continue;
            if(isset($linkedStories[$storyID])) continue;

            $laneID = isset($output['laneID']) ? $output['laneID'] : 0;
            if(!empty($lanes[$storyID])) $laneID = $lanes[$storyID];

            $columnID = $this->kanban->getColumnIDByLaneID($laneID, 'backlog');
            if(empty($columnID)) $columnID = isset($output['columnID']) ? $output['columnID'] : 0;

            if(!empty($laneID) and !empty($columnID)) $this->kanban->addKanbanCell($executionID, $laneID, $columnID, 'story', $storyID);

            $data = new stdclass();
            $data->project = $executionID;
            $data->product = (int)$products[$storyID];
            $data->branch  = $storyList[$storyID]->branch;
            $data->story   = $storyID;
            $data->version = $versions[$storyID];
            $data->order   = (int)++$lastOrder;
            $this->dao->replace(TABLE_PROJECTSTORY)->data($data)->exec();

            $this->story->setStage($storyID);
            $this->linkCases($executionID, (int)$products[$storyID], $storyID);

            $action = $execution->type == 'project' ? 'linked2project' : 'linked2execution';
            if($action == 'linked2execution' and $execution->type == 'kanban') $action = 'linked2kanban';
            if($execution->multiple or $execution->type == 'project') $this->action->create('story', $storyID, $action, '', $executionID);
            if($storyType == 'requirement' and $execution->model == 'ipd') $this->dao->update(TABLE_STORY)->set('status')->eq('developing')->where('id')->eq($storyID)->exec();
        }

        if(!isset($output['laneID']) or !isset($output['columnID'])) $this->kanban->updateLane($executionID, 'story');
    }

    /**
     * Link cases.
     *
     * @param  int    $executionID
     * @param  int    $productID
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function linkCases($executionID, $productID, $storyID)
    {
        $this->loadModel('action');
        $linkedCases   = $this->dao->select('*')->from(TABLE_PROJECTCASE)->where('project')->eq($executionID)->orderBy('order_desc')->fetchPairs('case', 'order');
        $lastCaseOrder = empty($linkedCases) ? 0 : reset($linkedCases);
        $cases         = $this->dao->select('id, version')->from(TABLE_CASE)->where('story')->eq($storyID)->fetchPairs();
        $execution     = $this->getById($executionID);
        foreach($cases as $caseID => $version)
        {
            if(isset($linkedCases[$caseID])) continue;

            $object = new stdclass();
            $object->project = $executionID;
            $object->product = $productID;
            $object->case    = $caseID;
            $object->version = $version;
            $object->order   = ++$lastCaseOrder;

            $this->dao->insert(TABLE_PROJECTCASE)->data($object)->exec();

            $action = $execution->type == 'project' ? 'linked2project' : 'linked2execution';
            if($execution->multiple or $execution->type == 'project') $this->action->create('case', $caseID, $action, '', $executionID);
        }
    }

    /**
     * Link all stories by execution.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function linkStories($executionID)
    {
        $plans = $this->dao->select('product, plan')->from(TABLE_PROJECTPRODUCT)
            ->where('project')->eq($executionID)
            ->fetchPairs('product', 'plan');

        $planStories  = array();
        $planProducts = array();
        $this->loadModel('story');
        if(!empty($plans))
        {
            $executionProducts = $this->loadModel('project')->getBranchesByProject($executionID);
            foreach($plans as $productID => $planIdList)
            {
                if(empty($planIdList)) continue;
                $planIdList = explode(',', $planIdList);
                $executionBranches = zget($executionProducts, $productID, array());
                foreach($planIdList as $planID)
                {
                    $planStory = $this->story->getPlanStories($planID);
                    if(!empty($planStory))
                    {
                        foreach($planStory as $id => $story)
                        {
                            if($story->status != 'active' or (!empty($story->branch) and !empty($executionBranches) and !isset($executionBranches[$story->branch])))
                            {
                                unset($planStory[$id]);
                                continue;
                            }
                            $planProducts[$story->id] = $story->product;
                        }
                        $planStories = array_merge($planStories, array_keys($planStory));
                    }
                }
            }
        }

        $projectID = $this->dao->select('project')->from(TABLE_EXECUTION)->where('id')->eq($executionID)->fetch('project');
        $this->session->set('project', $projectID);
        $this->linkStory($projectID, $planStories, $planProducts);

        $this->linkStory($executionID, $planStories, $planProducts);
    }

    /**
     * Unlink story.
     *
     * @param  int    $executionID
     * @param  int    $storyID
     * @param  int    $laneID
     * @param  int    $columnID
     * @access public
     * @return void
     */
    public function unlinkStory($executionID, $storyID, $laneID = 0, $columnID = 0)
    {
        $execution = $this->dao->findById($executionID)->from(TABLE_EXECUTION)->fetch();
        $storyType = $this->dao->findById($storyID)->from(TABLE_STORY)->fetch('type');
        if($execution->type == 'project')
        {
            $executions       = $this->dao->select('*')->from(TABLE_EXECUTION)->where('parent')->eq($executionID)->fetchAll('id');
            $executionStories = $this->dao->select('project,story')->from(TABLE_PROJECTSTORY)->where('story')->eq($storyID)->andWhere('project')->in(array_keys($executions))->fetchAll();
            if(!empty($executionStories)) return print(js::alert($this->lang->execution->notAllowedUnlinkStory));
        }
        $this->dao->delete()->from(TABLE_PROJECTSTORY)->where('project')->eq($executionID)->andWhere('story')->eq($storyID)->limit(1)->exec();

        /* In ipd project, unlink stories change it's status to launched. */
        if($execution->model == 'ipd' and $storyType == 'requirement') $this->dao->update(TABLE_STORY)->set('status')->eq('launched')->where('id')->eq($storyID)->exec();

        /* Resolve TABLE_KANBANCELL's field cards. */
        if($execution->type == 'kanban')
        {
            $cell = $this->dao->select('*')->from(TABLE_KANBANCELL)
                ->where('kanban')->eq($executionID)
                ->andWhere('`column`')->eq($columnID)
                ->andWhere('lane')->eq($laneID)
                ->fetch();
            /* Resolve signal ','. */
            $cell->cards = str_replace(",$storyID,", ',', $cell->cards);
            if(strlen($cell->cards) == 1) $cell->cards = '';
            $this->dao->update(TABLE_KANBANCELL)->data($cell)
                ->where('kanban')->eq($executionID)
                ->andWhere('`column`')->eq($columnID)
                ->andWhere('lane')->eq($laneID)
                ->exec();
    }

        $order   = 1;
        $stories = $this->dao->select('*')->from(TABLE_PROJECTSTORY)->where('project')->eq($executionID)->orderBy('order')->fetchAll();
        foreach($stories as $executionstory)
        {
            if($executionstory->order != $order) $this->dao->update(TABLE_PROJECTSTORY)->set('`order`')->eq($order)->where('project')->eq($executionID)->andWhere('story')->eq($executionstory->story)->exec();
            $order++;
        }

        $this->loadModel('story')->setStage($storyID);
        $this->unlinkCases($executionID, $storyID);
        $objectType = $execution->type == 'project' ? 'unlinkedfromproject' : 'unlinkedfromexecution';
        if($execution->multiple or $execution->type == 'project') $this->loadModel('action')->create('story', $storyID, $objectType, '', $executionID);

        /* Sync unlink story in no multiple execution. */
        if(empty($execution->multiple) and $execution->type != 'project')
        {
            $this->dao->delete()->from(TABLE_PROJECTSTORY)->where('project')->eq($execution->project)->andWhere('story')->eq($storyID)->limit(1)->exec();
            $this->loadModel('action')->create('story', $storyID, 'unlinkedfromproject', '', $execution->project);
        }

        $tasks = $this->dao->select('id')->from(TABLE_TASK)->where('story')->eq($storyID)->andWhere('execution')->eq($executionID)->andWhere('status')->in('wait,doing')->fetchPairs('id');
        foreach($tasks as $taskID)
        {
            if(empty($taskID)) continue;
            $changes  = $this->loadModel('task')->cancel($taskID);
            $actionID = $this->action->create('task', $taskID, 'Canceled');
            $this->action->logHistory($actionID, $changes);
        }
    }

    /**
     * Unlink cases.
     *
     * @param  int    $executionID
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function unlinkCases($executionID, $storyID)
    {
        $this->loadModel('action');
        $execution = $this->dao->findById($executionID)->from(TABLE_EXECUTION)->fetch();
        $cases     = $this->dao->select('id')->from(TABLE_CASE)->where('story')->eq($storyID)->fetchAll('id');
        foreach($cases as $caseID => $case)
        {
            $this->dao->delete()->from(TABLE_PROJECTCASE)->where('project')->eq($executionID)->andWhere('`case`')->eq($caseID)->limit(1)->exec();
            $action = $execution->type == 'project' ? 'unlinkedfromproject' : 'unlinkedfromexecution';
            if($execution->multiple or $execution->type == 'project') $this->action->create('case', $caseID, $action, '', $executionID);

            /* Sync unlink case in no multiple execution. */
            if(empty($execution->multiple) and $execution->type != 'project')
            {
                $this->dao->delete()->from(TABLE_PROJECTCASE)->where('project')->eq($execution->project)->andWhere('`case`')->eq($caseID)->limit(1)->exec();
                $this->loadModel('action')->create('case', $caseID, 'unlinkedfromproject', '', $execution->project);
            }
        }

        $order = 1;
        $cases = $this->dao->select('*')->from(TABLE_PROJECTCASE)->where('project')->eq($executionID)->orderBy('order')->fetchAll();
        foreach($cases as $case)
        {
            if($case->order != $order) $this->dao->update(TABLE_PROJECTCASE)->set('`order`')->eq($order)->where('project')->eq($executionID)->andWhere('`case`')->eq($case->case)->exec();
            $order++;
        }
    }

    /**
     * Get team members.
     *
     * @param  int    $executionID
     * @access public
     * @return array
     */
    public function getTeamMembers($executionID)
    {
        if(defined('TUTORIAL')) return $this->loadModel('tutorial')->getTeamMembers();

        return $this->dao->select("t1.*, t1.hours * t1.days AS totalHours, t2.id as userID, if(t2.deleted='0', t2.realname, t1.account) as realname")->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account = t2.account')
            ->where('t1.root')->eq((int)$executionID)
            ->andWhere('t1.type')->eq('execution')
            ->andWhere('t2.deleted')->eq('0')
            ->beginIF($this->config->vision)->andWhere("CONCAT(',', t2.visions, ',')")->like("%,{$this->config->vision},%")->fi()
            ->fetchAll('account');
    }

    /**
     * Get members by execution id list.
     *
     * @param  array $executionIdList
     * @access public
     * @return void
     */
    public function getMembersByIdList($executionIdList)
    {
        return $this->dao->select("t1.root, t1.account, t2.realname")->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account = t2.account')
            ->where('t1.root')->in($executionIdList)
            ->andWhere('t1.type')->eq('execution')
            ->andWhere('t2.deleted')->eq('0')
            ->fetchGroup('root');
    }

    /**
     * Get teams which can be imported.
     *
     * @param  string $account
     * @param  int    $currentExecution
     * @access public
     * @return array
     */
    public function getTeams2Import($account, $currentExecution)
    {
        $execution = $this->dao->findById($currentExecution)->from(TABLE_EXECUTION)->fetch();
        return $this->dao->select('t1.root, t2.name as executionName')
            ->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.root = t2.id')
            ->where('t1.account')->eq($account)
            ->andWhere('t1.root')->ne($currentExecution)
            ->andWhere('t1.type')->eq('execution')
            ->andWhere('t2.project')->eq($execution->project)
            ->andWhere('t2.deleted')->eq('0')
            ->groupBy('t1.root')
            ->orderBy('t1.root DESC')
            ->fetchPairs();
    }

    /**
     * Get members of a execution who can be imported.
     *
     * @param  int    $execution
     * @param  array  $currentMembers
     * @access public
     * @return array
     */
    public function getMembers2Import($execution, $currentMembers)
    {
        if($execution == 0) return array();

        return $this->dao->select('account, role, hours')
            ->from(TABLE_TEAM)
            ->where('root')->eq($execution)
            ->andWhere('type')->in('project,execution')
            ->andWhere('account')->notIN($currentMembers)
            ->fetchAll('account');
    }

    /**
     * Get projects and executions that copy the team.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getCanCopyObjects($projectID = 0)
    {
        if(empty($projectID)) return array();

        $objectPairs = $this->dao->select('id,name')->from(TABLE_PROJECT)
            ->where('deleted')->eq(0)
            ->andWhere('project', true)->eq($projectID)
            ->andWhere('type')->ne('project')
            ->markRight(1)
            ->orWhere('id')->eq($projectID)
            ->orderBy('type_asc,openedDate_desc')
            ->limit('11')
            ->fetchPairs();

        $countPairs = $this->dao->select('root, COUNT(*) as count')->from(TABLE_TEAM)
            ->where('( type')->eq('project')
            ->andWhere('root')->eq($projectID)
            ->markRight(1)
            ->orWhere('( type')->eq('execution')
            ->andWhere('root')->in(array_keys($objectPairs))
            ->markRight(1)
            ->groupBy('root')
            ->fetchPairs('root');

        foreach($objectPairs as $objectID => $objectName)
        {
            $memberCount = zget($countPairs, $objectID, 0);
            $countTip    = $memberCount > 1 ? str_replace('member', 'members', $this->lang->execution->countTip) : $this->lang->execution->countTip;
            $objectPairs[$objectID] = $objectName . sprintf($countTip, $memberCount);
        }

        return $objectPairs;
    }

    /**
     * Manage team members.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function manageMembers($executionID)
    {
        $execution = $this->getByID($executionID);
        $data      = (array)fixer::input('post')->get();

        extract($data);
        $executionID   = (int)$executionID;
        $executionType = 'execution';
        $accounts      = array_unique($accounts);
        $limited       = array_values($limited);
        $oldJoin       = $this->dao->select('`account`, `join`')->from(TABLE_TEAM)->where('root')->eq($executionID)->andWhere('type')->eq($executionType)->fetchPairs();

        foreach($accounts as $key => $account)
        {
            if(empty($account)) continue;

            if(!empty($execution->days) and (int)$days[$key] > $execution->days)
            {
                dao::$errors['message'][] = sprintf($this->lang->execution->daysGreaterProject, $execution->days);
                return false;
            }
            if((float)$hours[$key] > 24)
            {
                dao::$errors['message'][] = $this->lang->execution->errorHours;
                return false;
            }
        }

        $this->dao->delete()->from(TABLE_TEAM)->where('root')->eq($executionID)->andWhere('type')->eq($executionType)->exec();

        $executionMember = array();
        foreach($accounts as $key => $account)
        {
            if(empty($account)) continue;

            $member = new stdclass();
            $member->role    = $roles[$key];
            $member->days    = $days[$key];
            $member->hours   = $hours[$key];
            $member->limited = $limited[$key];

            $member->root    = $executionID;
            $member->account = $account;
            $member->join    = isset($oldJoin[$account]) ? $oldJoin[$account] : helper::today();
            $member->type    = $executionType;

            $executionMember[$account] = $member;
            $this->dao->insert(TABLE_TEAM)->data($member)->exec();
        }

        /* Only changed account update userview. */
        $oldAccounts     = array_keys($oldJoin);
        $changedAccounts = array_diff($accounts, $oldAccounts);
        $changedAccounts = array_merge($changedAccounts, array_diff($oldAccounts, $accounts));
        $changedAccounts = array_unique($changedAccounts);

        /* Add the execution team members to the project. */
        if($execution->project) $this->addProjectMembers($execution->project, $executionMember);
        if($execution->acl != 'open') $this->updateUserView($executionID, 'sprint', $changedAccounts);
    }

    /**
     * Add the execution team members to the project.
     *
     * @param  int    $projectID
     * @param  array  $members
     * @access public
     * @return void
     */
    public function addProjectMembers($projectID = 0, $members = array())
    {
        $projectType = 'project';
        $oldJoin     = $this->dao->select('`account`, `join`')->from(TABLE_TEAM)->where('root')->eq($projectID)->andWhere('type')->eq($projectType)->fetchPairs();

        $accounts = array();
        foreach($members as $account => $member)
        {
            if(isset($oldJoin[$member->account])) continue;

            $accounts[]   = $member->account;
            $member->root = $projectID;
            $member->type = $projectType;
            $this->dao->insert(TABLE_TEAM)->data($member)->exec();
        }

        /* Only changed account update userview. */
        $oldAccounts     = array_keys($oldJoin);
        $changedAccounts = array_diff($accounts, $oldAccounts);
        $changedAccounts = array_merge($changedAccounts, array_diff($oldAccounts, $accounts));
        $changedAccounts = array_unique($changedAccounts);

        if($changedAccounts)
        {
            $this->loadModel('user')->updateUserView($projectID, $projectType, $changedAccounts);
            $linkedProducts = $this->dao->select("t2.id")->from(TABLE_PROJECTPRODUCT)->alias('t1')
                ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
                ->where('t2.deleted')->eq(0)
                ->andWhere('t1.project')->eq($projectID)
                ->andWhere('t2.vision')->eq($this->config->vision)
                ->fetchPairs();

            if(!empty($linkedProducts)) $this->user->updateUserView(array_keys($linkedProducts), 'product', $changedAccounts);
        }
    }

    /**
     * Unlink a member.
     *
     * @param  int    $sprintID
     * @param  string $account
     * @access public
     * @return void
     */
    public function unlinkMember($sprintID, $account)
    {
        $sprint = $this->getByID($sprintID);
        $type   = strpos(',stage,sprint,kanban,', ",$sprint->type,") !== false ? 'execution' : $sprint->type;

        $this->dao->delete()->from(TABLE_TEAM)->where('root')->eq((int)$sprintID)->andWhere('type')->eq($type)->andWhere('account')->eq($account)->exec();
        $this->updateUserView($sprintID, 'sprint', array($account));

        /* Remove team members from the sprint or stage, and determine whether to remove team members from the execution. */
        if(strpos(',stage,sprint,kanban,', ",$sprint->type,") !== false)
        {
            $teamMember = $this->dao->select('t1.id, t2.account')->from(TABLE_EXECUTION)->alias('t1')
                ->leftJoin(TABLE_TEAM)->alias('t2')->on('t1.id = t2.root')
                ->where('t1.project')->eq($sprint->project)
                ->andWhere('t1.type')->eq($sprint->type)
                ->andWhere('t2.account')->eq($account)
                ->fetch();
            if(empty($teamMember))
            {
                $this->dao->delete()->from(TABLE_TEAM)->where('root')->eq($sprint->project)->andWhere('type')->eq('project')->andWhere('account')->eq($account)->exec();
                $this->loadModel('user')->updateUserView($sprint->project, 'project', array($account));
                $linkedProducts = $this->loadModel('product')->getProductPairsByProject($sprint->project);
                if(!empty($linkedProducts)) $this->user->updateUserView(array_keys($linkedProducts), 'product', array($account));
            }
        }
    }

    /**
     * Compute burn of a execution.
     *
     * @param  int    $executionID
     * @access public
     * @return array
     */
    public function computeBurn($executionID = 0)
    {
        $today = helper::today();
        $executions = $this->dao->select('id, code')->from(TABLE_EXECUTION)
            ->where('type')->in('sprint,stage')
            ->andWhere('lifetime')->ne('ops')
            ->andWhere('status')->notin('done,closed,suspended')
            ->beginIF($executionID)->andWhere('id')->in($executionID)->fi()
            ->fetchPairs();
        if(!$executions) return array();

        /* Update today's data of burn. */
        $burns = $this->dao->select("execution, '$today' AS date, sum(estimate) AS `estimate`, sum(`left`) AS `left`, SUM(consumed) AS `consumed`")
            ->from(TABLE_TASK)
            ->where('execution')->in(array_keys($executions))
            ->andWhere('deleted')->eq('0')
            ->andWhere('parent')->ge('0')
            ->andWhere('status')->ne('cancel')
            ->groupBy('execution')
            ->fetchAll('execution');
        $closedLefts = $this->dao->select('execution, sum(`left`) AS `left`')->from(TABLE_TASK)
            ->where('execution')->in(array_keys($executions))
            ->andWhere('deleted')->eq('0')
            ->andWhere('parent')->ge('0')
            ->andWhere('status')->eq('closed')
            ->groupBy('execution')
            ->fetchAll('execution');
        $finishedEstimates = $this->dao->select("execution, sum(`estimate`) AS `estimate`")->from(TABLE_TASK)
            ->where('execution')->in(array_keys($executions))
            ->andWhere('deleted')->eq('0')
            ->andWhere('parent')->ge('0')
            ->andWhere('status', true)->eq('done')
            ->orWhere('status')->eq('closed')
            ->markRight(1)
            ->groupBy('execution')
            ->fetchAll('execution');
        $storyPoints = $this->dao->select('t1.project, sum(t2.estimate) AS `storyPoint`')->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t2.product = t3.id')
            ->where('t1.project')->in(array_keys($executions))
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t2.status')->ne('closed')
            ->andWhere('t2.stage')->in('wait,planned,projected,developing')
            ->groupBy('project')
            ->fetchAll('project');

        foreach($burns as $executionID => $burn)
        {
            if(isset($closedLefts[$executionID]))
            {
                $closedLeft  = $closedLefts[$executionID];
                $burn->left -= (int)$closedLeft->left;
            }

            if(isset($finishedEstimates[$executionID]))
            {
                $finishedEstimate = $finishedEstimates[$executionID];
                $burn->estimate  -= (int)$finishedEstimate->estimate;
            }

            $burn->product = 0;
            $burn->task    = 0;
            if(isset($storyPoints[$executionID])) $burn->storyPoint = $storyPoints[$executionID]->storyPoint;

            $this->dao->replace(TABLE_BURN)->data($burn)->exec();
            $burn->executionName = $executions[$burn->execution];
        }

        return $burns;
    }

    /**
     * Compute cfd of a execution.
     *
     * @param  int    $executionID
     * @access public
     * @return array
     */
    public function computeCFD($executionID = 0)
    {
        $today = helper::today();
        $executions = $this->dao->select('id, code')->from(TABLE_EXECUTION)
            ->where('type')->eq('kanban')
            ->andWhere('status')->notin('done,closed,suspended')
            ->beginIF($executionID)->andWhere('id')->in($executionID)->fi()
            ->fetchPairs();
        if(!$executions) return array();

        /* Update today's data of cfd. */
        $cells = $this->dao->select("t1.id, t1.kanban as execution, t1.`column`, t1.type, t1.cards, t1.lane, t2.name, t2.parent")
            ->from(TABLE_KANBANCELL)->alias('t1')
            ->leftJoin(TABLE_KANBANCOLUMN)->alias('t2')->on('t1.column = t2.id')
            ->where('t1.kanban')->in(array_keys($executions))
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere('t1.type')->in('story,bug,task')
            ->orderBy('t2.id asc')
            ->fetchAll('id');

        /* Group by execution/type/name/lane/column. */
        $columnGroup = array();
        $parentNames = array();
        foreach($cells as $id => $column)
        {
            if($column->parent == '-1')
            {
                $parentNames[$column->column] = $column->name;
                continue;
            }

            $column->name = isset($parentNames[$column->parent]) ? $parentNames[$column->parent] . "($column->name)" : $column->name;
            $columnGroup[$column->execution][$column->type][$column->name][$column->lane][$column->column] = $column;
        }

        foreach($columnGroup as $executionID => $executionGroup)
        {
            foreach($executionGroup as $type => $columns)
            {
                foreach($columns as $colName => $laneGroup)
                {
                    $cfd = new stdclass();
                    $cfd->count = 0;
                    $cfd->date  = $today;
                    $cfd->type  = $type;
                    foreach($laneGroup as $laneID => $columnGroup)
                    {
                        foreach($columnGroup as $colID => $columnCard)
                        {
                            $cards = trim($columnCard->cards, ',');
                            $cfd->count += $cards ? count(explode(',', $cards)) : 0;
                        }
                    }

                    $cfd->name      = $colName;
                    $cfd->execution = $executionID;
                    $this->dao->replace(TABLE_CFD)->data($cfd)->exec();
                }
            }
        }
    }

    /**
     * Check whether there is data on the specified date of execution, and there is no data with the latest date added.
     *
     * @param int    $executionID
     * @param string $date
     * @access public
     * @return void
     */
    public function checkCFDData($executionID, $date)
    {
        $today = helper::today();
        if($date >= $today) return;

        $checkData = $this->dao->select("date, `count` AS value, `name`")->from(TABLE_CFD)
            ->where('execution')->eq((int)$executionID)
            ->andWhere('date')->eq($date)
            ->orderBy('date DESC, id asc')->fetchGroup('name', 'date');
        if(!$checkData)
        {
            $closetoDate = $this->dao->select("max(date) as date")->from(TABLE_CFD)->where('execution')->eq((int)$executionID)->andWhere('date')->lt($date)->fetch('date');
            if($closetoDate)
            {
                $copyData = $this->dao->select("*")->from(TABLE_CFD)
                    ->where('execution')->eq((int)$executionID)
                    ->andWhere('date')->eq($closetoDate)
                    ->fetchAll();
                foreach($copyData as $data)
                {
                    unset($data->id);
                    $data->date = $date;
                    $this->dao->replace(TABLE_CFD)->data($data)->exec();
                }
            }
        }
    }

    /**
     * Fix burn for first day.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function fixFirst($executionID)
    {
        $execution  = $this->getById($executionID);
        $burn     = $this->dao->select('*')->from(TABLE_BURN)->where('execution')->eq($executionID)->andWhere('date')->eq($execution->begin)->andWhere('task')->eq(0)->fetch();
        $withLeft = $this->post->withLeft ? $this->post->withLeft : 0;
        $burnLeft = empty($burn) ? 0 : $burn->left;

        $data = fixer::input('post')
            ->add('execution', $executionID)
            ->add('date', $execution->begin)
            ->add('left', $withLeft ? $this->post->estimate : $burnLeft)
            ->add('consumed', empty($burn) ? 0 : $burn->consumed)
            ->remove('withLeft')
            ->get();
        if(!is_numeric($data->estimate)) return false;

        $this->dao->replace(TABLE_BURN)->data($data)->exec();
    }

    /**
     * Get begin and end for CFD.
     *
     * @param  object $execution
     * @access public
     * @return void
     */
    public function getBeginEnd4CFD($execution)
    {
        $end   = (!helper::isZeroDate($execution->closedDate) and date('Y-m-d', strtotime($execution->closedDate)) < helper::today()) ? date('Y-m-d', strtotime($execution->closedDate)) : helper::today();
        $begin = (!helper::isZeroDate($execution->openedDate) and date('Y-m-d', strtotime($execution->openedDate)) > date('Y-m-d', strtotime('-13 days', strtotime($end)))) ? date('Y-m-d', strtotime($execution->openedDate)) : date('Y-m-d', strtotime('-13 days', strtotime($end)));
        return array($begin, $end);
    }

    /**
     * Get burn data for flot
     *
     * @param  int    $executionID
     * @param  string $burnBy
     * @param  bool   $showDelay
     * @param  array  $dateList
     * @access public
     * @return array
     */
    public function getBurnDataFlot($executionID = 0, $burnBy = '', $showDelay = false, $dateList = array())
    {
        /* Get execution and burn counts. */
        $execution = $this->getById($executionID);

        /* If the burnCounts > $itemCounts, get the latest $itemCounts records. */
        $sets = $this->dao->select("date AS name, `$burnBy` AS value, `$burnBy`")->from(TABLE_BURN)->where('execution')->eq((int)$executionID)->andWhere('task')->eq(0)->orderBy('date DESC')->fetchAll('name');

        $burnData = array();
        foreach($sets as $date => $set)
        {
            if($date < $execution->begin) continue;
            if(!$showDelay and $date > $execution->end) $set->value = 'null';
            if($showDelay  and $date < $execution->end) $set->value = 'null';

            $burnData[$date] = $set;
        }

        foreach($dateList as $date)
        {
            if(!isset($burnData[$date]))
            {
                if(($showDelay and $date < $execution->end) or (!$showDelay and $date > $execution->end))
                {
                    $set = new stdClass();
                    $set->name    = $date;
                    $set->value   = 'null';
                    $set->$burnBy = 0;

                    $burnData[$date] = $set;
                }
            }
        }

        krsort($burnData);
        $burnData = array_reverse($burnData);

        return $burnData;
    }

    /**
     * Get execution burn data.
     *
     * @param  array  $executions
     * @access public
     * @return array
     */
    public function getBurnData($executions)
    {
        if(empty($executions)) return array();

        /* Get burndown charts datas. */
        $burns = $this->dao->select('execution, date AS name, `left` AS value')
            ->from(TABLE_BURN)
            ->where('execution')->in(array_keys($executions))
            ->andWhere('task')->eq(0)
            ->orderBy('date desc')
            ->fetchGroup('execution', 'name');

        foreach($burns as $executionID => $executionBurns)
        {
            /* If executionBurns > $itemCounts, split it, else call processBurnData() to pad burns. */
            $begin = $executions[$executionID]->begin;
            $end   = $executions[$executionID]->end;
            if(helper::isZeroDate($begin)) $begin = $executions[$executionID]->openedDate;
            /* Unset burn information that is greater than the execution end date. */
            foreach($executionBurns as $date => $burnInfo)
            {
                if($date > $end) unset($executionBurns[$date]);
            }

            $executionBurns = $this->processBurnData($executionBurns, $this->config->execution->defaultBurnPeriod, $begin, $end);

            /* Shorter names. */
            foreach($executionBurns as $executionBurn)
            {
                $executionBurn->name = substr($executionBurn->name, 5);
                unset($executionBurn->execution);
            }

            ksort($executionBurns);
            $burns[$executionID] = $executionBurns;
        }

        return $burns;
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
        if(!helper::isZeroDate($end))
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
        $today    = helper::today();
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
            if($currentTime > time() and !$todayTag)
            {
                $todayTag = $i + 1;
            }

            if(isset($sets[$current])) $preValue = $sets[$current]->value;
            if(!isset($sets[$current]) and $mode == 'noempty')
            {
                $sets[$current]  = new stdclass();
                $sets[$current]->name  = $current;
                $sets[$current]->value = helper::diffDate($current, $today) < 0 ? $preValue : 'null';
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
     * Build CFD data.
     *
     * @param  int    $executionID
     * @param  string $type
     * @param  array  $dateList
     * @access public
     * @return array
     */
    public function buildCFDData($executionID, $dateList, $type)
    {
        $this->loadModel('report');
        $setGroup = $this->getCFDData($executionID, $dateList, $type);

        if(empty($setGroup)) return array();

        $chartData['labels'] = $this->report->convertFormat($dateList, DT_DATE5);
        $chartData['line']   = array();

        foreach($setGroup as $name => $sets)
        {
            $chartData['line'][$name] = $this->report->createSingleJSON($sets, $dateList);
        }

        return $chartData;
    }

    /**
     * Get CFD data to display.
     *
     * @param  int    $executionID
     * @param  string $type
     * @param  array  $dateList
     * @access public
     * @return array
     */
    public function getCFDData($executionID = 0, $dateList = array(), $type = 'story')
    {
        $execution = $this->getById($executionID);

        $setGroup = $this->dao->select("date, `count` AS value, `name`")->from(TABLE_CFD)
            ->where('execution')->eq((int)$executionID)
            ->andWhere('type')->eq($type)
            ->andWhere('date')->in($dateList)
            ->orderBy('date DESC, id asc')->fetchGroup('name', 'date');

        $data = array();
        foreach($setGroup as $name => $sets)
        {
            foreach($sets as $date => $set)
            {
                if($date < $execution->begin) continue;

                $data[$name][$date] = $set;
            }
        }

        return $data;
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
        if(strpos($condition, '`assignedTo`') !== false)
        {
            preg_match("/`assignedTo`\s+(([^']*) ('([^']*)'))/", $condition, $matches);
            $condition = preg_replace('/`(\w+)`/', 't1.`$1`', $condition);
            $condition = str_replace("t1.$matches[0]", "(t1.$matches[0] or (t1.mode = 'multi' and t2.`account` $matches[1] and t1.status != 'closed' and t2.status != 'done') )", $condition);
        }

        $sql = $this->dao->select('t1.id')->from(TABLE_TASK)->alias('t1');
        if(strpos($condition, '`assignedTo`') !== false) $sql = $sql->leftJoin(TABLE_TASKTEAM)->alias('t2')->on("t2.task = t1.id and t2.account $matches[1]");

        $orderBy = array_map(function($value){return 't1.' . $value;}, explode(',', $orderBy));
        $orderBy = implode(',', $orderBy);

        $taskIdList = $sql->where($condition)
            ->andWhere('deleted')->eq(0)
            ->orderBy($orderBy)
            ->page($pager, 't1.id')
            ->fetchAll('id');

        $orderBy = str_replace(array('t1.pri_', 't1.`pri'), array('priOrder_', '`priOrder_'), $orderBy);
        $tasks   = $this->dao->select('t1.*, t2.id AS storyID, t2.title AS storyTitle, t2.product, t2.branch, t2.version AS latestStoryVersion, t2.status AS storyStatus, t3.realname AS assignedToRealName, IF(t1.`pri` = 0, 999, t1.`pri`) as priOrder')
             ->from(TABLE_TASK)->alias('t1')
             ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
             ->leftJoin(TABLE_USER)->alias('t3')->on('t1.assignedTo = t3.account')
             ->where('t1.deleted')->eq(0)
             ->andWhere('t1.id')->in(array_keys($taskIdList))
             ->orderBy($orderBy)
             ->fetchAll('id');

        if(empty($tasks)) return array();

        $taskTeam = $this->dao->select('*')->from(TABLE_TASKTEAM)->where('task')->in(array_keys($tasks))->fetchGroup('task');
        if(!empty($taskTeam))
        {
            foreach($taskTeam as $taskID => $team) $tasks[$taskID]->team = $team;
        }

        $parents = array();
        foreach($tasks as $task)
        {
            if($task->parent > 0) $parents[$task->parent] = $task->parent;
        }
        $parents = $this->dao->select('*')->from(TABLE_TASK)->where('id')->in($parents)->fetchAll('id');

        if($this->config->vision == 'lite') $tasks = $this->loadModel('task')->appendLane($tasks);
        foreach($tasks as $task)
        {
            if($task->parent > 0)
            {
                if(isset($tasks[$task->parent]))
                {
                    $tasks[$task->parent]->children[$task->id] = $task;
                    unset($tasks[$task->id]);
                }
                else
                {
                    $parent = $parents[$task->parent];
                    $task->parentName = $parent->name;
                }
            }
        }
        return $this->loadModel('task')->processTasks($tasks);
    }

    /**
     * Get bugs by search in execution.
     *
     * @param  array     $products
     * @param  int       $executionID
     * @param  string    $sql
     * @param  object    $pager
     * @param  string    $orderBy
     * @access public
     * @return mixed
     */
    public function getSearchBugs($products, $executionID, $sql, $pager, $orderBy)
    {
        return $this->dao->select('*')->from(TABLE_BUG)
            ->where($sql)
            ->andWhere('status')->eq('active')
            ->andWhere('toTask')->eq(0)
            ->andWhere('tostory')->eq(0)
            ->beginIF(!empty($products))->andWhere('product')->in(array_keys($products))->fi()
            ->beginIF(empty($products))->andWhere('execution')->eq($executionID)->fi()
            ->andWhere('deleted')->eq(0)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get the summary of execution.
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
            if(isset($task->children))
            {
                foreach($task->children as $child)
                {
                    $totalEstimate  += $child->estimate;
                    $totalConsumed  += $child->consumed;

                    if($child->status != 'cancel' and $child->status != 'closed') $totalLeft += $child->left;
                }
            }
            else
            {
                $totalEstimate  += $task->estimate;
                $totalConsumed  += $task->consumed;

                if($task->status != 'cancel' and $task->status != 'closed') $totalLeft += $task->left;
            }

            $statusVar = 'status' . ucfirst($task->status);
            $$statusVar ++;
            if(isset($task->children))
            {
                foreach($task->children as $children)
                {
                    $statusVar = 'status' . ucfirst($children->status);
                    $$statusVar ++;
                    $taskSum ++;
                }
            }
            $taskSum ++;
        }

        return sprintf($this->lang->execution->taskSummary, $taskSum, $statusWait, $statusDoing, round($totalEstimate, 1), round($totalConsumed, 1), round($totalLeft, 1));
    }

    /**
     * Judge an action is clickable or not.
     *
     * @param  object    $execution
     * @param  string    $action
     * @param  string    $module
     * @access public
     * @return bool
     */
    public static function isClickable($execution, $action, $module = 'execution')
    {
        if($module == 'programplan') $module = 'execution';
        $action    = strtolower($action);
        $clickable = commonModel::hasPriv($module, $action);
        if(!$clickable) return false;

        if($action == 'start')    return $execution->status == 'wait';
        if($action == 'close')    return $execution->status != 'closed';
        if($action == 'suspend')  return $execution->status == 'wait' or $execution->status == 'doing';
        if($action == 'putoff')   return $execution->status == 'wait' or $execution->status == 'doing';
        if($action == 'activate') return $execution->status == 'suspended' or $execution->status == 'closed';

        return true;
    }

    /**
     * Get no weekend date
     *
     * @param  string     $begin
     * @param  string     $end
     * @param  string     $type
     * @param  string|int $interval
     * @param  string     $format
     * @param  string     $executionDeadline
     * @access public
     * @return array
     */
    public function getDateList($begin, $end, $type, $interval = '', $format = 'm/d/Y', $executionDeadline = '')
    {
        $this->app->loadClass('date', true);
        $dateList = date::getDateList($begin, $end, $format, $type, $this->config->execution->weekend);
        $days     = count($dateList);

        if(!$interval) $interval = floor(count($dateList) / $this->config->execution->maxBurnDay);

        /* Remove date by interval. */
        if($interval)
        {
            $spaces   = (int)$interval;
            $counter  = $spaces;
            foreach($dateList as $i => $date)
            {
                $counter ++;
                if($date == $executionDeadline) continue;
                if($counter <= $spaces)
                {
                    unset($dateList[$i]);
                    continue;
                }

                $counter = 0;
            }
        }

        return array(array_values($dateList), $interval);
    }

    /**
     * Get total estimate.
     *
     * @param  int    $executionID
     * @access public
     * @return float
     */
    public function getTotalEstimate($executionID)
    {
        $estimate = $this->dao->select('SUM(estimate) as estimate')->from(TABLE_TASK)->where('execution')->eq($executionID)->andWhere('deleted')->eq('0')->fetch('estimate');
        return round($estimate);
    }

    /**
     * Fix order.
     *
     * @access public
     * @return void
     */
    public function fixOrder()
    {
        $executions = $this->dao->select('id,`order`')->from(TABLE_EXECUTION)->orderBy('order')->fetchPairs('id', 'order');

        $i = 0;
        foreach($executions as $id => $order)
        {
            $i++;
            $newOrder = $i * 5;
            if($order == $newOrder) continue;
            $this->dao->update(TABLE_EXECUTION)->set('`order`')->eq($newOrder)->where('id')->eq($id)->exec();
        }
    }

    /**
     * Build bug search form.
     *
     * @param  array     $products
     * @param  int       $queryID
     * @param  string    $actionURL
     * @access public
     * @return void
     */
    public function buildBugSearchForm($products, $queryID, $actionURL, $type = 'execution')
    {
        $modules = array();
        $builds  = array('' => '', 'trunk' => $this->lang->trunk);
        foreach($products as $product)
        {
            $productModules = $this->loadModel('tree')->getOptionMenu($product->id, 'bug');
            $productBuilds  = $this->loadModel('build')->getBuildPairs($product->id, 'all', $params = 'noempty|notrunk|withbranch');
            foreach($productModules as $moduleID => $moduleName)
            {
                $modules[$moduleID] = ((count($products) >= 2 and $moduleID) ? $product->name : '') . $moduleName;
            }
            foreach($productBuilds as $buildID => $buildName)
            {
                $builds[$buildID] = ((count($products) >= 2 and $buildID) ? $product->name . '/' : '') . $buildName;
            }
        }

        $branchGroups = $this->loadModel('branch')->getByProducts(array_keys($products));
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
                if(isset($product->branches))
                {
                    foreach($product->branches as $branch)
                    {
                        if(isset($branchGroups[$product->id][$branch])) $branchPairs[$branch] = (count($products) > 1 ? $product->name . '/' : '') . $branchGroups[$product->id][$branch];
                    }
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

        $this->config->bug->search['module']    = $type == 'execution' ? 'executionBug' : 'projectBug';
        $this->config->bug->search['actionURL'] = $actionURL;
        $this->config->bug->search['queryID']   = $queryID;
        unset($this->config->bug->search['fields']['execution']);
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
     * @param  int    $executionID
     * @param  array  $executions
     * @param  int    $queryID
     * @param  string $actionURL
     * @param  array  $modules
     * @access public
     * @return void
     */
    public function buildTaskSearchForm($executionID, $executions, $queryID, $actionURL, $modules)
    {
        $this->config->execution->search['actionURL'] = $actionURL;
        $this->config->execution->search['queryID']   = $queryID;
        $this->config->execution->search['params']['execution']['values'] = array(''=>'', $executionID => $executions[$executionID], 'all' => $this->lang->execution->allExecutions);
        $this->config->execution->search['params']['module']['values']    = $modules;

        $this->loadModel('search')->setSearchParams($this->config->execution->search);
    }

    /**
     * Get Kanban tasks
     *
     * @param  int          $executionID
     * @param  string       $orderBy
     * @param  object       $pager
     * @param  array|string $excludeTasks
     * @access public
     * @return void
     */
    public function getKanbanTasks($executionID, $orderBy = 'status_asc, id_desc', $pager = null, $excludeTasks = '')
    {
        $tasks = $this->dao->select('t1.*, t2.id AS storyID, t2.title AS storyTitle, t2.version AS latestStoryVersion, t2.status AS storyStatus, t3.realname AS assignedToRealName')
            ->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->leftJoin(TABLE_USER)->alias('t3')->on('t1.assignedTo = t3.account')
            ->where('t1.execution')->eq((int)$executionID)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t1.parent')->ge(0)
            ->beginIF($excludeTasks)->andWhere('t1.id')->notIN($excludeTasks)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

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
                if($type == 'assignedTo' and $groupKey == 'closed')
                {
                    $closedTasks[$groupKey][] = $task;
                }
                else
                {
                    if(!isset($kanbanGroup[$groupKey])) $kanbanGroup[$groupKey] = new stdclass();
                    $kanbanGroup[$groupKey]->tasks[$status][] = $task;
                }
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
                if($type == 'assignedTo' and $groupKey == 'closed')
                {
                    $closedBugs[$groupKey][] = $bug;
                }
                else
                {
                    if(!isset($kanbanGroup[$groupKey])) $kanbanGroup[$groupKey] = new stdclass();
                    $kanbanGroup[$groupKey]->bugs[$status][] = $bug;
                }
            }
            else
            {
                $noKeyBugs[$status][] = $bug;
            }
        }

        $kanbanGroup['closed'] = new stdclass();
        if(isset($closedTasks)) $kanbanGroup['closed']->tasks = $closedTasks;
        if(isset($closedBugs))  $kanbanGroup['closed']->bugs  = $closedBugs;

        $kanbanGroup['nokey'] = new stdclass();
        if(isset($noKeyTasks)) $kanbanGroup['nokey']->tasks = $noKeyTasks;
        if(isset($noKeyBugs))  $kanbanGroup['nokey']->bugs = $noKeyBugs;

        return $kanbanGroup;
    }

    /**
     * Save Kanban Data.
     *
     * @param  int    $executionID
     * @param  array  $kanbanDatas
     * @access public
     * @return void
     */
    public function saveKanbanData($executionID, $kanbanDatas)
    {
        $data = array();
        foreach($kanbanDatas as $type => $kanbanData) $data[$type] = array_keys($kanbanData);
        $this->loadModel('setting')->setItem("null.execution.kanban.execution$executionID", json_encode($data));

    }

    /**
     * Get Prev Kanban.
     *
     * @param  int    $executionID
     * @access public
     * @return array
     */
    public function getPrevKanban($executionID)
    {
        $prevKanbans = $this->loadModel('setting')->getItem("owner=null&module=execution&section=kanban&key=execution$executionID");
        return json_decode($prevKanbans, true);
    }

    /**
     * Get kanban setting.
     *
     * @access public
     * @return object
     */
    public function getKanbanSetting()
    {
        $allCols    = '1';
        $showOption = '0';
        if(isset($this->config->execution->kanbanSetting->allCols)) $allCols = $this->config->execution->kanbanSetting->allCols;

        $colorList = $this->config->execution->kanbanSetting->colorList;
        if(!is_array($colorList)) $colorList = json_decode($colorList, true);

        $kanbanSetting = new stdclass();
        $kanbanSetting->allCols    = $allCols;
        $kanbanSetting->showOption = $showOption;
        $kanbanSetting->colorList  = $colorList;

        return $kanbanSetting;
    }

    /**
     * Get kanban columns.
     *
     * @param  object $kanbanSetting
     * @access public
     * @return array
     */
    public function getKanbanColumns($kanbanSetting)
    {
        if($kanbanSetting->allCols) return array('wait', 'doing', 'pause', 'done', 'cancel', 'closed');
        return array('wait', 'doing', 'pause', 'done');
    }

    /**
     * 获取状态和方法的映射关系，此关系决定了看板内容能否从一个泳道拖动到另一个泳道，以及拖动后执行什么方法。
     * Get the mapping between state and method. This relationship determines whether kanban content can be dragged from one lane
     * to another, and what method is executed after dragging.
     *
     * 映射关系的基本格式为 map[$mode][$fromStatus][$toStatus] = $methodName。
     * The basic format of the mapping relationship is map[$mode][$fromStatus][$toStatus] = $methodName.
     *
     * @param string $mode          看板内容类型，可选值 task|bug   The content mode of kanban, should be task or bug.
     * @param string $fromStatus    拖动内容的来源泳道              The origin lane the content draged from.
     * @param string $toStatus      拖动内容的目标泳道              The destination lane the content draged to.
     * @param string $methodName    拖动到目标泳道后执行的方法名    The method to execute after draged the content.
     *
     * 例如 map['task']['doing']['done'] = 'close' 表示：任务(task)看板从进行中(doing)泳道拖动到已完成(done)泳道时，执行关闭(close)方法。
     * For example, map['task']['doing']['done'] = 'close' means: when the task kanban is dragged from the doing lane to the done lane,
     * execute the close method.
     *
     * @param  object $kanbanSetting    This param is used in the biz version, don't remove it.
     * @access public
     * @return string
     */
    public function getKanbanStatusMap($kanbanSetting)
    {
        $statusMap = array();
        if(common::hasPriv('task', 'start')) $statusMap['task']['wait']['doing']  = 'start';
        if(common::hasPriv('task', 'pause')) $statusMap['task']['doing']['pause'] = 'pause';
        if(common::hasPriv('task', 'finish'))
        {
            $statusMap['task']['wait']['done']  = 'finish';
            $statusMap['task']['doing']['done'] = 'finish';
            $statusMap['task']['pause']['done'] = 'finish';
        }
        if(common::hasPriv('task', 'cancel'))
        {
            $statusMap['task']['wait']['cancel']  = 'cancel';
            $statusMap['task']['pause']['cancel'] = 'cancel';
        }
        if(common::hasPriv('task', 'activate'))
        {
            $statusMap['task']['pause']['doing']  = 'activate';
            $statusMap['task']['done']['doing']   = 'activate';
            $statusMap['task']['cancel']['doing'] = 'activate';
            $statusMap['task']['closed']['doing'] = 'activate';
        }
        if(common::hasPriv('task', 'close'))
        {
            $statusMap['task']['done']['closed']   = 'close';
            $statusMap['task']['cancel']['closed'] = 'close';
        }

        if(common::hasPriv('bug', 'resolve'))
        {
            $statusMap['bug']['wait']['done']   = 'resolve';
            $statusMap['bug']['wait']['cancel'] = 'resolve';
        }
        if(common::hasPriv('bug', 'close'))
        {
            $statusMap['bug']['done']['closed'] = 'close';
            $statusMap['bug']['cancel']['closed'] = 'close';
        }
        if(common::hasPriv('bug', 'activate'))
        {
            $statusMap['bug']['done']['wait']   = 'activate';
            $statusMap['bug']['cancel']['wait']   = 'activate';
            $statusMap['bug']['closed']['wait'] = 'activate';
        }

        return $statusMap;
    }

    /**
     * Get status list of kanban.
     *
     * @param  object $kanbanSetting    This param is used in the biz version, don't remove it.
     * @access public
     * @return string
     */
    public function getKanbanStatusList($kanbanSetting)
    {
        return $this->lang->task->statusList;
    }

    /**
     * Get color list of kanban.
     *
     * @param  object $kanbanSetting
     * @access public
     * @return array
     */
    public function getKanbanColorList($kanbanSetting)
    {
        return $kanbanSetting->colorList;
    }

    /**
     * Build burn data.
     *
     * @param  int    $executionID
     * @param  array  $dateList
     * @param  string $type
     * @param  string $burnBy
     * @param  string $executionEnd
     * @access public
     * @return array
     */
    public function buildBurnData($executionID, $dateList, $type, $burnBy = 'left', $executionEnd = '')
    {
        $this->loadModel('report');
        $burnBy = $burnBy ? $burnBy : 'left';

        $sets         = $this->getBurnDataFlot($executionID, $burnBy, false, $dateList);
        $limitJSON    = '[]';
        $baselineJSON = '[]';

        $firstBurn    = empty($sets) ? 0 : reset($sets);
        $firstTime    = !empty($firstBurn->$burnBy) ? $firstBurn->$burnBy : (!empty($firstBurn->value) ? $firstBurn->value : 0);
        $firstTime    = $firstTime == 'null' ? 0 : $firstTime;
        /* If the $executionEnd  is passed, the guide should end of execution. */
        $days         = $executionEnd ? array_search($executionEnd, $dateList) : count($dateList) - 1;
        $rate         = $days ? $firstTime / $days : '';
        $baselineJSON = '[';
        foreach($dateList as $i => $date)
        {
            $value = ($i > $days ? 0 : round(($days - $i) * (float)$rate, 3)) . ',';
            $baselineJSON .= $value;
        }
        $baselineJSON = rtrim($baselineJSON, ',') . ']';

        $chartData['labels']   = $this->report->convertFormat($dateList, DT_DATE5);
        $chartData['burnLine'] = $this->report->createSingleJSON($sets, $dateList);
        $chartData['baseLine'] = $baselineJSON;

        $execution = $this->getById($executionID);
        if((strpos('closed,suspended', $execution->status) === false and helper::today() > $execution->end)
            or ($execution->status == 'closed'    and substr($execution->closedDate, 0, 10) > $execution->end)
            or ($execution->status == 'suspended' and $execution->suspendedDate > $execution->end))
        {
            $delaySets = $this->getBurnDataFlot($executionID, $burnBy, true, $dateList);
            $chartData['delayLine'] = $this->report->createSingleJSON($delaySets, $dateList);
        }

        return $chartData;
    }

    /**
     * Fill tasks in tree.
     * @param  object $tree
     * @param  int    $executionID
     * @access public
     * @return object
     */
    public function fillTasksInTree($node, $executionID)
    {
        $node = (object)$node;
        static $storyGroups, $taskGroups;
        if(empty($storyGroups))
        {
            if($this->config->vision == 'lite')
            {
                $execution = $this->getById($executionID);
                $stories = $this->dao->select('t2.*, t1.version as taskVersion')->from(TABLE_PROJECTSTORY)->alias('t1')
                    ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
                    ->where('t1.project')->eq((int)$execution->project)
                    ->andWhere('t2.deleted')->eq(0)
                    ->orderBy('t1.`order`_desc')
                    ->fetchAll();
            }
            else
            {
                $stories = $this->dao->select('t2.*, t1.version as taskVersion')->from(TABLE_PROJECTSTORY)->alias('t1')
                    ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
                    ->where('t1.project')->eq((int)$executionID)
                    ->andWhere('t2.deleted')->eq(0)
                    ->andWhere('t2.type')->eq('story')
                    ->orderBy('t1.`order`_desc')
                    ->fetchAll();
            }
            $storyGroups = array();
            foreach($stories as $story) $storyGroups[$story->product][$story->module][$story->id] = $story;
        }
        if(empty($taskGroups))
        {
            $tasks = $this->dao->select('*')->from(TABLE_TASK)
                ->where('execution')->eq((int)$executionID)
                ->andWhere('deleted')->eq(0)
                ->andWhere('parent')->lt(1)
                ->orderBy('id_desc')
                ->fetchAll();
            $childTasks = $this->dao->select('*')->from(TABLE_TASK)
                ->where('execution')->eq((int)$executionID)
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
                $subNode = $this->fillTasksInTree($child, $executionID);
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
            static $users;
            if(empty($users)) $users = $this->loadModel('user')->getPairs('noletter');

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
                $storyItem->openedBy      = zget($users, $story->openedBy);
                $storyItem->assignedTo    = zget($users, $story->assignedTo);
                $storyItem->url           = helper::createLink('execution', 'storyView', "storyID=$story->id&execution=$executionID");
                $storyItem->taskCreateUrl = helper::createLink('task', 'batchCreate', "executionID={$executionID}&story={$story->id}");

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
                        if($taskItem->story > 0) continue; // If a story link to task, display task in story tree.

                        $node->children[$taskItem->id] = $taskItem;
                        if(!empty($tasks[$taskItem->id]->children))
                        {
                            $task = $this->formatTasksForTree($tasks[$taskItem->id]->children);
                            $node->children[$taskItem->id]->children=$task;
                            $node->tasksCount += count($task);
                        }
                    }
                }
                $node->children = isset($node->children) ? array_values($node->children) : array();
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
            $taskItem->story        = $task->story;
            $taskItem->estimate     = $task->estimate;
            $taskItem->consumed     = $task->consumed;
            $taskItem->left         = $task->left;
            $taskItem->openedBy     = zget($users, $task->openedBy);
            $taskItem->assignedTo   = zget($users, $task->assignedTo);
            $taskItem->url          = helper::createLink('task', 'view', "task=$task->id");
            $taskItem->storyChanged = $story and $story->status == 'active' and $story->version > $story->taskVersion;

            $buttons  = '';
            $buttons .= common::buildIconButton('task', 'assignTo', "executionID=$task->execution&taskID=$task->id", $task, 'list', '', '', 'iframe', true);
            $buttons .= common::buildIconButton('task', 'start',    "taskID=$task->id", $task, 'list', '', '', 'iframe', true);
            $buttons .= common::buildIconButton('task', 'recordEstimate', "taskID=$task->id", $task, 'list', 'time', '', 'iframe', true);
            if(isset($task->children)) $taskItem->children = $this->formatTasksForTree($task->children);

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
     * Get plans by $productID.
     *
     * @param int|array $productID
     * @param string    $param withMainPlan|skipParent
     * @param int       $executionID
     * @return mixed
     */
    public function getPlans($products, $param = '', $executionID = 0)
    {
        $this->loadModel('productplan');

        $date  = date('Y-m-d');

        $param        = strtolower($param);
        $branchIdList = strpos($param, 'withmainplan') !== false ? array(BRANCH_MAIN => BRANCH_MAIN) : array();
        $branchGroups = $this->getBranchByProduct(array_keys($products), $executionID, 'noclosed');
        foreach($branchGroups as $branches)
        {
            foreach($branches as $branchID => $branchName) $branchIdList[] = $branchID;
        }

        $branchQuery = '(';
        if(!empty($branchIdList))
        {
            $branchCount = count($branchIdList);
            foreach($branchIdList as $index => $branchID)
            {
                $branchQuery .= "FIND_IN_SET('$branchID', branch)";
                if($index < $branchCount - 1) $branchQuery .= ' OR ';
            }
        }
        else
        {
            $branchQuery .= "FIND_IN_SET('0', branch)";
        }

        $branchQuery .= ')';

        $plans = $this->dao->select('t1.id,t1.title,t1.product,t1.parent,t1.begin,t1.end,t1.branch,t2.type as productType')->from(TABLE_PRODUCTPLAN)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t2.id=t1.product')
            ->where('t1.product')->in(array_keys($products))
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere($branchQuery)
            ->beginIF(strpos($param, 'unexpired') !== false)->andWhere('t1.end')->ge($date)->fi()
            ->beginIF(strpos($param, 'noclosed')  !== false)->andWhere('t1.status')->ne('closed')->fi()
            ->orderBy('t1.begin desc')
            ->fetchAll('id');

        $plans        = $this->productplan->reorder4Children($plans);
        $plans        = $this->productplan->relationBranch($plans);
        $productPlans = array();
        foreach($plans as $plan)
        {
            if($plan->parent == '-1' and strpos($param, 'skipparent') !== false) continue;
            if($plan->parent > 0 and isset($plans[$plan->parent])) $plan->title = $plans[$plan->parent]->title . ' /' . $plan->title;
            $productPlans[$plan->product][$plan->id] = $plan->title . " [{$plan->begin} ~ {$plan->end}]";
            if($plan->begin == '2030-01-01' and $plan->end == '2030-01-01') $productPlans[$plan->product][$plan->id] = $plan->title . ' ' . $this->lang->productplan->future;
            if($plan->productType != 'normal') $productPlans[$plan->product][$plan->id] = $productPlans[$plan->product][$plan->id] . ' / ' . ($plan->branchName ? $plan->branchName : $this->lang->branch->main);
        }

        return $productPlans;
    }

    /**
     * Print html for tree.
     *
     * @param object $trees
     * @param bool   $hasProduct true|false
     * @access pubic
     * @return string
     */
    public function printTree($trees, $hasProduct = true)
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
                    $html .= "<a class='tree-toggle'><span class='title' title='{$tree->name}'>" . $tree->name . '</span></a>';
                    break;
                case 'task':
                    $link = helper::createLink('execution', 'treeTask', "taskID={$tree->id}");
                    $html .= '<li class="item-task">';
                    $html .= '<a class="tree-link" href="' . $link . '"><span class="label label-type">' . ($tree->parent > 0 ? $this->lang->task->children : $this->lang->task->common) . "</span><span class='title' title='{$tree->title}'>" . $tree->title . '</span> <span class="user"><i class="icon icon-person"></i> ' . (empty($tree->assignedTo) ? $tree->openedBy : $tree->assignedTo) . '</span><span class="label label-id">' . $tree->id . '</span></a>';
                    break;
                case 'product':
                    $this->app->loadLang('product');
                    $productName = $hasProduct ? $this->lang->productCommon : $this->lang->projectCommon;
                    $html .= '<li class="item-product">';
                    $html .= '<a class="tree-toggle"><span class="label label-type">' . $productName . "</span><span class='title' title='{$tree->name}'>" . $tree->name . '</span></a>';
                    break;
                case 'story':
                    $this->app->loadLang('story');
                    $link = helper::createLink('execution', 'treeStory', "storyID={$tree->storyId}");
                    $html .= '<li class="item-story">';
                    $html .= '<a class="tree-link" href="' . $link . '"><span class="label label-type">' . $this->lang->story->common . "</span><span class='title' title='{$tree->title}'>" . $tree->title . '</span> <span class="user"><i class="icon icon-person"></i> ' . (empty($tree->assignedTo) ? $tree->openedBy : $tree->assignedTo) . '</span><span class="label label-id">' . $tree->storyId . '</span></a>';
                    break;
                case 'branch':
                    $this->app->loadLang('branch');
                    $html .= "<li class='item-module'>";
                    $html .= "<a class='tree-toggle'><span class='label label-type'>{$this->lang->branch->common}</span><span class='title' title='{$tree->name}'>{$tree->name}</span></a>";
                    break;
            }
            if(isset($tree->children))
            {
                $html .= '<ul>';
                $html .= $this->printTree($tree->children, $hasProduct);
                $html .= '</ul>';
            }
            $html .= '</li>';
        }
        return $html;
    }

    /**
     * Print execution nested list.
     *
     * @param  int    $execution
     * @param  int    $isChild
     * @param  int    $users
     * @param  int    $productID
     * @param  string $project
     * @access public
     * @return void
     */
    public function printNestedList($execution, $isChild, $users, $productID, $project = '')
    {
        $this->loadModel('task');
        $this->loadModel('execution');
        $this->loadModel('programplan');

        $today = helper::today();

        if(!$isChild)
        {
            $trClass = 'is-top-level table-nest-child-hide';
            $trAttrs = "data-id='$execution->id' data-order='$execution->order' data-nested='true' data-status={$execution->status}";
        }
        else
        {
            if(strpos($execution->path, ",$execution->project,") !== false)
            {
                $path = explode(',', trim($execution->path, ','));
                $path = array_slice($path, array_search($execution->project, $path) + 1);
                $path = implode(',', $path);
            }

            $trClass  = 'table-nest-hide';
            $trAttrs  = "data-id={$execution->id} data-parent={$execution->parent} data-status={$execution->status}";
            $trAttrs .= " data-nest-parent='$execution->parent' data-order='$execution->order' data-nest-path='$path'";
        }

        $burns = join(',', $execution->burns);
        echo "<tr $trAttrs class='$trClass'>";
        echo "<td class='c-name text-left flex sort-handler'>";
        if(common::hasPriv('execution', 'batchEdit')) echo "<span id=$execution->id class='table-nest-icon icon table-nest-toggle'></span>";
        $spanClass = $execution->type == 'stage' ? 'label-warning' : 'label-info';
        echo "<span class='project-type-label label label-outline $spanClass'>{$this->lang->execution->typeList[$execution->type]}</span> ";
        if(empty($execution->children))
        {
            echo html::a(helper::createLink('execution', 'view', "executionID=$execution->id"), $execution->name, '', "class='text-ellipsis' title='{$execution->name}'");
            if(!helper::isZeroDate($execution->end))
            {
                if($execution->status != 'closed')
                {
                    echo strtotime($today) > strtotime($execution->end) ? '<span class="label label-danger label-badge">' . $this->lang->execution->delayed . '</span>' : '';
                }
            }
        }
        else
        {
            echo "<span class='text-ellipsis'>" . $execution->name . '</span>';
            if(!helper::isZeroDate($execution->end))
            {
                if($execution->status != 'closed')
                {
                    echo strtotime($today) > strtotime($execution->end) ? '<span class="label label-danger label-badge">' . $this->lang->execution->delayed . '</span>' : '';
                }
            }
        }
        if(!empty($execution->division) and $execution->hasProduct) echo "<td class='text-left' title='{$execution->productName}'>{$execution->productName}</td>";
        echo "<td class='status-{$execution->status} text-center'>" . zget($this->lang->project->statusList, $execution->status) . '</td>';
        echo '<td>' . zget($users, $execution->PM) . '</td>';
        echo helper::isZeroDate($execution->begin) ? '<td class="c-date"></td>' : '<td class="c-date">' . $execution->begin . '</td>';
        echo helper::isZeroDate($execution->end) ? '<td class="endDate c-date"></td>' : '<td class="endDate c-date">' . $execution->end . '</td>';
        echo "<td class='hours text-right' title='{$execution->estimate}{$this->lang->execution->workHour}'>" . $execution->estimate . $this->lang->execution->workHourUnit . '</td>';
        echo "<td class='hours text-right' title='{$execution->consumed}{$this->lang->execution->workHour}'>" . $execution->consumed . $this->lang->execution->workHourUnit . '</td>';
        echo "<td class='hours text-right' title='{$execution->left}{$this->lang->execution->workHour}'>" . $execution->left . $this->lang->execution->workHourUnit . '</td>';
        echo '<td>' . html::ring($execution->progress) . '</td>';
        echo "<td id='spark-{$execution->id}' class='sparkline text-left no-padding' values='$burns'></td>";
        echo '<td class="c-actions text-left">';

        $title = '';
        $disabled = '';
        $this->app->loadLang('stage');
        if($project and $project->model == 'ipd')
        {
            $title    = ($execution->ipdStage['canStart'] or $execution->ipdStage['isFirst']) ? '' : sprintf($this->lang->execution->disabledTip->startTip, $this->lang->stage->ipdTypeList[$execution->ipdStage['preAttribute']], $this->lang->stage->ipdTypeList[$execution->attribute]);
            $disabled = $execution->ipdStage['canStart'] ? '' : 'disabled';
        }
        echo common::buildIconButton('execution', 'start', "executionID={$execution->id}", $execution, 'list', '', '', 'iframe', true, $disabled, $title, '', empty($disabled));

        $class = !empty($execution->children) ? 'disabled' : '';
        echo $this->buildMenu('task', 'create', "executionID={$execution->id}", '', 'browse', '', '', $class, false, "data-app='execution'");

        if(empty($project)) $project = $this->loadModel('project')->getByID($execution->project);
        if($execution->type == 'stage' or ($this->app->tab == 'project' and !empty($project->model) and $project->model == 'waterfallplus'))
        {
            $isCreateTask = $this->loadModel('programplan')->isCreateTask($execution->id);
            $disabled     = ($isCreateTask and $execution->type == 'stage') ? '' : ' disabled';
            $title        = !$isCreateTask ? $this->lang->programplan->error->createdTask : $this->lang->programplan->createSubPlan;
            $title        = (!empty($disabled) and $execution->type != 'stage') ? $this->lang->programplan->error->notStage : $title;
            echo $this->buildMenu('programplan', 'create', "program={$execution->project}&productID=$productID&planID=$execution->id", $execution, 'browse', 'split', '', $disabled, '', '', $title);
        }

        if($execution->type == 'stage')
        {
            echo $this->buildMenu('programplan', 'edit', "stageID=$execution->id&projectID=$execution->project", $execution, 'browse', '', '', 'iframe', true);
        }
        else
        {
            echo $this->buildMenu('execution', 'edit', "executionID=$execution->id", $execution, 'browse', '', '', 'iframe', true);
        }

        $disabled = !empty($execution->children) ? ' disabled' : '';
        if($this->config->systemMode == 'PLM' and in_array($execution->attribute, array_keys($this->lang->stage->ipdTypeList))) $disabled = '';
        if($execution->status != 'closed' and common::hasPriv('execution', 'close', $execution))
        {
            $ipdDisabled = '';
            $title = $this->lang->execution->close;
            if(isset($execution->ipdStage['canClose']) and !$execution->ipdStage['canClose'] and !$isChild)
            {
                $ipdDisabled = ' disabled ';
                $title       = $execution->attribute == 'launch' ? $this->lang->execution->disabledTip->launchTip : $this->lang->execution->disabledTip->closeTip;
            }
            echo common::buildIconButton('execution', 'close', "stageID={$execution->id}", $execution, 'list', 'off', 'hiddenwin', $disabled . $ipdDisabled . ' iframe', true, (!empty($disabled) || !empty($ipdDisabled)) ? ' disabled' : '', $title, 0, empty($disabled) && empty($ipdDisabled));
        }
        elseif($execution->status == 'closed' and common::hasPriv('execution', 'activate', $execution))
        {
            echo $this->buildMenu('execution', 'activate', "stageID=$execution->id", $execution, 'browse', 'magic', 'hiddenwin' , $disabled . ' iframe', true, '', $this->lang->execution->activate);
        }

        if(common::hasPriv('execution', 'delete', $execution)) echo $this->buildMenu('execution', 'delete', "stageID=$execution->id&confirm=no", $execution, 'browse', 'trash', 'hiddenwin' , $disabled, '', '', $this->lang->delete);

        echo '</td>';
        echo '</tr>';

        if(!empty($execution->children))
        {
            foreach($execution->children as $child)
            {
                $child->division = $execution->division;
                $this->printNestedList($child, true, $users, $productID, $project);
            }
        }

        if(!empty($execution->tasks))
        {
            foreach($execution->tasks as $task)
            {
                $showmore = (count($execution->tasks) == 50) && ($task == end($execution->tasks));
                if($project->model == 'ipd')
                {
                    $canStart = $execution->status == 'wait' ? $execution->ipdStage['canStart'] : 1;
                    if($execution->status == 'close') $canStart = false;
                    $task->ipdStage = new stdclass();
                    $task->ipdStage->canStart      = $canStart;
                    $task->ipdStage->taskStartTip  = sprintf($this->lang->execution->disabledTip->taskStartTip, $this->lang->stage->ipdTypeList[$execution->ipdStage['preAttribute']], $this->lang->stage->ipdTypeList[$execution->attribute]);
                    $task->ipdStage->taskFinishTip = sprintf($this->lang->execution->disabledTip->taskFinishTip, $this->lang->stage->ipdTypeList[$execution->ipdStage['preAttribute']], $this->lang->stage->ipdTypeList[$execution->attribute]);
                    $task->ipdStage->taskRecordTip = sprintf($this->lang->execution->disabledTip->taskRecordTip, $this->lang->stage->ipdTypeList[$execution->ipdStage['preAttribute']], $this->lang->stage->ipdTypeList[$execution->attribute]);
                }
                echo $this->task->buildNestedList($execution, $task, false, $showmore, $users);
            }
        }

        if(!empty($execution->points) and $this->cookie->showStage)
        {
            $pendingReviews = $this->loadModel('approval')->getPendingReviews('review');
            foreach($execution->points as $point) echo $this->buildPointList($execution, $point, $pendingReviews);
        }
    }

    /**
     * Update user view of execution and it's product.
     *
     * @param  int|array $executionID
     * @param  string    $objectType
     * @param  array     $users
     * @access public
     * @return void
     */
    public function updateUserView($executionID, $objectType = 'sprint', $users = array())
    {
        $this->loadModel('user')->updateUserView($executionID, $objectType, $users);

        $products = $this->loadModel('product')->getProducts($executionID, 'all', '', false);
        if(!empty($products)) $this->user->updateUserView(array_keys($products), 'product', $users);
    }

    /**
     * Get the products associated with the stage.
     *
     * @param  string    $stageIdList
     * @access public
     * @return array
     */
    public function getStageLinkProductPairs($stageIdList = array())
    {
        $productpairs = $this->dao->select('t1.project, t2.name')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.project')->in($stageIdList)
            ->fetchPairs('project', 'name');
        return $productpairs;
    }

    /**
     * Set stage tree path.
     *
     * @param  int    $executionID
     * @access public
     * @return bool
     */
    public function setTreePath($executionID)
    {
        $execution = $this->dao->select('id, type, parent, path, grade')->from(TABLE_PROJECT)->where('id')->eq($executionID)->fetch();
        $parent    = $this->dao->select('id, type, parent, path, grade')->from(TABLE_PROJECT)->where('id')->eq($execution->parent)->fetch();

        if($parent->type == 'project')
        {
            $path['path']  =  ",{$parent->id},{$execution->id},";
            $path['grade'] = 1;
        }
        elseif($parent->type == 'stage')
        {
            $path['path']  = $parent->path . "{$execution->id},";
            $path['grade'] = $parent->grade + 1;
        }
        $this->dao->update(TABLE_PROJECT)->set('path')->eq($path['path'])->set('grade')->eq($path['grade'])->where('id')->eq($execution->id)->exec();
    }

    /**
     * Build execution action menu.
     *
     * @param  object $execution
     * @param  string $type
     * @access public
     * @return string
     */
    public function buildOperateMenu($execution, $type = 'view')
    {
        if($execution->deleted) return '';

        $menu   = '';
        $params = "executionID=$execution->id";

        $this->app->loadLang('stage');
        $title = '';

        $disabled = '';
        $menu .= "<div class='divider'></div>";
        if($this->config->systemMode == 'PLM' and in_array($execution->attribute, array_keys($this->lang->stage->ipdTypeList)))
        {
            $execution->ipdStage = $this->canStageStart($execution);
            if($execution->ipdStage['canStart']) $menu .= $this->buildMenu('execution', 'start',    $params, $execution, $type, '', '', 'iframe', true, $disabled, $title);
        }
        else
        {
            $menu .= $this->buildMenu('execution', 'start',    $params, $execution, $type, '', '', 'iframe', true, $disabled, $title);
        }

        $menu .= $this->buildMenu('execution', 'activate', $params, $execution, $type, '', '', 'iframe', true);
        $menu .= $this->buildMenu('execution', 'putoff',   $params, $execution, $type, '', '', 'iframe', true);
        $menu .= $this->buildMenu('execution', 'suspend',  $params, $execution, $type, '', '', 'iframe', true);

        $canClose = true;
        if($this->config->systemMode == 'PLM' and in_array($execution->attribute, array_keys($this->lang->stage->ipdTypeList))) $canClose = $this->canStageClose($execution->id);
        if($canClose) $menu .= $this->buildMenu('execution', 'close',    $params, $execution, $type, '', '', 'iframe', true);

        $menu .= "<div class='divider'></div>";
        $menu .= $this->buildFlowMenu('execution', $execution, 'view', 'direct');
        $menu .= "<div class='divider'></div>";

        $menu .= $this->buildMenu('execution', 'edit',   "execution=$execution->id", $execution);
        $menu .= $this->buildMenu('execution', 'delete', "execution=$execution->id", $execution, 'button', 'trash', 'hiddenwin');

        return $menu;
    }

    /**
     * Print cell data.
     *
     * @param  object $col
     * @param  object $execution
     * @param  array  $users
     * @param  string $mode
     * @param  bool   $isStage
     * @param  int    $productID
     * @param  bool   $child
     * @access public
     * @return void
     */
    public function printCell($col, $execution, $users, $mode = 'datatable', $isStage = false, $productID = 0, $child = false)
    {
        $canBatchEdit   = common::hasPriv('execution', 'batchEdit');
        $id             = $col->id;
        $onlyChildStage = ($execution->grade == 2 and $execution->project != $execution->parent);

        if(!$isStage and in_array($col->id, array('percent', 'attribute', 'actions'))) return;
        if($this->app->tab != 'execution' and $col->id == 'project') return;

        if($col->show)
        {
            $class = "c-{$id}";

            if($id == 'id')      $class .= ' cell-id';
            if($id == 'name')    $class .= ' text-left flex';
            if($id == 'code')    $class .= ' text-left';
            if($id == 'project') $class .= ' text-left c-name';
            if($id == 'status')  $class .= ' text-center';
            if($id == 'actions') $class .= ' text-center';

            if($id == 'name' and !$child) $class .= ' sort-handler';
            if($id == 'name' and !empty($execution->children)) $class .= ' parent';
            if(in_array($id, array('estimate', 'consumed', 'left'))) $class .= ' hours';

            $title = '';
            if($id == 'name')
            {
                $title = " title='{$execution->name}'";
                if(!empty($execution->children)) $class .= ' has-child';
                if(isset($execution->delay)) $class .= ' delay';
            }

            if($id == 'teamCount')
            {
                $title = " title='{$execution->teamCount}'";
                $class .= ' text-right';
            }

            if($id == 'project') $title = " title='{$execution->projectName}'";
            if($id == 'code')    $title = " title='{$execution->code}'";

            if($id == 'status')
            {
                $executionStatus = $this->processStatus('execution', $execution);
                $title           = " title='{$executionStatus}'";
            }

            if(in_array($id, array('estimate', 'consumed', 'left')))
            {
                $totalTitle = 'total' . ucfirst($id);
                $title     .= $execution->hours->{$totalTitle} . $this->lang->execution->workHour;
            }

            echo "<td class='{$class}' $title>";
            if($this->config->edition != 'open') $this->loadModel('flow')->printFlowCell('execution', $execution, $id);
            if($id == 'burn')
            {
                $burnValue = join(',', $execution->burns);
                echo "<span id='spark-{$execution->id}' class='sparkline text-left no-padding' values='$burnValue'></span>";
            }

            switch($id)
            {
            case 'id':
                if($canBatchEdit)
                {
                    echo "<div class='checkbox-primary'><input type='checkbox' name='executionIDList[$execution->id]' value='$execution->id' autocomplete='off'/><label></label></div>";
                }
                echo sprintf('%03d', $execution->id);
                break;
            case 'name':
                $label         = $execution->type == 'stage' ? 'label-warning' : 'label-info';
                $executionLink = $execution->projectModel == 'kanban' ? html::a(helper::createLink('execution', 'kanban', 'executionID=' . $execution->id), $execution->name, '', "class='text-ellipsis'") : html::a(helper::createLink('execution', 'task', 'execution=' . $execution->id), $execution->name, '', "class='text-ellipsis'");
                if(!$onlyChildStage) echo "<span class='project-type-label label label-outline $label'>{$this->lang->execution->typeList[$execution->type]}</span>";
                if($onlyChildStage) echo "<span class='label label-badge label-light label-children'>{$this->lang->programplan->childrenAB}</span> ";
                echo !empty($execution->children) ? "<span class='text-ellipsis'>$execution->name</span>" :  $executionLink;
                if(isset($execution->delay)) echo "<span class='label label-danger label-badge'>{$this->lang->execution->delayed}</span> ";
                if(!empty($execution->children))
                {
                    echo "<a class='plan-toggle' data-id='$execution->id'><i class='icon icon-angle-right'></i></a>";
                }
                break;
            case 'code':
                echo $execution->code;
                break;
            case 'project':
                echo "<span class='status-execution status-{$execution->projectName}'>{$execution->projectName}</span>";
                break;
            case 'PM':
                echo zget($users, $execution->PM);
                break;
            case 'status':
                echo "<span class='status-execution status-{$execution->status}'>$executionStatus</span>";
                break;
            case 'progress':
                echo html::ring($execution->hours->progress);
                break;
            case 'percent':
                echo $execution->percent . '%';
                break;
            case 'attribute':
                echo zget($this->lang->stage->typeList, $execution->attribute, '');
                break;
            case 'begin':
                echo helper::isZeroDate($execution->begin) ? '' : $execution->begin;
                break;
            case 'teamCount':
                echo $execution->teamCount;
                break;
            case 'end':
                echo helper::isZeroDate($execution->end) ? '' : $execution->end;
                break;
            case 'realBegan':
                echo helper::isZeroDate($execution->realBegan) ? '' : $execution->realBegan;
                break;
            case 'realEnd':
                echo helper::isZeroDate($execution->realEnd) ? '' : $execution->realEnd;
                break;
            case 'estimate':
                echo $execution->hours->totalEstimate . $this->lang->execution->workHourUnit;
                break;
            case 'consumed':
                echo $execution->hours->totalConsumed . $this->lang->execution->workHourUnit;
                break;
            case 'left':
                echo $execution->hours->totalLeft . $this->lang->execution->workHourUnit;
                break;
            case 'actions':
                common::printIcon('execution', 'start', "executionID={$execution->id}", $execution, 'list', '', '', 'iframe', true);
                $class = !empty($execution->children) ? 'disabled' : '';
                common::printIcon('task', 'create', "executionID={$execution->id}", $execution, 'list', '', '', $class, false, "data-app='execution'");

                if($execution->grade == 1 && $this->loadModel('programplan')->isCreateTask($execution->id))
                {
                    common::printIcon('programplan', 'create', "program={$execution->parent}&productID=$productID&planID=$execution->id", $execution, 'list', 'split', '', '', '', '', $this->lang->programplan->createSubPlan);
                }
                else
                {
                    $disabled = ($execution->grade == 2) ? ' disabled' : '';
                    echo common::hasPriv('programplan', 'create') ? html::a('javascript:alert("' . $this->lang->programplan->error->createdTask . '");', '<i class="icon-programplan-create icon-split"></i>', '', 'class="btn ' . $disabled . '"') : '';
                }

                common::printIcon('programplan', 'edit', "stageID=$execution->id&projectID=$execution->project", $execution, 'list', '', '', 'iframe', true);

                $disabled = !empty($execution->children) ? ' disabled' : '';
                if($execution->status != 'closed' and common::hasPriv('execution', 'close', $execution))
                {
                    common::printIcon('execution', 'close', "stageID=$execution->id", $execution, 'list', 'off', 'hiddenwin' , $disabled . ' iframe', true, '', $this->lang->programplan->close);
                }
                elseif($execution->status == 'closed' and common::hasPriv('execution', 'activate', $execution))
                {
                    common::printIcon('execution', 'activate', "stageID=$execution->id", $execution, 'list', 'magic', 'hiddenwin' , $disabled . ' iframe', true, '', $this->lang->programplan->activate);
                }

                if(common::hasPriv('execution', 'delete', $execution))
                {
                    common::printIcon('execution', 'delete', "stageID=$execution->id&confirm=no", $execution, 'list', 'trash', 'hiddenwin' , $disabled, '', '', $this->lang->programplan->delete);
                }
                break;
            }
            echo '</td>';
        }
    }

    /**
     * Generate col for dtable.
     *
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function generateCol($orderBy = '')
    {
        $this->loadModel('datatable');

        $setting   = $this->datatable->getSetting('execution');
        $fieldList = $this->config->execution->datatable->fieldList;

        foreach($fieldList as $field => $items)
        {
            $fieldKey = in_array($field, array('name', 'code', 'type', 'PM', 'status')) ? 'exec' . ucfirst($field) : $field;
            $title    = $field == 'id' ? 'ID' : zget($this->lang->execution, $fieldKey, zget($this->lang, $field, $field));
            $fieldList[$field]['title'] = $title;
        }

        if(empty($setting))
        {
            $setting = $this->config->execution->datatable->defaultField;
            $order   = 1;
            foreach($setting as $key => $value)
            {
                $id  = $value;
                $set = new stdclass();;
                $set->order = $order++;
                $set->show  = true;
                $set->name  = $value;
                $set->title = $fieldList[$id]['title'];

                $sortType = '';
                if(strpos($orderBy, $id) !== false)
                {
                    $sort = str_replace("{$id}_", '', $orderBy);
                    $sortType = $sort == 'asc' ? 'up' : 'down';
                }

                if(isset($fieldList[$id]['checkbox']))     $set->checkbox     = $fieldList[$id]['checkbox'];
                if(isset($fieldList[$id]['nestedToggle'])) $set->nestedToggle = $fieldList[$id]['nestedToggle'];
                if(isset($fieldList[$id]['fixed']))        $set->fixed        = $fieldList[$id]['fixed'];
                if(isset($fieldList[$id]['width']))        $set->width        = $fieldList[$id]['width'];
                if(isset($fieldList[$id]['type']))         $set->type         = $fieldList[$id]['type'];
                if(isset($fieldList[$id]['sortType']))     $set->sortType     = $fieldList[$id]['sortType'];
                if(isset($fieldList[$id]['flex']))         $set->flex         = $fieldList[$id]['flex'];
                if(isset($fieldList[$id]['minWidth']))     $set->minWidth     = $fieldList[$id]['minWidth'];
                if(isset($fieldList[$id]['maxWidth']))     $set->maxWidth     = $fieldList[$id]['maxWidth'];
                if(isset($fieldList[$id]['pri']))          $set->pri          = $fieldList[$id]['pri'];

                if($sortType) $set->sortType = $sortType;

                $setting[$key] = $set;
            }
        }
        else
        {
            foreach($setting as $key => $set)
            {
                if(empty($set->show))
                {
                    unset($setting[$key]);
                    continue;
                }

                $sortType = '';
                if(strpos($orderBy, $set->id) !== false)
                {
                    $sort = str_replace("{$set->id}_", '', $orderBy);
                    $sortType = $sort == 'asc' ? 'up' : 'down';
                }

                $set->name  = $set->id;
                $set->title = $fieldList[$set->id]['title'];

                if(isset($fieldList[$set->id]['checkbox']))     $set->checkbox     = $fieldList[$set->id]['checkbox'];
                if(isset($fieldList[$set->id]['nestedToggle'])) $set->nestedToggle = $fieldList[$set->id]['nestedToggle'];
                if(isset($fieldList[$set->id]['fixed']))        $set->fixed        = $fieldList[$set->id]['fixed'];
                if(isset($fieldList[$set->id]['type']))         $set->type         = $fieldList[$set->id]['type'];
                if(isset($fieldList[$set->id]['sortType']))     $set->sortType     = $fieldList[$set->id]['sortType'];
                if(isset($fieldList[$set->id]['flex']))         $set->flex         = $fieldList[$set->id]['flex'];
                if(isset($fieldList[$set->id]['minWidth']))     $set->minWidth     = $fieldList[$set->id]['minWidth'];
                if(isset($fieldList[$set->id]['maxWidth']))     $set->maxWidth     = $fieldList[$set->id]['maxWidth'];
                if(isset($fieldList[$set->id]['pri']))          $set->pri          = $fieldList[$set->id]['pri'];

                if($sortType) $set->sortType = $sortType;

                if(isset($set->width)) $set->width = str_replace('px', '', $set->width);

                unset($set->id);

            }
        }

        usort($setting, array('datatableModel', 'sortCols'));

        return $setting;
    }

    /**
     * Generate row for dtable.
     *
     * @param  array  $executions
     * @param  array  $users
     * @param  int    $productID
     * @access public
     * @return array
     */
    public function generateRow($executions, $users, $productID)
    {
        $today = helper::today();
        $rows  = array();
        foreach($executions as $execution)
        {
            $label = $execution->type == 'stage' ? 'label-warning' : 'label-info';
            $link  = $execution->type == 'kanban' ? helper::createLink('execution', 'kanban', "id=$execution->id") : helper::createLink('execution', 'task', "id=$execution->id");
            $execution->name     = "<span class='project-type-label label label-outline $label'>{$this->lang->execution->typeList[$execution->type]}</span> " . (empty($execution->children) ? html::a($link, $execution->name, '_self', 'class="text-primary"') : $execution->name) . ((strtotime($today) > strtotime($execution->end) and $execution->status != 'closed')? '<span class="label label-danger label-badge">' . $this->lang->execution->delayed . '</span>' : '');;
            $execution->project  = $execution->projectName;
            $execution->parent   = ($execution->parent and $execution->grade > 1) ? $execution->parent : '';
            $execution->asParent = !empty($execution->children);
            $execution->status   = zget($this->lang->execution->statusList, $execution->status);
            $execution->PM       = zget($users, $execution->PM);
            if(isset($this->config->setCode) and $this->config->setCode == 1)
            {
                $execution->code = "<div title=$execution->code style='justify-content: start;'>$execution->code</div>";
            }

            $children = isset($execution->children) ? $execution->children : array();
            unset($execution->children);

            $rows[] = $execution;

            if(!empty($children))
            {
                $rows = array_merge($rows, $this->generateRow($children, $users, $productID));
            }
        }

        return $rows;
    }

    /*
     * Build search form
     *
     * @param int     $queryID
     * @param string  $actionURL
     * @return void
     * */
    public function buildSearchForm($queryID, $actionURL)
    {
        $this->config->execution->all->search['queryID']   = $queryID;
        $this->config->execution->all->search['actionURL'] = $actionURL;

        $projectPairs  = array(0 => '');
        $projectPairs += $this->loadModel('project')->getPairsByProgram('', 'all', false, 'order_asc', '', '', 'multiple');
        $this->config->execution->all->search['params']['project']['values'] = $projectPairs + array('all' => $this->lang->execution->allProject);

        $this->loadModel('search')->setSearchParams($this->config->execution->all->search);
    }

    /*
     * Create default sprint.
     *
     * @param  int $projectID
     * @return int
     * */
    public function createDefaultSprint($projectID)
    {
        $project = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($projectID)->fetch();
        $post    = $_POST;

        $_POST = array();

        $_POST['project']     = $projectID;
        $_POST['name']        = $project->name;
        $_POST['begin']       = $project->begin;
        $_POST['end']         = $project->end;
        $_POST['status']      = 'wait';
        $_POST['days']        = $project->days;
        $_POST['team']        = $project->team;
        $_POST['desc']        = $project->desc;
        $_POST['teamMembers'] = array($this->app->user->account);
        $_POST['acl']         = 'open';
        $_POST['PO']          = $this->app->user->account;
        $_POST['QD']          = $this->app->user->account;
        $_POST['PM']          = $this->app->user->account;
        $_POST['RD']          = $this->app->user->account;
        $_POST['multiple']    = '0';
        $_POST['hasProduct']  = $project->hasProduct;
        if($project->code) $_POST['code'] = $project->code;

        $projectProducts = $this->dao->select('*')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($projectID)->fetchAll();
        foreach($projectProducts as $projectProduct)
        {
            $_POST['products'][] = $projectProduct->product;
            $_POST['branch'][]   = $projectProduct->branch;
            if($projectProduct->plan) $_POST['plans'][$projectProduct->product][$projectProduct->branch] = explode(',', trim($projectProduct->plan, ','));
        }

        $executionID = $this->create();
        if($project->model == 'kanban')
        {
            $execution = $this->getById($executionID);
            $this->loadModel('kanban')->createRDKanban($execution);
        }

        $_POST = $post;

        return $executionID;
    }

    /**
     * Sync no multiple project to sprint.
     *
     * @param  int    $projectID
     * @access public
     * @return int
     */
    public function syncNoMultipleSprint($projectID)
    {
        $project = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($projectID)->fetch();
        if(empty($project)) return 0;

        $post    = $_POST;

        $_POST = array();

        $_POST['project']   = $projectID;
        $_POST['name']      = $project->name;
        $_POST['begin']     = $project->begin;
        $_POST['end']       = $project->end;
        $_POST['realBegan'] = $project->realBegan;
        $_POST['realEnd']   = $project->realEnd;
        $_POST['days']      = $project->days;
        $_POST['team']      = $project->team;
        $_POST['PO']        = $project->PO;
        $_POST['QD']        = $project->QD;
        $_POST['PM']        = $project->PM;
        $_POST['RD']        = $project->RD;
        $_POST['status']    = $project->status;
        $_POST['acl']       = 'open';

        /* Handle extend fields. */
        $extendFields = $this->loadModel('project')->getFlowExtendFields();
        foreach($extendFields as $field)
        {
            $_POST[$field->field] = $product->field;
        }

        if(isset($this->config->setCode) and $this->config->setCode == 1) $_POST['code'] = $project->code;

        $projectProducts = $this->dao->select('*')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($projectID)->fetchAll();
        foreach($projectProducts as $projectProduct)
        {
            $_POST['products'][] = $projectProduct->product;
            $_POST['branch'][]   = $projectProduct->branch;
            if($projectProduct->plan) $_POST['plans'][$projectProduct->product][$projectProduct->branch] = explode(',', trim($projectProduct->plan, ','));
        }

        $teamMembers = $this->dao->select('*')->from(TABLE_TEAM)->where('type')->eq('project')->andWhere('root')->eq($projectID)->fetchPairs('account', 'account');
        $_POST['teamMembers'] = array_values($teamMembers);

        $executionID = $this->dao->select('*')->from(TABLE_EXECUTION)->where('project')->eq($projectID)->andWhere('type')->in('sprint,kanban')->andWhere('multiple')->eq(0)->fetch('id');
        if($executionID)
        {
            $this->update($executionID);
            $this->updateProducts($executionID);
        }

        $_POST = $post;

        return $executionID;
    }

    /**
     * Expand executions, return id list.
     *
     * @param  object $stats
     * @access public
     * @return array
     */
    public function expandExecutionIdList($stats)
    {
        $executionIdList = array();
        foreach($stats as $execution)
        {
            $executionIdList[$execution->id] = $execution->id;
            if(!empty($execution->children))
            {
                foreach($execution->children as $child)
                {
                    $childrenIdList = $this->expandExecutionChildrenIdList($child);
                    foreach($childrenIdList as $childID)
                    {
                        $executionIdList[$childID] = $childID;
                    }
                }
            }
        }

        return $executionIdList;
    }

    /**
     * Expand children of execution, return id list.
     *
     * @param object $execution
     * @access public
     * @return array
     */
    public function expandExecutionChildrenIdList($execution)
    {
        $executionIdList = array();
        $executionIdList[$execution->id] = $execution->id;

        if(!empty($execution->children))
        {
            foreach($execution->children as $child)
            {
                $childrenIdList = $this->expandExecutionChildrenIdList($child);
                foreach($childrenIdList as $childID)
                {
                    $executionIdList[$childID] = $childID;
                }
            }
        }

        return $executionIdList;
    }

    /**
     * Build execution object by status.
     *
     * @param  string    $status
     * @access public
     * @return object
     */
    public function buildExecutionByStatus($status)
    {
        $execution = new stdclass();
        $execution->status         = $status;
        $execution->lastEditedBy   = $this->app->user->account;
        $execution->lastEditedDate = helper::now();

        if($status == 'wait')
        {
            $execution->realBegan     = '';
            $execution->realEnd       = '';
            $execution->closedBy      = '';
            $execution->closedDate    = '';
            $execution->canceledBy    = '';
            $execution->canceledDate  = '';
            $execution->suspendedDate = '';
        }

        if($status == 'doing')
        {
            $execution->realBegan     = helper::today();
            $execution->realEnd       = '';
            $execution->closedBy      = '';
            $execution->closedDate    = '';
            $execution->canceledBy    = '';
            $execution->canceledDate  = '';
            $execution->suspendedDate = '';
        }

        if($status == 'suspended')
        {
            $execution->suspendedDate = helper::now();
            $execution->closedBy      = '';
            $execution->closedDate    = '';
        }

        if($status == 'closed')
        {
            $execution->closedBy   = $this->app->user->account;
            $execution->closedDate = helper::now();
        }

        return $execution;
    }

    /**
     * Reset execution orders.
     *
     * @param  array  $executions
     * @param  array  $parentExecutions
     * @param  array  $childExecutions
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function resetExecutionSorts($executions, $parentExecutions = array(), $childExecutions = array(), $projectID = 0)
    {
        if(empty($executions)) return array();
        if(empty($parentExecutions))
        {
            $execution        = current($executions);
            $projectID        = isset($execution->project) ? $execution->project : $projectID;
            $parentExecutions = $this->dao->select('id,parent,project,grade,status,name,type,PM')->from(TABLE_EXECUTION)
                ->where('parent')->eq($projectID)
                ->andWhere('deleted')->eq('0')
                ->andWhere('type')->in('kanban,sprint,stage')
                ->andWhere('grade')->eq(1)
                ->orderBy('order_asc')
                ->fetchAll('id');
        }

        if(empty($childExecutions))
        {
            $childExecutions = $this->dao->select('id,parent,project,grade,status,name,type,PM')->from(TABLE_EXECUTION)
                ->where('deleted')->eq(0)
                ->andWhere('parent')->in(array_keys($parentExecutions))
                ->orderBy('order_asc')
                ->fetchGroup('parent', 'id');
        }

        $sortedExecutions = array();
        foreach($parentExecutions as $executionID => $execution)
        {
            if(!isset($sortedExecutions[$executionID]) and isset($executions[$executionID])) $sortedExecutions[$executionID] = $executions[$executionID];

            $children = isset($childExecutions[$executionID]) ? $childExecutions[$executionID] : array();
            if(!empty($children)) $sortedExecutions += $this->resetExecutionSorts($executions, $children);
        }
        return $sortedExecutions;
    }
}
