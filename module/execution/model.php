<?php
/**
 * The model file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     execution
 * @version     $Id: model.php 5118 2013-07-12 07:41:41Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
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
        if(empty($executionID)) return false;

        /* If is admin, return true. */
        if($this->app->user->admin) return true;
        return (strpos(",{$this->app->user->view->sprints},", ",{$executionID},") !== false);
    }

    /**
     * Show accessDenied response.
     *
     * @access private
     * @return void
     */
    public function accessDenied()
    {
        echo(js::alert($this->lang->execution->accessDenied));

        if(!$this->server->http_referer) die(js::locate(helper::createLink('execution', 'index')));

        $loginLink = $this->config->requestType == 'GET' ? "?{$this->config->moduleVar}=user&{$this->config->methodVar}=login" : "user{$this->config->requestFix}login";
        if(strpos($this->server->http_referer, $loginLink) !== false) die(js::locate(helper::createLink('execution', 'index')));

        die(js::locate('back'));
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
        $executions = $this->loadModel('execution')->getPairs(0, 'all', 'nocode');
        if(!$executionID and $this->session->execution) $executionID = $this->session->execution;
        if(!$executionID or !in_array($executionID, array_keys($executions))) $executionID = key($executions);
        $this->session->set('execution', $executionID);

        /* Unset story, bug, build and testtask if type is ops. */
        $execution = $this->getByID($executionID);

        if($execution->type == 'stage')
        {
            global $lang;
            $this->app->loadLang('project');
            $lang->executionCommon = $lang->project->stage;
            include $this->app->getModulePath('execution') . 'lang/' . $this->app->getClientLang() . '.php';
        }

        if($execution and $execution->lifetime == 'ops')
        {
            unset($this->lang->execution->menu->story);
            unset($this->lang->execution->menu->qa);
            unset($this->lang->execution->menu->build);
        }

        /* Hide story and qa menu when execution is story or design type. */
        /*
        if($execution and ($execution->attribute == 'story' or $execution->attribute == 'design'))
        {
            unset($this->lang->execution->menu->story);
            unset($this->lang->execution->menu->qa);
        }
         */

        if($executions and (!isset($executions[$executionID]) or !$this->checkPriv($executionID))) $this->accessDenied();

        $moduleName = $this->app->getModuleName();
        $methodName = $this->app->getMethodName();

        if($this->cookie->executionMode == 'noclosed' and ($execution->status == 'done' or $execution->status == 'closed'))
        {
            setcookie('executionMode', 'all');
            $this->cookie->executionMode = 'all';
        }

        $this->lang->switcherMenu = $this->getSwitcher($executionID, $this->app->rawModule, $this->app->rawMethod);
        common::setMenuVars('execution', $executionID);
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

        if($this->config->systemMode == 'new')
        {
            if(isset($currentExecution->type) and $currentExecution->type == 'program') return;

            if($currentExecution->project) $project = $this->loadModel('project')->getByID($currentExecution->project);

            if(isset($project) and $project->model == 'waterfall')
            {
                $productID   = $this->loadModel('product')->getProductIDByProject($project->id);
                $productName = $this->dao->findByID($productID)->from(TABLE_PRODUCT)->fetch('name');
                $currentExecution->name = $productName . '/' . $currentExecution->name;
            }
        }

        $dropMenuLink = helper::createLink('execution', 'ajaxGetDropMenu', "executionID=$executionID&module=$currentModule&method=$currentMethod&extra=$extra");
        $currentExecutionName = '';
        if(isset($currentExecution->name)) $currentExecutionName = $currentExecution->name;

        if($this->config->systemMode == 'classic')
        {
            $output  = "<div class='btn-group header-btn' id='swapper'><button data-toggle='dropdown' type='button' class='btn' id='currentItem' title='{$currentExecutionName}'><span class='text'><i class='icon icon-sprint'></i> {$currentExecutionName}</span> <span class='caret'></span></button><div id='dropMenu' class='dropdown-menu search-list' data-ride='searchList' data-url='$dropMenuLink'>";
            $output .= '<div class="input-control search-box has-icon-left has-icon-right search-example"><input type="search" class="form-control search-input" /><label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label><a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a></div>';
            $output .= "</div></div>";

            $this->lang->execution->switcherMenu = $output;
        }

        $output  = "<div class='btn-group angle-btn'><div class='btn-group'><button data-toggle='dropdown' type='button' class='btn btn-limit' id='currentItem' title='{$currentExecutionName}'><span class='text'><i class='icon icon-{$this->lang->icons[$currentExecution->type]}'></i> {$currentExecutionName}</span> <span class='caret'></span></button><div id='dropMenu' class='dropdown-menu search-list' data-ride='searchList' data-url='$dropMenuLink'>";
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
        /* When the cookie and session do not exist, get it from the database. */
        if(empty($executionID) and isset($this->config->execution->lastExecution) and isset($executions[$this->config->execution->lastExecution]))
        {
            $this->session->set('execution', $this->config->execution->lastExecution);
            $this->setProjectSession($this->session->execution);
            return $this->session->execution;
        }

        if($executionID > 0) $this->session->set('execution', (int)$executionID);
        if($executionID == 0 and $this->cookie->lastExecution)
        {
            /* Execution link is execution-task. */
            $executionID = (int)$this->cookie->lastExecution;
            $executionID = in_array($executionID, array_keys($executions)) ? $executionID : key($executions);
            $this->session->set('execution', $executionID);
        }
        if($executionID == 0 and $this->session->execution == '') $this->session->set('execution', key($executions));
        if(!isset($executions[$this->session->execution]))
        {
            $this->session->set('execution', key($executions));
            if($executionID && strpos(",{$this->app->user->view->sprints},", ",{$this->session->execution},") === false) $this->accessDenied();
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
        $this->session->set('project', $execution->project);
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
        $type = 'sprint';
        $this->lang->execution->team = $this->lang->execution->teamname;

        if($this->config->systemMode == 'new')
        {
            if(empty($_POST['project']))
            {
                dao::$errors['message'][] = $this->lang->execution->projectNotEmpty;
                return false;
            }

            if($this->config->systemMode == 'new') $this->checkBeginAndEndDate($_POST['project'], $_POST['begin'], $_POST['end']);
            if(dao::isError()) return false;

            /* Determine whether to add a sprint or a stage according to the model of the execution. */
            $project = $this->loadModel('project')->getByID($_POST['project']);
            $type = zget($this->config->execution->modelList, $project->model, 'sprint');

            /* If the execution model is a stage, determine whether the product is linked. */
            if($type == 'stage' and empty($this->post->products[0]))
            {
                dao::$errors['message'][] = $this->lang->execution->noLinkProduct;
                return false;
            }

            $this->config->execution->create->requiredFields .= ',project';
        }

        /* Get the data from the post. */
        $sprint = fixer::input('post')
            ->setDefault('status', 'wait')
            ->setDefault('openedBy', $this->app->user->account)
            ->setDefault('openedDate', helper::now())
            ->setDefault('openedVersion', $this->config->version)
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', helper::now())
            ->setDefault('team', substr($this->post->name, 0, 30))
            ->setIF($this->config->systemMode == 'new', 'parent', $this->post->project)
            ->setIF($this->post->acl == 'open', 'whitelist', '')
            ->join('whitelist', ',')
            ->add('type', $type)
            ->stripTags($this->config->execution->editor->create['id'], $this->config->allowedTags)
            ->remove('products, workDays, delta, branch, uid, plans')
            ->get();

        /* Check the workload format and total. */
        if(!empty($sprint->percent)) $this->checkWorkload('create', $sprint->percent);

        /* Set planDuration and realDuration. */
        if(isset($this->config->maxVersion))
        {
            $sprint->planDuration = $this->loadModel('programplan')->getDuration($sprint->begin, $sprint->end);
            if(!empty($sprint->realBegan) and !empty($sprint->realEnd)) $sprint->realDuration = $this->loadModel('programplan')->getDuration($sprint->realBegan, $sprint->realEnd);
        }

        $sprint = $this->loadModel('file')->processImgURL($sprint, $this->config->execution->editor->create['id'], $this->post->uid);

        /* Replace required language. */
        if($this->app->openApp == 'project')
        {
            $this->lang->project->name = $this->lang->execution->name;
            $this->lang->project->code = $this->lang->execution->code;
        }

        $this->dao->insert(TABLE_EXECUTION)->data($sprint)
            ->autoCheck($skipFields = 'begin,end')
            ->batchcheck($this->config->execution->create->requiredFields, 'notempty')
            ->checkIF($sprint->begin != '', 'begin', 'date')
            ->checkIF($sprint->end != '', 'end', 'date')
            ->checkIF($sprint->end != '', 'end', 'gt', $sprint->begin)
            ->checkIF(!empty($sprint->code), 'code', 'unique')
            ->exec();

        /* Add the creater to the team. */
        if(!dao::isError())
        {
            $executionID   = $this->dao->lastInsertId();
            $today         = helper::today();
            $creatorExists = false;

            /* Save order. */
            $this->dao->update(TABLE_EXECUTION)->set('`order`')->eq($executionID * 5)->where('id')->eq($executionID)->exec();
            $this->file->updateObjectID($this->post->uid, $executionID, 'execution');

            /* Update the path. */
            if($this->config->systemMode == 'new') $this->setTreePath($executionID);

            /* Copy team of execution. */
            if($copyExecutionID != '')
            {
                $members = $this->dao->select('*')->from(TABLE_TEAM)->where('root')->eq($copyExecutionID)->andWhere('type')->eq('execution')->fetchAll();
                foreach($members as $member)
                {
                    unset($member->id);
                    $member->root = $executionID;
                    $member->join = $today;
                    $member->days = $sprint->days;
                    $member->type = 'execution';
                    $this->dao->insert(TABLE_TEAM)->data($member)->exec();
                    if($member->account == $this->app->user->account) $creatorExists = true;
                }
            }

            /* Add the creator to team. */
            if($copyExecutionID == '' or !$creatorExists)
            {
                $this->app->loadLang('user');
                $member = new stdclass();
                $member->root    = $executionID;
                $member->account = $this->app->user->account;
                $member->role    = zget($this->lang->user->roleList, $this->app->user->role, '');
                $member->join    = $today;
                $member->type    = 'execution';
                $member->days    = $sprint->days;
                $member->hours   = $this->config->execution->defaultWorkhours;
                $this->dao->insert(TABLE_TEAM)->data($member)->exec();

                if($this->config->systemMode == 'new') $this->addProjectMembers($sprint->project, array($member));
            }

            /* Create doc lib. */
            $this->app->loadLang('doc');
            $lib = new stdclass();
            $lib->execution = $executionID;
            $lib->name      = $type == 'stage' ? str_replace($this->lang->executionCommon, $this->lang->project->stage, $this->lang->doclib->main['execution']) : $this->lang->doclib->main['execution'];
            $lib->type      = 'execution';
            $lib->main      = '1';
            $lib->acl       = 'default';
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
        if($this->post->code == '')
        {
            dao::$errors['code'] = sprintf($this->lang->error->notempty, $this->lang->execution->code);
            return false;
        }

        if($oldExecution->type == 'stage' and empty($this->post->products[0]))
        {
            dao::$errors['message'][] = $this->lang->execution->noLinkProduct;
            return false;
        }

        /* Get team and language item. */
        $team = $this->loadModel('user')->getTeamMemberPairs($executionID, 'execution');
        $this->lang->execution->team = $this->lang->execution->teamname;

        /* Get the data from the post. */
        $execution = fixer::input('post')
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', helper::now())
            ->setIF(helper::isZeroDate($this->post->begin), 'begin', '')
            ->setIF(helper::isZeroDate($this->post->end), 'end', '')
            ->setIF($this->post->acl != 'custom', 'whitelist', '')
            ->setDefault('team', $this->post->name)
            ->join('whitelist', ',')
            ->stripTags($this->config->execution->editor->edit['id'], $this->config->allowedTags)
            ->remove('products, branch, uid, plans')
            ->get();

        if($this->config->systemMode == 'new') $this->checkBeginAndEndDate($oldExecution->project, $execution->begin, $execution->end);
        if(dao::isError()) return false;

        /* Child stage inherits parent stage permissions. */
        if(!isset($execution->acl)) $execution->acl = $oldExecution->acl;
        if($execution->acl == 'open') $execution->whitelist = '';

        $execution = $this->loadModel('file')->processImgURL($execution, $this->config->execution->editor->edit['id'], $this->post->uid);

        /* Check the workload format and total. */
        if(!empty($execution->percent)) $this->checkWorkload('update', $execution->percent, $oldExecution);

        /* Set planDuration and realDuration. */
        if(isset($this->config->maxVersion))
        {
            $execution->planDuration = $this->loadModel('programplan')->getDuration($execution->begin, $execution->end);
            if(!empty($execution->realBegan) and !empty($execution->realEnd)) $execution->realDuration = $this->loadModel('programplan')->getDuration($execution->realBegan, $execution->realEnd);
        }

        /* Update data. */
        $this->dao->update(TABLE_EXECUTION)->data($execution)
            ->autoCheck($skipFields = 'begin,end')
            ->batchcheck($this->config->execution->edit->requiredFields, 'notempty')
            ->checkIF($execution->begin != '', 'begin', 'date')
            ->checkIF($execution->end != '', 'end', 'date')
            ->checkIF($execution->end != '', 'end', 'gt', $execution->begin)
            ->check('code', 'unique', "id!=$executionID and deleted='0'")
            ->where('id')->eq($executionID)
            ->limit(1)
            ->exec();

        $changedAccounts = array();
        foreach($execution as $fieldName => $value)
        {
            if($fieldName == 'PO' or $fieldName == 'PM' or $fieldName == 'QD' or $fieldName == 'RD' )
            {
                if(!empty($value) and !isset($team[$value]))
                {
                    $member = new stdclass();
                    $member->root    = (int)$executionID;
                    $member->account = $value;
                    $member->join    = helper::today();
                    $member->role    = $this->lang->execution->$fieldName;
                    $member->days    = zget($execution, 'days', 0);
                    $member->type    = 'execution';
                    $member->hours   = $this->config->execution->defaultWorkhours;
                    $this->dao->replace(TABLE_TEAM)->data($member)->exec();

                    $changedAccounts[] = $value;
                }
            }
        }

        $whitelist = explode(',', $execution->whitelist);
        $this->loadModel('personnel')->updateWhitelist($whitelist, 'sprint', $executionID);

        /* Fix bug#3074, Update views for team members. */
        if($execution->acl != 'open') $this->updateUserView($executionID, 'sprint', $changedAccounts);

        if(!dao::isError())
        {
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

        $executions    = array();
        $allChanges  = array();
        $data        = fixer::input('post')->get();
        $oldExecutions = $this->getByIdList($this->post->executionIDList);
        $nameList    = array();
        $codeList    = array();

        /* Replace required language. */
        if($this->app->openApp == 'project')
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

        foreach($data->executionIDList as $executionID)
        {
            $executionName = $data->names[$executionID];
            $executionCode = $data->codes[$executionID];

            $executionID = (int)$executionID;
            $executions[$executionID] = new stdClass();
            $executions[$executionID]->name           = $executionName;
            $executions[$executionID]->code           = $executionCode;
            $executions[$executionID]->PM             = $data->PMs[$executionID];
            $executions[$executionID]->PO             = $data->POs[$executionID];
            $executions[$executionID]->QD             = $data->QDs[$executionID];
            $executions[$executionID]->RD             = $data->RDs[$executionID];
            $executions[$executionID]->lifetime       = $data->lifetimes[$executionID];
            $executions[$executionID]->attribute      = $data->attributes[$executionID];
            $executions[$executionID]->status         = $data->statuses[$executionID];
            $executions[$executionID]->begin          = $data->begins[$executionID];
            $executions[$executionID]->end            = $data->ends[$executionID];
            $executions[$executionID]->team           = $data->teams[$executionID];
            $executions[$executionID]->desc           = htmlspecialchars_decode($data->descs[$executionID]);
            $executions[$executionID]->days           = $data->dayses[$executionID];
            $executions[$executionID]->order          = $data->orders[$executionID];
            $executions[$executionID]->lastEditedBy   = $this->app->user->account;
            $executions[$executionID]->lastEditedDate = helper::now();

            /* Check unique name for edited executions. */
            if(isset($nameList[$executionName])) dao::$errors['name'][] = 'execution#' . $executionID .  sprintf($this->lang->error->unique, $this->lang->execution->name, $executionName);
            $nameList[$executionName] = $executionName;

            if(empty($executionCode))
            {
                dao::$errors['code'][] = 'execution#' . $executionID .  sprintf($this->lang->error->notempty, $this->lang->project->code);
            }
            else
            {
                /* Check unique code for edited executions. */
                if(isset($codeList[$executionCode])) dao::$errors['code'][] = 'execution#' . $executionID .  sprintf($this->lang->error->unique, $this->lang->project->code, $executionCode);
                $codeList[$executionCode] = $executionCode;
            }
        }
        if(dao::isError()) die(js::error(dao::getError()));

        foreach($executions as $executionID => $execution)
        {
            $oldExecution = $oldExecutions[$executionID];
            $team         = $this->loadModel('user')->getTeamMemberPairs($executionID, 'execution');

            $this->dao->update(TABLE_EXECUTION)->data($execution)
                ->autoCheck($skipFields = 'begin,end')
                ->batchcheck($this->config->execution->edit->requiredFields, 'notempty')
                ->checkIF($execution->begin != '', 'begin', 'date')
                ->checkIF($execution->end != '', 'end', 'date')
                ->checkIF($execution->end != '', 'end', 'gt', $execution->begin)
                ->checkIF($execution->code != '', 'code', 'unique', "id NOT " . helper::dbIN($data->executionIDList) . " and deleted='0'")
                ->where('id')->eq($executionID)
                ->limit(1)
                ->exec();
            if(dao::isError()) die(js::error('execution#' . $executionID . dao::getError(true)));

            $changedAccounts = array();
            foreach($execution as $fieldName => $value)
            {
                if($fieldName == 'PO' or $fieldName == 'PM' or $fieldName == 'QD' or $fieldName == 'RD' )
                {
                    if(!empty($value) and !isset($team[$value]))
                    {
                        $member = new stdClass();
                        $member->root    = (int)$executionID;
                        $member->type    = 'execution';
                        $member->account = $value;
                        $member->join    = helper::today();
                        $member->role    = $this->lang->execution->$fieldName;
                        $member->days    = 0;
                        $member->hours   = $this->config->execution->defaultWorkhours;
                        $this->dao->replace(TABLE_TEAM)->data($member)->exec();

                        $changedAccounts[] = $value;
                    }
                }
            }
            if(!empty($changedAccounts)) $this->updateUserView($executionID, 'sprint', $changedAccounts);

            $allChanges[$executionID] = common::createChanges($oldExecution, $execution);
        }
        $this->fixOrder();
        return $allChanges;
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
        $now        = helper::now();

        $execution = fixer::input('post')
            ->add('realBegan', $now)
            ->setDefault('status', 'doing')
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', $now)
            ->remove('comment')->get();

        $this->dao->update(TABLE_EXECUTION)->data($execution)->autoCheck()->where('id')->eq((int)$executionID)->exec();

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
        $now        = helper::now();
        $execution = fixer::input('post')
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', $now)
            ->remove('comment')
            ->get();

        if($this->config->systemMode == 'new') $this->checkBeginAndEndDate($oldExecution->project, $execution->begin, $execution->end);
        if(dao::isError()) return false;

        $this->dao->update(TABLE_EXECUTION)->data($execution)
            ->autoCheck()
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
        $now        = helper::now();
        $execution = fixer::input('post')
            ->setDefault('status', 'suspended')
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', $now)
            ->remove('comment')->get();

        $this->dao->update(TABLE_EXECUTION)->data($execution)
            ->autoCheck()
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
        $now        = helper::now();
        $execution = fixer::input('post')
            ->setDefault('status', 'doing')
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', $now)
            ->remove('comment,readjustTime,readjustTask')
            ->get();

        if(!$this->post->readjustTime)
        {
            unset($execution->begin);
            unset($execution->end);
        }

        $this->dao->update(TABLE_EXECUTION)->data($execution)
            ->autoCheck()
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

        if(!dao::isError()) return common::createChanges($oldExecution, $execution);
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
        $now        = helper::now();
        $execution = fixer::input('post')
            ->setDefault('status', 'closed')
            ->setDefault('closedBy', $this->app->user->account)
            ->setDefault('closedDate', $now)
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', $now)
            ->remove('comment')
            ->get();

        $this->dao->update(TABLE_EXECUTION)->data($execution)
            ->autoCheck()
            ->where('id')->eq((int)$executionID)
            ->exec();
        if(!dao::isError())
        {
            $this->loadModel('score')->create('execution', 'close', $oldExecution);
            return common::createChanges($oldExecution, $execution);
        }
    }

    /**
     * Check the workload format and total.
     *
     * @param  string $type create|update
     * @param  int    $percent
     * @param  object $oldExecution
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
        if($type == 'create' or $oldExecution->grade == 1)
        {
            $oldPercentTotal = $this->dao->select('SUM(t2.percent) as total')->from(TABLE_PROJECTPRODUCT)->alias('t1')
                ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.project=t2.id')
                ->where('t1.product')->eq($this->post->products[0])
                ->andWhere('t2.type')->eq('stage')
                ->andWhere('t2.grade')->eq(1)
                ->andWhere('t2.deleted')->eq(0)
                ->fetch('total');

            if($type == 'create') $percentTotal = $percent + $oldPercentTotal;
            if(!empty($oldExecution) and $oldExecution->grade == 1) $percentTotal = $oldPercentTotal - $oldExecution->percent + $this->post->percent;

            if($percentTotal >100)
            {
                dao::$errors['percent'] = sprintf($this->lang->execution->workloadTotal, $oldPercentTotal . '%');
                return false;
            }
        }

        if($type == 'update' and $oldExecution->grade == 2)
        {
            $parentPlan           = $this->loadModel('programPlan')->getByID($oldExecution->parent);
            $childrenTotalPercent = $this->dao->select('SUM(percent) as total')->from(TABLE_EXECUTION)->where('parent')->eq($oldExecution->parent)->andWhere('deleted')->eq(0)->fetch('total');
            $childrenTotalPercent = $childrenTotalPercent - $oldExecution->percent + $this->post->percent;

            if($childrenTotalPercent > 100)
            {
                dao::$errors['parent'] = sprintf($this->lang->execution->workloadTotal, $childrenTotalPercent . '%');
                return false;
            }
        }
    }

    /**
     * Check begin and end date.
     *
     * @param  string $begin
     * @param  string $end
     * @access public
     * @return void
     */
    public function checkBeginAndEndDate($projectID, $begin, $end)
    {
        $project = $this->loadModel('project')->getByID($projectID);
        if($begin < $project->begin) dao::$errors['begin'] = sprintf($this->lang->execution->errorBegin, $project->begin);

        if($end > $project->end) dao::$errors['end'] = sprintf($this->lang->execution->errorEnd, $project->end);
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

        $currentExecutionName = $this->lang->execution->common;
        if($executionID)
        {
            $currentExecution     = $this->getById($executionID);
            $currentExecutionName = $currentExecution->name;
        }

        if($this->app->viewType == 'mhtml' and $executionID)
        {
            $output  = html::a(helper::createLink('execution', 'index'), $this->lang->executionCommon) . $this->lang->colon;
            $output .= "<a id='currentItem' href=\"javascript:showSearchMenu('execution', '$executionID', '$currentModule', '$currentMethod', '')\">{$currentExecutionName} <span class='icon-caret-down'></span></a><div id='currentItemDropMenu' class='hidden affix enter-from-bottom layer'></div>";
            return $output;
        }

        $dropMenuLink = helper::createLink('execution', 'ajaxGetDropMenu', "executionID=$executionID&module=$currentModule&method=$currentMethod&extra=");
        $output  = "<div class='btn-group header-btn' id='swapper'><button data-toggle='dropdown' type='button' class='btn' id='currentItem' title='{$currentExecutionName}'><span class='text'>{$currentExecutionName}</span> <span class='caret'></span></button><div id='dropMenu' class='dropdown-menu search-list' data-ride='searchList' data-url='$dropMenuLink'>";
        $output .= '<div class="input-control search-box has-icon-left has-icon-right search-example"><input type="search" class="form-control search-input" /><label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label><a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a></div>';
        $output .= "</div></div>";

        return $output;
    }

    /**
     * Get project pairs.
     *
     * @param  int    $projectID
     * @param  string $type all|sprint|stage|kanban
     * @param  string $mode all|noclosed or empty
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
            $orderBy = $executionModel == 'waterfall' ? 'sortStatus_asc,begin_asc,id_asc' : 'id_desc';

            /* Waterfall execution, when all phases are closed, in reverse order of date. */
            if($executionModel == 'waterfall')
            {
                $summary = $this->dao->select('count(id) as executions, sum(IF(INSTR("closed", status) < 1, 0, 1)) as closedExecutions')->from(TABLE_EXECUTION)->where('project')->eq($projectID)->andWhere('deleted')->eq('0')->fetch();
                if($summary->executions == $summary->closedExecutions) $orderBy = 'sortStatus_asc,begin_desc,id_asc';
            }
        }

        /* Order by status's content whether or not done */
        $executions = $this->dao->select('*, IF(INSTR("done,closed", status) < 2, 0, 1) AS isDone, INSTR("doing,wait,suspended,closed", status) AS sortStatus')->from(TABLE_EXECUTION)
            ->where('deleted')->eq(0)
            ->beginIF($type == 'all' && $this->config->systemMode == 'new')->andWhere('type')->in('stage,sprint')->fi()
            ->beginIF($projectID && $this->config->systemMode == 'new')->andWhere('project')->eq($projectID)->fi()
            ->beginIF($type != 'all' && $this->config->systemMode == 'new')->andWhere('type')->eq($type)->fi()
            ->beginIF(strpos($mode, 'withdelete') === false)->andWhere('deleted')->eq(0)->fi()
            ->beginIF(!$this->app->user->admin and strpos($mode, 'all') === false)->andWhere('id')->in($this->app->user->view->sprints)->fi()
            ->orderBy($orderBy)
            ->fetchAll();

        $pairs = array();
        foreach($executions as $execution)
        {
            if(strpos($mode, 'noclosed') !== false and ($execution->status == 'done' or $execution->status == 'closed')) continue;
            $pairs[$execution->id] = $execution->name;
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
     * Get execution by idList.
     *
     * @param  array  $executionIdList
     * @access public
     * @return array
     */
    public function getByIdList($executionIdList = array())
    {
        return $this->dao->select('*')->from(TABLE_EXECUTION)->where('id')->in($executionIdList)->fetchAll('id');
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
     * @access public
     * @return array
     */
    public function getList($projectID = 0, $type = 'all', $status = 'all', $limit = 0, $productID = 0, $branch = 0)
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
                ->beginIF(!$this->app->user->admin)->andWhere('t2.id')->in($this->app->user->view->sprints)->fi()
                ->orderBy('order_desc')
                ->beginIF($limit)->limit($limit)->fi()
                ->fetchAll('id');
        }
        else
        {
            return $this->dao->select('*, IF(INSTR(" done,closed", status) < 2, 0, 1) AS isDone')->from(TABLE_EXECUTION)
                ->where('deleted')->eq(0)
                ->beginIF($type == 'all')->andWhere('type')->in('sprint,stage,kanban')->fi()
                ->beginIF($type != 'all')->andWhere('type')->eq($type)->fi()
                ->beginIF($projectID)->andWhere('project')->eq($projectID)->fi()
                ->beginIF($status == 'undone')->andWhere('status')->notIN('done,closed')->fi()
                ->beginIF($status != 'all' and $status != 'undone')->andWhere('status')->in($status)->fi()
                ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->sprints)->fi()
                ->orderBy('order_desc')
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
            return $this->dao->select('t1.*, IF(INSTR(" done,closed", t1.status) < 2, 0, 1) AS isDone')->from(TABLE_EXECUTION)->alias('t1')
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
            ->where('deleted')->eq('0')
            ->beginIF($projectID)->andWhere('project')->eq($projectID)->fi()
            ->beginIF($status == 'undone')->andWhere('status')->notIN('done,closed')->fi()
            ->beginIF($status != 'all' and $status != 'undone')->andWhere('status')->in($status)->fi()
            ->andWhere('type')->in('sprint,stage')
            ->fetchPairs('id', 'id');
    }

    /**
     * Get executions by project.
     *
     * @param  int     $projectID
     * @param  string  $status
     * @param  int     $limit
     * @param  string  $pairs
     * @access public
     * @return array
     */
    public function getByProject($projectID, $status = 'all', $limit = 0, $pairs = false)
    {
        $project = $this->loadModel('project')->getByID($projectID);
        $orderBy = (isset($project->model) and $project->model == 'waterfall') ? 'begin_asc,id_asc' : 'begin_desc,id_desc';

        $executions = $this->dao->select('*')->from(TABLE_EXECUTION)
            ->where('deleted')->eq('0')
            ->beginIF($projectID)->andWhere('project')->eq((int)$projectID)->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->sprints)->fi()
            ->beginIF($status == 'undone')->andWhere('status')->notIN('done,closed')->fi()
            ->beginIF($status != 'all' and $status != 'undone')->andWhere('status')->in($status)->fi()
            ->andWhere('type')->in('stage,sprint')
            ->orderBy($orderBy)
            ->beginIF($limit)->limit($limit)->fi()
            ->fetchAll('id');

        if(isset($project->model) and $project->model == 'waterfall')
        {
            $executionList = array();
            $executionProducts = $this->dao->select('t1.project,t1.product')->from(TABLE_PROJECTPRODUCT)->alias('t1')
                ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
                ->where('project')->in(array_keys($executions))
                ->andWhere('t2.deleted')->eq(0)
                ->fetchPairs();

            $products = $this->loadModel('product')->getProductPairsByProject($projectID);

            foreach($executions as $id => $execution)
            {
                if($execution->grade == 2 and isset($executions[$execution->parent]))
                {
                    $execution->name = $executions[$execution->parent]->name . '/' . $execution->name;
                    $executions[$execution->parent]->children[$id] = $execution;
                    unset($executions[$id]);
                }
            }

            foreach($executions as $id => $execution)
            {
                if(isset($execution->children))
                {
                    foreach($execution->children as $id => $execution)
                    {
                        $execution->name = (isset($executionProducts[$id]) ? $products[$executionProducts[$id]] . '/' : '') . $execution->name;
                        $executionList[$id] = $execution;
                    }
                }
                else
                {
                    $execution->name = (isset($executionProducts[$id]) ? $products[$executionProducts[$id]] . '/' : '') . $execution->name;
                    $executionList[$id] = $execution;
                }
            }
            $executions = $executionList;
        }

        if($pairs)
        {
            $executionPairs = array();
            foreach($executions as $execution) $executionPairs[$execution->id] = $execution->name;
            $executions = $executionPairs;
        }

        return $executions;
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
        if($module == 'testcase' and ($method == 'view' || $method == 'edit' || $method == 'batchedit'))
        {
            $module = 'execution';
            $method = 'testcase';
        }
        if($module == 'testtask' and ($method == 'view' || $method == 'create' || $method == 'edit'))
        {
            $module = 'execution';
            $method = 'testtask';
        }
        if($module == 'build' and ($method == 'edit' || $method= 'view'))
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
        elseif($module == 'execution' and ($method == 'index' or $method == 'all'))
        {
            $link = helper::createLink($module, 'task', "executionID=%s");
        }
        elseif($module == 'bug' and $method == 'create' and $this->app->openApp == 'execution')
        {
            $link = helper::createLink($module, $method, "productID=0&branch=0&extra=executionID=%s");
        }
        elseif(in_array($module, array('bug', 'case', 'testtask', 'testreport')) and $method == 'view')
        {
            $link = helper::createLink('execution', $module, "executionID=%s");
        }
        elseif($module == 'repo')
        {
            $link = helper::createLink('repo', 'browse', "repoID=0&branchID=&executionID=%s");
        }
        else
        {
            $link = helper::createLink($module, $method, "executionID=%s");
        }

        if($module == 'doc') $link = helper::createLink('doc', 'objectLibs', "type=execution&objectID=%s&from=execution");
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
     * @access public
     * @return void
     */
    public function getChildExecutions($executionID)
    {
        return $this->dao->select('id, name')->from(TABLE_EXECUTION)->where('parent')->eq((int)$executionID)->fetchPairs();
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
            ->where('type')->in('sprint,stage')
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
        $list = $this->dao->select('t1.id, t1.name,t1.status, t2.product')->from(TABLE_EXECUTION)->alias('t1')
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

            if(strpos($this->session->taskQuery, "deleted =") === false)   $this->session->set('taskQuery', $this->session->taskQuery . " AND deleted = '0'");

            $taskQuery = $this->session->taskQuery;
            /* Limit current execution when no execution. */
            if(strpos($taskQuery, "`execution` =") === false) $taskQuery = $taskQuery . " AND `execution` = $executionID";
            $executionQuery = "`execution` " . helper::dbIN(array_keys($executions));
            $taskQuery    = str_replace("`execution` = 'all'", $executionQuery, $taskQuery); // Search all execution.
            $this->session->set('taskQueryCondition', $taskQuery, $this->app->openApp);
            $this->session->set('taskOnlyCondition', true, $this->app->openApp);

            $tasks = $this->getSearchTasks($taskQuery, $pager, $sort);
        }

        return $tasks;
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

        $execution->days          = $execution->days ? $execution->days : '';
        $execution->totalHours    = $this->dao->select('sum(days * hours) AS totalHours')->from(TABLE_TEAM)->where('root')->eq($execution->id)->andWhere('type')->eq('execution')->fetch('totalHours');
        $execution->totalEstimate = round($total->totalEstimate, 1);
        $execution->totalConsumed = round($total->totalConsumed, 1);
        $execution->totalLeft     = round($total->totalLeft - $closedTotalLeft, 1);

        $execution = $this->loadModel('file')->replaceImgURL($execution, 'desc');
        if($setImgSize) $execution->desc = $this->file->setImgSize($execution->desc);

        return $execution;
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
     * Get products of a execution.
     *
     * @param  int    $executionID
     * @access public
     * @return array
     */
    public function getProducts($executionID, $withBranch = true)
    {
        if(defined('TUTORIAL'))
        {
            if(!$withBranch) return $this->loadModel('tutorial')->getProductPairs();
            return $this->loadModel('tutorial')->getExecutionProducts();
        }

        $query = $this->dao->select('t2.id, t2.name, t2.type, t1.branch, t1.plan')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')
            ->on('t1.product = t2.id')
            ->where('t1.project')->eq((int)$executionID)
            ->andWhere('t2.deleted')->eq(0)
            ->beginIF(!$this->app->user->admin)->andWhere('t2.id')->in($this->app->user->view->products)->fi();
        if(!$withBranch) return $query->fetchPairs('id', 'name');
        return $query->fetchAll('id');
    }

    /**
     * Get ordered executions.
     *
     * @param  int    $executionID
     * @param  string $status
     * @param  int    $num
     * @access public
     * @return array
     */
    public function getOrderedExecutions($executionID, $status, $num = 0)
    {
        $executionList = $this->getList($executionID, 'all', $status);
        if(empty($executionIdList)) return $executionList;

        $executions = $mineExecutions = $otherExecutions = $closedExecutions = array();
        foreach($executionList as $execution)
        {
            if(!$this->app->user->admin and !$this->checkPriv($execution->id)) continue;
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
     * @access public
     * @return void
     */
    public function buildStorySearchForm($products, $branchGroups, $modules, $queryID, $actionURL, $type = 'executionStory')
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
        if($type == 'executionStory')
        {
            $this->config->product->search['module'] = 'executionStory';
        }

        $this->config->product->search['fields']['title'] = str_replace($this->lang->SRCommon, $this->lang->SRCommon, $this->lang->story->title);
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
        }
        $this->config->product->search['params']['status'] = array('operator' => '=', 'control' => 'select', 'values' => $this->lang->story->statusList);

        $this->loadModel('search')->setSearchParams($this->config->product->search);
    }

    /**
     * Get executions to import
     *
     * @param  array  $executionIds
     * @param  string $type sprint|stage
     * @access public
     * @return array
     */
    public function getToImport($executionIds, $type)
    {
        $executions = $this->dao->select('*')->from(TABLE_EXECUTION)
            ->where('id')->in($executionIds)
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->sprints)->fi()
            ->andWhere('type')->eq($type)
            ->andWhere('deleted')->eq(0)
            ->orderBy('id desc')
            ->fetchAll('id');

        $pairs = array();
        $now   = date('Y-m-d');
        foreach($executions as $id => $execution) $pairs[$id] = $execution->name;
        return $pairs;
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
        $products = isset($_POST['products']) ? $_POST['products'] : $products;
        $oldExecutionProducts = $this->dao->select('*')->from(TABLE_PROJECTPRODUCT)->where('project')->eq((int)$executionID)->fetchGroup('product', 'branch');
        $this->dao->delete()->from(TABLE_PROJECTPRODUCT)->where('project')->eq((int)$executionID)->exec();
        $members = array_keys($this->getTeamMembers($executionID));
        if(empty($products))
        {
            $this->user->updateUserView(array_keys($oldExecutionProducts), 'product', $members);
            return true;
        }

        $branches = isset($_POST['branch']) ? $_POST['branch'] : array();
        $plans    = isset($_POST['plans']) ? $_POST['plans'] : array();;

        $existedProducts = array();
        foreach($products as $i => $productID)
        {
            if(empty($productID)) continue;
            if(isset($existedProducts[$productID])) continue;

            $oldPlan = 0;
            $branch  = isset($branches[$i]) ? $branches[$i] : 0;
            if(isset($oldExecutionProducts[$productID][$branch]))
            {
                $oldExecutionProduct = $oldExecutionProducts[$productID][$branch];
                $oldPlan           = $oldExecutionProduct->plan;
            }

            $data = new stdclass();
            $data->project = $executionID;
            $data->product = $productID;
            $data->branch  = $branch;
            $data->plan    = isset($plans[$productID]) ? $plans[$productID] : $oldPlan;
            $this->dao->insert(TABLE_PROJECTPRODUCT)->data($data)->exec();
            $existedProducts[$productID] = true;
        }

        $oldProductKeys = array_keys($oldExecutionProducts);
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
        $products = $this->getProducts($toExecution);
        if(empty($products)) return array();

        $execution  = $this->getById($toExecution);
        $executions = $this->dao->select('t1.product, t1.project')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.project=t2.id')
            ->where('t1.product')->in(array_keys($products))
            ->andWhere('t2.project')->eq($execution->project)
            ->fetchGroup('project');
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

        $tasks = $this->dao->select('id,execution,assignedTo,story,consumed,status')->from(TABLE_TASK)->where('id')->in($this->post->tasks)->fetchAll('id');
        foreach($tasks as $task)
        {
            /* Save the assignedToes and stories, should linked to execution. */
            $assignedToes[$task->assignedTo] = $task->execution;
            $stories[$task->story]           = $task->story;

            $data = new stdclass();
            $data->execution = $executionID;
            $data->status  = $task->consumed > 0 ? 'doing' : 'wait';

            if($task->status == 'cancel')
            {
                $data->canceledBy   = '';
                $data->canceledDate = null;
            }

            /* Update tasks. */
            $this->dao->update(TABLE_TASK)->data($data)->where('id')->eq($task->id)->exec();
            unset($data->status);
            $this->dao->update(TABLE_TASK)->data($data)->where('parent')->eq($task->id)->exec();

            $this->loadModel('action')->create('task', $task->id, 'moved', '', $task->execution);
        }

        /* Remove empty story. */
        unset($stories[0]);

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
        $lastOrder      = (int)$this->dao->select('*')->from(TABLE_PROJECTSTORY)->where('project')->eq($executionID)->orderBy('order_desc')->limit(1)->fetch('order');
        foreach($stories as $storyID)
        {
            if(!isset($executionStories[$storyID]))
            {
                $story = $this->dao->findById($storyID)->fields("$executionID as project, id as story, product, version")->from(TABLE_STORY)->fetch();
                $story->order = ++$lastOrder;
                $this->dao->insert(TABLE_PROJECTSTORY)->data($story)->exec();
                $this->action->create('story', $storyID, 'linked2execution', '', $executionID);
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

        $execution        = $this->getByID($executionID);
        $requiredFields = ',' . $this->config->task->create->requiredFields . ',';
        if($execution->type == 'ops') $requiredFields = str_replace(',story,', ',', $requiredFields);
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
            $task->deadline     = $bugToTasks->deadline[$key];
            $task->estimate     = $bugToTasks->estimate[$key];
            $task->consumed     = 0;
            $task->assignedTo   = '';
            $task->status       = 'wait';
            $task->openedDate   = $now;
            $task->openedBy     = $this->app->user->account;

            if($task->estimate !== '') $task->left = $task->estimate;
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
     * Link story.
     *
     * @param int   $executionID
     * @param array $stories
     * @param array $products
     *
     * @access public
     * @return bool
     */
    public function linkStory($executionID, $stories = array(), $products = array())
    {
        if(empty($stories)) $stories = $this->post->stories;
        if(empty($stories)) return false;
        if(empty($products)) $products = $this->post->products;

        $this->loadModel('action');
        $versions      = $this->loadModel('story')->getVersions($stories);
        $linkedStories = $this->dao->select('*')->from(TABLE_PROJECTSTORY)->where('project')->eq($executionID)->orderBy('order_desc')->fetchPairs('story', 'order');
        $lastOrder     = reset($linkedStories);
        $statusPairs   = $this->dao->select('id, status')->from(TABLE_STORY)->where('id')->in(array_values($stories))->fetchPairs();
        foreach($stories as $key => $storyID)
        {
            if($statusPairs[$storyID] == 'draft' || $statusPairs[$storyID] == 'closed') continue;
            if(isset($linkedStories[$storyID])) continue;

            $data = new stdclass();
            $data->project = $executionID;
            $data->product = (int)$products[$storyID];
            $data->story   = $storyID;
            $data->version = $versions[$storyID];
            $data->order   = ++$lastOrder;
            $this->dao->insert(TABLE_PROJECTSTORY)->data($data)->exec();

            $this->story->setStage($storyID);
            $this->linkCases($executionID, (int)$products[$storyID], $storyID);

            $action = $executionID == $this->session->project ? 'linked2project' : 'linked2execution';
            $this->action->create('story', $storyID, $action, '', $executionID);
        }
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
        $cases         = $this->dao->select('id, version')->from(TABLE_CASE)->where('story')->eq($storyID)->fetchParis('id');
        foreach($cases as $caseID => $case)
        {
            if(isset($linkedCases[$caseID])) continue;

            $object = new stdclass();
            $object->project = $executionID;
            $object->product = $productID;
            $object->case    = $caseID;
            $object->version = $case->version;
            $object->order   = ++$lastCaseOrder;

            $this->dao->insert(TABLE_PROJECTCASE)->data($object)->exec();

            $action = $executionID == $this->session->project ? 'linked2project' : 'linked2execution';

            $this->action->create('case', $caseID, $action, '', $executionID);
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
        $plans = $this->dao->select('plan,product')->from(TABLE_PROJECTPRODUCT)
            ->where('project')->eq($executionID)
            ->fetchPairs('plan', 'product');

        $planStories  = array();
        $planProducts = array();
        $count        = 0;
        $this->loadModel('story');
        if(!empty($plans))
        {
            foreach($plans as $planID => $productID)
            {
                if(empty($planID)) continue;
                $planStory = $this->story->getPlanStories($planID);
                if(!empty($planStory))
                {
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

        $projectID = $this->dao->select('project')->from(TABLE_EXECUTION)->where('id')->eq($executionID)->fetch('project');
        $this->linkStory($executionID, $planStories, $planProducts);
        $this->linkStory($projectID, $planStories, $planProducts);
        if($count != 0) echo js::alert(sprintf($this->lang->execution->haveDraft, $count)) . js::locate(helper::createLink('execution', 'create', "projectID=$projectID&executionID=$executionID"));
    }

    /**
     * Unlink story.
     *
     * @param  int    $executionID
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function unlinkStory($executionID, $storyID)
    {
        $execution = $this->dao->findById($executionID)->from(TABLE_EXECUTION)->fetch();
        if($execution->type == 'project')
        {
            $executions       = $this->dao->select('*')->from(TABLE_EXECUTION)->where('parent')->eq($executionID)->fetchAll('id');
            $executionStories = $this->dao->select('project,story')->from(TABLE_PROJECTSTORY)->where('story')->eq($storyID)->andWhere('project')->in(array_keys($executions))->fetchAll();
            if(!empty($executionStories)) die(js::alert($this->lang->execution->notAllowedUnlinkStory));
        }
        $this->dao->delete()->from(TABLE_PROJECTSTORY)->where('project')->eq($executionID)->andWhere('story')->eq($storyID)->limit(1)->exec();

        $order   = 1;
        $stories = $this->dao->select('*')->from(TABLE_PROJECTSTORY)->where('project')->eq($executionID)->orderBy('order')->fetchAll();
        foreach($stories as $executionstory)
        {
            if($executionstory->order != $order) $this->dao->update(TABLE_PROJECTSTORY)->set('`order`')->eq($order)->where('project')->eq($executionID)->andWhere('story')->eq($executionstory->story)->exec();
            $order++;
        }

        $this->loadModel('story')->setStage($storyID);
        $this->unlinkCases($executionID, $storyID);
        $objectType = $executionID == $this->session->project ? 'unlinkedfromproject' : 'unlinkedfromexecution';
        $this->loadModel('action')->create('story', $storyID, $objectType, '', $executionID);

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
        $cases = $this->dao->select('id')->from(TABLE_CASE)->where('story')->eq($storyID)->fetchAll('id');
        foreach($cases as $caseID => $case)
        {
            $this->dao->delete()->from(TABLE_PROJECTCASE)->where('project')->eq($executionID)->andWhere('`case`')->eq($caseID)->limit(1)->exec();
            $action = $executionID == $this->session->project ? 'unlinkedfromproject' : 'unlinkedfromexecution';
            $this->action->create('case', $caseID, $action, '', $executionID);
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
            ->fetchAll('account');
    }

    /**
     * Get the skip members of the team.
     *
     * @param  array  $teams
     * @param  string $begin
     * @param  string $end
     * @access public
     * @return array
     */
    public function getTeamSkip($teams, $begin, $end)
    {
        $members = array();
        foreach($teams as $account => $team)
        {
            if($account == $end) break;
            if(!empty($begin) and $account != $begin and empty($members)) continue;

            $members[$account] = $team;
        }

        return $members;
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
            ->andWhere('type')->eq('execution')
            ->andWhere('account')->notIN($currentMembers)
            ->fetchAll('account');
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
        $this->addProjectMembers($execution->project, $executionMember);
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

        $this->loadModel('user')->updateUserView($projectID, $projectType, $changedAccounts);
        $linkedProducts = $this->loadModel('product')->getProductPairsByProject($projectID);
        if(!empty($linkedProducts)) $this->user->updateUserView(array_keys($linkedProducts), 'product', $changedAccounts);
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
        $type   = ($sprint->type == 'stage' || $sprint->type == 'sprint') ? 'execution' : $sprint->type;

        $this->dao->delete()->from(TABLE_TEAM)->where('root')->eq((int)$sprintID)->andWhere('type')->eq($type)->andWhere('account')->eq($account)->exec();
        $this->updateUserView($sprintID, 'sprint', array($account));

        /* Remove team members from the sprint or stage, and determine whether to remove team members from the execution. */
        if($sprint->type == 'stage' || $sprint->type == 'sprint')
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
     * @access public
     * @return array
     */
    public function computeBurn()
    {
        $today = helper::today();
        $burns = array();

        $executions = $this->dao->select('id, code')->from(TABLE_EXECUTION)
            ->where("end")->ge($today)
            ->andWhere('type')->ne('ops')
            ->andWhere('status')->notin('done,closed,suspended')
            ->fetchPairs();
        if(!$executions) return $burns;

        $burns = $this->dao->select("execution, '$today' AS date, sum(estimate) AS `estimate`, sum(`left`) AS `left`, SUM(consumed) AS `consumed`")
            ->from(TABLE_TASK)
            ->where('execution')->in(array_keys($executions))
            ->andWhere('deleted')->eq('0')
            ->andWhere('parent')->ge('0')
            ->andWhere('status')->ne('cancel')
            ->groupBy('execution')
            ->fetchAll('execution');
        $closedLefts = $this->dao->select("execution, sum(`left`) AS `left`")->from(TABLE_TASK)
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
            ->andWhere('status')->eq('done')
            ->orWhere('status')->eq('closed')
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

            if(isset($storyPoints[$executionID])) $burn->storyPoint = $storyPoints[$executionID]->storyPoint;

            $this->dao->replace(TABLE_BURN)->data($burn)->exec();
            $burn->executionName = $executions[$burn->execution];
        }
        return $burns;
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
        $burn     = $this->dao->select('*')->from(TABLE_BURN)->where('execution')->eq($executionID)->andWhere('task')->eq(0)->andWhere('date')->eq($execution->begin)->fetch();
        $withLeft = $this->post->withLeft ? $this->post->withLeft : 0;

        $data = fixer::input('post')
            ->add('execution', $executionID)
            ->add('date', $execution->begin)
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
     * @param  int    $executionID
     * @param  string $burnBy
     * @access public
     * @return array
     */
    public function getBurnDataFlot($executionID = 0, $burnBy = '')
    {
        /* Get execution and burn counts. */
        $execution    = $this->getById($executionID);

        /* If the burnCounts > $itemCounts, get the latest $itemCounts records. */
        $sets = $this->dao->select("date AS name, `$burnBy` AS value, `$burnBy`")->from(TABLE_BURN)->where('execution')->eq((int)$executionID)->andWhere('task')->eq(0)->orderBy('date DESC')->fetchAll('name');

        $count    = 0;
        $burnData = array();
        foreach($sets as $date => $set)
        {
            if($date < $execution->begin) continue;
            if($date > $execution->end) continue;

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
             ->fetchAll('id');

        if(empty($tasks)) return array();

        $taskTeam = $this->dao->select('*')->from(TABLE_TEAM)->where('root')->in(array_keys($tasks))->andWhere('type')->eq('task')->fetchGroup('root');
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
            ->fetchAll();
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
            if(!isset($tasks[$task->parent]) or $task->parent <= 0)
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
     * @access public
     * @return bool
     */
    public static function isClickable($execution, $action)
    {
        $action = strtolower($action);

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
     * @access public
     * @return array
     */
    public function getDateList($begin, $end, $type, $interval = '', $format = 'm/d/Y')
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
        $estimate = $this->dao->select('SUM(estimate) as estimate')->from(TABLE_TASK)->where('deleted')->eq('0')->andWhere('execution')->eq($executionID)->fetch('estimate');
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
                if($product->branch and isset($branchGroups[$product->id][$product->branch]))
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
     * @access public
     * @return void
     */
    public function buildTaskSearchForm($executionID, $executions, $queryID, $actionURL)
    {
        $this->config->execution->search['actionURL'] = $actionURL;
        $this->config->execution->search['queryID']   = $queryID;
        $this->config->execution->search['params']['execution']['values'] = array(''=>'', $executionID => $executions[$executionID], 'all' => $this->lang->execution->allExecutions);

        $showAllModule = isset($this->config->execution->task->allModule) ? $this->config->execution->task->allModule : '';
        $this->config->execution->search['params']['module']['values']  = $this->loadModel('tree')->getTaskOptionMenu($executionID, 0, 0, $showAllModule ? 'allModule' : '');

        $this->loadModel('search')->setSearchParams($this->config->execution->search);
    }

    /**
     * Get Kanban tasks
     *
     * @param  int    $executionID
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return void
     */
    public function getKanbanTasks($executionID, $orderBy = 'status_asc, id_desc', $pager = null)
    {
        $tasks = $this->dao->select('t1.*, t2.id AS storyID, t2.title AS storyTitle, t2.version AS latestStoryVersion, t2.status AS storyStatus, t3.realname AS assignedToRealName')
            ->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->leftJoin(TABLE_USER)->alias('t3')->on('t1.assignedTo = t3.account')
            ->where('t1.execution')->eq((int)$executionID)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t1.parent')->ge(0)
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
     * @access public
     * @return array
     */
    public function buildBurnData($executionID, $dateList, $type, $burnBy = 'left')
    {
        $this->loadModel('report');
        $burnBy = $burnBy ? $burnBy : 'left';

        $sets          = $this->getBurnDataFlot($executionID, $burnBy);
        $limitJSON     = '[]';
        $baselineJSON  = '[]';

        $firstBurn    = empty($sets) ? 0 : reset($sets);
        $firstTime    = !empty($firstBurn->$burnBy) ? $firstBurn->$burnBy : (!empty($firstBurn->value) ? $firstBurn->value : 0);
        $days         = count($dateList) - 1;
        $rate         = $days ? $firstTime / $days : '';
        $baselineJSON = '[';
        foreach($dateList as $i => $date) $baselineJSON .= round(($days - $i) * (int)$rate, 1) . ',';
        $baselineJSON = rtrim($baselineJSON, ',') . ']';

        $chartData['labels']   = $this->report->convertFormat($dateList, DT_DATE5);
        $chartData['burnLine'] = $this->report->createSingleJSON($sets, $dateList);
        $chartData['baseLine'] = $baselineJSON;

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
            $stories = $this->dao->select('t2.*, t1.version as taskVersion')->from(TABLE_PROJECTSTORY)->alias('t1')
                ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
                ->where('t1.project')->eq((int)$executionID)
                ->andWhere('t2.deleted')->eq(0)
                ->orderBy('t1.`order`_desc')
                ->fetchAll();
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
                $storyItem->openedBy      = $users[$story->openedBy];
                $storyItem->assignedTo    = zget($users, $story->assignedTo);
                $storyItem->url           = helper::createLink('story', 'view', "storyID=$story->id&version=$story->version&from=execution&param=$executionID");
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
                    $html .= "<a class='tree-toggle'><span class='title' title='{$tree->name}'>" . $tree->name . '</span></a>';
                    break;
                case 'task':
                    $link = helper::createLink('execution', 'treeTask', "taskID={$tree->id}");
                    $html .= '<li class="item-task">';
                    $html .= '<a class="tree-link" href="' . $link . '"><span class="label label-type">' . ($tree->parent > 0 ? $this->lang->task->children : $this->lang->task->common) . "</span><span class='title' title='{$tree->title}'>" . $tree->title . '</span> <span class="user"><i class="icon icon-person"></i> ' . (empty($tree->assignedTo) ? $tree->openedBy : $tree->assignedTo) . '</span><span class="label label-id">' . $tree->id . '</span></a>';
                    break;
                case 'product':
                    $this->app->loadLang('product');
                    $html .= '<li class="item-product">';
                    $html .= '<a class="tree-toggle"><span class="label label-type">' . $this->lang->productCommon . "</span><span class='title' title='{$tree->name}'>" . $tree->name . '</span></a>';
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
                $html .= $this->printTree($tree->children);
                $html .= '</ul>';
            }
            $html .= '</li>';
        }
        return $html;
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
        $products = $this->getProducts($executionID, $withBranch = false);
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
     ** Set stage tree path.
     **
     ** @param  int    $executionID
     ** @access public
     ** @return bool
     **/
    public function setTreePath($executionID)
    {
        $execution = $this->dao->select('id,type,parent,path,grade')->from(TABLE_PROJECT)->where('id')->eq($executionID)->fetch();
        $parent    = $this->dao->select('id,type,parent,path,grade')->from(TABLE_PROJECT)->where('id')->eq($execution->parent)->fetch();

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
}
