<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class bugZenTest extends baseTest
{
    protected $moduleName = 'bug';
    protected $className  = 'zen';

    /**
     * Test afterBatchCreate method.
     *
     * @param  object $bug
     * @param  array  $output
     * @access public
     * @return bool
     */
    public function afterBatchCreateTest(object $bug, array $output = array()): bool
    {
        $result = $this->invokeArgs('afterBatchCreate', [$bug, $output]);
        if(dao::isError()) return false;
        return $result;
    }
}
