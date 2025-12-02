<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class tutorialModelTest extends baseTest
{
    protected $moduleName = 'tutorial';
    protected $className  = 'model';

    /**
     * Test getColumn method.
     *
     * @access public
     * @return object
     */
    public function getColumnTest()
    {
        $result = $this->invokeArgs('getColumn', []);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
