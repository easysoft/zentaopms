<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class testsuiteModelTest extends baseTest
{
    protected $moduleName = 'testsuite';
    protected $className  = 'model';

    public function selectTest($products, $productID, $currentModule, $currentMethod, $extra = '')
    {
        $objects = $this->instance->select($products, $productID, $currentModule, $currentMethod, $extra);

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

        $suiteID = $this->instance->create($suite);

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
        $objects = $this->instance->getSuites($productID, $orderBy, $pager);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get test suites pairs of a product.
     *
     * @param  int    $productID
     * @param  string $orderBy
     * @param  object $paper
     * @access public
     * @return void
     */
    public function getSuitePairsTest($productID, $orderBy = 'id_desc', $paper = null)
    {
        $relation = $this->instance->getSuitePairs($productID, $orderBy, $paper);

        if(dao::isError()) return dao::getError();

        return $relation;
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
        $objects = $this->instance->getUnitSuites($productID, $orderBy);

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
        $objects = $this->instance->getById($suiteID, $setImgSize);

        if(dao::isError()) return dao::getError();
        if($setImgSize == true) $objects->setImgSizeResult = strpos($objects->desc, 'setImageSize') !== false;

        return $objects;
    }

    /**
     * 测试更新一个套件。
     * Test update a test suite.
     *
     * @param  int    $suiteID
     * @param  string $name
     * @param  string $type
     * @param  string $desc
     * @param  string $uid
     * @access public
     * @return bool|array
     */
    public function updateTest(int $suiteID, string $name, string $type, string $desc, string $uid): bool|array
    {
        $suite = new stdclass();
        $suite->id   = $suiteID;
        $suite->name = $name;
        $suite->desc = $desc;
        $suite->type = $type;

        if(!empty($uid))
        {
            global $tester;
            $tester->session->set('album', array('used' => array($uid => array(1))));
        }

        $objects = $this->instance->update($suite, $uid);

        if(dao::isError()) return dao::getError();

        foreach($objects as &$object)
        {
            if($object['field'] == 'desc') $object['result'] = $object['new'] == $desc;
        }

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
        $this->instance->linkCase($suiteID, $cases, $versions);

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
        $objects = $this->instance->getLinkedCases($suiteID, $orderBy, $pager, $append);

        if(dao::isError()) return dao::getError();

        if(!$append)
        {
            foreach($objects as $object) $object->results = 'a';
        }

        return $objects;
    }

    /**
     * Test get linked cases pairs for suite.
     *
     * @param  int    $suiteID
     * @access public
     * @return void
     */
    public function getLinkedCasePairsTest($suiteID)
    {
        global $tester;
        $tester->session->set('project', 1);
        $relation = $this->instance->getLinkedCasePairs($suiteID);

        if(dao::isError()) return dao::getError();

        return $relation;
    }

    /**
     * Test get unlinked cases for suite.
     *
     * @param  int    $suiteID
     * @param  string $browseType
     * @param  int    $param
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getUnlinkedCasesTest($suiteID, $browseType = 'all', $param = 0, $pager = null)
    {
        global $tester;
        $tester->session->set('testsuiteQuery', null);

        $suite   = $this->instance->getById($suiteID);
        $objects = $this->instance->getUnlinkedCases($suite, $browseType, $param, $pager);

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
        $isClickable = $this->instance->isClickable($report, $action);
        if(dao::isError()) return dao::getError();
        return $isClickable ? 1 : 0;
    }

    /**
     * 测试根据套件id删除关联的用例。
     * Test delete case by suiteID.
     *
     * @param  int    $cases
     * @param  int    $suiteID
     * @access public
     * @return void
     */
    public function deleteCaseBySuiteIDTest($cases, $suiteID)
    {
        return $this->instance->deleteCaseBySuiteID($cases, $suiteID);
    }

    /**
     * 测试获取产品下用例关联的需求
     * Test get case linked stories by productID.
     *
     * @param  int    $productID
     * @access public
     * @return array
     */
    public function getCaseLinkedStoriesTest(int $productID): array
    {
        $result = $this->instance->getCaseLinkedStories($productID);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}
