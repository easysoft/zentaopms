<?php
declare(strict_types = 1);
class miscTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('misc');
        $this->objectTao   = $tester->loadTao('misc');
    }

    /**
     * Test hello method.
     *
     * @access public
     * @return mixed
     */
    public function helloTest()
    {
        $result = $this->objectTao->hello();
        if(dao::isError()) return dao::getError();

        return $result;
    }
}