<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class systemZenTest extends baseTest
{
    protected $moduleName = 'system';
    protected $className  = 'zen';

    /**
     * Test getCpuUsage method.
     *
     * @param  object $metrics
     * @access public
     * @return mixed
     */
    public function getCpuUsageTest(object $metrics)
    {
        $result = $this->invokeArgs('getCpuUsage', [$metrics]);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
