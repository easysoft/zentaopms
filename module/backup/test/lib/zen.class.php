<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class backupZenTest extends baseTest
{
    protected $moduleName = 'backup';
    protected $className  = 'zen';

    /**
     * Test backupCode method.
     *
     * @param  string $fileName 文件名
     * @param  string $reload   重载参数
     * @access public
     * @return mixed
     */
    public function backupCodeTest(string $fileName = '', string $reload = 'no')
    {
        $result = $this->invokeArgs('backupCode', [$fileName, $reload]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test backupCode method with nofile setting.
     *
     * @access public
     * @return mixed
     */
    public function backupCodeTestWithNofile()
    {
        global $config;
        $oldSetting = $config->backup->setting;
        $config->backup->setting = 'nofile';
        $result = $this->invokeArgs('backupCode', ['test_nofile']);
        $config->backup->setting = $oldSetting;
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test backupFile method.
     *
     * @param  string $fileName 文件名
     * @param  string $reload   重载参数
     * @access public
     * @return mixed
     */
    public function backupFileTest(string $fileName = '', string $reload = 'no')
    {
        $result = $this->invokeArgs('backupFile', [$fileName, $reload]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test backupFile method with nofile setting.
     *
     * @access public
     * @return mixed
     */
    public function backupFileTestWithNofile()
    {
        global $config;
        $oldSetting = $config->backup->setting;
        $config->backup->setting = 'nofile';
        $result = $this->invokeArgs('backupFile', ['test_nofile']);
        $config->backup->setting = $oldSetting;
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test backupSQL method.
     *
     * @param  string $fileName 文件名
     * @param  string $reload   重载参数
     * @access public
     * @return mixed
     */
    public function backupSQLTest(string $fileName = '', string $reload = 'no')
    {
        $result = $this->invokeArgs('backupSQL', [$fileName, $reload]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test backupSQL method with nosafe setting.
     *
     * @access public
     * @return mixed
     */
    public function backupSQLTestWithNosafe()
    {
        global $config;
        $oldSetting = $config->backup->setting;
        $config->backup->setting = 'nosafe';
        $result = $this->invokeArgs('backupSQL', ['test_nosafe_' . time()]);
        $config->backup->setting = $oldSetting;
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getBackupList method.
     *
     * @access public
     * @return array
     */
    public function getBackupListTest()
    {
        $result = $this->invokeArgs('getBackupList', []);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test removeExpiredFiles method.
     *
     * @access public
     * @return void
     */
    public function removeExpiredFilesTest()
    {
        $result = $this->invokeArgs('removeExpiredFiles', []);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test restoreFile method.
     *
     * @param  string $fileName 文件名
     * @access public
     * @return mixed
     */
    public function restoreFileTest(string $fileName = '')
    {
        $result = $this->invokeArgs('restoreFile', [$fileName]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test restoreSQL method.
     *
     * @param  string $fileName 文件名
     * @access public
     * @return mixed
     */
    public function restoreSQLTest(string $fileName = '')
    {
        $result = $this->invokeArgs('restoreSQL', [$fileName]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test setHoldDays method.
     *
     * @param  mixed $holdDays 保留天数
     * @access public
     * @return mixed
     */
    public function setHoldDaysTest($holdDays = null)
    {
        $data = new stdclass();
        if(!is_null($holdDays)) $data->holdDays = $holdDays;

        $result = $this->invokeArgs('setHoldDays', [$data]);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
