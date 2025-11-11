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

    /**
     * Test computeAuth method.
     *
     * @param  string $token
     * @access public
     * @return string
     */
    public function computeAuthTest(string $token): string
    {
        $result = $this->invokeArgs('computeAuth', [$token]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getBindFeishuUser method.
     *
     * @param  string $userToken
     * @param  object $feishuConfig
     * @access public
     * @return array
     */
    public function getBindFeishuUserTest(string $userToken, object $feishuConfig): array
    {
        $result = $this->invokeArgs('getBindFeishuUser', [$userToken, $feishuConfig]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getFeishuAccessToken method.
     *
     * @param  object $appConfig
     * @access public
     * @return array
     */
    public function getFeishuAccessTokenTest(object $appConfig): array
    {
        $result = $this->invokeArgs('getFeishuAccessToken', [$appConfig]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getFeishuUserToken method.
     *
     * @param  string $code
     * @param  string $accessToken
     * @access public
     * @return array
     */
    public function getFeishuUserTokenTest(string $code, string $accessToken): array
    {
        $result = $this->invokeArgs('getFeishuUserToken', [$code, $accessToken]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test idenfyFromSSO method.
     *
     * @param  string $locate
     * @access public
     * @return bool
     */
    public function idenfyFromSSOTest(string $locate): bool
    {
        $result = $this->invokeArgs('idenfyFromSSO', [$locate]);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
