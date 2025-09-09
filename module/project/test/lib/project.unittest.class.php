<?php
declare(strict_types = 1);
class Project
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('project');
    }

    /**
     * Test getAclListByObjectType method.
     *
     * @param  string $objectType
     * @access public
     * @return mixed
     */
    public function getAclListByObjectTypeTest($objectType = null)
    {
        $result = $this->objectModel->getAclListByObjectType($objectType);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getListByAcl method.
     *
     * @param  string $acl
     * @param  array  $idList
     * @access public
     * @return mixed
     */
    public function getListByAclTest($acl = '', $idList = array())
    {
        $result = $this->objectModel->getListByAcl($acl, $idList);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getTeamListByType method.
     *
     * @param  string $type
     * @access public
     * @return mixed
     */
    public function getTeamListByTypeTest($type = '')
    {
        $result = $this->objectModel->getTeamListByType($type);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getInvolvedListByCurrentUser method.
     *
     * @param  string $fields
     * @access public
     * @return mixed
     */
    public function getInvolvedListByCurrentUserTest($fields = 't1.*')
    {
        $result = $this->objectModel->getInvolvedListByCurrentUser($fields);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test leftJoinInvolvedTable method.
     *
     * @param  object $stmt
     * @access public
     * @return mixed
     */
    public function leftJoinInvolvedTableTest($stmt = null)
    {
        global $tester;
        if($stmt === null)
        {
            $stmt = $tester->dao->select('*')->from(TABLE_PROJECT)->alias('t1');
        }
        
        $result = $this->objectModel->leftJoinInvolvedTable($stmt);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}