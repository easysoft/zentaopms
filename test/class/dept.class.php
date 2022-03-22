<?php
class deptTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('dept');
    }

    /**
     * function getByID test by dept
     *
     * @param  string $deptID
     * @access public
     * @return array
     */
    public function getByIDTest($deptID)
    {
        $objects = $this->objectModel->getByID($deptID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * function getDeptPairs test by dept
     *
     * @param  string $deptID
     * @param  string $count
     * @access public
     * @return array
     */
    public function getDeptPairsTest($deptID, $count)
    {
        $objects = $this->objectModel->getDeptPairs($deptID);

        if(dao::isError()) return dao::getError();
        if($count == '1')  return count($objects);

        return $objects;
    }

    /**
     * function buildMenuQuery test by dept
     *
     * @param  string $rootDeptID
     * @access public
     * @return array
     */
    public function buildMenuQueryTest($rootDeptID)
    {
        $objects = $this->objectModel->buildMenuQuery($rootDeptID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * function getOptionMenu test by dept
     *
     * @param  string $rootDeptID
     * @param  string $count
     * @access public
     * @return array
     */
    public function getOptionMenuTest($rootDeptID, $count)
    {
        $objects = $this->objectModel->getOptionMenu($rootDeptID);

        if(dao::isError()) return dao::getError();
        if($count == '1')  return count($objects);

        return $objects;
    }

    /**
     * function getTreeMenu test by dept
     *
     * @param  string $rootDeptID
     * @param  array  $userFunc
     * @param  int    $param
     * @access public
     * @return string
     */
    public function getTreeMenuTest($rootDeptID, $userFunc, $param = 0)
    {
        $objects = $this->objectModel->getTreeMenu($rootDeptID, $userFunc, $param = 0);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * function update test by dept
     *
     * @param  string $deptID
     * @param  array  $param
     * @access public
     * @return array
     */
    public function updateTest($deptID, $param = array())
    {
        global $tester;

        $createFields = array('parent' => '', 'name' => '', 'manager' => '');

        foreach($createFields as $field => $defaultValue) $_POST[$field] = $defaultValue;
        foreach($param as $key => $value) $_POST[$key] = $value;

        $this->objectModel->update($deptID);

        unset($_POST);

        $objects = $tester->dao->select('*')->from(TABLE_DEPT)->where('id')->eq($deptID)->fetchAll('id');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * function createManageLink test by dept
     *
     * @param  string $deptID
     * @access public
     * @return string
     */
    public function createManageLinkTest($deptID)
    {
        $dept    = $this->objectModel->getByID($deptID);
        $objects = $this->objectModel->createManageLink($dept);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * function createMemberLink test by dept
     *
     * @param  string $deptID
     * @access public
     * @return string
     */
    public function createMemberLinkTest($deptID)
    {
        $dept    = $this->objectModel->getByID($deptID);
        $objects = $this->objectModel->createMemberLink($dept);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * function createGroupManageMemberLink test by dept
     *
     * @param  string $deptID
     * @param  string $groupID
     * @access public
     * @return string
     */
    public function createGroupManageMemberLinkTest($deptID, $groupID)
    {
        $dept    = $this->objectModel->getByID($deptID);
        $objects = $this->objectModel->createGroupManageMemberLink($dept, $groupID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * function createManageProjectAdminLink test by dept
     *
     * @param  string $deptID
     * @param  string $groupID
     * @access public
     * @return string
     */
    public function createManageProjectAdminLinkTest($deptID, $groupID)
    {
        $dept    = $this->objectModel->getByID($deptID);
        $objects = $this->objectModel->createManageProjectAdminLink($dept, $groupID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * function getSons test by dept
     *
     * @param  string $deptID
     * @param  string $count
     * @access public
     * @return array
     */
    public function getSonsTest($deptID, $count)
    {
        $objects = $this->objectModel->getSons($deptID);

        if(dao::isError()) return dao::getError();
        if($count == '1')  return count($objects);

        return $objects;
    }

    public function getAllChildIdTest($deptID, $count)
    {
        $objects = $this->objectModel->getAllChildId($deptID);

        if(dao::isError()) return dao::getError();
        if($count == '1')  return count($objects);

        return $objects;
    }

    public function getParentsTest($deptID)
    {
        $objects = $this->objectModel->getParents($deptID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function updateOrderTest($orders)
    {
        $objects = $this->objectModel->updateOrder($orders);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function manageChildTest($parentDeptID, $childs)
    {
        $objects = $this->objectModel->manageChild($parentDeptID, $childs);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getUsersTest($browseType = 'inside', $deptID = 0, $pager = null, $orderBy = 'id')
    {
        $objects = $this->objectModel->getUsers($browseType = 'inside', $deptID = 0, $pager = null, $orderBy = 'id');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getDeptUserPairsTest($deptID = 0, $key = 'account', $type = 'inside', $params = '')
    {
        $objects = $this->objectModel->getDeptUserPairs($deptID = 0, $key = 'account', $type = 'inside', $params = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function deleteTest($deptID, $null = null)
    {
        $objects = $this->objectModel->delete($deptID, $null = null);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function fixDeptPathTest()
    {
        $objects = $this->objectModel->fixDeptPath();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getDataStructureTest()
    {
        $objects = $this->objectModel->getDataStructure();

        if(dao::isError()) return dao::getError();

        return $objects;
    }
}
