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
}
