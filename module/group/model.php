<?php
/**
 * The model file of group module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     group
 * @version     $Id: model.php 4976 2013-07-02 08:15:31Z wyd621@gmail.com $
 * @link        https://www.zentao.net
 */
class groupModel extends model
{
    /**
     * 创建一个分组。
     * Create a group.
     *
     * @access public
     * @return bool
     */
    public function create(object $group): int|false
    {
        if(isset($group->limited))
        {
            unset($group->limited);
            $group->role = 'limited';
        }
        if(!isset($group->vision)) $group->vision = $this->config->vision;

        $this->lang->error->unique = $this->lang->group->repeat;
        $this->dao->insert(TABLE_GROUP)->data($group)
            ->check('name', 'unique', "vision = '{$this->config->vision}'")
            ->exec();
        if(dao::isError()) return false;

        $groupID = $this->dao->lastInsertId();

        $data         = new stdclass();
        $data->group  = $groupID;
        $data->module = 'index';
        $data->method = 'index';
        $this->dao->replace(TABLE_GROUPPRIV)->data($data)->exec();

        return $groupID;
    }

    /**
     * 更新权限分组。
     * Update a group.
     *
     * @param  int    $groupID
     * @param  object $group
     * @access public
     * @return void
     */
    public function update(int $groupID, object $group): bool
    {
        $this->lang->error->unique = $this->lang->group->repeat;
        $this->dao->update(TABLE_GROUP)->data($group)
            ->check('name', 'unique', "id != {$groupID} AND vision = '{$this->config->vision}'")
            ->where('id')->eq($groupID)
            ->exec();

        return !dao::isError();
    }

    /**
     * Copy a group.
     *
     * @param  int    $groupID
     * @param  object $group
     * @param  array  $options
     * @access public
     * @return bool
     */
    public function copy(int $groupID, object $group, array $options): bool
    {
        $this->lang->error->unique = $this->lang->group->repeat;
        $this->dao->insert(TABLE_GROUP)->data($group)
            ->check('name', 'unique', "vision = '{$this->config->vision}'")
            ->exec();
        if(dao::isError()) return false;
        if(empty($options)) return true;

        $newGroupID = $this->dao->lastInsertID();
        if(in_array('copyPriv', $options)) $this->copyPriv($groupID, $newGroupID);
        if(in_array('copyUser', $options)) $this->copyUser($groupID, $newGroupID);
        return true;
    }

    /**
     * Copy privileges.
     *
     * @param  string    $fromGroup
     * @param  string    $toGroup
     * @access public
     * @return void
     */
    public function copyPriv($fromGroup, $toGroup)
    {
        $privs = $this->dao->findByGroup($fromGroup)->from(TABLE_GROUPPRIV)->fetchAll();
        foreach($privs as $key => $priv)
        {
            $privs[$key]->group = $toGroup;
        }
        $this->insertPrivs($privs);
    }

    /**
     * Copy user.
     *
     * @param  string    $fromGroup
     * @param  string    $toGroup
     * @access public
     * @return void
     */
    public function copyUser($fromGroup, $toGroup)
    {
        $users = $this->dao->findByGroup($fromGroup)->from(TABLE_USERGROUP)->fetchAll();
        foreach($users as $user)
        {
            $user->group = $toGroup;
            $this->dao->insert(TABLE_USERGROUP)->data($user)->exec();
        }
    }

    /**
     * Get group lists.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getList($projectID = 0)
    {
        return $this->dao->select('*')->from(TABLE_GROUP)
            ->where('project')->eq($projectID)
            ->beginIF($this->config->vision)->andWhere('vision')->eq($this->config->vision)->fi()
            ->orderBy('id')
            ->fetchAll();
    }

    /**
     * Get group pairs.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getPairs($projectID = 0)
    {
        return $this->dao->select('id, name')->from(TABLE_GROUP)
            ->where('project')->eq($projectID)
            ->andWhere('vision')->eq($this->config->vision)
            ->orderBy('id')->fetchPairs();
    }

    /**
     * Get group by id.
     *
     * @param  int    $groupID
     * @access public
     * @return object
     */
    public function getByID($groupID)
    {
        $group = $this->dao->findById($groupID)->from(TABLE_GROUP)->fetch();
        if($group->acl) $group->acl = json_decode($group->acl, true);
        if(!isset($group->acl) || !is_array($group->acl)) $group->acl = array();
        return $group;
    }

    /**
     * Get group by account.
     *
     * @param  string    $account
     * @param  bool      $allVision
     * @access public
     * @return array
     */
    public function getByAccount($account, $allVision = false)
    {
        return $this->dao->select('t2.*')->from(TABLE_USERGROUP)->alias('t1')
            ->leftJoin(TABLE_GROUP)->alias('t2')
            ->on('t1.`group` = t2.id')
            ->where('t1.account')->eq($account)
            ->andWhere('t2.project')->eq(0)
            ->beginIF(!$allVision)->andWhere('t2.vision')->eq($this->config->vision)->fi()
            ->fetchAll('id');
    }

    /**
     * Get groups by accounts.
     *
     * @param  array  $accounts
     * @access public
     * @return array
     */
    public function getByAccounts($accounts)
    {
        return $this->dao->select('t1.account, t2.acl, t2.id')->from(TABLE_USERGROUP)->alias('t1')
            ->leftJoin(TABLE_GROUP)->alias('t2')
            ->on('t1.`group` = t2.id')
            ->where('t1.account')->in($accounts)
            ->andWhere('t2.vision')->eq($this->config->vision)
            ->fetchGroup('account');
    }

    /**
     * Get the account number in the group.
     *
     * @param  array  $groupIdList
     * @access public
     * @return array
     */
    public function getGroupAccounts($groupIdList = array())
    {
        $groupIdList = array_filter($groupIdList);
        if(empty($groupIdList)) return array();
        return $this->dao->select('account')->from(TABLE_USERGROUP)->where('`group`')->in($groupIdList)->fetchPairs('account');
    }

    /**
     * Get privileges of a groups.
     *
     * @param  int    $groupID
     * @access public
     * @return array
     */
    public function getPrivs($groupID)
    {
        $privs = array();
        $stmt  = $this->dao->select('module, method')->from(TABLE_GROUPPRIV)->where('`group`')->eq($groupID)->orderBy('module')->query();
        while($priv = $stmt->fetch()) $privs[$priv->module][$priv->method] = $priv->method;
        return $privs;
    }

    /**
     * Get user pairs of a group.
     *
     * @param  int    $groupID
     * @access public
     * @return array
     */
    public function getUserPairs($groupID)
    {
        return $this->dao->select('t2.account, t2.realname')
            ->from(TABLE_USERGROUP)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account = t2.account')
            ->where('`group`')->eq((int)$groupID)
            ->beginIF($this->config->vision)->andWhere("CONCAT(',', visions, ',')")->like("%,{$this->config->vision},%")->fi()
            ->andWhere('t2.deleted')->eq(0)
            ->orderBy('t2.account')
            ->fetchPairs();
    }

    /**
     * 获取系统内权限分组的成员信息。
     * Get all group members.
     *
     * @access public
     * @return array
     */
    public function getAllGroupMembers(): array
    {
        /* Get normal group members. */
        $memberGroup = $this->dao->select('t1.`group` as `group`, t2.account, t2.realname')
            ->from(TABLE_USERGROUP)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account = t2.account')
            ->where('t2.deleted')->eq(0)
            ->beginIF($this->config->vision)->andWhere("CONCAT(',', visions, ',')")->like("%,{$this->config->vision},%")->fi()
            ->orderBy('t2.account')
            ->fetchAll();

        $groupMembers = array();
        foreach($memberGroup as $member)
        {
            if(!isset($groupMembers[$member->group])) $groupMembers[$member->group] = array();
            $groupMembers[$member->group][$member->account] = $member->realname;
        }

        /* Get project admin memebers. */
        $projectAdmins = $this->dao->select('t1.account, t2.realname')->from(TABLE_PROJECTADMIN)->alias('t1')->leftJoin(TABLE_USER)->alias('t2')->on('t1.account = t2.account')->fetchPairs();
        $adminGroupID  = $this->dao->select('id')->from(TABLE_GROUP)->where('role')->eq('projectAdmin')->fetch('id');
        $groupMembers[$adminGroupID] = $projectAdmins;

        return $groupMembers;
    }

    /**
     * Get object for manage admin group.
     *
     * @access public
     * @return void
     */
    public function getObject4AdminGroup()
    {
        $objects = $this->dao->select('id, name, path, type, project, grade, parent')->from(TABLE_PROJECT)
            ->where('vision')->eq($this->config->vision)
            ->andWhere('type')->ne('program')
            ->andWhere('deleted')->eq(0)
            ->fetchAll('id');

        $productList = $this->dao->select('id, name, program')->from(TABLE_PRODUCT)
            ->where('vision')->eq($this->config->vision)
            ->andWhere('deleted')->eq(0)
            ->andWhere('shadow')->eq(0)
            ->fetchAll('id');

        /* Get the list of program sets under administrator permission. */
        if(!$this->app->user->admin)
        {
            $this->app->user->admin = true;
            $changeAdmin            = true;
        }
        $programs = $this->loadModel('program')->getParentPairs('', '', false);
        if(!empty($changeAdmin)) $this->app->user->admin = false;

        $projects   = array();
        $executions = array();
        $products   = array();
        foreach($objects as $object)
        {
            $type  = $object->type;
            $path  = explode(',', trim($object->path, ','));
            $topID = $path[0];

            if($type == 'project')
            {
                if($topID != $object->id) $object->name = isset($objects[$topID]) ? $objects[$topID]->name . '/' . $object->name : $object->name;
                $projects[$object->id] = $object->name;
            }
            else
            {
                if($object->grade == 2)
                {
                    unset($objects[$object->parent]);
                    unset($executions[$object->parent]);
                }

                $object->name = isset($objects[$object->project]) ? $objects[$object->project]->name . '/' . $object->name : $object->name;
                $executions[$object->id] = $object->name;
            }
        }

        foreach($productList as $id => $product)
        {
            if(isset($programs[$product->program]) and $this->config->systemMode == 'ALM') $product->name = $programs[$product->program] . '/' . $product->name;
            $products[$product->id] = $product->name;
        }

        return array($programs, $projects, $products, $executions);
    }

    /**
     * Get project admins for manage project admin.
     *
     * @access public
     * @return array
     */
    public function getProjectAdmins()
    {
        $admins = $this->dao->select('*')->from(TABLE_PROJECTADMIN)->fetchGroup('group', 'account');

        $projectAdmins = array();
        foreach($admins as $adminGroup)
        {
            if(!empty($adminGroup))
            {
                $accounts = implode(',', array_keys($adminGroup));
                if(isset($projectAdmins[$accounts]))
                {
                    $adminGroup = current($adminGroup);
                    $projectAdmins[$accounts]->programs   .= ',' . $adminGroup->programs;
                    $projectAdmins[$accounts]->projects   .= ',' . $adminGroup->projects;
                    $projectAdmins[$accounts]->products   .= ',' . $adminGroup->products;
                    $projectAdmins[$accounts]->executions .= ',' . $adminGroup->executions;
                }
                else
                {
                    $projectAdmins[$accounts] = current($adminGroup);
                }
            }
        }

        return $projectAdmins;
    }

    /**
     * Get admins by object id list.
     *
     * @param  array  $idList
     * @param  string $field
     * @access public
     * @return array
     */
    public function getAdmins(array $idList, string $field = 'programs'): array
    {
        $objects = array();
        foreach($idList as $id)
        {
            $objects[$id] = $this->dao->select('DISTINCT account')->from(TABLE_PROJECTADMIN)
                ->where("CONCAT(',', $field, ',')")->like("%$id%")
                ->orWhere($field)->eq('all')
                ->fetchPairs();
        }

        return $objects;
    }

    /**
     * Get the ID of the group that has access to the program.
     *
     * @access public
     * @return array
     */
    public function getAccessProgramGroup()
    {
        $accessibleGroup   = $this->getList();
        $accessibleGroupID = array(0);
        foreach($accessibleGroup as $group)
        {
            if($group->acl) $group->acl = json_decode($group->acl, true);
            if(!isset($group->acl) || !is_array($group->acl)) $group->acl = array();

            if(empty($group->acl))
            {
                $accessibleGroupID[] = $group->id;
                continue;
            }

            if(!isset($group->acl['views']) || empty($group->acl['views']))
            {
                $accessibleGroupID[] = $group->id;
                continue;
            }

            if(in_array('program', $group->acl['views']))
            {
                $accessibleGroupID[] = $group->id;
                continue;
            }
        }
        return $accessibleGroupID;
    }

    /**
     * Delete a group.
     *
     * @param  int    $groupID
     * @param  null   $null      compatible with that of model::delete()
     * @access public
     * @return void
     */
    public function delete($groupID, $null = null)
    {
        $this->dao->delete()->from(TABLE_GROUP)->where('id')->eq($groupID)->exec();
        $this->dao->delete()->from(TABLE_USERGROUP)->where('`group`')->eq($groupID)->exec();
        $this->dao->delete()->from(TABLE_GROUPPRIV)->where('`group`')->eq($groupID)->exec();
    }

    /**
     * Update privilege of a group.
     *
     * @param  int    $groupID
     * @param  string $nav
     * @param  string $version
     * @access public
     * @return bool
     */
    public function updatePrivByGroup($groupID, $nav, $version)
    {
        /* Delete old. */
        $privs = array_keys($this->getPrivListByNav($nav, $version));

        $this->dao->delete()->from(TABLE_GROUPPRIV)
            ->where('`group`')->eq($groupID)
            ->beginIF(!empty($nav))->andWhere("CONCAT(module, '-', method)")->in($privs)->fi()
            ->exec();

        $data         = new stdclass();
        $data->group  = $groupID;
        $data->module = 'index';
        $data->method = 'index';
        $this->dao->replace(TABLE_GROUPPRIV)->data($data)->exec();

        $hasDepend = false;

        /* Insert new. */
        if($this->post->actions)
        {
            $dependPrivs = array();
            foreach($this->config->group->package as $packageCode => $packageData)
            {
                if(!isset($packageData->privs)) continue;
                foreach($packageData->privs as $privCode => $priv)
                {
                    if(isset($priv['depend'])) $dependPrivs[$privCode] = $priv['depend'];
                }
            }

            $insertPrivs = array();
            foreach($this->post->actions as $moduleName => $moduleActions)
            {
                if(empty($moduleName) or empty($moduleActions)) continue;

                foreach($moduleActions as $actionName)
                {
                    $data = new stdclass();
                    $data->group  = $groupID;
                    $data->module = $moduleName;
                    $data->method = $actionName;

                    $insertPrivs["{$moduleName}-{$actionName}"] = $data;
                }
            }

            foreach($insertPrivs as $key => $priv)
            {
                if(!isset($dependPrivs[$key])) continue;
                foreach($dependPrivs[$key] as $depend)
                {
                    if(isset($insertPrivs[$depend])) continue;

                    list($moduleName, $methodName) = explode('-', $depend);

                    $data = new stdclass();
                    $data->group  = $groupID;
                    $data->module = $moduleName;
                    $data->method = $methodName;

                    $insertPrivs[$depend] = $data;

                    $hasDepend = true;
                }
            }

            $this->insertPrivs($insertPrivs);
        }

        return $hasDepend;
    }

    /**
     * Insert privs.
     *
     * @param  array $privs
     * @access protected
     * @return bool
     */
    protected function insertPrivs($privs)
    {
        $groups = array();
        foreach($privs as $priv) $groups[$priv->group] = $priv->group;

        $privMap  = array();
        $privList = $this->dao->select('`group`,module,method')->from(TABLE_GROUPPRIV)->where('group')->in($groups)->fetchAll();
        foreach($privList as $priv) $privMap[$priv->group . '-' . $priv->module . '-' . $priv->method] = true;

        foreach($privs as $priv)
        {
            if(!isset($privMap[$priv->group . '-' . $priv->module . '-' . $priv->method]))
            {
                $this->dao->insert(TABLE_GROUPPRIV)->data($priv)->exec();
            }
        }

        return true;
    }

    /**
     * Update view priv.
     *
     * @param  int    $groupID
     * @access public
     * @return bool
     */
    public function updateView($groupID)
    {
        $actions  = $this->post->actions;
        $oldGroup = $this->getByID($groupID);
        $projects = isset($actions['projects']) ? $actions['projects'] : array();
        $sprints  = isset($actions['sprints'])  ? $actions['sprints']  : array();

        /* Add shadow productID when select noProduct project or execution. */
        if(($projects or $sprints) and isset($actions['products']))
        {
            /* Get all noProduct projects and executions . */
            $noProductList       = $this->loadModel('project')->getNoProductList();
            $shadowProductIDList = $this->dao->select('id')->from(TABLE_PRODUCT)->where('shadow')->eq(1)->fetchPairs();
            $noProductObjects    = array_merge($projects, $sprints);

            foreach($noProductObjects as $objectID)
            {
                if(isset($noProductList[$objectID])) $actions['products'][] = $noProductList[$objectID]->product;
            }
        }

        $actions['views'] = empty($actions['views']) ? array() : array_keys($actions['views']);
        $actions['views'] = array_combine($actions['views'], $actions['views']);
        if(isset($_POST['actionallchecker'])) $actions['views']   = array();
        if(!isset($actions['actions']))       $actions['actions'] = array();

        if(isset($actions['actions']['project']['started']))   $actions['actions']['project']['syncproject']     = 'syncproject';
        if(isset($actions['actions']['execution']['started'])) $actions['actions']['execution']['syncexecution'] = 'syncexecution';

        $dynamic = $actions['actions'];
        if(!isset($_POST['actionallchecker']))
        {
            $dynamic = array();
            foreach($actions['actions'] as $moduleName => $moduleActions)
            {
                $groupName = $moduleName;
                if(isset($this->lang->navGroup->$moduleName)) $groupName = $this->lang->navGroup->$moduleName;
                if($moduleName == 'case') $groupName = $this->lang->navGroup->testcase;
                if($groupName != 'my' and !isset($actions['views'][$groupName])) continue;

                $moduleActions = array_keys($moduleActions);
                $dynamic[$moduleName] = array_combine($moduleActions, $moduleActions);
            }
        }
        $actions['actions'] = $dynamic;

        $actions = empty($actions) ? '' : json_encode($actions);
        $this->dao->update(TABLE_GROUP)->set('acl')->eq($actions)->where('id')->eq($groupID)->exec();
        return dao::isError() ? false : true;
    }

    /**
     * Update privilege by module.
     *
     * @access public
     * @return void
     */
    public function updatePrivByModule()
    {
        if($this->post->module == false or $this->post->actions == false or $this->post->groups == false) return false;

        $privs = array();
        foreach($this->post->actions as $action)
        {
            list($module, $method) = explode('-', $action);
            foreach($this->post->groups as $group)
            {
                $data         = new stdclass();
                $data->group  = $group;
                $data->module = $module;
                $data->method = $method;
                $privs[]      = $data;
            }
        }

        return $this->insertPrivs($privs);
    }

    /**
     * Update users.
     *
     * @param  int    $groupID
     * @access public
     * @return void
     */
    public function updateUser($groupID)
    {
        $members    = $this->post->members ? $this->post->members : array();
        $groupUsers = $this->dao->select('account')->from(TABLE_USERGROUP)->where('`group`')->eq($groupID)->fetchPairs('account');
        $newUsers   = array_diff($members, $groupUsers);
        $delUsers   = array_diff($groupUsers, $members);

        $this->dao->delete()->from(TABLE_USERGROUP)->where('`group`')->eq($groupID)->andWhere('account')->in($delUsers)->exec();

        if($newUsers)
        {
            foreach($newUsers as $account)
            {
                $data          = new stdclass();
                $data->account = $account;
                $data->group   = $groupID;
                $data->project = '';
                $this->dao->insert(TABLE_USERGROUP)->data($data)->exec();
            }
        }

        /* Update whitelist. */
        $acl = $this->dao->select('acl')->from(TABLE_GROUP)->where('id')->eq($groupID)->fetch('acl');
        $acl = json_decode($acl);

        /* Adjust user view. */
        $changedUsers = array_merge($newUsers, $delUsers);
        if(!empty($changedUsers))
        {
            $this->loadModel('user');
            foreach($changedUsers as $account) $this->user->computeUserView($account, true);
        }
    }

    /**
     * Update project admins.
     *
     * @param  int    $groupID
     * @access public
     * @return void
     */
    public function updateProjectAdmin($groupID)
    {
        $this->loadModel('user');

        $allUsers = $this->dao->select('account')->from(TABLE_PROJECTADMIN)->fetchPairs();
        $this->dao->delete()->from(TABLE_PROJECTADMIN)->exec();

        $members       = $this->post->members      ? $this->post->members      : array();
        $programs      = $this->post->program      ? $this->post->program      : array();
        $projects      = $this->post->project      ? $this->post->project      : array();
        $products      = $this->post->product      ? $this->post->product      : array();
        $executions    = $this->post->execution    ? $this->post->execution    : array();
        $programAll    = $this->post->programAll   ? $this->post->programAll   : '';
        $projectAll    = $this->post->projectAll   ? $this->post->projectAll   : '';
        $productAll    = $this->post->productAll   ? $this->post->productAll   : '';
        $executionAll  = $this->post->executionAll ? $this->post->executionAll : '';
        $noProductList = $this->loadModel('project')->getNoProductList();
        $shadowProductIDList = $this->dao->select('id')->from(TABLE_PRODUCT)->where('shadow')->eq(1)->fetchPairs();

        foreach($members as $lineID => $accounts)
        {
            $programs[$lineID]   = isset($programs[$lineID])   ? $programs[$lineID]   : array();
            $projects[$lineID]   = isset($projects[$lineID])   ? $projects[$lineID]   : array();
            $products[$lineID]   = isset($products[$lineID])   ? $products[$lineID]   : array();
            $executions[$lineID] = isset($executions[$lineID]) ? $executions[$lineID] : array();

            if(($projects[$lineID] or $executions[$lineID]) and !empty($products[$lineID]))
            {
                $objects = array_merge($projects[$lineID], $executions[$lineID]);
                foreach($objects as $objectID)
                {
                    if(isset($noProductList[$objectID])) $products[$lineID][] = $noProductList[$objectID]->product;
                }
            }

            if(isset($executionAll[$lineID]) or isset($projectAll[$lineID])) $products[$lineID] = array_merge($products[$lineID], $shadowProductIDList);

            if(empty($accounts)) continue;
            foreach($accounts as $account)
            {
                $program   = isset($programAll[$lineID])   ? 'all' : implode(',', $programs[$lineID]);
                $project   = isset($projectAll[$lineID])   ? 'all' : implode(',', $projects[$lineID]);
                $product   = isset($productAll[$lineID])   ? 'all' : implode(',', $products[$lineID]);
                $execution = isset($executionAll[$lineID]) ? 'all' : implode(',', $executions[$lineID]);

                $data = new stdclass();
                $data->group      = $lineID;
                $data->account    = $account;
                $data->programs   = $program;
                $data->projects   = $project;
                $data->products   = $product;
                $data->executions = $execution;

                $this->dao->replace(TABLE_PROJECTADMIN)->data($data)->exec();

                $allUsers[$account] = $account;
            }
        }

        foreach($allUsers as $account)
        {
            if(!$account) continue;
            $this->user->computeUserView($account, true);
        }

        if(!dao::isError()) return true;
        return false;
    }

    /**
     * Sort resource.
     *
     * @access public
     * @return void
     */
    public function sortResource()
    {
        $resources = $this->lang->resource;
        $this->lang->resource = new stdclass();

        /* sort moduleOrder. */
        ksort($this->lang->moduleOrder, SORT_ASC);
        foreach($this->lang->moduleOrder as $moduleName)
        {
            if(!isset($resources->$moduleName)) continue;

            $resource = $resources->$moduleName;
            unset($resources->$moduleName);
            $this->lang->resource->$moduleName = $resource;
        }
        foreach($resources as $key => $resource)
        {
            $this->lang->resource->$key = $resource;
        }

        /* sort methodOrder. */
        foreach($this->lang->resource as $moduleName => $resources)
        {
            $resources    = (array)$resources;
            $tmpResources = new stdclass();

            if(isset($this->lang->$moduleName->methodOrder))
            {
                ksort($this->lang->$moduleName->methodOrder, SORT_ASC);
                foreach($this->lang->$moduleName->methodOrder as $key)
                {
                    if(isset($resources[$key]))
                    {
                        $tmpResources->$key = $resources[$key];
                        unset($resources[$key]);
                    }
                }
                if($resources)
                {
                    foreach($resources as $key => $resource)
                    {
                        $tmpResources->$key = $resource;
                    }
                }
                $this->lang->resource->$moduleName = $tmpResources;
                unset($tmpResources);
            }
        }
    }

    /**
     * Check nav have subset.
     *
     * @param  string $nav
     * @param  string $subset
     * @access public
     * @return bool
     */
    public function checkNavSubset($nav, $subset)
    {
        if(empty($nav)) return true;

        if($nav == 'general') return !isset($this->config->group->subset->$subset) || !isset($this->config->group->subset->$subset->nav) || $this->config->group->subset->$subset->nav == 'general';

        return isset($this->config->group->subset->$subset)
            && isset($this->config->group->subset->$subset->nav)
            && $this->config->group->subset->$subset->nav == $nav;
    }

    /**
     * Check nav have module.
     *
     * @param  string $nav
     * @param  string $moduleName
     * @access public
     * @return bool
     */
    public function checkNavModule($nav, $moduleName, $methodName = '')
    {
        if(empty($nav)) return true;

        if($nav == 'general' and (isset($this->lang->navGroup->$moduleName) or isset($this->lang->mainNav->$moduleName))) return false;
        if($nav != 'general')
        {
            if($moduleName === $nav) return true;
            if(isset($this->lang->navGroup->{$moduleName . '_' . $methodName}))
            {
                if($this->lang->navGroup->{$moduleName . '_' . $methodName} === $nav) return true;
                return false;
            }
            if(isset($this->lang->navGroup->$moduleName) and $this->lang->navGroup->$moduleName == $nav) return true;
            return false;
        }
        if($nav == 'project' and strpos('caselib|testsuite|report', $moduleName) !== false) return false;

        return true;
    }

    /**
     * Get modules in menu
     *
     * @param  string  $menu
     * @param  bool    $translateLang
     * @access public
     * @return array
     */
    public function getMenuModules($menu = '', $translateLang = false)
    {
        $modules = array();
        foreach($this->lang->resource as $moduleName => $action)
        {
            if($this->checkNavModule($menu, $moduleName))
            {
                $modules[$moduleName] = $moduleName;
                if($translateLang)
                {
                    if(!isset($this->lang->{$moduleName}->common)) $this->app->loadLang($moduleName);
                    $modules[$moduleName] = isset($this->lang->{$moduleName}->common) ? $this->lang->{$moduleName}->common : $moduleName;
                    if($moduleName == 'requirement') $modules[$moduleName] = $this->lang->URCommon;
                }
            }
        }
        return $modules;
    }

    /**
     * Judge an action is clickable or not.
     *
     * @param  object $group
     * @param  string $action
     * @static
     * @access public
     * @return bool
     */
    public static function isClickable($group, $action)
    {
        $action = strtolower($action);

        if($action == 'manageview' && $group->role == 'limited') return false;
        if($action == 'manageprojectadmin' && $group->role != 'projectAdmin') return false;
        if($action == 'copy' && $group->role == 'limited') return false;

        return true;
    }

    /**
     * Create a privilege package.
     *
     * @access public
     * @return int
     */
    public function createPrivPackage()
    {
        $package = fixer::input('post')->get();

        $packages = $this->getPrivPackagesByModule($package->module);
        $package->order = (count($packages) + 1) * 5;

        $this->dao->insert(TABLE_PRIVPACKAGE)->data($package)->batchCheck($this->config->privPackage->create->requiredFields, 'notempty')->exec();
        $packageID = $this->dao->lastInsertId();
        $this->loadModel('action')->create('privpackage', $packageID, 'Opened');
        return $packageID;
    }

    /**
     * Update a privilege package.
     *
     * @param  int    $packageID
     * @access public
     * @return array
     */
    public function updatePrivPackage($packageID)
    {
        $oldPackage = $this->getPrivPackageByID($packageID);

        $package = fixer::input('post')->get();
        if($oldPackage->module != $package->module)
        {
            $packages = $this->getPrivPackagesByModule($package->module);
            $package->order = (count($packages) + 1) * 5;

            $priv = new stdclass();
            $priv->module = $package->module;
        }
        $this->dao->update(TABLE_PRIVPACKAGE)->data($package)->batchCheck($this->config->privPackage->edit->requiredFields, 'notempty')->where('id')->eq($packageID)->exec();
        if(dao::isError()) return false;
        if(isset($priv)) $this->dao->update(TABLE_PRIV)->data($priv)->where('package')->eq($packageID)->exec();

        $package = $this->getPrivPackageByID($packageID);
        $changes = common::createChanges($oldPackage, $package);

        return $changes;
    }

    /**
     * Delete a priv package.
     *
     * @param  int    $packageID
     * @access public
     * @return bool
     */
    public function deletePrivPackage($packageID)
    {
        $this->dao->delete()->from(TABLE_PRIVPACKAGE)->where('id')->eq($packageID)->exec();
        if(dao::isError()) return false;
        $this->dao->update(TABLE_PRIV)->set('package')->eq(0)->where('package')->eq($packageID)->exec();
    }

    /**
     * Get priv package by id.
     *
     * @param  int    $packageID
     * @access public
     * @return object
     */
    public function getPrivPackageByID($packageID)
    {
        return $this->dao->select('distinct t1.*,t2.`value` as name')->from(TABLE_PRIVMANAGER)->alias('t1')
            ->leftJoin(TABLE_PRIVLANG)->alias('t2')->on('t1.id=t2.objectID')
            ->where('t1.id')->eq($packageID)
            ->andWhere('t1.edition')->like("%,{$this->config->edition},%")
            ->andWhere('t1.vision')->like("%,{$this->config->vision},%")
            ->andWhere('t2.objectType')->eq('manager')
            ->fetch();
    }

    /**
     * Get priv packages by module.
     *
     * @param  string $module
     * @access public
     * @return array
     */
    public function getPrivPackagesByModule($module)
    {
        $moduleID = $this->dao->select('id')->from(TABLE_PRIVMANAGER)->where('`code`')->eq($module)->andWhere('type')->eq('module')->fetch('id');

        return $this->dao->select('t1.*,t2.value as name')->from(TABLE_PRIVMANAGER)->alias('t1')
            ->leftJoin(TABLE_PRIVLANG)->alias('t2')->on('t1.id=t2.objectID')
            ->where('t1.parent')->eq($moduleID)
            ->andWhere('t1.type')->eq('package')
            ->andWhere('t1.edition')->like("%,{$this->config->edition},%")
            ->andWhere('t1.vision')->like("%,{$this->config->vision},%")
            ->orderBy('order_asc')
            ->fetchAll('id');
    }

//    /**
//     * Get priv packages group by module.
//     *
//     * @param  array  $modules
//     * @access public
//     * @return array
//     */
//    public function getPrivPackageGroupByModules($modules = array())
//    {
//        return $this->dao->select('t1.*,t2.`code` as parentCode')->from(TABLE_PRIVMANAGER)->alias('t1')
//            ->leftJoin(TABLE_PRIVMANAGER)->alias('t2')->on('t1.parent=t2.id')
//            ->where('t1.`type`')->eq('package')
//            ->beginIF(!empty($modules))->andWhere('t2.`code`')->in($modules)->fi()
//            ->andWhere('t2.`type`')->eq('module')
//            ->andWhere('t1.edition')->like("%,{$this->config->edition},%")
//            ->andWhere('t1.vision')->like("%,{$this->config->vision},%")
//            ->fetchGroup('parentCode', 'id');
//    }

    /**
     * Get priv package pairs by view.
     *
     * @access public
     * @return array
     */
    public function getPrivPackagePairs($view = '', $module = '', $field = '`value` as name')
    {
        $modules = '';
        if(!empty($module))
        {
            $modules = $this->dao->select('id')->from(TABLE_PRIVMANAGER)->where('type')->eq('module')->andWhere('code')->eq($module)->fetch('id');
        }
        else
        {
            $modules = $this->getPrivManagerPairs('module', $view);
            $modules = array_keys($modules);
            $modules = implode(',', $modules);
        }
        $modules = trim($modules, ',');

        return $this->dao->select("id,{$field}")
            ->from(TABLE_PRIVMANAGER)->alias('t1')
            ->leftJoin(TABLE_PRIVLANG)->alias('t2')->on('t1.id=t2.objectID')
            ->where('1=1')
            ->andWhere('t2.objectType')->eq('manager')
            ->beginIF(!empty($modules))->andWhere('parent')->in($modules)->fi()
            ->andWhere('t1.edition')->like("%,{$this->config->edition},%")
            ->andWhere('t1.vision')->like("%,{$this->config->vision},%")
            ->andWhere('t2.lang')->eq($this->app->getClientLang())
            ->orderBy('order_asc')
            ->fetchPairs();
    }

    /**
     * 获取权限包id:name的键值对。
     * Get priv package pairs.
     *
     * @param  array  $packageIdList
     * @access public
     * @return array
     */
    public function getPackagePairs(array $packageIdList): array
    {
        return $this->dao->select('*')->from(TABLE_PRIVPACKAGE)->where('id')->in($packageIdList)->orderBy('`order`')->fetchPairs('id', 'name');
    }

    /**
     * Get priv modules.
     *
     * @param  string $viewName
     * @param  string $param
     * @access public
     * @return array
     */
    public function getPrivModules($viewName = '', $param = '')
    {
        $this->loadModel('setting');

        $tree       = array();
        $views      = empty($viewName) ? $this->setting->getItem("owner=system&module=priv&key=views") : $viewName;
        $views      = explode(',', $views);
        $modules    = array();
        $moduleLang = $this->getMenuModules('', true);
        foreach($views as $view)
        {
            $viewModules = $this->setting->getItem("owner=system&module=priv&key={$view}Modules");
            if(empty($viewModules)) continue;

            $viewModules = explode(',', $viewModules);
            foreach($viewModules as $index => $module)
            {
                $modules[$module] = $param == 'noViewName' ? zget($moduleLang, $module) : $this->lang->{$view}->common . '/' . zget($moduleLang, $module);
                unset($viewModules[$index]);
            }
        }

        return $modules;
    }

    /**
     * Get priv module view pairs.
     *
     * @access public
     * @return void
     */
    public function getPrivModuleViewPairs()
    {
        $this->loadModel('setting');

        $views = $this->setting->getItem("owner=system&module=priv&key=views");
        if(empty($views)) return array();
        $views = explode(',', $views);

        $pairs = array();
        foreach($views as $viewIndex => $view)
        {
            $viewModules = $this->setting->getItem("owner=system&module=priv&key={$view}Modules");
            $viewModules = explode(',', $viewModules);

            foreach($viewModules as $module) $pairs[$module] = $view;
        }

        return $pairs;
    }

    /**
     * Get priv package tree list.
     *
     * @access public
     * @return void
     */
    public function getPrivPackageTreeList()
    {
        $this->loadModel('setting');

        $views = empty($viewName) ? $this->setting->getItem("owner=system&module=priv&key=views") : $viewName;
        if(empty($views)) return array();
        $views = explode(',', $views);

        $modules    = array();
        $moduleLang = $this->getMenuModules('', true);
        foreach($views as $viewIndex => $view)
        {
            $viewID           = $view . 'View';
            $treeView         = new stdclass();
            $treeView->id     = $viewID;
            $treeView->type   = 'view';
            $treeView->name   = $this->lang->{$view}->common;
            $treeView->parent = 0;
            $treeView->path   = ",{$viewID},";
            $treeView->grade  = 1;
            $treeView->order  = $viewIndex;
            $treeView->desc   = '';
            $tree[$viewID]    = $treeView;

            $viewModules = $this->setting->getItem("owner=system&module=priv&key={$view}Modules");
            if(empty($viewModules)) continue;

            $viewModules = explode(',', $viewModules);
            foreach($viewModules as $moduleIndex => $module)
            {
                $treeModule         = new stdclass();
                $treeModule->id     = $module;
                $treeModule->type   = 'module';
                $treeModule->name   = zget($moduleLang, $module);
                $treeModule->parent = $viewID;
                $treeModule->path   = ",{$viewID},{$module},";
                $treeModule->grade  = 2;
                $treeModule->order  = $moduleIndex;
                $treeModule->desc   = '';
                $tree[$module]      = $treeModule;

                $packages = $this->getPrivPackagesByModule($module);
                foreach($packages as $packageID => $package)
                {
                    $treePackage = new stdclass();
                    $treePackage->id     = $packageID;
                    $treePackage->type   = 'package';
                    $treePackage->name   = $package->name;
                    $treePackage->parent = $module;
                    $treePackage->path   = ",{$viewID},{$module},{$packageID},";
                    $treePackage->grade  = 3;
                    $treePackage->desc   = $package->desc;
                    $treePackage->order  = $package->order;
                    $tree[$packageID]    = $treePackage;
                }
            }
        }

        return $tree;
    }

    /**
     * Super Model: Init Privs.
     *
     * @param  bool   $onlyUpdateModule
     * @access public
     * @return void
     */
    public function initPrivs($onlyUpdateModule = true)
    {
        $this->sortResource();
        $resource = json_decode(json_encode($this->lang->resource), true);
        if(!$onlyUpdateModule)
        {
            $this->dao->delete()->from(TABLE_PRIVLANG)->exec();
            $this->dao->delete()->from(TABLE_PRIVRELATION)->exec();
            $this->dao->delete()->from(TABLE_PRIV)->exec();
            $this->dao->delete()->from(TABLE_CONFIG)->where('module')->eq('priv')->exec();
            $this->dbh->exec('ALTER TABLE ' . TABLE_PRIV . ' auto_increment = 1');
        }

        $viewModules = array();
        $this->loadModel('setting');
        foreach($resource as $moduleName => $methods)
        {
            $groupKey = $moduleName;
            $view     = isset($this->lang->navGroup->{$groupKey}) ? $this->lang->navGroup->{$groupKey} : $moduleName;
            $viewModules[$view][] = $moduleName;

            if($onlyUpdateModule) continue;
            $order = 1;
            foreach($methods as $methodName => $methodLang)
            {
                $priv = new stdclass();
                $priv->moduleName = $moduleName;
                $priv->methodName = $methodName;
                $priv->module     = $moduleName;
                $priv->package    = 0;
                $priv->system     = 1;
                $priv->order      = $order * 5;
                $order ++;

                $this->dao->replace(TABLE_PRIV)->data($priv)->exec();
                if(!dao::isError())
                {
                    $privID = $this->dao->lastInsertId();

                    $this->app->loadLang($moduleName);
                    foreach($this->config->langs as $lang => $langValue)
                    {
                        if($lang != 'zh-cn') continue;
                        $privLang = new stdclass();
                        $privLang->priv = $privID;
                        $privLang->lang = $lang;
                        $privLang->name = isset($this->lang->{$moduleName}->{$methodLang}) ? $this->lang->{$moduleName}->{$methodLang} : "{$moduleName}-{$methodLang}";
                        $privLang->desc = '';
                        $this->dao->replace(TABLE_PRIVLANG)->data($privLang)->exec();
                    }
                }
            }
        }

        foreach($viewModules as $viewName => $modules)
        {
            $modules = implode(',', $viewModules[$viewName]);
            $this->setting->setItem("system.priv.{$viewName}Modules", $modules);
        }

        $views = array_keys($viewModules);
        $this->setting->setItem("system.priv.views", implode(',', $views));

        if(!dao::isError()) return true;
    }

    /**
     * Super Model: Init Data for priv package.
     *
     * @access public
     * @return void
     */
    public function initData()
    {
        $allResourceFile = $this->app->getModuleRoot() . 'group/lang/allresources.php';

        $views   = $this->loadModel('setting')->getItem("owner=system&module=priv&key=views");
        $views   = array_filter(explode(',', $views));
        $views[] = 'general';

        $this->dbh->exec("ALTER TABLE " . TABLE_PRIVLANG . " CHANGE `priv` `objectID` mediumint(8) unsigned NOT NULL;");
        $this->dbh->exec("ALTER TABLE " . TABLE_PRIVLANG . " ADD `objectType` enum('priv','manager') NOT NULL DEFAULT 'priv' AFTER `objectID`;");
        $this->dbh->exec("ALTER TABLE " . TABLE_PRIVLANG . " ADD `key` varchar(100) NOT NULL AFTER `lang`;");
        $this->dbh->exec("ALTER TABLE " . TABLE_PRIVLANG . " CHANGE `name` `value` varchar(255) NOT NULL;");
        $this->dbh->exec("ALTER TABLE " . TABLE_PRIVLANG . " ADD UNIQUE KEY `objectlang` (`objectID`,`objectType`,`lang`);");
        $this->dbh->exec("ALTER TABLE " . TABLE_PRIVLANG . " DROP INDEX `privlang`;");
        $this->dbh->exec("DROP TABLE IF EXISTS " . TABLE_PRIVMANAGER . ";");
        $this->dbh->exec("CREATE TABLE IF NOT EXISTS " . TABLE_PRIVMANAGER . " ( `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT, `parent` varchar(30) NOT NULL, `code` varchar(100) NOT NULL, `type` enum('view','module','package') NOT NULL DEFAULT 'package', `order` tinyint(3) unsigned NOT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        $this->dbh->exec("ALTER TABLE " . TABLE_PRIV . " ADD `edition` varchar(30) NOT NULL DEFAULT ',open,biz,max,' AFTER `package`;");
        $this->dbh->exec("ALTER TABLE " . TABLE_PRIV . " ADD `vision` varchar(30) NOT NULL DEFAULT ',rnd,' AFTER `edition`;");
        $this->dbh->exec("ALTER TABLE " . TABLE_PRIVMANAGER . " ADD `edition` varchar(30) NOT NULL DEFAULT ',open,biz,max,' AFTER `type`;");
        $this->dbh->exec("ALTER TABLE " . TABLE_PRIVMANAGER . " ADD `vision` varchar(30) NOT NULL DEFAULT ',rnd,' AFTER `edition`;");

        $this->dbh->exec('ALTER TABLE ' . TABLE_PRIV . ' CHANGE `order` `order` mediumint(8) NOT NULL;');
        $this->dbh->exec('ALTER TABLE ' . TABLE_PRIVMANAGER . ' CHANGE `order` `order` mediumint(8) NOT NULL;');

        $this->dbh->exec("ALTER TABLE " . TABLE_PRIV . " CHANGE `package` `package` varchar(100) NOT NULL;");
        $this->dbh->exec("UPDATE " . TABLE_PRIV . " SET `package`=`module` WHERE `package`=0;");
        $this->dbh->exec("ALTER TABLE " . TABLE_PRIV . " CHANGE `package` `parent` varchar(100) NOT NULL;");

        $this->loadModel('dev');

        /* 插入权限所有语言项 */
        $storedPrivs = array();
        $privList    = $this->dao->select('*')->from(TABLE_PRIV)->fetchAll('id');
        foreach($privList as $privID => $priv)
        {
            foreach($this->config->langs as $lang => $langValue)
            {
                $methodLang = isset($this->lang->resource->{$priv->moduleName}->{$priv->methodName}) ? $this->lang->resource->{$priv->moduleName}->{$priv->methodName} : $priv->methodName;

                $privLang = new stdclass();
                $privLang->objectID   = $privID;
                $privLang->objectType = 'priv';
                $privLang->lang       = $lang;
                $privLang->key        = "{$priv->moduleName}-{$methodLang}";
                $privLang->value      = '';
                $privLang->desc       = '';
                $this->dao->replace(TABLE_PRIVLANG)->data($privLang)->exec();
            }

            $storedPrivs["{$priv->moduleName}-{$priv->methodName}"] = $priv;
        }

        $originResource = json_decode(json_encode($this->lang->resource), true);
        $originPrivs    = array();
        foreach($originResource as $moduleName => $methods)
        {
            foreach($methods as $methodName => $methodLang)
            {
                $originPrivs["$moduleName-$methodName"] = "$moduleName-$methodName";
            }
        }

        /* 删掉语言项中不存在的权限 */
        foreach($storedPrivs as $moduleMethod => $storedPriv)
        {
            if(!empty($originPrivs[$moduleMethod])) continue;
            $this->dao->delete()->from(TABLE_PRIV)->where('id')->eq($storedPriv->id)->exec();
            $this->dao->delete()->from(TABLE_PRIVLANG)->where('objectType')->eq('priv')->andWhere('objectID')->eq($storedPriv->id)->exec();
        }

        /* 迁移权限包数据   privpackage => privmanager */
        /* 迁移权限包语言项 privpackage => privlang */
        $packageList = $this->dao->select('*')->from(TABLE_PRIVPACKAGE)->fetchAll('id');
        foreach($packageList as $packageID => $package)
        {
            $packageData = new stdclass();
            $packageData->id     = $packageID;
            $packageData->parent = $package->module;
            $packageData->type   = 'package';
            $packageData->order  = $package->order;
            $packageData->vision = ',rnd,lite,';

            $this->dao->insert(TABLE_PRIVMANAGER)->data($packageData)->exec();

            $packageLang = new stdclass();
            $packageLang->objectID   = $packageID;
            $packageLang->objectType = 'manager';
            $packageLang->lang       = 'zh-cn';
            $packageLang->key        = '';
            $packageLang->value      = $package->name;
            $packageLang->desc       = $package->desc;

            $this->dao->insert(TABLE_PRIVLANG)->data($packageLang)->exec();
        }

        /* 初始化视图和模块到zt_privmanager */
        $viewPairs   = array();
        $modulePairs = array();
        $indexMenu   = array();
        $hasStored   = false;
        $this->app->loadLang('index');
        $this->lang->mainNav = json_decode(json_encode($this->lang->mainNav), true);

        $indexMenu['index']  = "{$this->lang->navIcons['my']} {$this->lang->index->common}|index|index|";
        $this->lang->mainNav = (object)array_merge($indexMenu, $this->lang->mainNav);
        foreach($this->config->langs as $lang => $langValue)
        {
            /* 视图 */
            $viewOrder   = 1;
            $moduleOrder = 1;
            $this->lang->mainNav->general = "{$this->lang->navIcons['my']} {$this->lang->my->shortCommon}|my|index|";
            foreach($views as $moduleMenu)
            {
                $viewID = 0;
                if($moduleMenu != 'general' and isset($this->lang->mainNav->{$moduleMenu}))
                {
                    if(!$hasStored)
                    {
                        $viewData = new stdclass();
                        $viewData->parent = '';
                        $viewData->code   = $moduleMenu;
                        $viewData->type   = 'view';
                        $viewData->order  = $viewOrder * 10;
                        $viewOrder ++;

                        $this->dao->insert(TABLE_PRIVMANAGER)->data($viewData)->exec();
                        $viewID = $this->dao->lastInsertId();
                    }
                    else
                    {
                        $viewID = $this->dao->select('id')->from(TABLE_PRIVMANAGER)
                            ->where('`type`')->eq('view')
                            ->andWhere('code')->eq($moduleMenu)
                            ->fetch('id');
                    }

                    $viewLang = new stdclass();
                    $viewLang->objectID   = $viewID;
                    $viewLang->objectType = 'manager';
                    $viewLang->lang       = $lang;
                    $viewLang->key        = $moduleMenu;
                    $viewLang->value      = '';
                    $viewLang->desc       = '';

                    $this->dao->insert(TABLE_PRIVLANG)->data($viewLang)->exec();
                    $viewPairs[$moduleMenu] = $viewID;
                }

                /* 模块 */
                $modules     = $this->getMenuModules($moduleMenu);
                $viewModules = $this->setting->getItem("owner=system&module=priv&key={$moduleMenu}Modules");
                $viewModules = array_filter(explode(',', $viewModules));
                foreach($viewModules as $moduleName)
                {
                    if(!$hasStored)
                    {
                        $moduleData = new stdclass();
                        $moduleData->parent = (isset($this->lang->navGroup->{$moduleName}) and !in_array($moduleMenu, array('misc', 'conference'))) ? $viewPairs[$moduleMenu] : 0;
                        $moduleData->code   = $moduleName;
                        $moduleData->type   = 'module';
                        $moduleData->order  = $moduleOrder * 10;
                        $moduleOrder ++;

                        $this->dao->insert(TABLE_PRIVMANAGER)->data($moduleData)->exec();
                        $moduleID = $this->dao->lastInsertId();
                    }
                    else
                    {
                        $moduleID = $this->dao->select('id')->from(TABLE_PRIVMANAGER)
                            ->where('`type`')->eq('module')
                            ->andWhere('code')->eq($moduleName)
                            ->fetch('id');
                    }

                    $moduleLang = new stdclass();
                    $moduleLang->objectID   = $moduleID;
                    $moduleLang->objectType = 'manager';
                    $moduleLang->lang       = $lang;
                    $moduleLang->key        = $moduleName;
                    $moduleLang->value      = '';
                    $moduleLang->desc       = '';

                    $this->dao->insert(TABLE_PRIVLANG)->data($moduleLang)->exec();
                    $modulePairs[$moduleName] = $moduleID;

                    /* 更新权限包的parent字段 */
                    $this->dao->update(TABLE_PRIVMANAGER)->set('`parent`')->eq($moduleID)
                        ->where('`parent`')->eq($moduleName)
                        ->andWhere('`type`')->eq('package')
                        ->exec();

                    /* 更新权限的parent字段 */
                    $this->dao->update(TABLE_PRIV)->set('`parent`')->eq($moduleID)
                        ->where('`parent`')->eq($moduleName)
                        ->exec();
                }
            }

            $hasStored = true;
        }

        /* 初始化 view 的edition和vision字段 */
        include $allResourceFile;
        $editionMap = array('open' => ',open,biz,max,', 'biz' => ',biz,max,', 'max' => ',max,');
        foreach($views as $edition => $visions)
        {
            foreach($visions as $vision => $viewList)
            {
                $vision = $vision == 'lite' ? ',rnd,lite,' : ',rnd,';
                $viewList = unserialize($viewList);
                foreach($viewList as $view)
                {
                    if($view == 'menuOrder') continue;

                    $this->dao->update(TABLE_PRIVMANAGER)
                        ->set('edition')->eq("{$editionMap[$edition]}")
                        ->set('vision')->eq("$vision")
                        ->where('id')->eq($viewPairs[$view])
                        ->exec();
                }
            }
        }

        /* 初始化 module 和 priv 的edition和vision字段 */
        foreach($resources as $edition => $visions)
        {
            foreach($visions as $vision => $modules)
            {
                $vision = $vision == 'lite' ? ',rnd,lite,' : ',rnd,';
                $modules = unserialize($modules);
                foreach($modules as $module => $resourceList)
                {
                    if(!empty($modulePairs[$module]))
                    {
                        $this->dao->update(TABLE_PRIVMANAGER)
                            ->set('edition')->eq("{$editionMap[$edition]}")
                            ->set('vision')->eq("$vision")
                            ->where('id')->eq($modulePairs[$module])
                            ->exec();
                    }

                    foreach($resourceList as $method => $methodLang)
                    {
                        $this->dao->update(TABLE_PRIV)
                            ->set('edition')->eq("{$editionMap[$edition]}")
                            ->set('vision')->eq("$vision")
                            ->where('moduleName')->eq($module)
                            ->andWhere('methodName')->eq($method)
                            ->exec();
                    }
                }
            }
        }

        $this->dbh->exec('ALTER TABLE ' . TABLE_PRIV . ' DROP `module`;');
        $this->dbh->exec('ALTER TABLE ' . TABLE_PRIV . ' CHANGE `moduleName` `module` varchar(30) NOT NULL;');
        $this->dbh->exec('ALTER TABLE ' . TABLE_PRIV . ' CHANGE `methodName` `method` varchar(30) NOT NULL;');
        $this->dbh->exec('ALTER TABLE ' . TABLE_PRIV . ' DROP INDEX `priv`;');
        $this->dbh->exec('ALTER TABLE ' . TABLE_PRIV . ' ADD UNIQUE `priv` (`module`,`method`);');
        $this->dbh->exec("ALTER TABLE " . TABLE_PRIV . " CHANGE `parent` `parent` mediumint(8) unsigned NOT NULL;");
        $this->dbh->exec('ALTER TABLE ' . TABLE_PRIVMANAGER . ' CHANGE `parent` `parent` mediumint(8) unsigned NOT NULL;');
        $this->dbh->exec('DROP TABLE IF EXISTS ' . TABLE_PRIVPACKAGE . ';');

        echo 'success';
        helper::end();
    }

    /**
     * Super Model: Init system view, module and privileges.
     *
     * @access public
     * @return void
     */
    public function initSystemResources()
    {
        $allResourceFile = $this->app->getModuleRoot() . 'group/lang/allresources.php';
        if(!file_exists("$allResourceFile")) die("Please execute the commands: touch $allResourceFile; chmod 777 $allResourceFile");

        $resourceContents = file_get_contents($allResourceFile);
        if(!$resourceContents) file_put_contents($allResourceFile, "<?php\n\$views     = array();\n\$resources = array();\n");

        $this->sortResource();
        $resource = json_decode(json_encode($this->lang->resource), true);
        $resource = serialize($resource);
        $view     = array_keys(json_decode(json_encode($this->lang->mainNav), true));
        $view     = serialize($view);

        file_put_contents($allResourceFile, "\$views['{$this->config->edition}']['{$this->config->vision}'] = '$view';\n", FILE_APPEND);
        file_put_contents($allResourceFile, "\$resources['{$this->config->edition}']['{$this->config->vision}'] = '$resource';\n", FILE_APPEND);

        echo 'success';
        helper::end();
    }

    /**
     * Super Model: Init views, modules and privileges for IPD.
     *
     * @access public
     * @return bool
     */
    public function initPrivs4IPD()
    {
        $views     = array_keys(json_decode(json_encode($this->lang->mainNav), true));
        $resources = json_decode(json_encode($this->lang->resource), true);

        $hasStoredPrivs = $this->dao->select('*')->from(TABLE_PRIV)->fetchAll('id');
        foreach($hasStoredPrivs as $privID => $hasStoredPriv)
        {
            $hasStoredPrivs["{$hasStoredPriv->module}-{$hasStoredPriv->method}"] = $hasStoredPriv;
            unset($hasStoredPrivs[$privID]);
        }

        $hasStoredManagered = $this->dao->select('*')->from(TABLE_PRIVMANAGER)->where('type')->ne('package')->fetchGroup('type', 'code');
        $hasStoredViews     = $hasStoredManagered['view'];
        $hasStoredModules   = $hasStoredManagered['module'];

        $viewOrder = 1;
        $viewPairs = array();
        foreach($views as $view)
        {
            if($view == 'menuOrder') continue;

            if(!empty($hasStoredViews[$view]))
            {
                $vision  = strpos($hasStoredViews[$view]->vision, ",{$this->config->vision},") !== false ? $hasStoredViews[$view]->vision : $hasStoredViews[$view]->vision . "{$this->config->vision},";
                $edition = strpos($hasStoredViews[$view]->edition, ",{$this->config->edition},") !== false ? $hasStoredViews[$view]->edition : $hasStoredViews[$view]->edition . "{$this->config->edition},";

                $viewID = $hasStoredViews[$view]->id;
                $this->dao->update(TABLE_PRIVMANAGER)
                    ->set('vision')->eq($vision)
                    ->set('edition')->eq($edition)
                    ->where('id')->eq($viewID)
                    ->exec();
            }
            else
            {
                $viewManager = new stdclass();
                $viewManager->parent  = 0;
                $viewManager->code    = $view;
                $viewManager->type    = 'view';
                $viewManager->edition = ',ipd,';
                $viewManager->vision  = ",{$this->config->vision},";
                $viewManager->order   = $viewOrder * 10;
                $viewOrder ++;

                $this->dao->insert(TABLE_PRIVMANAGER)->data($viewManager)->exec();
                $viewID = $this->dao->lastInsertId();

                foreach($this->config->langs as $lang => $langValue)
                {
                    $viewLang = new stdclass();
                    $viewLang->objectID   = $viewID;
                    $viewLang->objectType = 'manager';
                    $viewLang->lang       = $lang;
                    $viewLang->key        = $view;
                    $viewLang->value      = '';
                    $viewLang->desc       = '';

                    $this->dao->insert(TABLE_PRIVLANG)->data($viewLang)->exec();
                }
            }

            $viewPairs[$view] = $viewID;
        }

        $moduleOrder = 1;
        foreach($resources as $module => $privs)
        {
            if(!empty($hasStoredModules[$module]))
            {
                $vision  = strpos($hasStoredModules[$module]->vision, ",{$this->config->vision},") !== false ? $hasStoredModules[$module]->vision : $hasStoredModules[$module]->vision . "{$this->config->vision},";
                $edition = strpos($hasStoredModules[$module]->edition, ",{$this->config->edition},") !== false ? $hasStoredModules[$module]->edition : $hasStoredModules[$module]->edition . "{$this->config->edition},";

                $moduleID = $hasStoredModules[$module]->id;
                $this->dao->update(TABLE_PRIVMANAGER)
                    ->set('vision')->eq($vision)
                    ->set('edition')->eq($edition)
                    ->where('id')->eq($moduleID)
                    ->exec();
            }
            else
            {
                $moduleMenu = isset($this->lang->navGroup->{$module}) ? $this->lang->navGroup->{$module} : '';
                $moduleManager = new stdclass();
                $moduleManager->parent  = $moduleMenu ? $viewPairs[$moduleMenu] : 0;
                $moduleManager->code    = $module;
                $moduleManager->type    = 'module';
                $moduleManager->edition = ',ipd,';
                $moduleManager->vision  = ",{$this->config->vision},";
                $moduleManager->order   = $moduleOrder * 10;
                $moduleOrder ++;

                $this->dao->insert(TABLE_PRIVMANAGER)->data($moduleManager)->exec();
                $moduleID = $this->dao->lastInsertId();

                foreach($this->config->langs as $lang => $langValue)
                {
                    $moduleLang = new stdclass();
                    $moduleLang->objectID   = $moduleID;
                    $moduleLang->objectType = 'manager';
                    $moduleLang->lang       = $lang;
                    $moduleLang->key        = $module;
                    $moduleLang->value      = '';
                    $moduleLang->desc       = '';

                    $this->dao->insert(TABLE_PRIVLANG)->data($moduleLang)->exec();
                }
            }

            $privOrder = 1;
            foreach($privs as $methodName => $methodLang)
            {
                if(!empty($hasStoredPrivs["$module-$methodName"]))
                {
                    $vision  = strpos($hasStoredPrivs["$module-$methodName"]->vision, ",{$this->config->vision},") !== false ? $hasStoredPrivs["$module-$methodName"]->vision : $hasStoredPrivs["$module-$methodName"]->vision . "{$this->config->vision},";
                    $edition = strpos($hasStoredPrivs["$module-$methodName"]->edition, ",{$this->config->edition},") !== false ? $hasStoredPrivs["$module-$methodName"]->edition : $hasStoredPrivs["$module-$methodName"]->edition . "{$this->config->edition},";

                    $this->dao->update(TABLE_PRIV)
                        ->set('vision')->eq($vision)
                        ->set('edition')->eq($edition)
                        ->where('id')->eq($hasStoredPrivs["$module-$methodName"]->id)
                        ->exec();
                }
                else
                {
                    $priv = new stdclass();
                    $priv->module  = $module;
                    $priv->method  = $methodName;
                    $priv->parent  = $moduleID;
                    $priv->edition = ',ipd,';
                    $priv->vision  = ",{$this->config->vision},";
                    $priv->system  = '1';
                    $priv->order   = $privOrder * 10;
                    $privOrder ++;

                    $this->dao->insert(TABLE_PRIV)->data($priv)->exec();
                    $privID = $this->dao->lastInsertId();

                    foreach($this->config->langs as $lang => $langValue)
                    {
                        $privLang = new stdclass();
                        $privLang->objectID   = $privID;
                        $privLang->objectType = 'priv';
                        $privLang->lang       = $lang;
                        $privLang->key        = "{$module}-{$methodLang}";
                        $privLang->value      = '';
                        $privLang->desc       = '';
                        $this->dao->replace(TABLE_PRIVLANG)->data($privLang)->exec();
                    }
                }
            }
        }

        return true;
    }

    /**
     * Get priv by id.
     *
     * @param  int    $privID
     * @param  string $lang
     * @access public
     * @return object
     */
    public function getPrivByID($privID, $lang = '')
    {
        if(empty($lang)) $lang = $this->app->getClientLang();
        return $this->dao->select('t1.*,t2.key,t2.value,t2.desc')->from(TABLE_PRIV)->alias('t1')
            ->leftJoin(TABLE_PRIVLANG)->alias('t2')->on('t1.id=t2.objectID')
            ->where('t1.id')->eq((int)$privID)
            ->andWhere('t2.objectType')->eq('priv')
            ->andWhere('t2.lang')->eq($lang)
            ->fetch();
    }

    /**
     * Get priv by id list.
     *
     * @param  string    $privIdList
     * @access public
     * @return array
     */
    public function getPrivByIdList($privIdList)
    {
        return $this->dao->select('t1.*,t2.key,t2.value,t2.desc')->from(TABLE_PRIV)->alias('t1')
            ->leftJoin(TABLE_PRIVLANG)->alias('t2')->on('t1.id=t2.objectID')
            ->where('t1.id')->in($privIdList)
            ->andWhere('t1.edition')->like("%,{$this->config->edition},%")
            ->andWhere('t1.vision')->like("%,{$this->config->vision},%")
            ->andWhere('t2.lang')->eq($this->app->getClientLang())
            ->andWhere('t2.objectType')->eq('priv')
            ->orderBy('order_asc')
            ->fetchAll('id');
    }

    /**
     * Get priv by module.
     *
     * @param  array    $modules
     * @access public
     * @return array
     */
    public function getPrivByModule($modules)
    {
        $moduleIdList = $this->dao->select('id')->from(TABLE_PRIVMANAGER)
            ->where('code')->in($modules)
            ->andWhere('`type`')->eq('module')
            ->fetchAll('id');

        $packageIdList = $this->dao->select('id')->from(TABLE_PRIVMANAGER)
            ->where('`parent`')->in(array_keys($moduleIdList))
            ->fetchAll('id');

        $parentsIdList = array_merge(array_keys($moduleIdList), array_keys($packageIdList));

        $stmt = $this->dao->select('t1.*,t3.`parent` as moduleID,t2.`key`,t2.value,t2.`desc`')->from(TABLE_PRIV)->alias('t1')
            ->leftJoin(TABLE_PRIVLANG)->alias('t2')->on('t1.id=t2.objectID')
            ->leftJoin(TABLE_PRIVMANAGER)->alias('t3')->on('t1.parent=t3.id')
            ->where('t3.id')->in($parentsIdList)
            ->andWhere('t1.edition')->like("%,{$this->config->edition},%")
            ->andWhere('t1.vision')->like("%,{$this->config->vision},%")
            ->andWhere('t2.lang')->eq($this->app->getClientLang())
            ->andWhere('t2.objectType')->eq('priv')
            ->orderBy('t1.`order`')
            ->query();

        $privs = array();
        while($priv = $stmt->fetch()) $privs[$priv->moduleID][$priv->parent][$priv->id] = $priv;
        return $privs;
    }

    /**
     * Get priv list by nav.
     *
     * @param  string $nav
     * @param  string $version
     * @access public
     * @return array
     */
    public function getPrivListByNav($nav = '', $version = '')
    {
        $privList = array();
        $allPrivs = array();

        /* Filter privs equal or greater than this version.*/
        if($version) $versionPrivs = $this->getPrivsAfterVersion($version);

        /* Privs in package. */
        foreach($this->config->group->package as $packageCode => $packageData)
        {
            if(!isset($packageData->privs)) continue;

            foreach($packageData->privs as $privCode => $priv)
            {
                list($moduleName, $methodName) = explode('-', $privCode);
                $allPrivs[$privCode] = $privCode;

                if(!$this->config->inQuickon && in_array("{$moduleName}-{$methodName}", $this->config->group->hiddenPriv)) continue;
                if(strpos(',' . $priv['edition'] . ',', ',' . $this->config->edition . ',') === false) continue;
                if(strpos(',' . $priv['vision'] . ',',  ',' . $this->config->vision . ',')  === false) continue;

                if(!$this->checkNavSubset($nav, $packageData->subset)) continue;

                /* If version is selected, only show privs before the version. */
                if(!empty($version) and strpos($versionPrivs, ",$privCode,") === false) continue;

                /* Add methods in workflow menus, remove privs unused in the edition. */
                if(isset($this->lang->$moduleName->menus) && isset($this->lang->$moduleName->menus[$methodName]))
                {
                    $privName = $this->lang->$moduleName->menus[$methodName];
                }
                else
                {
                    if(!isset($this->lang->resource->$moduleName) || !isset($this->lang->resource->$moduleName->$methodName)) continue;
                    $methodLang = $this->lang->resource->$moduleName->$methodName;
                    if(!isset($this->lang->$moduleName->$methodLang))
                    {
                        $this->app->loadLang($moduleName);
                        if($moduleName == 'requirement') $this->app->loadLang('story');
                    }
                    $privName = isset($this->lang->$moduleName) && isset($this->lang->$moduleName->$methodLang) ? $this->lang->$moduleName->$methodLang : $privCode;
                }

                $priv = (object)array('subset' => $packageData->subset, 'package' => $packageCode, 'module' => $moduleName, 'method' => $methodName, 'selected' => false, 'name' => $privName);

                $privList[$privCode] = $priv;
            }
        }

        return $privList;
    }

    /**
     * Get privs list by module.
     *
     * @param  int    $queryID
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getPrivsListBySearch($queryID = 0, $pager = null)
    {
        $query = $queryID ? $this->loadModel('search')->getQuery($queryID) : '';

        /* Get the sql and form status from the query. */
        if($query)
        {
            $this->session->set('privQuery', $query->sql);
            $this->session->set('privForm', $query->form);
        }
        if($this->session->privQuery == false) $this->session->set('privQuery', ' 1 = 1');

        $privQuery = $this->session->privQuery;

        $this->loadModel('setting');
        if(strpos($privQuery, '`view`') !== false)
        {
            preg_match_all("/`view`[^']+'([^']+)'/Ui", $privQuery, $out);
            $privQuery = str_replace(array('`view` =', '`view` LIKE', '`view`  =', '`view` !=', '`view`  NOT LIKE'), array('`view` IN', '`view` IN', '`view` IN', '`view` NOT IN', '`view` NOT IN'), $privQuery);
            foreach($out[1] as $view)
            {
                $view = str_replace('%', '', $view);
                $modules = $this->setting->getItem("owner=system&module=priv&key={$view}Modules");
                $modules = str_replace(',', "','", $modules);
                $privQuery = preg_replace("/`view`([^']+)'([%]?{$view}[%]?)'/Ui", "`module`$1('{$modules}')", $privQuery);
            }
        }

        if(strpos($privQuery, '`recommendPrivs`') !== false)
        {
            preg_match_all("/`recommendPrivs`[^']+'([^']+)'/Ui", $privQuery, $out);
            foreach($out[1] as $priv)
            {
                $priv = str_replace('%', '', $priv);
                if(!empty($priv))
                {
                    $privQuery = preg_replace(array('/`recommendPrivs`[ ]+=/', '/`recommendPrivs`[ ]+LIKE/', '/`recommendPrivs`[ ]+=/', '/`recommendPrivs`[ ]+!=/', '/`recommendPrivs`[ ]+NOT LIKE/'), array('`recommendPrivs` IN', '`recommendPrivs` IN', '`recommendPrivs` IN', '`recommendPrivs` NOT IN', '`recommendPrivs` NOT IN'), $privQuery);
                    $privs     = $this->dao->select('priv,priv')->from(TABLE_PRIVRELATION)->where('relationPriv')->eq($priv)->andWhere('type')->eq('recommend')->fetchPairs();
                }
                else
                {
                    $privQuery = preg_replace(array('/`recommendPrivs`[ ]+=/', '/`recommendPrivs`[ ]+LIKE/', '/`recommendPrivs`[ ]+=/', '/`recommendPrivs`[ ]+!=/', '/`recommendPrivs`[ ]+NOT LIKE/'), array('`recommendPrivs` NOT IN', '`recommendPrivs` NOT IN', '`recommendPrivs` NOT IN', '`recommendPrivs` IN', '`recommendPrivs` IN'), $privQuery);
                    $privs     = $this->dao->select('priv,relationPriv')->from(TABLE_PRIVRELATION)->where('type')->eq('recommend')->fetchPairs();
                    $privs     = array_unique(array_keys($privs) + array_values($privs));
                }
                $privs     = implode("','", $privs);
                $privs     = !empty($privs) ? $privs : 0;
                $privQuery = preg_replace("/`recommendPrivs`([^']+)'([%]?{$priv}[%]?)'/Ui", "t1.`id`$1('{$privs}')", $privQuery);
            }
        }
        if(strpos($privQuery, '`dependPrivs`') !== false)
        {
            preg_match_all("/`dependPrivs`[^']+'([^']*)'/Ui", $privQuery, $out);
            foreach($out[1] as $priv)
            {
                $priv = str_replace('%', '', $priv);
                if(!empty($priv))
                {
                    $privQuery = preg_replace(array('/`dependPrivs`[ ]+=/', '/`dependPrivs`[ ]+LIKE/', '/`dependPrivs`[ ]+=/', '/`dependPrivs`[ ]+!=/', '/`dependPrivs`[ ]+NOT LIKE/'), array('`dependPrivs` IN', '`dependPrivs` IN', '`dependPrivs` IN', '`dependPrivs` NOT IN', '`dependPrivs` NOT IN'), $privQuery);
                    $privs     = $this->dao->select('priv,priv')->from(TABLE_PRIVRELATION)->where('relationPriv')->eq($priv)->andWhere('type')->eq('depend')->fetchPairs();
                }
                else
                {
                    $privQuery = preg_replace(array('/`dependPrivs`[ ]+=/', '/`dependPrivs`[ ]+LIKE/', '/`dependPrivs`[ ]+=/', '/`dependPrivs`[ ]+!=/', '/`dependPrivs`[ ]+NOT LIKE/'), array('`dependPrivs` NOT IN', '`dependPrivs` NOT IN', '`dependPrivs` NOT IN', '`dependPrivs` IN', '`dependPrivs` IN'), $privQuery);
                    $privs     = $this->dao->select('priv,relationPriv')->from(TABLE_PRIVRELATION)->where('type')->eq('depend')->fetchPairs();
                    $privs     = array_unique(array_keys($privs) + array_values($privs));
                }
                $privs     = implode("','", $privs);
                $privs     = !empty($privs) ? $privs : 0;
                $privQuery = preg_replace("/`dependPrivs`([^']+)'([%]?{$priv}[%]?)'/Ui", "t1.`id`$1('{$privs}')", $privQuery);
            }
        }
        if(strpos($privQuery, '`name`') !== false) $privQuery = str_replace('`name`', 't2.`name`', $privQuery);
        if(strpos($privQuery, '`module`') !== false) $privQuery = str_replace('`module`', 't1.`module`', $privQuery);
        if(strpos($privQuery, '`desc`') !== false) $privQuery = str_replace('`desc`', 't2.`desc`', $privQuery);

        $views   = empty($view) ? $this->setting->getItem("owner=system&module=priv&key=views") : $view;
        $views   = explode(',', $views);
        $modules = '';
        foreach($views as $view) $modules .= ',' . $this->setting->getItem("owner=system&module=priv&key={$view}Modules");
        $modules = trim($modules, ',');

        return $this->dao->select("t1.*,t2.name,t2.desc, INSTR('$modules', t1.`module`) as moduleOrder")->from(TABLE_PRIV)->alias('t1')
            ->leftJoin(TABLE_PRIVLANG)->alias('t2')->on('t1.id=t2.priv')
            ->leftJoin(TABLE_PRIVPACKAGE)->alias('t3')->on('t1.parent=t3.id')
            ->where('1=1')
            ->andWhere($privQuery)
            ->andWhere('t1.edition')->like("%,{$this->config->edition},%")
            ->andWhere('t1.vision')->like("%,{$this->config->vision},%")
            ->andWhere('t2.lang')->eq($this->app->getClientLang())
            ->orderBy("moduleOrder asc, t3.order asc, `order` asc")
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get priv relation.
     *
     * @param  int     $priv
     * @param  string  $type    depend|recommend
     * @param  string  $module
     * @access public
     * @return array
     */
    public function getPrivRelation($priv, $type = '', $module = '')
    {
        $relations = $this->dao->select('t1.type,t2.*,t3.`key`,t3.value')->from(TABLE_PRIVRELATION)->alias('t1')
            ->leftJoin(TABLE_PRIV)->alias('t2')->on("t1.relationPriv=CONCAT(t2.module, '-', t2.method)")
            ->leftJoin(TABLE_PRIVLANG)->alias('t3')->on('t2.id=t3.objectID')
            ->where('t1.priv')->eq($priv)
            ->andWhere('t2.edition')->like("%,{$this->config->edition},%")
            ->andWhere('t2.vision')->like("%,{$this->config->vision},%")
            ->andWhere('t3.objectType')->eq('priv')
            ->beginIF(!empty($type))->andWhere('t1.type')->eq($type)->fi()
            ->beginIF($module)->andWhere('t2.module')->eq($module)->fi()
            ->fetchGroup('type', 'id');

        foreach($relations as $type => $privList) $relations[$type] = $this->transformPrivLang($privList);

        if(!empty($type)) return zget($relations, $type, array());
        return $relations;
    }

    /**
     * Get priv relation.
     *
     * @param  array  $privs
     * @param  string $type    depend|recommend
     * @access public
     * @return array
     */
    public function getPrivRelationsByIdList($privs, $type = '', $returnType = 'name')
    {
        $privs = $this->dao->select('t1.priv,t3.`key`,t3.value,t1.type,t1.relationPriv')->from(TABLE_PRIVRELATION)->alias('t1')
            ->leftJoin(TABLE_PRIV)->alias('t2')->on("t1.relationPriv=CONCAT(t2.module, '-', t2.method)")
            ->leftJoin(TABLE_PRIVLANG)->alias('t3')->on('t2.id=t3.objectID')
            ->where('t1.priv')->in($privs)
            ->andWhere('t2.edition')->like("%,{$this->config->edition},%")
            ->andWhere('t2.vision')->like("%,{$this->config->vision},%")
            ->andWhere('t3.objectType')->eq('priv')
            ->beginIF(!empty($type))->andWhere('t1.type')->eq($type)->fi()
            ->fetchGroup('type', 'relationPriv');
        if($returnType == 'idGroup') return $privs;

        $relationPrivs = array();
        foreach($privs as $type => $typePrivs)
        {
            $typePrivs = $this->transformPrivLang($typePrivs);
            $relationPrivs[$type] = array();
            foreach($typePrivs as $priv) $relationPrivs[$type][$priv->priv] = empty($relationPrivs[$type][$priv->priv]) ? $priv->name : "{$relationPrivs[$type][$priv->priv]},{$priv->name}";
        }

        return $relationPrivs;
    }

    /**
     * Save relation.
     *
     * @param  array    $privIdList
     * @param  string   $type    depend|recommend
     * @access public
     * @return bool
     */
    public function saveRelation($privIdList, $type)
    {
        if(is_string($privIdList)) $privIdList = explode(',', $privIdList);
        $data = fixer::input('post')->get();
        if(empty($data->relation)) return false;

        foreach($privIdList as $privID)
        {
            $relation = new stdclass();
            $relation->priv = $privID;
            $relation->type = $type;
            foreach($data->relation as $privModule => $privRelations)
            {
                foreach($privRelations as $privRelation)
                {
                    if($privID == $privRelation) continue;
                    $relation->relationPriv = $privRelation;
                    $this->dao->replace(TABLE_PRIVRELATION)->data($relation)->exec();
                }
            }
        }
        return true;
    }

    /**
     * Get priv package tree.
     *
     * @param  string $type
     * @return array
     **/
    public function getModuleAndPackageTree($type = 'all')
    {
        $modules = $this->getMenuModules(null, true);

        $tree = array();

        foreach($modules as $module => $moduleName)
        {
            if($type == 'all') $tree[$module] = $moduleName;
            $packages = $this->getPrivPackagesByModule($module);
            foreach($packages as $packageID => $package)
            {
                $tree[$module . ',' . $packageID] = $moduleName . '/' . $package->name;
            }
        }
        return $tree;
    }

    /**
     * Create a privilege package.
     *
     * @access public
     * @return int
     */
    public function createPriv()
    {
        $data = fixer::input('post')->get();

        if(!empty($data->moduleName) and !empty($data->methodName))
        {
            $method = $this->dao->select('`moduleName`,`methodName`')->from(TABLE_PRIV)->where('`moduleName`')->eq($data->moduleName)->andWhere('`methodName`')->eq($data->methodName)->fetchPairs();
            if(count($method) > 0) dao::$errors['methodName'] = $this->lang->group->repeatPriv;
        }

        $this->config->priv->create->requiredFields = explode(',', $this->config->priv->create->requiredFields);
        foreach($this->config->priv->create->requiredFields as $field)
        {
            if(isset($data->{$field}) and empty($data->{$field}))
            {
                $langField = 'priv' . ucfirst($field);
                dao::$errors[$field] = sprintf($this->lang->error->notempty, $this->lang->group->{$langField});
            }
        }

        if(dao::isError()) return false;

        $priv = fixer::input('post')->remove('name,desc,view')->get();
        if(!empty($priv->module)) $priv->order = $this->dao->select('(count(`id`) + 1) * 5 as `order`')->from(TABLE_PRIV)->where('`module`')->eq($priv->module)->andWhere('`package`')->eq($priv->package)->fetch('order');
        $this->dao->insert(TABLE_PRIV)->data($priv)->exec();
        if(dao::isError()) return false;

        $privID = $this->dao->lastInsertId();

        $privLang = fixer::input('post')->remove('moduleName,methodName,view,module,package')->get();
        $privLang->priv = $privID;
        $privLang->lang = $this->app->clientLang;
        $this->dao->insert(TABLE_PRIVLANG)->data($privLang)->exec();

        $this->loadModel('action')->create('privlang', $privID, 'Opened');
        return $packageID;
    }

    /**
     * update priv info
     *
     * @param   void
     * @return  void
     **/
    public function updatePriv($privID, $lang)
    {
        $oldPriv = $this->getPrivByID($privID, $lang);

        $data = fixer::input('post')->get();

        if(!empty($data->moduleName) and !empty($data->methodName))
        {
            $method = $this->dao->select('moduleName,methodName')->from(TABLE_PRIV)->where('`moduleName`')->eq($data->moduleName)->andWhere('`methodName`')->eq($data->methodName)->andWhere('id')->ne($privID)->fetchAll('methodName');
            if(count($method) > 0) dao::$errors['methodName'] = $this->lang->group->repeatPriv;
        }

        $this->config->priv->edit->requiredFields = explode(',', $this->config->priv->edit->requiredFields);
        foreach($this->config->priv->edit->requiredFields as $field)
        {
            if(isset($data->{$field}) and empty($data->{$field}))
            {
                $langField = 'priv' . ucfirst($field);
                dao::$errors[$field] = sprintf($this->lang->error->notempty, $this->lang->group->{$langField});
            }
        }

        if(dao::isError()) return false;

        $priv = fixer::input('post')->remove('name,desc,view')->get();
        if(!empty($priv->module) and $priv->module != $oldPriv->module and $priv->package != $oldPriv->package) $priv->order = $this->dao->select('(count(`id`) + 1) * 5 as `order`')->from(TABLE_PRIV)->where('`module`')->eq($priv->module)->andWhere('`package`')->eq($priv->package)->fetch('order');
        $this->dao->update(TABLE_PRIV)->data($priv)->where('id')->eq($privID)->exec();

        $privLang = fixer::input('post')->remove('moduleName,methodName,view,module,package')->get();
        $this->dao->update(TABLE_PRIVLANG)->data($privLang)->where('priv')->eq($privID)->andWhere('lang')->eq($lang)->exec();

        $priv = $this->getPrivByID($privID, $lang);

        $changes = common::createChanges($oldPriv, $priv);
        return $changes;

    }

    /**
     * Delete a priv.
     *
     * @param  int    $privID
     * @access public
     * @return bool
     */
    public function deletePriv($privID)
    {
        $this->dao->delete()->from(TABLE_PRIV)->where('id')->eq($privID)->exec();
        $this->dao->delete()->from(TABLE_PRIVLANG)->where('priv')->eq($privID)->exec();
        $this->dao->delete()->from(TABLE_PRIVRELATION)->where('priv')->eq($privID)->orWhere('relationPriv')->eq($privID)->exec();
        if(dao::isError()) return false;
    }

    /**
     * Batch change package.
     *
     * @param  array  $privIdList
     * @param  string $module
     * @param  int    $packageID
     * @access public
     * @return void
     */
    public function batchChangePackage($privIdList, $module, $packageID)
    {
        $oldPrivs = $this->getPrivByIdList($privIdList);
        foreach($privIdList as $privID)
        {
            $oldPriv = $oldPrivs[$privID];
            if($packageID == $oldPriv->package and $module == $oldPriv->module) continue;

            $priv = new stdclass();
            $priv->module  = $module;
            $priv->package = $packageID;

            $this->dao->update(TABLE_PRIV)->data($priv)->autoCheck()->where('id')->eq((int)$privID)->exec();
            if(!dao::isError()) $allChanges[$privID] = common::createChanges($oldPriv, $priv);
        }
        return $allChanges;
    }

    /**
     * Build priv search form.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return void
     */
    public function buildPrivSearchForm($queryID, $actionURL)
    {
        $this->config->group->priv->search['actionURL'] = $actionURL;
        $this->config->group->priv->search['queryID']   = $queryID;

        $this->loadModel('setting');

        $views = $this->setting->getItem("owner=system&module=priv&key=views");
        $views = explode(',', $views);
        foreach($views as $index => $view)
        {
            $views[$view] = isset($this->lang->{$view}->common) ? $this->lang->{$view}->common : $view;
            unset($views[$index]);
        }

        $modules = '';
        foreach(array_keys($views) as $view) $modules .= ',' . $this->setting->getItem("owner=system&module=priv&key={$view}Modules");
        $modules    = trim($modules, ',');
        $modules    = explode(',', $modules);
        $moduleLang = $this->getMenuModules('', true);
        foreach($modules as $index => $module)
        {
            $modules[$module] = zget($moduleLang, $module);
            unset($modules[$index]);
        }

        $packages       = $this->getPrivPackagePairs();
        $packageModules = $this->getPrivPackagePairs('', '', 'module');
        foreach($packages as $packageID => $package)
        {
            $packages[$packageID] = $modules[$packageModules[$packageID]] . '/' . $package;
        }

        $privs    = array();
        $privList = $this->getPrivsListByView();
        foreach($privList as $privID => $priv)
        {
            $privs[$privID] = $modules[$priv->module] . '/' . $priv->name;
        }

        $this->config->group->priv->search['params']['view']['values']           = $views;
        $this->config->group->priv->search['params']['module']['values']         = $modules;
        $this->config->group->priv->search['params']['package']['values']        = $packages;
        $this->config->group->priv->search['params']['recommendPrivs']['values'] = $privs;
        $this->config->group->priv->search['params']['dependPrivs']['values']    = $privs;

        $this->loadModel('search')->setSearchParams($this->config->group->priv->search);
    }

    /**
     * Get all priv's lang pairs.
     *
     * @access public
     * @return array
     */
    public function getPrivLangPairs()
    {
        return $this->dao->select('objectID as priv,value as name')->from(TABLE_PRIVLANG)
            ->where('lang')->eq($this->app->clientLang)
            ->fetchPairs();
    }

    /**
     * Update priv order.
     *
     * @access public
     * @return void
     */
    public function updatePrivOrder()
    {
        $data = fixer::input('post')->get();
        foreach($data->orders as $privID => $order) $this->dao->update(TABLE_PRIV)->set('order')->eq($order)->where('id')->eq($privID)->exec();
    }

    /**
     * Transform priv lang.
     *
     * @param  array    $privs
     * @param  bool     $needPairs
     * @access public
     * @return array
     */
    public function transformPrivLang($privs, $needPairs = false)
    {
        $privPairs = array();
        foreach($privs as $moduleMethod => $priv)
        {
            $priv->name = '';
            if(!empty($priv->value))
            {
                $priv->name = $priv->value;
            }
            else
            {
                list($moduleName, $methodLang) = explode('-', $priv->key);
                $actualModule = $moduleName == 'requirement' ? 'story' : $moduleName;
                $this->app->loadLang($actualModule);

                $hasLang = (!empty($moduleName) and !empty($methodLang) and isset($this->lang->resource->{$priv->module}) and isset($this->lang->resource->{$priv->module}->{$priv->method}));
                if(!$hasLang)
                {
                    unset($privs[$moduleMethod]);
                    continue;
                }

                $priv->name = (!empty($moduleName) and !empty($methodLang) and isset($this->lang->{$moduleName}->$methodLang)) ? $this->lang->{$moduleName}->$methodLang : $priv->method;
            }

            $privPairs[$moduleMethod] = $priv->name;
        }

        return $needPairs ? $privPairs : $privs;
    }

    /**
     * Get custom privs.
     *
     * @param  string $menu
     * @param  array  $privs
     * @access public
     * @return array
     */
    public function getCustomPrivs($menu, $privs = array())
    {
        $allPrivs = $this->dao->select('module,method')->from(TABLE_PRIV)->where('edition')->like("%,{$this->config->edition},%")->andWhere('vision')->like("%,{$this->config->vision},%")->fetchGroup('module', 'method');
        foreach($this->lang->resource as $module => $methods)
        {
            if(isset($this->lang->$module->menus) and (empty($menu) or $menu == 'general'))
            {
                foreach($this->lang->$module->menus as $method => $value)
                {
                    $key  = "{$module}-{$method}";
                    $priv = new stdclass();
                    $priv->id          = $key;
                    $priv->module      = $module;
                    $priv->method      = $method;
                    $priv->action      = $key;
                    $priv->parent      = 0;
                    $priv->key         = "{$module}-{$method}";
                    $priv->parentCode  = $module;
                    $priv->moduleOrder = 0;
                    $priv->name        = $value;

                    $privs[$key] = $priv;
                }
            }

            foreach($methods as $method => $methodLabel)
            {
                if(isset($allPrivs[$module][$method])) continue;
                if(!$this->checkNavModule($menu, $module)) continue;
                if(!isset($this->lang->{$module}->{$methodLabel})) $this->app->loadLang($module);
                if(isset($this->lang->$module->menus) and $method == 'browse') continue;

                $key  = "{$module}-{$method}";
                $priv = new stdclass();
                $priv->id          = $key;
                $priv->module      = $module;
                $priv->method      = $method;
                $priv->action      = $key;
                $priv->parent      = 0;
                $priv->key         = "{$module}-{$methodLabel}";
                $priv->parentCode  = $module;
                $priv->moduleOrder = 0;
                $priv->name        = isset($this->lang->{$module}->{$methodLabel}) ? $this->lang->{$module}->{$methodLabel} : $method;

                $privs[$key] = $priv;
            }
        }
        return $privs;
    }

    /**
     * Load language of resource.
     *
     * @access public
     * @return void
     */
    public function loadResourceLang()
    {
        foreach($this->lang->resource as $moduleName => $action) $this->app->loadLang($moduleName);

        $this->app->loadLang('doc');
        $this->app->loadLang('api');

        $this->lang->custom->common = $this->lang->group->config;
        $this->lang->doc->common    = $this->lang->doc->manage;
        $this->lang->api->common    = $this->lang->api->manage;

        if(($this->config->edition == 'max' or $this->config->edition == 'ipd') and $this->config->vision == 'rnd' and isset($this->lang->baseline)) $this->lang->baseline->common = $this->lang->group->docTemplate;
    }

    /**
     * Process circular dependency.
     *
     * @param array $depends
     * @param array $privs
     * @param array $excludes
     * @access protected
     * @return array
     */
    protected function processDepends($depends, $privs, $excludes)
    {
        foreach($privs as $priv)
        {
            if(!isset($depends[$priv])) continue;

            foreach($depends[$priv] as $dependPriv)
            {
                if(isset($privs[$dependPriv]) || in_array($dependPriv, $excludes)) continue;
                $privs[$dependPriv] = $dependPriv;

                $dependPrivs = $this->processDepends($depends, $depends[$dependPriv], $excludes);

                foreach($dependPrivs as $depend)
                {
                    if(!in_array($depend, $excludes)) $privs[$depend] = $depend;
                }
            }
        }

        return $privs;
    }

    /**
     * Get related privs.
     *
     * @param  array  $allPrivList
     * @param  array  $selectedPrivList
     * @param  array  $recommendSelect
     * @access public
     * @return array
     */
    public function getRelatedPrivs($allPrivList, $selectedPrivList, $recommendSelect = array())
    {
        $this->loadResourceLang();

        $depends = array();

        $privSubsets  = array();
        $relatedPrivs = array('depend' => array(), 'recommend' => array());
        foreach($this->config->group->package as $packagePage => $package)
        {
            if(!isset($package->privs)) continue;

            foreach($package->privs as $privCode => $priv)
            {
                $privSubsets[$privCode] = $package->subset;

                foreach(array('depend', 'recommend') as $type)
                {
                    /* Show related pirvs when select. */
                    if($type == 'recommend' && in_array($privCode, $recommendSelect)) $relatedPrivs[$type][$privCode] = $privCode;
                    if($type == 'depend') $depends[$privCode] = $priv['depend'];

                    if(!in_array($privCode, $selectedPrivList) || !isset($priv[$type])) continue;

                    foreach($priv[$type] as $relatedPriv)
                    {
                        if(!in_array($relatedPriv, $selectedPrivList) && in_array($relatedPriv, $allPrivList)) $relatedPrivs[$type][$relatedPriv] = $relatedPriv;
                    }
                }
            }
        }

        /* Process circular dependency. */
        $relatedPrivs['depend'] = $this->processDepends($depends, $relatedPrivs['depend'], $selectedPrivList);

        $subsetPrivs = array('depend' => array(), 'recommend' => array());
        foreach(array('depend', 'recommend') as $type)
        {
            foreach($relatedPrivs[$type] as $relatedPriv)
            {
                if($type == 'recommend' && isset($relatedPrivs['depend'][$relatedPriv])) continue; // Don't show depend privs to recommend.

                $subsetName  = $privSubsets[$relatedPriv];
                $subsetTitle = isset($this->lang->$subsetName) && isset($this->lang->$subsetName->common) ? $this->lang->$subsetName->common : $subsetName;
                if(!isset($subsetPrivs[$type][$subsetName])) $subsetPrivs[$type][$subsetName] = array('id' => $subsetName, 'text' => $subsetTitle, 'children' => array());

                list($moduleName, $methodName) = explode('-', $relatedPriv);
                $method = $this->lang->resource->$moduleName->$methodName;

                if(!isset($this->lang->$moduleName->$method)) $this->app->loadLang($moduleName);
                $subsetPrivs[$type][$subsetName]['children'][] = array('id' => $relatedPriv, 'data-module' => $moduleName, 'data-method' => $methodName, 'subset' => $subsetName, 'text' => $this->lang->$moduleName->$method, 'data-id' => $relatedPriv);
            }
        }

        return array('depend' => array_values($subsetPrivs['depend']), 'recommend' => array_values($subsetPrivs['recommend']));
    }

    /**
     * Get unassigned privs by module.
     *
     * @param  string  $module
     * @access public
     * @return array
     */
    public function getUnassignedPrivsByModule($module)
    {
        return $this->dao->select('t1.*')->from(TABLE_PRIV)->alias('t1')
            ->leftJoin(TABLE_PRIVMANAGER)->alias('t2')->on('t1.parent=t2.id')
            ->where('t2.`code`')->eq($module)
            ->andWhere('t1.edition')->like("%,{$this->config->edition},%")
            ->andWhere('t1.vision')->like("%,{$this->config->vision},%")
            ->andWhere('t2.type')->eq('module')
            ->orderBy('order_asc')
            ->fetchAll('id');
    }

    /**
     * Get privs list by group.
     *
     * @param  int    $groupID
     * @access public
     * @return array
     */
    public function getPrivListByGroup($groupID)
    {
        return $this->dao->select("CONCAT(module, '-',  method) AS action")->from(TABLE_GROUPPRIV)->where('`group`')->eq($groupID)->fetchPairs();
    }

    /**
     * AJAX: Get privs by parents.
     *
     * @param  string  $selectedSubset
     * @param  string  $selectedPackages
     * @access public
     * @return bool
     */
    public function getPrivByParents($selectedSubset, $selectedPackages = '')
    {
        $allPrivs = array();
        $privs    = array();

        /* Privs in package. */
        foreach($this->config->group->package as $packageCode => $packageData)
        {
            foreach($packageData->privs as $privCode => $priv)
            {
                list($moduleName, $methodName) = explode('-', $privCode);

                /* Remove privs unused in the edition. */
                if(isset($this->lang->$moduleName->menus) && isset($this->lang->$moduleName->menus[$methodName]))
                {
                    $privName = $this->lang->$moduleName->menus[$methodName];
                }
                else
                {
                    if(!isset($this->lang->resource->$moduleName) || !isset($this->lang->resource->$moduleName->$methodName)) continue;
                    $methodLang = $this->lang->resource->$moduleName->$methodName;
                    if(!isset($this->lang->$moduleName->$methodLang))
                    {
                        $this->app->loadLang($moduleName);
                        if($moduleName == 'requirement') $this->app->loadLang('story');
                    }
                    $privName = isset($this->lang->$moduleName) && isset($this->lang->$moduleName->$methodLang) ? $this->lang->$moduleName->$methodLang : "$moduleName-$methodName";
                }

                $allPrivs[$privCode] = $privCode;

                $subset = $packageData->subset;
                if($subset !== $selectedSubset) continue;

                if($selectedPackages && strpos($selectedPackages, "|$packageCode|") === false) continue;

                $privs[$privCode] = $privName;
            }
        }

        return $privs;
    }

    /**
     * Get privs after version
     *
     * @param  string $version
     * @access public
     * @return string
     */
    public function getPrivsAfterVersion($version)
    {
        $realVersion = str_replace('_', '.', $version);
        $changelog   = array();
        foreach($this->lang->changelog as $currentVersion => $currentChangeLog)
        {
            if(version_compare($currentVersion, $realVersion, '>=')) $changelog[] = join(',', $currentChangeLog);
        }
        $versionPrivs = ',' . join(',', $changelog) . ',';

        return $versionPrivs;
    }

    /**
     * 更新权限包的排序。
     * Update the order of package.
     *
     * @param  int    $id
     * @param  int    $order
     * @access public
     * @return bool
     */
    public function updatePackageOrder(int $id, int $order): bool
    {
        $this->dao->update(TABLE_PRIVPACKAGE)->set('order')->eq($order)->where('id')->eq($id)->exec();
        return !dao::isError();
    }
}
