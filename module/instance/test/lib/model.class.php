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
}
