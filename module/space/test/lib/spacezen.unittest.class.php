<?php
class spaceZenTest
{
    public $spaceZenTest;
    public $tester;
    function __construct()
    {
        global $tester;
        $this->tester = $tester;
        $tester->app->setModuleName('space');
        $tester->loadModel('space');

        $this->spaceZenTest = initReference('space');
    }

    /**
     * 获取空间下的应用实例 (zen版本)。
     * Get space instances (zen version).
     *
     * @param  string $browseType
     * @access public
     * @return array
     */
    public function getSpaceInstancesZenTest(string $browseType = 'all'): int
    {
        $method = $this->spaceZenTest->getMethod('getSpaceInstances');
        $method->setAccessible(true);

        $instances = $method->invokeArgs($this->spaceZenTest->newInstance(), array("all"));
        if(dao::isError()) return dao::getError();
        if (count($instances) > 0) {
            return 1;
        } else {
            return 0;
        }
    }
}
