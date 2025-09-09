<?php
class Project
{
    public function __construct()
    {
        global $tester;
        $this->project = $tester->loadModel('project');
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
        $result = $this->project->getAclListByObjectType($objectType);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}