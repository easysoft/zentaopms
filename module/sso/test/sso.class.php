<?php
class ssoTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('sso');
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
}
