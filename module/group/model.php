<?php
declare(strict_types=1);
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
            ->check('name', 'unique', "vision = '{$this->config->vision}' && project='{$group->project}'")
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
            ->where('id')->eq($groupID)
            ->exec();

        return !dao::isError();
    }

    /**
     * 复制一个分组。
     * Copy a group.
     *
     * @param  int    $groupID
     * @param  object $group
     * @param  array  $options
     * @access public
     * @return int|false
     */
    public function copy(int $groupID, object $group, array $options): int|false
    {
        $this->lang->error->unique = $this->lang->group->repeat;
        $this->dao->insert(TABLE_GROUP)->data($group)
            ->check('name', 'unique', "vision = '{$this->config->vision}' && project = '{$group->project}'")
            ->exec();
        if(dao::isError()) return false;

        $newGroupID = $this->dao->lastInsertID();
        if(empty($options)) return $newGroupID;

        if(in_array('copyPriv', $options)) $this->copyPriv($groupID, $newGroupID);
        if(in_array('copyUser', $options)) $this->copyUser($groupID, $newGroupID);

        return $newGroupID;
    }

    /**
     * 复制分组的权限。
     * Copy privileges.
     *
     * @param  int    $fromGroup
     * @param  int    $toGroup
     * @access public
     * @return void
     */
    public function copyPriv(int $fromGroupID, int $toGroupID)
    {
        $privs = $this->dao->findByGroup($fromGroupID)->from(TABLE_GROUPPRIV)->fetchAll();
        foreach($privs as $key => $priv)
        {
            $privs[$key]->group = $toGroupID;
        }
        $this->insertPrivs($privs);
    }

    /**
     * 复制分组的成员。
     * Copy user.
     *
     * @param  int    $fromGroup
     * @param  int    $toGroup
     * @access public
     * @return void
     */
    public function copyUser($fromGroupID, $toGroupID)
    {
        $users = $this->dao->findByGroup($fromGroupID)->from(TABLE_USERGROUP)->fetchAll();
        foreach($users as $user)
        {
            $user->group = $toGroupID;
            $this->dao->insert(TABLE_USERGROUP)->data($user)->exec();
        }
    }

    /**
     * 获取分组列表。
     * Get group lists.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getList(int $projectID = 0)
    {
        return $this->dao->select('*')->from(TABLE_GROUP)
            ->where('project')->eq($projectID)
            ->beginIF($this->config->vision)->andWhere('vision')->eq($this->config->vision)->fi()
            ->orderBy('id')
            ->fetchAll();
    }

    /**
     * 获取分组。
     * Get group pairs.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getPairs(int $projectID = 0)
    {
        return $this->dao->select('id, name')->from(TABLE_GROUP)
            ->where('project')->eq($projectID)
            ->andWhere('vision')->eq($this->config->vision)
            ->orderBy('id')->fetchPairs();
    }

    /**
     * 获取一个分组。
     * Get group by id.
     *
     * @param  int    $groupID
     * @access public
     * @return object
     */
    public function getByID(int $groupID)
    {
        $group = $this->dao->findById($groupID)->from(TABLE_GROUP)->fetch();
        if(!$group) return null;

        if($group->acl) $group->acl = json_decode($group->acl, true);
        if(!isset($group->acl) || !is_array($group->acl)) $group->acl = array();
        return $group;
    }

    /**
     * 获取用户的所有分组。
     * Get group by account.
     *
     * @param  string    $account
     * @param  bool      $allVision
     * @access public
     * @return array
     */
    public function getByAccount(string $account, bool $allVision = false)
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
     * 获取多个分组包含的所有用户。
     * Get the accounts in the groups.
     *
     * @param  array  $groupIdList
     * @access public
     * @return array
     */
    public function getGroupAccounts(array $groupIdList = array())
    {
        $groupIdList = array_filter($groupIdList);
        if(empty($groupIdList)) return array();
        return $this->dao->select('account')->from(TABLE_USERGROUP)->where('`group`')->in($groupIdList)->fetchPairs('account');
    }

    /**
     * 获取一个分组的权限列表。
     * Get privileges of a groups.
     *
     * @param  int    $groupID
     * @access public
     * @return array
     */
    public function getPrivs(int $groupID)
    {
        $privs = array();
        $stmt  = $this->dao->select('module, method')->from(TABLE_GROUPPRIV)->where('`group`')->eq($groupID)->orderBy('module')->query();
        while($priv = $stmt->fetch()) $privs[$priv->module][$priv->method] = $priv->method;
        return $privs;
    }

    /**
     * 获取一个分组下的用户Pairs。
     * Get user pairs of a group.
     *
     * @param  int    $groupID
     * @access public
     * @return array
     */
    public function getUserPairs(int $groupID)
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
     * 为项目管理员分组展示项目集、项目、产品、执行。
     * Get object for manage admin group.
     *
     * @access public
     * @return void
     */
    public function getObjectForAdminGroup()
    {
        $objects = $this->dao->select('id, name, path, type, project, grade, parent')->from(TABLE_PROJECT)
            ->where('vision')->eq($this->config->vision)
            ->andWhere('type')->ne('program')
            ->andWhere('deleted')->eq(0)
            ->fetchAll('id');

        $projects   = array();
        $executions = array();
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

        $programs = $this->getProgramsForAdminGroup();
        $products = $this->getProductsForAdminGroup($programs);
        return array($programs, $projects, $products, $executions);
    }

    /**
     * 为项目管理员分组展示项目集。
     * Get programs for admin group.
     *
     * @access protected
     * @return array
     */
    protected function getProgramsForAdminGroup()
    {
        $isAdmin = $this->app->user->admin;

        /* Get the list of program sets under administrator permission. */
        if(!$this->app->user->admin) $this->app->user->admin = true;
        $programs = $this->loadModel('program')->getParentPairs('', '', false);
        if(!$isAdmin) $this->app->user->admin = false;

        return $programs;
    }

    /**
     * 为项目管理员分组展示产品列表。
     * Get products for admin group.
     *
     * @param  array  $programs
     * @access protected
     * @return array
     */
    protected function getProductsForAdminGroup($programs)
    {
        $products = array();

        $productList = $this->dao->select('id, name, program')->from(TABLE_PRODUCT)
            ->where('vision')->eq($this->config->vision)
            ->andWhere('deleted')->eq(0)
            ->andWhere('shadow')->eq(0)
            ->fetchAll('id');

        foreach($productList as $id => $product)
        {
            if(isset($programs[$product->program]) and $this->config->systemMode == 'ALM') $product->name = $programs[$product->program] . '/' . $product->name;
            $products[$product->id] = $product->name;
        }

        return $products;
    }

    /**
     * 获取项目管理员。
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
                ->where("CONCAT(',', $field, ',')")->like("%,$id,%")
                ->orWhere($field)->eq('all')
                ->fetchPairs();
        }

        return $objects;
    }

    /**
     * 删除分组。
     * Remove group.
     *
     * @param  int    $groupID
     * @access public
     * @return void
     */
    public function remove(int $groupID)
    {
        $this->dao->delete()->from(TABLE_GROUP)->where('id')->eq($groupID)->exec();
        $this->dao->delete()->from(TABLE_USERGROUP)->where('`group`')->eq($groupID)->exec();
        $this->dao->delete()->from(TABLE_GROUPPRIV)->where('`group`')->eq($groupID)->exec();
    }

    /**
     * Insert privs.
     *
     * @param  array $privs
     * @access protected
     * @return bool
     */
    public function insertPrivs(array $privs)
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
     * 更新视野权限。
     * Update view priv.
     *
     * @param  int    $groupID
     * @param  array  $actions
     * @access public
     * @return bool
     */
    public function updateView(int $groupID, array $actions)
    {
        /* 如果产品不为空，需要将影子产品追加上。Add shadow productID when select noProduct project or execution. */
        if(($actions['projects'] || $actions['sprints']) && isset($actions['products']))
        {
            /* Get all noProduct projects and executions . */
            $noProductList       = $this->loadModel('project')->getNoProductList();
            $shadowProductIDList = $this->dao->select('id')->from(TABLE_PRODUCT)->where('shadow')->eq(1)->fetchPairs();
            $noProductObjects    = array_merge($actions['projects'], $actions['sprints']);

            foreach($noProductObjects as $objectID)
            {
                if(isset($noProductList[$objectID])) $actions['products'][] = $noProductList[$objectID]->product;
            }
        }

        /* 设置views。 Set views. */
        if(!$actions['actionallchecker'])
        {
            $actions['views'] = empty($actions['views']) ? array() : array_keys($actions['views']);
            $actions['views'] = array_combine($actions['views'], $actions['views']);

            if(!isset($actions['actions'])) $actions['actions'] = array();
            if(isset($actions['actions']['project']['started']))   $actions['actions']['project']['syncproject']     = 'syncproject';
            if(isset($actions['actions']['execution']['started'])) $actions['actions']['execution']['syncexecution'] = 'syncexecution';

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
            $actions['actions'] = $dynamic;
        }

        $actions = empty($actions) ? '' : json_encode($actions);
        $this->dao->update(TABLE_GROUP)->set('acl')->eq($actions)->where('id')->eq($groupID)->exec();
        return !dao::isError();
    }

    /**
     * 按分组维护权限。
     * Update privilege of a group.
     *
     * @param  int    $groupID
     * @param  string $nav
     * @param  string $version
     * @access public
     * @return bool
     */
    public function updatePrivByGroup(int $groupID, string $nav, string $version = '')
    {
        /* Delete old. */
        $privs = array_keys($this->getPrivsByNav($nav, $version));

        $this->dao->delete()->from(TABLE_GROUPPRIV)
            ->where('`group`')->eq($groupID)
            ->beginIF(!empty($nav))->andWhere("CONCAT(module, '-', method)")->in($privs)->fi()
            ->exec();

        $data         = new stdclass();
        $data->group  = $groupID;
        $data->module = 'index';
        $data->method = 'index';
        $this->dao->replace(TABLE_GROUPPRIV)->data($data)->exec();

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

            $insertPrivs    = array();
            $insertPrivKeys = array();
            foreach($this->post->actions as $moduleName => $moduleActions)
            {
                if(empty($moduleName) or empty($moduleActions)) continue;

                foreach($moduleActions as $actionName)
                {
                    $data = new stdclass();
                    $data->group  = $groupID;
                    $data->module = $moduleName;
                    $data->method = $actionName;

                    $priKey = "{$moduleName}-{$actionName}";
                    $insertPrivs[$priKey] = $data;
                    $insertPrivKeys[$priKey] = $priKey;
                }
            }

            $insertDependPrivs = $this->processDepends($dependPrivs, $insertPrivKeys, $insertPrivKeys);
            foreach($insertDependPrivs as $priKey)
            {
                if(isset($insertPrivs[$priKey])) continue;
                list($moduleName, $actionName) = explode('-', $priKey);

                $data = new stdclass();
                $data->group  = $groupID;
                $data->module = $moduleName;
                $data->method = $actionName;

                $insertPrivs[$priKey] = $data;
            }

            $this->insertPrivs($insertPrivs);
        }

        return count($insertDependPrivs) != count($insertPrivKeys);
    }

    /**
     * 按模块维护权限。
     * Update privilege by module.
     *
     * @access public
     * @return void
     */
    public function updatePrivByModule()
    {
        if($this->post->module == false || array_filter($this->post->actions) == false || array_filter($this->post->groups) == false) return false;

        $privs = array();
        foreach($this->post->actions as $action)
        {
            if(!$action) continue;
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
     * 更新用户。
     * Update users.
     *
     * @param  int    $groupID
     * @access public
     * @return void
     */
    public function updateUser(int $groupID)
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
     * 更新项目管理员。
     * Update project admins.
     *
     * @param  array  $formData
     * @access public
     * @return void
     */
    public function updateProjectAdmin(array $formData)
    {
        $allUsers = $this->dao->select('account')->from(TABLE_PROJECTADMIN)->fetchPairs();
        $this->dao->delete()->from(TABLE_PROJECTADMIN)->exec();

        $noProductList = $this->loadModel('project')->getNoProductList();
        $shadowProductIDList = $this->dao->select('id')->from(TABLE_PRODUCT)->where('shadow')->eq(1)->fetchPairs();

        foreach($formData as $lineID => $data)
        {
            if(!in_array('all', $data['product']))
            {
                /* 无产品项目的隐藏产品需要添加到product里。 Append products of 'No Product' project,execution. */
                if($data['project'] || $data['execution'])
                {
                    $objects = array_merge($data['project'], $data['execution']);
                    foreach($objects as $objectID)
                    {
                        if(isset($noProductList[$objectID])) $data['product'][] = $noProductList[$objectID]->product;
                    }
                }

                if((in_array('all', $data['execution']) || in_array('all', $data['project']))) $data['product'] = array_merge($data['product'], $shadowProductIDList);
            }

            foreach($data['accounts'] as $account)
            {
                if(!$account) continue;

                $projectAdmin = new stdclass();
                $projectAdmin->group      = $lineID;
                $projectAdmin->account    = $account;
                $projectAdmin->programs   = implode(',', $data['program']);
                $projectAdmin->projects   = implode(',', $data['project']);
                $projectAdmin->products   = implode(',', $data['product']);
                $projectAdmin->executions = implode(',', $data['execution']);
                $this->dao->replace(TABLE_PROJECTADMIN)->data($projectAdmin)->exec();

                $allUsers[$account] = $account;
            }
        }

        $this->loadModel('user');
        foreach($allUsers as $account)
        {
            if($account) $this->user->computeUserView($account, true);
        }

        return !dao::isError();
    }

    /**
     * resource排序。
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
     * 判断分组操作是否可点击。
     * Judge an action is clickable or not.
     *
     * @param  object $group
     * @param  string $action
     * @static
     * @access public
     * @return bool
     */
    public static function isClickable(object $group, string $action): bool
    {
        $action = strtolower($action);

        if($group->role == 'projectAdmin' && $action != 'manageprojectadmin') return false;
        if($action == 'manageprojectadmin' && $group->role != 'projectAdmin') return false;
        if($action == 'manageview' && $group->role == 'limited') return false;
        if($action == 'copy' && $group->role == 'limited') return false;

        return true;
    }

    /**
     * 加载resource的语言配置。
     * Load language of resource.
     *
     * @access public
     * @return void
     */
    public function loadResourceLang(): void
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
     * 处理权限依赖关系
     * Process circular dependency.
     *
     * @param array $depends
     * @param array $privs
     * @param array $excludes
     * @param array $processedPrivs
     * @access protected
     * @return array
     */
    protected function processDepends(array $depends, array $privs, array $excludes, array $processedPrivs = array()): array
    {
        foreach($privs as $priv)
        {
            if(!isset($depends[$priv]) || isset($processedPrivs[$priv])) continue;

            /* 不重复处理权限，防止出现死循环。Avoid the infinite loop. */
            $processedPrivs[$priv] = true;

            foreach($depends[$priv] as $dependPriv)
            {
                if(isset($privs[$dependPriv]) || in_array($dependPriv, $excludes)) continue;
                $privs[$dependPriv] = $dependPriv;

                $dependPrivs = $this->processDepends($depends, zget($depends, $dependPriv, array()), $excludes, $processedPrivs);

                foreach($dependPrivs as $depend)
                {
                    if(!in_array($depend, $excludes)) $privs[$depend] = $depend;
                }
            }
        }

        return $privs;
    }

    /**
     * 检查subset是否属于该nav。
     * Check nav have subset.
     *
     * @param  string $nav
     * @param  string $subset
     * @access protected
     * @return bool
     */
    protected function checkNavSubset(string $nav, string $subset): bool
    {
        if(empty($nav)) return true;

        if($nav == 'general') return !isset($this->config->group->subset->$subset) || !isset($this->config->group->subset->$subset->nav) || $this->config->group->subset->$subset->nav == 'general';

        return isset($this->config->group->subset->$subset)
            && isset($this->config->group->subset->$subset->nav)
            && $this->config->group->subset->$subset->nav == $nav;
    }

    /**
     * Get priv list by nav.
     *
     * @param  string $nav
     * @param  string $version
     * @access public
     * @return array
     */
    public function getPrivsByNav(string $nav = '', string $version = ''): array
    {
        $privList = array();
        $allPrivs = array();

        /* Filter privs equal or greater than this version.*/
        if($version) $versionPrivs = $this->getPrivsAfterVersion($version);
        $hiddenHost = $this->loadModel('zahost')->hiddenHost();

        /* Privs in package. */
        foreach($this->config->group->package as $packageCode => $packageData)
        {
            if(!isset($packageData->privs)) continue;

            foreach($packageData->privs as $privCode => $priv)
            {
                list($moduleName, $methodName) = explode('-', $privCode);
                $allPrivs[$privCode] = $privCode;

                if($hiddenHost && $moduleName == 'zahost') continue;
                if($hiddenHost && $moduleName == 'zanode' && !in_array($methodName, $this->config->group->showNodePriv)) continue;

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
     * Get privs list by group.
     *
     * @param  int    $groupID
     * @access public
     * @return array
     */
    public function getPrivsByGroup(int $groupID): array
    {
        return $this->dao->select("CONCAT(module, '-',  method) AS action")->from(TABLE_GROUPPRIV)->where('`group`')->eq($groupID)->fetchPairs();
    }

    /**
     * AJAX: Get privs by parents.
     *
     * @param  string  $selectedSubset
     * @param  string  $selectedPackages
     * @access public
     * @return array
     */
    public function getPrivsByParents(string $selectedSubset, string $selectedPackages = ''): array
    {
        $allPrivs = array();
        $privs    = array();

        /* Privs in package. */
        foreach($this->config->group->package as $packageCode => $packageData)
        {
            if(!isset($packageData->privs)) continue;
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
    public function getPrivsAfterVersion(string $version): string
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
     * Get related privs.
     *
     * @param  array  $allPrivList
     * @param  array  $selectedPrivList
     * @param  array  $recommendSelect
     * @access public
     * @return array
     */
    public function getRelatedPrivs(array $allPrivList, array $selectedPrivList, array $recommendSelect = array()): array
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
                    /* Show related privs when select. */
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

                if(!isset($this->lang->resource->$moduleName->$methodName)) continue;

                $method = $this->lang->resource->$moduleName->$methodName;

                if(!isset($this->lang->$moduleName->$method)) $this->app->loadLang($moduleName);
                $subsetPrivs[$type][$subsetName]['children'][] = array('id' => $relatedPriv, 'data-module' => $moduleName, 'data-method' => $methodName, 'subset' => $subsetName, 'text' => $this->lang->$moduleName->$method, 'data-id' => $relatedPriv);
            }
        }

        return array('depend' => array_values($subsetPrivs['depend']), 'recommend' => array_values($subsetPrivs['recommend']));
    }
}
