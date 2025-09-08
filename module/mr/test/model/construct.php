#!/usr/bin/env php
<?php

/**

title=测试 mrModel::__construct();
timeout=0
cid=0

- 执行mrTest模块的constructTest方法，参数是'', 'mr'  @mr
- 执行mrTest模块的constructTest方法，参数是'', 'mr'  @mr
- 执行mrTest模块的constructTest方法，参数是'zentao', 'mr'  @mr
- 执行mrTest模块的constructTest方法，参数是'', 'pullreq'  @pullreq
- 执行mrTest模块的constructTest方法，参数是'zentao', 'pullreq'  @pullreq

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

class mrConstructTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('mr');
    }

    /**
     * Test __construct method.
     *
     * @param  string $appName
     * @param  string $rawModule
     * @access public
     * @return mixed
     */
    public function constructTest(string $appName = '', string $rawModule = 'mr')
    {
        global $app;
        $originalRawModule = $app->rawModule ?? '';
        
        try {
            $app->rawModule = $rawModule;
            $mrModel = new mrModel($appName);
            $result = $mrModel->moduleName;
            $app->rawModule = $originalRawModule;
            return $result;
        } catch (Exception $e) {
            $app->rawModule = $originalRawModule;
            return 'error: ' . $e->getMessage();
        }
    }
}

su('admin');

$mrTest = new mrConstructTest();

r($mrTest->constructTest('', 'mr')) && p() && e('mr');
r($mrTest->constructTest('', 'mr')) && p() && e('mr');
r($mrTest->constructTest('zentao', 'mr')) && p() && e('mr');
r($mrTest->constructTest('', 'pullreq')) && p() && e('pullreq');
r($mrTest->constructTest('zentao', 'pullreq')) && p() && e('pullreq');