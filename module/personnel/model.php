<?php
declare(strict_types=1);
/**
 * The model file of personnel of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     personnel
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class personnelModel extends model
{
    /**
     * 获取项目集可访问人员。
     * Access to program set invest staff.
     *
     * @param  int       $programID
     * @param  int       $deptID
     * @param  string    $browseType
     * @param  int       $queryID
     * @access public
     * @return array
     */
    public function getAccessiblePersonnel(int $programID = 0, int $deptID = 0, string $browseType = 'all', int $queryID = 0)
    {
        $accessibleQuery = '';
        if($browseType == 'bysearch')
        {
            $query = $queryID ? $this->loadModel('search')->getQuery($queryID) : '';
            if($query)
            {
                $this->session->set('accessibleQuery', $query->sql);
                $this->session->set('accessibleForm', $query->form);
            }
            if($this->session->accessibleQuery == false) $this->session->set('accessibleQuery', ' 1=1');
            $accessibleQuery = $this->session->accessibleQuery;
        }

        /* Determine who can be accessed based on access control. */
        $program = $this->loadModel('program')->getByID($programID);
        if(!$program) return array();

        /* Get accessible account of program. */
        $accessibleQuery = preg_replace('/`(\w+)`/', 't2.`$1`', $accessibleQuery);
        $personnelList   = array();
        $personnelList   = $this->dao->select('t2.id,t2.dept,t2.account,t2.role,t2.realname,t2.gender')->from(TABLE_USERVIEW)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account=t2.account')
            ->where('t2.deleted')->eq(0)
            ->beginIF($program->acl != 'open')->andWhere("CONCAT(',', t1.programs, ',')")->like("%,$programID,%")->fi()
            ->beginIF($deptID > 0)->andWhere('t2.dept')->eq($deptID)->fi()
            ->beginIF($browseType == 'bysearch')->andWhere($accessibleQuery)->fi()
            ->fetchAll('id');

        /* If the program's acl is open and the user cannot access this program, remove it. */
        if($program->acl == 'open')
        {
            foreach($personnelList as $personnel)
            {
                if(!$this->canViewProgram($programID, $personnel->account)) unset($personnelList[$personnel->id]);
            }
        }

        return $personnelList;
    }

    /**
     * 检查你是否有权限查看项目集。
     * Check if you have permission to view the program.
     *
     * @param  int    $programID
     * @param  string $account
     * @access public
     * @return bool
     */
    public function canViewProgram(int $programID, string $account): bool
    {
        if($this->app->user->admin) return true;

        /* Get group acl and group info. */
        static $groupAcl  = array();
        static $groupInfo = array();
        if(empty($groupAcl))
        {
            $groupAcl = $this->dao->select('id,acl')->from(TABLE_GROUP)->fetchPairs();
            foreach($groupAcl as $groupID => $group) $groupInfo[$groupID] = json_decode($groupAcl[$groupID]);
        }

        /* Get user groups. */
        static $userGroups = array();
        if(empty($userGroups)) $userGroups = $this->dao->select('*')->from(TABLE_USERGROUP)->fetchGroup('account', 'group');

        /* Check permission. */
        $programRight = false;
        if(isset($userGroups[$account]))
        {
            foreach($userGroups[$account] as $groupID => $userGroup)
            {
                $group = isset($groupInfo[$groupID]) ? $groupInfo[$groupID] : '';

                if(!isset($group->programs) || in_array($programID, $group->programs))
                {
                    $programRight = true;
                    break;
                }
            }
        }
        return $programRight;
    }

    /**
     * 获取参与项目集的人员列表。
     * Get invest person list.
     *
     * @param  int    $programID
     * @access public
     * @return array
     */
    public function getInvest(int $programID = 0): array
    {
        /* Get all projects under the current program. */
        $projects = $this->dao->select('id,model,type,parent,path,name')->from(TABLE_PROJECT)
            ->where('type')->eq('project')
            ->andWhere('path')->like("%,{$programID},%")
            ->andWhere('deleted')->eq('0')
            ->orderBy('id_desc')
            ->fetchAll('id');
        if(empty($projects)) return array();

        /* Get account pairs. */
        $accountPairs = $this->getInvolvedProjects($projects);
        if(empty($accountPairs)) return array();

        /* Get execution, task, bug, story, issue and risk pairs, and get user list. */
        $executionPairs    = $this->getInvolvedExecutions($projects);
        $taskInvest        = $this->getProjectTaskInvest($projects, $accountPairs);
        $bugAndStoryInvest = $this->getBugAndStoryInvest($accountPairs, $programID);
        if($this->config->edition == 'max' || $this->config->edition == 'ipd')
        {
            $issueInvest = $this->getIssueInvest($accountPairs, $projects);
            $riskInvest  = $this->getRiskInvest($accountPairs, $projects);
        }
        $users = $this->loadModel('user')->getListByAccounts(array_keys($accountPairs), 'account');

        /* Build personnel list. */
        $personnelList = array();
        foreach($accountPairs as $account => $projects)
        {
            $user = zget($users, $account, '');
            if(empty($user)) continue;

            if(!isset($personnelList[$user->role])) $personnelList[$user->role] = array();
            $personnelList[$user->role][$account]['realname']   = $user ? $user->realname : $account;
            $personnelList[$user->role][$account]['account']    = $account;
            $personnelList[$user->role][$account]['role']       = $user ? zget($this->lang->user->roleList, $user->role) : '';
            $personnelList[$user->role][$account]['projects']   = $projects;
            $personnelList[$user->role][$account]['executions'] = zget($executionPairs, $account, 0);

            $personnelList[$user->role][$account] += $taskInvest[$account];
            $personnelList[$user->role][$account] += $bugAndStoryInvest[$account];
            if($this->config->edition == 'max' || $this->config->edition == 'ipd')
            {
                $personnelList[$user->role][$account] += $issueInvest[$account];
                $personnelList[$user->role][$account] += $riskInvest[$account];
            }
        }
        krsort($personnelList);
        return $personnelList;
    }

    /**
     * 获取用户参与的项目风险。
     * Get user project risk invest.
     *
     * @param  array  $accounts
     * @param  array  $projects
     * @access public
     * @return array
     */
    public function getRiskInvest(array $accounts, array $projects): array
    {
        /* Get the risks in projects. */
        $risks = $this->dao->select('id,createdBy,resolvedBy,status,assignedTo')->from(TABLE_RISK)
            ->where('project')->in(array_keys($projects))
            ->andWhere('deleted')->eq(0)
            ->fetchAll('id');

        /* Initialization personnel risks. */
        $invest = array();
        foreach($accounts as $account => $project)
        {
            $invest[$account]['createdRisk']  = 0;
            $invest[$account]['resolvedRisk'] = 0;
            $invest[$account]['pendingRisk']  = 0;
        }

        /* Build invest risks. */
        foreach($risks as $risk)
        {
            if($risk->createdBy  && isset($invest[$risk->createdBy]))                               $invest[$risk->createdBy]['createdRisk']   += 1;
            if($risk->resolvedBy && isset($invest[$risk->resolvedBy]))                              $invest[$risk->resolvedBy]['resolvedRisk'] += 1;
            if($risk->assignedTo && isset($invest[$risk->assignedTo]) && $risk->status == 'active') $invest[$risk->assignedTo]['pendingRisk']  += 1;
        }

        return $invest;
    }

    /**
     * 获取用户参与的项目问题。
     * Get user project issue invest.
     *
     * @param  array  $accounts
     * @param  array  $projects
     * @access public
     * @return array
     */
    public function getIssueInvest(array $accounts, array $projects): array
    {
        /* Get issues in the projects. */
        $issues = $this->dao->select('id,createdBy,resolvedBy,status,assignedTo')->from(TABLE_ISSUE)
            ->where('project')->in(array_keys($projects))
            ->andWhere('deleted')->eq(0)
            ->fetchAll('id');

        /* Initialization personnel issues. */
        $invest = array();
        foreach($accounts as $account => $project)
        {
            $invest[$account]['createdIssue']  = 0;
            $invest[$account]['resolvedIssue'] = 0;
            $invest[$account]['pendingIssue']  = 0;
        }

        /* Build invest issues. */
        foreach($issues as $issue)
        {
            if($issue->createdBy  && isset($invest[$issue->createdBy]))  $invest[$issue->createdBy]['createdIssue']   += 1;
            if($issue->resolvedBy && isset($invest[$issue->resolvedBy])) $invest[$issue->resolvedBy]['resolvedIssue'] += 1;
            if($issue->assignedTo && isset($invest[$issue->assignedTo]) && in_array($issue->status, array('unconfirmed', 'confirmed', 'active'))) $invest[$issue->assignedTo]['pendingIssue'] += 1;
        }

        return $invest;
    }

    /**
     * 获取用户参与的 bug 和需求。
     * Get user bug and story invest.
     *
     * @param  array  $accounts
     * @param  int    $programID
     * @access public
     * @return array
     */
    public function getBugAndStoryInvest(array $accounts, int $programID): array
    {
        /* Get invest products. */
        $productPairs = $this->loadModel('product')->getPairs('', $programID);
        $productKeys  = array_keys($productPairs);

        /* Get invest bugs, requirements and stories. */
        $bugs = $this->dao->select('id,status,openedBy,assignedTo,resolvedBy')->from(TABLE_BUG)
            ->where('product')->in($productKeys)
            ->andWhere('deleted')->eq(0)
            ->fetchAll('id');
        $requirement = $this->dao->select('openedBy, count(id) as number')->from(TABLE_STORY)
            ->where('product')->in($productKeys)
            ->andWhere('openedBy')->in(array_keys($accounts))
            ->andWhere('type')->eq('requirement')
            ->andWhere('deleted')->eq(0)
            ->groupBy('openedBy')
            ->fetchPairs('openedBy');
        $story = $this->dao->select('openedBy, count(id) as number')->from(TABLE_STORY)
            ->where('product')->in($productKeys)
            ->andWhere('openedBy')->in(array_keys($accounts))
            ->andWhere('type')->eq('story')
            ->andWhere('deleted')->eq(0)
            ->groupBy('openedBy')
            ->fetchPairs('openedBy');

        /* Initialize bugs and requirements related to personnel. */
        $invest = array();
        foreach($accounts as $account => $project) $invest[$account]['createdBug'] = $invest[$account]['resolvedBug'] = $invest[$account]['pendingBug'] = $invest[$account]['UR'] = $invest[$account]['SR'] = 0;

        /* Calculate the number of user invest bugs, requirements and stories. */
        foreach($requirement as $account => $number) $invest[$account]['UR'] = $number;
        foreach($story as $account => $number)       $invest[$account]['SR'] = $number;
        foreach($bugs as $bug)
        {
            if($bug->openedBy && isset($invest[$bug->openedBy])) $invest[$bug->openedBy]['createdBug'] += 1;
            if($bug->resolvedBy && isset($invest[$bug->resolvedBy])) $invest[$bug->resolvedBy]['resolvedBug'] += 1;
            if($bug->assignedTo && $bug->status == 'active' && isset($invest[$bug->assignedTo])) $invest[$bug->assignedTo]['pendingBug'] += 1;
        }

        return $invest;
    }

    /**
     * 获取项目成员帐户和参与项目的数量。
     * Get the project member accounts and the number of participating projects.
     *
     * @param  array  $projects
     * @access public
     * @return array
     */
    public function getInvolvedProjects(array $projects): array
    {
        return $this->dao->select('account, count(root) as projects')->from(TABLE_TEAM)
            ->where('root')->in(array_keys($projects))
            ->andWhere('type')->eq('project')
            ->groupBy('account')
            ->fetchPairs('account');
    }

    /**
     * 获取项目下的迭代或阶段。
     * Gets the iteration or phase under the project.
     *
     * @param  array  $projects
     * @access public
     * @return array
     */
    public function getInvolvedExecutions(array $projects): array
    {
        $executions = $this->dao->select('id,id')->from(TABLE_PROJECT)
            ->where('type')->in('stage,sprint')
            ->andWhere('project')->in(array_keys($projects))
            ->andWhere('deleted')->eq('0')
            ->fetchPairs();

        return $this->dao->select('account, count(root) as executions')->from(TABLE_TEAM)
            ->where('root')->in(array_keys($executions))
            ->andWhere('type')->in('execution')
            ->groupBy('account')
            ->fetchPairs('account');
    }

    /**
     * 获取投入项目的任务。
     * Get project task invest.
     *
     * @param  array  $projects
     * @param  array  $accounts
     * @access public
     * @return array
     */
    public function getProjectTaskInvest(array $projects, array $accounts): array
    {
        /* Get the tasks in the projects. */
        $tasks = $this->dao->select('id,status,openedBy,finishedBy,assignedTo,project')->from(TABLE_TASK)
          ->where('project')->in(array_keys($projects))
          ->andWhere('deleted')->eq('0')
          ->fetchAll('id');

        /* Initialize personnel related tasks. */
        $invest = array();
        foreach($accounts as $account => $project)
        {
            $invest[$account]['createdTask'] = $invest[$account]['finishedTask'] = $invest[$account]['pendingTask'] = $invest[$account]['consumedTask'] = $invest[$account]['leftTask'] = 0;
        }

        /* Number of tasks per person. */
        $userTasks = array();
        foreach($tasks as $task)
        {
            if($task->openedBy && isset($invest[$task->openedBy]))
            {
                $invest[$task->openedBy]['createdTask'] += 1;
                $userTasks[$task->openedBy][$task->id]   = $task->id;
            }
            if($task->finishedBy && isset($invest[$task->finishedBy]))
            {
                $invest[$task->finishedBy]['finishedTask'] += 1;
                $userTasks[$task->finishedBy][$task->id]    = $task->id;
            }
            if($task->assignedTo && isset($invest[$task->assignedTo]))
            {
                if($task->status == 'wait') $invest[$task->assignedTo]['pendingTask'] += 1;
                $userTasks[$task->assignedTo][$task->id] = $task->id;
            }
        }

        /* The number of hours per person. */
        $userHours = $this->getUserHours($userTasks);
        foreach($userHours as $account => $hours)
        {
            $invest[$account]['leftTask']     = $hours->left;
            $invest[$account]['consumedTask'] = $hours->consumed;
        }

        return $invest;
    }

    /**
     * Get user hours.
     *
     * @param  object    $userTasks
     * @access public
     * @return object
     */
    public function getUserHours($userTasks)
    {
        $accounts   = array();
        $taskIDList = array();
        foreach($userTasks as $account => $taskID)
        {
            $accounts[] = $account;
            $taskIDList = array_merge($taskIDList, $taskID);
        }

        $effortList = $this->dao->select('id, account, objectID , `left`, consumed')->from(TABLE_EFFORT)
            ->where('account')->in($accounts)
            ->andWhere('deleted')->eq(0)
            ->andWhere('objectID')->in($taskIDList)
            ->andWhere('objectType')->eq('task')
            ->orderBy('id_asc')
            ->fetchGroup('account', 'id');

        $userHours = array();
        foreach($effortList as $account => $efforts)
        {
            $latestLeft = array();

            $userHours[$account] = new stdclass();
            $userHours[$account]->left     = 0;
            $userHours[$account]->consumed = 0;

            foreach($efforts as $effort)
            {
                $latestLeft[$effort->objectID]  = $effort->left;
                $userHours[$account]->consumed += $effort->consumed;
            }
            $userHours[$account]->left = array_sum($latestLeft);
        }

        return $userHours;
    }

    /**
     * Get objects to copy.
     *
     * @param  int    $objectID
     * @param  int    $objectType
     * @param  bool   $addCount
     * @access public
     * @return array
     */
    public function getCopiedObjects($objectID, $objectType, $addCount = false)
    {
        $objects = array();

        if($objectType == 'sprint')
        {
            $parentID = 0;
            $this->loadModel('execution');
            $execution = $this->execution->getByID($objectID);
            $parentID  = $execution->project;
            $project   = $this->execution->getByID($parentID);
            $objects   = array($project->id => $project->name);

            $objects += $this->dao->select('id,name')->from(TABLE_EXECUTION)
                ->where('project')->eq($parentID)
                ->andWhere('id')->in($this->app->user->view->sprints)
                ->andWhere('deleted')->eq(0)
                ->orderBy('openedDate_desc')
                ->limit('10')
                ->fetchPairs();
        }
        elseif($objectType == 'project')
        {
            $path     = $this->dao->select('path')->from(TABLE_PROJECT)->where('id')->eq($objectID)->fetch('path');
            $path     = explode(',', trim($path, ','));
            $parentID = $path[0] == $objectID ? 0 : $path[0];
            $objects  = $this->loadModel('project')->getPairsByProgram((int)$parentID);
        }
        elseif($objectType == 'product')
        {
            $parentID = $this->dao->select('program')->from(TABLE_PRODUCT)->where('id')->eq($objectID)->fetch('program');
            $objects  = $this->loadModel('product')->getPairs('', $parentID);
        }
        elseif($objectType == 'program')
        {
            $objects = $this->loadModel('program')->getPairs();
        }

        unset($objects[$objectID]);

        if($addCount)
        {
            $objectType = $objectType == 'sprint' ? 'execution' : $objectType;
            $countPairs = $this->dao->select('root, COUNT(*) as count')->from(TABLE_TEAM)
                ->where('type')->eq($objectType)
                ->andWhere('root')->in(array_keys($objects))
                ->beginIF($objectType == 'execution')
                ->orWhere('( type')->eq('project')
                ->andWhere('root')->eq($parentID)->markRight(1)
                ->fi()
                ->groupBy('root')
                ->fetchPairs('root');

            foreach($objects as $objectID => $objectName)
            {
                $memberCount = zget($countPairs, $objectID, 0);
                $countTip    = $memberCount > 1 ? str_replace('member', 'members', $this->lang->personnel->countTip) : $this->lang->personnel->countTip;
                $objects[$objectID] = $objectName . sprintf($countTip, $memberCount);
            }
        }

        return $objects;
    }

    /**
     * Access to program set invest staff.
     *
     * @param  int       $objectID
     * @param  string    $objectType  program|project|product|sprint
     * @param  string    $orderBy
     * @param  object    $pager
     * @access public
     * @return array
     */
    public function getWhitelist($objectID = 0, $objectType = '', $orderBy = 'id_desc', $pager = '')
    {
        return $this->dao->select('t1.id,t1.account,t2.realname,t2.dept,t2.role,t2.phone,t2.qq,t2.weixin,t2.email')->from(TABLE_ACL)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account = t2.account')
            ->where('t1.objectID')->eq($objectID)
            ->andWhere('t1.type')->eq('whitelist')
            ->andWhere('t1.objectType')->eq($objectType)
            ->andWhere('t2.realname')->ne('')
            ->orderBy($orderBy)
            ->beginIF(!empty($pager))->page($pager)->fi()
            ->fetchAll();
    }

    /**
     * Get whitelisted accounts.
     *
     * @param  int    $objectID
     * @param  string $objectType
     * @access public
     * @return array
     */
    public function getWhitelistAccount(int $objectID = 0, string $objectType = '')
    {
        return $this->dao->select('account')->from(TABLE_ACL)->where('objectID')->eq($objectID)->andWhere('objectType')->eq($objectType)->fetchPairs('account', 'account');
    }

    /**
     * Adding users to access control lists.
     *
     * @param  array   $users
     * @param  string  $objectType  program|project|product|sprint
     * @param  int     $objectID
     * @param  string  $type        whitelist|blacklist
     * @param  string  $source      upgrade|add|sync
     * @param  string  $updateType  increase|replace
     * @access public
     * @return void
     */
    public function updateWhitelist(array $users = array(), string $objectType = '', int $objectID = 0, string $type = 'whitelist', string $source = 'add', string $updateType = 'replace')
    {
        $oldWhitelist = $this->dao->select('account,objectType,objectID,type,source')->from(TABLE_ACL)->where('objectID')->eq($objectID)->andWhere('objectType')->eq($objectType)->fetchAll('account');
        if($updateType == 'replace') $this->dao->delete()->from(TABLE_ACL)->where('objectID')->eq($objectID)->andWhere('objectType')->eq($objectType)->exec();

        $users    = array_unique(array_filter($users));
        $accounts = array();
        foreach($users as $account)
        {
            $accounts[$account] = $account;

            if(isset($oldWhitelist[$account]))
            {
                if($updateType == 'replace') $this->dao->insert(TABLE_ACL)->data($oldWhitelist[$account])->exec();
                continue;
            }

            $acl             = new stdClass();
            $acl->account    = $account;
            $acl->objectType = $objectType;
            $acl->objectID   = $objectID;
            $acl->type       = $type;
            $acl->source     = $source;
            $this->dao->insert(TABLE_ACL)->data($acl)->autoCheck()->exec();
            if(!dao::isError()) $this->loadModel('user')->updateUserView($acl->objectID, $acl->objectType, array($acl->account));
        }

        /* Update whitelist field. */
        $objectTable = $objectType == 'product' ? TABLE_PRODUCT : TABLE_PROJECT;
        if($updateType == 'increase')
        {
            $oldWhitelist = $this->dao->select('whitelist')->from($objectTable)->where('id')->eq($objectID)->fetch('whitelist');

            $groups = $this->dao->select('id')->from(TABLE_GROUP)->where('id')->in($oldWhitelist)->fetchPairs('id', 'id');
            if($groups)
            {
                $oldWhitelist = $this->dao->select('account')->from(TABLE_USERGROUP)->where('`group`')->in($groups)->fetchPairs('account', 'account');
                if($oldWhitelist) $accounts = array_unique(array_merge($accounts, $oldWhitelist));
            }
            else
            {
                $oldWhitelist = $this->dao->select('account')->from(TABLE_USER)->where('account')->in($oldWhitelist)->fetchPairs('account', 'account');
                if($oldWhitelist) $accounts = array_unique(array_merge($accounts, $oldWhitelist));
            }
        }
        $whitelist = !empty($accounts) ? ',' . implode(',', $accounts) : '';
        $this->dao->update($objectTable)->set('whitelist')->eq($whitelist)->where('id')->eq($objectID)->exec();

        $deletedAccounts = array();
        foreach($oldWhitelist as $account => $whitelist)
        {
            if(!isset($accounts[$account])) $deletedAccounts[] = $account;
        }

        /* Synchronization of people from the product whitelist to the program set. */
        if($objectType == 'product')
        {
            $product = $this->loadModel('product')->getById($objectID);
            if(empty($product)) return false;

            $programWhitelist = $this->getWhitelistAccount($product->program, 'program');
            $newWhitelist     = array_merge($programWhitelist, $accounts);
            $source           = $source == 'upgrade' ? 'upgrade' : 'sync';
            $this->updateWhitelist($newWhitelist, 'program', $product->program, 'whitelist', $source, $updateType);

            /* Removal of persons from centralized program whitelisting. */
            if($updateType == 'replace')
            {
                foreach($deletedAccounts as $account) $this->deleteProgramWhitelist($objectID, $account);
            }
        }

        /* Synchronization of people from the sprint white list to the project. */
        if($objectType == 'sprint')
        {
            $sprint = $this->dao->select('id,project')->from(TABLE_PROJECT)->where('id')->eq($objectID)->fetch();
            if(empty($sprint)) return false;

            $projectWhitelist = $this->getWhitelistAccount($sprint->project, 'project');
            $newWhitelist     = array_merge($projectWhitelist, $accounts);
            $source           = $source == 'upgrade' ? 'upgrade' : 'sync';
            $this->updateWhitelist($newWhitelist, 'project', $sprint->project, 'whitelist', $source, $updateType);

            /* Removal of whitelisted persons from projects. */
            if($updateType == 'replace')
            {
                foreach($deletedAccounts as $account) $this->deleteProjectWhitelist($objectID, $account);
            }
        }

        /* Update user view. */
        $this->loadModel('user')->updateUserView($objectID, $objectType, $deletedAccounts);
    }

    /**
     * Adding users to access control lists.
     *
     * @param  string  $objectType  program|project|product|sprint
     * @param  int     $objectID
     * @access public
     * @return void
     */
    public function addWhitelist($objectType = '', $objectID = 0)
    {
        $users = $this->post->account;
        if(empty($users)) $users = array();
        $this->updateWhitelist($users, $objectType, (int)$objectID);
    }

    /**
     * Delete product whitelist.
     *
     * @param  int    $productID
     * @param  string $account
     * @access public
     * @return void
     */
    public function deleteProductWhitelist($productID, $account = '')
    {
        $product = $this->dao->select('id,whitelist')->from(TABLE_PRODUCT)->where('id')->eq($productID)->fetch();
        if(empty($product)) return false;

        $result = $this->dao->delete()->from(TABLE_ACL)
             ->where('objectID')->eq($productID)
             ->andWhere('account')->eq($account)
             ->andWhere('objectType')->eq('product')
             ->andWhere('source')->eq('sync')
             ->exec();

        /* Update user view when delete success. */
        if($result)
        {
            $newWhitelist = str_replace(',' . $account, '', $product->whitelist);
            $this->dao->update(TABLE_PRODUCT)->set('whitelist')->eq($newWhitelist)->where('id')->eq($productID)->exec();

            $viewProducts    = $this->dao->select('products')->from(TABLE_USERVIEW)->where('account')->eq($account)->fetch('products');
            $newViewProducts = trim(str_replace(",$productID,", '', ",$viewProducts,"), ',');
            $this->dao->update(TABLE_USERVIEW)->set('products')->eq($newViewProducts)->where('account')->eq($account)->exec();
        }
    }

    /**
     * Determine whether the user exists in the white list of multiple products.
     *
     * @param  int     $programID
     * @param  string  $account
     * @access public
     * @return void
     */
    public function deleteProgramWhitelist(int $programID = 0, string $account = '')
    {
        $program = $this->loadModel('program')->getByID($programID);
        if(empty($program)) return false;

        $products  = $this->dao->select('id')->from(TABLE_PRODUCT)->where('program')->eq($programID)->andWhere('deleted')->eq('0')->fetchPairs('id');
        $whitelist = $this->dao->select('*')->from(TABLE_ACL)->where('objectID')->in($products)->andWhere('account')->eq($account)->andWhere('objectType')->eq('product')->fetch();

        /* Determine if the user exists in other products in the program set. */
        if(empty($whitelist))
        {
            $result = $this->dao->delete()->from(TABLE_ACL)
                ->where('objectID')->eq($programID)
                ->andWhere('account')->eq($account)
                ->andWhere('objectType')->eq('program')
                ->andWhere('source')->eq('sync')
                ->exec();

            /* Update user view when delete success. */
            if($result)
            {
                $newWhitelist = str_replace(',' . $account, '', $program->whitelist);
                $this->dao->update(TABLE_PROGRAM)->set('whitelist')->eq($newWhitelist)->where('id')->eq($programID)->exec();

                $viewPrograms    = $this->dao->select('programs')->from(TABLE_USERVIEW)->where('account')->eq($account)->fetch('programs');
                $newViewPrograms = trim(str_replace(",$programID,", '', ",$viewPrograms,"), ',');
                $this->dao->update(TABLE_USERVIEW)->set('programs')->eq($newViewPrograms)->where('account')->eq($account)->exec();
            }
        }
        $this->loadModel('user')->updateUserView($programID, 'program', array($account));
    }

    /**
     * Determine if the user is on a whitelist for multiple sprints
     *
     * @param  int     $objectID
     * @param  string  $account
     * @access public
     * @return void
     */
    public function deleteProjectWhitelist(int $objectID = 0, string $account = ''): void
    {
        $project = $this->dao->select('id,project,whitelist')->from(TABLE_PROJECT)->where('id')->eq($objectID)->fetch();
        if(empty($project)) return;

        $projectID = $project->project ? $project->project : $objectID;
        $sprints   = $this->dao->select('id')->from(TABLE_PROJECT)->where('project')->eq($projectID)->andWhere('deleted')->eq('0')->fetchPairs('id');
        $whitelist = $this->dao->select('*')->from(TABLE_ACL)->where('objectID')->in($sprints)->andWhere('account')->eq($account)->andWhere('objectType')->eq('sprint')->fetch();

        /* Determine if the user exists in other sprints in the project set. */
        if(empty($whitelist))
        {
            $result = $this->dao->delete()->from(TABLE_ACL)
                ->where('objectID')->eq($projectID)
                ->andWhere('account')->eq($account)
                ->andWhere('objectType')->eq('project')
                ->andWhere('source')->eq('sync')
                ->exec();

            /* Update user view when delete success. */
            if($result)
            {
                $newWhitelist = str_replace(',' . $account, '', $project->whitelist);
                $this->dao->update(TABLE_PROJECT)->set('whitelist')->eq($newWhitelist)->where('id')->eq($projectID)->exec();

                $viewProjects    = $this->dao->select('projects')->from(TABLE_USERVIEW)->where('account')->eq($account)->fetch('projects');
                $newViewProjects = trim(str_replace(",$projectID,", '', ",$viewProjects,"), ',');
                $this->dao->update(TABLE_USERVIEW)->set('projects')->eq($newViewProjects)->where('account')->eq($account)->exec();
            }
        }
        $this->loadModel('user')->updateUserView($projectID, 'project', array($account));
    }

    /**
     * Delete execution whitelist.
     *
     * @param  int    $executionID
     * @param  string $account
     * @access public
     * @return void
     */
    public function deleteExecutionWhitelist($executionID, $account = '')
    {
        $execution = $this->dao->select('id,whitelist')->from(TABLE_EXECUTION)->where('id')->eq($executionID)->fetch();
        if(empty($execution)) return false;

        $result = $this->dao->delete()->from(TABLE_ACL)
             ->where('objectID')->eq($executionID)
             ->andWhere('account')->eq($account)
             ->andWhere('objectType')->eq('sprint')
             ->andWhere('source')->eq('sync')
             ->exec();

        /* Update user view when delete success. */
        if($result)
        {
            $newWhitelist = str_replace(',' . $account, '', $execution->whitelist);
            $this->dao->update(TABLE_EXECUTION)->set('whitelist')->eq($newWhitelist)->where('id')->eq($executionID)->exec();

            $viewExecutions    = $this->dao->select('sprints')->from(TABLE_USERVIEW)->where('account')->eq($account)->fetch('sprints');
            $newViewExecutions = trim(str_replace(",$executionID,", '', ",$viewExecutions,"), ',');
            $this->dao->update(TABLE_USERVIEW)->set('sprints')->eq($newViewExecutions)->where('account')->eq($account)->exec();
        }
    }

    /**
     * Delete users in whitelist.
     *
     * @param  array  $users
     * @param  string $objectType
     * @param  int    $objectID
     * @param  int    $groupID
     * @access public
     * @return void
     */
    public function deleteWhitelist($users = array(), $objectType = 'program', $objectID = 0, $groupID = 0)
    {
        $userGroups = $this->loadModel('group')->getByAccounts($users);

        /* Determine whether to delete the whitelist. */
        foreach($users as $account)
        {
            $groups = zget($userGroups, $account, array());
            foreach($groups as $group)
            {
                if($group->id == $groupID) continue;

                $acl     = json_decode($group->acl);
                $keyName = $objectType . 's';
                if(isset($acl->$keyName) and in_array($objectID, $acl->$keyName)) return false;
            }

            if($objectType == 'program') $this->deleteProgramWhitelist($objectID, $account);
            if($objectType == 'project') $this->deleteProjectWhitelist($objectID, $account);
            if($objectType == 'product') $this->deleteProductWhitelist($objectID, $account);
            if($objectType == 'sprint')  $this->deleteExecutionWhitelist($objectID, $account);
        }
    }

    /**
     * Create access links by department.
     *
     * @param  object  $dept
     * @param  int     $programID
     * @access public
     * @return string
     */
    public function createMemberLink($dept = 0, $programID = 0)
    {
        return helper::createLink('personnel', 'accessible', "program={$programID}&deptID={$dept->id}");
    }

    /**
     * Build search form.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return void
     */
    public function buildSearchForm($queryID = 0, $actionURL = '')
    {
        $this->config->personnel->accessible->search['actionURL'] = $actionURL;
        $this->config->personnel->accessible->search['queryID']   = $queryID;

        $this->loadModel('search')->setSearchParams($this->config->personnel->accessible->search);
    }

    /**
     * 判断操作是否可以点击。
     * Judge an action is clickable or not.
     *
     * @param  object $whitelist
     * @param  string $action
     * @access public
     * @return bool
     */
    public function isClickable(object $whitelist, string $action): bool
    {
        return true;
    }
}
