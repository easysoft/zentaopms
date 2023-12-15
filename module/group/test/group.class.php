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
        $object = $this->objectModel->update($groupID, (object)$group);
        if(dao::isError()) return dao::getError();

        return $this->objectModel->getByID($groupID);
    }

    /**
     * Copy a group.
     *
     * @param  int    $groupID
     * @param  array  $group
     * @access public
     * @return void
     */
    public function copyTest($groupID, $group)
    {
        $objects = $this->objectModel->copy($groupID, (object)$group, array());
        if(dao::isError()) return dao::getError();

        $newGroupID = $this->objectModel->dao->lastInsertID();
        return $this->objectModel->getByID($newGroupID);
    }

    /**
     * Copy privileges.
     *
     * @param  string    $fromGroup
     * @param  string    $toGroup
     * @access public
     * @return void
     */
    public function copyPrivTest($fromGroup, $toGroup)
    {
        $objects = $this->objectModel->copyPriv($fromGroup, $toGroup);

        if(dao::isError()) return dao::getError();

        $fromPrivs = $this->objectModel->getPrivs($fromGroup);
        $toPrivs   = $this->objectModel->getPrivs($toGroup);

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
     * @param  string    $fromGroup
     * @param  string    $toGroup
     * @access public
     * @return void
     */
    public function copyUserTest($fromGroup, $toGroup)
    {
        $objects = $this->objectModel->copyUser($fromGroup, $toGroup);

        if(dao::isError()) return dao::getError();

        $fromUsers = $this->objectModel->getUserPairs($fromGroup);
        $toUsers   = $this->objectModel->getUserPairs($toGroup);

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
        $objects = $this->objectModel->getList($projectID = 0);

        if(dao::isError()) return dao::getError();

        return $objects;
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
        $objects = $this->objectModel->getPairs($projectID = 0);

        if(dao::isError()) return dao::getError();

        return $objects;
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
        $objects = $this->objectModel->getByID($groupID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get group by account.
     *
     * @param  string    $account
     * @access public
     * @return array
     */
    public function getByAccountTest($account)
    {
        $objects = $this->objectModel->getByAccount($account);

        if(dao::isError()) return dao::getError();

        return $objects;
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

        if(dao::isError()) return dao::getError();

        return $objects;
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
     * @access public
     * @return bool
     */
    public function updatePrivByGroupTest($groupID, $menu, $version)
    {
        $objects = $this->objectModel->updatePrivByGroup($groupID, $menu, $version);

        if(dao::isError()) return dao::getError();

        return $objects;
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
