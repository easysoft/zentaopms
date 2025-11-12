<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class taskTaoTest extends baseTest
{
    protected $moduleName = 'task';
    protected $className  = 'tao';

    /**
     * Test formatDatetime method.
     *
     * @param  object $task
     * @access public
     * @return object
     */
    public function formatDatetimeTest(object $task = null)
    {
        $result = $this->invokeArgs('formatDatetime', [$task]);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
