<?php
class programModel extends model
{
    /**
     * Save program state.
     *
     * @param  int    $programID
     * @param  array  $programs
     * @access public
     * @return int
     */
    public function savePGMState($programID = 0, $programs = array())
    {
        if($programID > 0) $this->session->set('PGM', (int)$programID);
        if($programID == 0 and $this->cookie->lastPGM) $this->session->set('PGM', (int)$this->cookie->lastPGM);
        if($programID == 0 and $this->session->PGM == '') $this->session->set('PGM', key($programs));
        if(!isset($programs[$this->session->PGM]))
        {
            $this->session->set('PGM', key($programs));
            if($programID && strpos(",{$this->app->user->view->programs},", ",{$this->session->PGM},") === false) $this->accessDenied();
        }

        return $this->session->PGM;
    }

    /**
     * Get program main menu action.
     *
     * @access public
     * @return string
     */
    public function getPGMMainAction()
    {
        $link = html::a(helper::createLink('program', 'pgmbrowse'), $this->lang->program->PGMBrowse, '', "style='border: none;'");
        $html = "<p style='padding-top:5px;'><i class='icon icon-list' style='padding-right:5px;'></i>" . $link . "</p>";
        return common::hasPriv('program', 'pgmbrowse') ? $html : '';
    }

    /**
     * Get program pairs.
     *
     * @access public
     * @return array
     */
    public function getPGMPairs()
    {
        return $this->dao->select('id, name')->from(TABLE_PROGRAM)
            ->where('type')->eq('program')
            ->andWhere('deleted')->eq(0)
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->programs)->fi()
            ->fetchPairs();
    }

    /**
     * Get program option.
     *
     * @access public
     * @return array
     */
    public function getPGMOption()
    {
        return $this->dao->select('id,name')->from(TABLE_PROGRAM)
            ->where('type')->eq('program')
            ->andWhere('deleted')->eq(0)
            ->fetchPairs();
    }

    /**
     * Get the product associated with the program.
     *
     * @param  int     $programID
     * @param  string  $mode       all|assign
     * @param  string  $status     all|noclosed
     * @access public
     * @return array
     */
    public function getPGMProductPairs($programID = 0, $mode = 'assign', $status = 'all')
    {
        /* Get the top programID. */
        if($programID)
        {
            $program   = $this->getPGMByID($programID);
            $path      = explode(',', $program->path);
            $path      = array_filter($path);
            $programID = current($path);
        }

        /* When mode equals assign and programID equals 0, you can query the standalone product. */
        $products = $this->dao->select('*')->from(TABLE_PRODUCT)
            ->where('deleted')->eq(0)
            ->beginIF($mode == 'assign')->andWhere('program')->eq($programID)->fi()
            ->beginIF(strpos($status, 'noclosed') !== false)->andWhere('status')->ne('closed')->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->products)->fi()
            ->fetchPairs('id', 'name');
        return $products;
    }

    /**
     * Get program by id.
     *
     * @param  int  $programID
     * @access public
     * @return array
     */
    public function getPGMByID($programID = 0)
    {
        return $this->dao->select('*')->from(TABLE_PROGRAM)->where('id')->eq($programID)->andWhere('`type`')->eq('program')->fetch();
    }

    /**
     * Get program list.
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
            ->where('type')->in('program,project')
            ->andWhere('deleted')->eq(0)
            ->beginIF(!$this->app->user->admin)
            ->andWhere('id')->in($this->app->user->view->programs)
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
     * @param  int    $programID
     * @access private
     * @return void
     */
    public function setPGMViewMenu($programID = 0)
    {
        foreach($this->lang->program->viewMenu as $label => $menu)
        {
            $this->lang->program->viewMenu->{$label}['link'] = is_array($menu) ? sprintf($menu['link'], $programID) : sprintf($menu, $programID);
        }

        foreach($this->lang->personnel->menu as $label => $menu)
        {
            $menu['link'] = is_array($menu) ? sprintf($menu['link'], $programID) : sprintf($menu, $programID);
            $this->lang->personnel->menu->$label = $menu;
        }

        $this->lang->program->menu = $this->lang->program->viewMenu;
    }

    /**
     * Create a program.
     *
     * @access private
     * @return int|bool
     */
    public function PGMCreate()
    {
        $program = fixer::input('post')
            ->setDefault('status', 'wait')
            ->setDefault('openedBy', $this->app->user->account)
            ->setDefault('parent', 0)
            ->setDefault('openedDate', helper::now())
            ->setIF($this->post->acl == 'open', 'whitelist', '')
            ->setIF($this->post->delta == 999, 'end', LONG_TIME)
            ->setIF($this->post->budget != 0, 'budget', round($this->post->budget, 2))
            ->add('type', 'program')
            ->join('whitelist', ',')
            ->stripTags($this->config->program->editor->pgmcreate['id'], $this->config->allowedTags)
            ->remove('delta,future')
            ->get();

        if($program->parent)
        {
            $parentProgram = $this->dao->select('*')->from(TABLE_PROGRAM)->where('id')->eq($program->parent)->fetch();
            if($parentProgram)
            {
                /* Child program begin cannot less than parent. */
                if($program->begin < $parentProgram->begin) dao::$errors['begin'] = sprintf($this->lang->program->PGMBeginLetterParent, $parentProgram->begin);

                /* When parent set end then child program end cannot greater than parent. */
                if($parentProgram->end != '0000-00-00' and $program->end > $parentProgram->end) dao::$errors['end'] = sprintf($this->lang->program->PGMEndGreaterParent, $parentProgram->end);

                /* When parent set end then child program cannot set longTime. */
                if(empty($program->end) and $this->post->delta == 999 and $parentProgram->end != '0000-00-00') dao::$errors['end'] = sprintf($this->lang->program->PGMEndGreaterParent, $parentProgram->end);

                /* The budget of a child program cannot beyond the remaining budget of the parent program. */
                $program->budgetUnit = $parentProgram->budgetUnit;
                if(isset($program->budget) and $parentProgram->budget != 0)
                {
                    $parentRemainBudget = $this->getParentRemainBudget($parentProgram);
                    if($program->budget > $parentRemainBudget) dao::$errors['budget'] = $this->lang->program->beyondParentBudget;
                }

                if(dao::isError()) return false;
            }
        }

        /* Redefines the language entries for the fields in the project table. */
        foreach(explode(',', $this->config->program->PGMCreate->requiredFields) as $fieldName)
        {
            $fieldKey = 'PGM' . ucfirst($fieldName);
            if(isset($this->lang->program->$fieldKey)) $this->lang->project->$fieldName = $this->lang->program->$fieldKey;
        }

        $program = $this->loadModel('file')->processImgURL($program, $this->config->program->editor->pgmcreate['id'], $this->post->uid);
        $this->dao->insert(TABLE_PROGRAM)->data($program)
            ->autoCheck()
            ->batchcheck($this->config->program->PGMCreate->requiredFields, 'notempty')
            ->checkIF($program->begin != '', 'begin', 'date')
            ->checkIF($program->end != '', 'end', 'date')
            ->checkIF($program->end != '', 'end', 'gt', $program->begin)
            ->checkIF(!empty($program->name), 'name', 'unique')
            ->exec();

        if(!dao::isError())
        {
            $programID = $this->dao->lastInsertId();
            $this->dao->update(TABLE_PROGRAM)->set('`order`')->eq($programID * 5)->where('id')->eq($programID)->exec(); // Save order.

            $whitelist = explode(',', $program->whitelist);
            $this->loadModel('personnel')->updateWhitelist($whitelist, 'program', $programID);
            if($program->acl != 'open') $this->loadModel('user')->updateUserView($programID, 'program');

            $this->file->updateObjectID($this->post->uid, $programID, 'project');
            $this->setTreePath($programID);

            /* Add program admin.*/
            $groupPriv = $this->dao->select('t1.*')->from(TABLE_USERGROUP)->alias('t1')
                ->leftJoin(TABLE_GROUP)->alias('t2')->on('t1.group = t2.id')
                ->where('t1.account')->eq($this->app->user->account)
                ->andWhere('t2.role')->eq('PRJAdmin')
                ->fetch();

            if(!empty($groupPriv))
            {
                $newProgram = $groupPriv->PRJ . ",$programID";
                $this->dao->update(TABLE_USERGROUP)->set('PRJ')->eq($newProgram)->where('account')->eq($groupPriv->account)->andWhere('`group`')->eq($groupPriv->group)->exec();
            }
            else
            {
                $PRJAdminID = $this->dao->select('id')->from(TABLE_GROUP)->where('role')->eq('PRJAdmin')->fetch('id');
                $groupPriv  = new stdclass();
                $groupPriv->account = $this->app->user->account;
                $groupPriv->group   = $PRJAdminID;
                $groupPriv->PRJ     = $programID;
                $this->dao->insert(TABLE_USERGROUP)->data($groupPriv)->exec();
            }

            return $programID;
        }
    }

    /**
     * Update program.
     *
     * @param  int    $programID
     * @access public
     * @return array|bool
     */
    public function PGMUpdate($programID)
    {
        $programID  = (int)$programID;
        $oldProgram = $this->dao->findById($programID)->from(TABLE_PROGRAM)->fetch();

        $program = fixer::input('post')
            ->setDefault('team', $this->post->name)
            ->setDefault('end', '')
            ->setIF($this->post->begin == '0000-00-00', 'begin', '')
            ->setIF($this->post->end   == '0000-00-00', 'end', '')
            ->setIF($this->post->acl   == 'open', 'whitelist', '')
            ->setIF($this->post->delta == 999, 'end', LONG_TIME)
            ->setIF($this->post->future, 'budget', 0)
            ->setIF($this->post->budget != 0, 'budget', round($this->post->budget, 2))
            ->join('whitelist', ',')
            ->stripTags($this->config->program->editor->pgmedit['id'], $this->config->allowedTags)
            ->remove('uid,delta,future')
            ->get();

        $program  = $this->loadModel('file')->processImgURL($program, $this->config->program->editor->pgmedit['id'], $this->post->uid);
        $children = $this->getChildren($programID);

        if($children > 0)
        {
            $minChildBegin = $this->dao->select('min(begin) as minBegin')->from(TABLE_PROGRAM)->where('id')->ne($programID)->andWhere('deleted')->eq(0)->andWhere('path')->like("%,{$programID},%")->fetch('minBegin');
            $maxChildEnd   = $this->dao->select('max(end) as maxEnd')->from(TABLE_PROGRAM)->where('id')->ne($programID)->andWhere('deleted')->eq(0)->andWhere('path')->like("%,{$programID},%")->andWhere('end')->ne('0000-00-00')->fetch('maxEnd');

            if($minChildBegin and $program->begin > $minChildBegin) dao::$errors['begin'] = sprintf($this->lang->program->PGMBeginGreateChild, $minChildBegin);
            if($maxChildEnd   and $program->end   < $maxChildEnd and $this->post->delta != 999) dao::$errors['end'] = sprintf($this->lang->program->PGMEndLetterChild, $maxChildEnd);
        }

        if($program->parent)
        {
            $parentProgram = $this->dao->select('*')->from(TABLE_PROGRAM)->where('id')->eq($program->parent)->fetch();
            if($parentProgram)
            {
                if($program->begin < $parentProgram->begin) dao::$errors['begin'] = sprintf($this->lang->program->PGMBeginLetterParent, $parentProgram->begin);
                if($parentProgram->end != '0000-00-00' and $program->end > $parentProgram->end) dao::$errors['end'] = sprintf($this->lang->program->PGMEndGreaterParent, $parentProgram->end);
                if(empty($program->end) and $this->post->delta == 999 and $parentProgram->end != '0000-00-00') dao::$errors['end'] = sprintf($this->lang->program->PGMEndGreaterParent, $parentProgram->end);
            }

            /* The budget of a child program cannot beyond the remaining budget of the parent program. */
            $program->budgetUnit = $parentProgram->budgetUnit;
            if($program->budget != 0 and $parentProgram->budget != 0)
            {
                $parentRemainBudget = $this->getParentRemainBudget($parentProgram);
                if($program->budget > $parentRemainBudget + $oldProgram->budget) dao::$errors['budget'] = $this->lang->program->beyondParentBudget;
            }
        }
        if(dao::isError()) return false;

        /* Redefines the language entries for the fields in the project table. */
        foreach(explode(',', $this->config->program->PGMCreate->requiredFields) as $fieldName)
        {
            $fieldKey = 'PGM' . ucfirst($fieldName);
            if(isset($this->lang->program->$fieldKey)) $this->lang->project->$fieldName = $this->lang->program->$fieldKey;
        }

        $this->dao->update(TABLE_PROGRAM)->data($program)
            ->autoCheck($skipFields = 'begin,end')
            ->batchcheck($this->config->program->PGMEdit->requiredFields, 'notempty')
            ->checkIF($program->begin != '', 'begin', 'date')
            ->checkIF($program->end != '', 'end', 'date')
            ->checkIF($program->end != '', 'end', 'gt', $program->begin)
            ->check('name', 'unique', "id!=$programID and deleted='0'")
            ->where('id')->eq($programID)
            ->limit(1)
            ->exec();

        if(!dao::isError())
        {
            $this->file->updateObjectID($this->post->uid, $programID, 'project');
            $whitelist = explode(',', $program->whitelist);
            $this->loadModel('personnel')->updateWhitelist($whitelist, 'program', $programID);
            if($program->acl != 'open') $this->loadModel('user')->updateUserView($programID, 'program');

            if($oldProgram->parent != $program->parent) $this->processNode($programID, $program->parent, $oldProgram->path, $oldProgram->grade);

            return common::createChanges($oldProgram, $program);
        }
    }

    /*
     * Get program swapper.
     *
     * @param  int     $programID
     * @param  bool    $active
     * @access private
     * @return string
     */
    public function getPGMSwitcher($programID = 0)
    {
        $currentProgramName = '';
        $currentModule      = $this->app->moduleName;
        $currentMethod      = $this->app->methodName;

        if($programID)
        {
            setCookie("lastProgram", $programID, $this->config->cookieLife, $this->config->webRoot, '', false, true);
            $currentProgram     = $this->getPGMById($programID);
            $currentProgramName = $currentProgram->name;
        }
        else
        {
            $currentProgramName = $this->lang->program->PGMAll;
        }

        $dropMenuLink = helper::createLink('program', 'ajaxGetPGMDropMenu', "objectID=$programID&module=$currentModule&method=$currentMethod");
        $output  = "<div class='btn-group header-angle-btn' id='swapper'><button data-toggle='dropdown' type='button' class='btn' id='currentItem' title='{$currentProgramName}'><span class='text'><i class='icon icon-folder-open-o'></i> {$currentProgramName}</span> <span class='caret'></span></button><div id='dropMenu' class='dropdown-menu search-list' data-ride='searchList' data-url='$dropMenuLink'>";
        $output .= '<div class="input-control search-box has-icon-left has-icon-right search-example"><input type="search" class="form-control search-input" /><label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label><a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a></div>'; $output .= "</div></div>";

        return $output;
    }

    /**
     * Get the tree menu of program.
     *
     * @param  int    $programID
     * @param  string $from
     * @param  string $vars
     * @access public
     * @return string
     */
    public function getPGMTreeMenu($programID = 0, $from = 'program', $vars = '')
    {
        $programMenu = array();
        $query = $this->dao->select('*')->from(TABLE_PROJECT)
            ->where('deleted')->eq('0')
            ->beginIF($from == 'program')
            ->andWhere('type')->eq('program')
            ->andWhere('id')->in($this->app->user->view->programs)
            ->fi()
            ->beginIF($from == 'product')
            ->andWhere('type')->eq('program')
            ->andWhere('grade')->eq(1)
            ->andWhere('id')->in($this->app->user->view->programs)
            ->fi()
            ->beginIF(!$this->cookie->showClosed)->andWhere('status')->ne('closed')->fi()
            ->orderBy('grade desc, `order`')->get();
        $stmt = $this->dbh->query($query);

        while($program = $stmt->fetch())
        {
            $link = $from == 'program' ? helper::createLink('program', 'pgmproduct', "programID=$program->id") : helper::createLink('product', 'all', "programID=$program->id" . $vars);
            $linkHtml = html::a($link, html::icon($this->lang->icons[$program->type], 'icon icon-sm text-muted') . ' ' . $program->name, '', "id='program$program->id' class='text-ellipsis' title=$program->name");

            if(isset($programMenu[$program->id]) and !empty($programMenu[$program->id]))
            {
                if(!isset($programMenu[$program->parent])) $programMenu[$program->parent] = '';
                $programMenu[$program->parent] .= "<li>$linkHtml";
                $programMenu[$program->parent] .= "<ul>".$programMenu[$program->id]."</ul>\n";
            }
            else
            {
                if(isset($programMenu[$program->parent]) and !empty($programMenu[$program->parent]))
                {
                    $programMenu[$program->parent] .= "<li>$linkHtml\n";
                }
                else
                {
                    $programMenu[$program->parent] = "<li>$linkHtml\n";
                }
            }
            $programMenu[$program->parent] .= "</li>\n";
        }

        krsort($programMenu);
        $programMenu = array_pop($programMenu);
        $lastMenu    = "<ul class='tree tree-simple' data-ride='tree' id='programTree' data-name='tree-program'>{$programMenu}</ul>\n";

        return $lastMenu;
    }

    /**
     * Get top program pairs.
     *
     * @param  string $model
     * @param  string $mode
     * @access public
     * @return array
     */
    public function getTopPGMPairs($model = '', $mode = '')
    {
        return $this->dao->select('id,name')->from(TABLE_PROGRAM)
            ->where('type')->eq('program')
            ->andWhere('grade')->eq(1)
            ->beginIF(strpos($mode, 'noclosed') !== false)->andWhere('status')->ne('closed')->fi()
            ->andWhere('id')->in($this->app->user->view->programs)
            ->andWhere('deleted')->eq(0)
            ->beginIF($model)->andWhere('model')->eq($model)->fi()
            ->orderBy('`order`')
            ->fetchPairs();
    }

    /**
     * Get top program by id.
     *
     * @param  int    $programID
     * @access public
     * @return int
     */
    public function getTopPGMByID($programID)
    {
        $topPGM  = 0;
        if(empty($programID)) return $topPGM;

        $program = $this->getPGMByID($programID);
        if(!empty($program)) list($topPGM) = explode(',', trim($program->path, ','));
        return $topPGM;
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
     * Get children by program id.
     *
     * @param  int     $programID
     * @access public
     * @return int
     */
    public function getChildren($programID = 0)
    {
        return $this->dao->select('count(*) as count')->from(TABLE_PROGRAM)->where('parent')->eq($programID)->fetch('count');
    }

    /**
     * Judge whether there is an unclosed programs or projects.
     *
     * @param  object  $program
     * @access public
     * @return int
     */
    public function hasUnfinished($program)
    {
        $unfinished = $this->dao->select("count(IF(id != {$program->id}, true, null)) as count")->from(TABLE_PROJECT)
            ->where('type')->in('program,project')
            ->andWhere('path')->like($program->path . '%')
            ->andWhere('status')->ne('closed')
            ->andWhere('deleted')->eq('0')
            ->fetch('count');

        return $unfinished;
    }

    /**
     * Get stakeholders by program id.
     *
     * @param  int     $programID
     * @param  string  $orderBy
     * @param  object  $paper
     * @access public
     * @return array
     */
    public function getStakeholders($programID = 0, $orderBy, $pager = null)
    {
        return $this->dao->select('t2.account,t2.realname,t2.role,t2.qq,t2.mobile,t2.phone,t2.weixin,t2.email,t1.id,t1.type,t1.key')->from(TABLE_STAKEHOLDER)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.user=t2.account')
            ->where('t1.objectID')->eq($programID)
            ->andWhere('t1.objectType')->eq('program')
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
    }

    /**
     * Get stakeholders by program id list.
     *
     * @param  string $programIdList
     * @access public
     * @return array
     */
    public function getStakeholdersByPGMList($programIdList = 0)
    {
        return $this->dao->select('distinct user as account')->from(TABLE_STAKEHOLDER)
            ->where('objectID')->in($programIdList)
            ->andWhere('objectType')->eq('program')
            ->fetchAll();
    }

    /**
     * Create stakeholder for a program.
     *
     * @param  int     $programID
     * @access public
     * @return void
     */
    public function createStakeholder($programID = 0)
    {
        $data = (array)fixer::input('post')->get();

        $accounts = array_unique($data['accounts']);
        $oldJoin  = $this->dao->select('`user`, createdDate')->from(TABLE_STAKEHOLDER)->where('objectID')->eq((int)$programID)->andWhere('objectType')->eq('program')->fetchPairs();
        $this->dao->delete()->from(TABLE_STAKEHOLDER)->where('objectID')->eq((int)$programID)->andWhere('objectType')->eq('program')->exec();

        foreach($accounts as $key => $account)
        {
            if(empty($account)) continue;

            $stakeholder = new stdclass();
            $stakeholder->objectID    = $programID;
            $stakeholder->objectType  = 'program';
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

        $this->loadModel('user')->updateUserView($programID, 'program', $changedAccounts);

        /* Update children user view. */
        $childPGMList  = $this->dao->select('id')->from(TABLE_PROJECT)->where('path')->like("%,$programID,%")->andWhere('type')->eq('program')->fetchPairs();
        $childPRJList  = $this->dao->select('id')->from(TABLE_PROJECT)->where('path')->like("%,$programID,%")->andWhere('type')->eq('project')->fetchPairs();
        $childProducts = $this->dao->select('id')->from(TABLE_PRODUCT)->where('program')->eq($programID)->fetchPairs();

        if(!empty($childPGMList))  $this->user->updateUserView($childPGMList, 'program', $changedAccounts);
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
        echo(js::alert($this->lang->program->accessDenied));

        if(!$this->server->http_referer) die(js::locate(helper::createLink('program', 'prjbrowse')));

        $loginLink = $this->config->requestType == 'GET' ? "?{$this->config->moduleVar}=user&{$this->config->methodVar}=login" : "user{$this->config->requestFix}login";
        if(strpos($this->server->http_referer, $loginLink) !== false) die(js::locate(helper::createLink('program', 'browse')));

        die(js::locate('back'));
    }

    /**
     * Judge an action is clickable or not.
     *
     * @param  object    $program
     * @param  string    $action
     * @access public
     * @return bool
     */
    public static function isClickable($program, $action)
    {
        $action = strtolower($action);

        if(empty($program)) return true;
        if(!isset($program->type)) return true;

        if($action == 'pgmclose')    return $program->status != 'closed';
        if($action == 'pgmactivate') return $program->status == 'done' or $program->status == 'closed';
        if($action == 'pgmsuspend')  return $program->status == 'wait' or $program->status == 'doing';

        if($action == 'prjstart')    return $program->status == 'wait' or $program->status == 'suspended';
        if($action == 'prjfinish')   return $program->status == 'wait' or $program->status == 'doing';
        if($action == 'prjclose')    return $program->status != 'closed';
        if($action == 'prjsuspend')  return $program->status == 'wait' or $program->status == 'doing';
        if($action == 'prjactivate') return $program->status == 'done' or $program->status == 'closed';

        return true;
    }

    /**
     * Check has content for program.
     *
     * @param  int    $programID
     * @access public
     * @return bool
     */
    public function checkHasContent($programID)
    {
        $count  = 0;
        $count += (int)$this->dao->select('count(*) as count')->from(TABLE_PROGRAM)->where('parent')->eq($programID)->fetch('count');
        $count += (int)$this->dao->select('count(*) as count')->from(TABLE_TASK)->where('PRJ')->eq($programID)->fetch('count');

        return $count > 0;
    }

    /**
     * Check has children project.
     *
     * @param  int    $programID
     * @access public
     * @return bool
     */
    public function checkHasChildren($programID)
    {
        $count = $this->dao->select('count(*) as count')->from(TABLE_PROGRAM)->where('parent')->eq($programID)->fetch('count');
        return $count > 0;
    }

    /**
     * Set program tree path.
     *
     * @param  int    $programID
     * @access public
     * @return bool
     */
    public function setTreePath($programID)
    {
        $program = $this->dao->select('*')->from(TABLE_PROGRAM)->where('id')->eq($programID)->fetch();

        $path['path']  = ",{$program->id},";
        $path['grade'] = 1;

        if($program->parent)
        {
            $parent = $this->dao->select('*')->from(TABLE_PROGRAM)->where('id')->eq($program->parent)->fetch();

            $path['path']  = $parent->path . "{$program->id},";
            $path['grade'] = $parent->grade + 1;
        }
        $this->dao->update(TABLE_PROGRAM)->set('path')->eq($path['path'])->set('grade')->eq($path['grade'])->where('id')->eq($program->id)->exec();
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
        foreach(explode(',', $this->config->program->unitList) as $unit) $budgetUnitList[$unit] = zget($this->lang->program->unitList, $unit, '');

        return $budgetUnitList;
    }

    /**
     * Get parent remain budget.
     *
     * @param  object  $parentProgram
     * @access public
     * @return int
     */
    public function getParentRemainBudget($parentProgram)
    {
        if(empty($parentProgram)) return;

        $childGrade     = $parentProgram->grade + 1;
        $childSumBudget = $this->dao->select("sum(budget) as sumBudget")->from(TABLE_PROJECT)
            ->where('path')->like("%{$parentProgram->id}%")
            ->andWhere('grade')->eq($childGrade)
            ->fetch('sumBudget');

        return (float)$parentProgram->budget - (float)$childSumBudget;
    }

    /**
     * Get program parent pairs
     *
     * @param  string $model
     * @access public
     * @return array
     */
    public function getParentPairs($model = '')
    {
        $modules = $this->dao->select('id,name,parent,path,grade')->from(TABLE_PROGRAM)
            ->where('type')->eq('program')
            ->andWhere('status')->ne('closed')
            ->andWhere('deleted')->eq(0)
            ->beginIF($model)->andWhere('model')->eq($model)->fi()
            ->orderBy('grade desc, `order`')
            ->fetchAll('id');

        $treeMenu = array();
        foreach($modules as $module)
        {
            if(strpos($this->app->user->view->programs, $module->id) === false) continue;

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
     * @param  int       $programID
     * @param  int       $parentID
     * @param  string    $oldPath
     * @param  int       $oldGrade
     * @access public
     * @return bool
     */
    public function processNode($programID, $parentID, $oldPath, $oldGrade)
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
            $path  = substr($childNode->path, strpos($childNode->path, ",{$programID},"));
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
        $currentProjectName = $this->lang->program->common;
        if($projectID)
        {
            $currentProject     = $this->getPRJById($projectID);
            $currentProjectName = $currentProject->name;
        }

        $dropMenuLink = helper::createLink('program', 'ajaxGetPRJDropMenu', "objectID=$projectID&module=$currentModule&method=$currentMethod");
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
        $link = html::a(helper::createLink('program', 'prjbrowse'), $this->lang->program->PRJBrowse, '', "style='border: none;'");
        $html = "<p style='padding-top:5px;'><i class='icon icon-list' style='padding-right:5px;'></i>" . $link . "</p>";
        return common::hasPriv('program', 'prjbrowse') ? $html : '';
    }

    /**
     * Get project list data.
     *
     * @param  int       $programID
     * @param  string    $browseType
     * @param  string    $queryID
     * @param  string    $orderBy
     * @param  object    $pager
     * @param  int       $programTitle
     * @param  int       $PRJMine
     * @access public
     * @return object
     */
    public function getPRJList($programID = 0, $browseType = 'all', $queryID = 0, $orderBy = 'id_desc', $pager = null, $programTitle = 0, $PRJMine = 0)
    {
        $path = '';
        if($programID)
        {
            $program = $this->getPGMByID($programID);
            $path    = $program->path;
        }

        $projectList = $this->dao->select('*')->from(TABLE_PROJECT)
            ->where('type')->eq('project')
            ->beginIF($browseType != 'all')->andWhere('status')->eq($browseType)->fi()
            ->beginIF($path)->andWhere('path')->like($path . '%')->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->projects)->fi()
            ->beginIF($this->cookie->PRJMine or $PRJMine)
            ->andWhere('openedBy', true)->eq($this->app->user->account)
            ->orWhere('PM')->eq($this->app->user->account)
            ->markRight(1)
            ->fi()
            ->andWhere('deleted')->eq('0')
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        /* Determine how to display the name of the program. */
        if($programTitle)
        {
            $programList = $this->getPGMPairs();
            foreach($projectList as $id => $project)
            {
                $path = explode(',', $project->path);
                $path = array_filter($path);
                array_pop($path);
                $programID = $programTitle == 'base' ? current($path) : end($path);
                if(empty($path) || $programID == $id) continue;

                $programName = isset($programList[$programID]) ? $programList[$programID] : '';

                $projectList[$id]->name = $programName . '/' . $projectList[$id]->name;
            }
        }
        return $projectList;
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
     * Get project stats.
     *
     * @param  int    $programID
     * @param  string $browseType
     * @param  int    $queryID
     * @param  string $orderBy
     * @param  object $pager
     * @param  string $programTitle
     * @param  int    $PRJMine
     * @access public
     * @return array
     */
    public function getPRJStats($programID = 0, $browseType = 'undone', $queryID = 0, $orderBy = 'id_desc', $pager = null, $programTitle = 0, $PRJMine = 0)
    {
        /* Init vars. */
        $projects = $this->getPRJList($programID, $browseType, $queryID, $orderBy, $pager, $programTitle, $PRJMine);
        if(empty($projects)) return array();

        $projectKeys = array_keys($projects);
        $stats       = array();
        $hours       = array();
        $emptyHour   = array('totalEstimate' => 0, 'totalConsumed' => 0, 'totalLeft' => 0, 'progress' => 0);

        /* Get all tasks and compute totalEstimate, totalConsumed, totalLeft, progress according to them. */
        $tasks = $this->dao->select('id, PRJ, estimate, consumed, `left`, status, closedReason')
            ->from(TABLE_TASK)
            ->where('PRJ')->in($projectKeys)
            ->andWhere('parent')->lt(1)
            ->andWhere('deleted')->eq(0)
            ->fetchGroup('PRJ', 'id');

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
            $hour->progress      = $hour->totalReal ? round($hour->totalConsumed / $hour->totalReal, 2) * 100 : 0;
        }

        /* Get the number of project teams. */
        $teams = $this->dao->select('root,count(*) as teams')->from(TABLE_TEAM)
            ->where('root')->in($projectKeys)
            ->andWhere('type')->eq('project')
            ->groupBy('root')
            ->fetchAll('root');

        /* Process projects. */
        foreach($projects as $key => $project)
        {
            if($project->end == '0000-00-00') $project->end = '';

            /* Judge whether the project is delayed. */
            if($project->status != 'done' and $project->status != 'closed' and $project->status != 'suspended')
            {
                $delay = helper::diffDate(helper::today(), $project->end);
                if($delay > 0) $project->delay = $delay;
            }

            /* Process the hours. */
            $project->hours = isset($hours[$project->id]) ? $hours[$project->id] : (object)$emptyHour;

            $project->teamCount = isset($teams[$project->id]) ? $teams[$project->id]->teams : 0;
            $stats[] = $project;
        }
        return $stats;
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
     * Get project team member pairs.
     *
     * @param  int  $programID
     * @access public
     * @return array
     */
    public function getPRJTeamMemberPairs($programID = 0)
    {
        $projectList = $this->getPRJPairs($programID);
        if(!$projectList) return array('' => '');

        $users = $this->dao->select("t2.id, t2.account, t2.realname")->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account = t2.account')
            ->where('t1.root')->in(array_keys($projectList))
            ->andWhere('t1.type')->eq('project')
            ->andWhere('t2.deleted')->eq(0)
            ->fetchAll('account');
        if(!$users) return array('' => '');

        foreach($users as $account => $user)
        {
            $firstLetter = ucfirst(substr($user->account, 0, 1)) . ':';
            if(!empty($this->config->isINT)) $firstLetter = '';
            $users[$account] = $firstLetter . ($user->realname ? $user->realname : $user->account);
        }

        return array('' => '') + $users;
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
        $program = $this->getPRJByID($projectID);
        if($program) $path = $program->path;

        return $this->dao->select('*')->from(TABLE_PROJECT)
            ->where('deleted')->eq('0')
            ->beginIF($type == 'list')->andWhere('type')->eq('program')->fi()
            ->beginIF($type == 'dropmenu')->andWhere('type')->in('program,project')->fi()
            ->andWhere('status')->ne('closed')
            ->beginIF(!$this->app->user->admin and $type == 'list')->andWhere('id')->in($this->app->user->view->programs)->fi()
            ->beginIF(!$this->app->user->admin and $type == 'dropmenu')->andWhere('id')->in($this->app->user->view->programs . ',' . $this->app->user->view->projects)->fi()
            ->beginIF($projectID > 0)->andWhere('path')->like($path . '%')->fi()
            ->orderBy('grade desc, `order`')
            ->get();
    }

    /**
     * Get project pairs by model and program.
     *
     * @param  string $model all|scrum|waterfall
     * @param  int    $programID
     * @access public
     * @return array
     */
    public function getPRJPairsByModel($model = 'all', $programID = 0)
    {
        return $this->dao->select('id, name')->from(TABLE_PROJECT)
            ->where('type')->eq('project')
            ->beginIF($programID)->andWhere('parent')->eq($programID)->fi()
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
        $link = $project->type == 'program' ? helper::createLink('program', 'PRJbrowse', "programID={$project->id}&status=all") : helper::createLink('program', 'index', "projectID={$project->id}", '', '', $project->id);
        $icon = $project->type == 'program' ? "<i class='icon icon-folder-open-o'></i> " : "<i class='icon icon-project'></i> ";
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
            ->add('type', 'project')
            ->join('whitelist', ',')
            ->stripTags($this->config->program->editor->prjcreate['id'], $this->config->allowedTags)
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

        $parentProgram = new stdClass();
        if($project->parent)
        {
            $parentProgram = $this->dao->select('*')->from(TABLE_PROGRAM)->where('id')->eq($project->parent)->fetch();
            if($parentProgram)
            {
                /* Child project begin cannot less than parent. */
                if($project->begin < $parentProgram->begin) dao::$errors['begin'] = sprintf($this->lang->program->PRJBeginGreateChild, $parentProgram->begin);

                /* When parent set end then child project end cannot greater than parent. */
                if($parentProgram->end != '0000-00-00' and $project->end > $parentProgram->end) dao::$errors['end'] = sprintf($this->lang->program->PRJEndLetterChild, $parentProgram->end);

                if(dao::isError()) return false;
            }

            /* The budget of a child project cannot beyond the remaining budget of the parent program. */
            $project->budgetUnit = $parentProgram->budgetUnit;
            if(isset($project->budget) and $parentProgram->budget != 0)
            {
                $parentRemainBudget = $this->getParentRemainBudget($parentProgram);
                if($project->budget > $parentRemainBudget) dao::$errors['budget'] = $this->lang->program->beyondParentBudget;
            }

            /* Judge products not empty. */
            if(empty($linkedProductsCount) and !isset($_POST['newProduct']))
            {
                dao::$errors[] = $this->lang->program->productNotEmpty;
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
                    dao::$errors['productName'] = $this->lang->program->existProductName;
                    return false;
                }
            }
        }

        $requiredFields = $this->config->program->PRJCreate->requiredFields;
        if($this->post->delta == 999) $requiredFields = trim(str_replace(',end,', ',', ",{$requiredFields},"), ',');

        /* Redefines the language entries for the fields in the project table. */
        foreach(explode(',', $requiredFields) as $fieldName)
        {
            $fieldKey = 'PRJ' . ucfirst($fieldName);
            if(isset($this->lang->program->$fieldKey)) $this->lang->project->$fieldName = $this->lang->program->$fieldKey;
        }

        $project = $this->loadModel('file')->processImgURL($project, $this->config->program->editor->prjcreate['id'], $this->post->uid);
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
                $product->program      = $project->parent ? current(array_filter(explode(',', $parentProgram->path))) : 0;
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
            ->setIF($this->post->delta == 999, 'end', LONG_TIME)
            ->setIF($this->post->delta == 999, 'days', 0)
            ->setIF($this->post->begin == '0000-00-00', 'begin', '')
            ->setIF($this->post->end   == '0000-00-00', 'end', '')
            ->setIF($this->post->acl   == 'open', 'whitelist', '')
            ->setIF($this->post->future, 'budget', 0)
            ->setIF($this->post->budget != 0, 'budget', round($this->post->budget, 2))
            ->join('whitelist', ',')
            ->stripTags($this->config->program->editor->prjedit['id'], $this->config->allowedTags)
            ->remove('products,branch,plans,delta,future')
            ->get();

        if($project->parent)
        {
            $parentProgram = $this->dao->select('*')->from(TABLE_PROGRAM)->where('id')->eq($project->parent)->fetch();

            if($parentProgram)
            {
                /* Child project begin cannot less than parent. */
                if($project->begin < $parentProgram->begin) dao::$errors['begin'] = sprintf($this->lang->program->PRJBeginGreateChild, $parentProgram->begin);

                /* When parent set end then child project end cannot greater than parent. */
                if($parentProgram->end != '0000-00-00' and $project->end > $parentProgram->end) dao::$errors['end'] = sprintf($this->lang->program->PRJEndLetterChild, $parentProgram->end);

                if(dao::isError()) return false;
            }

            /* The budget of a child project cannot beyond the remaining budget of the parent program. */
            $project->budgetUnit = $parentProgram->budgetUnit;
            if($project->budget != 0 and $parentProgram->budget != 0)
            {
                $parentRemainBudget = $this->getParentRemainBudget($parentProgram);
                if($project->budget > $parentRemainBudget + $oldProject->budget) dao::$errors['budget'] = $this->lang->program->beyondParentBudget;
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
            dao::$errors[] = $this->lang->program->errorNoProducts;
            return false;
        }

        $project = $this->loadModel('file')->processImgURL($project, $this->config->program->editor->prjedit['id'], $this->post->uid);

        $requiredFields = $this->config->program->PRJEdit->requiredFields;
        if($this->post->delta == 999) $requiredFields = trim(str_replace(',end,', ',', ",{$requiredFields},"), ',');

        /* Redefines the language entries for the fields in the project table. */
        foreach(explode(',', $requiredFields) as $fieldName)
        {
            $fieldKey = 'PRJ' . ucfirst($fieldName);
            if(isset($this->lang->program->$fieldKey)) $this->lang->project->$fieldName = $this->lang->program->$fieldKey;
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
            /* Product belonging program set processing. */
            $oldTopPGM = $this->getTopPGMByID($oldProject->parent);
            $newTopPGM = $this->getTopPGMByID($project->parent);
            if($oldTopPGM != $newTopPGM)
            {
                foreach($_POST['products'] as $productID => $product)
                {
                    $this->dao->update(TABLE_PRODUCT)->set('program')->eq($newTopPGM)->where('id')->eq((int)$productID)->exec();
                }
            }

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
            $parentProgram = $data->parents[$projectID];

            $projectID = (int)$projectID;
            $projects[$projectID] = new stdClass();
            $projects[$projectID]->name   = $projectName;
            $projects[$projectID]->parent = $parentProgram;
            $projects[$projectID]->PM     = $data->PMs[$projectID];

            /* Check unique name for edited projects. */
            if(isset($nameList[$projectName])) dao::$errors['name'][] = 'project#' . $projectID .  sprintf($this->lang->error->unique, $this->lang->program->PRJName, $projectName);
            $nameList[$projectName] = $projectName;
        }

        foreach($projects as $projectID => $project)
        {
            $oldProject = $oldProjects[$projectID];
            $this->dao->update(TABLE_PROJECT)
                ->data($project)
                ->autoCheck()
                ->batchCheck($this->config->program->PRJEdit->requiredFields , 'notempty')
                ->check('name', 'unique', "id NOT " . helper::dbIN($data->projectIdList) . " and deleted='0'")
                ->where('id')->eq($projectID)
                ->exec();

            if(dao::isError()) die(js::error('project#' . $projectID . dao::getError(true)));
            if(!dao::isError())
            {
                /* Product belonging program set processing. */
                $oldTopPGM      = $this->getTopPGMByID($oldProject->parent);
                $newTopPGM      = $this->getTopPGMByID($project->parent);
                $linkedProducts = $this->dao->select('product')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($projectID)->fetchPairs();
                if($oldTopPGM != $newTopPGM)
                {
                    foreach($linkedProducts as $productID => $product)
                    {
                        $this->dao->update(TABLE_PRODUCT)->set('program')->eq($newTopPGM)->where('id')->eq((int)$productID)->exec();
                    }
                }
                if($oldProject->parent != $project->parent) $this->processNode($projectID, $project->parent, $oldProject->path, $oldProject->grade);
            }
            $allChanges[$projectID] = common::createChanges($oldProject, $project);
        }
        return $allChanges;
    }

    /**
     * Print datatable cell.
     *
     * @param  object $col
     * @param  object $project
     * @param  array  $users
     * @param  int    $programID
     * @access public
     * @return void
     */
    public function printCell($col, $project, $users, $programID = 0)
    {
        $canOrder     = common::hasPriv('program', 'PRJOrderUpdate');
        $canBatchEdit = common::hasPriv('program', 'PRJBatchEdit');
        $projectLink  = helper::createLink('program', 'index', "projectID=$project->id", '', '', $project->id);
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

            $PRJProgram = '';
            if($id == 'PRJProgram' and $project->parent != 0)
            {
                $programList  = $this->getPGMOption();
                $programIndex = $programID ? strpos($project->path, (string)$programID) : 0;
                $projectIndex = strpos($project->path, $project->id);
                $programPath  = explode(',' , substr($project->path, $programIndex, $projectIndex - $programIndex));
                foreach($programPath as $program)
                {
                    if($program) $PRJProgram .= '/' . zget($programList, $program);
                }
                $title = "title='{$PRJProgram}'";
            }
            if($id == 'PRJBudget')
            {
                $programBudget = in_array($this->app->getClientLang(), ['zh-cn','zh-tw']) && $project->budget >= 10000 ? number_format($project->budget / 10000, 1) . $this->lang->program->tenThousand : number_format((float)$project->budget, 1);
                $budgetTitle   = $project->budget != 0 ? zget($this->lang->program->currencySymbol, $project->budgetUnit) . ' ' . $programBudget : $this->lang->program->future;

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
                    if($project->model === 'waterfall') echo "<span class='project-type-label label label-outline label-warning'>{$this->lang->program->waterfall}</span>";
                    if($project->model === 'scrum')     echo "<span class='project-type-label label label-outline label-info'>{$this->lang->program->scrum}</span>";
                    break;
                case 'PRJProgram':
                    echo $PRJProgram;
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
                    echo $project->end == LONG_TIME ? $this->lang->program->PRJLongTime : $project->end;
                    break;
                case 'PRJStatus':
                    echo "<span class='status-task status-{$project->status}'> " . zget($this->lang->program->statusList, $project->status) . "</span>";
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
                    if($project->status == 'wait' || $project->status == 'suspended') common::printIcon('program', 'PRJStart', "projectID=$project->id", $project, 'list', 'play', '', 'iframe', true);
                    if($project->status == 'doing') common::printIcon('program', 'PRJClose', "projectID=$project->id", $project, 'list', 'off', '', 'iframe', true);
                    if($project->status == 'closed') common::printIcon('program', 'PRJActivate', "projectID=$project->id", $project, 'list', 'magic', '', 'iframe', true);

                    if(common::hasPriv('program','PRJSuspend') || (common::hasPriv('program','PRJClose') && $project->status != 'doing') || (common::hasPriv('program','PRJActivate') && $project->status != 'closed'))
                    {
                        echo "<div class='btn-group'>";
                        echo "<button type='button' class='btn icon-caret-down dropdown-toggle' data-toggle='context-dropdown' title='{$this->lang->more}' style='width: 16px; padding-left: 0px; border-radius: 4px;'></button>";
                        echo "<ul class='dropdown-menu pull-right text-center' role='menu' style='position: unset; min-width: auto; padding: 5px 6px;'>";
                        common::printIcon('program', 'PRJSuspend', "projectID=$project->id", $project, 'list', 'pause', '', 'iframe btn-action', true);
                        if($project->status != 'doing') common::printIcon('program', 'PRJClose', "projectID=$project->id", $project, 'list', 'off', '', 'iframe btn-action', true);
                        if($project->status != 'closed') common::printIcon('program', 'PRJActivate', "projectID=$project->id", $project, 'list', 'magic', '', 'iframe btn-action', true);
                        echo "</ul>";
                        echo "</div>";
                    }

                    $from       = $project->from == 'PRJ' ? 'PRJ' : 'pgmproject';
                    $openModule = $project->from == 'PRJ' ? 'project' : 'program';
                    common::printIcon('program', 'PRJEdit', "projectID=$project->id&from=$from", $project, 'list', 'edit', '', '', '', "data-group=$openModule", '', $project->id);
                    common::printIcon('program', 'PRJManageMembers', "projectID=$project->id", $project, 'list', 'group', '', '', '', '', '', $project->id);
                    common::printIcon('program', 'PRJGroup', "projectID=$project->id&programID=$programID", $project, 'list', 'lock', '', '', '', '', '', $project->id);

                    if(common::hasPriv('program','PRJManageProducts') || common::hasPriv('program','PRJWhitelist') || common::hasPriv('program','PRJDelete'))
                    {
                        echo "<div class='btn-group'>";
                        echo "<button type='button' class='btn dropdown-toggle' data-toggle='context-dropdown' title='{$this->lang->more}'><i class='icon-more-alt'></i></button>";
                        echo "<ul class='dropdown-menu pull-right text-center' role='menu'>";
                        common::printIcon('program', 'PRJManageProducts', "projectID=$project->id&programID=$programID&from=$from", $project, 'list', 'link', '', 'btn-action', '', "data-group=$openModule", '', $project->id);
                        common::printIcon('program', 'PRJWhitelist', "projectID=$project->id&programID=$programID&module=program&from=$from", $project, 'list', 'shield-check', '', 'btn-action', '', "data-group=$openModule", '', $project->id);
                        if(common::hasPriv('program','PRJDelete')) echo html::a(inLink("PRJDelete", "projectID=$project->id"), "<i class='icon-trash'></i>", 'hiddenwin', "class='btn btn-action' title='{$this->lang->program->PRJDelete}'");
                        echo "</ul>";
                        echo "</div>";
                    }
                    break;
            }
            echo '</td>';
        }
    }
}
