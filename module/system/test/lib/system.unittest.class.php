<?php
declare(strict_types = 1);
class systemTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('system');
    }

    /**
     * Test updateMinioDomain method.
     *
     * @access public
     * @return mixed
     */
    public function updateMinioDomainTest()
    {
        $result = $this->objectModel->updateMinioDomain();
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
        $result = $this->objectModel->getBackupStatus($instance, $backupName);
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
        $result = $this->objectModel->getBackupList($instance);
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
        $result = $this->objectModel->restore($instance, $backupName, $account);
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
        $result = $this->objectModel->deleteBackup($instance, $backupName);
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
        $result = $this->objectModel->setMaintenance($action);
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
        $this->objectModel->unsetMaintenance();
        if(dao::isError()) return dao::getError();

        // 检查维护模式配置是否被删除
        $maintenance = $this->objectModel->loadModel('setting')->getItem('owner=system&module=system&key=maintenance');
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
        $result = $this->objectModel->getLatestRelease();
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
        $result = $this->objectModel->isUpgradeable();
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
        $result = $this->objectModel->setSystemRelease($systemID, $releaseID, $releasedDate);
        if(dao::isError()) return dao::getError();

        return $result ? '1' : '0';
    }
}