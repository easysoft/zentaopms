<?php
class testsuiteTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('testsuite');
    }

    public function selectTest($products, $productID, $currentModule, $currentMethod, $extra = '')
    {
        $objects = $this->objectModel->select($products, $productID, $currentModule, $currentMethod, $extra);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * 测试创建一个测试套件。
     * Test create a test suite.
     *
     * @param  int     $productID
     * @param  string  $name
     * @param  string  $type
     * @access public
     * @return array|int
     */
    public function createTest(int $productID, string $name, string $type): array|int
    {
        $suite = new stdclass();
        $suite->name = $name;
        $suite->desc = '';
        $suite->type = $type;

        $suiteID = $this->objectModel->create($suite);

        if(dao::isError()) return dao::getError();

        return $suiteID;
    }

    /**
     * Test get test suites of a product.
     *
     * @param  int    $productID
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getSuitesTest($productID, $orderBy = 'id_desc', $pager = null)
    {
        $objects = $this->objectModel->getSuites($productID, $orderBy, $pager);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get unit suite.
     *
     * @param  int    $productID
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getUnitSuitesTest($productID, $orderBy = 'id_desc')
    {
        $objects = $this->objectModel->getUnitSuites($productID, $orderBy);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get test suite info by id.
     *
     * @param  int   $suiteID
     * @param  bool  $setImgSize
     * @access public
     * @return object
     */
    public function getByIdTest($suiteID, $setImgSize = false)
    {
        $objects = $this->objectModel->getById($suiteID, $setImgSize);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test update a test suite.
     *
     * @param  int   $suiteID
     * @access public
     * @return bool|array
     */
    public function updateTest($suiteID, $name, $type)
    {
        $suite = new stdclass();
        $suite->id   = $suiteID;
        $suite->name = $name;
        $suite->desc = '';
        $suite->type = $type;

        $uid = 1;
        $objects = $this->objectModel->update($suite, $uid);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test link cases.
     *
     * @param  int   $suiteID
     * @param  array $cases
     * @param  array $versions
     * @access public
     * @return void
     */
    public function linkCaseTest($suiteID, $cases, $versions)
    {
        $this->objectModel->linkCase($suiteID, $cases, $versions);

        if(dao::isError()) return dao::getError();

        global $tester;
        $objects = $tester->dao->select('*')->from(TABLE_SUITECASE)->where('suite')->eq($suiteID)->andWhere('`case`')->in($cases)->fetchAll();

        return $objects;
    }

    /**
     * Test get linked cases for suite.
     *
     * @param  int    $suiteID
     * @param  string $orderBy
     * @param  object $pager
     * @param  bool   $append
     * @access public
     * @return array
     */
    public function getLinkedCasesTest($suiteID, $orderBy = 'id_desc', $pager = null, $append = true)
    {
        $objects = $this->objectModel->getLinkedCases($suiteID, $orderBy, $pager, $append);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get unlinked cases for suite.
     *
     * @param  int    $suiteID
     * @param  int    $param
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getUnlinkedCasesTest($suiteID, $param = 0, $pager = null)
    {
        global $tester;
        $tester->session->set('testsuiteQuery', null);

        $suite   = $this->objectModel->getById($suiteID);
        $objects = $this->objectModel->getUnlinkedCases($suite, $param, $pager);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test delete suite and library.
     *
     * @param  int    $suiteID
     * @param  string $table
     * @access public
     * @return int
     */
    public function deleteTest($suiteID, $table = '')
    {
        $objects = $this->objectModel->delete($suiteID, $table);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * 测试判断操作是否可以点击。
     * Test judge an action is clickable or not.
     *
     * @param  object     $report
     * @param  string     $action
     * @access public
     * @return int|array
     */
    public function isClickableTest(object $report, string $action): int|array
    {
        $isClickable = $this->objectModel->isClickable($report, $action);
        if(dao::isError()) return dao::getError();
        return $isClickable ? 1 : 0;
    }
}
