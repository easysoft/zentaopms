<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class designZenTest extends baseTest
{
    protected $moduleName = 'design';
    protected $className  = 'zen';

    /**
     * Test setMenu method.
     *
     * @param  int    $projectID
     * @param  int    $productID
     * @param  string $type
     * @access public
     * @return mixed
     */
    public function setMenuTest(int $projectID = 0, int $productID = 0, string $type = '')
    {
        global $lang;
        $lang->waterfall = new stdclass();
        $lang->waterfall->menu = new stdclass();
        $lang->ipd = new stdclass();
        $lang->ipd->menu = new stdclass();

        $result = $this->invokeArgs('setMenu', [$projectID, $productID, $type]);
        if(dao::isError()) return dao::getError();

        if(!isset($lang->waterfall->menu->design['subMenu'])) return (object)array('count' => 0);

        $subMenu = $lang->waterfall->menu->design['subMenu'];
        $menuData = get_object_vars($subMenu);

        $checkIpd = isset($lang->ipd->menu->design) ? 1 : 0;

        return (object)array(
            'count'    => count($menuData),
            'hasAll'   => isset($menuData['all']) ? 1 : 0,
            'hasHlds'  => isset($menuData['hlds']) ? 1 : 0,
            'hasDds'   => isset($menuData['dds']) ? 1 : 0,
            'hasDbds'  => isset($menuData['dbds']) ? 1 : 0,
            'hasAds'   => isset($menuData['ads']) ? 1 : 0,
            'hasMore'  => isset($menuData['more']) ? 1 : 0,
            'hasIpd'   => $checkIpd
        );
    }
}
