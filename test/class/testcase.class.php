<?php
class testcaseTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('testcase');
    }

    /**
     * Test create a case.
     *
     * @param  array  $param
     * @access public
     * @return array
     */
    public function createTest($param)
    {
        $bugID = 0;

        $_POST['product']      = '1';
        $_POST['module']       = '1821';
        $_POST['type']         = 'feature';
        $_POST['stage']        = array('', 'unittest');
        $_POST['story']        = '4';
        $_POST['color']        = '';
        $_POST['pri']          = '3';
        $_POST['precondition'] = '前置条件';
        $_POST['steps']        = array('1' => '1','1.1' => '1.1', '1.2' => '1.2', '2' => '2', '3' => '3', '4' => '');
        $_POST['stepType']     = array('1' => 'group','1.1' => 'item', '1.2' => 'item', '2' => 'step', '3' => 'item', '4' => 'step');
        $_POST['expects']      = array('1' => '','1.1' => '', '1.2' => '', '2' => '', '3' => '', '4' => '');
        $_POST['keywords']     = '关键词1,关键词2';
        $_POST['status']       = 'normal';
        $_POST['labels']       = array('');
        $_POST['files']        = array('');

        foreach($param as $field => $value) $_POST[$field] = $value;

        $objects = $this->objectModel->create($bugID);

        unset($_POST);

        if(dao::isError()) return isset($param['type']) ? dao::getError()['type'][0] : dao::getError()['title'][0];

        return $objects;
    }

    /**
     * Test batch create cases.
     *
     * @param  array  $param
     * @access public
     * @return int
     */
    function batchCreateTest($param)
    {
        $productID = 1;
        $branch    = 0;
        $storyID   = 0;

        $module       = array(0, 0, 0);
        $story        = array(0, 0, 0);
        $title        = array('测试批量创建1', '测试批量创建2', '测试批量创建3');
        $color        = array('#3da7f5', '', '#ffaf38');
        $type         = array('performance', 'config', 'install');
        $pri          = array('1', '2', '3');
        $precondition = array('测试批量创建前置1', '测试批量创建前置2', '测试批量创建前置3');
        $keywords     = array('测试批量创建关键词1', '测试批量创建关键词2', '测试批量创建关键词3');
        $stage        = array(array('smoke'), array('bvt'), array('intergrate'));
        $needReview   = array(0, 0, 0);

        $_POST['module']       =  $module;
        $_POST['story']        =  $story;
        $_POST['title']        =  $title;
        $_POST['color']        =  $color;
        $_POST['type']         =  $type;
        $_POST['pri']          =  $pri;
        $_POST['precondition'] =  $precondition;
        $_POST['keywords']     =  $keywords;
        $_POST['stage']        =  $stage;
        $_POST['needReview']   =  $needReview;

        foreach($param as $field => $value) $_POST[$field] = $value;

        $objects = $this->objectModel->batchCreate($productID, $branch, $storyID);

        unset($_POST);

        if(dao::isError()) return dao::getError();

        return count($objects);
    }

    /**
     * Test get cases of a module.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  int    $moduleIdList
     * @param  string $browseType
     * @param  string $auto
     * @param  string $caseType
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getModuleCasesTest($productID, $branch = 0, $moduleIdList = 0, $browseType = '', $auto = 'no', $caseType = '', $orderBy = 'id_desc', $pager = null)
    {
        $objects = $this->objectModel->getModuleCases($productID, $branch, $moduleIdList, $browseType, $auto, $caseType, $orderBy, $pager);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get project cases of a module.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  int    $moduleIdList
     * @param  string $browseType
     * @param  string $auto
     * @param  string $caseType
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getModuleProjectCasesTest($productID, $branch = 0, $moduleIdList = 0, $browseType = '', $auto = 'no', $caseType = '', $orderBy = 'id_desc', $pager = null)
    {
        $objects = $this->objectModel->getModuleProjectCases($productID, $branch, $moduleIdList, $browseType, $auto, $caseType, $orderBy, $pager);

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

    /**
     * Test get test cases.
     *
     * @param  int    $productID
     * @param  string $browseType
     * @param  int    $queryID
     * @param  string $auto
     * @access public
     * @return array
     */
    public function getTestCasesTest($productID, $browseType, $queryID, $auto = 'no')
    {
        $objects = $this->objectModel->getTestCases($productID, 0, $browseType, $queryID, $moduleID, '', 'id_desc', null, $auto);

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

    /**
     * Test get cases by type.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  string $type
     * @param  string $status
     * @param  int    $moduleID
     * @param  string $orderBy
     * @param  object $pager
     * @param  string $auto
     * @access public
     * @return int
     */
    public function getByStatusTest($productID = 0, $branch = 0, $type = 'all', $status = 'all', $moduleID = 0, $orderBy = 'id_desc', $pager = null, $auto = 'no')
    {
        $objects = $this->objectModel->getByStatus($productID, $branch, $type, $status, $moduleID, $orderBy, $pager, $auto);

        if(dao::isError()) return dao::getError();

        return count($objects);
    }

    /**
     * Test get stories' cases.
     *
     * @param  int    $storyID
     * @access public
     * @return array
     */
    public function getStoryCasesTest($storyID)
    {
        $objects = $this->objectModel->getStoryCases($storyID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get case pairs by product id and branch.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @access public
     * @return array
     */
    public function getPairsByProductTest($productID = 0, $branch = 0)
    {
        $objects = $this->objectModel->getPairsByProduct($productID, $branch);

        if(dao::isError()) return dao::getError();

        if(empty($objects)) return 'empty';
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

    /**
     * Test update a case.
     *
     * @param  array $param
     * @access public
     * @return int
     */
    public function updateTest($param = array())
    {
        $caseId = 1;
        $case = $this->objectModel->getById($caseId);

        $_POST['title']          = $case->title;
        $_POST['color']          = $case->color;
        $_POST['precondition']   = $case->precondition;
        $_POST['steps']          = array('用例步骤描述1');
        $_POST['stepType']       = array('step');
        $_POST['expects']        = array('这是用例预期结果1');
        $_POST['comment']        = '';
        $_POST['labels']         = '';
        $_POST['lastEditedDate'] = $case->lastEditedDate;
        $_POST['product']        = $case->product;
        $_POST['module']         = $case->module;
        $_POST['story']          = $case->story;
        $_POST['type']           = $case->type;
        $_POST['stage']          = $case->stage;
        $_POST['pri']            = $case->pri;
        $_POST['status']         = $case->status;
        $_POST['keywords']       = $case->keywords;

        foreach($param as $field => $value) $_POST[$field] = $value;

        $change = $this->objectModel->update('1');
        if($change == array()) $change = '没有数据更新';

        unset($_POST);

        if(dao::isError()) return dao::getError();

        return $change;
    }

    /**
     * Test review case.
     *
     * @param  int    $caseID
     * @param  object $case
     * @access public
     * @return array
     */
    public function reviewTest($caseID, $case)
    {
        foreach($case as $field => $value) $_POST[$field] = $value;

        $objects = $this->objectModel->review($caseID);

        unset($_POST);

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

    /**
     * Test batch update cases.
     *
     * @param  array  $param
     * @access public
     * @return array
     */
    public function batchUpdateTest($index, $param = array())
    {
        $batchUpdateField['caseIDList']   = array('1' => 1, '2' => 2, '3' => 3, '4' => 4);
        $batchUpdateField['pris']         = array('1' => 1, '2' => 2, '3' => 3, '4' => 4);
        $batchUpdateField['statuses']     = array('1' => 'wait', '2' => 'normal', '3' => 'blocked', '4' => 'investigate');
        $batchUpdateField['modules']      = array('1' => 0, '2' => 0, '3' => 0, '4' => 0);
        $batchUpdateField['branches']     = array('1' => 0, '2' => 0, '3' => 0, '4' => 0);
        $batchUpdateField['story']        = array('1' => 2, '2' => 2, '3' => 2, '4' => 2);
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

        return $objects[$index][0];
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

    /**
     * Test join steps to a string, thus can diff them.
     *
     * @param  array  $stepIDList
     * @access public
     * @return string
     */
    public function joinStepTest($stepIDList)
    {
        global $tester;
        $steps = $tester->dao->select('*')->from(TABLE_CASESTEP)->where('id')->in($stepIDList)->fetchAll();

        $string = $this->objectModel->joinStep($steps);

        if(dao::isError()) return dao::getError();

        $string = str_replace("\n", ' ', $string);
        return $string;
    }

    /**
     * Test create from import.
     *
     * @param  int    $productID
     * @param  array  $param
     * @access public
     * @return string
     */
    public function createFromImportTest($productID, $param = array())
    {
        global $tester;

        $_POST['product']      = array('1' => '1', '2' => '1');
        $_POST['keywords']     = array('1' => '这是关键词1', '2' => '这是关键词2');
        $_POST['title']        = array('1' => '导入测试用例1', '2' => '导入测试用例2');
        $_POST['module']       = array('1' => '0', '2' => '0');
        $_POST['story']        = array('1' => '2', '2' => '2');
        $_POST['pri']          = array('1' => '1', '2' => '2');
        $_POST['type']         = array('1' => 'performance', '2' => 'feature');
        $_POST['stage']        = array('1' => array('0' => 'feature'), '2' => array('0' => 'unittest'));
        $_POST['precondition'] = array('1' => '这是前置条件1', '2' => '这是前置条件2');
        $_POST['stepType']     = array('1' => array('1' => 'step'), '2' => array('1' => 'step'));
        $_POST['desc']         = array('1' => array('1' => '用例步骤描述1'), '2' => array('1' => '用例步骤描述2'));
        $_POST['expect']       = array('1' => array('1' => '这是用例预期结果1'), '2' => array('1' => '这是用例预期结果2'));
        $_POST['isEndPage']    = '1';
        $_POST['pagerID']      = '1';

        foreach($param as $field => $value) $_POST[$field] = $value;

        $fileName = __DIR__ . DS . 'a.txt';
        fopen($fileName, 'a');
        $tester->session->fileImport = $fileName;

        $this->objectModel->createFromImport($productID, 0);


        unset($_POST);

        if(dao::isError()) return dao::getError();

        $objects = $tester->dao->select('*')->from(TABLE_CASE)->where('product')->eq(1)->andWhere('lib')->eq(0)->markLeft()->andWhere('id')->gt(560)->orWhere('id')->in('1,2')->markRight()->fetchAll('title');
        $titles = implode(',', array_keys($objects));
        return $titles;
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

    /**
     * Test import case from Lib.
     *
     * @param  int    $productID
     * @param  array  $caseIdList
     * @access public
     * @return array
     */
    public function importFromLibTest($productID, $caseIdList = array())
    {
        $_POST['module']     = array('410' => 0, '409' => 'ditto', '408' => 'ditto', '407' => 'ditto', '406' => 'ditto', '405' => 'ditto', '404' => 'ditto', '403' => 'ditto', '402' => 'ditto', '401' => 'ditto');
        $_POST['caseIdList'] = $caseIdList;

        $this->objectModel->importFromLib($productID);

        unset($_POST);

        if(dao::isError()) return dao::getError();

        global $tester;
        $objects = $tester->dao->select('*')->from(TABLE_CASE)->where('fromCaseID')->in($caseIdList)->orderBy('id_asc')->fetchAll();
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

    /**
     * Test sync case to project.
     *
     * @param  int    $caseID
     * @access public
     * @return int
     */
    public function syncCase2ProjectTest($caseID)
    {
        global $tester;
        $tester->dao->delete()->from(TABLE_PROJECTCASE)->where('`case`')->eq($caseID)->exec();
        $case = $this->objectModel->getByID($caseID);
        $this->objectModel->syncCase2Project($case, $caseID);

        if(dao::isError()) return dao::getError();

        $objects = $tester->dao->select('*')->from(TABLE_PROJECTCASE)->where('`case`')->eq($caseID)->fetchAll();
        return count($objects);
    }

    /**
     * Test deal with the relationship between the case and project when edit the case.
     *
     * @param  int    $caseID
     * @param  string $objectType
     * @param  int    $objectID
     * @access public
     * @return array
     */
    public function updateCase2ProjectTest($caseID, $objectType, $objectID)
    {
        $oldCase = $this->objectModel->getByID($caseID);

        $case = new stdclass();
        $case->title          = $oldCase->title;
        $case->color          = $oldCase->color;
        $case->precondition   = $oldCase->precondition;
        $case->lastEditedDate = $oldCase->lastEditedDate;
        $case->product        = $objectType == 'product' ? $objectID : $oldCase->product;
        $case->module         = $oldCase->module;
        $case->story          = $objectType == 'story' ? $objectID : $oldCase->story;
        $case->type           = $oldCase->type;
        $case->stage          = $oldCase->stage;
        $case->pri            = $oldCase->pri;
        $case->status         = $oldCase->status;
        $case->keywords       = $oldCase->keywords;
        $case->version        = $oldCase->version + 1;
        $case->linkCase       = $oldCase->linkCase;
        $case->lastEditedBy   = $oldCase->lastEditedBy;
        $case->branch         = $oldCase->branch;

        $this->objectModel->updateCase2Project($oldCase, $case, $caseID);

        if(dao::isError()) return dao::getError();

        global $tester;
        $objects = $tester->dao->select('*')->from(TABLE_PROJECTCASE)->where('`case`')->eq($caseID)->fetchAll();
        return $objects;
    }

    /**
     * Test get status for different method.
     *
     * @param  string $methodName
     * @param  object $case
     * @param  array  $param
     * @access public
     * @return array
     */
    public function getStatusTest($methodName, $case = null, $param = array())
    {
        if($methodName == 'update')
        {
            $case = $this->objectModel->getByID(1);
            $_POST['title']          = $case->title;
            $_POST['color']          = $case->color;
            $_POST['precondition']   = $case->precondition;
            $_POST['steps']          = array('用例步骤描述1');
            $_POST['stepType']       = array('step');
            $_POST['expects']        = array('这是用例预期结果1');
            $_POST['comment']        = '';
            $_POST['labels']         = '';
            $_POST['lastEditedDate'] = $case->lastEditedDate;
            $_POST['product']        = $case->product;
            $_POST['module']         = $case->module;
            $_POST['story']          = $case->story;
            $_POST['type']           = $case->type;
            $_POST['stage']          = $case->stage;
            $_POST['pri']            = $case->pri;
            $_POST['status']         = $case->status;
            $_POST['keywords']       = $case->keywords;

            foreach($param as $field => $value) $_POST[$field] = $value;
        }

        $objects = $this->objectModel->getStatus($methodName, $case);

        unset($_POST);

        if(dao::isError()) return dao::getError()[0];

        return $objects;
    }
}
