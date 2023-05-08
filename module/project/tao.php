<?php
declare(strict_types=1);
/**
 * The tao file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      sunguangming <sunguangming@easycorp.ltd>
 * @link        https://www.zentao.net
 */
class projectTao extends projectModel
{
    /**
     * Update project table when start a project.
     *
     * @param  int    $projectID
     * @param  object $project
     * @access protected
     * @return bool
     */
    protected function doStart(int $projectID, object $project): bool
    {
        $this->dao->update(TABLE_PROJECT)->data($project)
            ->autoCheck()
            ->check($this->config->project->start->requiredFields, 'notempty')
            ->checkIF($project->realBegan != '', 'realBegan', 'le', helper::today())
            ->checkFlow()
            ->where('id')->eq($projectID)
            ->exec();

        return !dao::isError();
    }

    /**
     * Update project table when suspend a project.
     *
     * @param  int    $projectID
     * @param  object $project
     *
     * @access protected
     * @return bool
     */
    protected function doSuspend(int $projectID, object $project): bool
    {
        $this->dao->update(TABLE_PROJECT)->data($project)
            ->autoCheck()
            ->checkFlow()
            ->where('id')->eq($projectID)
            ->exec();

        return !dao::isError();
    }

    /**
     * Update project table when close a project.
     *
     * @param  int    $projectID
     * @param  object $project
     * @param  object $oldProject
     *
     * @access protected
     * @return bool
     */
    protected function doClosed(int $projectID, object $project, object $oldProject): bool
    {
        $this->lang->error->ge = $this->lang->project->ge;
        $this->dao->update(TABLE_PROJECT)->data($project)
            ->autoCheck()
            ->check($this->config->project->close->requiredFields, 'notempty')
            ->checkIF($project->realEnd != '', 'realEnd', 'le', helper::today())
            ->checkIF($project->realEnd != '', 'realEnd', 'ge', $oldProject->realBegan)
            ->checkFlow()
            ->where('id')->eq($projectID)
            ->exec();

        return !dao::isError();
    }

    /**
     * Update project table when activate a project.
     *
     * @param  int    $projectID
     * @param  object $project
     * @access protected
     * @return bool
     */
    protected function doActivate(int $projectID ,object $project): bool
    {
        $this->dao->update(TABLE_PROJECT)->data($project , 'readjustTime, readjustTask, comment')
            ->autoCheck()
            ->checkFlow()
            ->where('id')->eq((int)$projectID)
            ->exec();

        return !dao::isError();
    }

    /**
     * Update project table when edit a project.
     *
     * @param  int    $projectID
     * @param  object $project
     * @param  object $oldProject
     * @access protected
     * @return bool
     */
    protected function doUpdate(int $projectID ,object $project, object $oldProject): bool
    {
        $this->dao->update(TABLE_PROJECT)->data($project)
            ->autoCheck('begin,end')
            ->batchcheck($requiredFields, 'notempty')
            ->checkIF($project->begin != '', 'begin', 'date')
            ->checkIF($project->end != '', 'end', 'date')
            ->checkIF($project->end != '', 'end', 'gt', $project->begin)
            ->checkIF(!empty($project->name), 'name', 'unique', "id != $projectID and `type` = 'project' and `parent` = '$oldProject->parent' and `model` = '{$project->model}' and `deleted` = '0'")
            ->checkIF(!empty($project->code), 'code', 'unique', "id != $projectID and `type` = 'project' and `model` = '{$project->model}' and `deleted` = '0'")
            ->checkFlow()
            ->where('id')->eq($projectID)
            ->remove('products,branch,plans,delta,future,contactListMenu,teamMembers')
            ->exec();

        return !dao::isError();
    }

    /**
     * Fetch undone tasks.
     *
     * @param  int $projectID
     * @access protected
     * @return array
     */
    protected function fetchUndoneTasks(int $projectID): array
    {
        return $this->dao->select('id,estStarted,deadline,status')->from(TABLE_TASK)
            ->where('deadline')->notZeroDate()
            ->andWhere('status')->in('wait,doing')
            ->andWhere('project')->eq($projectID)
            ->fetchAll();

    }

    /**
     * Update start and end date of tasks.
     *
     * @param  array $tasks
     * @access protected
     * @return bool
     */
    protected function updateTasksStartAndEndDate(array $tasks, object $oldProject, object $project): bool
    {
        $beginTimeStamp = strtotime($project->begin);

        foreach($tasks as $task)
        {
            if($task->status == 'wait' and !helper::isZeroDate($task->estStarted))
            {
                $taskDays   = helper::diffDate($task->deadline, $task->estStarted);
                $taskOffset = helper::diffDate($task->estStarted, $oldProject->begin);

                $estStartedTimeStamp = $beginTimeStamp + $taskOffset * 24 * 3600;
                $estStarted = date('Y-m-d', $estStartedTimeStamp);
                $deadline   = date('Y-m-d', $estStartedTimeStamp + $taskDays * 24 * 3600);

                if($estStarted > $project->end) $estStarted = $project->end;
                if($deadline > $project->end)   $deadline   = $project->end;

                $this->dao->update(TABLE_TASK)
                    ->set('estStarted')->eq($estStarted)
                    ->set('deadline')->eq($deadline)
                    ->where('id')->eq($task->id)
                    ->exec();

                if(dao::isError()) return false;
            }
            else
            {
                $taskOffset = helper::diffDate($task->deadline, $oldProject->begin);
                $deadline   = date('Y-m-d', $beginTimeStamp + $taskOffset * 24 * 3600);

                if($deadline > $project->end) $deadline = $project->end;
                $this->dao->update(TABLE_TASK)->set('deadline')->eq($deadline)->where('id')->eq($task->id)->exec();

                if(dao::isError()) return false;
            }
        }

        return true;
    }

    /**
     * Get project details, including all contents of the project.
     * 获取项目的详情，包含project表的所有内容。
     *
     * @param  int       $projectID
     * @access protected
     * @return object|false
     */
    protected function fetchProjectInfo(int $projectID): object|false
    {
        $project = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($projectID)->fetch();

        /* Filter the date is empty or 1970. */
        if($project and helper::isZeroDate($project->end)) $project->end = '';
        return $project;
    }

    /**
     * 创建项目后，将团队成员插入到TEAM表.
     * Insert into zt_team after create a project.
     *
     * @param  int    $projectID
     * @param  object $project
     * @param  object $postData
     * @access protected
     * @return array
     */
    protected function setProjectTeam(int $projectID, object $project, object $postData): array
    {
        $this->loadModel('execution');

        /* Set team of project. */
        $members = isset($postData->rawdata->teamMembers) ? $postData->rawdata->teamMembers : array();
        array_push($members, $project->PM, $project->openedBy);
        $members = array_unique($members);
        $roles   = $this->loadModel('user')->getUserRoles(array_values($members));

        $teamMembers = array();
        foreach($members as $account)
        {
            if(empty($account)) continue;

            $member = new stdClass();
            $member->root    = $projectID;
            $member->type    = 'project';
            $member->account = $account;
            $member->role    = zget($roles, $account, '');
            $member->join    = helper::now();
            $member->days    = zget($project, 'days', 0);
            $member->hours   = $this->config->execution->defaultWorkhours;
            $this->dao->insert(TABLE_TEAM)->data($member)->exec();
            $teamMembers[$account] = $member;
        }

        return $teamMembers;
    }

    /**
     * 创建项目后，创建默认的项目主库.
     * Create doclib after create a project.
     *
     * @param  int    $projectID
     * @param  object $project
     * @param  object $program
     * @access protected
     * @return bool
     */
    protected function createDocLib(int $projectID, object $project, object $program): bool
    {
        /* Create doc lib. */
        $this->app->loadLang('doc');
        $authorizedUsers = array();

        if($project->parent and $project->acl == 'program')
        {
            $stakeHolders    = $this->loadModel('stakeholder')->getStakeHolderPairs($project->parent);
            $authorizedUsers = array_keys($stakeHolders);

            foreach(explode(',', $project->whitelist) as $user)
            {
                if(empty($user)) continue;
                $authorizedUsers[$user] = $user;
            }

            $authorizedUsers[$project->PM]       = $project->PM;
            $authorizedUsers[$project->openedBy] = $project->openedBy;
            $authorizedUsers[$program->PM]       = $program->PM;
            $authorizedUsers[$program->openedBy] = $program->openedBy;
        }

        $lib = new stdclass();
        $lib->project   = $projectID;
        $lib->name      = $this->lang->doclib->main['project'];
        $lib->type      = 'project';
        $lib->main      = '1';
        $lib->acl       = 'default';
        $lib->users     = ',' . implode(',', array_filter($authorizedUsers)) . ',';
        $lib->vision    = zget($project, 'vision', 'rnd');
        $lib->addedBy   = $this->app->user->account;
        $lib->addedDate = helper::now();
        $this->dao->insert(TABLE_DOCLIB)->data($lib)->exec();

        return !dao::isError();
    }

    /**
     * 创建项目时，如果直接输入了产品名，则创建产品并与项目关联.
     * Create doclib after create a project.
     *
     * @param  int    $projectID
     * @param  object $project
     * @param  object $postData
     * @param  object $program
     * @access protected
     * @return bool
     */
    protected function createProduct(int $projectID, object $project, object $postData, object $program): bool
    {
        /* If parent not empty, link products or create products. */
        $product = new stdclass();
        $product->name           = $project->hasProduct && !empty($postData->rawdata->productName) ? $postData->rawdata->productName : zget($project, 'name', '');
        $product->shadow         = zget($project, 'vision', 'rnd') == 'rnd' ? (int)empty($project->hasProduct) : 1;
        $product->bind           = $postData->rawdata->parent ? 0 : 1;
        $product->program        = $project->parent ? current(array_filter(explode(',', $program->path))) : 0;
        $product->acl            = $project->acl == 'open' ? 'open' : 'private';
        $product->PO             = $project->PM;
        $product->QD             = '';
        $product->RD             = '';
        $product->whitelist      = '';
        $product->createdBy      = $this->app->user->account;
        $product->createdDate    = helper::now();
        $product->status         = 'normal';
        $product->line           = 0;
        $product->desc           = '';
        $product->createdVersion = $this->config->version;
        $product->vision         = zget($project, 'vision', 'rnd');

        $this->app->loadConfig('product');
        $this->dao->insert(TABLE_PRODUCT)->data($product)
            ->batchCheck($this->config->product->create->requiredFields, 'notempty')
            ->checkIF(!empty($product->name), 'name', 'unique', "`program` = {$product->program} and `deleted` = '0'")
            ->exec();
        if(dao::isError()) return false;

        $productID = $this->dao->lastInsertId();
        if(!$project->hasProduct) $this->loadModel('personnel')->updateWhitelist($whitelist, 'product', $productID);
        $this->loadModel('action')->create('product', $productID, 'opened');
        $this->dao->update(TABLE_PRODUCT)->set('`order`')->eq($productID * 5)->where('id')->eq($productID)->exec();
        if($product->acl != 'open') $this->loadModel('user')->updateUserView($productID, 'product');

        $projectProduct = new stdclass();
        $projectProduct->project = $projectID;
        $projectProduct->product = $productID;
        $projectProduct->branch  = 0;
        $projectProduct->plan    = 0;

        $this->dao->insert(TABLE_PROJECTPRODUCT)->data($projectProduct)->exec();

        if($project->hasProduct)
        {
            /* Create doc lib. */
            $this->app->loadLang('doc');
            $lib = new stdclass();
            $lib->product   = $productID;
            $lib->name      = $this->lang->doclib->main['product'];
            $lib->type      = 'product';
            $lib->main      = '1';
            $lib->acl       = 'default';
            $lib->addedBy   = $this->app->user->account;
            $lib->addedDate = helper::now();
            $this->dao->insert(TABLE_DOCLIB)->data($lib)->exec();
        }

        return !dao::isError();
    }

    /**
     * 获取创建项目时选择的产品数量.
     * Get products count from post.
     *
     * @param  object $project
     * @param  object $rawdata
     * @param  object $program
     * @access protected
     * @return bool
     */
    protected function getLinkedProductsCount(object $project, object $rawdata): int
    {
        $linkedProductsCount = 0;
        if($project->hasProduct && isset($rawdata->products))
        {
            foreach($rawdata->products as $product)
            {
                if(!empty($product)) $linkedProductsCount++;
            }
        }

        return $linkedProductsCount;
    }

    /**
     * 创建项目后，将项目创建者加到项目管理员分组.
     * Create project admin after create a project.
     *
     * @param  int $projectID
     * @access protected
     * @return bool
     */
    protected function addProjectAdmin(int $projectID): bool
    {
        $projectAdmin = $this->dao->select('t1.*')->from(TABLE_PROJECTADMIN)->alias('t1')
            ->leftJoin(TABLE_GROUP)->alias('t2')->on('t1.`group` = t2.id')
            ->where('t1.account')->eq($this->app->user->account)
            ->andWhere('t2.role')->eq('projectAdmin')
            ->fetch();

        if(!empty($projectAdmin))
        {
            $newProject = $projectAdmin->projects . ",$projectID";
            $this->dao->update(TABLE_PROJECTADMIN)->set('projects')->eq($newProject)->where('account')->eq($projectAdmin->account)->andWhere('`group`')->eq($projectAdmin->group)->exec();
        }
        else
        {
            $projectAdminID = $this->dao->select('id')->from(TABLE_GROUP)->where('role')->eq('projectAdmin')->fetch('id');

            $projectAdmin = new stdclass();
            $projectAdmin->account  = $this->app->user->account;
            $projectAdmin->group    = $projectAdminID;
            $projectAdmin->projects = $projectID;
            $this->dao->replace(TABLE_PROJECTADMIN)->data($projectAdmin)->exec();
        }

        return !dao::isError();
    }

    /**
     * 生成project模块的项目下拉框跳转链接.
     * Build project link in project page.
     *
     * @param  string $method
     * @access protected
     * @return string
     */
    protected function buildLinkForProject(string $method) :string
    {
        if($method == 'execution')
            return helper::createLink($module, $method, "status=all&projectID=%s");

        if($method == 'managePriv')
            return helper::createLink($module, 'group', "projectID=%s");

        if($method == 'showerrornone')
            return helper::createLink('projectstory', 'story', "projectID=%s");

        $methods = ',bug,testcase,testtask,testreport,build,dynamic,view,manageproducts,team,managemembers,whitelist,addwhitelist,group,';
        if(strpos($methods, ',' . $method . ',') !== false)
            return helper::createLink($module, $method, "projectID=%s");
    }

    /**
     * 生成bug模块的项目下拉框跳转链接.
     * Build project link in bug page.
     *
     * @param  string $method
     * @access protected
     * @return string
     */
    protected function buildLinkForBug(string $method) :string
    {
        if($method == 'create')
            return helper::createLink($module, $method, "productID=0&branch=0&extras=projectID=%s");

        if($method == 'edit')
            return helper::createLink('project', 'bug', "projectID=%s");
    }

    /**
     * 生成story模块的项目下拉框跳转链接.
     * Build project link in story page.
     *
     * @param  string $method
     * @access protected
     * @return string
     */
    protected function buildLinkForStory(string $method) :string
    {
        if($method == 'change' or $method == 'create')
            return helper::createLink('projectstory', 'story', "projectID=%s");
        if($method == 'zerocase')
            return helper::createLink('project', 'testcase', "projectID=%s");
    }

    /**
     * 删除项目团队成员。
     * Delete project team member.
     *
     * @param  array|int $projectIdList
     * @param  string    $type
     * @param  string    $account
     * @access protected
     * @return bool
     */
    protected function unlinkTeamMember(int|array $projectIdList, string $type, string $account): bool
    {
        $this->dao->delete()->from(TABLE_TEAM)
            ->where('root')->in($projectIdList)
            ->andWhere('type')->eq($type)
            ->andWhere('account')->eq($account)
            ->exec();
        return !dao::isError();
    }

    /**
     * 获取最近进行中的执行列表。
     * Get latest executions.
     *
     * @access protected
     * @return array
     */
    protected function getLatestExecutions(): array
    {
        /* 获取进行中的执行。 */
        $executions = $this->loadModel('execution')->getStatData(0, 'doing', 0, 0, false, 'hasParentName|skipParent');
        $doingExecutions = array();
        foreach($executions as $execution) $doingExecutions[$execution->project][$execution->id] = $execution;

        /* 将执行按照执行ID进行逆序排序。*/
        $latestExecutions = array();
        foreach($doingExecutions as $projectID => $executions)
        {
            krsort($doingExecutions[$projectID]);
            $latestExecutions[$projectID] = current($doingExecutions[$projectID]);
        }
        return $latestExecutions;
    }

    /**
     * 获取所有项目的统计信息。
     * Get projects stats.
     *
     * @access protected
     * @return array
     */
    protected function getProjectsStats(): array
    {
        $this->loadModel('program');
        $projectsStats = $this->program->getProjectStats(0, 'all', 0, 'order_asc');
        $projectsStats = $this->classfyProjects($projectsStats);

        /* 只保留最近关闭的两个项目。*/
        /* Only display recent two closed projects. */
        $projectsStats = $this->sortAndReduceClosedGroup($projectsStats, 2);
        return $projectsStats;
    }

    /**
     * 对项目按照我的、其它的和关闭的进行分类。
     * Classfy projects by my, other and closed.
     *
     * @param  int       $projects
     * @access protected
     * @return array
     */
    protected function classfyProjects($projects): array
    {
        $myProjects    = array();
        $otherProjects = array();
        $closedGroup   = array();
        foreach($projects as $project)
        {
            if(!str_contains('wait,doing,closed', $project->status)) continue;

            $projectPath = explode(',', trim($project->path, ','));
            $topProgram  = !empty($project->parent) ? $projectPath[0] : $project->parent;

            if($project->PM == $this->app->user->account)
            {
                if($project->status != 'closed')
                {
                    $myProjects[$topProgram][$project->status][] = $project;
                }
                else
                {
                    $closedGroup['my'][$topProgram][$project->closedDate] = $project;
                }
            }
            else
            {
                if($project->status != 'closed')
                {
                    $otherProjects[$topProgram][$project->status][] = $project;
                }
                else
                {
                    $closedGroup['other'][$topProgram][$project->closedDate] = $project;
                }
            }
        }

        return array('myProjects' => $myProjects, 'otherProjects' => $otherProjects, 'closedGroup' => $closedGroup);
    }

    /**
     * 对groups列表进行排序和缩减。
     * Sort and reduce groups.
     *
     * @param  array     $projectsStats
     * @param  int       $retainNum
     * @access protected
     * @return array
     */
    protected function sortAndReduceClosedGroup(array $projectsStats, int $retainNum = 2): array
    {
        $myProjects    = $projectsStats['myProjects'];
        $otherProjects = $projectsStats['otherProjects'];
        $closedGroup   = $projectsStats['closedGroup'];
        foreach($closedGroup as $group => $closedProjects)
        {
            foreach($closedProjects as $topProgram => $projects)
            {
                krsort($projects);
                if($retainNum > 0)
                {
                    if($group == 'my')
                    {
                        $myProjects[$topProgram]['closed'] = array_slice($projects, 0, $retainNum);
                    }
                    else
                    {
                        $otherProjects[$topProgram]['closed'] = array_slice($projects, 0, $retainNum);
                    }
                }
            }
        }

        return array('my' => $myProjects, 'other' => $otherProjects);
    }

    /**
     * 根据项目集ID查询所有项目集的层级。
     * Get all program level of a program.
     *
     * @param  int    $program
     * @param  string $path
     * @param  int    $grade
     * @access public
     * @return string
     */
    public function getParentProgram(string $path, int $grade): string
    {
        $programList = $this->dao->select('id,name')->from(TABLE_PROGRAM)
            ->where('id')->in(trim($path, ','))
            ->andWhere('grade')->lt($grade)
            ->orderBy('grade asc')
            ->fetchPairs();

        $programName = '';
        foreach($programList as $program) $programName .= $program . '/';

        return rtrim($programName, '/');
    }

    /**
     * 根据状态和和我参与的查询项目列表。
     * Get project list by status and with my participation.
     *
     * @param  string $status
     * @param  string $orderBy
     * @param  bool   $involved
     * @param  object $pager
     * @access protected
     * @return array
     */
    protected function fetchProjectList(string $status, string $orderBy, bool $involved, object|null $pager): array
    {
        return $this->dao->select('DISTINCT t1.*')->from(TABLE_PROJECT)->alias('t1')
            ->leftJoin(TABLE_TEAM)->alias('t2')->on('t1.id=t2.root')
            ->leftJoin(TABLE_STAKEHOLDER)->alias('t3')->on('t1.id=t3.objectID')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t1.vision')->eq($this->config->vision)
            ->andWhere('t1.type')->eq('project')
            ->beginIF(!in_array($status, array('all', 'undone', 'review', 'unclosed'), true))->andWhere('t1.status')->eq($status)->fi()
            ->beginIF($status == 'undone' or $status == 'unclosed')->andWhere('t1.status')->in('wait,doing')->fi()
            ->beginIF($status == 'review')
            ->andWhere("FIND_IN_SET('{$this->app->user->account}', t1.reviewers)")
            ->andWhere('t1.reviewStatus')->eq('doing')
            ->fi()
            ->beginIF($this->cookie->involved or $involved)
            ->andWhere('t2.type')->eq('project')
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
    }

    /**
     * 通过条件查询和数量限制查询项目列表。
     * Get project list by query and with limit.
     *
     * @param  string     $queryType
     * @param  string|int $param
     * @param  string     $orderBy
     * @param  int        $limit
     * @param  string     $excludedModel
     * @access protected
     * @return array
     */
    protected function fetchProjectListByQuery(string $queryType, string|int $param, string $orderBy, int $limit, string $excludedModel): array
    {
        $queryType = strtolower($queryType);
        return $this->dao->select('*')->from(TABLE_PROJECT)
            ->where('type')->eq('project')
            ->andWhere('vision')->eq($this->config->vision)
            ->andWhere('deleted')->eq(0)
            ->beginIF($excludedModel)->andWhere('model')->ne($excludedModel)->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->projects)->fi()
            ->beginIF($queryType == 'bystatus' and $param == 'undone')->andWhere('status')->notIN('done,closed')->fi()
            ->beginIF($queryType == 'bystatus' and $param != 'all' and $param != 'undone')->andWhere('status')->eq($param)->fi()
            ->beginIF($queryType == 'byid')->andWhere('id')->eq($param)->fi()
            ->orderBy($orderBy)
            ->beginIF($limit)->limit($limit)->fi()
            ->fetchAll('id');
    }

    /**
     * 根据项目ID列表查询团队成员数量。
     * Get project team member count by project id list.
     *
     * @param  array $projectIdList
     * @access protected
     * @return array
     */
    protected function fetchMemberCountByIdList(array $projectIdList): array
    {
        return $this->dao->select('t1.root, count(t1.id) as count')->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account=t2.account')
            ->where('t1.root')->in($projectIdList)
            ->andWhere('t2.deleted')->eq(0)
            ->groupBy('t1.root')
            ->fetchPairs();
    }

    /**
     * 根据项目ID列表查询任务的总预计工时。
     * Get task all estimate by project id list.
     *
     * @param  array  $projectIdList
     * @param  string $fields
     * @access protected
     * @return array
     */
    protected function fetchTaskEstimateByIdList(array $projectIdList, string $fields = 't2.project as project, sum(estimate) as estimate'): array
    {
        return $this->dao->select($fields)->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.execution = t2.id')
            ->where('t1.parent')->lt(1)
            ->andWhere('t2.project')->in($projectIdList)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->groupBy('t2.project')
            ->fetchAll('project');
    }

    /**
     * 通过项目ID列表查询需求的数量。
     * Get the number of stories associated with the project.
     *
     * @param  array   $projectIdList
     * @access public
     * @return array
     */
    public function getTotalStoriesByProject(array $projectIdList): array
    {
        return $this->dao->select("t1.project, count(t2.id) as allStories, count(if(t2.status = 'active' or t2.status = 'changing', 1, null)) as leftStories, count(if(t2.status = 'closed' and t2.closedReason = 'done', 1, null)) as doneStories")->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story=t2.id')
            ->where('t1.project')->in($projectIdList)
            ->andWhere('t2.type')->eq('story')
            ->andWhere('deleted')->eq('0')
            ->groupBy('project')
            ->fetchAll('project');
    }

    /**
     * 通过项目ID获取任务数量统计。
     * Get the number of tasks associated with the project.
     *
     * @param  array  $projectIdList
     * @access public
     * @return array
     */
    public function getTotalTaskByProject(array $projectIdList): array
    {
        return $this->dao->select("t1.project, count(t1.id) as allTasks, count(if(t1.status = 'wait', 1, null)) as waitTasks, count(if(t1.status = 'doing', 1, null)) as doingTasks, count(if(t1.status = 'done', 1, null)) as doneTasks, count(if(t1.status = 'wait' or t1.status = 'pause' or t1.status = 'cancel', 1, null)) as leftTasks, count(if(t1.status = 'done' or t1.status = 'closed', 1, null)) as litedoneTasks")->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.execution=t2.id')
            ->where('t1.project')->in($projectIdList)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->groupBy('t1.project')
            ->fetchAll('project');
    }

    /**
     * 通过项目ID获取Bug数量统计。
     * Get the number of bugs associated with the project.
     *
     * @param  array  $projectIdList
     * @access public
     * @return array
     */
    public function getTotalBugByProject(array $projectIdList): array
    {
        return $this->dao->select("project, count(id) as allBugs, count(if(status = 'active', 1, null)) as leftBugs, count(if(status = 'resolved', 1, null)) as doneBugs")->from(TABLE_BUG)
            ->where('project')->in($projectIdList)
            ->andWhere('deleted')->eq(0)
            ->groupBy('project')
            ->fetchAll('project');
    }
}
