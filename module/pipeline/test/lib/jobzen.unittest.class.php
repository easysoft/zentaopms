<?php
declare(strict_types = 1);
class jobTest
{
    public $jobZenTest;
    public $tester;

    public function __construct()
    {
        global $tester;
        $this->tester = $tester;
        $tester->app->setModuleName('job');
        $tester->loadModel('job');

        $this->jobZenTest = initReference('job');
    }

    /**
     * Test checkRepoEmpty method.
     *
     * @access public
     * @return mixed
     */
    public function checkRepoEmptyTest()
    {
        try {
            $method = $this->jobZenTest->getMethod('checkRepoEmpty');
            $method->setAccessible(true);
            $result = $method->invokeArgs($this->jobZenTest->newInstance(), array());

            if(dao::isError()) return dao::getError();
            return $result;
        }
        catch(Exception $e) {
            return array('error' => $e->getMessage());
        }
    }
}