<?php
class programModel extends model
{
    /**
     * Get program list.
     *
     * @param  varchar $status
     * @param  varchar $orderBy
     * @param  object  $pager
     * @param  bool    $includeCat
     * @param  bool    $mine
     * @access public
     * @return array
     */
    public function getList($status = 'all', $orderBy = 'id_desc', $pager = NULL, $includeCat = false, $mine = false)
    {
        return $this->dao->select('*')->from(TABLE_PROJECT)
            ->where('type')->eq('program')
            ->andWhere('deleted')->eq(0)
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->programs)->fi()
            ->beginIF($status != 'all')->andWhere('status')->eq($status)->fi()
            ->beginIF($this->cookie->mine or $mine)
            ->andWhere('openedBy', true)->eq($this->app->user->account)
            ->orWhere('PM')->eq($this->app->user->account)
            ->markRight(1)
            ->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get program pairs.
     *
     * @access public
     * @return void
     */
    public function getPairs()
    {
        return $this->dao->select('id, name')->from(TABLE_PROJECT)
            ->where('type')->eq('program')
            ->andWhere('deleted')->eq(0)
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->programs)->fi()
            ->fetchPairs();
    }

    /**
     * Get program pairs by template.
     *
     * @param  varchar $template
     * @access public
     * @return void
     */
    public function getPairsByTemplate($model)
    {
        return $this->dao->select('id, name')->from(TABLE_PROJECT)
            ->where('type')->eq('program')
            ->andWhere('model')->eq($model)
            ->andWhere('deleted')->eq(0)
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->programs)->fi()
            ->fetchPairs();
    }

    /**
     * Get program overview for block.
     *
     * @param  varchar     $queryType byId|byStatus
     * @param  varchar|int $param
     * @param  varchar     $orderBy
     * @param  int         $limit
     * @param  int         $programID
     * @access public
     * @return void
     */
    public function getProgramOverview($queryType = 'byStatus', $param = 'all', $orderBy = 'id_desc', $limit = 15)
    {
        $queryType = strtolower($queryType);
        $programs = $this->dao->select('*')->from(TABLE_PROJECT)
            ->where('type')->eq('program')
            ->andWhere('deleted')->eq(0)
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->programs)->fi()
            ->beginIF($queryType == 'bystatus' and $param != 'all')->andWhere('status')->eq($param)->fi()
            ->beginIF($queryType == 'byid')->andWhere('id')->eq($param)->fi()
            ->orderBy($orderBy)
            ->limit($limit)
            ->fetchAll('id');

        if(empty($programs)) return array();
        $programIdList = array_keys($programs);

        $hours = $this->dao->select('program, 
            cast(sum(consumed) as decimal(10,2)) as consumed, 
            cast(sum(estimate) as decimal(10,2)) as estimate')
            ->from(TABLE_TASK)
            ->where('program')->in($programIdList)
            ->andWhere('deleted')->eq(0)
            ->andWhere('parent')->lt(1)
            ->groupBy('program')
            ->fetchAll('program');

        $teams = $this->dao->select('root, count(*) as count')->from(TABLE_TEAM)
            ->where('root')->in($programIdList)
            ->groupBy('root')
            ->fetchAll('root');

        $leftTasks = $this->dao->select('program, count(*) as leftTasks')->from(TABLE_TASK)
            ->where('program')->in($programIdList)
            ->andWhere('deleted')->eq(0)
            ->andWhere('status')->in('wait,doing,pause')
            ->groupBy('program')
            ->fetchAll('program');

        $allStories = $this->dao->select('program, count(*) as allStories')->from(TABLE_STORY)
            ->where('program')->in($programIdList)
            ->andWhere('deleted')->eq(0)
            ->andWhere('status')->ne('draft')
            ->groupBy('program')
            ->fetchAll('program');

        $doneStories = $this->dao->select('program, count(*) as doneStories')->from(TABLE_STORY)
            ->where('program')->in($programIdList)
            ->andWhere('deleted')->eq(0)
            ->andWhere('status')->eq('closed')
            ->andWhere('closedReason')->eq('done')
            ->groupBy('program')
            ->fetchAll('program');

        $leftStories = $this->dao->select('program, count(*) as leftStories')->from(TABLE_STORY)
            ->where('program')->in($programIdList)
            ->andWhere('deleted')->eq(0)
            ->andWhere('status')->eq('active')
            ->groupBy('program')
            ->fetchAll('program');

        $leftBugs = $this->dao->select('program, count(*) as leftBugs')->from(TABLE_BUG)
            ->where('program')->in($programIdList)
            ->andWhere('deleted')->eq(0)
            ->andWhere('status')->eq('active')
            ->groupBy('program')
            ->fetchAll('program');

        $allBugs = $this->dao->select('program, count(*) as allBugs')->from(TABLE_BUG)
            ->where('program')->in($programIdList)
            ->andWhere('deleted')->eq(0)
            ->groupBy('program')
            ->fetchAll('program');

        $doneBugs = $this->dao->select('program, count(*) as doneBugs')->from(TABLE_BUG)
            ->where('program')->in($programIdList)
            ->andWhere('deleted')->eq(0)
            ->andWhere('status')->eq('resolved')
            ->groupBy('program')
            ->fetchAll('program');

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
     * @param  string $status
     * @param  int    $itemCounts
     * @param  string $orderBy
     * @param  int    $pager
     * @access public
     * @return void
     */
    public function getProgramStats($status = 'undone', $itemCounts = 30, $orderBy = 'order_desc', $pager = null)
    {
        /* Init vars. */
        $this->loadModel('project');
        $programs = $this->getList($status, $orderBy, $pager);

        if(empty($programs)) return array();

        $programIdList = array_keys($programs);
        $programs = $this->dao->select('*')->from(TABLE_PROJECT)
            ->where('id')->in($programIdList)
            ->orderBy($orderBy)
            ->limit($itemCounts)
            ->fetchAll('id');

        $teams = $this->dao->select('root, count(*) as count')->from(TABLE_TEAM)
            ->where('root')->in($programIdList)
            ->groupBy('root')
            ->fetchAll('root');

        $estimates = $this->dao->select('program, sum(estimate) as estimate')->from(TABLE_TASK)
            ->where('program')->in($programIdList)
            ->andWhere('deleted')->eq(0)
            ->andWhere('parent')->lt(1)
            ->groupBy('program')
            ->fetchAll('program');

        foreach($programs as $programID => $program)
        {
            $orderBy = $program->template == 'waterfall' ? 'id_asc' : 'id_desc';
            $program->projects   = $this->project->getProjectStats($status, 0, 0, $itemCounts, $orderBy, $pager, $programID);
            $program->teamCount  = isset($teams[$programID]) ? $teams[$programID]->count : 0;
            $program->estimate   = isset($estimates[$programID]) ? $estimates[$programID]->estimate : 0;
            $program->parentName = $this->project->getProjectParentName($program->parent);
        }

        return $programs;
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
     * Create a program.
     *
     * @access private
     * @return void
     */
    public function create()
    {
        $project = fixer::input('post')
            ->setDefault('status', 'wait')
            ->add('type', 'program')
            ->setIF($this->post->acl != 'custom', 'whitelist', '')
            ->setDefault('openedBy', $this->app->user->account)
            ->setDefault('end', '')
            ->setDefault('openedDate', helper::now())
            ->setDefault('team', substr($this->post->name, 0, 30))
            ->join('whitelist', ',')
            ->cleanInt('budget')
            ->stripTags($this->config->program->editor->create['id'], $this->config->allowedTags)
            ->remove('products, workDays, delta, branch, uid, plans, longTime')
            ->get();

        if($project->parent)
        {
            $parentProgram = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($project->parent)->fetch();
            if($parentProgram)
            {
                /* Child project begin cannot less than parent. */
                if($project->begin < $parentProgram->begin) dao::$errors['begin'] = sprintf($this->lang->program->beginLetterParent, $parentProgram->begin);
                /* When parent set end then child project end cannot greater than parent. */
                if($parentProgram->end != '0000-00-00' and $project->end > $parentProgram->end) dao::$errors['end'] = sprintf($this->lang->program->endGreaterParent, $parentProgram->end);
                /* When parent set end then child project cannot set longTime. */
                if(empty($project->end) and $this->post->longTime and $parentProgram->end != '0000-00-00') dao::$errors['end'] = sprintf($this->lang->program->endGreaterParent, $parentProgram->end);

                if(dao::isError()) return false;
            }
        }

        $requiredFields = $this->config->program->create->requiredFields;
        if($this->post->longTime) $requiredFields = trim(str_replace(',end,', ',', ",{$requiredFields},"), ',');

        $project = $this->loadModel('file')->processImgURL($project, $this->config->program->editor->create['id'], $this->post->uid);
        $this->dao->insert(TABLE_PROJECT)->data($project)
            ->autoCheck()
            ->batchcheck($requiredFields, 'notempty')
            ->check('name', 'unique', "deleted='0'")
            ->check('code', 'unique', "deleted='0'")
            ->exec();

        /* Add the creater to the team. */
        if(!dao::isError())
        {
            $programID = $this->dao->lastInsertId();
            $today     = helper::today();
            if($project->acl != 'open') $this->loadModel('user')->updateUserView($programID, 'program');

            /* Save order. */
            $this->dao->update(TABLE_PROJECT)->set('`order`')->eq($programID * 5)->where('id')->eq($programID)->exec();
            $this->file->updateObjectID($this->post->uid, $programID, 'project');

            if($project->parent > 0) $this->dao->update(TABLE_PROJECT)->set('isCat')->eq(1)->where('id')->eq($project->parent)->exec();
            $this->setTreePath($programID);

            /* Add program admin.*/
            $groupPriv = $this->dao->select('t1.*')->from(TABLE_USERGROUP)->alias('t1')
                ->leftJoin(TABLE_GROUP)->alias('t2')->on('t1.group = t2.id')
                ->where('t1.account')->eq($this->app->user->account)
                ->andWhere('t2.role')->eq('PRJadmin')
                ->fetch();
            if(!empty($groupPriv))
            {
                $newProgram = $groupPriv->program . ",$programID";
                $this->dao->update(TABLE_USERGROUP)->set('program')->eq($newProgram)->where('account')->eq($groupPriv->account)->andWhere('`group`')->eq($groupPriv->group)->exec();
            }
            else
            {
                $PRJadminID = $this->dao->select('id')->from(TABLE_GROUP)->where('role')->eq('PRJadmin')->fetch('id');
                $groupPriv  = new stdclass();
                $groupPriv->account = $this->app->user->account;
                $groupPriv->group   = $PRJadminID;
                $groupPriv->program = $programID;
                $this->dao->insert(TABLE_USERGROUP)->data($groupPriv)->exec();
            }

            if($project->template == 'waterfall')
            {
                $product = new stdclass();
                $product->name        = $project->name;
                $product->program     = $programID;
                $product->status      = 'normal';
                $product->createdBy   = $this->app->user->account;
                $product->createdDate = helper::now();

                $this->dao->insert(TABLE_PRODUCT)->data($product)->exec();

                $productID = $this->dao->lastInsertId();
                $this->dao->update(TABLE_PRODUCT)->set('`order`')->eq($productID * 5)->where('id')->eq($productID)->exec();

                $data = new stdclass();
                $data->project = $programID;
                $data->product = $productID;
                $this->dao->insert(TABLE_PROJECTPRODUCT)->data($data)->exec();
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
    public function update($programID)
    {
        $programID  = (int)$programID;
        $oldProgram = $this->dao->findById($programID)->from(TABLE_PROJECT)->fetch();

        $program = fixer::input('post')
            ->setDefault('team', $this->post->name)
            ->setDefault('end', '')
            ->setDefault('isCat', 0)
            ->setIF($this->post->begin == '0000-00-00', 'begin', '')
            ->setIF($this->post->end   == '0000-00-00', 'end', '')
            ->setIF($this->post->acl != 'custom', 'whitelist', '')
            ->setIF($this->post->acl == 'custom' and !isset($_POST['whitelist']), 'whitelist', '')
            ->join('whitelist', ',')
            ->stripTags($this->config->program->editor->edit['id'], $this->config->allowedTags)
            ->remove('products, branch, uid, plans, longTime')
            ->get();
        $program = $this->loadModel('file')->processImgURL($program, $this->config->program->editor->edit['id'], $this->post->uid);

        if(!$oldProgram->isCat and !empty($program->isCat) and $this->checkHasContent($programID))  dao::$errors['isCat'] = $this->lang->program->cannotChangeToCat;
        if($oldProgram->isCat  and empty($program->isCat)  and $this->checkHasChildren($programID)) dao::$errors['isCat'] = $this->lang->program->cannotCancelCat;

        if(!empty($program->isCat))
        {
            $minChildBegin = $this->dao->select('min(begin) as minBegin')->from(TABLE_PROJECT)->where('id')->ne($programID)->andWhere('deleted')->eq(0)->andWhere('path')->like("%,{$programID},%")->fetch('minBegin');
            $maxChildEnd   = $this->dao->select('max(end) as maxEnd')->from(TABLE_PROJECT)->where('id')->ne($programID)->andWhere('deleted')->eq(0)->andWhere('path')->like("%,{$programID},%")->andWhere('end')->ne('0000-00-00')->fetch('maxEnd');

            if($minChildBegin and $program->begin > $minChildBegin) dao::$errors['begin'] = sprintf($this->lang->program->beginGreateChild, $minChildBegin);
            if($maxChildEnd   and $program->end   < $maxChildEnd and !$this->post->longTime) dao::$errors['end'] = sprintf($this->lang->program->endLetterChild,   $maxChildEnd);

            $longTimeCount = $this->dao->select('count(*) as count')->from(TABLE_PROJECT)->where('id')->ne($programID)->andWhere('deleted')->eq(0)->andWhere('path')->like("%,{$programID},%")->andWhere('end')->eq('0000-00-00')->fetch('count');
            if(!empty($program->end) and $longTimeCount != 0) dao::$errors['end'] = $this->lang->program->childLongTime;
        }

        if($program->parent)
        {
            $parentProgram = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($program->parent)->fetch();
            if($parentProgram)
            {
                if($program->begin < $parentProgram->begin) dao::$errors['begin'] = sprintf($this->lang->program->beginLetterParent, $parentProgram->begin);
                if($parentProgram->end != '0000-00-00' and $program->end > $parentProgram->end) dao::$errors['end'] = sprintf($this->lang->program->endGreaterParent, $parentProgram->end);
                if(empty($program->end) and $this->post->longTime and $parentProgram->end != '0000-00-00') dao::$errors['end'] = sprintf($this->lang->program->endGreaterParent, $parentProgram->end);
            }
        }
        if(dao::isError()) return false;

        $requiredFields = $this->config->program->edit->requiredFields;
        if($this->post->longTime) $requiredFields = trim(str_replace(',end,', ',', ",{$requiredFields},"), ',');

        $this->dao->update(TABLE_PROJECT)->data($program)
            ->autoCheck($skipFields = 'begin,end')
            ->batchcheck($requiredFields, 'notempty')
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
            if($program->acl != 'open' and ($program->acl != $oldProgram->acl or $program->whitelist != $oldProgram->whitelist))
            {
                $this->loadModel('user')->updateUserView($programID, 'program');
            }

            if($oldProgram->parent != $program->parent) $this->moveNode($programID, $program->parent, $oldProgram->path, $oldProgram->grade);

            return common::createChanges($oldProgram, $program);
        }
    }

    /*
     * Get program swapper.
     *
     * @param  object  $programs
     * @param  int     $programID
     * @param  varchar $currentModule
     * @param  varchar $currentMethod
     * @param  varchar $extra
     * @access private
     * @return void
     */
    public function getSwitcher($programs, $programID, $currentModule, $currentMethod, $extra = '')
    {
        $this->loadModel('project');
        $currentProgramName = '';
        if($programID)
        {
            setCookie("lastProgram", $programID, $this->config->cookieLife, $this->config->webRoot, '', false, true);
            $currentProgram     = $this->project->getById($programID);
            $currentProgramName = $currentProgram->name;
        }
        if($currentModule == 'program' && $currentMethod == 'browse') $currentProgramName = $this->lang->program->all;

        $dropMenuLink = helper::createLink('program', 'ajaxGetDropMenu', "objectID=$programID&module=$currentModule&method=$currentMethod&extra=$extra");
        $output  = "<div class='btn-group' id='swapper'><button data-toggle='dropdown' type='button' class='btn btn-limit' id='currentItem' title='{$currentProgramName}'>{$currentProgramName} <i class='icon icon-swap'></i></button><div id='dropMenu' class='dropdown-menu search-list' data-ride='searchList' data-url='$dropMenuLink'>";
        $output .= '<div class="input-control search-box has-icon-left has-icon-right search-example"><input type="search" class="form-control search-input" /><label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label><a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a></div>';
        $output .= "</div></div>";
        //if($isMobile) $output  = "<a id='currentItem' href=\"javascript:showSearchMenu('project', '$projectID', '$currentModule', '$currentMethod', '$extra')\">{$currentProjectName} <span class='icon-caret-down'></span></a><div id='currentItemDropMenu' class='hidden affix enter-from-bottom layer'></div>";

        return $output;
    }

    /**
     * Judge an action is clickable or not.
     *
     * @param  object    $project
     * @param  string    $action
     * @access public
     * @return bool
     */
    public static function isClickable($program, $action)
    {
        $action = strtolower($action);

        if(empty($program)) return true;

        if(!empty($program->isCat) and $action == 'group')         return false;
        if(!empty($program->isCat) and $action == 'managemembers') return false;
        if(!empty($program->isCat) and $action == 'start')         return false;
        if(!empty($program->isCat) and $action == 'activate')      return false;
        if(!empty($program->isCat) and $action == 'suspend')       return false;
        if(!empty($program->isCat) and $action == 'close')         return false;

        if($action == 'start')    return $program->status == 'wait' or $program->status == 'suspended';
        if($action == 'finish')   return $program->status == 'wait' or $program->status == 'doing';
        if($action == 'close')    return $program->status != 'closed';
        if($action == 'suspend')  return $program->status == 'wait' or $program->status == 'doing';
        if($action == 'activate') return $program->status == 'done';

        return true;
    }

    /**
     * Check has content for program
     *
     * @param  int    $programID
     * @access public
     * @return bool
     */
    public function checkHasContent($programID)
    {
        $count  = 0;
        $count += (int)$this->dao->select('count(*) as count')->from(TABLE_PROJECT)->where('program')->eq($programID)->fetch('count');
        $count += (int)$this->dao->select('count(*) as count')->from(TABLE_TASK)->where('program')->eq($programID)->fetch('count');

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
        $count = $this->dao->select('count(*) as count')->from(TABLE_PROJECT)->where('parent')->eq($programID)->fetch('count');
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
        $program = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($programID)->fetch();

        $path['path']  = ",{$program->id},";
        $path['grade'] = 1;

        if($program->parent)
        {
            $parent = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($program->parent)->fetch();

            $path['path']  = $parent->path . "{$program->id},";
            $path['grade'] = $parent->grade + 1;
        }
        $this->dao->update(TABLE_PROJECT)->set('path')->eq($path['path'])->set('grade')->eq($path['grade'])->where('id')->eq($program->id)->exec();
        return !dao::isError();
    }

    /**
     * Get program parent pairs
     *
     * @access public
     * @return array
     */
    public function getParentPairs($template = '')
    {
        $modules = $this->dao->select('id,name,parent,path,grade')->from(TABLE_PROJECT)
            ->where('isCat')->eq(1)
            ->andWhere('deleted')->eq(0)
            ->andWhere('template')->ne('')
            ->beginIF($template)->andWhere('template')->eq($template)->fi()
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
     * @param  int    $programID
     * @param  int    $parentID
     * @param  string $oldPath
     * @param  int    $oldGrade
     * @access public
     * @return bool
     */
    public function moveNode($programID, $parentID, $oldPath, $oldGrade)
    {
        $parent = $this->dao->select('id,parent,path,grade')->from(TABLE_PROJECT)->where('id')->eq($parentID)->fetch();

        $childNodes = $this->dao->select('id,parent,path,grade')->from(TABLE_PROJECT)
            ->where('path')->like("{$oldPath}%")
            ->andWhere('deleted')->eq(0)
            ->andWhere('template')->ne('')
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
            $this->dao->update(TABLE_PROJECT)->set('path')->eq($path)->set('grade')->eq($grade)->where('id')->eq($childNode->id)->exec();
        }

        return !dao::isError();
    }
}
