<?php
declare(strict_types = 1);
class instanceTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('instance');
        $this->objectTao   = $tester->loadTao('instance');
    }

    /**
     * Test __construct method.
     *
     * @access public
     * @return mixed
     */
    public function __constructTest()
    {
        $result = new stdClass();
        $result->cneLoaded = property_exists($this->objectModel, 'cne') && is_object($this->objectModel->cne);
        $result->actionLoaded = property_exists($this->objectModel, 'action') && is_object($this->objectModel->action);
        $result->parentCalled = property_exists($this->objectModel, 'dao') && is_object($this->objectModel->dao);

        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test updateInstancesDomain method.
     *
     * @access public
     * @return mixed
     */
    public function updateInstancesDomainTest()
    {
        $result = $this->objectModel->updateInstancesDomain();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test updateCpuSize method.
     *
     * @param  object     $instance
     * @param  int|string $size
     * @access public
     * @return mixed
     */
    public function updateCpuSizeTest(object $instance, int|string $size)
    {
        $result = $this->objectModel->updateCpuSize($instance, $size);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test updateVolSize method.
     *
     * @param  object     $instance
     * @param  int|string $size
     * @param  string     $name
     * @access public
     * @return mixed
     */
    public function updateVolSizeTest(object $instance, int|string $size, string $name)
    {
        $result = $this->objectModel->updateVolSize($instance, $size, $name);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test url method.
     *
     * @param  object $instance
     * @access public
     * @return string
     */
    public function urlTest(object $instance): string
    {
        $result = $this->objectModel->url($instance);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test k8nameExists method.
     *
     * @param  string $k8name
     * @access public
     * @return mixed
     */
    public function k8nameExistsTest(string $k8name)
    {
        $result = $this->objectModel->k8nameExists($k8name);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test installationSettingsMap method.
     *
     * @param  object $customData
     * @param  object $dbInfo
     * @param  object $instance
     * @access public
     * @return mixed
     */
    public function installationSettingsMapTest(object $customData, object $dbInfo, object $instance)
    {
        $result = $this->objectModel->installationSettingsMap($customData, $dbInfo, $instance);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getValidDBSettings method.
     *
     * @param  object $dbSettings
     * @param  string $defaultUser
     * @param  string $defaultDBName
     * @param  int    $times
     * @access public
     * @return mixed
     */
    public function getValidDBSettingsTest(object $dbSettings, string $defaultUser, string $defaultDBName, int $times = 1)
    {
        $reflection = new ReflectionClass($this->objectModel);
        $method = $reflection->getMethod('getValidDBSettings');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->objectModel, $dbSettings, $defaultUser, $defaultDBName, $times);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test install method.
     *
     * @param  object $app
     * @param  object $dbInfo
     * @param  object $customData
     * @param  int    $spaceID
     * @param  array  $settings
     * @access public
     * @return mixed
     */
    public function installTest(object $app, object $dbInfo, object $customData, ?int $spaceID = null, array $settings = array())
    {
        $result = $this->objectModel->install($app, $dbInfo, $customData, $spaceID, $settings);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test installSysSLB method.
     *
     * @param  object $app
     * @param  string $k8name
     * @param  string $channel
     * @access public
     * @return mixed
     */
    public function installSysSLBTest(object $app, string $k8name = 'cne-lb', string $channel = 'stable')
    {
        $result = $this->objectModel->installSysSLB($app, $k8name, $channel);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test createInstance method.
     *
     * @param  object $app
     * @param  object $space
     * @param  string $thirdDomain
     * @param  string $name
     * @param  string $k8name
     * @param  string $channel
     * @param  array  $snippets
     * @access public
     * @return mixed
     */
    public function createInstanceTest(object $app, object $space, string $thirdDomain, string $name = '', string $k8name = '', string $channel = 'stable', array $snippets = array())
    {
        $result = $this->objectModel->createInstance($app, $space, $thirdDomain, $name, $k8name, $channel, $snippets);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test doCneInstall method.
     *
     * @param  object|null $instance
     * @param  object      $space
     * @param  object      $settingsMap
     * @param  array       $snippets
     * @param  array       $settings
     * @access public
     * @return mixed
     */
    public function doCneInstallTest($instance, object $space, object $settingsMap, array $snippets = array(), array $settings = array())
    {
        if($instance === null) return false;
        
        $reflection = new ReflectionClass($this->objectModel);
        $method = $reflection->getMethod('doCneInstall');
        $method->setAccessible(true);
        
        $result = $method->invoke($this->objectModel, $instance, $space, $settingsMap, $snippets, $settings);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}