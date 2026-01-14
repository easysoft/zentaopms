<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class groupModelTest extends baseTest
{
    protected $moduleName = 'group';
    protected $className  = 'model';

    /**
     * Test create a group.
     *
     * @param  array  $group
     * @access public
     * @return object
     */
    public function createObject($group)
    {
        $groupID = $this->instance->create((object)$group);

        if(dao::isError()) return dao::getError();
        return $this->instance->getById($groupID);
    }

    /**
     * Update a group.
     *
     * @param  int    $groupID
     * @param  array  $group
     * @access public
     * @return void
     */
    public function updateTest($groupID, $group)
    {
        $this->instance->update($groupID, (object)$group);
        if(dao::isError()) return dao::getError();

        return $this->instance->getByID($groupID);
    }

    /**
     * 获取group信息，方便ztf检查
     * Get group for ztf
     *
     * @param  int    $groupID
     * @access private
     * @return object
     */
    private function getGroup($groupID)
    {
        $group = $this->instance->getByID($groupID);

        $privs = $this->instance->getPrivs($groupID);
        $privList = array();
        foreach($privs as $module => $priv)
        {
            foreach($priv as $method => $method) $privList[] = $module . '-' . $method;
        }

        $users = $this->instance->getUserPairs($groupID);
        $group->privs = implode('|', $privList);
        $group->users = implode('|', array_keys($users));

        return $group;
    }

    /**
     * 获取group信息，方便ztf检查
     * Get group for ztf
     *
     * @param  int    $groupID
     * @access private
     * @return object
     */
    public function insertPrivsTest($privs)
    {
        $result = $this->instance->insertPrivs($privs);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    public function getGroupPrivsTest($groupId = 0)
    {
        if($groupId == 0)
        {
            $privs = $this->instance->dao->select('*')->from(TABLE_GROUPPRIV)->fetchGroup('group');
            foreach($privs as $group => $privList)
            {
                foreach($privList as $key => $priv) $privs[$group][$key] = $priv->module . '-' . $priv->method;
            }
            return $privs;
        }
        else
        {
            $privs = $this->instance->dao->select('*')->from(TABLE_GROUPPRIV)->where('group')->eq($groupId)->fetchAll();
            $result = array();
            foreach($privs as $key => $priv) $result[$key] = $priv->module . '-' . $priv->method;
            return $result;
        }
    }


    /**
     * Copy a group.
     *
     * @param  int    $groupID
     * @param  array  $group
     * @access public
     * @return void
     */
    public function copyTest($groupID, $group, $options = array())
    {
        $newGroupID = $this->instance->copy($groupID, (object)$group, $options);
        if(dao::isError()) return dao::getError();

        return $this->getGroup($newGroupID);
    }

    /**
     * Copy privileges.
     *
     * @param  int    $fromGroupID
     * @param  int    $toGroupID
     * @access public
     * @return void
     */
    public function copyPrivTest($fromGroupID, $toGroupID)
    {
        $this->instance->copyPriv($fromGroupID, $toGroupID);

        if(dao::isError()) return dao::getError();

        $fromPrivs = $this->instance->getPrivs($fromGroupID);
        $toPrivs   = $this->instance->getPrivs($toGroupID);

        $result = true;
        foreach($fromPrivs as $group => $privs)
        {
            foreach($privs as $key => $priv)
            {
                if(!isset($toPrivs[$group][$key]) or $toPrivs[$group][$key] != $fromPrivs[$group][$key])
                {
                    $result = false;
                    break;
                }
            }
        }
        return $result;
    }

    /**
     * Copy user.
     *
     * @param  int    $fromGroup
     * @param  int    $toGroup
     * @access public
     * @return void
     */
    public function copyUserTest($fromGroupID, $toGroupID)
    {
        $this->instance->copyUser($fromGroupID, $toGroupID);

        if(dao::isError()) return dao::getError();

        $fromUsers = $this->instance->getUserPairs($fromGroupID);
        $toUsers   = $this->instance->getUserPairs($toGroupID);

        $result = true;
        foreach($fromUsers as $account => $name)
        {
                if(!isset($toUsers[$account]) or $toUsers[$account] != $fromUsers[$account])
                {
                    $result = false;
                    break;
                }
        }
        return $result;
    }

    /**
     * Get group lists.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getListTest($projectID = 0)
    {
        $groups = $this->instance->getList($projectID);

        if(dao::isError()) return dao::getError();

        return $groups;
    }

    /**
     * Get group pairs.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getPairsTest($projectID = 0)
    {
        $groups = $this->instance->getPairs($projectID);

        if(dao::isError()) return dao::getError();

        return $groups;
    }

    /**
     * Get group by id.
     *
     * @param  int    $groupID
     * @access public
     * @return object
     */
    public function getByIDTest($groupID)
    {
        $group = $this->instance->getByID($groupID);

        if(dao::isError()) return dao::getError();

        return $group;
    }

    /**
     * Get group by account.
     *
     * @param  string $account
     * @param  bool   $allVision
     * @access public
     * @return array
     */
    public function getByAccountTest($account, $allVision = false)
    {
        $groups = $this->instance->getByAccount($account, $allVision);

        if(dao::isError()) return dao::getError();

        return $groups;
    }

    /**
     * Get the account number in the group.
     *
     * @param  array  $groupIdList
     * @access public
     * @return array
     */
    public function getGroupAccountsTest($groupIdList)
    {
        $objects = $this->instance->getGroupAccounts($groupIdList);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get privileges of a groups.
     *
     * @param  int    $groupID
     * @access public
     * @return array
     */
    public function getPrivsTest($groupID)
    {
        $objects = $this->instance->getPrivs($groupID);
        $result = array();
        foreach($objects as $module => $methods)
        {
            $result[$module] = implode('|', $methods);
        }

        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Get user pairs of a group.
     *
     * @param  int    $groupID
     * @access public
     * @return array
     */
    public function getUserPairsTest($groupID)
    {
        $objects = $this->instance->getUserPairs($groupID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get all group memebers.
     *
     * @access public
     * @return array
     */
    public function getAllGroupMembersTest()
    {
        $groupMembers = $this->instance->getAllGroupMembers();

        if(dao::isError()) return dao::getError();

        $result = array();
        foreach($groupMembers as $group => $members)
        {
            $result[$group] = implode('|', array_keys($members));
        }

        return $result;
    }

    /**
     * Get object for manage admin group.
     *
     * @access public
     * @return array
     */
    public function getObjectForAdminGroupTest()
    {
        list($programs, $projects, $products, $executions) = $this->instance->getObjectForAdminGroup();

        if(dao::isError()) return dao::getError();

        return array(
            'programs'   => implode('|', $programs),
            'projects'   => implode('|', $projects),
            'products'   => implode('|', $products),
            'executions' => implode('|', $executions)
        );
    }

    /**
     * 测试getAdmins方法。
     * Test getAdmins method.
     *
     * @param  array  $idList
     * @param  string $field
     * @access public
     * @return array
     */
    public function getAdminsTest(array $idList, string $field = 'programs'): array
    {
        return $this->instance->getAdmins($idList, $field);
    }

    /**
     * Remove a group.
     *
     * @param  int    $groupID
     * @param  null   $null      compatible with that of model::delete()
     * @access public
     * @return void
     */
    public function removeTest($groupID)
    {
        $this->instance->remove($groupID);

        if(dao::isError()) return dao::getError();

        return true;
    }

    /**
     * Verify remove operation completeness.
     *
     * @param  int    $groupID
     * @access public
     * @return object
     */
    public function verifyRemoveCompleteTest($groupID)
    {
        $this->instance->remove($groupID);

        if(dao::isError()) return dao::getError();

        // 检查group表中是否还存在该记录
        $groupExists = $this->instance->dao->select('count(*)')->from(TABLE_GROUP)->where('id')->eq($groupID)->fetch('count(*)');

        // 检查usergroup表中是否还存在该组的关联记录
        $usergroupExists = $this->instance->dao->select('count(*)')->from(TABLE_USERGROUP)->where('`group`')->eq($groupID)->fetch('count(*)');

        // 检查grouppriv表中是否还存在该组的权限记录
        $groupprivExists = $this->instance->dao->select('count(*)')->from(TABLE_GROUPPRIV)->where('`group`')->eq($groupID)->fetch('count(*)');

        $result = new stdclass();
        $result->groupExists = $groupExists;
        $result->usergroupExists = $usergroupExists;
        $result->groupprivExists = $groupprivExists;

        return $result;
    }

    /**
     * Update privilege of a group.
     *
     * @param  int    $groupID
     * @param  string $nav
     * @param  string $version
     * @param  array  $actions
     * @access public
     * @return bool
     */
    public function updatePrivByGroupTest($groupID, $nav, $version = '', $actions = array())
    {
        global $app;
        $app->post->set('actions', $actions);

        $this->instance->updatePrivByGroup($groupID, $nav, $version);

        if(dao::isError()) return dao::getError();

        return $this->getPrivsTest($groupID);
    }

    /**
     * Update privilege by module.
     *
     * @access public
     * @return void
     */
    public function updatePrivByModuleTest($module, $groups, $actions)
    {
        global $app;
        $app->post->set('module', $module);
        $app->post->set('groups', $groups);
        $app->post->set('actions', $actions);

        $this->instance->updatePrivByModule();

        if(dao::isError()) return dao::getError();

        $result = array();
        foreach($groups as $group)
        {
            $result[$group] = $this->getPrivsTest($group);
        }

        return $result;
    }

    /**
     * Update users.
     *
     * @param  int    $groupID
     * @access public
     * @return void
     */
    public function updateUserTest($groupID)
    {
        $this->instance->updateUser($groupID);

        if(dao::isError()) return dao::getError();

        $users = $this->instance->getUserPairs($groupID);

        return $users;
    }

    /**
     * Get project admins.
     *
     * @access public
     * @return array
     */
    public function getProjectAdminsTest()
    {
        $admins = $this->instance->getProjectAdmins();

        if(dao::isError()) return dao::getError();

        return $admins;
    }

    /**
     * Update project admins.
     *
     * @param  int    $groupID
     * @param  array  $formData
     * @access public
     * @return void
     */
    public function updateViewTest($groupID, $formData)
    {
        $this->instance->updateView($groupID, $formData);

        if(dao::isError()) return dao::getError();
        $group = $this->instance->getByID($groupID);
        $acl   = $group->acl;
        if(isset($acl['actions']))
        {
            foreach($acl['actions'] as $module => $methods)
            {
                $acl['actions'][$module] = implode('|', $methods);
            }
        }

        return $acl;
    }

    /**
     * Update project admins.
     *
     * @param  array  $formData
     * @access public
     * @return void
     */
    public function updateProjectAdminTest($formData)
    {
        $this->instance->updateProjectAdmin($formData);

        if(dao::isError()) return dao::getError();
        return $this->getProjectAdminsTest();
    }

    /**
     * Sort resource.
     *
     * @access public
     * @return void
     */
    public function sortResourceTest()
    {
        $this->instance->sortResource();
        return $this->instance->lang->resource;
    }

    /**
     * Load resource lang.
     *
     * @access public
     * @return void
     */
    public function loadResourceLangTest()
    {
        $this->instance->loadResourceLang();
        return $this->instance->lang;
    }

    /**
     * Get priv list by nav.
     *
     * @param  string $nav
     * @param  string $version
     * @access public
     * @return array
     */
    public function getPrivsByNavTest($nav, $version = '')
    {
        $privList = $this->instance->getPrivsByNav($nav, $version);
        $privList = array_combine(array_keys($privList), array_keys($privList));

        return $privList;
    }

    /**
     * Get privs by group.
     *
     * @param  int    $groupID
     * @access public
     * @return array
     */
    public function getPrivsByGroupTest($groupID)
    {
        $privList = $this->instance->getPrivsByGroup($groupID);

        return $privList;
    }

    /**
     * Get privs after version.
     *
     * @param  string $version
     * @access public
     * @return array
     */
    public function getPrivsAfterVersionTest($version = '')
    {
        $privList = $this->instance->getPrivsAfterVersion($version);
        $privList = explode(',', $privList);
        $privList = array_combine($privList, $privList);

        return $privList;
    }

    /**
     * Get related privs.
     *
     * @param  array $allPrivList
     * @param  array $selectedPrivList
     * @param  array $recommendSelect
     * @access public
     * @return array
     */
    public function getRelatedPrivsTest($allPrivList, $selectedPrivList, $recommendSelect)
    {
        $privList = $this->instance->getRelatedPrivs($allPrivList, $selectedPrivList, $recommendSelect);
        $depend = array();
        foreach($privList['depend'] as $priv)
        {
            $depend[$priv['id']] = $priv['id'];
        }

        $recommend = array();
        foreach($privList['recommend'] as $priv)
        {
            $recommend[$priv['id']] = $priv['id'];
        }

        return array('depend' => $depend, 'recommend' => $recommend);
    }

    /**
     * Test getProgramsForAdminGroup method.
     *
     * @access public
     * @return array
     */
    public function getProgramsForAdminGroupTest()
    {
        $reflectionClass = new ReflectionClass($this->instance);
        $method = $reflectionClass->getMethod('getProgramsForAdminGroup');
        $method->setAccessible(true);
        $result = $method->invoke($this->instance);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getProductsForAdminGroup method.
     *
     * @param  array  $programs
     * @access public
     * @return array
     */
    public function getProductsForAdminGroupTest($programs = array())
    {
        $reflectionClass = new ReflectionClass($this->instance);
        $method = $reflectionClass->getMethod('getProductsForAdminGroup');
        $method->setAccessible(true);
        $result = $method->invoke($this->instance, $programs);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getProductsForAdminGroup method return count.
     *
     * @param  array  $programs
     * @access public
     * @return int
     */
    public function getProductsForAdminGroupCountTest($programs = array())
    {
        $result = $this->getProductsForAdminGroupTest($programs);
        if(dao::isError()) return dao::getError();

        return count($result);
    }

    /**
     * Test processDepends method.
     *
     * @param  array $depends
     * @param  array $privs
     * @param  array $excludes
     * @param  array $processedPrivs
     * @access public
     * @return array
     */
    public function processDependsTest($depends, $privs, $excludes, $processedPrivs = array())
    {
        $reflectionClass = new ReflectionClass($this->instance);
        $method = $reflectionClass->getMethod('processDepends');
        $method->setAccessible(true);
        $result = $method->invoke($this->instance, $depends, $privs, $excludes, $processedPrivs);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test checkNavSubset method.
     *
     * @param  string $nav
     * @param  string $subset
     * @access public
     * @return bool
     */
    public function checkNavSubsetTest($nav, $subset)
    {
        $reflectionClass = new ReflectionClass($this->instance);
        $method = $reflectionClass->getMethod('checkNavSubset');
        $method->setAccessible(true);
        $result = $method->invoke($this->instance, $nav, $subset);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getPrivsByParents method.
     *
     * @param  string $selectedSubset
     * @param  string $selectedPackages
     * @access public
     * @return array
     */
    public function getPrivsByParentsTest($selectedSubset, $selectedPackages = '')
    {
        $result = $this->instance->getPrivsByParents($selectedSubset, $selectedPackages);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}
