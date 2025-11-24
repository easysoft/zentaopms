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

    /**
     * Test getLatestResultByCode method.
     *
     * @param  string $code
     * @param  array  $options
     * @param  object|null $pager
     * @param  string $vision
     * @access public
     * @return array|bool
     */
    public function getLatestResultByCodeTest(string $code = '', array $options = array(), $pager = null, string $vision = 'rnd')
    {
        /* Check if metric code exists first to avoid fatal error. */
        $metric = $this->instance->getByCode($code);
        if(empty($metric)) return array();

        $result = $this->invokeArgs('getLatestResultByCode', [$code, $options, $pager, $vision]);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
