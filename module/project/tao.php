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
            ->exec();

        return !dao::isError();
    }

    /**
     * Fetch undone tasks.
     *
     * @param  int $projectID
     * @access protected
     * @return array|false
     */
    protected function fetchUndoneTasks(int $projectID): array|false
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
     * Get project details, including all contents of the TABLE_PROJECT.
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
     * @param  object $postData
     * @param  object $program
     * @access protected
     * @return bool
     */
    protected function createDocLib(int $projectID, object $project, object $postData, object $program): bool
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
        $product->name           = $project->hasProduct && $postData->rawdata->productName ? $postData->rawdata->productName : $project->name;
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

        $this->dao->insert(TABLE_PRODUCT)->data($product)->exec();
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
     * 根据项目集ID查询所有项目集的层级。
     * Get all program level of a program.
     *
     * @param  int    $program
     * @param  string $path
     * @param  int    $grade
     * @access public
     * @return string
     */
    public function getParentProgram(int $program, string $path, int $grade): string
    {
        $parentName = $this->dao->select('id,name')->from(TABLE_PROGRAM)
            ->where('id')->in(trim($path, ','))
            ->andWhere('grade')->lt($grade)
            ->orderBy('grade asc')
            ->fetchPairs();

        $parentProgram = '';
        foreach($parentName as $name) $parentProgram .= $name . '/';
        $parentProgram = rtrim($parentProgram, '/');

        return $parentProgram;
    }

    /**
     * 将旧的产品替换成新的
     * replace oldProduct
     *
     * @param  array $executionIDs
     *
     * @access protected
     * @return bool
     */
    protected function replaceOldProduct(array $executionIDs): bool
    {
        $oldExecutionProducts = $this->dao->select('project,product')->from(TABLE_PROJECTPRODUCT)->where('project')->in($executionIDs)->fetchGroup('project', 'product');
        return !dao::isError();
    }

    /**
     * 根据状态和和我参与的查询项目列表。
     * Get project list by status and with my participation.
     *
     * @param  string $status
     * @param  string $orderBy
     * @param  int    $involved
     * @param  object $pager
     * @access protected
     * @return array
     */
    protected function fetchProjectList(string $status, string $orderBy, int $involved, object|null $pager): array
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
     * 根据项目ID列表查询团队成员分组。
     * Get project team members by project id list.
     *
     * @param  array $projectIdList
     * @access protected
     * @return array
     */
    protected function fetchTeamGroupByIdList(array $projectIdList): array
    {
        return $this->dao->select('t1.root, count(t1.id) as count')->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account=t2.account')
            ->where('t1.root')->in($projectIdList)
            ->andWhere('t2.deleted')->eq(0)
            ->groupBy('t1.root')
            ->fetchAll('root');
    }
}
