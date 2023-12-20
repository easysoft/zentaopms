<?php
declare(strict_types=1);
class stakeholderModel extends model
{
    /**
     * Create a stakeholder.
     *
     * @param  int objectID
     * @access public
     * @return int|bool
     */
    public function create($objectID = 0)
    {
        $data = fixer::input('post')
            ->setDefault('objectType', $this->app->tab)
            ->setDefault('objectID', $objectID)
            ->setDefault('createdBy', $this->app->user->account)
            ->setDefault('createdDate', helper::today())
            ->stripTags($this->config->stakeholder->editor->create['id'], $this->config->allowedTags)
            ->remove('uid')
            ->get();

        $account = isset($data->user) ? $data->user : '';
        if($data->from != 'outside')
        {
            if(!$account)
            {
                dao::$errors['user'] = $this->lang->stakeholder->userEmpty;
                return false;
            }
            $user = new stdclass();
            $user->nature   = $data->nature;
            $user->analysis = $data->analysis;
            $user->strategy = $data->strategy;

            $this->dao->update(TABLE_USER)->data($user)->where('account')->eq($account)->exec();
        }
        else
        {
            /* If it's an outsider and it's added for the first time, insert to user table. */
            if(!$account)
            {
                if(!$data->name) dao::$errors['name'] = sprintf($this->lang->error->notempty, $this->lang->stakeholder->name);
                if(!isset($data->newCompany) && !$data->company)    dao::$errors['company'] = sprintf($this->lang->error->notempty, $this->lang->stakeholder->company);
                if(isset($data->newCompany) && !$data->companyName) dao::$errors['company'] = sprintf($this->lang->error->notempty, $this->lang->stakeholder->company);
                if(dao::isError()) return false;

                $companyID = $data->company;
                if(isset($data->newCompany) && $data->companyName)
                {
                    $company = new stdclass();
                    $company->name = $data->companyName;
                    $this->dao->insert(TABLE_COMPANY)->data($company)->autoCheck()->exec();

                    $companyID = $this->dao->lastInsertID();
                }

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
            }
        }

        $stakeholder = new stdclass();
        $stakeholder->user        = $account;
        $stakeholder->objectType  = $data->objectType;
        $stakeholder->objectID    = $data->objectID;
        $stakeholder->key         = $data->key;
        $stakeholder->from        = $data->from;
        $stakeholder->type        = $data->from == 'team' ? 'inside' : 'outside';
        $stakeholder->createdBy   = $this->app->user->account;
        $stakeholder->createdDate = helper::today();

        $this->dao->insert(TABLE_STAKEHOLDER)->data($stakeholder)->check('user', 'unique', "objectID = {$stakeholder->objectID} and deleted = '0'")->autoCheck()->exec();
        $stakeholderID = $this->dao->lastInsertID();

        if(!dao::isError())
        {
            $userList = empty($stakeholder->user) ? array() : array($stakeholder->user);

            $this->loadModel('user')->updateUserView($stakeholder->objectID, $stakeholder->objectType, $userList);

            /* Update linked products view. */
            if($stakeholder->objectType == 'project' and $stakeholder->objectID)
            {
                $this->loadModel('project')->updateInvolvedUserView($stakeholder->objectID, $userList);
            }

            if($stakeholder->objectType == 'program' and $stakeholder->objectID)
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
        return false;
    }

    /**
     * Batch create stakeholders for a project.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function batchCreate($projectID)
    {
        $this->loadModel('action');
        $data = (array)fixer::input('post')->get();

        $members  = $this->loadModel('user')->getTeamMemberPairs($projectID, 'project');
        $accounts = array_unique($data['accounts']);
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

        $this->loadModel('user')->updateUserView($projectID, 'project', $changedAccounts);

        $this->loadModel('project')->updateInvolvedUserView($projectID, $changedAccounts);

        if($stakeholder->objectType == 'program' and $stakeholder->objectID)
        {
            $programID = $stakeholder->objectID;
            /* Update children user view. */
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
     * Edit a stakeholder.
     *
     * @param  int $stakeholderID
     * @access public
     * @return void
     */
    public function edit($stakeholderID)
    {
        $oldStakeholder = $this->getByID($stakeholderID);
        $data = fixer::input('post')
            ->stripTags($this->config->stakeholder->editor->edit['id'], $this->config->allowedTags)
            ->remove('uid')
            ->get();

        $user = new stdclass();
        if($oldStakeholder->from == 'outside')
        {
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

        $stakeholder = new stdclass();
        $stakeholder->key        = $data->key;
        $stakeholder->editedBy   = $this->app->user->account;
        $stakeholder->editedDate = helper::today();

        $this->dao->update(TABLE_STAKEHOLDER)->data($stakeholder)
            ->autoCheck()
            ->where('id')->eq($stakeholderID)
            ->exec();

        if(!dao::isError()) return common::createChanges($oldStakeholder, $stakeholder);
        return false;
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
     * Get stakeholder pairs.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getStakeHolderPairs($projectID)
    {
        return $this->dao->select('t1.user, t2.realname')->from(TABLE_STAKEHOLDER)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.user=t2.account')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t1.objectID')->eq($projectID)
            ->orderBy('t1.id_desc')
            ->fetchPairs();
    }

    /**
     * Get self stakeholders by object id list.
     *
     * @param  array  $objectIdList
     * @access public
     * @return array
     */
    public function getStakeholderGroup(array $objectIdList): array
    {
        $stakeholders = $this->dao->select('objectID, user')->from(TABLE_STAKEHOLDER)->where('objectID')->in($objectIdList)->andWhere('deleted')->eq('0')->fetchAll();

        $stakeholderGroup = array();
        foreach($stakeholders as $stakeholder)
        {
            $stakeholderGroup[$stakeholder->objectID][$stakeholder->user] = $stakeholder->user;
        }

        return $stakeholderGroup;
    }

    /**
     * Get parent stakeholder group by object id list.
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

        /* Get all parent stakeholders.*/
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
     * Get stakeholder group by type.
     *
     * @access public
     * @return array
     */
    public function getListByType()
    {
        return $this->dao->select('t2.realname as name, t2.account, t1.type, t2.role')->from(TABLE_STAKEHOLDER)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.user=t2.account')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t1.objectID')->eq($this->session->project)
            ->fetchGroup('type');
    }

    /**
     * Get stakeholder by id.
     *
     * @param  int    $userID
     * @access public
     * @return array
     */
    public function getByID($userID)
    {
        return $this->dao->select('t1.*, t2.phone, t2.realname as name, t2.email, t2.qq, t2.weixin, t2.nature, t2.analysis, t2.strategy, t3.name as companyName, t3.id as company')->from(TABLE_STAKEHOLDER)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.user=t2.account')
            ->leftJoin(TABLE_COMPANY)->alias('t3')->on('t2.company=t3.id')
            ->where('t1.id')->eq($userID)
            ->fetch();
    }

    /**
     * Get group activities.
     *
     * @access public
     * @return array
     */
    public function getProcessGroup()
    {
        return $this->dao->select('process, activity')->from(TABLE_PROGRAMACTIVITY)
            ->where('project')->eq($this->session->project)
            ->andWhere('result')->eq('yes')
            ->fetchGroup('process');
    }

    /**
     * Get process pairs.
     *
     * @access public
     * @return array
     */
    public function getProcess()
    {
        return $this->dao->select('id, name')->from(TABLE_PROCESS)
            ->where('deleted')->eq(0)
            ->fetchPairs();
    }

    /**
     * Get plans.
     *
     * @access public
     * @return array
     */
    public function getPlans()
    {
        return $this->dao->select('*')->from(TABLE_INTERVENTION)
            ->where('deleted')->eq(0)
            ->andWhere('project')->eq($this->session->project)
            ->fetchAll('activity');
    }

    /**
     * Get stakeholder issues.
     *
     * @access public
     * @return array
     */
    public function getIssues()
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
     * Get activity pairs.
     *
     * @access public
     * @return array
     */
    public function getActivities()
    {
        return $this->dao->select('id, name')->from(TABLE_ACTIVITY)
            ->where('deleted')->eq(0)
            ->fetchPairs();
    }

    /**
     * Add communication record.
     *
     * @param  int    $stakeholderID
     * @access public
     * @return void
     */
    public function communicate($stakeholderID)
    {
        $data = fixer::input('post')
            ->stripTags($this->config->stakeholder->editor->communicate['id'], $this->config->allowedTags)
            ->get();
        $this->loadModel('action')->create('stakeholder', $stakeholderID, 'communicate', $data->comment);
    }

    /**
     * Add expect record.
     *
     * @param  int    $userID
     * @access public
     * @return void|int
     */
    public function expect($userID)
    {
        $data = fixer::input('post')
            ->add('userID', $userID)
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', date('Y-m-d'))
            ->add('project', $this->session->project)
            ->stripTags($this->config->stakeholder->editor->expect['id'], $this->config->allowedTags)
            ->get();

        if(strpos($this->config->stakeholder->expect->requiredFields, 'expect') !== false and !$this->post->expect)
        {
            dao::$errors[] = sprintf($this->lang->error->notempty, $this->lang->stakeholder->expect);
            return false;
        }

        if(strpos($this->config->stakeholder->expect->requiredFields, 'progress') !== false and !$this->post->progress)
        {
            dao::$errors[] = sprintf($this->lang->error->notempty, $this->lang->stakeholder->progress);
            return false;
        }

        $this->dao->insert(TABLE_EXPECT)->data($data)
            ->autoCheck()
            ->exec();

        if(!dao::isError()) return $this->dao->lastInsertID();
        return false;
    }

    /**
     * Get expect list.
     *
     * @param  string $browseType
     * @param  int    $queryID
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return object
     */
    public function getExpectList($browseType = 'all', $queryID = 0, $orderBy = 'id_desc', $pager = null)
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

        $expects = $this->dao->select('t1.*,t2.key,t3.realname')->from(TABLE_EXPECT)->alias('t1')
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

        return $expects;
    }

    /**
     * Delete expect.
     *
     * @param  int    $expectID
     * @param  object $null
     * @access public
     * @return void
     */
    public function deleteExpect($expectID, $null = null)
    {
        $this->dao->update(TABLE_EXPECT)->set('deleted')->eq('1')->where('id')->eq($expectID)->exec();
    }

    /**
     * Edit expect.
     *
     * @param  int    $expectID
     * @access public
     * @return object
     */
    public function editExpect($expectID)
    {
        $data = fixer::input('post')
            ->stripTags($this->config->stakeholder->editor->editexpect['id'], $this->config->allowedTags)
            ->get();

        if(strpos($this->config->stakeholder->expect->requiredFields, 'userID') !== false and !$this->post->userID)
        {
            dao::$errors[] = sprintf($this->lang->error->notempty, $this->lang->stakeholder->common);
            return false;
        }

        if(strpos($this->config->stakeholder->expect->requiredFields, 'expect') !== false and !$this->post->expect)
        {
            dao::$errors[] = sprintf($this->lang->error->notempty, $this->lang->stakeholder->expect);
            return false;
        }

        if(strpos($this->config->stakeholder->expect->requiredFields, 'progress') !== false and !$this->post->progress)
        {
            dao::$errors[] = sprintf($this->lang->error->notempty, $this->lang->stakeholder->progress);
            return false;
        }

        $oldExpect = $this->getExpectByID($expectID);
        $this->dao->update(TABLE_EXPECT)->data($data)->where('id')->eq($expectID)->autoCheck()->exec();

        if(dao::isError()) return false;
        return common::createChanges($oldExpect, $data);
    }

    /**
     * Get stakeholder user.
     *
     * @access public
     * @return object
     */
    public function getStakeholderUsers()
    {
        return $this->dao->select("t1.id, CONCAT_WS('/', t3.name,t2.realname) as realname")->from(TABLE_STAKEHOLDER)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.user=t2.account')
            ->leftJoin(TABLE_COMPANY)->alias('t3')->on('t2.company=t3.id')
            ->where('t1.objectID')->eq($this->session->project)
            ->andWhere('t1.deleted')->eq('0')
            ->fetchPairs('id', 'realname');
    }

    /**
     * Get stakeholder pairs for issue.
     *
     * @access public
     * @return object
     */
    public function getStakeholders4Issue()
    {
        return $this->dao->select("t1.user, CONCAT_WS('/', t3.name,t2.realname) as realname")->from(TABLE_STAKEHOLDER)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.user=t2.account')
            ->leftJoin(TABLE_COMPANY)->alias('t3')->on('t2.company=t3.id')
            ->where('t1.objectID')->eq($this->session->project)
            ->andWhere('t1.deleted')->eq('0')
            ->fetchPairs('user', 'realname');
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
     * Build search form.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return void
     */
    public function buildSearchForm($actionURL, $queryID)
    {
        $this->config->stakeholder->search['actionURL'] = $actionURL;
        $this->config->stakeholder->search['queryID']   = $queryID;

        $this->loadModel('search')->setSearchParams($this->config->stakeholder->search);
    }

    /**
     * Get expect details.
     *
     * @param  int    $userID
     * @access public
     * @return object
     */
    public function getExpectByUser($userID = 0)
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
