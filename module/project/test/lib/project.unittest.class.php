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

    /**
     * Test appendInvolvedCondition method.
     *
     * @param  object $stmt
     * @access public
     * @return mixed
     */
    public function appendInvolvedConditionTest($stmt = null)
    {
        global $tester;
        if($stmt === null)
        {
            $stmt = $tester->dao->select('*')->from(TABLE_PROJECT)->alias('t1');
            $stmt = $this->objectModel->leftJoinInvolvedTable($stmt);
        }
        
        $result = $this->objectModel->appendInvolvedCondition($stmt);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getExecutionProductGroup method.
     *
     * @param  array $executionIDs
     * @access public
     * @return mixed
     */
    public function getExecutionProductGroupTest($executionIDs = array())
    {
        $result = $this->objectModel->getExecutionProductGroup($executionIDs);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test addTeamMembers method.
     *
     * @param  int    $projectID
     * @param  object $project
     * @param  array  $members
     * @access public
     * @return mixed
     */
    public function addTeamMembersTest($projectID = 0, $project = null, $members = array())
    {
        $reflection = new ReflectionClass($this->objectModel);
        $method = $reflection->getMethod('addTeamMembers');
        $method->setAccessible(true);

        $result = $method->invoke($this->objectModel, $projectID, $project, $members);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test checkBranchAndProduct method.
     *
     * @param  int   $parent
     * @param  array $products
     * @param  array $branch
     * @access public
     * @return mixed
     */
    public function checkBranchAndProductTest($parent = 0, $products = array(), $branch = array())
    {
        $result = $this->objectModel->checkBranchAndProduct($parent, $products, $branch);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test checkDates method.
     *
     * @param  int    $projectID
     * @param  object $project
     * @access public
     * @return mixed
     */
    public function checkDatesTest($projectID = 0, $project = null)
    {
        $result = $this->objectModel->checkDates($projectID, $project);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test updateTeamMembers method.
     *
     * @param  object $project
     * @param  object $oldProject
     * @param  array  $newMembers
     * @access public
     * @return mixed
     */
    public function updateTeamMembersTest($project = null, $oldProject = null, $newMembers = array())
    {
        $result = $this->objectModel->updateTeamMembers($project, $oldProject, $newMembers);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}