<?php
class programModel extends model
{
    /**
     * Get program overview for block.
     *
     * @param  varchar     $queryType byId|byStatus
     * @param  varchar|int $param
     * @param  varchar     $orderBy
     * @param  int         $limit
     * @access public
     * @return void
     */
    public function getProgramOverview($queryType = 'byStatus', $param = 'all', $orderBy = 'id_desc', $limit = 15)
    {
        $queryType = strtolower($queryType);
        $programs = $this->dao->select('*')->from(TABLE_PROGRAM)
            ->where('type')->eq('project')
            ->andWhere('deleted')->eq(0)
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->programs)->fi()
            ->beginIF($queryType == 'bystatus' and $param != 'all')->andWhere('status')->eq($param)->fi()
            ->beginIF($queryType == 'byid')->andWhere('id')->eq($param)->fi()
            ->orderBy($orderBy)
            ->limit($limit)
            ->fetchAll('id');

        if(empty($programs)) return array();
        $programIdList = array_keys($programs);

        $hours = $this->dao->select('PRJ,
            cast(sum(consumed) as decimal(10,2)) as consumed,
            cast(sum(estimate) as decimal(10,2)) as estimate')
            ->from(TABLE_TASK)
            ->where('PRJ')->in($programIdList)
            ->andWhere('deleted')->eq(0)
            ->andWhere('parent')->lt(1)
            ->groupBy('PRJ')
            ->fetchAll('PRJ');

        $teams = $this->dao->select('root, count(*) as count')->from(TABLE_TEAM)
            ->where('root')->in($programIdList)
            ->groupBy('root')
            ->fetchAll('root');

        $leftTasks = $this->dao->select('PRJ, count(*) as leftTasks')->from(TABLE_TASK)
            ->where('PRJ')->in($programIdList)
            ->andWhere('deleted')->eq(0)
            ->andWhere('status')->in('wait,doing,pause')
            ->groupBy('PRJ')
            ->fetchAll('PRJ');

        $allStories = $this->dao->select('PRJ, count(*) as allStories')->from(TABLE_STORY)
            ->where('PRJ')->in($programIdList)
            ->andWhere('deleted')->eq(0)
            ->andWhere('status')->ne('draft')
            ->groupBy('PRJ')
            ->fetchAll('PRJ');

        $doneStories = $this->dao->select('PRJ, count(*) as doneStories')->from(TABLE_STORY)
            ->where('PRJ')->in($programIdList)
            ->andWhere('deleted')->eq(0)
            ->andWhere('status')->eq('closed')
            ->andWhere('closedReason')->eq('done')
            ->groupBy('PRJ')
            ->fetchAll('PRJ');

        $leftStories = $this->dao->select('PRJ, count(*) as leftStories')->from(TABLE_STORY)
            ->where('PRJ')->in($programIdList)
            ->andWhere('deleted')->eq(0)
            ->andWhere('status')->eq('active')
            ->groupBy('PRJ')
            ->fetchAll('PRJ');

        $leftBugs = $this->dao->select('PRJ, count(*) as leftBugs')->from(TABLE_BUG)
            ->where('PRJ')->in($programIdList)
            ->andWhere('deleted')->eq(0)
            ->andWhere('status')->eq('active')
            ->groupBy('PRJ')
            ->fetchAll('PRJ');

        $allBugs = $this->dao->select('PRJ, count(*) as allBugs')->from(TABLE_BUG)
            ->where('PRJ')->in($programIdList)
            ->andWhere('deleted')->eq(0)
            ->groupBy('PRJ')
            ->fetchAll('PRJ');

        $doneBugs = $this->dao->select('PRJ, count(*) as doneBugs')->from(TABLE_BUG)
            ->where('PRJ')->in($programIdList)
            ->andWhere('deleted')->eq(0)
            ->andWhere('status')->eq('resolved')
            ->groupBy('PRJ')
            ->fetchAll('PRJ');

        foreach($programs as $programID => $program)
        {
            $program->teamCount   = isset($teams[$programID]) ? $teams[$programID]->count : 0;
            $program->consumed    = isset($hours[$programID]) ? $hours[$programID]->consumed : 0;
            $program->estimate    = isset($hours[$programID]) ? $hours[$programID]->estimate : 0;
            $program->leftTasks   = isset($leftTasks[$programID]) ? $leftTasks[$programID]->leftTasks : 0;
            $program->allStories  = isset($allStories[$programID]) ? $allStories[$programID]->allStories : 0;
            $program->doneStories = isset($doneStories[$programID]) ? $doneStories[$programID]->doneStories : 0;
            $program->leftStories = isset($leftStories[$programID]) ? $leftStories[$programID]->leftStories : 0;
            $program->leftBugs    = isset($leftBugs[$programID]) ? $leftBugs[$programID]->leftBugs : 0;
            $program->allBugs     = isset($allBugs[$programID]) ? $allBugs[$programID]->allBugs : 0;
            $program->doneBugs    = isset($doneBugs[$programID]) ? $doneBugs[$programID]->doneBugs : 0;
        }

        return $programs;
    }

    /**
     * Get program stats.
     *
     * @param  string    $status
     * @param  int       $itemCounts
     * @param  string    $orderBy
     * @param  int       $pager
     * @access public
     * @return void
     */
    public function getProgramStats($status = 'undone', $itemCounts = 30, $orderBy = 'order_desc', $pager = null)
    {
        /* Init vars. */
        $this->loadModel('project');
        $programs = $this->getPRJList(0, $status, $orderBy, $pager);

        if(empty($programs)) return array();

        $programIdList = array_keys($programs);
        $programs = $this->dao->select('*')->from(TABLE_PROGRAM)
            ->where('id')->in($programIdList)
            ->orderBy($orderBy)
            ->limit($itemCounts)
            ->fetchAll('id');

        $teams = $this->dao->select('root, count(*) as count')->from(TABLE_TEAM)
            ->where('root')->in($programIdList)
            ->groupBy('root')
            ->fetchAll('root');

        $estimates = $this->dao->select('PRJ, sum(estimate) as estimate')->from(TABLE_TASK)
            ->where('PRJ')->in($programIdList)
            ->andWhere('deleted')->eq(0)
            ->andWhere('parent')->lt(1)
            ->groupBy('PRJ')
            ->fetchAll('PRJ');

        foreach($programs as $programID => $program)
        {
            $orderBy = $program->model == 'waterfall' ? 'id_asc' : 'id_desc';
            $program->projects   = $this->project->getProjectStats($status, 0, 0, $itemCounts, $orderBy, $pager, $programID);
            $program->teamCount  = isset($teams[$programID]) ? $teams[$programID]->count : 0;
            $program->estimate   = isset($estimates[$programID]) ? $estimates[$programID]->estimate : 0;
            $program->parentName = $this->project->getProjectParentName($program->parent);
        }

        return $programs;
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
     * Get program by id.
     *
     * @param  int  $programID
     * @access public
     * @return array
     */
    public function getPGMByID($programID = 0)
    {
        return $this->dao->select('*')->from(TABLE_PROGRAM)->where('id')->eq($programID)->fetch();
    }

    /**
     * Get program list.
     *
     * @param  varchar $status
     * @param  varchar $orderBy
     * @param  object  $pager
     * @param  bool    $includeCat
     * @access public
     * @return array
     */
    public function getPGMList($status = 'all', $orderBy = 'id_desc', $pager = NULL)
    {
        return $this->dao->select('*')->from(TABLE_PROGRAM)
            ->where('type')->in('program,project')
            ->andWhere('deleted')->eq(0)
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->programs)->fi()
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
     * @return void
     */
    public function PGMCreate()
    {
        $program = fixer::input('post')
            ->setDefault('status', 'wait')
            ->setDefault('openedBy', $this->app->user->account)
            ->setDefault('end', '')
            ->setDefault('parent', 0)
            ->setDefault('openedDate', helper::now())
            ->setIF($this->post->acl == 'open', 'whitelist', '')
            ->add('type', 'program')
            ->join('whitelist', ',')
            ->cleanInt('budget')
            ->stripTags($this->config->program->editor->pgmcreate['id'], $this->config->allowedTags)
            ->get();

        if($program->parent)
        {
            $parentProgram = $this->dao->select('*')->from(TABLE_PROGRAM)->where('id')->eq($program->parent)->fetch();
            if($parentProgram)
            {
                /* Child program begin cannot less than parent. */
                if($program->begin < $parentProgram->begin) dao::$errors['begin'] = sprintf($this->lang->program->beginLetterParent, $parentProgram->begin);

                /* When parent set end then child program end cannot greater than parent. */
                if($parentProgram->end != '0000-00-00' and $program->end > $parentProgram->end) dao::$errors['end'] = sprintf($this->lang->program->endGreaterParent, $parentProgram->end);

                /* When parent set end then child program cannot set longTime. */
                if(empty($program->end) and $this->post->longTime and $parentProgram->end != '0000-00-00') dao::$errors['end'] = sprintf($this->lang->program->endGreaterParent, $parentProgram->end);

                if(dao::isError()) return false;
            }
        }

        $program = $this->loadModel('file')->processImgURL($program, $this->config->program->editor->pgmcreate['id'], $this->post->uid);
        $this->dao->insert(TABLE_PROGRAM)->data($program)
            ->autoCheck()
            ->batchcheck($this->config->program->PGMCreate->requiredFields, 'notempty')
            ->check('name', 'unique', "deleted='0'")
            ->check('code', 'unique', "deleted='0'")
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
                $groupPriv->program = $programID;
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
     * @return array
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
            ->join('whitelist', ',')
            ->stripTags($this->config->program->editor->pgmedit['id'], $this->config->allowedTags)
            ->remove('uid')
            ->get();

        $program  = $this->loadModel('file')->processImgURL($program, $this->config->program->editor->pgmedit['id'], $this->post->uid);
        $children = $this->getChildren($programID);

        if($children > 0)
        {
            $minChildBegin = $this->dao->select('min(begin) as minBegin')->from(TABLE_PROGRAM)->where('id')->ne($programID)->andWhere('deleted')->eq(0)->andWhere('path')->like("%,{$programID},%")->fetch('minBegin');
            $maxChildEnd   = $this->dao->select('max(end) as maxEnd')->from(TABLE_PROGRAM)->where('id')->ne($programID)->andWhere('deleted')->eq(0)->andWhere('path')->like("%,{$programID},%")->andWhere('end')->ne('0000-00-00')->fetch('maxEnd');

            if($minChildBegin and $program->begin > $minChildBegin) dao::$errors['begin'] = sprintf($this->lang->program->beginGreateChild, $minChildBegin);
            if($maxChildEnd   and $program->end   < $maxChildEnd and !$this->post->longTime) dao::$errors['end'] = sprintf($this->lang->program->endLetterChild, $maxChildEnd);
        }

        if($program->parent)
        {
            $parentProgram = $this->dao->select('*')->from(TABLE_PROGRAM)->where('id')->eq($program->parent)->fetch();
            if($parentProgram)
            {
                if($program->begin < $parentProgram->begin) dao::$errors['begin'] = sprintf($this->lang->program->beginLetterParent, $parentProgram->begin);
                if($parentProgram->end != '0000-00-00' and $program->end > $parentProgram->end) dao::$errors['end'] = sprintf($this->lang->program->endGreaterParent, $parentProgram->end);
                if(empty($program->end) and $this->post->longTime and $parentProgram->end != '0000-00-00') dao::$errors['end'] = sprintf($this->lang->program->endGreaterParent, $parentProgram->end);
            }
        }
        if(dao::isError()) return false;

        $this->dao->update(TABLE_PROGRAM)->data($program)
            ->autoCheck($skipFields = 'begin,end')
            ->batchcheck($this->config->program->PGMEdit->requiredFields, 'notempty')
            ->checkIF($program->begin != '', 'begin', 'date')
            ->checkIF($program->end != '', 'end', 'date')
            ->checkIF($program->end != '', 'end', 'gt', $program->begin)
            ->check('name', 'unique', "id!=$programID and deleted='0'")
            ->check('code', 'unique', "id!=$programID and deleted='0'")
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
     * @param  int    $programID
     * @access private
     * @return void
     */
    public function getPGMCommonAction($programID = 0)
    {
        $output  = "<div class='btn-group header-angle-btn' id='pgmCommonAction'><button data-toggle='dropdown' type='button' class='btn' title='{$this->lang->program->PGMCommon}'>{$this->lang->program->PGMCommon} <span class='caret'></span></button>";
        $output .= '<ul class="dropdown-menu">';
        $output .= '<li>' . html::a(helper::createLink('program', 'pgmindex'), "<i class='icon icon-home'></i> " . $this->lang->program->PGMIndex) . '</li>';
        $output .= '<li>' . html::a(helper::createLink('program', 'pgmbrowse'), "<i class='icon icon-cards-view'></i> " . $this->lang->program->PGMBrowse) . '</li>';
        $output .= '<li>' . html::a(helper::createLink('program', 'pgmcreate'), "<i class='icon icon-plus'></i> " . $this->lang->program->PGMCreate) . '</li>';
        $output .= '</ul>';
        $output .= "</div>";

        return $output;
    }

    /*
     * Get program swapper.
     *
     * @param  int     $programID
     * @access private
     * @return void
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
        $output  = "<div class='btn-group header-angle-btn' id='swapper'><button data-toggle='dropdown' type='button' class='btn' id='currentItem' title='{$currentProgramName}'>{$currentProgramName} <span class='caret'></span></button><div id='dropMenu' class='dropdown-menu search-list' data-ride='searchList' data-url='$dropMenuLink'>";
        $output .= '<div class="input-control search-box has-icon-left has-icon-right search-example"><input type="search" class="form-control search-input" /><label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label><a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a></div>'; $output .= "</div></div>";

        return $output;
    }

    /**
     * Get the tree menu of program.
     *
     * @param  int    $programID
     * @param  int    $from
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
            ->andWhere('type')->in('program,project')
            ->andWhere('id')->in($this->app->user->view->programs . ',' . $this->app->user->view->projects)
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
            $link = $from == 'program' ? helper::createLink('program', 'pgmview', "programID=$program->id") : helper::createLink('product', 'all', "programID=$program->id" . $vars);
            $linkHtml = html::a($link, "<i class='icon icon-folder'></i> " . $program->name, '', "id='program$program->id' class='text-ellipsis' title=$program->name");

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
        $lastMenu    = "<ul class='tree' data-ride='tree' id='programTree' data-name='tree-program'>{$programMenu}</ul>\n";

        return $lastMenu;
    }

    /**
     * Get top program pairs.
     *
     * @access public
     * @return void
     */
    public function getTopPGMPairs($model = '')
    {
        return $this->dao->select('id,name')->from(TABLE_PROGRAM)
            ->where('type')->eq('program')
            ->andWhere('grade')->eq(1)
            ->andWhere('deleted')->eq(0)
            ->beginIF($model)->andWhere('model')->eq($model)->fi()
            ->orderBy('`order`')
            ->fetchPairs();
    }

    /**
     * Get children by program id.
     *
     * @param  int     $programID
     * @access public
     * @return void
     */
    public function getChildren($programID = 0)
    {
        return $this->dao->select('count(*) as count')->from(TABLE_PROGRAM)->where('parent')->eq($programID)->fetch('count');
    }

    /**
     * Get stakeholders by program id.
     *
     * @param  string $orderBy
     * @param  int     $programID
     * @access public
     * @return void
     */
    public function getStakeholders($programID = 0, $orderBy, $pager = null)
    {
        return $this->dao->select('t2.account,t2.realname,t2.role,t2.qq,t2.mobile,t2.phone,t2.weixin,t2.email,t1.id')->from(TABLE_STAKEHOLDER)->alias('t1')
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
     * @param  string $orderBy
     * @access public
     * @return void
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

        if(!$this->server->http_referer) die(js::locate(helper::createLink('program', 'browse')));

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

        if($program->type == 'program' && ($action == 'prjstart' || $action == 'prjsuspend')) return false;

        if($action == 'pgmclose')    return $program->status != 'closed';
        if($action == 'pgmactivate') return $program->status == 'done' or $program->status == 'closed';

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
            ->andWhere('deleted')->eq(0)
            ->beginIF($model)->andWhere('model')->eq($model)->fi()
            ->orderBy('grade desc, `order`')
            ->fetchAll('id');

        $treeMenu = array();
        foreach($modules as $module)
        {
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

    /*
     * Get project swapper.
     *
     * @access public
     * @return void
     */
    public function printPRJCommonAction()
    {
        $output  = "<div class='btn-group header-angle-btn' id='pgmCommonAction'><button data-toggle='dropdown' type='button' class='btn' id='currentItem' title='{$this->lang->program->PRJAll}'>{$this->lang->program->PRJAll} <span class='caret'></span></button>";
        $output .= '<ul class="dropdown-menu">';
        $output .= '<li>' . html::a(helper::createLink('program', 'prjbrowse'), "<i class='icon icon-cards-view'></i> " . $this->lang->program->PRJAll) . '</li>';
        $output .= '<li>' . html::a(helper::createLink('program', 'createGuide'), "<i class='icon icon-plus'></i> " . $this->lang->program->PRJCreate, '', 'data-toggle="modal" data-target="#guideDialog"') . '</li>';
        $output .= '</ul>';
        $output .= "</div>";
        echo $output;
    }

    /*
     * Get project swapper.
     *
     * @param  int     $projectID
     * @param  string  $currentModule
     * @param  string  $currentMethod
     * @access public
     * @return void
     */
    public function getPRJSwitcher($projectID, $currentModule, $currentMethod)
    {
        $this->printPRJCommonAction();
        if($currentModule == 'program' && $currentMethod != 'index') return;

        $this->loadModel('project');
        $currentProjectName = $this->lang->program->common;
        if($projectID)
        {
            $currentProject     = $this->project->getById($projectID);
            $currentProjectName = $currentProject->name;
        }

        $dropMenuLink = helper::createLink('program', 'ajaxGetPRJDropMenu', "objectID=$projectID&module=$currentModule&method=$currentMethod");
        $output  = "<div class='btn-group header-angle-btn' id='swapper'><button data-toggle='dropdown' type='button' class='btn' id='currentItem' title='{$currentProjectName}'>{$currentProjectName} <span class='caret'></span></button><div id='dropMenu' class='dropdown-menu search-list' data-ride='searchList' data-url='$dropMenuLink'>";
        $output .= '<div class="input-control search-box has-icon-left has-icon-right search-example"><input type="search" class="form-control search-input" /><label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label><a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a></div>';
        $output .= "</div></div>";

        return $output;
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
            $program = $this->getPRJByID($programID);
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
        if(!$projectID) return false;

        $project = $this->dao->findById($projectID)->from(TABLE_PROJECT)->fetch();
        if($project->end == '0000-00-00') $project->end = '';
        return $project;
    }

    /**
     * Get project name.
     *
     * @param  int    $projectID
     * @access public
     * @return object
     */
    public function getPRJPairs($programID = 0)
    {
        return $this->dao->select('id, name')->from(TABLE_PROJECT)
            ->where('type')->eq('project')
            ->andWhere('deleted')->eq(0)
            ->beginIF($programID)->andWhere('parent')->eq($programID)->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->projects)->fi()
            ->fetchPairs();
    }

    /**
     * Get recent stage and sprint.
     *
     * @access public
     * @return object
     */
    public function getPRJRecent()
    {
        return $this->dao->select('id,parent,name')->from(TABLE_PROJECT)
            ->where('type')->in('stage,sprint')
            ->beginIF(!$this->app->user->admin && $this->app->user->view->projects)->andWhere('parent')->in($this->app->user->view->projects)->fi()
            ->andWhere('status')->ne('status')
            ->andWhere('deleted')->eq('0')
            ->orderBy('id_desc')
            ->limit('5,' . $this->config->program->PRJRecentQuantity)
            ->fetchAll();
    }

    /**
     * Build the query.
     *
     * @param  int    $projectID
     * @access public
     * @return object
     */
    public function buildPRJMenuQuery($projectID = 0)
    {
        $path    = '';
        $program = $this->getPRJByID($projectID);
        if($program) $path = $program->path;

        return $this->dao->select('*')->from(TABLE_PROJECT)
            ->where('type')->in('project,program')
            ->andWhere('status')->ne('closed')
            ->andWhere('deleted')->eq('0')
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->programs . ',' . $this->app->user->view->projects)->fi()
            ->beginIF($projectID > 0)->andWhere('path')->like($path . '%')->fi()
            ->orderBy('grade desc, `order`')
            ->get();
    }

    /**
     * Get project pairs by model.
     *
     * @param  string $model
     * @param  int    $programID
     * @access public
     * @return void
     */
    public function getPRJPairsByTemplate($model, $programID = 0)
    {
        return $this->dao->select('id, name')->from(TABLE_PROGRAM)
            ->where('type')->eq('project')
            ->beginIF($programID)->andWhere('parent')->eq($programID)->fi()
            ->andWhere('model')->eq($model)
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
     * @access public
     * @return string
     */
    public function getPRJTreeMenu($projectID = 0, $userFunc, $param = 0)
    {
        $projectMenu = array();
        $stmt        = $this->dbh->query($this->buildPRJMenuQuery($projectID));

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
     * @param  int    $project
     * @access public
     * @return string
     */
    public function createPRJManageLink($project)
    {
        $link = $project->type == 'program' ? helper::createLink('program', 'PRJbrowse', "programID={$project->id}") : helper::createLink('program', 'index', "projectID={$project->id}", '', '', $project->id);
        $icon = $project->type == 'program' ? '<i class="icon icon-folder"></i> ' : '<i class="icon icon-file"></i> ';
        return html::a($link, $icon . $project->name, '_self', "id=project{$project->id} title='{$project->name}' class='text-ellipsis'");
    }

    /**
     * Create a project.
     *
     * @access public
     * @return void
     */
    public function PRJCreate()
    {
        $project = fixer::input('post')
            ->setDefault('status', 'wait')
            ->setIF($this->post->longTime == 1, 'end', '')
            ->setIF($this->post->longTime == 1, 'days', 0)
            ->setIF($this->post->acl      == 'open', 'whitelist', '')
            ->setDefault('openedBy', $this->app->user->account)
            ->setDefault('end', '')
            ->setDefault('openedDate', helper::now())
            ->setDefault('team', substr($this->post->name, 0, 30))
            ->add('type', 'project')
            ->join('whitelist', ',')
            ->cleanInt('budget')
            ->stripTags($this->config->program->editor->prjcreate['id'], $this->config->allowedTags)
            ->remove('longTime')
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
        }

        $requiredFields = $this->config->program->PRJCreate->requiredFields;
        if($this->post->longTime) $requiredFields = trim(str_replace(',end,', ',', ",{$requiredFields},"), ',');

        $project = $this->loadModel('file')->processImgURL($project, $this->config->program->editor->prjcreate['id'], $this->post->uid);
        $this->dao->insert(TABLE_PROJECT)->data($project)
            ->autoCheck()
            ->batchcheck($requiredFields, 'notempty')
            ->check('name', 'unique', "deleted='0'")
            ->check('code', 'unique', "deleted='0'")
            ->exec();

        /* Add the creater to the team. */
        if(!dao::isError())
        {
            $projectID = $this->dao->lastInsertId();
            $whitelist = explode(',', $project->whitelist);
            $this->loadModel('personnel')->updateWhitelist($whitelist, 'project', $projectID);
            if($project->acl != 'open') $this->loadModel('user')->updateUserView($projectID, 'project');

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
        $oldProject = $this->dao->findById($projectID)->from(TABLE_PROJECT)->fetch();

        $project = fixer::input('post')
            ->setDefault('team', substr($this->post->name, 0, 30))
            ->setIF($this->post->longTime == 1, 'end', '')
            ->setIF($this->post->longTime == 1, 'days', 0)
            ->setIF($this->post->begin == '0000-00-00', 'begin', '')
            ->setIF($this->post->end   == '0000-00-00', 'end', '')
            ->setIF($this->post->acl   == 'open', 'whitelist', '')
            ->join('whitelist', ',')
            ->stripTags($this->config->program->editor->prjedit['id'], $this->config->allowedTags)
            ->remove('longTime')
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
        }

        $project = $this->loadModel('file')->processImgURL($project, $this->config->program->editor->prjedit['id'], $this->post->uid);

        $requiredFields = $this->config->program->PRJEdit->requiredFields;
        if($this->post->longTime) $requiredFields = trim(str_replace(',end,', ',', ",{$requiredFields},"), ',');

        $this->dao->update(TABLE_PROJECT)->data($project)
            ->autoCheck($skipFields = 'begin,end')
            ->batchcheck($requiredFields, 'notempty')
            ->checkIF($project->begin != '', 'begin', 'date')
            ->checkIF($project->end != '', 'end', 'date')
            ->checkIF($project->end != '', 'end', 'gt', $project->begin)
            ->check('name', 'unique', "id!=$projectID and deleted='0'")
            ->check('code', 'unique', "id!=$projectID and deleted='0'")
            ->where('id')->eq($projectID)
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

    /**
     * Get project stats.
     *
     * @param  int    $programID
     * @param  string $browseType
     * @param  string $queryID
     * @param  string $orderBy
     * @param  object $pager
     * @param  int    $programTitle
     * @param  int    $PRJMine
     * @access public
     * @return void
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
            $hour->progress      = $hour->totalReal ? round($hour->totalConsumed / $hour->totalReal, 3) * 100 : 0;
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
        $canOrder = common::hasPriv('program', 'PRJOrderUpdate');
        $account  = $this->app->user->account;
        $id       = $col->id;

        if($col->show)
        {
            $class = "c-$id";
            $title = '';

            if($id == 'idAB') $class .= ' cell-id';

            if($id == 'PRJName')
            {
                $class .= ' text-left';
                $title  = "title='{$project->name}'";
            }

            echo "<td class='" . $class . "' $title>";
            switch($id)
            {
                case 'idAB':
                    printf('%03d', $project->id);
                    break;
                case 'PRJCode':
                    echo $project->code;
                    break;
                case 'PRJName':
                    echo html::a(helper::createLink('program', 'index', "projectID=$project->id", '', '', $project->id), $project->name);
                    break;
                case 'PRJModel':
                    echo zget($this->lang->program->modelList, $project->model);
                    break;
                case 'PRJPM':
                    echo zget($users, $project->PM);
                    break;
                case 'begin':
                    echo $project->begin;
                    break;
                case 'end':
                    echo $project->end;
                    break;
                case 'PRJStatus':
                    echo zget($this->lang->program->statusList, $project->status);
                    break;
                case 'PRJBudget':
                    echo $project->budget . zget($this->lang->program->unitList, $project->budgetUnit);
                    break;
                case 'teamCount':
                    echo $project->teamCount;
                    break;
                case 'PRJEstimate':
                    echo $project->hours->totalEstimate;
                    break;
                case 'PRJConsume':
                    echo $project->hours->totalConsumed;
                    break;
                case 'PRJSurplus':
                    echo $project->hours->totalLeft;
                    break;
                case 'PRJProgress':
                    echo "<span class='pie-icon' data-percent='{$project->hours->progress}' data-border-color='#ddd' data-back-color='#f1f1f1'></span> {$project->hours->progress}%";
                    break;
                case 'actions':
                    common::printIcon('program', 'PRJGroup', "projectID=$project->id&programID=$programID", $project, 'list', 'lock');
                    common::printIcon('program', 'PRJManageMembers', "programID=$project->id", $project, 'list', 'persons');
                    common::printIcon('program', 'PRJWhitelist', "projectID=$project->id&programID=$programID", $project, 'list', 'group');
                    common::printIcon('program', 'PRJStart', "programID=$project->id", $project, 'list', 'start', '', 'iframe', true);
                    common::printIcon('program', 'PRJActivate', "programID=$project->id", $project, 'list', 'magic', '', 'iframe', true);
                    common::printIcon('program', 'PRJSuspend', "programID=$project->id", $project, 'list', 'pause', '', 'iframe', true);
                    common::printIcon('program', 'PRJClose', "programID=$project->id", $project, 'list', 'off', '', 'iframe', true);
                    if(common::hasPriv('program', 'PRJEdit')) echo html::a(helper::createLink("program", "PRJEdit", "programID=$project->id"), "<i class='icon-edit'></i>", '', "class='btn' title='{$this->lang->edit}'");
                    common::printIcon('program', 'PRJDelete', "projectID=$project->id", $project, 'list', 'trash', 'hiddenwin', '', true);
                    break;
            }
        }
    }
}
