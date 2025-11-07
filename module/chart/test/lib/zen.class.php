<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class chartZenTest extends baseTest
{
    protected $moduleName = 'chart';
    protected $className  = 'zen';

    /**
     * Test getChartsToView method.
     *
     * @param  array $chartList
     * @access public
     * @return array|string
     */
    public function getChartsToViewTest(array $chartList = array())
    {
        $result = $this->invokeArgs('getChartsToView', array($chartList));
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
