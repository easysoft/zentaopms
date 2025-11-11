<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class ssoZenTest extends baseTest
{
    protected $moduleName = 'sso';
    protected $className  = 'zen';

    /**
     * Test buildLocationByGET method.
     *
     * @param  string $location
     * @param  string $referer
     * @access public
     * @return string
     */
    public function buildLocationByGETTest(string $location, string $referer): string
    {
        $result = $this->invokeArgs('buildLocationByGET', [$location, $referer]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test buildLocationByPATHINFO method.
     *
     * @param  string $location
     * @param  string $referer
     * @access public
     * @return string
     */
    public function buildLocationByPATHINFOTest(string $location, string $referer): string
    {
        $result = $this->invokeArgs('buildLocationByPATHINFO', [$location, $referer]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test buildSSOParams method.
     *
     * @param  string $referer
     * @access public
     * @return string
     */
    public function buildSSOParamsTest(string $referer): string
    {
        $result = $this->invokeArgs('buildSSOParams', [$referer]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test buildUserForCreate method.
     *
     * @access public
     * @return object
     */
    public function buildUserForCreateTest(): object
    {
        $result = $this->invokeArgs('buildUserForCreate', []);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
