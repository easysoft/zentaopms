<?php
class projectModel extends model
{
    /**
     * Save project state.
     *
     * @param  int    $projectID
     * @param  array  $projects
     * @access public
     * @return int
     */
    public function savePGMState($projectID = 0, $projects = array())
    {
        if($projectID > 0) $this->session->set('PGM', (int)$projectID);
        if($projectID == 0 and $this->cookie->lastPGM) $this->session->set('PGM', (int)$this->cookie->lastPGM);
        if($projectID == 0 and $this->session->PGM == '') $this->session->set('PGM', key($projects));
        if(!isset($projects[$this->session->PGM]))
        {
            $this->session->set('PGM', key($projects));
            if($projectID && strpos(",{$this->app->user->view->projects},", ",{$this->session->PGM},") === false) $this->accessDenied();
        }

        return $this->session->PGM;
    }

    /**
     * Get project main menu action.
     *
     * @access public
     * @return string
     */
    public function getPGMMainAction()
    {
        $link = html::a(helper::createLink('project', 'pgmbrowse'), "<i class='icon icon-list'></i>", '', "style='border: none;'");
        $html = "<p style='padding-top:5px;'>" . $link . "</p>";
        return common::hasPriv('project', 'pgmbrowse') ? $html : '';
    }

    /**
     * Get project pairs.
     *
     * @param  bool   $isQueryAll
     * @access public
     * @return array
     */
    public function getPGMPairs($isQueryAll = false)
    {
        return $this->dao->select('id, name')->from(TABLE_PROGRAM)
            ->where('type')->eq('project')
            ->andWhere('deleted')->eq(0)
            ->beginIF(!$this->app->user->admin and !$isQueryAll)->andWhere('id')->in($this->app->user->view->projects)->fi()
            ->fetchPairs();
    }

    /**
     * Get the product associated with the project.
     *
     * @param  int     $projectID
     * @param  string  $mode       all|assign
     * @param  string  $status     all|noclosed
     * @access public
     * @return array
     */
    public function getPGMProductPairs($projectID = 0, $mode = 'assign', $status = 'all')
    {
        /* Get the top projectID. */
        if($projectID)
        {
            $project   = $this->getPGMByID($projectID);
            $path      = explode(',', $project->path);
            $path      = array_filter($path);
            $projectID = current($path);
        }

        /* When mode equals assign and projectID equals 0, you can query the standalone product. */
        $products = $this->dao->select('*')->from(TABLE_PRODUCT)
            ->where('deleted')->eq(0)
            ->beginIF($mode == 'assign')->andWhere('project')->eq($projectID)->fi()
            ->beginIF(strpos($status, 'noclosed') !== false)->andWhere('status')->ne('closed')->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->products)->fi()
            ->fetchPairs('id', 'name');
        return $products;
    }

    /**
     * Get project by id.
     *
     * @param  int  $projectID
     * @access public
     * @return array
     */
    public function getPGMByID($projectID = 0)
    {
        return $this->dao->select('*')->from(TABLE_PROGRAM)->where('id')->eq($projectID)->andWhere('`type`')->eq('project')->fetch();
    }

    /**
     * Get project list.
     *
     * @param  string $status
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getPGMList($status = 'all', $orderBy = 'id_asc', $pager = NULL)
    {
        return $this->dao->select('*')->from(TABLE_PROGRAM)
            ->where('type')->in('project,project')
            ->andWhere('deleted')->eq(0)
            ->beginIF(!$this->app->user->admin)
            ->andWhere('id')->in($this->app->user->view->projects)
            ->orWhere('id')->in($this->app->user->view->projects)
            ->fi()
            ->beginIF($status != 'all')->andWhere('status')->eq($status)->fi()
            ->beginIF(!$this->cookie->showClosed)->andWhere('status')->ne('closed')->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Set view menu.
     *
     * @param  int    $projectID
     * @access private
     * @return void
     */
    public function setPGMViewMenu($projectID = 0)
    {
        foreach($this->lang->project->viewMenu as $label => $menu)
        {
            $this->lang->project->viewMenu->{$label}['link'] = is_array($menu) ? sprintf($menu['link'], $projectID) : sprintf($menu, $projectID);
        }

        foreach($this->lang->personnel->menu as $label => $menu)
        {
            $menu['link'] = is_array($menu) ? sprintf($menu['link'], $projectID) : sprintf($menu, $projectID);
            $this->lang->personnel->menu->$label = $menu;
        }

        $this->lang->project->menu = $this->lang->project->viewMenu;
    }

    /**
     * Create a project.
     *
     * @access private
     * @return int|bool
     */
    public function PGMCreate()
    {
        $project = fixer::input('post')
            ->setDefault('status', 'wait')
            ->setDefault('openedBy', $this->app->user->account)
            ->setDefault('parent', 0)
            ->setDefault('openedDate', helper::now())
            ->setIF($this->post->acl == 'open', 'whitelist', '')
            ->setIF($this->post->delta == 999, 'end', LONG_TIME)
            ->setIF($this->post->budget != 0, 'budget', round($this->post->budget, 2))
            ->add('type', 'project')
            ->join('whitelist', ',')
            ->stripTags($this->config->project->editor->pgmcreate['id'], $this->config->allowedTags)
            ->remove('delta,future')
            ->get();

        if($project->parent)
        {
            $parentProject = $this->dao->select('*')->from(TABLE_PROGRAM)->where('id')->eq($project->parent)->fetch();
            if($parentProject)
            {
                /* Child project begin cannot less than parent. */
                if($project->begin < $parentProject->begin) dao::$errors['begin'] = sprintf($this->lang->project->PGMBeginLetterParent, $parentProject->begin);

                /* When parent set end then child project end cannot greater than parent. */
                if($parentProject->end != '0000-00-00' and $project->end > $parentProject->end) dao::$errors['end'] = sprintf($this->lang->project->PGMEndGreaterParent, $parentProject->end);

                /* When parent set end then child project cannot set longTime. */
                if(empty($project->end) and $this->post->delta == 999 and $parentProject->end != '0000-00-00') dao::$errors['end'] = sprintf($this->lang->project->PGMEndGreaterParent, $parentProject->end);

                /* The budget of a child project cannot beyond the remaining budget of the parent project. */
                $project->budgetUnit = $parentProject->budgetUnit;
                if(isset($project->budget) and $parentProject->budget != 0)
                {
                    $availableBudget = $this->getAvailableBudget($parentProject);
                    if($project->budget > $availableBudget) dao::$errors['budget'] = $this->lang->project->beyondParentBudget;
                }

                if(dao::isError()) return false;
            }
        }

        /* Redefines the language entries for the fields in the project table. */
        foreach(explode(',', $this->config->project->PGMCreate->requiredFields) as $fieldName)
        {
            $fieldKey = 'PGM' . ucfirst($fieldName);
            if(isset($this->lang->project->$fieldKey)) $this->lang->project->$fieldName = $this->lang->project->$fieldKey;
        }

        $project = $this->loadModel('file')->processImgURL($project, $this->config->project->editor->pgmcreate['id'], $this->post->uid);
        $this->dao->insert(TABLE_PROGRAM)->data($project)
            ->autoCheck()
            ->batchcheck($this->config->project->PGMCreate->requiredFields, 'notempty')
            ->checkIF($project->begin != '', 'begin', 'date')
            ->checkIF($project->end != '', 'end', 'date')
            ->checkIF($project->end != '', 'end', 'gt', $project->begin)
            ->checkIF(!empty($project->name), 'name', 'unique')
            ->exec();

        if(!dao::isError())
        {
            $projectID = $this->dao->lastInsertId();
            $this->dao->update(TABLE_PROGRAM)->set('`order`')->eq($projectID * 5)->where('id')->eq($projectID)->exec(); // Save order.

            $whitelist = explode(',', $project->whitelist);
            $this->loadModel('personnel')->updateWhitelist($whitelist, 'project', $projectID);
            if($project->acl != 'open') $this->loadModel('user')->updateUserView($projectID, 'project');

            $this->file->updateObjectID($this->post->uid, $projectID, 'project');
            $this->setTreePath($projectID);

            /* Add project admin.*/
            $groupPriv = $this->dao->select('t1.*')->from(TABLE_USERGROUP)->alias('t1')
                ->leftJoin(TABLE_GROUP)->alias('t2')->on('t1.group = t2.id')
                ->where('t1.account')->eq($this->app->user->account)
                ->andWhere('t2.role')->eq('PRJAdmin')
                ->fetch();

            if(!empty($groupPriv))
            {
                $newProject = $groupPriv->PRJ . ",$projectID";
                $this->dao->update(TABLE_USERGROUP)->set('PRJ')->eq($newProject)->where('account')->eq($groupPriv->account)->andWhere('`group`')->eq($groupPriv->group)->exec();
            }
            else
            {
                $PRJAdminID = $this->dao->select('id')->from(TABLE_GROUP)->where('role')->eq('PRJAdmin')->fetch('id');
                $groupPriv  = new stdclass();
                $groupPriv->account = $this->app->user->account;
                $groupPriv->group   = $PRJAdminID;
                $groupPriv->PRJ     = $projectID;
                $this->dao->insert(TABLE_USERGROUP)->data($groupPriv)->exec();
            }

            return $projectID;
        }
    }

    /**
     * Update project.
     *
     * @param  int    $projectID
     * @access public
     * @return array|bool
     */
    public function PGMUpdate($projectID)
    {
        $projectID  = (int)$projectID;
        $oldProject = $this->dao->findById($projectID)->from(TABLE_PROGRAM)->fetch();

        $project = fixer::input('post')
            ->setDefault('team', $this->post->name)
            ->setDefault('end', '')
            ->setIF($this->post->begin == '0000-00-00', 'begin', '')
            ->setIF($this->post->end   == '0000-00-00', 'end', '')
            ->setIF($this->post->acl   == 'open', 'whitelist', '')
            ->setIF($this->post->delta == 999, 'end', LONG_TIME)
            ->setIF($this->post->future, 'budget', 0)
            ->setIF($this->post->budget != 0, 'budget', round($this->post->budget, 2))
            ->join('whitelist', ',')
            ->stripTags($this->config->project->editor->pgmedit['id'], $this->config->allowedTags)
            ->remove('uid,delta,future')
            ->get();

        $project  = $this->loadModel('file')->processImgURL($project, $this->config->project->editor->pgmedit['id'], $this->post->uid);
        $children = $this->getChildren($projectID);

        if($children > 0)
        {
            $minChildBegin = $this->dao->select('min(begin) as minBegin')->from(TABLE_PROGRAM)->where('id')->ne($projectID)->andWhere('deleted')->eq(0)->andWhere('path')->like("%,{$projectID},%")->fetch('minBegin');
            $maxChildEnd   = $this->dao->select('max(end) as maxEnd')->from(TABLE_PROGRAM)->where('id')->ne($projectID)->andWhere('deleted')->eq(0)->andWhere('path')->like("%,{$projectID},%")->andWhere('end')->ne('0000-00-00')->fetch('maxEnd');

            if($minChildBegin and $project->begin > $minChildBegin) dao::$errors['begin'] = sprintf($this->lang->project->PGMBeginGreateChild, $minChildBegin);
            if($maxChildEnd   and $project->end   < $maxChildEnd and $this->post->delta != 999) dao::$errors['end'] = sprintf($this->lang->project->PGMEndLetterChild, $maxChildEnd);
        }

        if($project->parent)
        {
            $parentProject = $this->dao->select('*')->from(TABLE_PROGRAM)->where('id')->eq($project->parent)->fetch();
            if($parentProject)
            {
                if($project->begin < $parentProject->begin) dao::$errors['begin'] = sprintf($this->lang->project->PGMBeginLetterParent, $parentProject->begin);
                if($parentProject->end != '0000-00-00' and $project->end > $parentProject->end) dao::$errors['end'] = sprintf($this->lang->project->PGMEndGreaterParent, $parentProject->end);
                if(empty($project->end) and $this->post->delta == 999 and $parentProject->end != '0000-00-00') dao::$errors['end'] = sprintf($this->lang->project->PGMEndGreaterParent, $parentProject->end);
            }

            /* The budget of a child project cannot beyond the remaining budget of the parent project. */
            $project->budgetUnit = $parentProject->budgetUnit;
            if($project->budget != 0 and $parentProject->budget != 0)
            {
                $availableBudget = $this->getAvailableBudget($parentProject);
                if($project->budget > $availableBudget + $oldProject->budget) dao::$errors['budget'] = $this->lang->project->beyondParentBudget;
            }
        }
        if(dao::isError()) return false;

        /* Redefines the language entries for the fields in the project table. */
        foreach(explode(',', $this->config->project->PGMCreate->requiredFields) as $fieldName)
        {
            $fieldKey = 'PGM' . ucfirst($fieldName);
            if(isset($this->lang->project->$fieldKey)) $this->lang->project->$fieldName = $this->lang->project->$fieldKey;
        }

        $this->dao->update(TABLE_PROGRAM)->data($project)
            ->autoCheck($skipFields = 'begin,end')
            ->batchcheck($this->config->project->PGMEdit->requiredFields, 'notempty')
            ->checkIF($project->begin != '', 'begin', 'date')
            ->checkIF($project->end != '', 'end', 'date')
            ->checkIF($project->end != '', 'end', 'gt', $project->begin)
            ->check('name', 'unique', "id!=$projectID and deleted='0'")
            ->where('id')->eq($projectID)
            ->limit(1)
            ->exec();

        if(!dao::isError())
        {
            $this->file->updateObjectID($this->post->uid, $projectID, 'project');
            $whitelist = explode(',', $project->whitelist);
            $this->loadModel('personnel')->updateWhitelist($whitelist, 'project', $projectID);
            if($project->acl != 'open') $this->loadModel('user')->updateUserView($projectID, 'project');

            if($oldProject->parent != $project->parent) $this->processNode($projectID, $project->parent, $oldProject->path, $oldProject->grade);

            return common::createChanges($oldProject, $project);
        }
    }

    /*
     * Get project swapper.
     *
     * @param  int     $projectID
     * @param  bool    $active
     * @access private
     * @return string
     */
    public function getPGMSwitcher($projectID = 0)
    {
        $currentProjectName = '';
        $currentModule      = $this->app->moduleName;
        $currentMethod      = $this->app->methodName;

        if($projectID)
        {
            setCookie("lastProject", $projectID, $this->config->cookieLife, $this->config->webRoot, '', false, true);
            $currentProject     = $this->getPGMById($projectID);
            $currentProjectName = $currentProject->name;
        }
        else
        {
            $currentProjectName = $this->lang->project->PGMAll;
        }

        $dropMenuLink = helper::createLink('project', 'ajaxGetPGMDropMenu', "objectID=$projectID&module=$currentModule&method=$currentMethod");
        $output  = "<div class='btn-group header-angle-btn' id='swapper'><button data-toggle='dropdown' type='button' class='btn' id='currentItem' title='{$currentProjectName}'><span class='text'><i class='icon icon-folder-open-o'></i> {$currentProjectName}</span> <span class='caret'></span></button><div id='dropMenu' class='dropdown-menu search-list' data-ride='searchList' data-url='$dropMenuLink'>";
        $output .= '<div class="input-control search-box has-icon-left has-icon-right search-example"><input type="search" class="form-control search-input" /><label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label><a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a></div>'; $output .= "</div></div>";

        return $output;
    }

    /**
     * Get the tree menu of project.
     *
     * @param  int    $projectID
     * @param  string $from
     * @param  string $vars
     * @access public
     * @return string
     */
    public function getPGMTreeMenu($projectID = 0, $from = 'project', $vars = '')
    {
        $projectMenu = array();
        $query = $this->dao->select('*')->from(TABLE_PROJECT)
            ->where('deleted')->eq('0')
            ->beginIF($from == 'project')
            ->andWhere('type')->eq('project')
            ->andWhere('id')->in($this->app->user->view->projects)
            ->fi()
            ->beginIF($from == 'product')
            ->andWhere('type')->eq('project')
            ->andWhere('grade')->eq(1)
            ->andWhere('id')->in($this->app->user->view->projects)
            ->fi()
            ->beginIF(!$this->cookie->showClosed)->andWhere('status')->ne('closed')->fi()
            ->orderBy('grade desc, `order`')->get();
        $stmt = $this->dbh->query($query);

        while($project = $stmt->fetch())
        {
            $link = $from == 'project' ? helper::createLink('project', 'pgmproduct', "projectID=$project->id") : helper::createLink('product', 'all', "projectID=$project->id" . $vars);
            $linkHtml = html::a($link, html::icon($this->lang->icons[$project->type], 'icon icon-sm text-muted') . ' ' . $project->name, '', "id='project$project->id' class='text-ellipsis' title=$project->name");

            if(isset($projectMenu[$project->id]) and !empty($projectMenu[$project->id]))
            {
                if(!isset($projectMenu[$project->parent])) $projectMenu[$project->parent] = '';
                $projectMenu[$project->parent] .= "<li>$linkHtml";
                $projectMenu[$project->parent] .= "<ul>".$projectMenu[$project->id]."</ul>\n";
            }
            else
            {
                if(isset($projectMenu[$project->parent]) and !empty($projectMenu[$project->parent]))
                {
                    $projectMenu[$project->parent] .= "<li>$linkHtml\n";
                }
                else
                {
                    $projectMenu[$project->parent] = "<li>$linkHtml\n";
                }
            }
            $projectMenu[$project->parent] .= "</li>\n";
        }

        krsort($projectMenu);
        $projectMenu = array_pop($projectMenu);
        $lastMenu    = "<ul class='tree tree-simple' data-ride='tree' id='projectTree' data-name='tree-project'>{$projectMenu}</ul>\n";

        return $lastMenu;
    }

    /**
     * Get top project pairs.
     *
     * @param  string $model
     * @param  string $mode
     * @access public
     * @return array
     */
    public function getTopPGMPairs($model = '', $mode = '')
    {
        return $this->dao->select('id,name')->from(TABLE_PROGRAM)
            ->where('type')->eq('project')
            ->andWhere('grade')->eq(1)
            ->beginIF(strpos($mode, 'noclosed') !== false)->andWhere('status')->ne('closed')->fi()
            ->andWhere('id')->in($this->app->user->view->projects)
            ->andWhere('deleted')->eq(0)
            ->beginIF($model)->andWhere('model')->eq($model)->fi()
            ->orderBy('`order`')
            ->fetchPairs();
    }

    /**
     * Get top project by id.
     *
     * @param  int    $projectID
     * @access public
     * @return int
     */
    public function getTopPGMByID($projectID)
    {
        if(empty($projectID)) return 0;

        $project = $this->getPGMByID($projectID);
        if(empty($project)) return 0;

        $path = explode(',', trim($project->path, ','));
        return $path[0];
    }

    /**
     * Get Multiple linked products for project.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getMultiLinkedProducts($projectID)
    {
        $linkedProducts      = $this->dao->select('product')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($projectID)->fetchPairs();
        $multiLinkedProducts = $this->dao->select('t3.id,t3.name')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product = t3.id')
            ->where('t1.product')->in($linkedProducts)
            ->andWhere('t1.project')->ne($projectID)
            ->andWhere('t2.type')->eq('project')
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere('t3.deleted')->eq('0')
            ->fetchPairs('id', 'name');

        return $multiLinkedProducts;
    }

    /**
     * Get children by project id.
     *
     * @param  int     $projectID
     * @access public
     * @return int
     */
    public function getChildren($projectID = 0)
    {
        return $this->dao->select('count(*) as count')->from(TABLE_PROGRAM)->where('parent')->eq($projectID)->fetch('count');
    }

    /**
     * Judge whether there is an unclosed projects or projects.
     *
     * @param  object  $project
     * @access public
     * @return int
     */
    public function hasUnfinished($project)
    {
        $unfinished = $this->dao->select("count(IF(id != {$project->id}, true, null)) as count")->from(TABLE_PROJECT)
            ->where('type')->in('project,project')
            ->andWhere('path')->like($project->path . '%')
            ->andWhere('status')->ne('closed')
            ->andWhere('deleted')->eq('0')
            ->fetch('count');

        return $unfinished;
    }

    /**
     * Get stakeholders by project id.
     *
     * @param  int     $projectID
     * @param  string  $orderBy
     * @param  object  $paper
     * @access public
     * @return array
     */
    public function getStakeholders($projectID = 0, $orderBy, $pager = null)
    {
        return $this->dao->select('t2.account,t2.realname,t2.role,t2.qq,t2.mobile,t2.phone,t2.weixin,t2.email,t1.id,t1.type,t1.key')->from(TABLE_STAKEHOLDER)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.user=t2.account')
            ->where('t1.objectID')->eq($projectID)
            ->andWhere('t1.objectType')->eq('project')
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
    }

    /**
     * Get stakeholders by project id list.
     *
     * @param  string $projectIdList
     * @access public
     * @return array
     */
    public function getStakeholdersByPGMList($projectIdList = 0)
    {
        return $this->dao->select('distinct user as account')->from(TABLE_STAKEHOLDER)
            ->where('objectID')->in($projectIdList)
            ->andWhere('objectType')->eq('project')
            ->fetchAll();
    }

    /**
     * Create stakeholder for a project.
     *
     * @param  int     $projectID
     * @access public
     * @return void
     */
    public function createStakeholder($projectID = 0)
    {
        $data = (array)fixer::input('post')->get();

        $accounts = array_unique($data['accounts']);
        $oldJoin  = $this->dao->select('`user`, createdDate')->from(TABLE_STAKEHOLDER)->where('objectID')->eq((int)$projectID)->andWhere('objectType')->eq('project')->fetchPairs();
        $this->dao->delete()->from(TABLE_STAKEHOLDER)->where('objectID')->eq((int)$projectID)->andWhere('objectType')->eq('project')->exec();

        foreach($accounts as $key => $account)
        {
            if(empty($account)) continue;

            $stakeholder = new stdclass();
            $stakeholder->objectID    = $projectID;
            $stakeholder->objectType  = 'project';
            $stakeholder->user        = $account;
            $stakeholder->createdBy   = $this->app->user->account;
            $stakeholder->createdDate = isset($oldJoin[$account]) ? $oldJoin[$account] : helper::today();

            $this->dao->insert(TABLE_STAKEHOLDER)->data($stakeholder)->exec();
        }

        /* If any account changed, update his view. */
        $oldAccounts     = array_keys($oldJoin);
        $changedAccounts = array_diff($accounts, $oldAccounts);
        $changedAccounts = array_merge($changedAccounts, array_diff($oldAccounts, $accounts));
        $changedAccounts = array_unique($changedAccounts);

        $this->loadModel('user')->updateUserView($projectID, 'project', $changedAccounts);

        /* Update children user view. */
        $childPGMList  = $this->dao->select('id')->from(TABLE_PROJECT)->where('path')->like("%,$projectID,%")->andWhere('type')->eq('project')->fetchPairs();
        $childPRJList  = $this->dao->select('id')->from(TABLE_PROJECT)->where('path')->like("%,$projectID,%")->andWhere('type')->eq('project')->fetchPairs();
        $childProducts = $this->dao->select('id')->from(TABLE_PRODUCT)->where('project')->eq($projectID)->fetchPairs();

        if(!empty($childPGMList))  $this->user->updateUserView($childPGMList, 'project', $changedAccounts);
        if(!empty($childPRJList))  $this->user->updateUserView($childPRJList, 'project', $changedAccounts);
        if(!empty($childProducts)) $this->user->updateUserView($childProducts, 'product', $changedAccounts);
    }

    /**
     * Show accessDenied response.
     *
     * @access private
     * @return void
     */
    public function accessDenied()
    {
        echo(js::alert($this->lang->project->accessDenied));

        if(!$this->server->http_referer) die(js::locate(helper::createLink('project', 'prjbrowse')));

        $loginLink = $this->config->requestType == 'GET' ? "?{$this->config->moduleVar}=user&{$this->config->methodVar}=login" : "user{$this->config->requestFix}login";
        if(strpos($this->server->http_referer, $loginLink) !== false) die(js::locate(helper::createLink('project', 'browse')));

        die(js::locate('back'));
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

        if(empty($project)) return true;
        if(!isset($project->type)) return true;

        if($action == 'pgmclose')    return $project->status != 'closed';
        if($action == 'pgmactivate') return $project->status == 'done' or $project->status == 'closed';
        if($action == 'pgmsuspend')  return $project->status == 'wait' or $project->status == 'doing';

        if($action == 'prjstart')    return $project->status == 'wait' or $project->status == 'suspended';
        if($action == 'prjfinish')   return $project->status == 'wait' or $project->status == 'doing';
        if($action == 'prjclose')    return $project->status != 'closed';
        if($action == 'prjsuspend')  return $project->status == 'wait' or $project->status == 'doing';
        if($action == 'prjactivate') return $project->status == 'done' or $project->status == 'closed';

        return true;
    }

    /**
     * Check has content for project.
     *
     * @param  int    $projectID
     * @access public
     * @return bool
     */
    public function checkHasContent($projectID)
    {
        $count  = 0;
        $count += (int)$this->dao->select('count(*) as count')->from(TABLE_PROGRAM)->where('parent')->eq($projectID)->fetch('count');
        $count += (int)$this->dao->select('count(*) as count')->from(TABLE_TASK)->where('PRJ')->eq($projectID)->fetch('count');

        return $count > 0;
    }

    /**
     * Check has children project.
     *
     * @param  int    $projectID
     * @access public
     * @return bool
     */
    public function checkHasChildren($projectID)
    {
        $count = $this->dao->select('count(*) as count')->from(TABLE_PROGRAM)->where('parent')->eq($projectID)->fetch('count');
        return $count > 0;
    }

    /**
     * Set project tree path.
     *
     * @param  int    $projectID
     * @access public
     * @return bool
     */
    public function setTreePath($projectID)
    {
        $project = $this->dao->select('*')->from(TABLE_PROGRAM)->where('id')->eq($projectID)->fetch();

        $path['path']  = ",{$project->id},";
        $path['grade'] = 1;

        if($project->parent)
        {
            $parent = $this->dao->select('*')->from(TABLE_PROGRAM)->where('id')->eq($project->parent)->fetch();

            $path['path']  = $parent->path . "{$project->id},";
            $path['grade'] = $parent->grade + 1;
        }
        $this->dao->update(TABLE_PROGRAM)->set('path')->eq($path['path'])->set('grade')->eq($path['grade'])->where('id')->eq($project->id)->exec();
        return !dao::isError();
    }

    /**
     * Get budget unit list.
     *
     * @access public
     * @return array
     */
    public function getBudgetUnitList()
    {
        $budgetUnitList = array();
        foreach(explode(',', $this->config->project->unitList) as $unit) $budgetUnitList[$unit] = zget($this->lang->project->unitList, $unit, '');

        return $budgetUnitList;
    }

    /**
     * Get available budget.
     *
     * @param  object  $parentProject
     * @access public
     * @return int
     */
    public function getAvailableBudget($parentProject)
    {
        if(empty($parentProject)) return;

        $childGrade     = $parentProject->grade + 1;
        $childSumBudget = $this->dao->select("sum(budget) as sumBudget")->from(TABLE_PROJECT)
            ->where('path')->like("%{$parentProject->id}%")
            ->andWhere('grade')->eq($childGrade)
            ->fetch('sumBudget');

        return (float)$parentProject->budget - (float)$childSumBudget;
    }

    /**
     * Get project parent pairs
     *
     * @param  string $model
     * @access public
     * @return array
     */
    public function getParentPairs($model = '', $mode = '')
    {
        $modules = $this->dao->select('id,name,parent,path,grade')->from(TABLE_PROGRAM)
            ->where('type')->eq('project')
            ->beginIF(strpos($mode, 'noclosed') === false)->andWhere('status')->ne('closed')->fi()
            ->andWhere('deleted')->eq(0)
            ->beginIF($model)->andWhere('model')->eq($model)->fi()
            ->orderBy('grade desc, `order`')
            ->fetchAll('id');

        $treeMenu = array();
        foreach($modules as $module)
        {
            if(strpos($this->app->user->view->projects, $module->id) === false) continue;

            $moduleName    = '/';
            $parentModules = explode(',', $module->path);
            foreach($parentModules as $parentModuleID)
            {
                if(empty($parentModuleID)) continue;
                if(empty($modules[$parentModuleID])) continue;
                $moduleName .= $modules[$parentModuleID]->name . '/';
            }
            $moduleName  = str_replace('|', '&#166;', rtrim($moduleName, '/'));
            $moduleName .= "|$module->id\n";

            if(!isset($treeMenu[$module->parent])) $treeMenu[$module->parent] = '';
            $treeMenu[$module->parent] .= $moduleName;

            if(isset($treeMenu[$module->id]) and !empty($treeMenu[$module->id])) $treeMenu[$module->parent] .= $treeMenu[$module->id];
        }

        ksort($treeMenu);
        $topMenu = array_shift($treeMenu);
        $topMenu = explode("\n", trim($topMenu));
        $lastMenu[] = '/';
        foreach($topMenu as $menu)
        {
            if(strpos($menu, '|') === false) continue;
            list($label, $moduleID) = explode('|', $menu);
            $lastMenu[$moduleID] = str_replace('&#166;', '|', $label);
        }

        return $lastMenu;
    }

    /**
     * Move project node.
     *
     * @param  int       $projectID
     * @param  int       $parentID
     * @param  string    $oldPath
     * @param  int       $oldGrade
     * @access public
     * @return bool
     */
    public function processNode($projectID, $parentID, $oldPath, $oldGrade)
    {
        $parent = $this->dao->select('id,parent,path,grade')->from(TABLE_PROGRAM)->where('id')->eq($parentID)->fetch();

        $childNodes = $this->dao->select('id,parent,path,grade')->from(TABLE_PROGRAM)
            ->where('path')->like("{$oldPath}%")
            ->andWhere('deleted')->eq(0)
            ->orderBy('grade')
            ->fetchAll();

        /* Process child node path and grade field. */
        foreach($childNodes as $childNode)
        {
            $path  = substr($childNode->path, strpos($childNode->path, ",{$projectID},"));
            $grade = $childNode->grade - $oldGrade + 1;
            if($parent)
            {
                $path  = rtrim($parent->path, ',') . $path;
                $grade = $parent->grade + $grade;
            }
            $this->dao->update(TABLE_PROGRAM)->set('path')->eq($path)->set('grade')->eq($grade)->where('id')->eq($childNode->id)->exec();
        }

        return !dao::isError();
    }

    /**
     * Save project state.
     *
     * @param  int    $projectID
     * @param  array  $projects
     * @access public
     * @return int
     */
    public function savePRJState($projectID = 0, $projects = array())
    {
        if($projectID > 0) $this->session->set('PRJ', (int)$projectID);
        if($projectID == 0 and $this->cookie->lastPRJ) $this->session->set('PRJ', (int)$this->cookie->lastPRJ);
        if($projectID == 0 and $this->session->PRJ == '') $this->session->set('PRJ', key($projects));
        if(!isset($projects[$this->session->PRJ]))
        {
            $this->session->set('PRJ', key($projects));
            if($projectID && strpos(",{$this->app->user->view->projects},", ",{$this->session->PRJ},") === false) $this->accessDenied();
        }

        return $this->session->PRJ;
    }

    /*
     * Get project swapper.
     *
     * @param  int     $projectID
     * @param  string  $currentModule
     * @param  string  $currentMethod
     * @access public
     * @return string
     */
    public function getPRJSwitcher($projectID, $currentModule, $currentMethod)
    {
        $this->session->set('moreProjectLink', $this->app->getURI(true));

        $this->loadModel('project');
        $currentProjectName = $this->lang->project->common;
        if($projectID)
        {
            $currentProject     = $this->getPRJById($projectID);
            $currentProjectName = $currentProject->name;
        }

        $dropMenuLink = helper::createLink('project', 'ajaxGetPRJDropMenu', "objectID=$projectID&module=$currentModule&method=$currentMethod");
        $output  = "<div class='btn-group header-angle-btn' id='swapper'><button data-toggle='dropdown' type='button' class='btn' id='currentItem' title='{$currentProjectName}'><span class='text'><i class='icon icon-project'></i> {$currentProjectName}</span> <span class='caret'></span></button><div id='dropMenu' class='dropdown-menu search-list' data-ride='searchList' data-url='$dropMenuLink'>";
        $output .= '<div class="input-control search-box has-icon-left has-icon-right search-example"><input type="search" class="form-control search-input" /><label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label><a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a></div>';
        $output .= "</div></div>";

        return $output;
    }

    /**
     * Get project main menu action.
     *
     * @param  string $module
     * @param  string $method
     * @access public
     * @return string
     */
    public function getPRJMainAction($module, $method)
    {
        $link = html::a(helper::createLink('project', 'prjbrowse'), "<i class='icon icon-list'></i>", '', "style='border: none;'");
        $html = "<p style='padding-top:5px;'>" . $link . "</p>";
        return common::hasPriv('project', 'prjbrowse') ? $html : '';
    }

    /**
     * Get a project by id.
     *
     * @param  int    $projectID
     * @access public
     * @return object
     */
    public function getPRJByID($projectID)
    {
        $project = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($projectID)->andWhere('`type`')->eq('project')->fetch();
        if(!$project) return false;

        if($project->end == '0000-00-00') $project->end = '';
        return $project;
    }

    /**
     * Get project info.
     *
     * @param  string    $status
     * @param  int       $itemCounts
     * @param  string    $orderBy
     * @param  int       $pager
     * @access public
     * @return array
     */
    public function getPRJInfo($status = 'undone', $itemCounts = 30, $orderBy = 'order_desc', $pager = null)
    {
        /* Init vars. */
        $this->loadModel('project');
        $projects = $this->getPRJList(0, $status, 0, $orderBy, $pager);
        if(empty($projects)) return array();

        $projectIdList = array_keys($projects);
        $teams = $this->dao->select('root, count(*) as count')->from(TABLE_TEAM)
            ->where('root')->in($projectIdList)
            ->groupBy('root')
            ->fetchAll('root');

        $estimates = $this->dao->select('PRJ, sum(estimate) as estimate')->from(TABLE_TASK)
            ->where('PRJ')->in($projectIdList)
            ->andWhere('deleted')->eq(0)
            ->andWhere('parent')->lt(1)
            ->groupBy('PRJ')
            ->fetchAll('PRJ');

        $this->app->loadClass('pager', $static = true);
        foreach($projects as $projectID => $project)
        {
            $orderBy = $project->model == 'waterfall' ? 'id_asc' : 'id_desc';
            $pager   = $project->model == 'waterfall' ? null : new pager(0, 1, 1);
            $project->executions = $this->project->getExecutionStats($projectID, 'undone', 0, 0, 30, $orderBy, $pager);
            $project->teamCount  = isset($teams[$projectID]) ? $teams[$projectID]->count : 0;
            $project->estimate   = isset($estimates[$projectID]) ? round($estimates[$projectID]->estimate, 2) : 0;
            $project->parentName = $this->getPRJParentName($project->parent);
        }
        return $projects;
    }

    /**
     * Gets the top-level project name.
     *
     * @param  int       $parentID
     * @access private
     * @return string
     */
    public function getPRJParentName($parentID = 0)
    {
        if($parentID == 0) return '';

        static $parent;
        $parent = $this->dao->select('id,parent,name')->from(TABLE_PROJECT)->where('id')->eq($parentID)->fetch();
        if($parent->parent) $this->getPRJParentName($parent->parent);

        return $parent->name;
    }

    /**
     * Get project overview for block.
     *
     * @param  string     $queryType byId|byStatus
     * @param  string|int $param
     * @param  string     $orderBy
     * @param  int        $limit
     * @access public
     * @return array
     */
    public function getPRJOverview($queryType = 'byStatus', $param = 'all', $orderBy = 'id_desc', $limit = 15)
    {
        $queryType = strtolower($queryType);
        $projects = $this->dao->select('*')->from(TABLE_PROJECT)
            ->where('type')->eq('project')
            ->andWhere('deleted')->eq(0)
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->projects)->fi()
            ->beginIF($queryType == 'bystatus' and $param != 'all')->andWhere('status')->eq($param)->fi()
            ->beginIF($queryType == 'byid')->andWhere('id')->eq($param)->fi()
            ->orderBy($orderBy)
            ->limit($limit)
            ->fetchAll('id');

        if(empty($projects)) return array();
        $projectIdList = array_keys($projects);

        $teams = $this->dao->select('root, count(*) as teams')->from(TABLE_TEAM)->where('root')->in($projectIdList)->groupBy('root')->fetchPairs();

        $hours = $this->dao->select('PRJ,
            cast(sum(consumed) as decimal(10,2)) as consumed,
            cast(sum(estimate) as decimal(10,2)) as estimate')
            ->from(TABLE_TASK)
            ->where('PRJ')->in($projectIdList)
            ->andWhere('deleted')->eq(0)
            ->andWhere('parent')->lt(1)
            ->groupBy('PRJ')
            ->fetchAll('PRJ');

        $leftTasks = $this->dao->select('PRJ, count(*) as tasks')->from(TABLE_TASK)
            ->where('PRJ')->in($projectIdList)
            ->andWhere('deleted')->eq(0)
            ->andWhere('status')->in('wait,doing,pause')
            ->groupBy('PRJ')
            ->fetchPairs();

        foreach($projectIdList as $projectID)
        {
            $productIdList = $this->loadModel('product')->getProductIDByProject($projectID, false);
            $allStories[$projectID]  = $this->product->getTotalStoriesByProduct($productIdList, 'story');
            $doneStories[$projectID] = $this->product->getTotalStoriesByProduct($productIdList, 'story', 'closed');
            $leftStories[$projectID] = $this->product->getTotalStoriesByProduct($productIdList, 'story', 'active');
        }

        $leftBugs = $this->getTotalBugByPRJ($projectIdList, 'active');
        $allBugs  = $this->getTotalBugByPRJ($projectIdList, 'all');
        $doneBugs = $this->getTotalBugByPRJ($projectIdList, 'resolved');

        foreach($projects as $projectID => $project)
        {
            $project->consumed    = isset($hours[$projectID]) ? $hours[$projectID]->consumed : 0;
            $project->estimate    = isset($hours[$projectID]) ? $hours[$projectID]->estimate : 0;
            $project->teamCount   = isset($teams[$projectID]) ? $teams[$projectID] : 0;
            $project->leftTasks   = isset($leftTasks[$projectID]) ? $leftTasks[$projectID] : 0;
            $project->leftBugs    = isset($leftBugs[$projectID])  ? $leftBugs[$projectID]  : 0;
            $project->allBugs     = isset($allBugs[$projectID])   ? $allBugs[$projectID]   : 0;
            $project->doneBugs    = isset($doneBugs[$projectID])  ? $doneBugs[$projectID]  : 0;
            $project->allStories  = $allStories[$projectID];
            $project->doneStories = $doneStories[$projectID];
            $project->leftStories = $leftStories[$projectID];
        }

        return $projects;
    }

    /**
     * Get project workhour info.
     *
     * @param  int    $projectID
     * @access public
     * @return object
     */
    public function getPRJWorkhour($projectID)
    {
        $executions = $this->loadModel('project')->getExecutionPairs($projectID);

        $total = $this->dao->select('
            ROUND(SUM(estimate), 2) AS totalEstimate,
            ROUND(SUM(consumed), 2) AS totalConsumed,
            ROUND(SUM(`left`), 2) AS totalLeft')
            ->from(TABLE_TASK)
            ->where('project')->in(array_keys($executions))
            ->andWhere('deleted')->eq(0)
            ->andWhere('parent')->lt(1)
            ->fetch();
        $closedTotalLeft = $this->dao->select('ROUND(SUM(`left`), 2) AS totalLeft')->from(TABLE_TASK)
            ->where('project')->in(array_keys($executions))
            ->andWhere('deleted')->eq(0)
            ->andWhere('parent')->lt(1)
            ->andWhere('status')->in('closed,cancel')
            ->fetch('totalLeft');

        $workhour = new stdclass();
        $workhour->totalHours    = $this->dao->select('sum(days * hours) AS totalHours')->from(TABLE_TEAM)->where('root')->in(array_keys($executions))->andWhere('type')->eq('project')->fetch('totalHours');
        $workhour->totalEstimate = round($total->totalEstimate, 1);
        $workhour->totalConsumed = round($total->totalConsumed, 1);
        $workhour->totalLeft     = round($total->totalLeft - $closedTotalLeft, 1);

        return $workhour;
    }

    /**
     * Get project stat data .
     *
     * @param  int    $projectID
     * @access public
     * @return object
     */
    public function getPRJStatData($projectID)
    {
        $executions = $this->loadModel('project')->getExecutionPairs($projectID);
        $storyCount = $this->dao->select('count(t2.story) as storyCount')->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PROJECTSTORY)->alias('t2')->on('t1.id = t2.story')
            ->where('t2.project')->eq($projectID)
            ->andWhere('t1.deleted')->eq(0)
            ->fetch('storyCount');

        $taskCount = $this->dao->select('count(id) as taskCount')->from(TABLE_TASK)->where('project')->in(array_keys($executions))->andWhere('deleted')->eq(0)->fetch('taskCount');
        $bugCount  = $this->dao->select('count(id) as bugCount')->from(TABLE_BUG)->where('project')->in(array_keys($executions))->andWhere('deleted')->eq(0)->fetch('bugCount');

        $statData = new stdclass();
        $statData->storyCount = $storyCount;
        $statData->taskCount  = $taskCount;
        $statData->bugCount   = $bugCount;

        return $statData;
    }

    /**
     * Get project pairs.
     *
     * @param  int    $programID
     * @param  status $status    all|wait|doing|suspended|closed|noclosed
     * @access public
     * @return object
     */
    public function getPRJPairs($programID = 0, $status = 'all')
    {
        return $this->dao->select('id, name')->from(TABLE_PROJECT)
            ->where('type')->eq('project')
            ->andWhere('deleted')->eq(0)
            ->beginIF($programID)->andWhere('parent')->eq($programID)->fi()
            ->beginIF($status != 'all' && $status != 'noclosed')->andWhere('status')->eq($status)->fi()
            ->beginIF($status == 'noclosed')->andWhere('status')->ne('closed')->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->projects)->fi()
            ->fetchPairs();
    }

    /**
     * Get project by id list.
     *
     * @param  array    $projectIdList
     * @access public
     * @return object
     */
    public function getPRJByIdList($projectIdList = array())
    {
        return $this->dao->select('*')->from(TABLE_PROJECT)
            ->where('type')->eq('project')
            ->andWhere('deleted')->eq(0)
            ->andWhere('id')->in($projectIdList)
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->projects)->fi()
            ->fetchAll('id');
    }

    /**
     * Get project pairs by id list.
     *
     * @param  array  $projectIdList
     * @access public
     * @return array
     */
    public function getPRJPairsByIdList($projectIdList = array())
    {
        return $this->dao->select('id, name')->from(TABLE_PROJECT)
            ->where('type')->eq('project')
            ->andWhere('deleted')->eq(0)
            ->andWhere('id')->in($projectIdList)
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->projects)->fi()
            ->fetchPairs('id', 'name');
    }

    /**
     * Get associated bugs by project.
     *
     * @param  array  $projectIdList
     * @param  string $status   active|resolved|all
     * @access public
     * @return array
     */
    public function getTotalBugByPRJ($projectIdList, $status)
    {
        return $this->dao->select('PRJ, count(*) as bugs')->from(TABLE_BUG)
            ->where('PRJ')->in($projectIdList)
            ->andWhere('deleted')->eq(0)
            ->beginIF($status != 'all')->andWhere('status')->eq($status)->fi()
            ->groupBy('PRJ')
            ->fetchPairs('PRJ');
    }

    /**
     * Build the query.
     *
     * @param  int    $projectID
     * @param  string $type   list|dropmenu
     * @access public
     * @return object
     */
    public function buildPRJMenuQuery($projectID = 0, $type = 'list')
    {
        $path    = '';
        $project = $this->getPRJByID($projectID);
        if($project) $path = $project->path;

        return $this->dao->select('*')->from(TABLE_PROJECT)
            ->where('deleted')->eq('0')
            ->beginIF($type == 'list')->andWhere('type')->eq('project')->fi()
            ->beginIF($type == 'dropmenu')->andWhere('type')->in('project,project')->fi()
            ->andWhere('status')->ne('closed')
            ->beginIF(!$this->app->user->admin and $type == 'list')->andWhere('id')->in($this->app->user->view->projects)->fi()
            ->beginIF(!$this->app->user->admin and $type == 'dropmenu')->andWhere('id')->in($this->app->user->view->projects . ',' . $this->app->user->view->projects)->fi()
            ->beginIF($projectID > 0)->andWhere('path')->like($path . '%')->fi()
            ->orderBy('grade desc, `order`')
            ->get();
    }

    /**
     * Get project pairs by model and project.
     *
     * @param  string $model all|scrum|waterfall
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getPRJPairsByModel($model = 'all', $projectID = 0)
    {
        return $this->dao->select('id, name')->from(TABLE_PROJECT)
            ->where('type')->eq('project')
            ->beginIF($projectID)->andWhere('parent')->eq($projectID)->fi()
            ->beginIF($model != 'all')->andWhere('model')->eq($model)->fi()
            ->andWhere('deleted')->eq('0')
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->projects)->fi()
            ->orderBy('id_desc')
            ->fetchPairs();
    }

    /**
     * Get the tree menu of project.
     *
     * @param  int       $projectID
     * @param  string    $userFunc
     * @param  int       $param
     * @param  string    $type  list|dropmenu
     * @access public
     * @return string
     */
    public function getPRJTreeMenu($projectID = 0, $userFunc, $param = 0, $type = 'list')
    {
        $projectMenu = array();
        $stmt        = $this->dbh->query($this->buildPRJMenuQuery($projectID, $type));

        while($project = $stmt->fetch())
        {
            $linkHtml = call_user_func($userFunc, $project, $param);

            if(isset($projectMenu[$project->id]) and !empty($projectMenu[$project->id]))
            {
                if(!isset($projectMenu[$project->parent])) $projectMenu[$project->parent] = '';
                $projectMenu[$project->parent] .= "<li>$linkHtml";
                $projectMenu[$project->parent] .= "<ul>".$projectMenu[$project->id]."</ul>\n";
            }
            else
            {
                if(isset($projectMenu[$project->parent]) and !empty($projectMenu[$project->parent]))
                {
                    $projectMenu[$project->parent] .= "<li>$linkHtml\n";
                }
                else
                {
                    $projectMenu[$project->parent] = "<li>$linkHtml\n";
                }
            }
            $projectMenu[$project->parent] .= "</li>\n";
        }

        krsort($projectMenu);
        $projectMenu = array_pop($projectMenu);
        $lastMenu    = "<ul class='tree' data-ride='tree' id='projectTree' data-name='tree-project'>{$projectMenu}</ul>\n";

        return $lastMenu;
    }

    /**
     * Create the manage link.
     *
     * @param  object    $project
     * @access public
     * @return string
     */
    public function createPRJManageLink($project)
    {
        $link = $project->type == 'project' ? helper::createLink('project', 'PRJbrowse', "projectID={$project->id}&status=all") : helper::createLink('project', 'index', "projectID={$project->id}", '', '', $project->id);
        $icon = $project->type == 'project' ? "<i class='icon icon-folder-open-o'></i> " : "<i class='icon icon-project'></i> ";
        return html::a($link, $icon . $project->name, '_self', "id=project{$project->id} title='{$project->name}' class='text-ellipsis'");
    }

    /**
     * Create a project.
     *
     * @access public
     * @return int|bool
     */
    public function PRJCreate()
    {
        $project = fixer::input('post')
            ->setDefault('status', 'wait')
            ->setIF($this->post->delta == 999, 'end', LONG_TIME)
            ->setIF($this->post->delta == 999, 'days', 0)
            ->setIF($this->post->acl   == 'open', 'whitelist', '')
            ->setIF($this->post->budget != 0, 'budget', round($this->post->budget, 2))
            ->setDefault('openedBy', $this->app->user->account)
            ->setDefault('openedDate', helper::now())
            ->setDefault('team', substr($this->post->name, 0, 30))
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', helper::now())
            ->add('type', 'project')
            ->join('whitelist', ',')
            ->stripTags($this->config->project->editor->prjcreate['id'], $this->config->allowedTags)
            ->remove('products,branch,plans,delta,newProduct,productName,future')
            ->get();

        $linkedProductsCount = 0;
        if(isset($_POST['products']))
        {
            foreach($_POST['products'] as $product)
            {
                if(!empty($product)) $linkedProductsCount++;
            }
        }

        $parentProject = new stdClass();
        if($project->parent)
        {
            $parentProject = $this->dao->select('*')->from(TABLE_PROGRAM)->where('id')->eq($project->parent)->fetch();
            if($parentProject)
            {
                /* Child project begin cannot less than parent. */
                if($project->begin < $parentProject->begin) dao::$errors['begin'] = sprintf($this->lang->project->PRJBeginGreateChild, $parentProject->begin);

                /* When parent set end then child project end cannot greater than parent. */
                if($parentProject->end != '0000-00-00' and $project->end > $parentProject->end) dao::$errors['end'] = sprintf($this->lang->project->PRJEndLetterChild, $parentProject->end);

                if(dao::isError()) return false;
            }

            /* The budget of a child project cannot beyond the remaining budget of the parent project. */
            $project->budgetUnit = $parentProject->budgetUnit;
            if(isset($project->budget) and $parentProject->budget != 0)
            {
                $availableBudget = $this->getAvailableBudget($parentProject);
                if($project->budget > $availableBudget) dao::$errors['budget'] = $this->lang->project->beyondParentBudget;
            }

            /* Judge products not empty. */
            if(empty($linkedProductsCount) and !isset($_POST['newProduct']))
            {
                dao::$errors[] = $this->lang->project->productNotEmpty;
                return false;
            }
        }

        /* When select create new product, product name cannot be empty and duplicate. */
        if(isset($_POST['newProduct']))
        {
            if(empty($_POST['productName']))
            {
                $this->app->loadLang('product');
                dao::$errors['productName'] = sprintf($this->lang->error->notempty, $this->lang->product->name);
                return false;
            }
            else
            {
                $existProductName = $this->dao->select('name')->from(TABLE_PRODUCT)->where('name')->eq($_POST['productName'])->fetch('name');
                if(!empty($existProductName))
                {
                    dao::$errors['productName'] = $this->lang->project->existProductName;
                    return false;
                }
            }
        }

        $requiredFields = $this->config->project->PRJCreate->requiredFields;
        if($this->post->delta == 999) $requiredFields = trim(str_replace(',end,', ',', ",{$requiredFields},"), ',');

        /* Redefines the language entries for the fields in the project table. */
        foreach(explode(',', $requiredFields) as $fieldName)
        {
            $fieldKey = 'PRJ' . ucfirst($fieldName);
            if(isset($this->lang->project->$fieldKey)) $this->lang->project->$fieldName = $this->lang->project->$fieldKey;
        }

        $project = $this->loadModel('file')->processImgURL($project, $this->config->project->editor->prjcreate['id'], $this->post->uid);
        $this->dao->insert(TABLE_PROJECT)->data($project)
            ->autoCheck()
            ->batchcheck($requiredFields, 'notempty')
            ->checkIF(!empty($project->name), 'name', 'unique')
            ->exec();

        /* Add the creater to the team. */
        if(!dao::isError())
        {
            $projectID = $this->dao->lastInsertId();

            /* Add the creator to team. */
            $this->app->loadLang('user');
            $member = new stdclass();
            $member->root    = $projectID;
            $member->account = $this->app->user->account;
            $member->role    = $this->lang->user->roleList[$this->app->user->role];
            $member->join    = helper::today();
            $member->type    = 'project';
            $member->hours   = $this->config->project->defaultWorkhours;
            $this->dao->insert(TABLE_TEAM)->data($member)->exec();

            $whitelist = explode(',', $project->whitelist);
            $this->loadModel('personnel')->updateWhitelist($whitelist, 'project', $projectID);
            if($project->acl != 'open') $this->loadModel('user')->updateUserView($projectID, 'project');

            $this->loadModel('project')->updateProducts($projectID);

            if(isset($_POST['newProduct']) || (!$project->parent && empty($linkedProductsCount)))
            {
                /* If parent not empty, link products or create products. */
                $product = new stdclass();
                $product->name         = $this->post->productName ? $this->post->productName : $project->name;
                $product->bind         = $this->post->productName ? 0 : 1;
                $product->project      = $project->parent ? current(array_filter(explode(',', $parentProject->path))) : 0;
                $product->acl          = $project->acl = 'open' ? 'open' : 'private';
                $product->PO           = $project->PM;
                $product->createdBy    = $this->app->user->account;
                $product->createdDate  = helper::now();
                $product->status       = 'normal';

                $this->dao->insert(TABLE_PRODUCT)->data($product)->exec();
                $productID = $this->dao->lastInsertId();
                if($product->acl != 'open') $this->loadModel('user')->updateUserView($productID, 'product');

                $projectProduct = new stdclass();
                $projectProduct->project = $projectID;
                $projectProduct->product = $productID;

                $this->dao->insert(TABLE_PROJECTPRODUCT)->data($projectProduct)->exec();

                /* Create doc lib. */
                $this->app->loadLang('doc');
                $lib = new stdclass();
                $lib->product = $productID;
                $lib->name    = $this->lang->doclib->main['product'];
                $lib->type    = 'product';
                $lib->main    = '1';
                $lib->acl     = 'default';
                $this->dao->insert(TABLE_DOCLIB)->data($lib)->exec();
            }

            /* Save order. */
            $this->dao->update(TABLE_PROJECT)->set('`order`')->eq($projectID * 5)->where('id')->eq($projectID)->exec();
            $this->file->updateObjectID($this->post->uid, $projectID, 'project');
            $this->setTreePath($projectID);

            /* Add project admin. */
            $groupPriv = $this->dao->select('t1.*')->from(TABLE_USERGROUP)->alias('t1')
                ->leftJoin(TABLE_GROUP)->alias('t2')->on('t1.group = t2.id')
                ->where('t1.account')->eq($this->app->user->account)
                ->andWhere('t2.role')->eq('PRJAdmin')
                ->fetch();

            if(!empty($groupPriv))
            {
                $newProject = $groupPriv->PRJ . ",$projectID";
                $this->dao->update(TABLE_USERGROUP)->set('PRJ')->eq($newProject)->where('account')->eq($groupPriv->account)->andWhere('`group`')->eq($groupPriv->group)->exec();
            }
            else
            {
                $PRJAdminID = $this->dao->select('id')->from(TABLE_GROUP)->where('role')->eq('PRJAdmin')->fetch('id');
                $groupPriv  = new stdclass();
                $groupPriv->account = $this->app->user->account;
                $groupPriv->group   = $PRJAdminID;
                $groupPriv->PRJ     = $projectID;
                $this->dao->insert(TABLE_USERGROUP)->data($groupPriv)->exec();
            }

            return $projectID;
        }
    }

    /**
     * Update project.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function PRJUpdate($projectID = 0)
    {
        $oldProject        = $this->dao->findById($projectID)->from(TABLE_PROJECT)->fetch();
        $linkedProducts    = $this->dao->select('product')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($projectID)->fetchPairs();
        $_POST['products'] = isset($_POST['products']) ? $_POST['products'] : $linkedProducts;

        $project = fixer::input('post')
            ->setDefault('team', substr($this->post->name, 0, 30))
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', helper::now())
            ->setIF($this->post->delta == 999, 'end', LONG_TIME)
            ->setIF($this->post->delta == 999, 'days', 0)
            ->setIF($this->post->begin == '0000-00-00', 'begin', '')
            ->setIF($this->post->end   == '0000-00-00', 'end', '')
            ->setIF($this->post->acl   == 'open', 'whitelist', '')
            ->setIF($this->post->future, 'budget', 0)
            ->setIF($this->post->budget != 0, 'budget', round($this->post->budget, 2))
            ->join('whitelist', ',')
            ->stripTags($this->config->project->editor->prjedit['id'], $this->config->allowedTags)
            ->remove('products,branch,plans,delta,future')
            ->get();

        if($project->parent)
        {
            $parentProject = $this->dao->select('*')->from(TABLE_PROGRAM)->where('id')->eq($project->parent)->fetch();

            if($parentProject)
            {
                /* Child project begin cannot less than parent. */
                if($project->begin < $parentProject->begin) dao::$errors['begin'] = sprintf($this->lang->project->PRJBeginGreateChild, $parentProject->begin);

                /* When parent set end then child project end cannot greater than parent. */
                if($parentProject->end != '0000-00-00' and $project->end > $parentProject->end) dao::$errors['end'] = sprintf($this->lang->project->PRJEndLetterChild, $parentProject->end);

                if(dao::isError()) return false;
            }

            /* The budget of a child project cannot beyond the remaining budget of the parent project. */
            $project->budgetUnit = $parentProject->budgetUnit;
            if($project->budget != 0 and $parentProject->budget != 0)
            {
                $availableBudget = $this->getAvailableBudget($parentProject);
                if($project->budget > $availableBudget + $oldProject->budget) dao::$errors['budget'] = $this->lang->project->beyondParentBudget;
            }
        }

        /* Judge products not empty. */
        $linkedProductsCount = 0;
        foreach($_POST['products'] as $product)
        {
            if(!empty($product)) $linkedProductsCount++;
        }
        if(empty($linkedProductsCount))
        {
            dao::$errors[] = $this->lang->project->errorNoProducts;
            return false;
        }

        $project = $this->loadModel('file')->processImgURL($project, $this->config->project->editor->prjedit['id'], $this->post->uid);

        $requiredFields = $this->config->project->PRJEdit->requiredFields;
        if($this->post->delta == 999) $requiredFields = trim(str_replace(',end,', ',', ",{$requiredFields},"), ',');

        /* Redefines the language entries for the fields in the project table. */
        foreach(explode(',', $requiredFields) as $fieldName)
        {
            $fieldKey = 'PRJ' . ucfirst($fieldName);
            if(isset($this->lang->project->$fieldKey)) $this->lang->project->$fieldName = $this->lang->project->$fieldKey;
        }

        $this->dao->update(TABLE_PROJECT)->data($project)
            ->autoCheck($skipFields = 'begin,end')
            ->batchcheck($requiredFields, 'notempty')
            ->checkIF($project->begin != '', 'begin', 'date')
            ->checkIF($project->end != '', 'end', 'date')
            ->checkIF($project->end != '', 'end', 'gt', $project->begin)
            ->check('name', 'unique', "id!=$projectID and deleted='0'")
            ->where('id')->eq($projectID)
            ->exec();

        if(!dao::isError())
        {
            $this->updateProductPGM($oldProject->parent, $project->parent, $_POST['products']);
            $this->loadModel('project')->updateProducts($projectID, $_POST['products']);
            $this->file->updateObjectID($this->post->uid, $projectID, 'project');

            $whitelist = explode(',', $project->whitelist);
            $this->loadModel('personnel')->updateWhitelist($whitelist, 'project', $projectID);
            if($project->acl != 'open') $this->loadModel('user')->updateUserView($projectID, 'project');

            if($oldProject->parent != $project->parent) $this->processNode($projectID, $project->parent, $oldProject->path, $oldProject->grade);

            return common::createChanges($oldProject, $project);
        }
    }

    /**
     * Batch update projects.
     *
     * @access public
     * @return array
     */
    public function PRJBatchUpdate()
    {
        $projects    = array();
        $allChanges  = array();
        $data        = fixer::input('post')->get();
        $oldProjects = $this->getPRJByIdList($this->post->projectIdList);
        $nameList    = array();

        foreach($data->projectIdList as $projectID)
        {
            $projectName   = $data->names[$projectID];

            $projectID = (int)$projectID;
            $projects[$projectID] = new stdClass();
            $projects[$projectID]->name           = $projectName;
            $projects[$projectID]->parent         = $data->parents[$projectID];
            $projects[$projectID]->PM             = $data->PMs[$projectID];
            $projects[$projectID]->begin          = $data->begins[$projectID];
            $projects[$projectID]->end            = isset($data->ends[$projectID]) ? $data->ends[$projectID] : LONG_TIME;
            $projects[$projectID]->days           = $data->dayses[$projectID];
            $projects[$projectID]->acl            = $data->acls[$projectID];
            $projects[$projectID]->lastEditedBy   = $this->app->user->account;
            $projects[$projectID]->lastEditedDate = helper::now();

            /* Check unique name for edited projects. */
            if(isset($nameList[$projectName])) dao::$errors['name'][] = 'project#' . $projectID . sprintf($this->lang->error->unique, $this->lang->project->PRJName, $projectName);
            $nameList[$projectName] = $projectName;

            if($projects[$projectID]->parent)
            {
                $parentProject = $this->dao->select('*')->from(TABLE_PROGRAM)->where('id')->eq($projects[$projectID]->parent)->fetch();

                if($parentProject)
                {
                    /* Child project begin cannot less than parent. */
                    if($projects[$projectID]->begin < $parentProject->begin) dao::$errors['begin'] = sprintf($this->lang->project->PRJBeginGreateChild, $parentProject->begin);

                    /* When parent set end then child project end cannot greater than parent. */
                    if($parentProject->end != '0000-00-00' and $projects[$projectID]->end > $parentProject->end) dao::$errors['end'] =  sprintf($this->lang->project->PRJEndLetterChild, $parentProject->end);

                }
            }
        }

        foreach($projects as $projectID => $project)
        {
            $oldProject = $oldProjects[$projectID];
            $this->dao->update(TABLE_PROJECT)->data($project)
                ->autoCheck($skipFields = 'begin,end')
                ->batchCheck($this->config->project->PRJEdit->requiredFields , 'notempty')
                ->checkIF($project->begin != '', 'begin', 'date')
                ->checkIF($project->end != '', 'end', 'date')
                ->checkIF($project->end != '', 'end', 'gt', $project->begin)
                ->check('name', 'unique', "id NOT " . helper::dbIN($data->projectIdList) . " and deleted='0'")
                ->where('id')->eq($projectID)
                ->exec();

            if(dao::isError()) die(js::error('project#' . $projectID . dao::getError(true)));
            if(!dao::isError())
            {
                $linkedProducts = $this->dao->select('product')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($projectID)->fetchPairs();
                $this->updateProductPGM($oldProject->parent, $project->parent, $linkedProducts);

                if($oldProject->parent != $project->parent) $this->processNode($projectID, $project->parent, $oldProject->path, $oldProject->grade);
                /* When acl is open, white list set empty. When acl is private,update user view. */
                if($project->acl == 'open') $this->loadModel('personnel')->updateWhitelist('', 'project', $projectID);
                if($project->acl != 'open') $this->loadModel('user')->updateUserView($projectID, 'project');
            }
            $allChanges[$projectID] = common::createChanges($oldProject, $project);
        }
        return $allChanges;
    }

    /**
     * Update the project set of the product.
     *
     * @param  int    $oldProject
     * @param  int    $newProject
     * @param  array  $products
     * @access public
     * @return void
     */
    public function updateProductPGM($oldProject, $newProject, $products)
    {
        $this->loadModel('action');
        /* Product belonging project set processing. */
        $oldTopPGM = $this->getTopPGMByID($oldProject);
        $newTopPGM = $this->getTopPGMByID($newProject);
        if($oldTopPGM != $newTopPGM)
        {
            foreach($products as $productID => $product)
            {
                $oldProduct = $this->dao->findById($productID)->from(TABLE_PRODUCT)->fetch();
                $this->dao->update(TABLE_PRODUCT)->set('project')->eq((int)$newTopPGM)->where('id')->eq((int)$productID)->exec();
                $newProduct = $this->dao->findById($productID)->from(TABLE_PRODUCT)->fetch();
                $changes    = common::createChanges($oldProduct, $newProduct);
                $actionID   = $this->action->create('product', $productID, 'edited');
                $this->action->logHistory($actionID, $changes);
            }
        }
    }

    /**
     * Print datatable cell.
     *
     * @param  object $col
     * @param  object $project
     * @param  array  $users
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function printCell($col, $project, $users, $projectID = 0)
    {
        $canOrder     = common::hasPriv('project', 'PRJOrderUpdate');
        $canBatchEdit = common::hasPriv('project', 'PRJBatchEdit');
        $projectLink  = $this->config->systemMode == 'new' ? helper::createLink('project', 'index', "projectID=$project->id", '', '', $project->id) : helper::createLink('project', 'task', "projectID=$project->id");
        $account      = $this->app->user->account;
        $id           = $col->id;

        if($col->show)
        {
            $title = '';
            $class = "c-$id" . (in_array($id, ['PRJBudget', 'teamCount']) ? ' c-number' : ' c-name');

            if($id == 'id') $class .= ' cell-id';

            if($id == 'PRJName')
            {
                $class .= ' text-left';
                $title  = "title='{$project->name}'";
            }

            if($id == 'PRJBudget')
            {
                $projectBudget = in_array($this->app->getClientLang(), ['zh-cn','zh-tw']) && $project->budget >= 10000 ? number_format($project->budget / 10000, 1) . $this->lang->project->tenThousand : number_format((float)$project->budget, 1);
                $budgetTitle   = $project->budget != 0 ? zget($this->lang->project->currencySymbol, $project->budgetUnit) . ' ' . $projectBudget : $this->lang->project->future;

                $title = "title='$budgetTitle'";
            }

            if($id == 'PRJEstimate') $title = "title='{$project->hours->totalEstimate} {$this->lang->project->workHour}'";
            if($id == 'PRJConsume')  $title = "title='{$project->hours->totalConsumed} {$this->lang->project->workHour}'";
            if($id == 'PRJSurplus')  $title = "title='{$project->hours->totalLeft} {$this->lang->project->workHour}'";

            echo "<td class='$class' $title>";
            switch($id)
            {
                case 'id':
                    if($canBatchEdit)
                    {
                        echo html::checkbox('projectIdList', array($project->id => '')) . html::a($projectLink, sprintf('%03d', $project->id));
                    }
                    else
                    {
                        printf('%03d', $project->id);
                    }
                    break;
                case 'PRJName':
                    echo html::a($projectLink, $project->name);
                    if($project->model === 'waterfall') echo "<span class='project-type-label label label-outline label-warning'>{$this->lang->project->waterfall}</span>";
                    if($project->model === 'scrum')     echo "<span class='project-type-label label label-outline label-info'>{$this->lang->project->scrum}</span>";
                    break;
                case 'PM':
                    $user   = $this->loadModel('user')->getByID($project->PM, 'account');
                    $userID = !empty($user) ? $user->id : '';
                    $PMLink = helper::createLink('user', 'profile', "userID=$userID", '', true);
                    echo empty($project->PM) ? '' : html::a($PMLink, zget($users, $project->PM), '', "data-toggle='modal' data-type='iframe' data-width='600'");
                    break;
                case 'begin':
                    echo $project->begin;
                    break;
                case 'end':
                    echo $project->end == LONG_TIME ? $this->lang->project->PRJLongTime : $project->end;
                    break;
                case 'PRJStatus':
                    echo "<span class='status-task status-{$project->status}'> " . zget($this->lang->project->statusList, $project->status) . "</span>";
                    break;
                case 'PRJBudget':
                    echo $budgetTitle;
                    break;
                case 'teamCount':
                    echo $project->teamCount;
                    break;
                case 'PRJEstimate':
                    echo $project->hours->totalEstimate . ' ' . $this->lang->project->workHourUnit;
                    break;
                case 'PRJConsume':
                    echo $project->hours->totalConsumed . ' ' . $this->lang->project->workHourUnit;
                    break;
                case 'PRJSurplus':
                    echo $project->hours->totalLeft     . ' ' . $this->lang->project->workHourUnit;
                    break;
                case 'PRJProgress':
                    echo "<div class='progress-pie' data-doughnut-size='80' data-color='#00da88' data-value='{$project->hours->progress}' data-width='24' data-height='24' data-back-color='#e8edf3'><div class='progress-info'>{$project->hours->progress}%</div></div>";
                    break;
                case 'actions':
                    if($project->status == 'wait' || $project->status == 'suspended') common::printIcon('project', 'PRJStart', "projectID=$project->id", $project, 'list', 'play', '', 'iframe', true);
                    if($project->status == 'doing') common::printIcon('project', 'PRJClose', "projectID=$project->id", $project, 'list', 'off', '', 'iframe', true);
                    if($project->status == 'closed') common::printIcon('project', 'PRJActivate', "projectID=$project->id", $project, 'list', 'magic', '', 'iframe', true);

                    if(common::hasPriv('project','PRJSuspend') || (common::hasPriv('project','PRJClose') && $project->status != 'doing') || (common::hasPriv('project','PRJActivate') && $project->status != 'closed'))
                    {
                        echo "<div class='btn-group'>";
                        echo "<button type='button' class='btn icon-caret-down dropdown-toggle' data-toggle='context-dropdown' title='{$this->lang->more}' style='width: 16px; padding-left: 0px; border-radius: 4px;'></button>";
                        echo "<ul class='dropdown-menu pull-right text-center' role='menu' style='position: unset; min-width: auto; padding: 5px 6px;'>";
                        common::printIcon('project', 'PRJSuspend', "projectID=$project->id", $project, 'list', 'pause', '', 'iframe btn-action', true);
                        if($project->status != 'doing') common::printIcon('project', 'PRJClose', "projectID=$project->id", $project, 'list', 'off', '', 'iframe btn-action', true);
                        if($project->status != 'closed') common::printIcon('project', 'PRJActivate', "projectID=$project->id", $project, 'list', 'magic', '', 'iframe btn-action', true);
                        echo "</ul>";
                        echo "</div>";
                    }

                    $from      = $project->from == 'PRJ' ? 'PRJ' : 'pgmproject';
                    $openGroup = $project->from == 'PRJ' ? 'project' : 'project';
                    common::printIcon('project', 'PRJEdit', "projectID=$project->id&from=$from", $project, 'list', 'edit', '', '', '', "data-group=$openGroup", '', $project->id);
                    common::printIcon('project', 'PRJManageMembers', "projectID=$project->id", $project, 'list', 'group', '', '', '', '', $this->lang->project->team, $project->id);
                    if($this->config->systemMode == 'new') common::printIcon('project', 'PRJGroup', "projectID=$project->id&projectID=$projectID", $project, 'list', 'lock', '', '', '', '', '', $project->id);

                    if(common::hasPriv('project','PRJManageProducts') || common::hasPriv('project','PRJWhitelist') || common::hasPriv('project','PRJDelete'))
                    {
                        echo "<div class='btn-group'>";
                        echo "<button type='button' class='btn dropdown-toggle' data-toggle='context-dropdown' title='{$this->lang->more}'><i class='icon-more-alt'></i></button>";
                        echo "<ul class='dropdown-menu pull-right text-center' role='menu'>";
                        common::printIcon('project', 'PRJManageProducts', "projectID=$project->id&projectID=$projectID&from=$from", $project, 'list', 'link', '', 'btn-action', '', "data-group=$openGroup", $this->lang->project->manageProducts, $project->id);
                        if($this->config->systemMode == 'new') common::printIcon('project', 'PRJWhitelist', "projectID=$project->id&projectID=$projectID&module=project&from=$from", $project, 'list', 'shield-check', '', 'btn-action', '', "data-group=$openGroup", '', $project->id);
                        if(common::hasPriv('project','PRJDelete')) echo html::a(inLink("PRJDelete", "projectID=$project->id"), "<i class='icon-trash'></i>", 'hiddenwin', "class='btn btn-action' title='{$this->lang->project->PRJDelete}'");
                        echo "</ul>";
                        echo "</div>";
                    }
                    break;
            }
            echo '</td>';
        }
    }
}
