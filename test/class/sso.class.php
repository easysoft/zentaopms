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
     * @param  array  $param
     * @access public
     * @return void
     */
    public function createTest($param = array())
    {
        foreach($param as $key => $value) $_POST[$key] = $value;

        $result = $this->objectModel->createUser();

        unset($_POST);
        if($result['status'] == 'fail') return 'fail';

        $object = $this->objectModel->getBindUser($param['account']);
        return $object;
    }
}
