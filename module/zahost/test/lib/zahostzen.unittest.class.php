<?php
declare(strict_types = 1);
class zahostTest
{
    public $zahostZenTest;
    public $tester;

    public function __construct()
    {
        global $tester;
        $this->tester = $tester;
        $this->objectModel = $tester->loadModel('zahost');
        $tester->app->setModuleName('zahost');

        $this->zahostZenTest = initReference('zahost');
    }

    /**
     * Test getServiceStatus method.
     *
     * @param  object $host
     * @access public
     * @return mixed
     */
    public function getServiceStatusTest($host = null)
    {
        if($host === null)
        {
            $host = new stdClass();
            $host->status = 'online';
            $host->extranet = '192.168.1.1';
            $host->zap = '8086';
            $host->tokenSN = 'test-token';
        }

        $method = $this->zahostZenTest->getMethod('getServiceStatus');
        $method->setAccessible(true);

        $result = $method->invokeArgs($this->zahostZenTest->newInstance(), [$host]);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}