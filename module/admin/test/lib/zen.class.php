<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class adminZenTest extends baseTest
{
    protected $moduleName = 'admin';
    protected $className  = 'zen';

    /**
     * Test certifyByAPI method.
     *
     * @param  string $type mobile|email
     * @access public
     * @return mixed
     */
    public function certifyByAPITest(string $type = 'mobile')
    {
        $result = $this->invokeArgs('certifyByAPI', [$type]);
        if(dao::isError()) return dao::getError();
        return $result;
    }
}
