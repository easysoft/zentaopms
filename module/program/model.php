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
    public function saveState($programID = 0, $programs = array())
    {
        if($programID > 0) $this->session->set('program', (int)$programID);
        if($programID == 0 and $this->cookie->lastProgram) $this->session->set('program', (int)$this->cookie->lastProgram);
        if($programID == 0 and $this->session->program == '') $this->session->set('program', key($programs));
        if(!isset($programs[$this->session->program]))
        {
            $this->session->set('program', key($programs));
            if($programID && strpos(",{$this->app->user->view->programs},", ",{$this->session->program},") === false) $this->accessDenied();
        }

        return $this->session->program;
    }

    /**
     * Get program pairs.
     *
     * @param  bool   $isQueryAll
     * @access public
     * @return array
     */
    public function getPairs($isQueryAll = false)
    {
        return $this->dao->select('id, name')->from(TABLE_PROGRAM)
            ->where('type')->eq('program')
            ->andWhere('deleted')->eq(0)
            ->beginIF(!$this->app->user->admin and !$isQueryAll)->andWhere('id')->in($this->app->user->view->programs)->fi()
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
    public function getProductPairs($programID = 0, $mode = 'assign', $status = 'all')
    {
        /* Get the top programID. */
        if($programID)
        {
            $program   = $this->getByID($programID);
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
    public function getByID($programID = 0)
    {
        $program = $this->dao->select('*')->from(TABLE_PROGRAM)->where('id')->eq($programID)->andWhere('`type`')->eq('program')->fetch();
        $program = $this->loadModel('file')->replaceImgURL($program, 'desc');
        return $program;
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
    public function getList($status = 'all', $orderBy = 'id_asc', $pager = NULL)
    {
        return $this->dao->select('*')->from(TABLE_PROGRAM)
            ->where('type')->in('program,project')
            ->andWhere('deleted')->eq(0)
            ->beginIF(!$this->app->user->admin)
            ->andWhere('(id')->in($this->app->user->view->programs)
            ->orWhere('id')->in($this->app->user->view->projects)
            ->markRight(1)
            ->fi()
            ->beginIF($status != 'all')->andWhere('status')->eq($status)->fi()
            ->beginIF(!$this->cookie->showClosed)->andWhere('status')->ne('closed')->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
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
     * @param  int       $involved
     * @access public
     * @return object
     */
    public function getProjectList($programID = 0, $browseType = 'all', $queryID = 0, $orderBy = 'id_desc', $pager = null, $programTitle = 0, $involved = 0)
    {
        $path = '';
        if($programID)
        {
            $program = $this->getByID($programID);
            $path    = $program->path;
        }

        $projectList = $this->dao->select('*')->from(TABLE_PROJECT)
            ->where('deleted')->eq('0')
            ->beginIF($this->config->systemMode == 'new')->andWhere('type')->eq('project')->fi()
            ->beginIF($browseType != 'all')->andWhere('status')->eq($browseType)->fi()
            ->beginIF($path)->andWhere('path')->like($path . '%')->fi()
            ->beginIF(!$this->app->user->admin and $this->config->systemMode == 'new')->andWhere('id')->in($this->app->user->view->projects)->fi()
            ->beginIF(!$this->app->user->admin and $this->config->systemMode == 'classic')->andWhere('id')->in($this->app->user->view->sprints)->fi()
            ->beginIF($this->cookie->involved or $involved)
            ->andWhere('openedBy', true)->eq($this->app->user->account)
            ->orWhere('PM')->eq($this->app->user->account)
            ->markRight(1)
            ->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        /* Determine how to display the name of the program. */
        if($programTitle and $this->config->systemMode == 'new')
        {
            $programList = $this->getPairs();
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
    public function getStakeholdersByPrograms($programIdList = 0)
    {
        return $this->dao->select('distinct user as account')->from(TABLE_STAKEHOLDER)
            ->where('objectID')->in($programIdList)
            ->andWhere('objectType')->eq('program')
            ->fetchAll();
    }

    /**
     * Create a program.
     *
     * @access private
     * @return int|bool
     */
    public function create()
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
            ->stripTags($this->config->program->editor->create['id'], $this->config->allowedTags)
            ->remove('delta,future')
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
                if(empty($program->end) and $this->post->delta == 999 and $parentProgram->end != '0000-00-00') dao::$errors['end'] = sprintf($this->lang->program->endGreaterParent, $parentProgram->end);

                /* The budget of a child program cannot beyond the remaining budget of the parent program. */
                $program->budgetUnit = $parentProgram->budgetUnit;
                if(isset($program->budget) and $parentProgram->budget != 0)
                {
                    $availableBudget = $this->getBudgetLeft($parentProgram);
                    if($program->budget > $availableBudget) dao::$errors['budget'] = $this->lang->program->beyondParentBudget;
                }

                if(dao::isError()) return false;
            }
        }

        /* Redefines the language entries for the fields in the project table. */
        foreach(explode(',', $this->config->program->create->requiredFields) as $field)
        {
            if(isset($this->lang->program->$field)) $this->lang->project->$field = $this->lang->program->$field;
        }

        $program = $this->loadModel('file')->processImgURL($program, $this->config->program->editor->create['id'], $this->post->uid);
        $this->dao->insert(TABLE_PROGRAM)->data($program)
            ->autoCheck()
            ->batchcheck($this->config->program->create->requiredFields, 'notempty')
            ->checkIF($program->begin != '', 'begin', 'date')
            ->checkIF($program->end != '', 'end', 'date')
            ->checkIF($program->end != '', 'end', 'gt', $program->begin)
            ->checkIF(!empty($program->name), 'name', 'unique', "`type`='program'")
            ->exec();

        if(!dao::isError())
        {
            $programID = $this->dao->lastInsertId();
            $this->dao->update(TABLE_PROGRAM)->set('`order`')->eq($programID * 5)->where('id')->eq($programID)->exec(); // Save order.

            $whitelist = explode(',', $program->whitelist);
            $this->loadModel('personnel')->updateWhitelist($whitelist, 'program', $programID);
            if($program->acl != 'open') $this->loadModel('user')->updateUserView($programID, 'program');

            $this->file->updateObjectID($this->post->uid, $programID, 'program');
            $this->setTreePath($programID);

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
    public function update($programID)
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
            ->stripTags($this->config->program->editor->edit['id'], $this->config->allowedTags)
            ->remove('uid,delta,future')
            ->get();

        $program  = $this->loadModel('file')->processImgURL($program, $this->config->program->editor->edit['id'], $this->post->uid);
        $children = $this->getChildren($programID);

        if($children > 0)
        {
            $minChildBegin = $this->dao->select('min(begin) as minBegin')->from(TABLE_PROGRAM)->where('id')->ne($programID)->andWhere('deleted')->eq(0)->andWhere('path')->like("%,{$programID},%")->fetch('minBegin');
            $maxChildEnd   = $this->dao->select('max(end) as maxEnd')->from(TABLE_PROGRAM)->where('id')->ne($programID)->andWhere('deleted')->eq(0)->andWhere('path')->like("%,{$programID},%")->andWhere('end')->ne('0000-00-00')->fetch('maxEnd');

            if($minChildBegin and $program->begin > $minChildBegin) dao::$errors['begin'] = sprintf($this->lang->program->beginGreateChild, $minChildBegin);
            if($maxChildEnd   and $program->end   < $maxChildEnd and $this->post->delta != 999) dao::$errors['end'] = sprintf($this->lang->program->endLetterChild, $maxChildEnd);
            if(dao::isError()) return false;
        }

        if($program->parent)
        {
            $parentProgram = $this->dao->select('*')->from(TABLE_PROGRAM)->where('id')->eq($program->parent)->fetch();
            if($parentProgram)
            {
                if($program->begin < $parentProgram->begin) dao::$errors['begin'] = sprintf($this->lang->program->beginLetterParent, $parentProgram->begin);
                if($parentProgram->end != '0000-00-00' and $program->end > $parentProgram->end) dao::$errors['end'] = sprintf($this->lang->program->endGreaterParent, $parentProgram->end);
                if(empty($program->end) and $this->post->delta == 999 and $parentProgram->end != '0000-00-00') dao::$errors['end'] = sprintf($this->lang->program->endGreaterParent, $parentProgram->end);
            }

            /* The budget of a child program cannot beyond the remaining budget of the parent program. */
            $program->budgetUnit = $parentProgram->budgetUnit;
            if($program->budget != 0 and $parentProgram->budget != 0)
            {
                $availableBudget = $this->getBudgetLeft($parentProgram);
                if($program->budget > $availableBudget + $oldProgram->budget) dao::$errors['budget'] = $this->lang->program->beyondParentBudget;
            }
        }
        if(dao::isError()) return false;

        /* Redefines the language entries for the fields in the project table. */
        foreach(explode(',', $this->config->program->edit->requiredFields) as $field)
        {
            if(isset($this->lang->program->$field)) $this->lang->project->$field = $this->lang->program->$field;
        }

        $this->dao->update(TABLE_PROGRAM)->data($program)
            ->autoCheck($skipFields = 'begin,end')
            ->batchcheck($this->config->program->edit->requiredFields, 'notempty')
            ->checkIF($program->begin != '', 'begin', 'date')
            ->checkIF($program->end != '', 'end', 'date')
            ->checkIF($program->end != '', 'end', 'gt', $program->begin)
            ->check('name', 'unique', "id!=$programID and deleted='0' and `type`='program'")
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
     * @access private
     * @return string
     */
    public function getSwitcher($programID = 0)
    {
        $currentProgramName = '';
        $currentModule      = $this->app->moduleName;
        $currentMethod      = $this->app->methodName;

        if($programID)
        {
            setCookie("lastProgram", $programID, $this->config->cookieLife, $this->config->webRoot, '', false, true);
            $currentProgram     = $this->getById($programID);
            $currentProgramName = $currentProgram->name;
        }
        else
        {
            $currentProgramName = $this->lang->program->all;
        }

        $dropMenuLink = helper::createLink('program', 'ajaxGetDropMenu', "objectID=$programID&module=$currentModule&method=$currentMethod");
        $output  = "<div class='btn-group header-btn' id='swapper'><button data-toggle='dropdown' type='button' class='btn' id='currentItem' title='{$currentProgramName}'><span class='text'>{$currentProgramName}</span> <span class='caret'></span></button><div id='dropMenu' class='dropdown-menu search-list' data-ride='searchList' data-url='$dropMenuLink'>";
        $output .= '<div class="input-control search-box has-icon-left has-icon-right search-example"><input type="search" class="form-control search-input" /><label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label><a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a></div>'; $output .= "</div></div>";

        return $output;
    }

    /**
     * Get the tree menu of program.
     *
     * @param  int    $programID
     * @param  string $from
     * @param  string $vars
     * @param  string $moduleName
     * @param  string $methodName
     * @access public
     * @return string
     */
    public function getTreeMenu($programID = 0, $from = 'program', $vars = '', $moduleName = '', $methodName = '')
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
            $link = $from == 'program' ? helper::createLink($moduleName, $methodName, "programID=$program->id") : helper::createLink('product', 'all', "programID=$program->id" . $vars);
            $linkHtml = html::a($link, $program->name, '', "id='program$program->id' class='text-ellipsis' title=$program->name");

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
    public function getTopPairs($model = '', $mode = '')
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
    public function getTopByID($programID)
    {
        if(empty($programID)) return 0;

        $program = $this->getByID($programID);
        if(empty($program)) return 0;

        $path = explode(',', trim($program->path, ','));
        return $path[0];
    }

    /**
     * Get children by program id.
     *
     * @param  int  $programID
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
            ->where('type')->in('program, project')
            ->andWhere('path')->like($program->path . '%')
            ->andWhere('status')->ne('closed')
            ->andWhere('deleted')->eq('0')
            ->fetch('count');

        return $unfinished;
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
        $childPrograms = $this->dao->select('id')->from(TABLE_PROJECT)->where('path')->like("%,$programID,%")->andWhere('type')->eq('program')->fetchPairs();
        $childProjects = $this->dao->select('id')->from(TABLE_PROJECT)->where('path')->like("%,$programID,%")->andWhere('type')->eq('project')->fetchPairs();
        $childProducts = $this->dao->select('id')->from(TABLE_PRODUCT)->where('program')->eq($programID)->fetchPairs();

        if(!empty($childPrograms)) $this->user->updateUserView($childPrograms, 'program', $changedAccounts);
        if(!empty($childProjects)) $this->user->updateUserView($childProjects, 'project', $changedAccounts);
        if(!empty($childProducts)) $this->user->updateUserView($childProducts, 'product', $changedAccounts);
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

        if($action == 'close')    return $program->status != 'closed';
        if($action == 'activate') return $program->status == 'done' or $program->status == 'closed';
        if($action == 'suspend')  return $program->status == 'wait' or $program->status == 'doing';

        return true;
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
     * Get budget left.
     *
     * @param  object  $parentProgram
     * @access public
     * @return int
     */
    public function getBudgetLeft($parentProgram)
    {
        if(empty($parentProgram)) return;

        $childGrade     = $parentProgram->grade + 1;
        $childSumBudget = $this->dao->select("sum(budget) as sumBudget")->from(TABLE_PROGRAM)
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
    public function getParentPairs($model = '', $mode = 'noclosed')
    {
        $modules = $this->dao->select('id,name,parent,path,grade')->from(TABLE_PROGRAM)
            ->where('type')->eq('program')
            ->beginIF(strpos($mode, 'noclosed') !== false)->andWhere('status')->ne('closed')->fi()
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
     * Get project stats.
     *
     * @param  int    $programID
     * @param  string $browseType
     * @param  int    $queryID
     * @param  string $orderBy
     * @param  object $pager
     * @param  string $programTitle
     * @param  int    $involved
     * @access public
     * @return array
     */
    public function getProjectStats($programID = 0, $browseType = 'undone', $queryID = 0, $orderBy = 'id_desc', $pager = null, $programTitle = 0, $involved = 0)
    {
        /* Init vars. */
        $projects = $this->getProjectList($programID, $browseType, $queryID, $orderBy, $pager, $programTitle, $involved);
        if(empty($projects)) return array();

        $projectKeys = array_keys($projects);
        $stats       = array();
        $hours       = array();
        $emptyHour   = array('totalEstimate' => 0, 'totalConsumed' => 0, 'totalLeft' => 0, 'progress' => 0);

        /* Get all tasks and compute totalEstimate, totalConsumed, totalLeft, progress according to them. */
        $tasks = $this->dao->select('id, project, estimate, consumed, `left`, status, closedReason')
            ->from(TABLE_TASK)
            ->where('project')->in($projectKeys)
            ->andWhere('parent')->lt(1)
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
     * Get program team member pairs.
     *
     * @param  int  $programID
     * @access public
     * @return array
     */
    public function getTeamMemberPairs($programID = 0)
    {
      $projectList = $this->loadModel('project')->getPairsByProgram($programID);
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

    /*
     * Set program menu.
     *
     * @param  int    $programID
     * @access public
     * @return void
     */
    public function setMenu($programID)
    {
        $this->lang->switcherMenu = $this->getSwitcher($programID);
        common::setMenuVars('program', $programID);
    }
}
