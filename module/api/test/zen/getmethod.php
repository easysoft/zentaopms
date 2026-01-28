#!/usr/bin/env php
<?php

/**

title=测试 apiZen::getMethod();
timeout=0
cid=15125

- 执行apiTest模块的getMethodTest方法，参数是$filePath1, 'Zen' 属性className @api
- 执行apiTest模块的getMethodTest方法，参数是$filePath1, 'Zen' 属性methodName @getMethod
- 执行apiTest模块的getMethodTest方法，参数是$filePath2, 'Zen' 属性methodName @generateLibsDropMenu
- 执行apiTest模块的getMethodTest方法，参数是$filePath3, 'Zen' 属性methodName @parseDocSpaceParam
- 执行apiTest模块的getMethodTest方法，参数是$filePath4, 'Zen' 属性methodName @request
- 执行apiTest模块的getMethodTest方法，参数是$filePath4, 'Zen' 属性post @1
- 执行apiTest模块的getMethodTest方法，参数是$invalidPath, 'Zen' 属性error @Class "toZen" does not exist

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$apiTest = new apiZenTest();

$zenFile = realpath(dirname(__FILE__, 3) . '/zen.php');
$filePath1 = $zenFile . '/getMethod';
$filePath2 = $zenFile . '/generateLibsDropMenu';
$filePath3 = $zenFile . '/parseDocSpaceParam';
$filePath4 = $zenFile . '/request';
$invalidPath = '/invalid/path/to/file.php/method';

r($apiTest->getMethodTest($filePath1, 'Zen')) && p('className') && e('api');
r($apiTest->getMethodTest($filePath1, 'Zen')) && p('methodName') && e('getMethod');
r($apiTest->getMethodTest($filePath2, 'Zen')) && p('methodName') && e('generateLibsDropMenu');
r($apiTest->getMethodTest($filePath3, 'Zen')) && p('methodName') && e('parseDocSpaceParam');
r($apiTest->getMethodTest($filePath4, 'Zen')) && p('methodName') && e('request');
r($apiTest->getMethodTest($filePath4, 'Zen')) && p('post') && e('1');
r($apiTest->getMethodTest($invalidPath, 'Zen')) && p('error') && e('Class "toZen" does not exist');