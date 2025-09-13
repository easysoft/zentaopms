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
     * 测试删除用例库。
     * Delete test.
     *
     * @param mixed         $libID
     * @param string        $table
     * @access public
     * @return array|object
     */
    public function deleteTest(int $libID, string $table = ''): array|object
    {
        $objects = $this->objectModel->delete($libID, $table);

        if(dao::isError()) return dao::getError();

        return $this->objectModel->getByID($libID);
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
     * 测试获取用例库列表。
     * Get list test.
     *
     * @param string $orderBy
     * @param mixed $pager
     * @access public
     * @return void
     */
    public function getListTest($orderBy = 'id_desc', $pager = null)
    {
        $objects = $this->objectModel->getList('all', $orderBy, $pager);

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

    /**
     * 测试获取用例库 1.5 级下拉的链接。
     * Test get lib link.
     *
     * @param  string $module
     * @param  string $method
     * @access public
     * @return array
     */
    public function getLibLinkTest(string $module, string $method): array
    {
        $link = $this->objectModel->getLibLink($module, $method);

        if(dao::isError()) return dao::getError();

        $isCaselibBrowse = preg_match('/caselib.*browse/', $link) ? 1 : 0;
        $isItself        = preg_match("/{$module}.*{$method}/", $link) ? 1 : 0;
        return array('isCaselibBrowse' => $isCaselibBrowse, 'isItself' => $isItself);
    }

    /**
     * 测试初始化导入的用例
     * Test init imported case.
     *
     * @param  object $data
     * @access public
     * @return array|bool
     */
    public function initImportedCaseTest(object $data): bool|array
    {
        $cases = $this->objectModel->initImportedCase($data);

        if(dao::isError()) return dao::getError();

        return $cases;
    }

    /**
     * 测试插入一条导入的用例。
     * Test insert a imported case.
     *
     * @param  int     $key
     * @param  object  $caseData
     * @param  object  $data
     * @param  bool    $forceNotReview
     * @access public
     * @return array|object|false
     */
    public function insertImportedCaseTest(int $key, object $caseData, object $data, bool $forceNotReview): array|object|false
    {
        $caseID = $this->objectModel->insertImportedCase($key, $caseData, $data, $forceNotReview);
        if(dao::isError()) return dao::getError();

        global $tester;
        return $tester->loadModel('testcase')->getByID($caseID);
    }

    /**
     * 测试导入的用例覆盖已存在的用例。
     * Test update a imported case.
     *
     * @param  int     $key
     * @param  object  $caseData
     * @param  object  $data
     * @param  bool    $forceNotReview
     * @access public
     * @return array|object|false
     */
    public function updateImportedCaseTest(int $key, object $caseData, object $data, bool $forceNotReview): array|object|false
    {
        $caseID = $data->id[$key];

        global $tester;
        $oldCase  = $tester->loadModel('testcase')->getById($caseID);
        $oldSteps = $tester->testcase->fetchStepsByList(array($caseID));
        $oldCase->steps = zget($oldSteps, $caseID, array());
        if(!isset($caseData->steps))
        {
            $caseData->steps    = array(1 => '步骤更新');
            $caseData->expects  = array(1 => '');
            $caseData->stepType = array(1 => 'step');
        }

        $this->objectModel->updateImportedCase($key, $caseData, $data, $forceNotReview, $oldCase);
        if(dao::isError()) return dao::getError();

        return $tester->testcase->getByID($caseID);
    }

    /**
     * 测试获取导出的用例库用例。
     * Test get cases to export.
     *
     * @param  string       $browseType
     * @param  string       $exportType
     * @param  string       $orderBy
     * @param  int          $limit
     * @access public
     * @return array|string
     */
    public function getCasesToExportTest(string $browseType, string $exportType, string $orderBy, string $checkedItem = '', int $limit = 0): array|string
    {
        $moduleID = $browseType == 'bymodule' ? 101 : 0;
        $this->objectModel->getLibCases(201, $browseType, 0, $moduleID, $orderBy);
        $this->objectModel->loadModel('common')->saveQueryCondition($this->objectModel->dao->get(), 'testcase', true);

        if($exportType == 'selected') $_COOKIE['checkedItem']= $checkedItem;
        $objects = $this->objectModel->getCasesToExport($exportType, $orderBy, $limit);

        if(dao::isError()) return dao::getError();

        return implode(';', array_keys($objects));
    }

    /**
     * Test setLibMenu method.
     *
     * @param  array $libraries
     * @param  int   $libID
     * @access public
     * @return bool
     */
    public function setLibMenuTest(array $libraries = array(), int $libID = 0): bool
    {
        $result = $this->objectModel->setLibMenu($libraries, $libID);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getPairs method.
     *
     * @param  string $type
     * @param  string $orderBy
     * @param  mixed  $pager
     * @access public
     * @return array
     */
    public function getPairsTest(string $type = 'all', string $orderBy = 'id_desc', $pager = null): array
    {
        $result = $this->objectModel->getPairs($type, $orderBy, $pager);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test buildSearchConfig method.
     *
     * @param  int $libID
     * @access public
     * @return array
     */
    public function buildSearchConfigTest(int $libID): array
    {
        $result = $this->objectModel->buildSearchConfig($libID);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test checkStepChanged method.
     *
     * @param  array $oldSteps
     * @param  array $steps
     * @access public
     * @return bool|array
     */
    public function checkStepChangedTest(array $oldSteps, array $steps): bool|array
    {
        global $tester;
        $objectTao = $tester->loadTao('caselib');
        
        $reflection = new ReflectionClass($objectTao);
        $method = $reflection->getMethod('checkStepChanged');
        $method->setAccessible(true);
        
        $result = $method->invoke($objectTao, $oldSteps, $steps);
        
        if(dao::isError()) return dao::getError();
        
        return $result;
    }

    /**
     * Test processSteps method.
     *
     * @param  array $descs
     * @param  array $stepTypes
     * @param  array $expects
     * @access public
     * @return array
     */
    public function processStepsTest(array $descs, array $stepTypes, array $expects): array
    {
        global $tester;
        $objectTao = $tester->loadTao('caselib');
        
        $reflection = new ReflectionClass($objectTao);
        $method = $reflection->getMethod('processSteps');
        $method->setAccessible(true);
        
        $result = $method->invoke($objectTao, $descs, $stepTypes, $expects);
        
        if(dao::isError()) return dao::getError();
        
        return $result;
    }

    /**
     * Test saveLibState method.
     *
     * @param  int   $libID
     * @param  array $libraries
     * @access public
     * @return int
     */
    public function saveLibStateTest(int $libID = 0, array $libraries = array()): int
    {
        $zen = initReference('caselib');
        $method = $zen->getMethod('saveLibState');
        $zenInstance = $zen->newInstance();
        
        $result = $method->invoke($zenInstance, $libID, $libraries);
        
        if(dao::isError()) return dao::getError();
        
        return $result;
    }

    /**
     * Test setBrowseSessionAndCookie method.
     *
     * @param  int    $libID
     * @param  string $browseType
     * @param  int    $param
     * @access public
     * @return mixed
     */
    public function setBrowseSessionAndCookieTest(int $libID = 0, string $browseType = 'all', int $param = 0)
    {
        $zen = initReference('caselib');
        $method = $zen->getMethod('setBrowseSessionAndCookie');
        $zenInstance = $zen->newInstance();
        
        // Execute method
        $method->invoke($zenInstance, $libID, $browseType, $param);
        
        if(dao::isError()) return dao::getError();
        
        // Method returns void, so we return success indicator
        return true;
    }
}
