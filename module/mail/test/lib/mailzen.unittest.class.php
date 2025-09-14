<?php
class mailZenTest
{
    public $mailZenTest;
    public $tester;
    function __construct()
    {
        global $tester;
        $this->tester = $tester;
        $tester->app->setModuleName('mail');
        $tester->loadModel('mail');

        $this->mailZenTest = initReference('mail');
    }

    /**
     * Test getConfigForEdit method.
     *
     * @access public
     * @return mixed
     */
    public function getConfigForEditZenTest()
    {
        $method = $this->mailZenTest->getMethod('getConfigForEdit');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->mailZenTest->newInstance(), array());
        if(dao::isError()) return dao::getError();

        return $result;
    }
}