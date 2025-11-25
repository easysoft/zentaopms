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

    /**
     * Test getChartToFilter method.
     *
     * @param  int   $groupID
     * @param  int   $chartID
     * @param  array $filterValues
     * @access public
     * @return object|null|string
     */
    public function getChartToFilterTest(int $groupID = 0, int $chartID = 0, array $filterValues = array())
    {
        $result = $this->invokeArgs('getChartToFilter', array($groupID, $chartID, $filterValues));
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getMenuItems method.
     *
     * @param  array $menus
     * @access public
     * @return array
     */
    public function getMenuItemsTest(array $menus = array())
    {
        $result = $this->invokeArgs('getMenuItems', array($menus));
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
