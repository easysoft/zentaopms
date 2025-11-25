<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class convertModelTest extends baseTest
{
    protected $moduleName = 'convert';
    protected $className  = 'model';

    /**
     * Test getZentaoFields method.
     *
     * @param  string $module
     * @access public
     * @return array
     */
    public function getZentaoFieldsTest(string $module = ''): array
    {
        $result = $this->invokeArgs('getZentaoFields', [$module]);
        if(dao::isError()) return dao::getError();
        return $result;
    }

    /**
     * Test getZentaoObjectList method.
     *
     * @access public
     * @return array
     */
    public function getZentaoObjectListTest(): array
    {
        $result = $this->invokeArgs('getZentaoObjectList', []);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
