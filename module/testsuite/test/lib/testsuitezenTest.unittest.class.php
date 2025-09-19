<?php
declare(strict_types = 1);
class testsuiteTest
{
    public $testsuiteZenTest;
    public $tester;

    public function __construct()
    {
        global $tester;
        $this->tester = $tester;
        $this->objectModel = $tester->loadModel('testsuite');
        $tester->app->setModuleName('testsuite');

        $this->testsuiteZenTest = initReference('testsuite');
    }

    /**
     * Test checkTestsuiteAccess method.
     *
     * @param  int $suiteID
     * @access public
     * @return mixed
     */
    public function checkTestsuiteAccessTest($suiteID = null)
    {
        try
        {
            $method = $this->testsuiteZenTest->getMethod('checkTestsuiteAccess');
            $method->setAccessible(true);
            $result = $method->invokeArgs($this->testsuiteZenTest->newInstance(), [$suiteID]);
            if(dao::isError()) return dao::getError();

            return $result;
        }
        catch(EndResponseException $e)
        {
            // Capture the response content from EndResponseException
            $content = $e->getContent();
            if(!empty($content))
            {
                $decoded = json_decode($content, true);
                if($decoded && isset($decoded['result']))
                {
                    return $decoded;
                }
            }
            return array('result' => 'fail', 'message' => 'EndResponseException caught');
        }
        catch(Exception $e)
        {
            return array('error' => $e->getMessage());
        }
    }
}