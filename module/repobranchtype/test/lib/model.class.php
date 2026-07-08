<?php
class repobranchtypeTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('repobranchtype');
    }

    /**
     * Test getBranchTypeList method.
     *
     * @param  int    $repoID
     * @param  string $name
     * @param  string $key
     * @param  string $prefix
     * @access public
     * @return mixed
     */
    public function getBranchTypeListTest(int $repoID = 0, string $name = '', string $key = '', string $prefix = '')
    {
        $result = $this->objectModel->getBranchTypeList($repoID, $name, $key, $prefix);
        if(dao::isError()) return dao::getError();

        return empty($result) ? 0 : $result;
    }

    /**
     * Test getBranchTypeByID method.
     *
     * @param  int $typeID
     * @access public
     * @return mixed
     */
    public function getBranchTypeByIDTest(int $typeID)
    {
        $result = $this->objectModel->getBranchTypeByID($typeID);
        if(dao::isError()) return dao::getError();

        return $result ? $result : 0;
    }

    /**
     * Test getBranchTypeByIDs method.
     *
     * @param  array $typeIDs
     * @access public
     * @return mixed
     */
    public function getBranchTypeByIDsTest(array $typeIDs)
    {
        $result = $this->objectModel->getBranchTypeByIDs($typeIDs);
        if(dao::isError()) return dao::getError();

        return $result ? $result : array();
    }

    /**
     * Test getBranchTypeByRepoID method.
     *
     * @param  int $repoID
     * @access public
     * @return mixed
     */
    public function getBranchTypeByRepoIDTest(int $repoID)
    {
        $result = $this->objectModel->getBranchTypeByRepoID($repoID);
        if(dao::isError()) return dao::getError();

        return $result ? $result : array();
    }

    /**
     * Test parsePrefixToArray method.
     *
     * @param  string $prefix
     * @access public
     * @return string
     */
    public function parsePrefixToArrayTest(string $prefix): string
    {
        $method = new ReflectionMethod($this->objectModel, 'parsePrefixToArray');
        $method->setAccessible(true);
        $result = $method->invoke($this->objectModel, $prefix);

        if(dao::isError()) return dao::getError();

        return empty($result) ? '0' : implode(',', $result);
    }

        /**
     * Test getBranchTypePairs method.
     *
     * @param  int $repoID
     * @access public
     * @return array
     */
    public function getBranchTypePairsTest(int $repoID): array
    {
        $method = new ReflectionMethod($this->objectModel, 'getBranchTypePairs');
        $method->setAccessible(true);
        $result = $method->invoke($this->objectModel, $repoID);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}
