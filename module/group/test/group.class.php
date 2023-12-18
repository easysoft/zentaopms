<?php
class groupTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('group');
    }

    /**
     * Test create a group.
     *
     * @param  array  $group
     * @access public
     * @return object
     */
    public function createObject($group)
    {
        $groupID = $this->objectModel->create((object)$group);

        if(dao::isError()) return dao::getError();
        return $this->objectModel->getById($groupID);
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
        $this->objectModel->update($groupID, (object)$group);
        if(dao::isError()) return dao::getError();

        return $this->objectModel->getByID($groupID);
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
        $group = $this->objectModel->getByID($groupID);

        $privs = $this->objectModel->getPrivs($groupID);
        $privList = array();
        foreach($privs as $module => $priv)
        {
            foreach($priv as $method => $method) $privList[] = $module . '-' . $method;
        }

        $users = $this->objectModel->getUserPairs($groupID);
        $group->privs = implode('|', $privList);
        $group->users = implode('|', array_keys($users));

        return $group;
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
        $newGroupID = $this->objectModel->copy($groupID, (object)$group, $options);
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
        $this->objectModel->copyPriv($fromGroupID, $toGroupID);

        if(dao::isError()) return dao::getError();

        $fromPrivs = $this->objectModel->getPrivs($fromGroupID);
        $toPrivs   = $this->objectModel->getPrivs($toGroupID);

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
        $this->objectModel->copyUser($fromGroupID, $toGroupID);

        if(dao::isError()) return dao::getError();

        $fromUsers = $this->objectModel->getUserPairs($fromGroupID);
        $toUsers   = $this->objectModel->getUserPairs($toGroupID);

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
        $groups = $this->objectModel->getList($projectID);

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
        $groups = $this->objectModel->getPairs($projectID);

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
        $group = $this->objectModel->getByID($groupID);

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
        $groups = $this->objectModel->getByAccount($account, $allVision);

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
        $objects = $this->objectModel->getGroupAccounts($groupIdList);

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
        $objects = $this->objectModel->getPrivs($groupID);
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
        $objects = $this->objectModel->getUserPairs($groupID);

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
        $groupMembers = $this->objectModel->getAllGroupMembers();

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
        list($programs, $projects, $products, $executions) = $this->objectModel->getObjectForAdminGroup();

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
        return $this->objectModel->getAdmins($idList, $field);
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
        $this->objectModel->remove($groupID);

        if(dao::isError()) return dao::getError();

        return true;
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
        $this->objectModel->updatePrivByGroup($groupID, $nav, $version);

        if(dao::isError()) return dao::getError();

        return $this->getPrivsTest($groupID);
    }

    /**
     * Update privilege by module.
     *
     * @access public
     * @return void
     */
    public function updatePrivByModuleTest()
    {
        $objects = $this->objectModel->updatePrivByModule();

        if(dao::isError()) return dao::getError();

        return $objects;
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
        $objects = $this->objectModel->updateUser($groupID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get project admins.
     *
     * @access public
     * @return array
     */
    public function getProjectAdminsTest()
    {
        $admins = $this->objectModel->getProjectAdmins();

        if(dao::isError()) return dao::getError();

        return $admins;
    }

    /**
     * Update project admins.
     *
     * @param  int    $groupID
     * @access public
     * @return void
     */
    public function updateProjectAdminTest($groupID)
    {
        $objects = $this->objectModel->updateProjectAdmin($groupID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Sort resource.
     *
     * @access public
     * @return void
     */
    public function sortResourceTest()
    {
        $objects = $this->objectModel->sortResource();

        if(dao::isError()) return dao::getError();

        return $objects;
    }
}
