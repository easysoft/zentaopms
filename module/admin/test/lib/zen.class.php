<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class adminZenTest extends baseTest
{
    protected $moduleName = 'admin';
    protected $className  = 'zen';

    /**
     * Test certifyByAPI method.
     *
     * @param  string $type mobile|email
     * @access public
     * @return mixed
     */
    public function certifyByAPITest(string $type = 'mobile')
    {
        $result = $this->invokeArgs('certifyByAPI', [$type]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test fetchAPI method.
     *
     * @param  string $url
     * @access public
     * @return mixed
     */
    public function fetchAPITest(string $url = '')
    {
        $result = $this->invokeArgs('fetchAPI', [$url]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getZentaoData method.
     *
     * @access public
     * @return mixed
     */
    public function getZentaoDataTest()
    {
        $result = $this->invokeArgs('getZentaoData', []);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test sendCodeByAPI method.
     *
     * @param  string $type mobile|email
     * @access public
     * @return mixed
     */
    public function sendCodeByAPITest(string $type = 'mobile')
    {
        $result = $this->invokeArgs('sendCodeByAPI', [$type]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test setCompanyByAPI method.
     *
     * @access public
     * @return mixed
     */
    public function setCompanyByAPITest()
    {
        $result = $this->invokeArgs('setCompanyByAPI', []);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test syncDynamics method.
     *
     * @param  int $limit
     * @access public
     * @return mixed
     */
    public function syncDynamicsTest(int $limit = 2)
    {
        $result = $this->invokeArgs('syncDynamics', [$limit]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test syncExtensions method.
     *
     * @param  string $type  plugin|patch
     * @param  int    $limit
     * @access public
     * @return mixed
     */
    public function syncExtensionsTest(string $type = 'plugin', int $limit = 5)
    {
        $result = $this->invokeArgs('syncExtensions', [$type, $limit]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test syncPublicClasses method.
     *
     * @param  int $limit
     * @access public
     * @return mixed
     */
    public function syncPublicClassesTest(int $limit = 3)
    {
        $result = $this->invokeArgs('syncPublicClasses', [$limit]);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
