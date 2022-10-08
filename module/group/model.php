<?php
/**
 * The model file of group module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
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
     * @access public
     * @return array
     */
    public function getByAccount($account)
    {
        return $this->dao->select('t2.*')->from(TABLE_USERGROUP)->alias('t1')
            ->leftJoin(TABLE_GROUP)->alias('t2')
            ->on('t1.`group` = t2.id')
            ->where('t1.account')->eq($account)
            ->andWhere('t2.project')->eq(0)
            ->andWhere('t2.vision')->eq($this->config->vision)
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

                $object->name = (isset($objects[$object->project]) and $this->config->systemMode == 'new') ? $objects[$object->project]->name . '/' . $object->name : $object->name;
                $executions[$object->id] = $object->name;
            }
        }

        foreach($productList as $id => $product)
        {
            if(isset($programs[$product->program]) and $this->config->systemMode == 'new') $product->name = $programs[$product->program] . '/' . $product->name;
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

            /* Replace new. */
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

        /* Delete old. */
        $this->dao->delete()->from(TABLE_GROUPPRIV)->where('`group`')->eq($groupID)->andWhere('module')->in($this->getMenuModules($menu))->exec();

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

        $members      = $this->post->members      ? $this->post->members      : array();
        $programs     = $this->post->program      ? $this->post->program      : array();
        $projects     = $this->post->project      ? $this->post->project      : array();
        $products     = $this->post->product      ? $this->post->product      : array();
        $executions   = $this->post->execution    ? $this->post->execution    : array();
        $programAll   = $this->post->programAll   ? $this->post->programAll   : '';
        $projectAll   = $this->post->projectAll   ? $this->post->projectAll   : '';
        $productAll   = $this->post->productAll   ? $this->post->productAll   : '';
        $executionAll = $this->post->executionAll ? $this->post->executionAll : '';

        foreach($members as $lineID => $accounts)
        {
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
     * @param  string    $menu
     * @access public
     * @return void
     */
    public function getMenuModules($menu)
    {
        $modules = array();
        foreach($this->lang->resource as $moduleName => $action)
        {
            if($this->checkMenuModule($menu, $moduleName)) $modules[] = $moduleName;
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
}
