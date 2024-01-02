<?php
declare(strict_types=1);
/**
 * The model file of program module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     program
 * @link        https://www.zentao.net
 */
class programModel extends model
{
    /**
     * 提示权限不足并跳转页面。
     * Show accessDenied response.
     *
     * @access public
     * @return void
     */
    public function accessDenied()
    {
        if(commonModel::isTutorialMode()) return true;

        $link = helper::createLink('program', 'browse');
        if(!$this->server->http_referer) $link = helper::createLink('my', 'index');

        $loginLink = $this->config->requestType == 'GET' ? "?{$this->config->moduleVar}=user&{$this->config->methodVar}=login" : "user{$this->config->requestFix}login";
        if(strpos($this->server->http_referer, $loginLink) !== false) $link = helper::createLink('my', 'index');

        return $this->app->control->sendError($this->lang->program->accessDenied, $link);
    }

    /**
     * 设置并返回一个用户可见的项目集ID。
     * Set and return the projects that user can see.
     *
     * @param  int    $programID
     * @param  array  $programs
     * @access public
     * @return int
     */
    public function checkAccess(int $programID = 0, array $programs = array()): int
    {
        if($programID > 0) $this->session->set('program', $programID);
        if(!$programID && $this->cookie->lastProgram) $this->session->set('program', $this->cookie->lastProgram);
        if(!$programID && !$this->session->program)   $this->session->set('program', key($programs));
        if(!isset($programs[$this->session->program]))
        {
            $this->session->set('program', key($programs));
            if($programID && strpos(",{$this->app->user->view->programs},", ",{$this->session->program},") === false) $this->accessDenied();
        }

        return (int)$this->session->program;
    }

    /**
     * 获取项目集id:name的键值对。
     * Gets the  pair for the program id:name
     *
     * @param  bool   $isQueryAll
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getPairs($isQueryAll = false, $orderBy = 'id_desc'): array
    {
        return $this->dao->select('id, name')->from(TABLE_PROGRAM)
            ->where('type')->eq('program')
            ->andWhere('deleted')->eq(0)
            ->beginIF(!$this->app->user->admin && !$isQueryAll)->andWhere('id')->in($this->app->user->view->programs)->fi()
            ->orderBy($orderBy)
            ->fetchPairs();
    }

    /**
     * 获取项目集关联的产品列表。
     * Get the product associated with the program.
     *
     * @param  int          $programID
     * @param  string       $mode       all|assign
     * @param  string       $status     all|noclosed
     * @param  string|array $append
     * @param  string|int   $shadow     all | 0 | 1
     * @param  bool         $withProgram
     * @access public
     * @return array
     */
    public function getProductPairs(int $programID = 0, string $mode = 'assign', string $status = 'all', string $append = '', int|string $shadow = 0, bool $withProgram = false): array
    {
        /* Get the top programID. */
        if($programID)
        {
            $program   = $this->getByID($programID);
            $path      = array_filter(explode(',', $program->path));
            $programID = current($path);
        }

        /* When mode equals assign and programID equals 0, you can query the standalone product. */
        if(!empty($append) && is_array($append)) $append = implode(',', $append);
        $views = empty($append) ? $this->app->user->view->products : $this->app->user->view->products . ",$append";

        $dao = $this->dao->select('id, name, program')->from(TABLE_PRODUCT)
            ->where('deleted')->eq(0)
            ->andWhere("FIND_IN_SET('{$this->config->vision}', vision)")
            ->beginIF($shadow !== 'all')->andWhere('shadow')->eq((int)$shadow)->fi()
            ->beginIF($mode == 'assign')->andWhere('program')->eq($programID)->fi()
            ->beginIF(strpos($status, 'noclosed') !== false)->andWhere('status')->ne('closed')->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($views)->fi();

        if(!$withProgram) return $dao->fetchPairs('id', 'name');

        /* Put products of current program first.*/
        $products = $dao->orderBy('program,order')->fetchGroup('program');
        if(!empty($products) && isset($products[$programID]) && $mode != 'assign' && $programID)
        {
            $currentProgramProducts = $products[$programID];
            unset($products[$programID]);

            array_unshift($products, $currentProgramProducts);
        }

        $productPairs    = array();
        $productPrograms = $this->getPairs(true);
        foreach($products as $programProducts)
        {
            foreach($programProducts as $product)
            {
                $programName = zget($productPrograms, $product->program, '') . '/';
                $productPairs[$product->id] = $programName . $product->name;
            }
        }

        return $productPairs;
    }

    /**
     * 根据项目集ID获取项目集信息。
     * Get program by id.
     *
     * @param  int          $programID
     * @access public
     * @return object|false
     */
    public function getByID(int $programID = 0): object|false
    {
        $program = $this->fetchByID($programID);
        if(!$program) return false;

        $program = $this->loadModel('file')->replaceImgURL($program, 'desc');
        return $program;
    }

    /**
     * 根据项目集ID列表获取项目集信息。
     * Get program pairs by id list.
     *
     * @param  array  $programIDList
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getPairsByList(array $programIDList = array(), string $orderBy = 'order_asc'): array
    {
        return $this->dao->select('id, name')->from(TABLE_PROGRAM)
            ->where('id')->in($programIDList)
            ->andWhere('`type`')->eq('program')
            ->andWhere('deleted')->eq(0)
            ->orderBy($orderBy)
            ->fetchPairs();
    }

    /**
     * 获取项目集列表。
     * Get program list.
     *
     * @param  string $status
     * @param  string $orderBy
     * @param  string $type       top|child
     * @param  array  $topIdList
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList(string $status = 'all', string $orderBy = 'id_asc', string $type = '', array $topIdList = array(), object $pager = null): array
    {
        $userViewIdList = trim($this->app->user->view->programs, ',') . ',' . trim($this->app->user->view->projects, ',');
        $userViewIdList = array_filter(explode(',', $userViewIdList));

        $objectIdList = array();
        if($this->app->rawMethod == 'browse')
        {
            $pathList = $this->dao->select('id,path')->from(TABLE_PROJECT)
                 ->where('type')->in('program,project')
                 ->beginIF(!$this->app->user->admin)->andWhere('id')->in($userViewIdList)->fi()
                 ->andWhere('deleted')->eq(0)
                 ->orderBy('id_asc')
                 ->fetchPairs('id');

            foreach($pathList as $path)
            {
                if($type == 'child' && !empty($topIdList))
                {
                    $topID = $this->getTopByPath($path);
                    if(!in_array($topID, $topIdList)) continue;
                }

                foreach(explode(',', trim($path, ',')) as $pathID) $objectIdList[$pathID] = $pathID;
            }
        }

        return $this->dao->select('*')->from(TABLE_PROGRAM)
            ->where('type')->in('program,project')
            ->andWhere('deleted')->eq(0)
            ->andWhere('vision')->eq($this->config->vision)
            ->beginIF($status != 'all' && $status != 'unclosed')->andWhere('status')->in($status)->fi()
            ->beginIF($status == 'unclosed')->andWhere('status')->ne('closed')->fi()
            ->beginIF($this->app->rawMethod == 'browse' && $type === 'top')->andWhere('parent')->eq(0)->fi()
            ->beginIF($this->app->rawMethod == 'browse' && ($type === 'child' or !$this->app->user->admin))->andWhere('id')->in($objectIdList)->fi()
            ->beginIF(!$this->app->user->admin && $this->app->rawMethod != 'browse')->andWhere('id')->in($userViewIdList)->fi()
            ->beginIF(defined('RUN_MODE') && RUN_MODE == 'api' && !$this->cookie->showClosed)->andWhere('status')->ne('closed')->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * 根据查询ID获取项目集列表。
     * Get program list by search.
     *
     * @param string  $orderBy
     * @param int     $queryID
     * @access public
     * @return array
     */
    public function getListBySearch(string $orderBy = 'id_asc', int $queryID = 0): array
    {
        if($this->session->programQuery == false) $this->session->set('programQuery', ' 1 = 1');
        if($queryID)
        {
            $this->session->set('programQuery', ' 1 = 1');
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set('programQuery', $query->sql);
                $this->session->set('programForm', $query->form);
            }
        }
        $query = $this->session->programQuery;

        $objectIdList = array();
        if(!$this->app->user->admin)
        {
            $objectIdList = trim($this->app->user->view->programs, ',') . ',' . trim($this->app->user->view->projects, ',');
            $objectIdList = array_filter(explode(',', $objectIdList));
            asort($objectIdList);

            if($this->app->rawMethod == 'browse')
            {
                $pathList = $this->dao->select('id,path')->from(TABLE_PROJECT)->where('id')->in($objectIdList)->andWhere('deleted')->eq(0)->fetchPairs('id', 'path');
                foreach($pathList as $path)
                {
                    foreach(explode(',', trim($path, ',')) as $pathID) $objectIdList[$pathID] = $pathID;
                }
            }
            $objectIdList = array_unique($objectIdList);
        }

        return $this->dao->select('*')->from(TABLE_PROGRAM)->where('deleted')->eq(0)
            ->andWhere('vision')->eq($this->config->vision)
            ->andWhere('type')->eq('program')
            ->beginIF($query)->andWhere($query)->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($objectIdList)->fi()
            ->orderBy($orderBy)
            ->fetchAll('id');
    }

    /**
     * 获取看板统计的相关数据。
     * Get Kanban statistics data.
     *
     * @param  array  $programs
     * @access public
     * @return array
     */
    public function getKanbanStatisticData(array $programs): array
    {
        $productGroup  = $this->getProductByProgram(array_keys($programs));
        $productIdList = array();
        foreach($productGroup as $programID => $products)
        {
            foreach($products as $product)
            {
                $productIdList[$product->id] = $product->id;
                if($product->shadow) $product->name = $product->name . ' (' . $this->lang->project->common . ')';
            }
        }

        /* Get all plans under products. */
        $planGroup = $this->loadModel('productplan')->getProductPlans($productIdList, helper::today());

        /* Get all products linked projects. */
        $projectGroup  = $this->loadModel('project')->getGroupByProduct($productIdList, 'wait,doing');
        $projectIdList = array();
        foreach($projectGroup as $projects) $projectIdList = array_merge($projectIdList, array_keys($projects));

        /* Get all releases under products. */
        $releaseGroup = $this->loadModel('release')->getGroupByProduct($productIdList);

        /* Get doing executions. */
        $doingExecutions = $this->dao->select('id, project, name, end')->from(TABLE_EXECUTION)
            ->where('type')->in('sprint,stage,kanban')
            ->andWhere('status')->eq('doing')
            ->andWhere('deleted')->eq(0)
            ->andWhere('multiple')->ne(0)
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->sprints)->fi()
            ->orderBy('id_asc')
            ->fetchAll('project');

        $executionPairs = array();
        foreach($doingExecutions as $execution) $executionPairs[$execution->id] = $execution->id;

        return array($productGroup, $planGroup, $releaseGroup, $projectGroup, $doingExecutions);
    }

    /**
     * 获取用户参与的项目集列表信息。
     * Get involved programs by user.
     *
     * @param  string $account
     * @access public
     * @return array
     */
    public function getInvolvedPrograms(string $account): array
    {
        $involvedProgramIdList = array();

        /* All objects in program table. */
        $objects = $this->dao->select('id,type,project,parent,path,openedBy,PM')->from(TABLE_PROGRAM)->where('deleted')->eq(0)->fetchAll('id');
        foreach($objects as $id => $object)
        {
            if($object->openedBy != $account && $object->PM != $account) continue;

            $programID = $this->getProgramIDByObject($id, $object, $objects);
            if($programID) $involvedProgramIdList[$programID] = $programID;
        }

        /* All involves in stakeholder table. */
        $stakeholders = $this->dao->select('t1.objectID, t2.type')->from(TABLE_STAKEHOLDER)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.objectID = t2.id')
            ->where('t1.objectType')->in("program,project")
            ->andWhere('t1.user')->eq($account)
            ->fetchAll('objectID');
        foreach($stakeholders as $objectID => $object)
        {
            $programID = $this->getProgramIDByObject($objectID, $object, $objects);
            if($programID) $involvedProgramIdList[$programID] = $programID;
        }

        /* All involves in team table. */
        $teams = $this->dao->select('t1.root, t2.project, t2.type')->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.root = t2.id')
            ->where('t1.account')->eq($account)
            ->andWhere('t1.type')->in('project,execution')
            ->fetchAll('root');
        foreach($teams as $objectID => $object)
        {
            $programID = $this->getProgramIDByObject($objectID, $object, $objects);
            if($programID) $involvedProgramIdList[$programID] = $programID;
        }

        /* All involves in products table. */
        $products = $this->dao->select('id, program, createdBy, PO, QD, RD')->from(TABLE_PRODUCT)
            ->where('deleted')->eq(0)
            ->andWhere("(createdBy = '$account' or PO = '$account' or QD = '$account' or RD = '$account')")
            ->fetchAll('id');
        foreach($products as $id => $product) $involvedProgramIdList[$product->program] = $product->program;

        return $this->dao->select('id')->from(TABLE_PROGRAM)
            ->where('deleted')->eq(0)
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->programs)->fi()
            ->andWhere('id')->in($involvedProgramIdList)
            ->andWhere('grade')->eq(1)
            ->fetchPairs();
    }

    /**
     * 获取项目/执行的所属项目集ID。
     * Get the programID of the project/execution.
     *
     * @param  int    $objectID
     * @param  object $object
     * @param  array  $objects
     * @access public
     * @return int
     */
    public function getProgramIDByObject(int $objectID, object $object, array $objects): int
    {
        if($object->type == 'program') return $objectID;
        if($object->type == 'project')
        {
            $project = zget($objects, $objectID, array());
            if(!$project || !$project->parent) return 0;

            return $this->getTopByPath($project->path);
        }
        if(in_array($object->type, array('sprint', 'stage', 'kanban')))
        {
            $execution = zget($objects, $objectID, array());
            if(!$execution) return 0;

            $project = zget($objects, $execution->project, array());
            if(!$project || !$project->parent) return 0;

            return $this->getTopByPath($project->path);
        }
        return 0;
    }

    /**
     * 将团队成员等统计信息，追加到对应的项目中。
     * Append statistics fields to projects.
     *
     * @param  array  $projects
     * @param  array  $teams         array keys are hours, teams and leftTasks.
     * @access public
     * @return array
     */
    public function appendStatToProjects(array $projects, array $teams = array()): array
    {
        if(empty($projects)) return array();

        /* Get the number of left tasks. */
        $leftTasks  = array();
        $executions = $this->loadModel('project')->getExecutionList(array_keys($projects));
        if($this->cookie->projectType && $this->cookie->projectType == 'bycard')
        {
            $leftTasks = $this->dao->select('t2.parent as project, count(*) as tasks')->from(TABLE_TASK)->alias('t1')
                ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.execution = t2.id')
                ->where('t1.execution')->in(array_keys($executions))
                ->andWhere('t1.status')->notIn('cancel,closed')
                ->groupBy('t2.parent')
                ->fetchAll('project');
        }

        /* Process projects. */
        $stats = array();
        foreach($projects as $projectID => $project)
        {
            if(helper::isZeroDate($project->end)) $project->end = '';

            /* Judge whether the project is delayed. */
            if($project->status != 'done' && $project->status != 'closed' && $project->status != 'suspended')
            {
                $delay = empty($project->end) ? 0 : helper::diffDate(helper::today(), $project->end);
                if($delay > 0) $project->delay = $delay;
            }

            /* Merge project team. */
            $project->teamCount   = 0;
            $project->teamMembers = array();
            $project->leftTasks   = isset($leftTasks[$project->id]) ? $leftTasks[$project->id]->tasks : '—';
            if(!empty($teams))
            {
                $project->teamCount   = isset($teams[$project->id]) ? count($teams[$project->id]) : 0;
                $project->teamMembers = isset($teams[$project->id]) ? array_keys($teams[$project->id]) : array();
            }

            $stats[$projectID] = $project;
        }

        return $stats;
    }

    /**
     * 获取项目列表数据。
     * Get project list data.
     *
     * @param  int         $programID
     * @param  string      $browseType all|wait|undone|doing|suspended|closed|bysearch|review
     * @param  string      $queryID
     * @param  string      $orderBy
     * @param  string      $programTitle 0|base|end
     * @param  bool        $queryAll
     * @param  object|null $pager
     * @access public
     * @return object[]
     */
    public function getProjectList(int $programID = 0, string $browseType = 'all', int $queryID = 0, string $orderBy = 'id_desc', string $programTitle = '', bool $queryAll = false, object $pager = null): array
    {
        $path = '';
        if($programID) $path = $this->getByID($programID)->path;

        /* Get query project SQL. */
        $query = '';
        if($browseType == 'bysearch')
        {
            $this->loadModel('search')->setQuery('project', $queryID);
            $query = str_replace('`id`','t1.id', $this->session->projectQuery);
        }

        $stmt = $this->dao->select('DISTINCT t1.*')->from(TABLE_PROJECT)->alias('t1');
        if($this->cookie->involved) $stmt->leftJoin(TABLE_TEAM)->alias('t2')->on('t1.id=t2.root')->leftJoin(TABLE_STAKEHOLDER)->alias('t3')->on('t1.id=t3.objectID');
        $stmt->where('t1.deleted')->eq('0')
            ->andWhere('t1.vision')->eq($this->config->vision)
            ->beginIF($browseType == 'bysearch' && $query)->andWhere($query)->fi()
            ->andWhere('t1.type')->eq('project')
            ->beginIF(!in_array($browseType, array('all', 'undone', 'bysearch', 'review', 'unclosed'), true))->andWhere('t1.status')->eq($browseType)->fi()
            ->beginIF($browseType == 'undone' || $browseType == 'unclosed')->andWhere('t1.status')->in('wait,doing')->fi()
            ->beginIF($browseType == 'review')
            ->andWhere("FIND_IN_SET('{$this->app->user->account}', t1.reviewers)")
            ->andWhere('t1.reviewStatus')->eq('doing')
            ->fi()
            ->beginIF($path)->andWhere('t1.path')->like($path . '%')->fi()
            ->beginIF(!$queryAll && !$this->app->user->admin)->andWhere('t1.id')->in($this->app->user->view->projects)->fi();

        if($this->cookie->involved)
        {
            $stmt->andWhere('t2.type')->eq('project')
                ->andWhere('t1.openedBy', true)->eq($this->app->user->account)
                ->orWhere('t1.PM')->eq($this->app->user->account)
                ->orWhere('t2.account')->eq($this->app->user->account)
                ->orWhere('(t3.user')->eq($this->app->user->account)
                ->andWhere('t3.deleted')->eq(0)
                ->markRight(1)
                ->orWhere("CONCAT(',', t1.whitelist, ',')")->like("%,{$this->app->user->account},%")
                ->markRight(1);
        }
        $projectList = $stmt->orderBy($orderBy)->page($pager, 't1.id')->fetchAll('id');

        /* Determine how to display the name of the program. */
        if($programTitle and in_array($this->config->systemMode, array('ALM', 'PLM'))) $projectList = $this->batchProcessProgramName($projectList, $programTitle);
        return $projectList;
    }

    /**
     * 批量处理项目所属的项目集名称
     * Batch the name of the program to which the project belongs.
     *
     * @param  array  $projectList
     * @param  string $programTitle
     * @access public
     * @return object[]
     */
    public function batchProcessProgramName(array $projectList, string $programTitle = ''): array
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

            if($programName) $projectList[$id]->name = $programName . '/' . $projectList[$id]->name;
        }
        return $projectList;
    }

    /**
     * 通过项目集ID获取干系人列表信息。
     * Get stakeholders by program id.
     *
     * @param  int     $programID
     * @param  string  $orderBy
     * @param  object  $paper
     * @access public
     * @return array
     */
    public function getStakeholders(int $programID = 0, string $orderBy = 'id_desc', object $pager = null): array
    {
        return $this->dao->select('t2.account,t2.realname,t2.role,t2.qq,t2.mobile,t2.phone,t2.weixin,t2.email,t1.id,t1.type,t1.from,t1.key')->from(TABLE_STAKEHOLDER)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.user=t2.account')
            ->where('t1.objectID')->eq($programID)
            ->andWhere('t1.objectType')->eq('program')
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
    }

    /**
     * 创建项目集。
     * Create a program.
     *
     * @access private
     * @return int|bool
     */
    public function create(object $program): int|false
    {
        /* Redefines the language entries for the fields in the project table. */
        $this->lang->error->unique = $this->lang->error->repeat;
        foreach(explode(',', $this->config->program->create->requiredFields) as $field)
        {
            if(isset($this->lang->program->$field)) $this->lang->project->$field = $this->lang->program->$field;
        }

        $this->dao->insert(TABLE_PROGRAM)->data($program)
            ->autoCheck()
            ->batchcheck($this->config->program->create->requiredFields, 'notempty')
            ->checkIF($program->begin != '', 'begin', 'date')
            ->checkIF($program->end != '', 'end', 'date')
            ->checkIF($program->end != '', 'end', 'gt', $program->begin)
            ->checkIF(!empty($program->name), 'name', 'unique', "`type`='program' and `parent` = " . $this->dao->sqlobj->quote($program->parent) . " and `deleted` = '0'")
            ->checkFlow()
            ->exec();
        if(dao::isError()) return false;

        $programID = $this->dao->lastInsertId();
        $this->dao->update(TABLE_PROGRAM)->set('`order`')->eq($programID * 5)->where('id')->eq($programID)->exec(); // Save order.

        $whitelist = explode(',', $program->whitelist);
        $this->loadModel('personnel')->updateWhitelist($whitelist, 'program', $programID);

        $this->loadModel('file')->updateObjectID($this->post->uid, $programID, 'program');
        $this->setTreePath($programID);

        if($program->acl != 'open') $this->loadModel('user')->updateUserView(array($programID), 'program');

        return $programID;
    }

    /**
     * 更新项目集。
     * Update the program.
     *
     * @param  int    $programID
     * @param  object $program
     * @access public
     * @return array|false
     */
    public function update(int $programID, object $program): array|false
    {
        $oldProgram = $this->fetchByID($programID);

        /* Update line root to top program. */
        if($program->parent) $this->dao->update(TABLE_MODULE)->set('root')->eq($program->parent)->where('root')->eq($programID)->andwhere('type')->eq('line')->exec();
        if(dao::isError()) return false;

        /* Redefines the language entries for the fields in the project table. */
        $this->app->loadLang('project');
        $this->lang->error->unique = $this->lang->error->repeat;
        foreach(explode(',', $this->config->program->edit->requiredFields) as $field)
        {
            if(isset($this->lang->program->$field)) $this->lang->project->$field = $this->lang->program->$field;
        }

        $this->dao->update(TABLE_PROGRAM)->data($program, 'syncPRJUnit,exchangeRate')
            ->autoCheck('begin,end')
            ->batchCheck($this->config->program->edit->requiredFields, 'notempty')
            ->checkIF($program->begin != '', 'begin', 'date')
            ->checkIF($program->end != '', 'end', 'date')
            ->checkIF($program->end != '', 'end', 'gt', $program->begin)
            ->checkIF(!empty($program->name), 'name', 'unique', "id!=$programID and `type`='program' and `parent` = " . $this->dao->sqlobj->quote($program->parent) . " and `deleted` = '0'")
            ->checkFlow()
            ->where('id')->eq($programID)
            ->exec();

        if(!dao::isError())
        {
            $this->loadModel('user');
            $this->loadModel('file')->updateObjectID($this->post->uid, $programID, 'project');
            if($program->whitelist) $this->loadModel('personnel')->updateWhitelist(explode(',', $program->whitelist), 'program', $programID);
            if($program->acl != 'open') $this->user->updateUserView(array($programID), 'program');

            /* If the program changes, the authorities of programs and projects under the program should be refreshed. */
            $children = $this->dao->select('id, type')->from(TABLE_PROGRAM)->where('path')->like("%,{$programID},%")->andWhere('id')->ne($programID)->andWhere('acl')->eq('program')->fetchGroup('type', 'id');
            foreach($children as $type => $idList) $this->user->updateUserView(array_keys($idList), $type);
            if(isset($program->PM) and $program->PM != $oldProgram->PM)
            {
                $productIdList = $this->dao->select('id')->from(TABLE_PRODUCT)->where('program')->eq($programID)->fetchPairs('id');
                $this->user->updateUserView($productIdList, 'product');
            }

            if($oldProgram->parent != $program->parent)
            {
                $this->processNode($programID, $program->parent, $oldProgram->path, $oldProgram->grade);
                $this->programTao->fixLinkedProduct($programID, $program->parent, $oldProgram->parent, $oldProgram->path);
            }
            return common::createChanges($oldProgram, $program);
        }
        return false;
    }

    /**
     * 关闭一个项目集。
     * Close a program.
     *
     * @param  object $oldProgram
     * @param  object $program
     * @access public
     * @return array|bool
     */
    public function close(object $program, object $oldProgram): array|bool
    {
        $program = $this->loadModel('file')->processImgURL($program, $this->config->program->editor->close['id'], (string)$this->post->uid);
        $this->dao->update(TABLE_PROJECT)->data($program)
            ->autoCheck()
            ->checkIF($program->realEnd != '', 'realEnd', 'le', helper::today())
            ->checkIF($program->realEnd != '', 'realEnd', 'ge', $oldProgram->realBegan)
            ->checkFlow()
            ->where('id')->eq($oldProgram->id)
            ->exec();

        if(dao::isError()) return false;
        return common::createChanges($oldProgram, $program);
    }

    /**
     * 激活一个项目集。
     * Activate a program.
     *
     * @param  object      $program
     * @param  object      $oldProgram
     * @access public
     * @return array|false
     */
    public function activate(object $program, $oldProgram): array|false
    {
        if($program->begin > $program->end)
        {
            dao::$errors['end'] = sprintf($this->lang->error->ge, $this->lang->program->end, $this->lang->program->begin);
            return false;
        }

        if(!helper::isZeroDate($oldProgram->realBegan)) $program->realBegan = helper::today();
        $program = $this->loadModel('file')->processImgURL($program, $this->config->program->editor->activate['id'], (string)$this->post->uid);

        $this->dao->update(TABLE_PROJECT)->data($program)
            ->autoCheck()
            ->checkFlow()
            ->where('id')->eq($oldProgram->id)
            ->exec();

        if(dao::isError()) return false;

        return common::createChanges($oldProgram, $program);
    }

    /**
     * 挂起一个项目集。
     * Suspend a program.
     *
     * @param  int    $programID
     * @param  object $postData
     * @access public
     * @return array
     */
    public function suspend(int $programID, object $postData): bool
    {
        $oldProgram = $this->getByID($programID);

        $program = $this->loadModel('file')->processImgURL($postData, $this->config->program->editor->suspend['id'], $postData->uid);
        $this->dao->update(TABLE_PROJECT)->data($program, 'comment,uid')
            ->autoCheck()
            ->checkFlow()
            ->where('id')->eq($programID)
            ->exec();

        $newProgram = $this->getByID($programID);

        if(dao::isError()) return false;

        $changes = common::createChanges($oldProgram, $newProgram);
        if($postData->comment != '' or !empty($changes))
        {
            $actionID = $this->loadModel('action')->create('program', $programID, 'Suspended', $postData->comment);
            $this->action->logHistory($actionID, $changes);
        }

        return true;
    }

    /**
     * 获取一级项目集id:name的键值对。
     * Get top program pairs.
     *
     * @param  string $model
     * @param  string $mode
     * @param  bool   $isQueryAll
     * @access public
     * @return array
     */
    public function getTopPairs(string $mode = '', bool $isQueryAll = false): array
    {
        $topPairs = $this->dao->select('id,name')->from(TABLE_PROGRAM)
            ->where('type')->eq('program')
            ->andWhere('grade')->eq(1)
            ->beginIF(strpos($mode, 'noclosed') !== false)->andWhere('status')->ne('closed')->fi()
            ->beginIF(strpos($mode, 'withDeleted') === false)->andWhere('deleted')->eq(0)->fi()
            ->beginIF(!$isQueryAll && !$this->app->user->admin)->andWhere('id')->in($this->app->user->view->programs)->fi()
            ->orderBy('`order` asc')
            ->fetchPairs();

        if(strpos($mode, 'withDeleted') !== false)
        {
            $deletedTopPairs = $this->dao->select('id, name')->from(TABLE_PROGRAM)
                ->where('type')->eq('program')
                ->andWhere('grade')->eq(1)
                ->andWhere('deleted')->eq(1)
                ->fetchPairs();

            foreach($topPairs as $id => $name)
            {
                if(isset($deletedTopPairs[$id])) $topPairs[$id] .= ' (' . $this->lang->program->deleted . ')';
            }
        }

        return $topPairs;
    }

    /**
     * 获取顶级项目集的ID。
     * Get top program by id.
     *
     * @param  int    $programID
     * @access public
     * @return int
     */
    public function getTopByID(int $programID): int
    {
        if(empty($programID)) return 0;

        $program = $this->getByID($programID);
        if(empty($program)) return 0;

        return $this->getTopByPath($program->path);
    }

    /**
     * 通过路径获取顶级项目集的ID。
     * Get top program by path.
     *
     * @param  string  $path
     * @access public
     * @return int
     */
    public function getTopByPath(string $path): int
    {
        $paths = explode(',', trim($path, ','));
        return (int)$paths[0];
    }

    /**
     * 该项目集下是否有未关闭的子项目集或项目。
     * Judge whether there is an unclosed programs or projects.
     *
     * @param  object  $program
     * @access public
     * @return bool
     */
    public function hasUnfinishedChildren(object $program): bool
    {
        $count = $this->dao->select("id")->from(TABLE_PROJECT)
            ->where('type')->in('program, project')
            ->andWhere('path')->like($program->path . '%')
            ->andWhere('id')->ne($program->id)
            ->andWhere('status')->ne('closed')
            ->andWhere('deleted')->eq('0')
            ->count();

        return $count != 0;
    }

    /**
     * 更新项目集及子项的用户视图。
     * Update user view of program and its children.
     *
     * @param  int    $programID
     * @param  array  $account
     * @access public
     * @return bool
     */
    public function updateChildUserView(int $programID = 0, array $accounts = array()): bool
    {
        $this->loadModel('user')->updateUserView(array($programID), 'program', $accounts);

        $programList = $this->dao->select('id')->from(TABLE_PROJECT)->where('path')->like("%,$programID,%")->andWhere('type')->eq('program')->fetchPairs();
        $projectList = $this->loadModel('project')->getPairsByProgram($programID, 'all', true);
        $productList = $this->loadModel('product')->getPairs('', $programID);

        if(!empty($programList)) $this->user->updateUserView($programList, 'program', $accounts);
        if(!empty($projectList)) $this->user->updateUserView(array_keys($projectList), 'project', $accounts);
        if(!empty($productList)) $this->user->updateUserView(array_keys($productList), 'product', $accounts);
        return !dao::isError();
    }

    /**
     * 判断操作按钮是否可以点击。
     * Judge an action is clickable or not.
     *
     * @param  object $program
     * @param  string $action
     * @access public
     * @return bool
     */
    public static function isClickable(object $program, string $action): bool
    {
        $action = strtolower($action);

        if(empty($program)) return true;
        if(!isset($program->type)) return true;

        if($action == 'close')    return $program->status != 'closed';
        if($action == 'activate') return $program->status == 'done' || $program->status == 'closed';
        if($action == 'suspend')  return $program->status == 'wait' || $program->status == 'doing';
        if($action == 'start')    return $program->status == 'wait' || $program->status == 'suspended';

        return true;
    }

    /**
     * 设置项目集的路径。
     * Set program tree path.
     *
     * @param  int    $programID
     * @access public
     * @return bool
     */
    public function setTreePath(int $programID): bool
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
     * 获取项目集的预算剩余。
     * Get budget left of program.
     *
     * @param  object    $parentProgram
     * @param  int|float $leftBudget
     * @access public
     * @return float
     */
    public function getBudgetLeft(object $parentProgram, int|float $leftBudget = 0): float
    {
        if(empty($parentProgram->id)) return 0;

        $childGrade     = $parentProgram->grade + 1;
        $childSumBudget = $this->dao->select("sum(budget) as sumBudget")->from(TABLE_PROGRAM)
            ->where('path')->like("%,{$parentProgram->id},%")
            ->andWhere('grade')->eq($childGrade)
            ->andWhere('deleted')->eq('0')
            ->fetch('sumBudget');

        $leftBudget += (float)$parentProgram->budget - (float)$childSumBudget;

        if($parentProgram->budget == 0 && $parentProgram->parent)
        {
            $parentParent = $this->getById($parentProgram->parent);
            return $this->getBudgetLeft($parentParent, $leftBudget);
        }

        return $leftBudget;
    }

    /**
     * 获取父项目集列表。
     * Get program parent pairs
     *
     * @param  string $model
     * @param  string $mode       noclosed|all
     * @param  bool   $showRoot
     * @access public
     * @return array
     */
    public function getParentPairs(string $model = '', string $mode = 'noclosed', bool $showRoot = true): array
    {
        $programList = $this->dao->select('id,name,parent,path,grade')->from(TABLE_PROGRAM)
            ->where('type')->eq('program')
            ->andWhere('deleted')->eq(0)
            ->beginIF($model)->andWhere('model')->eq($model)->fi()
            ->beginIF(strpos($mode, 'noclosed') !== false)->andWhere('status')->ne('closed')->fi()
            ->orderBy('grade desc, `order`')
            ->fetchAll('id');

        $treeMenu = array();
        foreach($programList as $program)
        {
            if(!$this->app->user->admin && strpos($mode, 'all') === false && strpos(",{$this->app->user->view->programs},", ",{$program->id},") === false) continue;

            $programName = $showRoot ? '/' : '';
            $parentList  = explode(',', trim($program->path, ','));
            foreach($parentList as $parentID)
            {
                if(empty($parentID) || empty($programList[$parentID])) continue;

                $programName .= $programList[$parentID]->name . '/';
            }
            $programName  = str_replace('|', '&#166;', rtrim($programName, '/'));
            $programName .= "|$program->id\n";

            if(!isset($treeMenu[$program->parent])) $treeMenu[$program->parent] = '';
            $treeMenu[$program->parent] .= $programName;

            if(isset($treeMenu[$program->id]) && !empty($treeMenu[$program->id])) $treeMenu[$program->parent] .= $treeMenu[$program->id];
        }

        ksort($treeMenu);
        $topMenu = array_shift($treeMenu);
        $topMenu = empty($topMenu) ? '' : trim($topMenu);
        $topMenu = explode("\n", $topMenu);

        $lastMenu = $showRoot ? array('/') : array();
        foreach($topMenu as $menu)
        {
            if(strpos($menu, '|') === false) continue;

            list($label, $moduleID) = explode('|', $menu);
            $lastMenu[$moduleID]    = str_replace('&#166;', '|', $label);
        }

        return $lastMenu;
    }

    /**
     * 根据项目集ID列表获取父项目集的负责人。
     * Get parent PM by programIdList.
     *
     * @param  array  $programIdList
     * @access public
     * @return array
     */
    public function getParentPM(array $programIdList): array
    {
        $objects = $this->dao->select('id, path, parent')->from(TABLE_PROGRAM)->where('id')->in($programIdList)->andWhere('acl')->ne('open')->fetchAll('id');

        $parents = array();
        foreach($objects as $object)
        {
            if($object->parent == 0) continue;
            foreach(explode(',', $object->path) as $objectID)
            {
                if(empty($objectID) || $objectID == $object->id) continue;
                $parents[$objectID][] = $object->id;
            }
        }

        /* Get all parent PM.*/
        $parentPM = $this->dao->select('id, PM')->from(TABLE_PROGRAM)->where('id')->in(array_keys($parents))->andWhere('deleted')->eq('0')->fetchAll();

        $parentPMGroup = array();
        foreach($parentPM as $PM)
        {
            $subPrograms = zget($parents, $PM->id, array());
            foreach($subPrograms as $subProgramID) $parentPMGroup[$subProgramID][$PM->PM] = $PM->PM;
        }

        return $parentPMGroup;
    }

    /**
     * 修改子项目集和项目的层级。
     * Modify the subProgram and project grade.
     *
     * @param  int    $programID
     * @param  int    $parentID
     * @param  string $oldPath
     * @param  int    $oldGrade
     * @access public
     * @return bool
     */
    public function processNode(int $programID, int $parentID, string $oldPath, int $oldGrade): bool
    {
        $parent     = $this->dao->select('id,parent,path,grade')->from(TABLE_PROGRAM)->where('id')->eq($parentID)->fetch();
        $childNodes = $this->dao->select('id,parent,path,grade,type')->from(TABLE_PROGRAM)
            ->where('path')->like("{$oldPath}%")
            ->andWhere('deleted')->eq(0)
            ->orderBy('grade')
            ->fetchAll();

        /* Process child node path and grade field. */
        foreach($childNodes as $childNode)
        {
            $hasProgram = strpos($childNode->path, ",{$programID},");
            if($hasProgram === false) continue;

            $path = substr($childNode->path, $hasProgram);

            /* Only program and project sets update grade. */
            $grade = in_array($childNode->type, array('program', 'project')) ? $childNode->grade - $oldGrade + 1 : $childNode->grade;
            if($parent)
            {
                $path  = rtrim($parent->path, ',') . $path;
                $grade = in_array($childNode->type, array('program', 'project')) ? $parent->grade + $grade : $grade;
            }

            $this->dao->update(TABLE_PROGRAM)->set('path')->eq($path)->set('grade')->eq($grade)->where('id')->eq($childNode->id)->exec();
        }

        return !dao::isError();
    }

    /**
     * 获取项目统计数据。
     * Get project stats data.
     *
     * @param  int         $programID
     * @param  string      $browseType
     * @param  int         $queryID
     * @param  string      $orderBy
     * @param  object|null $pager
     * @param  string      $programTitle
     * @param  bool        $queryAll
     * @access public
     * @return array
     */
    public function getProjectStats(int $programID = 0, string $browseType = 'undone', int $queryID = 0, string $orderBy = 'id_desc', string $programTitle = '', bool $queryAll = false, object|null $pager = null): array
    {
        if(commonModel::isTutorialMode()) return $this->loadModel('tutorial')->getProjectStats($browseType);

        $projects = $this->getProjectList($programID, $browseType, $queryID, $orderBy, $programTitle, $queryAll, $pager);
        if(empty($projects)) return array();

        /* Get the members of project teams. */
        $teamMembers = $this->loadModel('project')->getTeamMemberGroup(array_keys($projects));

        return $this->appendStatToProjects($projects, $teamMembers);
    }

    /**
     * 根据项目集ID获取团队成员。
     * Get program team member pairs.
     *
     * @param  int  $programID
     * @access public
     * @return array
     */
    public function getTeamMemberPairs(int $programID = 0): array
    {
      $projectList = $this->getProjectList($programID);
      if(!$projectList) return array();

      $users = $this->dao->select("t2.id, t2.account, t2.realname")->from(TABLE_TEAM)->alias('t1')
          ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account = t2.account')
          ->where('t1.root')->in(array_keys($projectList))
          ->andWhere('t1.type')->eq('project')
          ->andWhere('t2.deleted')->eq(0)
          ->fetchAll('account');
      if(!$users) return array();

      $firstLetter = '';
      foreach($users as $account => $user)
      {
          if(empty($this->config->isINT)) $firstLetter = ucfirst(substr($user->account, 0, 1)) . ':';

          $users[$account]  = $firstLetter;
          $users[$account] .= $user->realname ? $user->realname : $user->account;
      }

      return $users;
    }

    /**
     * 创建默认的项目集。
     * Create default program.
     *
     * @access public
     * @return int
     */
    public function createDefaultProgram()
    {
        $defaultProgram = $this->loadModel('setting')->getItem('owner=system&module=common&section=global&key=defaultProgram');
        if($defaultProgram)
        {
            $program = $this->dao->select('id')->from(TABLE_PROGRAM)->where('id')->eq($defaultProgram)->andWhere('deleted')->eq(0)->fetch();
            if($program) return $defaultProgram;
        }

        $program = $this->dao->select('id')->from(TABLE_PROGRAM)->where('name')->eq($this->lang->program->defaultProgram)->andWhere('deleted')->eq(0)->fetch();
        if($program) return $program->id;

        $account  = isset($this->app->user->account) ? $this->app->user->account : '';
        $minBegin = $this->dao->select('min(`begin`) as min')->from(TABLE_PROJECT)->where('deleted')->eq(0)->fetch('min');

        $program = new stdclass();
        $program->name          = $this->lang->program->defaultProgram;
        $program->type          = 'program';
        $program->budgetUnit    = 'CNY';
        $program->status        = 'doing';
        $program->auth          = 'extend';
        $program->begin         = !empty($minBegin) ? $minBegin : helper::today();
        $program->end           = LONG_TIME;
        $program->openedBy      = $account;
        $program->openedDate    = helper::now();
        $program->openedVersion = $this->config->version;
        $program->acl           = 'open';
        $program->grade         = 1;
        $program->vision        = 'rnd';

        $this->app->loadLang('program');
        $this->app->loadLang('project');
        $this->lang->project->name = $this->lang->program->name;

        $this->dao->insert(TABLE_PROGRAM)->data($program)->exec();
        if(dao::isError()) return false;

        $programID = $this->dao->lastInsertId();

        $this->dao->update(TABLE_PROGRAM)->set('path')->eq(",{$programID},")->set('`order`')->eq($programID * 5)->where('id')->eq($programID)->exec();
        $this->loadModel('action')->create('program', $programID, 'openedbysystem');

        return $programID;
    }

    /**
     * 构造项目集列表的操作列数据。
     * Build actions data.
     *
     * @param  object $program
     * @access public
     * @return array
     */
    public function buildActions(object $program): array
    {
        $actionsMap = $this->programTao->buildProgramActionsMap($program);
        if($program->type == 'project') $actionsMap = array_merge($actionsMap, $this->programTao->buildProjectActionsMap($program));
        return $actionsMap;
    }

    /**
     * 获取项目集下的产品列表信息。
     * Get product list information under the program.
     *
     * @param  array  $programIdList
     * @access public
     * @return array
     */
    public function getProductByProgram(array $programIdList = array()): array
    {
        return $this->dao->select('*')->from(TABLE_PRODUCT)
            ->where('deleted')->eq(0)
            ->andWhere('status')->ne('closed')
            ->beginIF(!empty($programIdList))->andWhere('program')->in($programIdList)->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->products)->fi()
            ->orderBy('order_asc')
            ->fetchGroup('program');
    }

    /**
     * 更新项目集排序。
     * Update the order of the program.
     *
     * @param  int    $programID
     * @param  int    $order
     * @access public
     * @return bool
     */
    public function updateOrder(int $programID, int $order): bool
    {
        $this->dao->update(TABLE_PROGRAM)->set('`order`')->eq($order)->where('id')->eq($programID)->exec();
        return !dao::isError();
    }

    /**
     * 批量移除项目集下的关系人。
     * Batch unlink program stakeholders.
     *
     * @param  int    $programID
     * @param  array  $stakeholderIdList
     * @access public
     * @return bool
     */
    public function batchUnlinkStakeholders(int $programID, array $stakeholderIdList): bool
    {
        $accountList = $this->dao->select('user')->from(TABLE_STAKEHOLDER)->where('id')->in($stakeholderIdList)->fetchPairs('user');
        $this->dao->delete()->from(TABLE_STAKEHOLDER)->where('id')->in($stakeholderIdList)->exec();

        if(dao::isError()) return false;

        $this->updateChildUserView($programID, $accountList);
        return !dao::isError();
    }

    /**
     * 通过项目集ID列表批量获取项目集基本数据。
     * Get program base data with program ID array.
     *
     * @param  array  $programIdList
     * @access public
     * @return array
     */
    public function getBaseDataList(array $programIdList): array
    {
        return $this->dao->select('id,name,PM,path,parent,type')
            ->from(TABLE_PROGRAM)
            ->where('id')->in($programIdList)
            ->andWhere('deleted')->eq('0')
            ->andWhere('type')->eq('program')
            ->fetchAll('id');
    }

    /**
     * 刷新项目集的统计数据。
     * Refresh stats fields(estimate,consumed,left,progress) of program, project, execution.
     *
     * @param  bool $refreshAll
     * @access public
     * @return void
     */
    public function refreshStats($refreshAll = false): void
    {
        $updateTime = zget($this->app->config->global, 'projectStatsTime', '');
        $now        = helper::now();

        /*
         * If projectStatsTime is before two weeks ago, refresh all executions directly.
         * Else only refresh the latest executions in action table.
         */
        $projects = array();
        if($updateTime < date('Y-m-d', strtotime('-14 days')) or $refreshAll)
        {
            $projects = $this->dao->select('id,project,model,deleted')->from(TABLE_PROJECT)->fetchAll('id');
        }
        else
        {
            $projects = $this->dao->select('distinct t1.project,t2.model,t2.deleted')->from(TABLE_ACTION)->alias('t1')
                ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
                ->where('t1.`date`')->ge($updateTime)
                ->fetchAll('project');
            if(empty($projects)) return;
        }

        /* 1. Refresh stats to db. */
        $this->programTao->updateStats(array_keys($projects));

        /* 2. Update programStatsTime. */
        $this->programTao->updateProcess();

        /* 3. Update projectStatsTime in config. */
        $this->loadModel('setting')->setItem('system.common.global.projectStatsTime', $now);
        $this->app->config->global->projectStatsTime = $now;

        /* 4. Clear actions older than 30 days. */
        $this->loadModel('action')->cleanActions();
    }

    /**
     * Check the privilege.
     *
     * @param  int    $programID
     * @access public
     * @return bool
     */
    public function checkPriv(int $programID):bool
    {
        return !empty($programID) && ($this->app->user->admin || (strpos(",{$this->app->user->view->programs},", ",{$programID},") !== false));
    }
}
