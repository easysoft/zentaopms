<?php
class testcaseTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('testcase');
    }

    public function setMenuTest($products, $productID, $branch = 0, $moduleID = 0, $suiteID = 0, $orderBy = 'id_desc')
    {
        $objects = $this->objectModel->setMenu($products, $productID, $branch = 0, $moduleID = 0, $suiteID = 0, $orderBy = 'id_desc');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get cases of a module.
     *
     * @param  int   $productID
     * @param  int   $branch
     * @param  int   $moduleIdList
     * @param  string $orderBy
     * @param  object $pager
     * @param  string $browseType
     * @param  string $auto
     * @access public
     * @return array
     */
    public function getModuleCasesTest($productID, $branch = 0, $moduleIdList = 0, $orderBy = 'id_desc', $pager = null, $browseType = '', $auto = 'no')
    {
        $objects = $this->objectModel->getModuleCases($productID, $branch, $moduleIdList, $orderBy, $pager, $browseType, $auto);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get project cases of a module.
     *
     * @param  int   $productID
     * @param  int   $branch
     * @param  int   $moduleIdList
     * @param  string $orderBy
     * @param  object $pager
     * @param  string $browseType
     * @param  string $auto
     * @access public
     * @return array
     */
    public function getModuleProjectCasesTest($productID, $branch = 0, $moduleIdList = 0, $orderBy = 'id_desc', $pager = null, $browseType = '', $auto = 'no')
    {
        $objects = $this->objectModel->getModuleProjectCases($productID, $branch, $moduleIdList, $orderBy, $pager, $browseType, $auto);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getProjectCasesTest($projectID, $orderBy = 'id_desc', $pager = null, $browseType = '')
    {
        $objects = $this->objectModel->getProjectCases($projectID, $orderBy = 'id_desc', $pager = null, $browseType = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get execution cases.
     *
     * @param  int    $executionID
     * @param  string $orderBy
     * @param  object $pager
     * @param  string $browseType
     * @access public
     * @return string
     */
    public function getExecutionCasesTest($executionID, $orderBy = 'id_desc', $pager = null, $browseType = '')
    {
        $objects = $this->objectModel->getExecutionCases($executionID, $orderBy, $pager, $browseType);

        if(dao::isError()) return dao::getError();

        return $browseType == 'all' ? $objects : count($objects);
    }

    /**
     * Test get cases by suite.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  int    $suiteID
     * @param  int    $moduleIdList
     * @param  string $orderBy
     * @param  object $pager
     * @param  string $auto
     * @access public
     * @return array
     */
    public function getBySuiteTest($productID, $branch = 0, $suiteID = 0, $moduleIdList = 0, $orderBy = 'id_desc', $pager = null, $auto = 'no')
    {
        $objects = $this->objectModel->getBySuite($productID, $branch, $suiteID, $moduleIdList, $orderBy, $pager, $auto);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get case info by ID.
     *
     * @param  int $caseID
     * @param  int $version
     * @access public
     * @return object
     */
    public function getByIdTest($caseID, $version = 0)
    {
        $object = $this->objectModel->getById($caseID, $version = 0);

        if(dao::isError()) return dao::getError();

        return $object;
    }

    /**
     * Test get case list.
     *
     * @param  string $caseIDList
     * @access public
     * @return array
     */
    public function getByListTest($caseIDList = 0)
    {
        $objects = $this->objectModel->getByList($caseIDList = 0);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getTestCasesTest($productID, $branch, $browseType, $queryID, $moduleID, $sort, $pager, $auto = 'no')
    {
        $objects = $this->objectModel->getTestCases($productID, $branch, $browseType, $queryID, $moduleID, $sort, $pager, $auto = 'no');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getBySearchTest($productID, $queryID, $orderBy, $pager = null, $branch = 0, $auto = 'no')
    {
        $objects = $this->objectModel->getBySearch($productID, $queryID, $orderBy, $pager = null, $branch = 0, $auto = 'no');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get cases by assignedTo.
     *
     * @param  string $account
     * @param  string $orderBy
     * @param  object $pager
     * @param  string $auto
     * @access public
     * @return string
     */
    public function getByAssignedToTest($account, $orderBy = 'id_desc', $pager = null, $auto = 'no')
    {
        $objects = $this->objectModel->getByAssignedTo($account, $orderBy = 'id_desc', $pager = null, $auto = 'no');

        if(dao::isError()) return dao::getError();

        $ids = implode(array_keys($objects), ',');
        return $ids;
    }

    /**
     * Test get cases by openedBy
     *
     * @param  string $account
     * @param  string $orderBy
     * @param  object $pager
     * @param  string $auto
     * @access public
     * @return array
     */
    public function getByOpenedByTest($account, $orderBy = 'id_desc', $pager = null, $auto = 'no')
    {
        $objects = $this->objectModel->getByOpenedBy($account, $orderBy = 'id_desc', $pager = null, $auto = 'no');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getByStatusTest($productID = 0, $branch = 0, $type = 'all', $status = 'all', $moduleID = 0, $orderBy = 'id_desc', $pager = null, $auto = 'no')
    {
        $objects = $this->objectModel->getByStatus($productID, $branch, $type, $status, $moduleID, $orderBy, $pager, $auto);

        if(dao::isError()) return dao::getError();

        return count($objects);
    }

    public function getStoryCasesTest($storyID)
    {
        $objects = $this->objectModel->getStoryCases($storyID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get counts of some stories' cases.
     *
     * @param  array  $stories
     * @access public
     * @return void
     */
    public function getStoryCaseCountsTest($stories)
    {
        $counts = $this->objectModel->getStoryCaseCounts($stories);

        if(dao::isError()) return dao::getError();

        return $counts;
    }

    public function updateTest($caseID)
    {
        $objects = $this->objectModel->update($caseID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function reviewTest($caseID)
    {
        $objects = $this->objectModel->review($caseID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test batch review cases.
     *
     * @param  array  $caseIdList
     * @param  string $result
     * @access public
     * @return array
     */
    public function batchReviewTest($caseIdList, $result)
    {
        $objects = $this->objectModel->batchReview($caseIdList, $result);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getCases2LinkTest($caseID, $browseType = 'bySearch', $queryID = 0)
    {
        $objects = $this->objectModel->getCases2Link($caseID, $browseType = 'bySearch', $queryID = 0);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function batchUpdateTest($param = array())
    {
        $batchUpdateField['caseIDList']   = array('1' => 1, '2' => 2, '3' => 3, '4' => 4);
        $batchUpdateField['pris']         = array('1' => 1, '2' => 2, '3' => 3, '4' => 4);
        $batchUpdateField['statuses']     = array('1' => 'wait', '2' => 'normal', '3' => 'blocked', '4' => 'investigate');
        $batchUpdateField['modules']      = array('1' => 0, '2' => 0, '3' => 0, '4' => 0);
        $batchUpdateField['branches']     = array('1' => 0, '2' => 0, '3' => 0, '4' => 0);
        $batchUpdateField['story']        = array('1' => 0, '2' => 0, '3' => 0, '4' => 0);
        $batchUpdateField['product']      = array('1' => 1, '2' => 1, '3' => 1, '4' => 1);
        $batchUpdateField['title']        = array('1' => '这个是测试用例1', '2' => '这个是测试用例2', '3' => '这个是测试用例3', '4' => '这个是测试用例4');
        $batchUpdateField['color']        = array('1' => '#3da7f5', '2' => '#75c941', '3' => '#2dbdb2', '4' => '#797ec9');
        $batchUpdateField['types']        = array('1' => 'feature', '2' => 'performance', '3' => 'config', '4' => 'install');
        $batchUpdateField['precondition'] = array('1' => '这是前置条件1', '2' => '这是前置条件2', '3' => '这是前置条件3', '4' => '这是前置条件4');
        $batchUpdateField['keywords']     = array('1' => '这是关键词1', '2' => '这是关键词2', '3' => '这是关键词3', '4' => '这是关键词4');
        $batchUpdateField['stages']       = array('1' => array('unittest'), '2' => array('feature'), '3' => array('intergrate'), '4' => array('system'));

        foreach($batchUpdateField as $field => $defaultValue) $_POST[$field] = $defaultValue;

        foreach($param as $key => $value) $_POST[$key] = $value;

        $objects = $this->objectModel->batchUpdate();

        unset($_POST);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test batch change branch.
     *
     * @param  array  $caseIDList
     * @param  int    $branchID
     * @access public
     * @return array
     */
    public function batchChangeBranchTest($caseIDList, $branchID)
    {
        $this->objectModel->batchChangeBranch($caseIDList, $branchID);

        if(dao::isError()) return dao::getError();

        $objects = $this->objectModel->getByList($caseIDList);
        return $objects;
    }

    /**
     * Test batch change module.
     *
     * @param  array  $caseIDList
     * @param  int    $moduleID
     * @access public
     * @return array
     */
    public function batchChangeModuleTest($caseIDList, $moduleID)
    {
        $this->objectModel->batchChangeModule($caseIDList, $moduleID);

        if(dao::isError()) return dao::getError();

        $objects = $this->objectModel->getByList($caseIDList);
        return $objects;
    }

    /**
     * Test batch case type change.
     *
     * @param  array  $caseIdList
     * @param  string $result
     * @access public
     * @return array
     */
    public function batchCaseTypeChangeTest($caseIdList, $result)
    {
        $this->objectModel->batchCaseTypeChange($caseIdList, $result);

        if(dao::isError()) return dao::getError();

        $objects = $this->objectModel->getByList($caseIdList);
        return $objects;
    }

    public function joinStepTest($steps)
    {
        $objects = $this->objectModel->joinStep($steps);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function createFromImportTest($productID, $branch = 0)
    {
        $objects = $this->objectModel->createFromImport($productID, $branch = 0);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get fields for import.
     *
     * @param  int    $productID
     * @access public
     * @return array
     */
    public function getImportFieldsTest($productID = 0)
    {
        $object = $this->objectModel->getImportFields($productID);

        if(dao::isError()) return dao::getError();

        return $object;
    }

    public function importFromLibTest($productID)
    {
        $objects = $this->objectModel->importFromLib($productID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function buildSearchFormTest($productID, $products, $queryID, $actionURL, $projectID = 0)
    {
        $objects = $this->objectModel->buildSearchForm($productID, $products, $queryID, $actionURL, $projectID = 0);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function printCellTest($col, $case, $users, $branches, $modulePairs = array(), $browseType = '', $mode = 'datatable')
    {
        $objects = $this->objectModel->printCell($col, $case, $users, $branches, $modulePairs = array(), $browseType = '', $mode = 'datatable');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test append bugs and results.
     *
     * @param  array  $cases
     * @param  string $type
     * @access public
     * @return array
     */
    public function appendDataTest($cases, $type = 'case')
    {
        $objects = $this->objectModel->appendData($cases, $type = 'case');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test check whether force not review.
     *
     * @access public
     * @return int
     */
    public function forceNotReviewTest()
    {
        $object = $this->objectModel->forceNotReview();

        if(dao::isError()) return dao::getError();

        return $object ? 1 : 2;
    }

    public function summaryTest($cases)
    {
        $objects = $this->objectModel->summary($cases);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function syncCase2ProjectTest($case, $caseID)
    {
        $objects = $this->objectModel->syncCase2Project($case, $caseID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function updateCase2ProjectTest($oldCase, $case, $caseID)
    {
        $objects = $this->objectModel->updateCase2Project($oldCase, $case, $caseID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getStatusTest($methodName, $case = null)
    {
        $objects = $this->objectModel->getStatus($methodName, $case = null);

        if(dao::isError()) return dao::getError();

        return $objects;
    }
}
