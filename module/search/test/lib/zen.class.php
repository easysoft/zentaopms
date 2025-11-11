<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class searchZenTest extends baseTest
{
    protected $moduleName = 'search';
    protected $className  = 'zen';

    /**
     * Test getTypeList method.
     *
     * @access public
     * @return mixed
     */
    public function getTypeListTest()
    {
        $result = $this->invokeArgs('getTypeList');
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test setOptionAndOr method.
     *
     * @access public
     * @return mixed
     */
    public function setOptionAndOrTest()
    {
        $result = $this->invokeArgs('setOptionAndOr');
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test setOptionFields method.
     *
     * @param  array $fields
     * @param  array $fieldParams
     * @access public
     * @return mixed
     */
    public function setOptionFieldsTest($fields, $fieldParams)
    {
        $result = $this->invokeArgs('setOptionFields', [$fields, $fieldParams]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test setOptionOperators method.
     *
     * @access public
     * @return mixed
     */
    public function setOptionOperatorsTest()
    {
        $result = $this->invokeArgs('setOptionOperators');
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test setOptions method.
     *
     * @param  array $fields
     * @param  array $fieldParams
     * @param  array $queries
     * @access public
     * @return mixed
     */
    public function setOptionsTest($fields, $fieldParams, $queries = array())
    {
        $result = $this->invokeArgs('setOptions', [$fields, $fieldParams, $queries]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test setSessionForIndex method.
     *
     * @param  string $uri
     * @param  string $words
     * @param  string|array $type
     * @access public
     * @return mixed
     */
    public function setSessionForIndexTest($uri, $words, $type)
    {
        $this->invokeArgs('setSessionForIndex', [$uri, $words, $type]);
        if(dao::isError()) return dao::getError();

        /* Return session values for verification. */
        $sessionData = new stdclass();
        $sessionData->bugList = $_SESSION['bugList'] ?? '';
        $sessionData->taskList = $_SESSION['taskList'] ?? '';
        $sessionData->productList = $_SESSION['productList'] ?? '';
        $sessionData->searchIngWord = $_SESSION['searchIngWord'] ?? '';
        $sessionData->searchIngType = $_SESSION['searchIngType'] ?? '';

        return $sessionData;
    }
}
