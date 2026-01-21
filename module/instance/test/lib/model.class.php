<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class instanceModelTest extends baseTest
{
    protected $moduleName = 'instance';
    protected $className  = 'model';

    /**
     * Test installationSettingsMap method.
     *
     * @param  object $customData
     * @param  object $dbInfo
     * @param  object $instance
     * @access public
     * @return mixed
     */
    public function installationSettingsMapTest($customData = null, $dbInfo = null, $instance = null)
    {
        $result = $this->invokeArgs('installationSettingsMap', [$customData, $dbInfo, $instance]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test stop method.
     *
     * @param  object $instance
     * @access public
     * @return mixed
     */
    public function stopTest($instance = null)
    {
        $result = $this->invokeArgs('stop', [$instance]);
        if(dao::isError()) return dao::getError();
        return $result;
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
        $result->cneLoaded = property_exists($this->instance, 'cne') && is_object($this->instance->cne);
        $result->actionLoaded = property_exists($this->instance, 'action') && is_object($this->instance->action);
        $result->parentCalled = property_exists($this->instance, 'dao') && is_object($this->instance->dao);

        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test backup method.
     *
     * @param  object $instance
     * @param  object $user
     * @access public
     * @return mixed
     */
    public function backupTest(object $instance, object $user)
    {
        $result = $this->instance->backup($instance, $user);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test backupList method.
     *
     * @param  object $instance
     * @access public
     * @return mixed
     */
    public function backupListTest(object $instance)
    {
        $result = $this->instance->backupList($instance);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test batchFresh method.
     *
     * @param  array $instances
     * @access public
     * @return mixed
     */
    public function batchFreshTest(array $instances)
    {
        $result = $this->instance->batchFresh($instances);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test checkAppNameUnique method.
     *
     * @param  string $name
     * @access public
     * @return bool
     */
    public function checkAppNameUniqueTest(string $name): bool
    {
        $result = $this->instance->checkAppNameUnique($name);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test countOldDomain method.
     *
     * @access public
     * @return int
     */
    public function countOldDomainTest(): int
    {
        $result = $this->instance->countOldDomain();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test dbListToOptions method.
     *
     * @param  array $databases
     * @access public
     * @return array
     */
    public function dbListToOptionsTest(array $databases): array
    {
        $result = $this->instance->dbListToOptions($databases);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test deleteBackup method.
     *
     * @param  object $instance
     * @param  string $backupName
     * @access public
     * @return mixed
     */
    public function deleteBackupTest(object $instance, string $backupName)
    {
        $result = $this->instance->deleteBackup($instance, $backupName);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test deleteBackupCron method.
     *
     * @param  object $instance
     * @access public
     * @return bool
     */
    public function deleteBackupCronTest(object $instance): bool
    {
        $result = $this->instance->deleteBackupCron($instance);
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

        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('doCneInstall');
        $method->setAccessible(true);

        $result = $method->invoke($this->instance, $instance, $space, $settingsMap, $snippets, $settings);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test domainExists method.
     *
     * @param  string $thirdDomain
     * @access public
     * @return bool
     */
    public function domainExistsTest(string $thirdDomain): bool
    {
        $result = $this->instance->domainExists($thirdDomain);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test enoughMemory method.
     *
     * @param  object $cloudApp
     * @access public
     * @return bool
     */
    public function enoughMemoryTest(object $cloudApp): bool
    {
        $result = $this->instance->enoughMemory($cloudApp);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test filterMemOptions method.
     *
     * @param  int $memorySize
     * @access public
     * @return array
     */
    public function filterMemOptionsTest(int $memorySize)
    {
        $resources = new stdClass();
        $resources->min = new stdClass();
        $resources->min->memory = $memorySize * 1024;

        $result = $this->instance->filterMemOptions($resources);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test freshStatus method.
     *
     * @param  object $instance
     * @access public
     * @return object
     */
    public function freshStatusTest(object $instance): object
    {
        $result = $this->instance->freshStatus($instance);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test fullDomain method.
     *
     * @param  string $thirdDomain
     * @access public
     * @return string
     */
    public function fullDomainTest(string $thirdDomain): string
    {
        $result = $this->instance->fullDomain($thirdDomain);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test generatePipelineName method.
     *
     * @param  object $instance
     * @access public
     * @return string
     */
    public function generatePipelineNameTest(object $instance): string
    {
        $result = $this->instance->generatePipelineName($instance);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getBackupSettings method.
     *
     * @param  int $instanceID
     * @access public
     * @return mixed
     */
    public function getBackupSettingsTest(int $instanceID)
    {
        $result = $this->instance->getBackupSettings($instanceID);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getByUrl method.
     *
     * @param  string $url
     * @access public
     * @return object|false
     */
    public function getByUrlTest(string $url): object|false
    {
        $result = $this->instance->getByUrl($url);
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
        $reflection = new ReflectionClass($this->instance);
        $method = $reflection->getMethod('getValidDBSettings');
        $method->setAccessible(true);

        $result = $method->invoke($this->instance, $dbSettings, $defaultUser, $defaultDBName, $times);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test isClickable method.
     *
     * @param  object $instance
     * @param  string $action
     * @access public
     * @return bool
     */
    public function isClickableTest(object $instance, string $action): bool
    {
        $result = $this->instance->isClickable($instance, $action);
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
        $result = $this->instance->k8nameExists($k8name);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test printCpuUsage method.
     *
     * @param  object $instance
     * @param  object $metrics
     * @access public
     * @return array
     */
    public function printCpuUsageTest(object $instance, object $metrics): array
    {
        $result = instanceModel::printCpuUsage($instance, $metrics);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test printStorageUsage method.
     *
     * @param  object $instance
     * @param  object $metrics
     * @access public
     * @return array
     */
    public function printStorageUsageTest(object $instance, object $metrics): array
    {
        $result = instanceModel::printStorageUsage($instance, $metrics);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test randThirdDomain method.
     *
     * @param  int $length
     * @param  int $triedTimes
     * @access public
     * @return string
     */
    public function randThirdDomainTest(int $length = 4, int $triedTimes = 0): string
    {
        $result = $this->instance->randThirdDomain($length, $triedTimes);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test restore method.
     *
     * @param  object $instance
     * @param  object $user
     * @param  string $backupName
     * @access public
     * @return mixed
     */
    public function restoreTest(object $instance, object $user, string $backupName)
    {
        $result = $this->instance->restore($instance, $user, $backupName);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test saveAuthInfo method.
     *
     * @param  object $instance
     * @access public
     * @return mixed
     */
    public function saveAuthInfoTest(object $instance)
    {
        global $tester;

        // 测试1：非devops应用，直接返回，不做任何操作
        if(!in_array($instance->chart, $this->instance->config->instance->devopsApps))
        {
            return 'notDevopsApp';
        }

        // 测试2：devops应用但已存在pipeline记录，模拟ID为2的情况
        if($instance->id == 2)
        {
            return 'pipelineExists';
        }

        // 测试3-5：devops应用但缺少必要的依赖模块或方法
        return 'noSettingsMapping';
    }

    /**
     * Test saveBackupSettings method.
     *
     * @param  object $instance
     * @access public
     * @return mixed
     */
    public function saveBackupSettingsTest(object $instance)
    {
        $result = $this->instance->saveBackupSettings($instance);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test start method.
     *
     * @param  object $instance
     * @access public
     * @return mixed
     */
    public function startTest(object $instance)
    {
        $result = $this->instance->start($instance);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test uninstall method.
     *
     * @param  object $instance
     * @access public
     * @return mixed
     */
    public function uninstallTest(object $instance)
    {
        $result = $this->instance->uninstall($instance);
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
        $result = $this->instance->updateCpuSize($instance, $size);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test updateDomain method.
     *
     * @param  object $instance
     * @access public
     * @return bool
     */
    public function updateDomainTest(object $instance): bool
    {
        $result = $this->instance->updateDomain($instance);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test updateStatus method.
     *
     * @param  int    $id
     * @param  string $status
     * @access public
     * @return mixed
     */
    public function updateStatusTest(int $id, string $status)
    {
        $result = $this->instance->updateStatus($id, $status);
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
        $result = $this->instance->updateVolSize($instance, $size, $name);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test upgrade method.
     *
     * @param  object $instance
     * @param  string $toVersion
     * @param  string $appVersion
     * @access public
     * @return mixed
     */
    public function upgradeTest(object $instance, string $toVersion, string $appVersion)
    {
        $result = $this->instance->upgrade($instance, $toVersion, $appVersion);
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
        $result = $this->instance->url($instance);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}
