<?php
declare(strict_types = 1);
class ssoTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('sso');
         $this->objectZen   = $tester->loadZen('sso');
    }

    /**
     * Test create a user.
     *
     * @param  object  $user
     * @access public
     * @return array
     */
    public function createTest($user)
    {
        return $this->objectModel->createUser($user);
    }

    /**
     * Test getFeishuAccessToken method.
     *
     * @param  object $appConfig
     * @access public
     * @return array
     */
    public function getFeishuAccessTokenTest($appConfig)
    {
        $result = $this->objectZen->getFeishuAccessToken($appConfig);
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
    public function getFeishuUserTokenTest($code, $accessToken)
    {
        $result = $this->objectZen->getFeishuUserToken($code, $accessToken);
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
    public function getBindFeishuUserTest($userToken, $feishuConfig)
    {
        $result = $this->objectZen->getBindFeishuUser($userToken, $feishuConfig);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}
