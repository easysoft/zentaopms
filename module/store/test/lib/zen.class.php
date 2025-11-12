<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class storeZenTest extends baseTest
{
    protected $moduleName = 'store';
    protected $className  = 'zen';

    /**
     * Test getInstalledApps method.
     *
     * @access public
     * @return array
     */
    public function getInstalledAppsTest()
    {
        $result = $this->invokeArgs('getInstalledApps', []);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
