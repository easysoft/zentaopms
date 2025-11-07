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
}
