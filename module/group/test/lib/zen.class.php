<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class groupZenTest extends baseTest
{
    protected $moduleName = 'group';
    protected $className  = 'zen';

    /**
     * Test getNavGroup method.
     *
     * @access public
     * @return array
     */
    public function getNavGroupTest()
    {
        $result = $this->invokeArgs('getNavGroup', []);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
