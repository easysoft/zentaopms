#!/usr/bin/env php
<?php

/**

title=测试 jobZen::getCompileData();
timeout=0
cid=16861

- 执行jobTest模块的getCompileDataTest方法，参数是$compile1 属性taskID @1
- 执行jobTest模块的getCompileDataTest方法，参数是$compile2 属性taskID @2
- 执行jobTest模块的getCompileDataTest方法，参数是$compile3 属性taskID @3
- 执行jobTest模块的getCompileDataTest方法，参数是$compile4 属性groupCases @1
- 执行jobTest模块的getCompileDataTest方法，参数是$compile5 属性suites @5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';
su('admin');

zenData('product')->gen(10);
zenData('story')->gen(20);
zenData('compile')->gen(10);
zenData('user')->gen(5);

$testtask = zenData('testtask');
$testtask->id->range('1-3');
$testtask->product->range('1,1,2');
$testtask->gen(3);

zenData('testrun')->gen(15);
zenData('case')->gen(20);
zenData('testresult')->gen(15);
zenData('testsuite')->gen(5);
zenData('suitecase')->gen(15);

$jobTest = new jobZenTest();

$compile1 = new stdClass();
$compile1->testtask = 1;
r($jobTest->getCompileDataTest($compile1)) && p('taskID') && e('1');

$compile2 = new stdClass();
$compile2->testtask = 2;
r($jobTest->getCompileDataTest($compile2)) && p('taskID') && e('2');

$compile3 = new stdClass();
$compile3->testtask = 3;
r($jobTest->getCompileDataTest($compile3)) && p('taskID') && e('3');

$compile4 = new stdClass();
$compile4->testtask = 1;
r($jobTest->getCompileDataTest($compile4)) && p('groupCases') && e('1');

$compile5 = new stdClass();
$compile5->testtask = 2;
r($jobTest->getCompileDataTest($compile5)) && p('suites') && e('5');