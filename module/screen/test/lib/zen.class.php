<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class screenZenTest extends baseTest
{
    protected $moduleName = 'screen';
    protected $className  = 'zen';

    /**
     * Test commonAction method.
     *
     * @param  int  $dimensionID
     * @param  bool $setMenu
     * @access public
     * @return mixed
     */
    public function commonActionTest($dimensionID, $setMenu = true)
    {
        $result = $this->invokeArgs('commonAction', [$dimensionID, $setMenu]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test prepareCardList method.
     *
     * @param  array $screens
     * @access public
     * @return array
     */
    public function prepareCardListTest(array $screens): array
    {
        $result = $this->invokeArgs('prepareCardList', [$screens]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test setSelectFilter method.
     *
     * @param  string $sourceID
     * @param  array  $filters
     * @access public
     * @return mixed
     */
    public function setSelectFilterTest($sourceID, $filters)
    {
        $result = $this->invokeArgs('setSelectFilter', [$sourceID, $filters]);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
