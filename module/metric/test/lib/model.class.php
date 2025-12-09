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
     * @param  bool   $checkResult
     * @access public
     * @return string|int
     */
    public function getDateByDateTypeTest(string $dateType = '', bool $checkResult = false)
    {
        /* Suppress error output for invalid dateType to test gracefully. */
        ob_start();
        $oldErrorReporting = error_reporting(0);
        $result = $this->invokeArgs('getDateByDateType', [$dateType]);
        error_reporting($oldErrorReporting);
        ob_end_clean();

        if(dao::isError()) return dao::getError();

        /* If checkResult is true, compare with expected date and return 1 for match, 0 for mismatch. */
        if($checkResult)
        {
            $expected = '';
            if($dateType == 'day')   $expected = date('Y-m-d', strtotime('-7 days'));
            if($dateType == 'week')  $expected = date('Y-m-d', strtotime('-1 month'));
            if($dateType == 'month') $expected = date('Y-m-d', strtotime('-1 year'));
            if($dateType == 'year')  $expected = date('Y-m-d', strtotime('-3 years'));
            if($dateType == '' || !in_array($dateType, array('day', 'week', 'month', 'year'))) $expected = '1970-01-01';

            return $result == $expected ? 1 : 0;
        }

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
