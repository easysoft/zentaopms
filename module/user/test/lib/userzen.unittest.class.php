<?php
declare(strict_types = 1);
class userZenTest
{
    public $userZenTest;
    public $tester;

    function __construct()
    {
        global $tester;
        $this->tester = $tester;
        $tester->app->setModuleName('user');

        $this->objectModel = $tester->loadModel('user');
        $this->userZenTest = initReference('user');
    }

    /**
     * Test checkDirPermission method.
     *
     * @access public
     * @return mixed
     */
    public function checkDirPermissionTest()
    {
        try {
            $result = callZenMethod('user', 'checkDirPermission', array());
            if(dao::isError()) return dao::getError();
            return 'success';
        } catch (EndResponseException $e) {
            return 'permission_denied';
        } catch (Exception $e) {
            return 'exception: ' . $e->getMessage();
        }
    }

    /**
     * Test checkTmp method.
     *
     * @access public
     * @return mixed
     */
    public function checkTmpTest()
    {
        $result = callZenMethod('user', 'checkTmp', array());
        if(dao::isError()) return dao::getError();

        return $result;
    }
}