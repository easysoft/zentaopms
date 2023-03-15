<?php
/**
 * The model file of group module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     group
 * @version     $Id: model.php 4976 2013-07-02 08:15:31Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
class groupModel extends model
{
    /**
     * Create a group.
     *
     * @access public
     * @return bool
     */
    public function create()
    {
        $group = fixer::input('post')->get();
        if(isset($group->limited))
        {
            unset($group->limited);
            $group->role = 'limited';
        }
        $this->lang->error->unique = $this->lang->group->repeat;
        $this->dao->insert(TABLE_GROUP)->data($group)->batchCheck($this->config->group->create->requiredFields, 'notempty')->check('name', 'unique')->exec();
        return $this->dao->lastInsertId();
    }

    /**
     * Update a group.
     *
     * @param  int    $groupID
     * @access public
     * @return void
     */
    public function update($groupID)
    {
        $group = fixer::input('post')->get();
        $this->lang->error->unique = $this->lang->group->repeat;
        return $this->dao->update(TABLE_GROUP)->data($group)->batchCheck($this->config->group->edit->requiredFields, 'notempty')->check('name', 'unique', "id != {$groupID}")->where('id')->eq($groupID)->exec();
    }

    /**
     * Copy a group.
     *
     * @param  int    $groupID
     * @access public
     * @return void
     */
    public function copy($groupID)
    {
        $group = fixer::input('post')->remove('options')->get();
        $this->lang->error->unique = $this->lang->group->repeat;
        $this->dao->insert(TABLE_GROUP)->data($group)->check('name', 'unique')->check('name', 'notempty')->exec();
        if($this->post->options == false) return;
        if(!dao::isError())
        {
            $newGroupID = $this->dao->lastInsertID();
            $options    = join(',', $this->post->options);
            if(strpos($options, 'copyPriv') !== false) $this->copyPriv($groupID, $newGroupID);
            if(strpos($options, 'copyUser') !== false) $this->copyUser($groupID, $newGroupID);
        }
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
        foreach($privs as $priv)
        {
            $priv->group = $toGroup;
            $this->dao->replace(TABLE_GROUPPRIV)->data($priv)->exec();
        }
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
        foreach($admins as $groupID => $adminGroup)
        {
            if(!empty($adminGroup))
            {
                $accounts = implode(',', array_keys($adminGroup));
                $projectAdmins[$accounts] = current($adminGroup);
            }
        }

        return $projectAdmins;
    }

    /**
     * Get admins by object id list.
     *
     * @param  int    $idList
     * @param  string $field
     * @access public
     * @return void
     */
    public function getAdmins($idList, $field = 'programs')
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
     * @access public
     * @return bool
     */
    public function updatePrivByGroup($groupID, $menu, $version)
    {
        /* Delete old. */
        /* Set priv when have version. */
        if($version)
        {
            $noCheckeds = trim($this->post->noChecked, ',');
            if($noCheckeds)
            {
                $noCheckeds = explode(',', $noCheckeds);
                foreach($noCheckeds as $noChecked)
                {
                    /* Delete no checked priv*/
                    list($module, $method) = explode('-', $noChecked);
                    $this->dao->delete()->from(TABLE_GROUPPRIV)->where('`group`')->eq($groupID)->andWhere('module')->eq($module)->andWhere('method')->eq($method)->exec();
                }
            }
        }
        else
        {
            foreach($this->getMenuModules($menu) as $moduleName)
            {
                $methodList = (array)zget($this->lang->resource, $moduleName, array());
                $methodList = array_keys($methodList);
                $this->dao->delete()->from(TABLE_GROUPPRIV)
                    ->where('`group`')->eq($groupID)
                    ->andWhere('module')->eq($moduleName)
                    ->beginIF($methodList)->andWhere('method')->in($methodList)->fi()
                    ->exec();
            }
        }

        /* Insert new. */
        if($this->post->actions)
        {
            foreach($this->post->actions as $moduleName => $moduleActions)
            {
                foreach($moduleActions as $actionName)
                {
                    $data         = new stdclass();
                    $data->group  = $groupID;
                    $data->module = $moduleName;
                    $data->method = $actionName;
                    $this->dao->replace(TABLE_GROUPPRIV)->data($data)->exec();
                }
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

        if(isset($_POST['allchecker']))$actions['views']   = array();
        if(!isset($actions['actions']))$actions['actions'] = array();

        if(isset($actions['actions']['project']['started']))   $actions['actions']['project']['syncproject'] = 'syncproject';
        if(isset($actions['actions']['execution']['started'])) $actions['actions']['execution']['syncexecution'] = 'syncexecution';

        $dynamic = $actions['actions'];
        if(!isset($_POST['allchecker']))
        {
            $dynamic = array();
            foreach($actions['actions'] as $moduleName => $moduleActions)
            {
                $groupName = $moduleName;
                if(isset($this->lang->navGroup->$moduleName)) $groupName = $this->lang->navGroup->$moduleName;
                if($moduleName == 'case') $groupName = $this->lang->navGroup->testcase;
                if($groupName != 'my' and isset($actions['views']) and !in_array($groupName, $actions['views'])) continue;

                $dynamic[$moduleName] = $moduleActions;
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

        foreach($this->post->actions as $action)
        {
            foreach($this->post->groups as $group)
            {
                $data         = new stdclass();
                $data->group  = $group;
                $data->module = $this->post->module;
                $data->method = $action;
                $this->dao->replace(TABLE_GROUPPRIV)->data($data)->exec();
            }
        }
        return true;
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

            if($executionAll[$lineID] or $projectAll[$lineID]) $products[$lineID] = array_merge($products[$lineID], $shadowProductIDList);

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
     * Check menu have module
     *
     * @param  string    $menu
     * @param  string    $moduleName
     * @access public
     * @return void
     */
    public function checkMenuModule($menu, $moduleName)
    {
        if(empty($menu)) return true;
        if($menu == 'other' and (isset($this->lang->navGroup->$moduleName) or isset($this->lang->mainNav->$moduleName))) return false;
        if($menu != 'other' and !($moduleName == $menu or (isset($this->lang->navGroup->$moduleName) and $this->lang->navGroup->$moduleName == $menu))) return false;
        if($menu == 'project' and strpos('caselib|testsuite|report', $moduleName) !== false) return false;
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
            if($this->checkMenuModule($menu, $moduleName))
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

        if($action == 'manageview' and $group->role == 'limited') return false;
        if($action == 'copy' and $group->role == 'limited') return false;

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
        }
        $this->dao->update(TABLE_PRIVPACKAGE)->data($package)->batchCheck($this->config->privPackage->edit->requiredFields, 'notempty')->where('id')->eq($packageID)->exec();
        if(dao::isError()) return false;

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
        return $this->dao->findById($packageID)->from(TABLE_PRIVPACKAGE)->fetch();
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
        return $this->dao->select('*')->from(TABLE_PRIVPACKAGE)->where('module')->eq($module)->orderBy('order_asc')->fetchAll('id');
    }

    /**
     * Get priv package pairs by view.
     *
     * @access public
     * @return array
     */
    public function getPrivPackagePairs($view = '', $module = '', $field = 'name')
    {
        $this->loadModel('setting');

        $modules = '';
        if(!empty($module))
        {
            $modules = $module;
        }
        elseif(!empty($view))
        {
            $modules = $this->setting->getItem("owner=system&module=priv&key={$view}Modules");
        }
        $modules = trim($modules, ',');

        return $this->dao->select("id,{$field}")->from(TABLE_PRIVPACKAGE)->where('1=1')->beginIF(!empty($modules))->andWhere('module')->in($modules)->fi()->orderBy('order_asc')->fetchPairs();
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
     * Init Privs.
     *
     * @access public
     * @return void
     */
    public function initPrivs()
    {
        $this->sortResource();
        $resource = json_decode(json_encode($this->lang->resource), true);
        $this->dao->delete()->from(TABLE_PRIVLANG)->exec();
        $this->dao->delete()->from(TABLE_PRIVRELATION)->exec();
        $this->dao->delete()->from(TABLE_PRIV)->exec();
        $this->dao->delete()->from(TABLE_CONFIG)->where('module')->eq('priv')->exec();
        $this->dbh->exec('ALTER TABLE ' . TABLE_PRIV . ' auto_increment = 1');

        $viewModules = array();
        $this->loadModel('setting');
        foreach($resource as $moduleName => $methods)
        {
            $groupKey = $moduleName;
            $view     = isset($this->lang->navGroup->{$groupKey}) ? $this->lang->navGroup->{$groupKey} : $moduleName;
            $viewModules[$view][] = $moduleName;

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
        return $this->dao->select('t1.*,t2.name,t2.desc')->from(TABLE_PRIV)->alias('t1')
            ->leftJoin(TABLE_PRIVLANG)->alias('t2')->on('t1.id=t2.priv')
            ->where('t1.id')->eq($privID)
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
        return $this->dao->select('t1.*,t2.name,t2.desc')->from(TABLE_PRIV)->alias('t1')
            ->leftJoin(TABLE_PRIVLANG)->alias('t2')->on('t1.id=t2.priv')
            ->where('t1.id')->in($privIdList)
            ->andWhere('t2.lang')->eq($this->app->getClientLang())
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
        return $this->dao->select('t1.*,t2.name,t2.desc')->from(TABLE_PRIV)->alias('t1')
            ->leftJoin(TABLE_PRIVLANG)->alias('t2')->on('t1.id=t2.priv')
            ->where('t1.module')->in($modules)
            ->andWhere('t2.lang')->eq($this->app->getClientLang())
            ->orderBy('`order`')
            ->fetchGroup('module', 'id');
    }

    /**
     * Get privs list by module.
     *
     * @param  string $view
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getPrivsListByView($view = '', $pager = null)
    {
        $this->loadModel('setting');
        $views   = empty($view) ? $this->setting->getItem("owner=system&module=priv&key=views") : $view;
        $views   = explode(',', $views);
        $modules = '';
        foreach($views as $view) $modules .= ',' . $this->setting->getItem("owner=system&module=priv&key={$view}Modules");
        $modules = trim($modules, ',');

        $privs = $this->dao->select("t1.*,t2.name,t2.desc, INSTR('$modules', t1.`module`) as moduleOrder")->from(TABLE_PRIV)->alias('t1')
            ->leftJoin(TABLE_PRIVLANG)->alias('t2')->on('t1.id=t2.priv')
            ->leftJoin(TABLE_PRIVPACKAGE)->alias('t3')->on('t1.package=t3.id')
            ->where('1=1')
            ->beginIF(!empty($view))->andWhere('t1.module')->in($modules)->fi()
            ->andWhere('t2.lang')->eq($this->app->getClientLang())
            ->orderBy("moduleOrder asc, t3.order asc, `order` asc")
            ->page($pager)
            ->fetchAll('id');
        return $privs;
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
            $privQuery = preg_replace(array('/`recommendPrivs`[ ]+=/', '/`recommendPrivs`[ ]+LIKE/', '/`recommendPrivs`[ ]+=/', '/`recommendPrivs`[ ]+!=/', '/`recommendPrivs`[ ]+NOT LIKE/'), array('`recommendPrivs` IN', '`recommendPrivs` IN', '`recommendPrivs` IN', '`recommendPrivs` NOT IN', '`recommendPrivs` NOT IN'), $privQuery);
            foreach($out[1] as $priv)
            {
                $priv  = str_replace('%', '', $priv);
                $privs = $this->dao->select('priv,priv')->from(TABLE_PRIVRELATION)->where('relationPriv')->eq($priv)->andWhere('type')->eq('recommend')->fetchPairs();
                $privs = implode("','", $privs);
                $privQuery = preg_replace("/`recommendPrivs`([^']+)'([%]?{$priv}[%]?)'/Ui", (!empty($privs) ? "t1.`id`$1('{$privs}')" : '0=1'), $privQuery);
            }
        }
        if(strpos($privQuery, '`dependPrivs`') !== false)
        {
            preg_match_all("/`dependPrivs`[^']+'([^']+)'/Ui", $privQuery, $out);
            $privQuery = preg_replace(array('/`dependPrivs`[ ]+=/', '/`dependPrivs`[ ]+LIKE/', '/`dependPrivs`[ ]+=/', '/`dependPrivs`[ ]+!=/', '/`dependPrivs`[ ]+NOT LIKE/'), array('`dependPrivs` IN', '`dependPrivs` IN', '`dependPrivs` IN', '`dependPrivs` NOT IN', '`dependPrivs` NOT IN'), $privQuery);
            foreach($out[1] as $priv)
            {
                $priv  = str_replace('%', '', $priv);
                $privs = $this->dao->select('priv,priv')->from(TABLE_PRIVRELATION)->where('relationPriv')->eq($priv)->andWhere('type')->eq('depend')->fetchPairs();
                $privs = implode(',', $privs);
                $privQuery = preg_replace("/`dependPrivs`([^']+)'([%]?{$priv}[%]?)'/Ui", (!empty($privs) ? "t1.`id`$1('{$privs}')" : '0=1'), $privQuery);
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
            ->leftJoin(TABLE_PRIVPACKAGE)->alias('t3')->on('t1.package=t3.id')
            ->where('1=1')
            ->andWhere($privQuery)
            ->andWhere('t2.lang')->eq($this->app->getClientLang())
            ->orderBy("moduleOrder asc, t3.order asc, `order` asc")
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get priv relation.
     *
     * @param  int    $priv
     * @param  string $type    depend|recommend
     * @param  string $module
     * @access public
     * @return array
     */
    public function getPrivRelation($priv, $type = '', $module = '')
    {
        $relations = $this->dao->select('t1.type,t2.*,t3.name')->from(TABLE_PRIVRELATION)->alias('t1')
            ->leftJoin(TABLE_PRIV)->alias('t2')->on('t1.relationPriv=t2.id')
            ->leftJoin(TABLE_PRIVLANG)->alias('t3')->on('t2.id=t3.priv')
            ->where('t1.priv')->in($priv)
            ->beginIF(!empty($type))->andWhere('t1.type')->eq($type)->fi()
            ->beginIF($module)->andWhere('t2.module')->eq($module)->fi()
            ->fetchGroup('type', 'id');

        if(!empty($type)) return zget($relations, $type, array());
        return $relations;
    }

    /**
     * Get priv relation.
     *
     * @param  int    $priv
     * @param  string $type
     * @param  string $module
     * @access public
     * @return array
     */
    public function getPrivRelationPairs($priv, $type = '', $module = '')
    {
        return $this->dao->select('t2.id,t3.name')->from(TABLE_PRIVRELATION)->alias('t1')
            ->leftJoin(TABLE_PRIV)->alias('t2')->on('t1.relationPriv=t2.id')
            ->leftJoin(TABLE_PRIVLANG)->alias('t3')->on('t2.id=t3.priv')
            ->where('t1.priv')->in($priv)
            ->beginIF(!empty($type))->andWhere('t1.type')->eq($type)->fi()
            ->beginIF($module)->andWhere('t2.module')->eq($module)->fi()
            ->fetchPairs();
    }

    /**
     * Get priv relation.
     *
     * @param  array  $privs
     * @param  string $type    depend|recommend
     * @access public
     * @return array
     */
    public function getPrivRelationsByIdList($privs, $type = '')
    {
        $privs = $this->dao->select('t1.priv,t3.name,t1.type,t1.relationPriv')->from(TABLE_PRIVRELATION)->alias('t1')
            ->leftJoin(TABLE_PRIV)->alias('t2')->on('t1.relationPriv=t2.id')
            ->leftJoin(TABLE_PRIVLANG)->alias('t3')->on('t2.id=t3.priv')
            ->where('t1.priv')->in($privs)
            ->beginIF(!empty($type))->andWhere('t1.type')->eq($type)->fi()
            ->fetchGroup('type');
        $relationPrivs = array();
        foreach($privs as $type => $typePrivs)
        {
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

        $deleteModuleSQL = $this->dao->select('id')->from(TABLE_PRIV)->where('module')->in(array_keys($data->relation))->get();
        $this->dao->delete()->from(TABLE_PRIVRELATION)
            ->where('priv')->in($privIdList)
            ->andWhere('type')->eq($type)
            ->andWhere('relationPriv IN(' . $deleteModuleSQL . ')')
            ->exec();

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
                    $this->dao->insert(TABLE_PRIVRELATION)->data($relation)->exec();
                }
            }
        }
        return true;
    }

    /**
     * get priv
     *
     * @param   int     $privID
     * @param   string  $lang
     * @return  object
     **/
    public function getPrivInfo($priv,$lang)
    {
        $privInfo = $this->dao->select("t1.priv, t1.name, t1.desc, t2.module, t2.package")->from(TABLE_PRIVLANG)->alias('t1')
            ->leftJoin(TABLE_PRIV)->alias('t2')
            ->on("t1.priv = t2.id")
            ->where('t1.priv')->eq($priv)
            ->andWHere('t1.lang')->eq($lang)
            ->fetch();

        return $privInfo;
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

        $packages = $this->getPrivPackagePairs();

        $privs = $this->getPrivLangPairs();

        $this->config->group->priv->search['params']['view']['values']           = array('' => '') + $views;
        $this->config->group->priv->search['params']['module']['values']         = array('' => '') + $modules;
        $this->config->group->priv->search['params']['package']['values']        = array('' => '') + $packages;
        $this->config->group->priv->search['params']['recommendPrivs']['values'] = array('' => '') + $privs;
        $this->config->group->priv->search['params']['dependPrivs']['values']    = array('' => '') + $privs;

        $this->loadModel('search')->setSearchParams($this->config->group->priv->search);
    }

    /**
     * Get priv group by module.
     *
     * @param  array    $moduleList
     * @access public
     * @return array
     */
    public function getPrivGroup($moduleList = array())
    {
        return $this->dao->select('*')->from(TABLE_PRIV)
            ->beginIF(!empty($moduleList))->where('module')->in($moduleList)->fi()
            ->orderBy('order_asc')
            ->fetchGroup('module');
    }

    /**
     * Get all priv's lang pairs.
     *
     * @access public
     * @return array
     */
    public function getPrivLangPairs()
    {
        return $this->dao->select('priv,name')->from(TABLE_PRIVLANG)
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
}
