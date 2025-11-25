<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class searchModelTest extends baseTest
{
    protected $moduleName = 'search';
    protected $className  = 'model';

    /**
     * Test buildOldQuery method.
     *
     * @access public
     * @return mixed
     */
    public function buildOldQueryTest()
    {
        $result = $this->invokeArgs('buildOldQuery', []);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getOldQuery method.
     *
     * @param  int $queryID
     * @access public
     * @return object|false
     */
    public function getOldQueryTest(int $queryID)
    {
        $result = $this->invokeArgs('getOldQuery', [$queryID]);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
