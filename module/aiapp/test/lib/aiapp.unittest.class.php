<?php
declare(strict_types = 1);
class aiappTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('aiapp');
    }

    /**
     * Test __construct method.
     *
     * @access public
     * @return mixed
     */
    public function __constructTest()
    {
        $result = $this->objectModel;
        if(dao::isError()) return dao::getError();

        return $result;
    }
}