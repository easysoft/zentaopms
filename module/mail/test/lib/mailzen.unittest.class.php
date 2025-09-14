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

    /**
     * Test getConfigForSave method.
     *
     * @access public
     * @return mixed
     */
    public function getConfigForSaveZenTest()
    {
        $method = $this->mailZenTest->getMethod('getConfigForSave');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->mailZenTest->newInstance(), array());
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test getHasMailUserPairs method.
     *
     * @access public
     * @return mixed
     */
    public function getHasMailUserPairsZenTest()
    {
        $method = $this->mailZenTest->getMethod('getHasMailUserPairs');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->mailZenTest->newInstance(), array());
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test sendQueue method.
     *
     * @param  object $queue
     * @param  bool   $includeMe
     * @access public
     * @return mixed
     */
    public function sendQueueZenTest(object $queue, bool $includeMe = false)
    {
        $method = $this->mailZenTest->getMethod('sendQueue');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->mailZenTest->newInstance(), array($queue, $includeMe));
        if(dao::isError()) return dao::getError();

        return $result;
    }
}