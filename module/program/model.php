<?php
declare(strict_types=1);
/**
 * The model file of program module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     program
 * @link        http://www.zentao.net
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
            ->beginIF(!$this->app->user->admin and !$isQueryAll)->andWhere('id')->in($this->app->user->view->programs)->fi()
            ->orderBy($orderBy)
            ->fetchPairs();
    }

    /**
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
    public function getProductPairs($programID = 0, $mode = 'assign', $status = 'all', $append = '', $shadow = 0, $withProgram = false)
    {
        /* Get the top programID. */
        if($programID)
        {
            $program   = $this->getByID($programID);
            $path      = array_filter(explode(',', $program->path));
            $programID = current($path);
        }

        /* When mode equals assign and programID equals 0, you can query the standalone product. */
        if(!empty($append) and is_array($append)) $append = implode(',', $append);
        $views = empty($append) ? $this->app->user->view->products : $this->app->user->view->products . ",$append";

        $dao = $this->dao->select('id, name, program')->from(TABLE_PRODUCT)
            ->where('deleted')->eq(0)
            ->andWhere('vision')->eq($this->config->vision)
            ->beginIF($shadow !== 'all')->andWhere('shadow')->eq((int)$shadow)->fi()
            ->beginIF($mode == 'assign')->andWhere('program')->eq($programID)->fi()
            ->beginIF(strpos($status, 'noclosed') !== false)->andWhere('status')->ne('closed')->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($views)->fi();

        if(!$withProgram) return $dao->fetchPairs('id', 'name');

        $products = $dao->orderBy('program,order')->fetchGroup('program');
        $productPrograms = $this->dao->select('id, name')->from(TABLE_PROJECT)->where('type')->eq('program')->andWhere('deleted')->eq('0')->fetchPairs();

        /* Put products of current program first.*/
        if(!empty($products) and isset($products[$programID]) and $mode != 'assign' and $programID)
        {
            $currentProgramProducts = $products[$programID];
            unset($products[$programID]);
            array_unshift($products, $currentProgramProducts);
        }

        $productPairs = array();
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
     * Get program pairs by id list.
     *
     * @param  string|array $programIDList
     * @param  string       $orderBy
     * @access public
     * @return array
     */
    public function getPairsByList($programIDList = '', $orderBy = 'order_asc')
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
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
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
     * 获取看板数据。
     * Get kanban group data.
     *
     * @access public
     * @return array
     */
    public function getKanbanGroup(): array
    {
        $programs = $this->getTopPairs('noclosed');

        /* Group data by product. */
        list($productGroup, $planGroup, $releaseGroup, $projectGroup, $doingExecutions, $hours, $projectHours) = $this->getKanbanStatisticData($programs);
        $productGroup = $this->processProductsForKanban($productGroup, $planGroup, $releaseGroup, $projectGroup, $doingExecutions, $hours, $projectHours);

        /* Group data by program. */
        $kanbanGroup           = array();
        $kanbanGroup['my']     = array();
        $kanbanGroup['others'] = array();
        $involvedPrograms      = $this->getInvolvedPrograms($this->app->user->account);
        foreach($programs as $programID => $programName)
        {
            $programGroup = new stdclass();
            $programGroup->name     = $programName;
            $programGroup->products = zget($productGroup, $programID, array());

            if(in_array($programID, $involvedPrograms))
            {
                $kanbanGroup['my'][] = $programGroup;
            }
            else
            {
                $kanbanGroup['others'][] = $programGroup;
            }
        }

        return $kanbanGroup;
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

        /* Compute executions and projects progress. */
        $tasks = $this->dao->select('id, project, estimate, consumed, `left`, status, closedReason, execution')
            ->from(TABLE_TASK)
            ->where('(execution')->in($executionPairs)
            ->orWhere('project')->in($projectIdList)
            ->markRight(1)
            ->andWhere('parent')->lt(1)
            ->andWhere('deleted')->eq(0)
            ->fetchGroup('execution', 'id');

        $hours        = $this->computeProjectHours($tasks);
        $projectHours = $this->getProgressList();

        return array($productGroup, $planGroup, $releaseGroup, $projectGroup, $doingExecutions, $hours, $projectHours);
    }

    /**
     * 处理项目集看板中产品数据。
     * Process product data in program Kanban.
     *
     * @param  array  $productGroup
     * @param  array  $planGroup
     * @param  array  $releaseGroup
     * @param  array  $projectGroup
     * @param  array  $doingExecutions
     * @param  array  $hours
     * @param  array  $projectHours
     * @access public
     * @return array
     */
    public function processProductsForKanban(array $productGroup, array $planGroup, array $releaseGroup, array $projectGroup, array $doingExecutions, array $hours, array $projectHours): array
    {
        if(empty($productGroup)) return $productGroup;

        $today = helper::today();
        foreach($productGroup as $programID => $products)
        {
            foreach($products as $product)
            {
                $product->plans = zget($planGroup, $product->id, array());

                /* Convert predefined HTML entities to characters. */
                foreach($product->plans as $plan) $plan->title = htmlspecialchars_decode($plan->title, ENT_QUOTES);
                $product->name = htmlspecialchars_decode($product->name, ENT_QUOTES);

                $product->releases = zget($releaseGroup, $product->id, array());
                $projects          = zget($projectGroup, $product->id, array());
                foreach($projects as $project)
                {
                    if(helper::diffDate($today, $project->end) > 0) $project->delay = 1;
                    if($this->config->systemMode == 'ALM' && !$this->config->program->showAllProjects && $project->parent != $product->program && strpos($project->path, ",{$product->program},") !== 0) continue;

                    $status    = $project->status == 'wait' ? 'wait' : 'doing';
                    $execution = zget($doingExecutions, $project->id, array());

                    if(!empty($execution))
                    {
                        $execution->hours = zget($hours, $execution->id, array());
                        if(helper::diffDate($today, $execution->end) > 0) $execution->delay = 1;
                    }

                    $project->execution = $execution;
                    $project->hours['progress'] = zget($projectHours, $project->id, array());

                    /* Convert predefined HTML entities to characters. */
                    $project->name = htmlspecialchars_decode($project->name, ENT_QUOTES);
                    $product->projects[$status][] = $project;
                }
            }
        }
        return $productGroup;
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
     * 根据按照项目分组的任务，统计各个项目的工时和进度
     * Compute hours and progress for project or execution.
     *
     * @param  array $tasks
     * @access public
     * @return array
     */
    public function computeProjectHours(array $tasks): array
    {
        if(empty($tasks)) return array();

        $hours = array();
        foreach($tasks as $projectID => $projectTasks)
        {
            /* Init hour. */
            $hour = new stdclass();
            $hour->totalConsumed = 0;
            $hour->totalEstimate = 0;
            $hour->totalLeft     = 0;

            /* Compute totalEstimate, totalConsumed, totalLeft to them. */
            foreach($projectTasks as $task)
            {
                $hour->totalConsumed += $task->consumed;
                $hour->totalEstimate += $task->estimate;
                if(strpos('|cancel|closed|done|', "|{$task->status}|") === false) $hour->totalLeft += $task->left;
            }
            $hours[$projectID] = $hour;
        }

        /* 计算进度。四舍五入totalEstimate, totalConsumed, totalLeft. */
        $progressList = $this->loadModel('project')->getWaterfallProgress(array_keys($hours));
        foreach($hours as $projectID => $hour)
        {
            $hour->totalEstimate = round($hour->totalEstimate, 1) ;
            $hour->totalConsumed = round($hour->totalConsumed, 1);
            $hour->totalLeft     = round($hour->totalLeft, 1);
            $hour->totalReal     = $hour->totalConsumed + $hour->totalLeft;
            $hour->progress      = zget($progressList, $projectID, ($hour->totalReal ? round($hour->totalConsumed / $hour->totalReal, 2) * 100 : 0)) ;
        }

        return $hours;
    }

    /**
     * 将工时、团队人数、剩余任务数、团队成员等统计信息，追加到对应的项目中。
     * Append statistics fields to projects.
     *
     * @param  array  $projects
     * @param  string $appendFields  hours,teamCount,leftTasks,teamMembers
     * @param  array  $data          array keys are hours, teams and leftTasks.
     * @access public
     * @return array
     */
    public function appendStatToProjects(array $projects, string $appendFields = '', array $data = array()): array
    {
        if(empty($projects)) return array();
        if(empty($appendFields) || empty($data)) return $projects;

        $appendFields = explode(',', $appendFields);
        $emptyHour    = json_decode(json_encode(array('totalEstimate' => 0, 'totalConsumed' => 0, 'totalLeft' => 0, 'progress' => 0)));
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

            /* Merge the hours. */
            if(in_array('hours', $appendFields))
            {
                $project->hours = $emptyHour;
                if(isset($data['hours'])) $project->hours = zget($data['hours'], $project->id, $emptyHour);
            }

            /* Merge the team and left tasks. */
            if(in_array('teamCount',   $appendFields)) $project->teamCount   = isset($data['teams'][$project->id]) ? count($data['teams'][$project->id]) : 0;
            if(in_array('teamMembers', $appendFields)) $project->teamMembers = isset($data['teams'][$project->id]) ? array_keys($data['teams'][$project->id]) : array();
            if(in_array('leftTasks',   $appendFields)) $project->leftTasks   = isset($data['leftTasks'][$project->id]) ? $data['leftTasks'][$project->id]->tasks : '—';

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
     * @param  object|null $pager
     * @param  string      $programTitle 0|base|end
     * @param  int         $involved
     * @param  bool        $queryAll
     * @access public
     * @return object[]
     */
    public function getProjectList(int $programID = 0, string $browseType = 'all', int $queryID = 0, string $orderBy = 'id_desc', object|null $pager = null, string $programTitle = '', int $involved = 0, bool $queryAll = false): array
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

        $projectList = $this->dao->select('DISTINCT t1.*')->from(TABLE_PROJECT)->alias('t1')
            ->leftJoin(TABLE_TEAM)->alias('t2')->on('t1.id=t2.root')
            ->leftJoin(TABLE_STAKEHOLDER)->alias('t3')->on('t1.id=t3.objectID')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t1.vision')->eq($this->config->vision)
            ->beginIF($browseType == 'bysearch' and $query)->andWhere($query)->fi()
            ->andWhere('t1.type')->eq('project')
            ->beginIF($this->cookie->involved or $involved)->andWhere('t2.type')->eq('project')->fi()
            ->beginIF(!in_array($browseType, array('all', 'undone', 'bysearch', 'review', 'unclosed'), true))->andWhere('t1.status')->eq($browseType)->fi()
            ->beginIF($browseType == 'undone' or $browseType == 'unclosed')->andWhere('t1.status')->in('wait,doing')->fi()
            ->beginIF($browseType == 'review')
            ->andWhere("FIND_IN_SET('{$this->app->user->account}', t1.reviewers)")
            ->andWhere('t1.reviewStatus')->eq('doing')
            ->fi()
            ->beginIF($path)->andWhere('t1.path')->like($path . '%')->fi()
            ->beginIF(!$queryAll and !$this->app->user->admin)->andWhere('t1.id')->in($this->app->user->view->projects)->fi()
            ->beginIF($this->cookie->involved or $involved)
            ->andWhere('t1.openedBy', true)->eq($this->app->user->account)
            ->orWhere('t1.PM')->eq($this->app->user->account)
            ->orWhere('t2.account')->eq($this->app->user->account)
            ->orWhere('(t3.user')->eq($this->app->user->account)
            ->andWhere('t3.deleted')->eq(0)
            ->markRight(1)
            ->orWhere("CONCAT(',', t1.whitelist, ',')")->like("%,{$this->app->user->account},%")
            ->markRight(1)
            ->fi()
            ->orderBy($orderBy)
            ->page($pager, 't1.id')
            ->fetchAll('id');

        /* Determine how to display the name of the program. */
        if($programTitle and $this->config->systemMode == 'ALM') $projectList = $this->batchProcessProgramName($projectList, $programTitle);
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
     * Get stakeholders by program id.
     *
     * @param  int     $programID
     * @param  string  $orderBy
     * @param  object  $paper
     * @access public
     * @return array
     */
    public function getStakeholders($programID = 0, $orderBy = 'id_desc', $pager = null)
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
     * 计算项目和项目集的进度。
     * Get program and project progress list.
     *
     * @access public
     * @return array
     */
    public function getProgressList(): array
    {
        $totalProgress = array();
        $projectCount  = array();
        $userPRJCount  = array();
        $progressList  = array();
        $programPairs  = $this->getPairs();
        $projectStats  = $this->getProjectStats(0, 'all', 0, 'id_desc', null, 0, 0, true);

        /* Add program progress. */
        foreach(array_keys($programPairs) as $programID)
        {
            $totalProgress[$programID] = 0;
            $projectCount[$programID]  = 0;
            $userPRJCount[$programID]  = 0;
            $progressList[$programID]  = 0;

            foreach($projectStats as $project)
            {
                if(strpos($project->path, ',' . $programID . ',') === false) continue;

                /* The number of projects under this program that the user can view. */
                if(strpos(',' . $this->app->user->view->projects . ',', ',' . $project->id . ',') !== false) $userPRJCount[$programID] ++;

                $totalProgress[$programID] += $project->hours->progress;
                $projectCount[$programID] ++;
            }

            if(empty($projectCount[$programID])) continue;

            /* Program progress can't see when this user don't have all projects priv. */
            if(!$this->app->user->admin && $userPRJCount[$programID] != $projectCount[$programID])
            {
                unset($progressList[$programID]);
                continue;
            }

            $progressList[$programID] = round($totalProgress[$programID] / $projectCount[$programID]);
        }

        /* Add project progress. */
        foreach($projectStats as $project) $progressList[$project->id] = $project->hours->progress;

        return $progressList;
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
            ->setDefault('code', '')
            ->setDefault('openedDate', helper::now())
            ->setIF($this->post->acl == 'open', 'whitelist', '')
            ->setIF($this->post->delta == 999, 'end', LONG_TIME)
            ->setIF($this->post->budget != 0, 'budget', round((float)$this->post->budget, 2))
            ->setIF(!isset($_POST['whitelist']), 'whitelist', '')
            ->add('type', 'program')
            ->join('whitelist', ',')
            ->stripTags($this->config->program->editor->create['id'], $this->config->allowedTags)
            ->remove('delta,future,contactListMenu,uid')
            ->get();

        /* Redefines the language entries for the fields in the project table. */
        foreach(explode(',', $this->config->program->create->requiredFields) as $field)
        {
            if(isset($this->lang->program->$field)) $this->lang->project->$field = $this->lang->program->$field;
        }

        $this->lang->error->unique = $this->lang->error->repeat;
        $program = $this->loadModel('file')->processImgURL($program, $this->config->program->editor->create['id'], $this->post->uid);
        $this->dao->insert(TABLE_PROGRAM)->data($program)
            ->autoCheck()
            ->batchcheck($this->config->program->create->requiredFields, 'notempty')
            ->checkIF($program->begin != '', 'begin', 'date')
            ->checkIF($program->end != '', 'end', 'date')
            ->checkIF($program->end != '', 'end', 'gt', $program->begin)
            ->checkIF(!empty($program->name), 'name', 'unique', "`type`='program' and `parent` = $program->parent and `deleted` = '0'")
            ->checkFlow()
            ->exec();

        if(!dao::isError())
        {
            $programID = $this->dao->lastInsertId();
            $this->dao->update(TABLE_PROGRAM)->set('`order`')->eq($programID * 5)->where('id')->eq($programID)->exec(); // Save order.

            $whitelist = explode(',', $program->whitelist);
            $this->loadModel('personnel')->updateWhitelist($whitelist, 'program', $programID);

            $this->file->updateObjectID($this->post->uid, $programID, 'program');
            $this->setTreePath($programID);

            if($program->acl != 'open') $this->loadModel('user')->updateUserView($programID, 'program');

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
        $this->app->loadLang('project');
        $programID  = (int)$programID;
        $oldProgram = $this->dao->findById($programID)->from(TABLE_PROGRAM)->fetch();

        $program = fixer::input('post')
            ->add('id', $programID)
            ->setDefault('team', $this->post->name)
            ->setDefault('end', '')
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', helper::now())
            ->setIF($this->post->begin == '0000-00-00', 'begin', '')
            ->setIF($this->post->end   == '0000-00-00', 'end', '')
            ->setIF($this->post->delta == 999, 'end', LONG_TIME)
            ->setIF($this->post->realBegan != '' and $oldProgram->status == 'wait', 'status', 'doing')
            ->setIF($this->post->future, 'budget', 0)
            ->setIF($this->post->budget != 0, 'budget', round($this->post->budget, 2))
            ->setIF(!isset($_POST['budgetUnit']), 'budgetUnit', $oldProgram->budgetUnit)
            ->setIF(!isset($_POST['whitelist']), 'whitelist', '')
            ->join('whitelist', ',')
            ->stripTags($this->config->program->editor->edit['id'], $this->config->allowedTags)
            ->remove('id,uid,delta,future,syncPRJUnit,exchangeRate,contactListMenu')
            ->get();

        $program  = $this->loadModel('file')->processImgURL($program, $this->config->program->editor->edit['id'], $this->post->uid);

        if($program->parent)
        {
            $this->dao->update(TABLE_MODULE)
                ->set('root')->eq($program->parent)
                ->where('root')->eq($programID)
                ->andwhere('type')->eq('line')
                ->exec();
        }
        if(dao::isError()) return false;

        /* Redefines the language entries for the fields in the project table. */
        foreach(explode(',', $this->config->program->edit->requiredFields) as $field)
        {
            if(isset($this->lang->program->$field)) $this->lang->project->$field = $this->lang->program->$field;
        }

        $this->lang->error->unique = $this->lang->error->repeat;
        $this->dao->update(TABLE_PROGRAM)->data($program)
            ->autoCheck($skipFields = 'begin,end')
            ->batchcheck($this->config->program->edit->requiredFields, 'notempty')
            ->checkIF($program->begin != '', 'begin', 'date')
            ->checkIF($program->end != '', 'end', 'date')
            ->checkIF($program->end != '', 'end', 'gt', $program->begin)
            ->checkIF(!empty($program->name), 'name', 'unique', "id!=$programID and `type`='program' and `parent` = $program->parent and `deleted` = '0'")
            ->checkFlow()
            ->where('id')->eq($programID)
            ->limit(1)
            ->exec();

        if(!dao::isError())
        {
            /* If the program changes, the budget unit will be updated to the project and sub-programs simultaneously. */
            if($program->budgetUnit != $oldProgram->budgetUnit and $_POST['syncPRJUnit'] == 'true')
            {
                $this->dao->update(TABLE_PROJECT)
                    ->set('budgetUnit')->eq($program->budgetUnit)
                    ->beginIF(!empty($_POST['exchangeRate']))->set("budget = {$_POST['exchangeRate']} * `budget`")->fi()
                    ->where('path')->like(",{$programID},%")
                    ->andWhere('type')->in('program,project')
                    ->exec();
            }

            $this->file->updateObjectID($this->post->uid, $programID, 'project');
            $whitelist = explode(',', $program->whitelist);
            $this->loadModel('personnel')->updateWhitelist($whitelist, 'program', $programID);
            $this->loadModel('user');
            if($program->acl != 'open') $this->user->updateUserView($programID, 'program');

            /* If the program changes, the authorities of programs and projects under the program should be refreshed. */
            $children = $this->dao->select('id, type')->from(TABLE_PROGRAM)->where('path')->like("%,{$programID},%")->andWhere('id')->ne($programID)->andWhere('acl')->eq('program')->fetchPairs('id', 'type');
            foreach($children as $id => $type) $this->user->updateUserView($id, $type);
            if(isset($program->PM) and $program->PM != $oldProgram->PM)
            {
                $productIdList = $this->dao->select('id')->from(TABLE_PRODUCT)->where('program')->eq($programID)->fetchPairs('id');
                foreach($productIdList as $productID) $this->user->updateUserView($productID, 'product');
            }

            if($oldProgram->parent != $program->parent)
            {
                $this->processNode($programID, $program->parent, $oldProgram->path, $oldProgram->grade);

                /* Move product to new top program. */
                $oldTopProgram = $this->getTopByPath($oldProgram->path);
                $newTopProgram = $this->getTopByID($programID);

                if($oldTopProgram != $newTopProgram)
                {
                    if($oldProgram->parent == 0)
                    {
                        $this->dao->update(TABLE_PRODUCT)->set('program')->eq($newTopProgram)->where('program')->eq($oldTopProgram)->exec();
                    }
                    else
                    {
                        /* Get the shadow products that produced by the program's no product projects. */
                        $shadowProducts = $this->dao->select('t1.id')->from(TABLE_PRODUCT)->alias('t1')
                            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')->on('t1.id=t2.product')
                            ->leftJoin(TABLE_PROJECT)->alias('t3')->on('t2.project=t3.id')
                            ->where('t3.path')->like("%,$programID,%")
                            ->andWhere('t3.type')->eq('project')
                            ->andWhere('t3.hasProduct')->eq('0')
                            ->andWhere('t1.shadow')->eq('1')
                            ->fetchPairs();
                        $this->dao->update(TABLE_PRODUCT)->set('program')->eq($newTopProgram)->where('id')->in($shadowProducts)->exec();
                    }

                }
            }

            return common::createChanges($oldProgram, $program);
        }
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
    public function close(object $program, object $oldProgram) :array|bool
    {
        $program = $this->loadModel('file')->processImgURL($program, $this->config->program->editor->close['id'], $this->post->uid);
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
    public function activate(object $program, $oldProgram) :array|false
    {
        if($program->begin > $program->end)
        {
            dao::$errors['end'] = sprintf($this->lang->error->ge, $this->lang->program->end, $this->lang->program->begin);
            return false;
        }

        if(!helper::isZeroDate($oldProgram->realBegan)) $program->realBegan = helper::today();
        $program = $this->loadModel('file')->processImgURL($program, $this->config->program->editor->activate['id'], $this->post->uid);

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
            $link     = $this->getLink($moduleName, $methodName, $program->id, $vars, $from);
            $linkHtml = html::a($link, $program->name, '', "id='program$program->id' class='text-ellipsis programName' title=$program->name");

            if(isset($programMenu[$program->id]) and !empty($programMenu[$program->id]))
            {
                if(!isset($programMenu[$program->parent])) $programMenu[$program->parent] = '';
                $programMenu[$program->parent] .= "<li>$linkHtml";
                $programMenu[$program->parent] .= "<ul>" . $programMenu[$program->id] . "</ul>\n";
            }
            else
            {
                if(empty($programMenu[$program->parent])) $programMenu[$program->parent] = '';
                $programMenu[$program->parent] .= "<li>$linkHtml\n";
            }
            $programMenu[$program->parent] .= "</li>\n";
        }

        krsort($programMenu);
        $programMenu = array_pop($programMenu);
        $lastMenu    = "<ul class='tree tree-simple' data-ride='tree' id='programTree' data-name='tree-program'>{$programMenu}</ul>\n";

        return $lastMenu;
    }

    /**
     * Get link for drop tree menu.
     *
     * @param  string $moduleName
     * @param  string $methodName
     * @param  int    $programID
     * @param  string $vars
     * @param  string $from
     * @access public
     * @return string
     */
    public function getLink($moduleName, $methodName, $programID, $vars = '', $from = 'program')
    {
        if($from != 'program') return helper::createLink('product', 'all', "programID={$programID}" . $vars);

        if($moduleName == 'project')
        {
            $moduleName = 'program';
            $methodName = 'project';
        }
        if($moduleName == 'product')
        {
            $moduleName = 'program';
            $methodName = 'product';
        }

        return helper::createLink($moduleName, $methodName, "programID={$programID}");
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
    public function getTopByPath($path): int
    {
        $paths = explode(',', trim($path, ','));
        return (int)$paths[0];
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
     * 该项目集下未关闭的子项目集或项目的数量。
     * Judge whether there is an unclosed programs or projects.
     *
     * @param  object  $program
     * @access public
     * @return int
     */
    public function hasUnfinished(object $program): int
    {
        return $this->dao->select("count(IF(id != {$program->id}, 1, 0)) as count")->from(TABLE_PROJECT)
            ->where('type')->in('program, project')
            ->andWhere('path')->like($program->path . '%')
            ->andWhere('status')->ne('closed')
            ->andWhere('deleted')->eq('0')
            ->fetch('count');
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
        $this->loadModel('user')->updateUserView($programID, 'program', $accounts);

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
     * Get budget left.
     *
     * @param  int    $parentProgram
     * @param  int    $leftBudget
     * @access public
     * @return int
     */
    public function getBudgetLeft($parentProgram, $leftBudget = 0)
    {
        if(empty($parentProgram->id)) return;

        $childGrade     = $parentProgram->grade + 1;
        $childSumBudget = $this->dao->select("sum(budget) as sumBudget")->from(TABLE_PROGRAM)
            ->where('path')->like("%,{$parentProgram->id},%")
            ->andWhere('grade')->eq($childGrade)
            ->andWhere('deleted')->eq('0')
            ->fetch('sumBudget');

        $leftBudget += (float)$parentProgram->budget - (float)$childSumBudget;

        if($parentProgram->budget == 0 and $parentProgram->parent)
        {
            $parentParent = $this->getById($parentProgram->parent);
            return $this->getBudgetLeft($parentParent, $leftBudget);
        }
        else
        {
            return $leftBudget;
        }
    }

    /**
     * Get program parent pairs
     *
     * @param  string $model
     * @param  string $mode       noclosed|all
     * @param  bool   $showRoot
     * @access public
     * @return array
     */
    public function getParentPairs($model = '', $mode = 'noclosed', $showRoot = true)
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
            if(strpos($mode, 'all') !== false and strpos(",{$this->app->user->view->programs},", ",{$module->id},") === false and (!$this->app->user->admin)) continue;

            $moduleName    = $showRoot ? '/' : '';
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
        $topMenu = empty($topMenu) ? '' : $topMenu;
        $topMenu = explode("\n", trim($topMenu));
        $showRoot ? $lastMenu[] = '/' : $lastMenu = array();
        foreach($topMenu as $menu)
        {
            if(strpos($menu, '|') === false) continue;
            list($label, $moduleID) = explode('|', $menu);
            $lastMenu[$moduleID] = str_replace('&#166;', '|', $label);
        }

        return $lastMenu;
    }

    /**
     * Get parent PM by programIdList.
     *
     * @param  array $programIdList
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
            $path = substr($childNode->path, strpos($childNode->path, ",{$programID},"));

            /* Only program and project sets update grade. */
            $grade = in_array($childNode->type, array('program', 'project')) ? $childNode->grade - $oldGrade + 1 : $childNode->grade;
            if($parent)
            {
                $path  = rtrim($parent->path, ',') . $path;
                $grade =  in_array($childNode->type, array('program', 'project'))? $parent->grade + $grade : $grade;
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
     * @param  int         $involved
     * @param  bool        $queryAll
     * @access public
     * @return array
     */
    public function getProjectStats(int $programID = 0, string $browseType = 'undone', int $queryID = 0, string $orderBy = 'id_desc', object|null $pager = null, string $programTitle = '', int $involved = 0, bool $queryAll = false)
    {
        if(commonModel::isTutorialMode()) return $this->loadModel('tutorial')->getProjectStats($browseType);

        /* Init vars. */
        $projects = $this->getProjectList($programID, $browseType, $queryID, $orderBy, $pager, $programTitle, $involved, $queryAll);
        if(empty($projects)) return array();

        $projectKeys = array_keys($projects);
        $executions  = $this->loadModel('project')->getExecutionList($projectKeys);

        /* Get all tasks and compute totalEstimate, totalConsumed, totalLeft, progress according to them. */
        $tasks = $this->dao->select('id, project, estimate, consumed, `left`, status, closedReason, execution')
            ->from(TABLE_TASK)
            ->where('project')->in($projectKeys)
            ->andWhere('execution')->in(array_keys($executions))
            ->andWhere('parent')->lt(1)
            ->andWhere('deleted')->eq(0)
            ->fetchGroup('project', 'id');
        $hours = $this->computeProjectHours($tasks);

        /* Get the number of left tasks. */
        $leftTasks = array();
        if($this->cookie->projectType && $this->cookie->projectType == 'bycard')
        {
            $leftTasks = $this->dao->select('t2.parent as project, count(*) as tasks')->from(TABLE_TASK)->alias('t1')
                ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.execution = t2.id')
                ->where('t1.execution')->in(array_keys($executions))
                ->andWhere('t1.status')->notIn('cancel,closed')
                ->groupBy('t2.parent')
                ->fetchAll('project');
        }

        /* Get the members of project teams. */
        $teamMembers = $this->dao->select('t1.root,t1.account')->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account=t2.account')
            ->where('t1.root')->in($projectKeys)
            ->andWhere('t1.type')->eq('project')
            ->andWhere('t2.deleted')->eq(0)
            ->fetchGroup('root', 'account');

        $stats = $this->appendStatToProjects($projects, 'hours,teamCount,teamMembers,leftTasks', array('hours' => $hours, 'teams' => $teamMembers, 'leftTasks' => $leftTasks));
        foreach($stats as $project) $project->name = htmlspecialchars_decode($project->name, ENT_QUOTES); //  Convert predefined HTML entities to characters.

        return $stats;
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
      $projectList = $this->getProjectList($programID);
      if(!$projectList) return array();

      $users = $this->dao->select("t2.id, t2.account, t2.realname")->from(TABLE_TEAM)->alias('t1')
          ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account = t2.account')
          ->where('t1.root')->in(array_keys($projectList))
          ->andWhere('t1.type')->eq('project')
          ->andWhere('t2.deleted')->eq(0)
          ->fetchAll('account');
      if(!$users) return array();

      foreach($users as $account => $user)
      {
        $firstLetter = ucfirst(substr($user->account, 0, 1)) . ':';
        if(!empty($this->config->isINT)) $firstLetter = '';
        $users[$account] = $firstLetter . ($user->realname ? $user->realname : $user->account);
      }

      return $users;
    }

    /**
     * Build program action menu.
     *
     * @param  object $program
     * @param  string $type
     * @access public
     * @return string
     */
    public function buildOperateMenu($program, $type = 'view')
    {
        $menu   = '';
        $params = "programID=$program->id";
        if($program->type == 'program' && strpos(",{$this->app->user->view->programs},", ",$program->id,") !== false)
        {
            if($program->status == 'wait' || $program->status == 'suspended')
            {
                $menu .= $this->buildMenu('program', 'start', $params, $program, $type, 'play', '', 'iframe', true, '', $this->lang->program->start);
            }
            if($program->status == 'doing')
            {
                $menu .= $this->buildMenu('program', 'close', $params, $program, $type, 'off', '', 'iframe', true);
            }
            if($program->status == 'closed')
            {
                $menu .= $this->buildMenu('program', 'activate', $params, $program, $type, 'magic', '', 'iframe', true);
            }
            if(common::hasPriv('program', 'suspend') || (common::hasPriv('program', 'close') && $program->status != 'doing') || (common::hasPriv('program', 'activate') && $program->status != 'closed'))
            {
                $menu .= "<div class='btn-group'>";
                $menu .= "<button type='button' class='btn icon-caret-down dropdown-toggle' data-toggle='context-dropdown' title='{$this->lang->more}' style='width: 16px; padding-left: 0px;'></button>";
                $menu .= "<ul class='dropdown-menu pull-right text-center' role='menu'>";
                $menu .= $this->buildMenu('program', 'suspend', $params, $program, $type, 'pause', '', 'iframe btn-action btn', true, '', $this->lang->program->suspend);
                if($program->status != 'doing')  $menu .= $this->buildMenu('program', 'close',    $params, $program, $type, 'off', '',   'iframe btn-action btn', true);
                if($program->status != 'closed') $menu .= $this->buildMenu('program', 'activate', $params, $program, $type, 'magic', '', 'iframe btn-action btn', true);
                $menu .= "</ul>";
                $menu .= "</div>";
            }

            $disabled = $program->status == 'closed' ? " disabled='disabled' style='pointer-events: none;'" : '';
            $menu .= $this->buildMenu('program', 'edit',   $params, $program, $type, 'edit');
            $menu .= $this->buildMenu('program', 'create', $params, $program, $type, 'split', '', '', '', $disabled, $this->lang->program->children);
            if(common::hasPriv('program', 'delete'))
            {
                $menu .= $this->buildMenu('program', 'delete', $params, $program, $type, 'trash', 'hiddenwin', '', '', '', $this->lang->program->delete);
            }
        }
        elseif($program->type == 'project')
        {
            if($program->status == 'wait' || $program->status == 'suspended')
            {
                $menu .= $this->buildMenu('project', 'start', $params, $program, $type, 'play', '', 'iframe', true);
            }
            if($program->status == 'doing')  $menu .= $this->buildMenu('project', 'close',    $params, $program, $type, 'off',   '', 'iframe', true);
            if($program->status == 'closed') $menu .= $this->buildMenu('project', 'activate', $params, $program, $type, 'magic', '', 'iframe', true);
            if(common::hasPriv('project', 'suspend') || (common::hasPriv('project', 'close') && $program->status != 'doing') || (common::hasPriv('project', 'activate') && $program->status != 'closed'))
            {
                $menu .= "<div class='btn-group'>";
                $menu .= "<button type='button' class='btn icon-caret-down dropdown-toggle' data-toggle='context-dropdown' title='{$this->lang->more}' style='width: 16px; padding-left: 0px;'></button>";
                $menu .= "<ul class='dropdown-menu pull-right text-center' role='menu'>";
                $menu .= $this->buildMenu('project', 'suspend', $params, $program, $type, 'pause', '', 'iframe btn-action btn', true);
                if($program->status != 'doing')  $menu .= $this->buildMenu('project', 'close',    $params, $program, $type, 'off',   '', 'iframe btn-action btn', true);
                if($program->status != 'closed') $menu .= $this->buildMenu('project', 'activate', $params, $program, $type, 'magic', '', 'iframe btn-action btn', true);
                $menu .= "</ul>";
                $menu .= "</div>";
            }

            $menu .= $this->buildMenu('project', 'edit',  $params, $program, $type, 'edit',  '', 'iframe', true);
            $menu .= $this->buildMenu('project', 'team',  $params, $program, $type, 'group', '', '',   '', 'data-app="project"');

            $disabledGroup = $program->model == 'kanban' ? " disabled='disabled' style='pointer-events: none;'" : '';
            $menu         .= $this->buildMenu('project', 'group', $params, $program, $type, 'lock',  '', '',   '', 'data-app="project"' . $disabledGroup);
            if(common::hasPriv('project', 'manageProducts') || common::hasPriv('project', 'whitelist') || common::hasPriv('project', 'delete'))
            {
                $menu .= "<div class='btn-group'>";
                $menu .= "<button type='button' class='btn dropdown-toggle' data-toggle='context-dropdown' title='{$this->lang->more}'><i class='icon-ellipsis-v'></i></button>";
                $menu .= "<ul class='dropdown-menu pull-right text-center' role='menu'>";
                $menu .= $this->buildMenu('project', 'manageProducts', $params, $program, $type, 'link', '', 'btn-action', '', "data-app='project'" . ($program->hasProduct ? '' : " disabled='disabled'"));

                $disabledWhitelist = $program->acl == 'open' ? " disabled='disabled' style='pointer-events: none;'" : '';
                $menu             .= $this->buildMenu('project', 'whitelist', "$params&module=project&from=browse", $program, $type, 'shield-check', '', 'btn-action', '', "data-app='project'" . $disabledWhitelist);
                if(common::hasPriv('project','delete'))
                {
                    $menu .= $this->buildMenu("project", "delete", $params, $program, $type, 'trash', 'hiddenwin', 'btn-action', '', "data-group='program'", $this->lang->delete);
                }
                $menu .= "</ul>";
                $menu .= "</div>";
            }
        }
        return $menu;
    }

    /**
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

    /*
     * Build row data.
     *
     * @param  string $program
     * @param  array  $PMList
     * @param  array  $progressList
     * @access public
     * @return object
     */
    public function buildRowData($program, $PMList, $progressList)
    {
        $row = new stdclass();

        $manager       = isset($PMList[$program->PM]) ? $PMList[$program->PM] : '';
        $programBudget = $this->project->getBudgetWithUnit($program->budget);
        $link          = $program->type == 'program' ? helper::createLink('program', 'product', "programID=$program->id") : helper::createLink('project', 'index', "projectID=$program->id");
        $name          = html::a($link, $program->name, '', "title=$program->name");
        if($program->status != 'done' and $program->status != 'closed' and $program->status != 'suspended')
        {
            $delay = helper::diffDate(helper::today(), $program->end);
            if($delay > 0) $name .= "<span class='label label-danger label-badge'>{$this->lang->project->statusList['delay']}</span>";
        }

        $row->id       = $program->id;
        $row->parent   = $program->parent ? $program->parent : '';
        $row->asParent = $program->type == 'program';
        $row->type     = $program->type;
        $row->model    = $program->model;
        $row->name     = $name;
        $row->status   = $program->status;
        $row->PM       = empty($manager) ? '' : $manager->realname;
        $row->PMAvatar = empty($manager) ? '' : $manager->avatar;
        $row->budget   = $program->budget != 0 ? zget($this->lang->project->currencySymbol, $program->budgetUnit) . ' ' . $programBudget : $this->lang->project->future;
        $row->begin    = $program->begin;
        $row->end      = $program->end == LONG_TIME ? $this->lang->program->longTime : $program->end;
        $row->progress = isset($progressList[$program->id]) ? round($progressList[$program->id]) : 0;
        $row->actions  = $this->buildActions($program);

        return $row;
    }

    /**
     * Build actions data.
     *
     * @param  object $program
     * @access public
     * @return array
     */
    public function buildActions($program)
    {
        if($program->type == 'program') return $this->programTao->buildProgramActionsMap($program);
        if($program->type == 'project') return $this->programTao->buildProjectActionsMap($program);
        return array();
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
}
