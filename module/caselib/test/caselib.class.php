<?php
class caselibTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('caselib');
    }

    /**
     * Save lib state test.
     *
     * @param int $libID
     * @param array $libraries
     * @access public
     * @return void
     */
    public function saveLibStateTest($libID = 0, $libraries = array())
    {
        $objects = $this->objectModel->saveLibState($libID, $libraries);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get by ID test.
     *
     * @param mixed $libID
     * @param mixed $setImgSize
     * @access public
     * @return void
     */
    public function getByIdTest($libID, $setImgSize = false)
    {
        $objects = $this->objectModel->getById($libID, $setImgSize);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Update test.
     *
     * @param mixed $libID
     * @access public
     * @return void
     */
    public function updateTest($libID)
    {
        $objects = $this->objectModel->update($libID);

        if(dao::isError()) return dao::getError();

        $objects = $this->objectModel->getById($libID);

        return $objects;
    }

    /**
     * Delete test.
     *
     * @param mixed $libID
     * @param string $table
     * @access public
     * @return void
     */
    public function deleteTest($libID, $table = '')
    {
        $objects = $this->objectModel->delete($libID, $table);

        if(dao::isError()) return dao::getError();

        $objects = $this->objectModel->getById($libID);

        return $objects;
    }

    /**
     * Get libraries test.
     *
     * @access public
     * @return void
     */
    public function getLibrariesTest()
    {
        $objects = $this->objectModel->getLibraries();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get list test.
     *
     * @param string $orderBy
     * @param mixed $pager
     * @access public
     * @return void
     */
    public function getListTest($orderBy = 'id_desc', $pager = null)
    {
        $objects = $this->objectModel->getList($orderBy, $pager);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * 创建用例库单元测试方法。
     * Create case lib test function.
     *
     * @param  array        $params
     * @access public
     * @return array|object
     */
    public function createTest(array $params = array()): array|object
    {
        $lib = new stdclass();
        foreach($params as $key => $value) $lib->{$key} = $value;

        $libID = $this->objectModel->create($lib);

        if(dao::isError()) return dao::getError();
        return $this->objectModel->getById($libID);
    }

    /**
     * Get libcases test.
     *
     * @param mixed $libID
     * @param mixed $browseType
     * @param int $queryID
     * @param int $moduleID
     * @param string $sort
     * @param mixed $pager
     * @access public
     * @return void
     */
    public function getLibCasesTest($libID, $browseType, $queryID = 0, $moduleID = 0, $sort = 'id_desc', $pager = null)
    {
        $objects = $this->objectModel->getLibCases($libID, $browseType, $queryID, $moduleID, $sort, $pager);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Create from import test.
     *
     * @param mixed $libID
     * @access public
     * @return void
     */
    public function createFromImportTest($libID)
    {
        $objects = $this->objectModel->createFromImport($libID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Batch create case test.
     *
     * @param mixed $libID
     * @access public
     * @return void
     */
    public function batchCreateCaseTest($libID)
    {
        $objects = $this->objectModel->batchCreateCase($libID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }
}
