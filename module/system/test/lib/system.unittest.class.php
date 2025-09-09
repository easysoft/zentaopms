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

        return $result;
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
}