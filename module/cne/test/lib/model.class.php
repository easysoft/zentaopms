<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class cneModelTest extends baseTest
{
    protected $moduleName = 'cne';
    protected $className  = 'model';

    /**
     * Test apiGet method.
     *
     * @param  string       $url
     * @param  array|object $data
     * @param  array        $header
     * @param  string       $host
     * @access public
     * @return mixed
     */
    public function apiGetTest(string $url, array|object $data, array $header = array(), string $host = '')
    {
        $result = $this->invokeArgs('apiGet', [$url, $data, $header, $host]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test apiPost method.
     *
     * @param  string       $url
     * @param  array|object $data
     * @param  array        $header
     * @param  string       $host
     * @access public
     * @return mixed
     */
    public function apiPostTest(string $url, array|object $data, array $header = array(), string $host = '')
    {
        $result = $this->invokeArgs('apiPost', [$url, $data, $header, $host]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test backup method.
     *
     * @param  object      $instance
     * @param  string|null $account
     * @param  string      $mode
     * @access public
     * @return mixed
     */
    public function backupTest(object $instance, string|null $account = '', string $mode = '')
    {
        $result = $this->invokeArgs('backup', [$instance, $account, $mode]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test cneServerError method.
     *
     * @access public
     * @return object
     */
    public function cneServerErrorTest()
    {
        $result = $this->invokeArgs('cneServerError', []);
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
        $result = $this->invokeArgs('deleteBackup', [$instance, $backupName]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getAppVolumes method.
     *
     * @param  object      $instance
     * @param  bool|string $component
     * @access public
     * @return mixed
     */
    public function getAppVolumesTest(object $instance, bool|string $component = false)
    {
        $result = $this->invokeArgs('getAppVolumes', [$instance, $component]);
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
    public function getBackupListTest(object $instance)
    {
        $result = $this->invokeArgs('getBackupList', [$instance]);
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
    public function getBackupStatusTest(object $instance, string $backupName)
    {
        $result = $this->invokeArgs('getBackupStatus', [$instance, $backupName]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getDiskSettings method.
     *
     * @param  object      $instance
     * @param  bool|string $component
     * @access public
     * @return mixed
     */
    public function getDiskSettingsTest(object $instance, bool|string $component = false)
    {
        $result = $this->invokeArgs('getDiskSettings', [$instance, $component]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getVolumesMetrics method.
     *
     * @param  object $instance
     * @access public
     * @return mixed
     */
    public function getVolumesMetricsTest(object $instance)
    {
        $result = $this->invokeArgs('getVolumesMetrics', [$instance]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test installApp method.
     *
     * @param  object $apiParams
     * @access public
     * @return mixed
     */
    public function installAppTest(object $apiParams)
    {
        $result = $this->invokeArgs('installApp', [$apiParams]);
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
    public function restoreTest(object $instance, string $backupName, string $account = '')
    {
        $result = $this->invokeArgs('restore', [$instance, $backupName, $account]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test translateError method.
     *
     * @param  object $apiResult
     * @access public
     * @return object
     */
    public function translateErrorTest(object &$apiResult)
    {
        $cne = $this->getInstance('cne', 'model');
        $reflection = new ReflectionClass($cne);
        $method = $reflection->getMethod('translateError');
        $method->setAccessible(true);
        $result = $method->invokeArgs($cne, array(&$apiResult));
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
