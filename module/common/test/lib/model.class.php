<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class commonModelTest extends baseTest
{
    protected $moduleName = 'common';
    protected $className  = 'model';

    /**
     * Test apiError method.
     *
     * @param  object|null $result
     * @access public
     * @return object
     */
    public function apiErrorTest($result = null)
    {
        $reflection = new ReflectionClass('commonModel');
        $method = $reflection->getMethod('apiError');
        $method->setAccessible(true);

        $result = $method->invoke(null, $result);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
