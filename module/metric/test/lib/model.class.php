<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class metricModelTest extends baseTest
{
    protected $moduleName = 'metric';
    protected $className  = 'model';

    /**
     * Test getDateByDateType method.
     *
     * @param  string $dateType
     * @access public
     * @return string
     */
    public function getDateByDateTypeTest(string $dateType = '')
    {
        $result = $this->invokeArgs('getDateByDateType', [$dateType]);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
