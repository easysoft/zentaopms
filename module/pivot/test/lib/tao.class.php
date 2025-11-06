<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class pivotTaoTest extends baseTest
{
    protected $moduleName = 'pivot';
    protected $className  = 'tao';

    /**
     * Test fetchPivot method.
     *
     * @param  int         $id
     * @param  string|null $version
     * @access public
     * @return object|bool
     */
    public function fetchPivotTest(int $id, ?string $version = null): object|bool
    {
        $result = $this->invokeArgs('fetchPivot', [$id, $version]);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
