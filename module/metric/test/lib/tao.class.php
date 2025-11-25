<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class metricTaoTest extends baseTest
{
    protected $moduleName = 'metric';
    protected $className  = 'tao';

    /**
     * Test getObjectsWithPager method.
     *
     * @param  object      $metric
     * @param  object      $query
     * @param  object|null $pager
     * @param  array       $extra
     * @access public
     * @return mixed
     */
    public function getObjectsWithPagerTest($metric = null, $query = null, $pager = null, $extra = array())
    {
        $result = $this->invokeArgs('getObjectsWithPager', [$metric, $query, $pager, $extra]);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
