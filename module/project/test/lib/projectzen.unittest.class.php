<?php
class projectZenTest
{
    public $projectZenTest;
    public $tester;
    function __construct()
    {
        global $tester;
        $this->tester = $tester;
        $tester->app->setModuleName('project');
        $tester->loadModel('project');

        $this->projectZenTest = initReference('project');
    }

    /**
     * 测试prepareCreateExtras方法。
     * Test prepareCreateExtras method.
     *
     * @param  int    $testData
     * @param  int    $expect
     * @access public
     * @return array|bool
     */
    public function prepareCreateExtrasTest($testData, $expect)
    {
    }
}
