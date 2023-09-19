<?php
class caselibTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('caselib');
    }

    /**
     * 测试通过 id 获取用例库信息。
     * Get by ID test.
     *
     * @param  int                $libID
     * @param  bool               $setImgSize
     * @access public
     * @return array|object|false
     */
    public function getByIdTest(int $libID, bool $setImgSize = false): array|object|false
    {
        $object = $this->objectModel->getById($libID, $setImgSize);

        if(dao::isError()) return dao::getError();

        return $object;
    }

    /**
     * 测试更新用例库。
     * Update case lib test.
     *
     * @param  object $lib
     * @access public
     * @return void
     */
    public function updateTest(object $lib)
    {
        $this->objectModel->update($lib);

        if(dao::isError()) return dao::getError();

        return $this->objectModel->getByID($lib->id);
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
     * 测试获取用例库键对。
     * Get libraries test.
     *
     * @access public
     * @return array
     */
    public function getLibrariesTest(): array
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
     * 测试获取用例库的用例。
     * Get libcases test.
     *
     * @param  int          $libID
     * @param  string       $browseType
     * @param  int          $moduleID
     * @param  string       $sort
     * @access public
     * @return array|string
     */
    public function getLibCasesTest(int $libID, string $browseType, int $moduleID = 0, string $sort = 'id_desc'): array|string
    {
        $objects = $this->objectModel->getLibCases($libID, $browseType, 0, $moduleID, $sort);

        if(dao::isError()) return dao::getError();

        return implode(',', array_keys($objects));
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
}
