<?php
declare(strict_types=1);
class stakeholderModel extends model
{
    /**
     * 创建一个干系人。
     * Create a stakeholder.
     *
     * @param  object   $data
     * @access public
     * @return int|bool
     */
    public function create(object $data): int|bool
    {
        $account = $this->replaceUserInfo($data);
        if(dao::isError()) return false;

        $stakeholder = new stdclass();
        $stakeholder->user        = $account;
        $stakeholder->objectType  = $data->objectType;
        $stakeholder->objectID    = $data->objectID;
        $stakeholder->key         = $data->key;
        $stakeholder->from        = $data->from;
        $stakeholder->type        = $data->from == 'team' ? 'inside' : 'outside';
        $stakeholder->createdBy   = $this->app->user->account;
        $stakeholder->createdDate = helper::today();
        $this->dao->insert(TABLE_STAKEHOLDER)->data($stakeholder)->check('user', 'unique', "objectID = {$stakeholder->objectID} && deleted = '0'")->autoCheck()->exec();

        $stakeholderID = $this->dao->lastInsertID();
        if(dao::isError()) return false;

        $userList = empty($stakeholder->user) ? array() : array($stakeholder->user);
        $this->loadModel('user')->updateUserView(array($stakeholder->objectID), $stakeholder->objectType, $userList);

        /* Update linked products view. */
        if($stakeholder->objectType == 'project' && $stakeholder->objectID)
        {
            $this->loadModel('project')->updateInvolvedUserView($stakeholder->objectID, $userList);
        }

        if($stakeholder->objectType == 'program' && $stakeholder->objectID)
        {
            $programID = $stakeholder->objectID;

            /* Update children user view. */
            $childPrograms = $this->dao->select('id')->from(TABLE_PROJECT)->where('path')->like("%,$programID,%")->andWhere('type')->eq('program')->fetchPairs();
            $childProjects = $this->dao->select('id')->from(TABLE_PROJECT)->where('path')->like("%,$programID,%")->andWhere('type')->eq('project')->fetchPairs();
            $childProducts = $this->dao->select('id')->from(TABLE_PRODUCT)->where('program')->eq($programID)->fetchPairs();

            if(!empty($childPrograms)) $this->user->updateUserView($childPrograms, 'program', array($stakeholder->user));
            if(!empty($childProjects)) $this->user->updateUserView($childProjects, 'project', array($stakeholder->user));
            if(!empty($childProducts)) $this->user->updateUserView($childProducts, 'product', array($stakeholder->user));
        }

        return $stakeholderID;
    }

    /**
     * 更新/插入用户信息。
     * Update/insert user info.
     *
     * @param  object      $data
     * @access public
     * @return bool|string
     */
    public function replaceUserInfo(object $data): bool|string
    {
        $account = isset($data->user) ? $data->user : '';
        if($data->from != 'outside')
        {
            if(!$account) return dao::$errors['user'] = sprintf($this->lang->error->notempty, $this->lang->stakeholder->user);

            $user = new stdclass();
            $user->nature   = $data->nature;
            $user->analysis = $data->analysis;
            $user->strategy = $data->strategy;
            $this->dao->update(TABLE_USER)->data($user)->where('account')->eq($account)->exec();

            return $account;
        }

        if($account) return $account;

        /* If it's an outsider and it's added for the first time, insert to user table. */
        if(!$data->newUser && !$account) return dao::$errors['user'] = sprintf($this->lang->error->notempty, $this->lang->stakeholder->user);
        if(!$data->name) return dao::$errors['name'] = sprintf($this->lang->error->notempty, $this->lang->stakeholder->name);
        if(!$data->newCompany && !$data->company)    return dao::$errors['company'] = sprintf($this->lang->error->notempty, $this->lang->stakeholder->company);
        if($data->newCompany && !$data->companyName) return dao::$errors['company'] = sprintf($this->lang->error->notempty, $this->lang->stakeholder->company);

        $companyID = $data->company;
        if(isset($data->newCompany) && $data->companyName)
        {
            $company = new stdclass();
            $company->name = $data->companyName;
            $this->dao->insert(TABLE_COMPANY)->data($company)->autoCheck()->exec();
            $companyID = $this->dao->lastInsertID();
        }

        /* Create new user. */
        $user = new stdclass();
        $user->type     = 'outside';
        $user->realname = $data->name;
        $user->account  = mt_rand(1111, 99999);
        $user->company  = $companyID;
        $user->phone    = $data->phone;
        $user->qq       = $data->qq;
        $user->weixin   = $data->weixin;
        $user->email    = $data->email;
        $user->nature   = $data->nature;
        $user->analysis = $data->analysis;
        $user->strategy = $data->strategy;
        $this->dao->insert(TABLE_USER)->data($user)->exec();

        $userID  = $this->dao->lastInsertID();
        $account = 'u' . $userID;
        $this->dao->update(TABLE_USER)->set('account')->eq($account)->where('id')->eq($userID)->exec();

        if(dao::isError()) return false;
        return $account;
    }

    /**
     * 批量创建项目的干系人。
     * Batch create stakeholders for a project.
     *
     * @param  int    $projectID
     * @param  array  $accounts
     * @access public
     * @return array
     */
    public function batchCreate(int $projectID, array $accounts): array
    {
        $this->loadModel('action');

        $members  = $this->loadModel('user')->getTeamMemberPairs($projectID, 'project');
        $accounts = array_unique($accounts);
        $oldJoin  = $this->dao->select('`user`, createdDate')->from(TABLE_STAKEHOLDER)->where('objectID')->eq($projectID)->andWhere('objectType')->eq('project')->fetchPairs();
        $this->dao->delete()->from(TABLE_STAKEHOLDER)->where('objectID')->eq($projectID)->andWhere('objectType')->eq('project')->exec();

        $stakeholderList = array();
        foreach($accounts as $account)
        {
            if(empty($account)) continue;

            $stakeholder = new stdclass();
            $stakeholder->objectID    = $projectID;
            $stakeholder->objectType  = 'project';
            $stakeholder->user        = $account;
            $stakeholder->type        = in_array($account, array_keys($members)) ? 'inside' : 'outside';
            $stakeholder->from        = $stakeholder->type == 'inside' ? 'team' : 'company';
            $stakeholder->createdBy   = $this->app->user->account;
            $stakeholder->createdDate = isset($oldJoin[$account]) ? $oldJoin[$account] : helper::today();
            $this->dao->insert(TABLE_STAKEHOLDER)->data($stakeholder)->exec();

            $stakeholderID     = $this->dao->lastInsertId();
            $stakeholderList[] = $stakeholderID;
            $this->action->create('stakeholder', $stakeholderID, 'added');
        }

        /* Only changed account update userview. */
        $oldAccounts     = array_keys($oldJoin);
        $changedAccounts = array_diff($accounts, $oldAccounts);
        $changedAccounts = array_merge($changedAccounts, array_diff($oldAccounts, $accounts));
        $changedAccounts = array_unique($changedAccounts);
        $this->loadModel('user')->updateUserView(array($projectID), 'project', $changedAccounts);
        $this->loadModel('project')->updateInvolvedUserView($projectID, $changedAccounts);

        if(!empty($accounts) && $stakeholder->objectType == 'program' && $stakeholder->objectID)
        {
            /* Update children user view. */
            $programID     = $stakeholder->objectID;
            $childPrograms = $this->dao->select('id')->from(TABLE_PROJECT)->where('path')->like("%,$programID,%")->andWhere('type')->eq('program')->fetchPairs();
            $childProjects = $this->dao->select('id')->from(TABLE_PROJECT)->where('path')->like("%,$programID,%")->andWhere('type')->eq('project')->fetchPairs();
            $childProducts = $this->dao->select('id')->from(TABLE_PRODUCT)->where('program')->eq($programID)->fetchPairs();

            if(!empty($childPrograms)) $this->user->updateUserView($childPrograms, 'program', $stakeholder->user);
            if(!empty($childProjects)) $this->user->updateUserView($childProjects, 'project', $stakeholder->user);
            if(!empty($childProducts)) $this->user->updateUserView($childProducts, 'product', $stakeholder->user);
        }

        return $stakeholderList;
    }

    /**
     * 编辑一个干系人。
     * Edit a stakeholder.
     *
     * @param  int        $stakeholderID
     * @param  object     $data
     * @access public
     * @return bool|array
     */
    public function edit(int $stakeholderID, object $data): bool|array
    {
        $oldStakeholder = $this->getByID($stakeholderID);
        if(!$oldStakeholder) return false;

        $user = new stdclass();
        if($oldStakeholder->from == 'outside')
        {
            if(empty($data->name))
            {
                dao::$errors['name'] = sprintf($this->lang->error->notempty, $this->lang->stakeholder->name);
                return false;
            }

            $user->realname = $data->name;
            $user->phone    = $data->phone;
            $user->qq       = $data->qq;
            $user->weixin   = $data->weixin;
            $user->email    = $data->email;
        }

        $user->nature   = $data->nature;
        $user->analysis = $data->analysis;
        $user->strategy = $data->strategy;
        $this->dao->update(TABLE_USER)->data($user)->where('account')->eq($oldStakeholder->user)->exec();
        if(dao::isError()) return false;

        $stakeholder = new stdclass();
        $stakeholder->key        = $data->key;
        $stakeholder->editedBy   = $this->app->user->account;
        $stakeholder->editedDate = helper::today();

        $this->dao->update(TABLE_STAKEHOLDER)->data($stakeholder)
            ->autoCheck()
            ->where('id')->eq($stakeholderID)
            ->exec();

        if(dao::isError()) return false;
        return common::createChanges($oldStakeholder, $stakeholder);
    }

    /**
     * 获取干系人列表数据。
     * Get stakeholder list.
     *
     * @param  int    $projectID
     * @param  string $browseType all|inside|outside|key
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getStakeholders(int $projectID, string $browseType = 'all', string $orderBy = 'id_desc', object $pager = null): array
    {
        return $this->dao->select('t1.*, t2.phone, t2.realname as name, t2.email, t2.qq, t2.weixin, t2.nature, t2.analysis, t2.strategy, t3.name as companyName, t4.model as projectModel')->from(TABLE_STAKEHOLDER)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.user=t2.account')
            ->leftJoin(TABLE_COMPANY)->alias('t3')->on('t2.company=t3.id')
            ->leftJoin(TABLE_PROJECT)->alias('t4')->on('t1.objectID=t4.id')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t1.objectID')->eq($projectID)
            ->beginIF($browseType == 'inside')->andWhere('t1.type')->eq('inside')->fi()
            ->beginIF($browseType == 'outside')->andWhere('t1.type')->eq('outside')->fi()
            ->beginIF($browseType == 'key')->andWhere('t1.key')->ne('0')->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * 获取干系人account => realname键值对。
     * Get the stakeholder account => realname key-value pair.
     *
     * @param  int    $objectID
     * @access public
     * @return array
     */
    public function getStakeHolderPairs(int $objectID): array
    {
        return $this->dao->select('t1.user, t2.realname')->from(TABLE_STAKEHOLDER)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.user=t2.account')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t1.objectID')->eq($objectID)
            ->orderBy('t1.id_desc')
            ->fetchPairs();
    }

    /**
     * 获取按照对象ID分组的干系人列表。
     * Get stakeholder group by object id.
     *
     * @param  array  $objectIdList
     * @access public
     * @return array
     */
    public function getStakeholderGroup(array $objectIdList): array
    {
        $stakeholders     = $this->dao->select('objectID, user')->from(TABLE_STAKEHOLDER)->where('objectID')->in($objectIdList)->andWhere('deleted')->eq('0')->fetchAll();
        $stakeholderGroup = array();
        foreach($stakeholders as $stakeholder)
        {
            $stakeholderGroup[$stakeholder->objectID][$stakeholder->user] = $stakeholder->user;
        }

        return $stakeholderGroup;
    }

    /**
     * 获取父项目集/父项目的干系人列表。
     * Get the stakeholder list for the parent program / parent project.
     *
     * @param  array  $objectIdList
     * @access public
     * @return array
     */
    public function getParentStakeholderGroup(array $objectIdList): array
    {
        $objects = $this->dao->select('id, path, parent')->from(TABLE_PROJECT)->where('id')->in($objectIdList)->andWhere('acl')->ne('open')->fetchAll('id');
        $parents = array();
        foreach($objects as $object)
        {
            if($object->parent == 0) continue;
            foreach(explode(',', $object->path) as $objectID)
            {
                if(empty($objectID)) continue;
                if($objectID == $object->id) continue;
                $parents[$objectID][] = $object->id;
            }
        }

        if(empty($parents)) return array();

        /* Get all parent stakeholders. */
        $parentStakeholders     = $this->dao->select('objectID, user')->from(TABLE_STAKEHOLDER)->where('objectID')->in(array_keys($parents))->andWhere('deleted')->eq('0')->fetchAll();
        $parentStakeholderGroup = array();
        foreach($parentStakeholders as $parentStakeholder)
        {
            $subPrograms = zget($parents, $parentStakeholder->objectID, array());
            foreach($subPrograms as $subProgramID) $parentStakeholderGroup[$subProgramID][$parentStakeholder->user] = $parentStakeholder->user;
        }

        return $parentStakeholderGroup;
    }

    /**
     * 获取项目按照人员类型分组的干系人列表。
     * Get stakeholder group by type.
     *
     * @access public
     * @return array
     */
    public function getListByType(): array
    {
        return $this->dao->select('t2.realname as name, t2.account, t1.type, t2.role')->from(TABLE_STAKEHOLDER)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.user=t2.account')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t1.objectID')->eq($this->session->project)
            ->fetchGroup('type');
    }

    /**
     * 获取干系人信息。
     * Get stakeholder by id.
     *
     * @param  int         $stakeholderID
     * @access public
     * @return object|bool
     */
    public function getByID(int $stakeholderID): object|bool
    {
        return $this->dao->select('t1.*, t2.phone, t2.realname as name, t2.email, t2.qq, t2.weixin, t2.nature, t2.analysis, t2.strategy, t3.name as companyName, t3.id as company')->from(TABLE_STAKEHOLDER)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.user=t2.account')
            ->leftJoin(TABLE_COMPANY)->alias('t3')->on('t2.company=t3.id')
            ->where('t1.id')->eq($stakeholderID)
            ->fetch();
    }

    /**
     * 获取项目按照进度分组的活动列表。
     * Get group activities.
     *
     * @access public
     * @return array
     */
    public function getProcessGroup(): array
    {
        return $this->dao->select('process, activity')->from(TABLE_PROGRAMACTIVITY)
            ->where('project')->eq($this->session->project)
            ->andWhere('result')->eq('yes')
            ->fetchGroup('process');
    }

    /**
     * 获取进度id=>name的键值对。
     * Get the key-value pair of progress id=>name.
     *
     * @access public
     * @return array
     */
    public function getProcess(): array
    {
        return $this->dao->select('id, name')->from(TABLE_PROCESS)
            ->where('deleted')->eq(0)
            ->fetchPairs();
    }

    /**
     * 获取项目的干预列表信息。
     * Get intervention list.
     *
     * @access public
     * @return array
     */
    public function getPlans(): array
    {
        return $this->dao->select('*')->from(TABLE_INTERVENTION)
            ->where('deleted')->eq(0)
            ->andWhere('project')->eq($this->session->project)
            ->fetchAll('activity');
    }

    /**
     * 获取项目下干系人提出的问题列表。
     * Get a list of issues owner by stakeholders under the project.
     *
     * @access public
     * @return array
     */
    public function getIssues(): array
    {
        $stakeholders = $this->getStakeHolderPairs($this->session->project);
        return $this->dao->select('*')->from(TABLE_ISSUE)
            ->where('deleted')->eq(0)
            ->andWhere('project')->eq($this->session->project)
            ->andWhere('owner')->in(array_keys($stakeholders))
            ->orWhere('activity')->ne('')
            ->orderBy('id_desc')
            ->fetchAll('id');
    }

    /**
     * 获取活动 id=>name 的键值对。
     * Get the key-value pair for the activity id=>name.
     *
     * @access public
     * @return array
     */
    public function getActivities(): array
    {
        return $this->dao->select('id, name')->from(TABLE_ACTIVITY)
            ->where('deleted')->eq(0)
            ->fetchPairs();
    }

    /**
     * 添加一条期望记录。
     * Add a expect record.
     *
     * @param  object   $data
     * @access public
     * @return int|bool
     */
    public function expect(object $data): int|bool
    {
        $this->dao->insert(TABLE_EXPECT)->data($data)
            ->autoCheck()
            ->batchCheck($this->config->stakeholder->expect->requiredFields, 'notempty')
            ->exec();

        if(dao::isError()) return false;
        return $this->dao->lastInsertID();
    }

    /**
     * 获取期望列表数据。
     * Get expect list.
     *
     * @param  string $browseType all|bysearch
     * @param  int    $queryID
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getExpectList(string $browseType = 'all', int $queryID = 0, string $orderBy = 'id_desc', object $pager = null): array
    {
        $stakeholderQuery = '';
        if($browseType == 'bysearch')
        {
            $query = $queryID ? $this->loadModel('search')->getQuery($queryID) : '';
            if($query)
            {
                $this->session->set('stakeholderQuery', $query->sql);
                $this->session->set('stakeholderForm', $query->form);
            }
            if($this->session->stakeholderQuery == false) $this->session->set('stakeholderQuery', ' 1=1');
            $stakeholderQuery = $this->session->stakeholderQuery;
        }

        return $this->dao->select('t1.*,t2.key,t3.realname')->from(TABLE_EXPECT)->alias('t1')
            ->leftJoin(TABLE_STAKEHOLDER)->alias('t2')->on('t1.userID=t2.id')
            ->leftJoin(TABLE_USER)->alias('t3')->on('t2.user=t3.account')
            ->where('t1.project')->eq($this->session->project)
            ->beginIF($browseType == 'bysearch')
            ->andWhere($stakeholderQuery)
            ->fi()
            ->andWhere('t1.deleted')->eq('0')
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
    }

    /**
     * 删除一个期望。
     * Delete a expect.
     *
     * @param  int    $expectID
     * @access public
     * @return bool
     */
    public function deleteExpect(int $expectID): bool
    {
        $this->dao->update(TABLE_EXPECT)->set('deleted')->eq('1')->where('id')->eq($expectID)->exec();

        return !dao::isError();
    }

    /**
     * 获取项目下干系人field=>realname的键值对。
     * Get a key-value pair for stakeholder field=>realname under the project.
     *
     * @access public
     * @return array
     */
    public function getStakeholderUsers(string $field = 'id'): array
    {
        return $this->dao->select("t1.{$field}, CONCAT_WS('/', t3.name,t2.realname) as realname")->from(TABLE_STAKEHOLDER)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.user=t2.account')
            ->leftJoin(TABLE_COMPANY)->alias('t3')->on('t2.company=t3.id')
            ->where('t1.objectID')->eq($this->session->project)
            ->andWhere('t1.deleted')->eq('0')
            ->fetchPairs($field, 'realname');
    }

    /**
     * Get expect details.
     *
     * @param  int    $expectID
     * @access public
     * @return object
     */
    public function getExpectByID($expectID = 0)
    {
        return $this->dao->select('*')->from(TABLE_EXPECT)->where('id')->eq($expectID)->andWhere('deleted')->eq('0')->fetch();
    }

    /**
     * 获取给定用户ID的期望信息。
     * Get the expect information by user ID.
     *
     * @param  int    $userID
     * @access public
     * @return array
     */
    public function getExpectByUser(int $userID = 0): array
    {
        return $this->dao->select('*')->from(TABLE_EXPECT)
            ->where('userID')->eq($userID)
            ->andWhere('deleted')->eq('0')
            ->orderBy('id_desc')
            ->fetchAll();
    }

    /**
     * Get stakeholder issue.
     *
     * @param  int    $account
     * @access public
     * @return object
     */
    public function getStakeholderIssue($account)
    {
        return $this->dao->select('*')->from(TABLE_ISSUE)
            ->where('project')->eq($this->session->project)
            ->andWhere('owner')->eq($account)
            ->andWhere('deleted')->eq('0')
            ->orderBy('id_desc')
            ->fetchAll();
    }

    /**
     * Judge the action is clickable.
     *
     * @param  object $object stakeholder
     * @param  string $action
     * @access public
     * @return bool
     */
    public static function isClickable(object $object, string $action): bool
    {
        /* Judge the object whether can be changed. */
        $canChange = common::canBeChanged('stakeholder', $object);
        if(!$canChange) return false;

        /* Special action can be set its own condition. */
        if($action == 'notExists') return false;

        /* The action is clickable by default. */
        return true;
    }
}
