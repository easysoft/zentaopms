<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class companyZenTest extends baseTest
{
    protected $moduleName = 'company';
    protected $className  = 'zen';

    /**
     * Test buildDyanmicSearchForm method.
     *
     * @param  array  $products
     * @param  array  $projects
     * @param  array  $executions
     * @param  int    $userID
     * @param  int    $queryID
     * @access public
     * @return mixed
     */
    public function buildDyanmicSearchFormTest($products = array(), $projects = array(), $executions = array(), $userID = 0, $queryID = 0)
    {
        $result = $this->invokeArgs('buildDyanmicSearchForm', [$products, $projects, $executions, $userID, $queryID]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test loadAllSearchModule method.
     *
     * @param  int    $userID
     * @param  mixed  $queryID
     * @access public
     * @return mixed
     */
    public function loadAllSearchModuleTest($userID = 0, $queryID = 0)
    {
        $result = $this->invokeArgs('loadAllSearchModule', [$userID, $queryID]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test loadExecution method.
     *
     * @access public
     * @return array
     */
    public function loadExecutionTest()
    {
        $result = $this->invokeArgs('loadExecution', []);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test loadProduct method.
     *
     * @access public
     * @return array
     */
    public function loadProductTest()
    {
        $result = $this->invokeArgs('loadProduct', []);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test loadProject method.
     *
     * @access public
     * @return array
     */
    public function loadProjectTest()
    {
        $result = $this->invokeArgs('loadProject', []);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test loadUserModule method.
     *
     * @param  int    $userID
     * @access public
     * @return array
     */
    public function loadUserModuleTest($userID = 0)
    {
        $result = $this->invokeArgs('loadUserModule', [$userID]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test saveUriIntoSession method.
     *
     * @param  string $testUri
     * @access public
     * @return bool
     */
    public function saveUriIntoSessionTest(string $testUri = ''): bool
    {
        if($testUri) $this->instance->app->uri = $testUri;

        $this->invokeArgs('saveUriIntoSession', []);
        if(dao::isError()) return false;

        return true;
    }
}
