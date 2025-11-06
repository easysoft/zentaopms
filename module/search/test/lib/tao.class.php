<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class searchTaoTest extends baseTest
{
    protected $moduleName = 'search';
    protected $className  = 'tao';

    /**
     * Test appendFiles method.
     *
     * @param  object $object
     * @access public
     * @return object
     */
    public function appendFilesTest(object $object): object
    {
        $result = $this->invokeArgs('appendFiles', [$object]);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
