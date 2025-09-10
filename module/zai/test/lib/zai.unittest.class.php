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

    /**
     * Test formatOldSetting method.
     *
     * @param  object|null $setting
     * @access public
     * @return object|null
     */
    public function formatOldSettingTest($setting)
    {
        $result = $this->objectModel->formatOldSetting($setting);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test setSetting method.
     *
     * @param  object|null $setting
     * @access public
     * @return mixed
     */
    public function setSettingTest($setting)
    {
        $this->objectModel->setSetting($setting);
        if(dao::isError()) return dao::getError();

        return true;
    }

    /**
     * Test getVectorizedInfo method.
     *
     * @access public
     * @return object
     */
    public function getVectorizedInfoTest()
    {
        $result = $this->objectModel->getVectorizedInfo();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test setVectorizedInfo method.
     *
     * @param  object $info
     * @access public
     * @return mixed
     */
    public function setVectorizedInfoTest($info)
    {
        $this->objectModel->setVectorizedInfo($info);
        if(dao::isError()) return dao::getError();

        return true;
    }

    /**
     * Test getNextTarget method.
     *
     * @param  string $type
     * @param  int $id
     * @access public
     * @return object|null
     */
    public function getNextTargetTest($type, $id)
    {
        $result = $this->objectModel->getNextTarget($type, $id);
        if(dao::isError()) return dao::getError();

        if(is_null($result) || $result === false) return false;
        if(is_object($result) && empty((array)$result)) return false;

        return $result;
    }

    /**
     * Test getNextSyncType static method.
     *
     * @param  string $currentType
     * @access public
     * @return string
     */
    public function getNextSyncTypeTest($currentType = '')
    {
        return zaiModel::getNextSyncType($currentType);
    }

    /**
     * Test getSyncTypes static method.
     *
     * @access public
     * @return array
     */
    public function getSyncTypesTest()
    {
        return zaiModel::getSyncTypes();
    }
}
