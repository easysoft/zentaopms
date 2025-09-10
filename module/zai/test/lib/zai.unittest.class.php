<?php
declare(strict_types = 1);
class zaiTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('zai');
    }

    public function getSettingTest($includeAdmin = false): ?object
    {
        $result = $this->objectModel->getSetting($includeAdmin);
        return $result;
    }

    /**
     * Test getToken method.
     *
     * @param  object|null $zaiConfig
     * @param  bool $admin
     * @access public
     * @return array
     */
    public function getTokenTest($zaiConfig = null, $admin = false)
    {
        $result = $this->objectModel->getToken($zaiConfig, $admin);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}
