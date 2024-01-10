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
        $this->dao->update(TABLE_PROJECT)->data($project, 'comment')
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
     * 激活项目时更新项目的信息。
     * Update project table when activate a project.
     *
     * @param  int       $projectID
     * @param  object    $project
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
     * 更新项目主表。
     * Update project table when edit a project.
     *
     * @param  int       $projectID
     * @param  object    $project
     * @access protected
     * @return bool
     */
    protected function doUpdate(int $projectID, object $project): bool
    {
        $this->dao->update(TABLE_PROJECT)->data($project)
            ->autoCheck('begin,end')
            ->check('end',  'gt', $project->begin)
            ->checkIF(!empty($project->name), 'name', 'unique', "id != $projectID and `type` = 'project' and `parent` = '$project->parent' and `model` = " . $this->dao->sqlobj->quote($project->model) . " and `deleted` = '0'")
            ->checkIF(!empty($project->code), 'code', 'unique', "id != $projectID and `type` = 'project' and `model` = " . $this->dao->sqlobj->quote($project->model) . " and `deleted` = '0'")
            ->checkFlow()
            ->where('id')->eq($projectID)
            ->exec();

        return !dao::isError();
    }

    /**
     * 新增项目。
     * Insert a project to project table.
     *
     * @param  object    $project
     * @access protected
     * @return bool
     */
    protected function doCreate(object $project): bool
    {
        $this->lang->error->unique = $this->lang->error->repeat;
        $this->dao->insert(TABLE_PROJECT)->data($project)
            ->autoCheck()
            ->batchCheck($this->config->project->create->requiredFields, 'notempty')
            ->checkIF(!empty($project->name), 'name', 'unique', "`type`='project' and `parent` = " . $this->dao->sqlobj->quote($project->parent) . " and `model` =  " . $this->dao->sqlobj->quote($project->model) . " and `deleted` = '0'")
            ->checkIF(!empty($project->code), 'code', 'unique', "`type`='project' and `model` = " . $this->dao->sqlobj->quote($project->model) . " and `deleted` = '0'")
            ->checkIF($project->end != '', 'end', 'gt', $project->begin)
            ->checkFlow()
            ->exec();

        return !dao::isError();
    }

    /**
     * 获取未完成的任务。
     * Fetch undone tasks.
     *
     * @param  int       $projectID
     * @access protected
     * @return object[]
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
     * 更新任务的起止日期。
     * Update start and end date of tasks.
     *
     * @param  array     $tasks
     * @param  object    $oldProject
     * @param  object    $project
     * @access protected
     * @return bool
     */
    protected function updateTasksStartAndEndDate(array $tasks, object $oldProject, object $project): bool
    {
        $beginTimeStamp = strtotime($project->begin);

        foreach($tasks as $task)
        {
            if($task->status == 'wait' && !helper::isZeroDate($task->estStarted))
            {
                $taskDays   = helper::diffDate($task->deadline, $task->estStarted);
                $taskOffset = helper::diffDate($task->estStarted, $oldProject->begin);

                $estStartedTimeStamp = $beginTimeStamp + $taskOffset * 24 * 3600;
                $estStarted          = date('Y-m-d', $estStartedTimeStamp);
                $deadline            = date('Y-m-d', $estStartedTimeStamp + $taskDays * 24 * 3600);

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
     * 插入项目团队成员。
     * Insert project members.
     *
     * @param  array     $members
     * @param  int       $projectID
     * @param  array     $oldJoin
     * @access protected
     * @return array
     */
    protected function insertMember(array $members, int $projectID, array $oldJoin): array
    {
        $accounts = array();
        foreach($members as $member)
        {
            if(empty($member->account)) continue;
            $account    = $member->account;
            $accounts[] = $account;

            $data          = new stdclass();
            $data->role    = $member->role;
            $data->days    = $member->days;
            $data->hours   = $member->hours;
            $data->limited = isset($member->limited) ? $member->limited : 'no';

            $data->root    = $projectID;
            $data->account = $account;
            $data->join    = isset($oldJoin[$account]) ? $oldJoin[$account] : helper::today();
            $data->type    = 'project';

            $this->dao->insert(TABLE_TEAM)->data($data)->exec();
        }

        return $accounts;
    }

    /**
     * 更新项目团队成员的视图权限
     * Update member view when project member changed.
     *
     * @param  int       $projectID
     * @param  array     $accounts
     * @param  array     $oldJoin
     * @access protected
     * @return void
     */
    protected function updateMemberView(int $projectID, array $accounts, array $oldJoin): void
    {
        /* Only changed account update userview. */
        $oldAccounts     = array_keys($oldJoin);
        $removedAccounts = array_diff($oldAccounts, $accounts);
        $changedAccounts = array_merge($removedAccounts, array_diff($accounts, $oldAccounts));
        $changedAccounts = array_unique($changedAccounts);

        $childSprints = $this->dao->select('id')->from(TABLE_PROJECT)
            ->where('project')->eq($projectID)
            ->andWhere('type')->in('stage,sprint')
            ->andWhere('deleted')->eq('0')
            ->fetchPairs();

        $linkedProducts = $this->dao->select("t2.id")->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
            ->where('t2.deleted')->eq(0)
            ->andWhere('t1.project')->eq($projectID)
            ->andWhere('t2.vision')->eq($this->config->vision)
            ->fetchPairs();

        $this->loadModel('user')->updateUserView(array($projectID), 'project', $changedAccounts);
        if(!empty($childSprints))   $this->user->updateUserView($childSprints, 'sprint', $changedAccounts);
        if(!empty($linkedProducts)) $this->user->updateUserView(array_keys($linkedProducts), 'product', $changedAccounts);
    }

    /**
     * 获取项目的详情，包含project表的所有内容。
     * Get project details, including all contents of the project.
     *
     * @param  int          $projectID
     * @param  string       $type
     * @access protected
     * @return object|false
     */
    protected function fetchProjectInfo(int $projectID, string $type = ''): object|false
    {
        $project = $this->dao->select('*')
            ->from(TABLE_PROJECT)
            ->where('id')->eq($projectID)
            ->beginIF(!empty($type))->andWhere('`type`')->in($type)->fi()
            ->fetch();

        /* Filter the date is empty or 1970. */
        if($project && helper::isZeroDate($project->end)) $project->end = '';
        return $project;
    }


    /**
     * 创建项目后，创建默认的项目主库。
     * Create doclib after create a project.
     *
     * @param  int       $projectID
     * @param  object    $project
     * @param  object    $program
     * @access protected
     * @return bool
     */
    protected function createDocLib(int $projectID, object $project, object $program): bool
    {
        /* Create doc lib. */
        $this->app->loadLang('doc');
        $authorizedUsers = array();

        if($project->parent && $project->acl == 'program')
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
     * 创建项目时，如果直接输入了产品名，则创建产品并与项目关联。
     * Create doclib after create a project.
     *
     * @param  int        $projectID
     * @param  object     $project
     * @param  object     $postData
     * @param  object     $program
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
        $product->program        = $project->parent ? current(array_filter(explode(',', zget($program, 'path', '')))) : 0;
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

        $this->app->loadLang('product');
        $this->dao->insert(TABLE_PRODUCT)->data($product)
            ->check('name', 'notempty')
            ->checkIF(!empty($product->name), 'name', 'unique', "`program` = {$product->program} and `deleted` = '0'")
            ->exec();
        if(dao::isError()) return false;

        $productID = $this->dao->lastInsertId();

        /* Update whitelist and user view. */
        if(!$project->hasProduct) $this->loadModel('personnel')->updateWhitelist(explode(',', $project->whitelist), 'product', $productID);
        $this->loadModel('action')->create('product', $productID, 'opened');
        $this->dao->update(TABLE_PRODUCT)->set('`order`')->eq($productID * 5)->where('id')->eq($productID)->exec();
        if($product->acl != 'open') $this->loadModel('user')->updateUserView(array($productID), 'product');

        /* Link product. */
        $projectProduct = new stdclass();
        $projectProduct->project = $projectID;
        $projectProduct->product = $productID;
        $projectProduct->branch  = 0;
        $projectProduct->plan    = 0;
        $this->dao->insert(TABLE_PROJECTPRODUCT)->data($projectProduct)->exec();
        if(dao::isError()) return false;

        /* Create doc lib. */
        if($project->hasProduct) $this->createProductDocLib($productID);
        return !dao::isError();
    }

    /**
     * 创建产品后，创建默认的产品主库。
     * Create doclib after create a product.
     *
     * @param  int       $productID
     * @access protected
     * @return bool
     */
    protected function createProductDocLib(int $productID): bool
    {
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

        return !dao::isError();
    }

    /**
     * 获取创建项目时选择的产品数量。
     * Get products count from post.
     *
     * @param  object    $project
     * @param  object    $rawdata
     * @access protected
     * @return int
     */
    protected function getLinkedProductsCount(object $project, object $rawdata): int
    {
        if(!isset($project)) return 0;

        $linkedProductsCount = 0;
        if($project->hasProduct && isset($rawdata->products))
        {
            foreach($rawdata->products as $product)
            {
                if(empty($product)) continue;

                $linkedProductsCount ++;
            }
        }

        return $linkedProductsCount;
    }

    /**
     * 创建项目后，将项目创建者加到项目管理员分组。
     * Create project admin after create a project.
     *
     * @param  int       $projectID
     * @access protected
     * @return bool
     */
    protected function addProjectAdmin(int $projectID): bool
    {
        $projectAdmin = $this->dao->select('*')->from(TABLE_PROJECTADMIN)->where('account')->eq($this->app->user->account)->fetch();

        if(!empty($projectAdmin))
        {
            $newProject = $projectAdmin->projects . ",$projectID";
            $this->dao->update(TABLE_PROJECTADMIN)->set('projects')->eq($newProject)->where('account')->eq($projectAdmin->account)->andWhere('`group`')->eq($projectAdmin->group)->exec();
        }
        else
        {
            $maxGroupID = $this->dao->select('max(`group`) as maxGroupID')->from(TABLE_PROJECTADMIN)->fetch('maxGroupID');

            $projectAdmin = new stdclass();
            $projectAdmin->account  = $this->app->user->account;
            $projectAdmin->group    = (int)$maxGroupID + 1;
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
        if($method == 'change' || $method == 'create')
            return helper::createLink('projectstory', 'story', "projectID=%s");
        if($method == 'zerocase')
            return helper::createLink('project', 'testcase', "projectID=%s");
    }

    /**
     * 构造批量更新项目的数据。
     * Build bathc update project data.
     *
     * @param  array     $data
     * @param  array     $oldProjects
     * @access protected
     * @return array
     */
    protected function buildBatchUpdateProjects(array $data, array $oldProjects): array
    {
        if(empty($data)) return array();

        $extendFields = $this->getFlowExtendFields();

        $projects = array();
        foreach($data as $projectID => $project)
        {
            $projectID = (int)$projectID;

            $projects[$projectID] = new stdClass();
            $projects[$projectID]->id             = $projectID;
            $projects[$projectID]->name           = $project->name;
            $projects[$projectID]->model          = $oldProjects[$projectID]->model;
            $projects[$projectID]->PM             = $project->PM;
            $projects[$projectID]->begin          = $project->begin;
            $projects[$projectID]->end            = $project->end == $this->lang->project->longTime ? LONG_TIME : $project->end;
            $projects[$projectID]->acl            = $project->acl;
            $projects[$projectID]->lastEditedBy   = $this->app->user->account;
            $projects[$projectID]->lastEditedDate = helper::now();

            if(isset($project->parent)) $projects[$projectID]->parent = $project->parent;
            if(isset($project->code))   $projects[$projectID]->code   = $project->code;
            if($project->end == $this->lang->project->longTime) $projects[$projectID]->days = 0;

            foreach($extendFields as $extendField)
            {
                $projects[$projectID]->{$extendField->field} = $project->{$extendField->field};
                if(is_array($projects[$projectID]->{$extendField->field})) $projects[$projectID]->{$extendField->field} = implode(',', $projects[$projectID]->{$extendField->field});

                $projects[$projectID]->{$extendField->field} = htmlSpecialString($projects[$projectID]->{$extendField->field});
            }
        }

        return $projects;
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
     * 对项目按照我的、其它的和关闭的进行分类。
     * Classify projects according to my, other and closed.
     *
     * @param  array     $projects
     * @access protected
     * @return array
     */
    protected function classifyProjects(array $projects): array
    {
        $classifiedProjects = array('myProjects' => array(), 'otherProjects' => array(), 'closedProjects' => array());
        foreach($projects as $project)
        {
            if(!str_contains('wait,doing,closed', $project->status)) continue;

            /* Convert predefined HTML entities to characters. */
            $project->name = htmlspecialchars_decode($project->name, ENT_QUOTES);

            $projectPath = explode(',', trim($project->path, ','));
            $topProgram  = !empty($project->parent) ? $projectPath[0] : $project->parent;

            if($project->PM == $this->app->user->account)
            {
                if($project->status != 'closed')
                {
                    $classifiedProjects['myProjects'][$topProgram][$project->status][] = $project;
                }
                else
                {
                    $classifiedProjects['closedProjects']['my'][$topProgram][$project->closedDate] = $project;
                }
            }
            else
            {
                if($project->status != 'closed')
                {
                    $classifiedProjects['otherProjects'][$topProgram][$project->status][] = $project;
                }
                else
                {
                    $classifiedProjects['closedProjects']['other'][$topProgram][$project->closedDate] = $project;
                }
            }
        }

        return $classifiedProjects;
    }

    /**
     * 对groups列表进行排序和缩减。
     * Sort and reduce projects.
     *
     * @param  array     $projectsStats
     * @param  int       $retainNum
     * @access protected
     * @return array
     */
    protected function sortAndReduceClosedProjects(array $projectsStats, int $retainNum = 2): array
    {
        $sortedAndReducedProjects = array('my' => $projectsStats['myProjects'], 'other' => $projectsStats['otherProjects']);
        $closedProjects           = $projectsStats['closedProjects'];
        foreach($closedProjects as $group => $groupedProjects)
        {
            foreach($groupedProjects as $topProgram => $projects)
            {
                krsort($projects);
                if($retainNum > 0)
                {
                    $sortedAndReducedProjects[$group][$topProgram]['closed'] = array_slice($projects, 0, $retainNum);
                }
            }
        }

        return $sortedAndReducedProjects;
    }

    /**
     * 根据项目集ID查询所有项目集的层级。
     * Get all program level of a program.
     *
     * @param  int       $program
     * @param  string    $path
     * @param  int       $grade
     * @access protected
     * @return string
     */
    protected function getParentProgram(string $path, int $grade): string
    {
        $programList = $this->dao->select('id,name')->from(TABLE_PROGRAM)
            ->where('id')->in(trim($path, ','))
            ->andWhere('grade')->lt($grade)
            ->orderBy('grade asc')
            ->fetchPairs();

        return implode('/', $programList);
    }

    /**
     * 根据状态和和我参与的查询项目列表。
     * Get project list by status and with my participation.
     *
     * @param  string    $status
     * @param  string    $orderBy
     * @param  bool      $involved
     * @param  object    $pager
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
            ->beginIF($status == 'undone' || $status == 'unclosed')->andWhere('t1.status')->in('wait,doing')->fi()
            ->beginIF($status == 'review')
            ->andWhere("FIND_IN_SET('{$this->app->user->account}', t1.reviewers)")
            ->andWhere('t1.reviewStatus')->eq('doing')
            ->fi()
            ->beginIF($this->cookie->involved || $involved)
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
     * @param  string     $status
     * @param  int        $projectID
     * @param  string     $orderBy
     * @param  int        $limit
     * @param  string     $excludedModel
     * @access protected
     * @return array
     */
    protected function fetchProjectListByQuery(string $status = 'all', int $projectID = 0, string $orderBy = 'order_asc', int $limit = 0, string $excludedModel = ''): array
    {
        return $this->dao->select('*')->from(TABLE_PROJECT)
            ->where('type')->eq('project')
            ->andWhere('vision')->eq($this->config->vision)
            ->andWhere('deleted')->eq(0)
            ->beginIF($excludedModel)->andWhere('model')->ne($excludedModel)->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->projects)->fi()
            ->beginIF($status == 'undone')->andWhere('status')->notIN('done,closed')->fi()
            ->beginIF($status == 'unclosed')->andWhere('status')->ne('closed')->fi()
            ->beginIF($status && !in_array($status, array('all', 'undone', 'unclosed')))->andWhere('status')->eq($status)->fi()
            ->beginIF($projectID)->andWhere('id')->eq($projectID)->fi()
            ->orderBy($orderBy)
            ->beginIF($limit)->limit($limit)->fi()
            ->fetchAll('id');
    }

    /**
     * 根据项目ID列表查询团队成员数量。
     * Get project team member count by project id list.
     *
     * @param  array     $projectIdList
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
     * @param  array     $projectIdList
     * @param  string    $fields
     * @access protected
     * @return array
     */
    protected function fetchTaskEstimateByIdList(array $projectIdList, string $fields = 'estimate'): array
    {
        $fields       = explode(',', $fields);
        $selectFields = 't2.project';
        foreach($fields as $field) $selectFields .= ", ROUND(SUM(t1.{$field}), 1) AS {$field}";

        return $this->dao->select($selectFields)->from(TABLE_TASK)->alias('t1')
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
     * @param  array     $projectIdList
     * @access protected
     * @return array
     */
    protected function getTotalStoriesByProject(array $projectIdList): array
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
     * 删除团队成员。
     * Delete Members.
     *
     * @param  int       $projectID
     * @param  string    $openedBy
     * @param  array     $deleteMembers
     * @access protected
     * @return bool
     */
    protected function deleteMembers(int $projectID, string $openedBy, array $deleteMembers): bool
    {
        $this->dao->delete()->from(TABLE_TEAM)
            ->where('root')->eq($projectID)
            ->andWhere('type')->eq('project')
            ->andWhere('account')->in($deleteMembers)
            ->andWhere('account')->ne($openedBy)
            ->exec();

        return !dao::isError();
    }

    /**
     * 通过项目ID获取任务数量统计。
     * Get the number of tasks associated with the project.
     *
     * @param  array     $projectIdList
     * @access protected
     * @return array
     */
    protected function getTotalTaskByProject(array $projectIdList): array
    {
        $executionIdList = $this->dao->select('id')->from(TABLE_EXECUTION)->where('project')->in($projectIdList)->andWhere('deleted')->eq(0)->fetchPairs();
        return $this->dao->select("project, count(id) as allTasks, count(if(status = 'wait', 1, null)) as waitTasks, count(if(status = 'doing', 1, null)) as doingTasks, count(if(status = 'done', 1, null)) as doneTasks, count(if(status = 'wait' or status = 'pause' or status = 'cancel', 1, null)) as leftTasks, count(if(status = 'done' or status = 'closed', 1, null)) as litedoneTasks")->from(TABLE_TASK)
            ->where('execution')->in($executionIdList)
            ->andWhere('deleted')->eq(0)
            ->groupBy('project')
            ->fetchAll('project');
    }

    /**
     * 返回请求被拒绝的跳转信息。
     * return accessDenied response.
     *
     * @access protected
     * @return string
     */
    protected function accessDenied(): string
    {
        $this->session->set('project', '');
        return $this->app->control->sendError($this->lang->project->accessDenied, helper::createLink('project', 'index'));
    }

    /**
     * 修改无迭代项目下执行的状态。
     * Modify the execution status when changing the status of no execution project.
     *
     * @param  int    $projectID
     * @param  string $status
     *
     * @access protected
     * @return array|false
     */
    protected function changeExecutionStatus(int $projectID, string $status): array|false
    {
        if(!in_array($status, array('start', 'suspend', 'activate', 'close'))) return false;

        $execution = $this->dao->select('*')->from(TABLE_EXECUTION)->where('project')->eq($projectID)->andWhere('multiple')->eq('0')->fetch();
        if(!$execution) return false;

        $project = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($projectID)->fetch();

        $postData = new stdclass();
        $postData->status  = $status;
        $postData->begin   = $execution->begin;
        $postData->end     = $execution->end;
        $postData->uid     = '';
        $postData->comment = '';
        if($status == 'close') $postData->realEnd = $project->realEnd;
        return $this->loadModel('execution')->$status($execution->id, $postData);
    }

    /**
     * 通过项目ID获取Bug数量统计。
     * Get the number of bugs associated with the project.
     *
     * @param  array     $projectIdList
     * @access protected
     * @return array
     */
    protected function getTotalBugByProject(array $projectIdList): array
    {
        return $this->dao->select("project, count(id) as allBugs, count(if(status = 'active', 1, null)) as leftBugs, count(if(status = 'resolved', 1, null)) as doneBugs")->from(TABLE_BUG)
            ->where('project')->in($projectIdList)
            ->andWhere('deleted')->eq(0)
            ->groupBy('project')
            ->fetchAll('project');
    }

    /**
     * 根据项目模式设置菜单。
     * Set menu by project model.
     *
     * @param  string    $projectModel
     * @access protected
     * @return bool
     */
    protected function setMenuByModel(string $projectModel): bool
    {
        global $lang;
        $model = 'scrum';
        if(in_array($projectModel, $this->config->project->waterfallList))
        {
            $model = 'waterfall';
            $lang->project->createExecution = str_replace($lang->executionCommon, $lang->project->stage, $lang->project->createExecution);
            $lang->project->lastIteration   = str_replace($lang->executionCommon, $lang->project->stage, $lang->project->lastIteration);

            $this->loadModel('execution');
            $executionCommonLang   = $lang->executionCommon;
            $lang->executionCommon = $lang->project->stage;

            include $this->app->getModulePath('', 'execution') . 'lang/' . $this->app->getClientLang() . '.php';

            $lang->execution->typeList['sprint'] = $executionCommonLang;
        }
        elseif($projectModel == 'kanban')
        {
            $model = 'kanbanProject';
            $lang->executionCommon = $lang->project->kanban;
        }

        if(isset($lang->$model))
        {
            $lang->project->menu        = $lang->{$model}->menu;
            $lang->project->menuOrder   = $lang->{$model}->menuOrder;
            $lang->project->dividerMenu = $lang->{$model}->dividerMenu;
        }

        return true;
    }

    /**
     * 根据是否产品型项目设置菜单。
     * Set menu by whether product project.
     *
     * @param  int       $projectID
     * @param  int       $hasProduct
     * @param  string    $model
     * @access protected
     * @return bool
     */
    protected function setMenuByProduct(int $projectID, int $hasProduct, string $model): bool
    {
        global $lang;
        if($hasProduct)
        {
            unset($lang->project->menu->projectplan);
            unset($lang->project->menu->settings['subMenu']->module);
            if(isset($lang->project->menu->storyGroup)) unset($lang->project->menu->storyGroup);
            return true;
        }

        $projectProduct = (int)$this->dao->select('product')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($projectID)->fetch('product');
        $lang->project->menu->settings['subMenu']->module['link'] = sprintf($lang->project->menu->settings['subMenu']->module['link'], $projectProduct);

        if(in_array($model, $this->config->project->scrumList)) $lang->project->menu->projectplan['link'] = sprintf($lang->project->menu->projectplan['link'], $projectProduct);

        /* Init story dropdown for project secondary meun. */
        if($model !== 'kanban' && !empty($this->config->URAndSR) && isset($lang->project->menu->storyGroup))
        {
            $lang->project->menu->story = $lang->project->menu->storyGroup;
            $lang->project->menu->story['link'] = sprintf($lang->project->menu->storyGroup['link'], '%s', $projectProduct);
            $lang->project->menu->story['dropMenu']->story['link']       = sprintf($lang->project->menu->storyGroup['dropMenu']->story['link'], '%s', $projectProduct);
            $lang->project->menu->story['dropMenu']->requirement['link'] = sprintf($lang->project->menu->storyGroup['dropMenu']->requirement['link'], '%s', $projectProduct);
            if(isset($this->app->params['storyType']) && $this->app->params['storyType'] == 'story') $lang->project->menu->story['dropMenu']->story['subModule'] .= ',projectstory,story';
            if(isset($this->app->params['storyType']) && $this->app->params['storyType'] == 'requirement') $lang->project->menu->story['dropMenu']->requirement['subModule'] .= ',projectstory,story';
        }

        unset($lang->project->menu->settings['subMenu']->products);
        if(isset($lang->project->menu->storyGroup)) unset($lang->project->menu->storyGroup);
        if(!in_array($model, $this->config->project->scrumList)) unset($lang->project->menu->projectplan);
        return true;
    }

    /**
     * 根据访问模块的导航组设置菜单。
     * Set menu lang for module.
     *
     * @param  string     $navGroup
     * @param  int        $executionID
     * @param  object     $project
     * @access protected
     * @return bool
     */
    protected function setNavGroupMenu(string $navGroup, int $executionID, object $project): bool
    {
        global $lang;
        /* Single execution and has no product project menu. */
        if(!$project->hasProduct && !$project->multiple && !empty($this->config->URAndSR) && isset($lang->$navGroup->menu->storyGroup))
        {
            $lang->$navGroup->menu->story = $lang->$navGroup->menu->storyGroup;
            $lang->$navGroup->menu->story['link'] = sprintf($lang->$navGroup->menu->storyGroup['link'], '%s', $project->id);

            $lang->$navGroup->menu->story['dropMenu']->story['link']       = sprintf($lang->$navGroup->menu->storyGroup['dropMenu']->story['link'], '%s', $project->id);
            $lang->$navGroup->menu->story['dropMenu']->requirement['link'] = sprintf($lang->$navGroup->menu->storyGroup['dropMenu']->requirement['link'], '%s', $project->id);

            if(isset($this->app->params['storyType']) && $this->app->params['storyType'] == 'story') $lang->$navGroup->menu->story['dropMenu']->story['subModule'] = 'story,execution';
            if(isset($this->app->params['storyType']) && $this->app->params['storyType'] == 'requirement') $lang->$navGroup->menu->story['dropMenu']->requirement['subModule'] = 'story,execution';
        }

        if(isset($lang->$navGroup->menu->storyGroup)) unset($lang->$navGroup->menu->storyGroup);
        foreach($lang->$navGroup->menu as $label => $menu)
        {
            $objectID = 0;
            if(strpos($this->config->project->multiple['project'], ",{$label},") !== false) $objectID = $project->id;
            if(strpos($this->config->project->multiple['execution'], ",{$label},") !== false)
            {
                $objectID = $executionID;
                $lang->$navGroup->menu->{$label}['subModule'] = 'project';
            }

            $lang->$navGroup->menu->$label = commonModel::setMenuVarsEx($menu, $objectID);
            if(isset($menu['subMenu']))
            {
                foreach($menu['subMenu'] as $key1 => $subMenu) $lang->$navGroup->menu->{$label}['subMenu']->$key1 = common::setMenuVarsEx($subMenu, $objectID);
            }

            if(!isset($menu['dropMenu'])) continue;
            foreach($menu['dropMenu'] as $key2 => $dropMenu)
            {
                $lang->$navGroup->menu->{$label}['dropMenu']->$key2 = common::setMenuVarsEx($dropMenu, $objectID);

                if(!isset($dropMenu['subMenu'])) continue;
                foreach($dropMenu['subMenu'] as $key3 => $subMenu) $lang->$navGroup->menu->{$label}['dropMenu']->$key3 = common::setMenuVarsEx($subMenu, $objectID);
            }
        }

        return true;
    }
}
