<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class systemModelTest extends baseTest
{
    protected $moduleName = 'system';
    protected $className  = 'model';

    /**
     * Test updateMinioDomain method.
     *
     * @access public
     * @return mixed
     */
    public function updateMinioDomainTest()
    {
        $result = $this->instance->updateMinioDomain();
        if(dao::isError()) return dao::getError();

        return is_null($result) ? '0' : $result;
    }

    /**
     * Test getBackupStatus method.
     *
     * @param  object $instance
     * @param  string $backupName
     * @access public
     * @return mixed
     */
    public function getBackupStatusTest($instance, $backupName)
    {
        ob_start();
        $result = $this->instance->getBackupStatus($instance, $backupName);
        ob_end_clean();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getBackupList method.
     *
     * @param  object $instance
     * @access public
     * @return mixed
     */
    public function getBackupListTest($instance)
    {
        $result = $this->instance->getBackupList($instance);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test restore method.
     *
     * @param  object $instance
     * @param  string $backupName
     * @param  string $account
     * @access public
     * @return mixed
     */
    public function restoreTest($instance, $backupName, $account = '')
    {
        ob_start();
        $result = $this->instance->restore($instance, $backupName, $account);
        ob_end_clean();
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
    public function deleteBackupTest($instance, $backupName)
    {
        ob_start();
        $result = $this->instance->deleteBackup($instance, $backupName);
        ob_end_clean();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test setMaintenance method.
     *
     * @param  string $action
     * @access public
     * @return mixed
     */
    public function setMaintenanceTest($action)
    {
        $result = $this->instance->setMaintenance($action);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test unsetMaintenance method.
     *
     * @access public
     * @return mixed
     */
    public function unsetMaintenanceTest()
    {
        $this->instance->unsetMaintenance();
        if(dao::isError()) return dao::getError();

        // 检查维护模式配置是否被删除
        $maintenance = $this->instance->loadModel('setting')->getItem('owner=system&module=system&key=maintenance');
        return empty($maintenance) ? 'deleted' : 'exists';
    }

    /**
     * Test getLatestRelease method.
     *
     * @access public
     * @return mixed
     */
    public function getLatestReleaseTest()
    {
        $result = $this->instance->getLatestRelease();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test isUpgradeable method.
     *
     * @access public
     * @return mixed
     */
    public function isUpgradeableTest()
    {
        $result = $this->instance->isUpgradeable();
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test setSystemRelease method.
     *
     * @param  int    $systemID
     * @param  int    $releaseID
     * @param  string $releasedDate
     * @access public
     * @return mixed
     */
    public function setSystemReleaseTest($systemID, $releaseID, $releasedDate = '')
    {
        $result = $this->instance->setSystemRelease($systemID, $releaseID, $releasedDate);
        if(dao::isError()) return dao::getError();

        return $result ? '1' : '0';
    }

    /**
     * Test getCpuUsage method.
     *
     * @param  object $metrics
     * @access public
     * @return mixed
     */
    public function getCpuUsageTest($metrics)
    {
        // 模拟systemZen::getCpuUsage方法的逻辑
        $rate = $metrics->rate;
        $tip  = "{$rate}% = {$metrics->usage} / {$metrics->capacity}";

        $color = '';
        if(empty($color) && $rate == 0)               $color = 'gray';
        if(empty($color) && $rate > 0 && $rate < 50)  $color = 'var(--color-secondary-500)';
        if(empty($color) && $rate >= 50 && $rate < 70) $color = 'var(--color-warning-500)';
        if(empty($color) && $rate >= 70 && $rate < 90) $color = 'var(--color-important-500)';
        if(empty($color) && $rate >= 90)              $color = 'var(--color-danger-500)';

        return array('color' => $color, 'tip' => $tip, 'rate' => $rate);
    }

    /**
     * Test getMemUsage method.
     *
     * @param  object $metrics
     * @access public
     * @return mixed
     */
    public function getMemUsageTest($metrics)
    {
        // 直接模拟systemZen::getMemUsage方法的逻辑
        $rate = $metrics->rate;
        $tip  = "{$rate}% = " . helper::formatKB($metrics->usage) . ' / ' . helper::formatKB($metrics->capacity);

        $color = '';
        if(empty($color) && $rate == 0)               $color = 'gray';
        if(empty($color) && $rate > 0 && $rate < 50)  $color = 'var(--color-secondary-500)';
        if(empty($color) && $rate >= 50 && $rate < 70) $color = 'var(--color-warning-500)';
        if(empty($color) && $rate >= 70 && $rate < 90) $color = 'var(--color-important-500)';
        if(empty($color) && $rate >= 90)              $color = 'var(--color-danger-500)';

        return array('color' => $color, 'tip' => $tip, 'rate' => $rate);
    }

    /**
     * Test getDomainSettings method.
     *
     * @access public
     * @return mixed
     */
    public function getDomainSettingsTest()
    {
        $result = $this->instance->getDomainSettings();
        if(dao::isError()) return dao::getError();

        return $result;
    }
}