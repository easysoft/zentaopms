<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class reportZenTest extends baseTest
{
    protected $moduleName = 'report';
    protected $className  = 'zen';

    /**
     * Test assignAnnualBaseData method.
     *
     * @param  string $account
     * @param  string $dept
     * @param  string $year
     * @access public
     * @return mixed
     */
    public function assignAnnualBaseDataTest(string $account, string $dept, string $year)
    {
        $result = $this->invokeArgs('assignAnnualBaseData', [$account, $dept, $year]);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
