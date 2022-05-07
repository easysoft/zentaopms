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

    /**
     * Test bind a user.
     *
     * @param  array  $param
     * @param  string $bindUser
     * @access public
     * @return void
     */
    public function bindTest($param = array(), $bindUser = '')
    {
        global $tester;
        if($bindUser)
        {
            $tester->session->ssoData = new stdClass();
            $tester->session->ssoData->account = $bindUser;
        }

        foreach($param as $key => $value) $_POST[$key] = $value;
        if(isset($param['password1'])) $_POST['passwordStrength'] = strlen($param['password1']);

        $object = $this->objectModel->bind();

        unset($_POST);

        if(!$object) return 'fail';
        if(is_int($object)) return 'user get error';

        return $object;
    }
}
