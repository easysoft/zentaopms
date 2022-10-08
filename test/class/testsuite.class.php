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
     * Test create a test suite.
     *
     * @param  int     $productID
     * @param  string  $name
     * @param  string  $type
     * @access public
     * @return array|int
     */
    public function createTest($productID, $name, $type)
    {
        $_POST['name'] = $name;
        $_POST['desc'] = '';
        $_POST['type'] = $type;
        $_POST['uid']  = '62538b850bb9d';

        $objects = $this->objectModel->create($productID);
        unset($_POST);

        if(dao::isError()) return dao::getError();

        return $objects;
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
        $_POST['name'] = $name;
        $_POST['desc'] = '';
        $_POST['type'] = $type;
        $_POST['uid']  = '62538b850bb9d';

        $objects = $this->objectModel->update($suiteID);
        unset($_POST);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test link cases.
     *
     * @param  int   $suiteID
     * @param  array $cases
     * @access public
     * @return void
     */
    public function linkCaseTest($suiteID, $cases)
    {
        $_POST['cases']    = $cases;
        foreach($cases as $case)
        {
            $_POST['versions'][$case] = 1;
        }

        $this->objectModel->linkCase($suiteID);
        unset($_POST);

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
     * Test get not imported cases.
     *
     * @param  int    $productID
     * @param  int    $libID
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getNotImportedCasesTest($productID, $libID, $orderBy = 'id_desc', $pager = null, $browseType = '', $queryID = 0)
    {
        $objects = $this->objectModel->getNotImportedCases($productID, $libID, $orderBy, $pager, $browseType, $queryID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }
}
