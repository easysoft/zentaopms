#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';
zenData('user')->gen(5);
su('admin');

/**

title=测试 executionModel->saveSession();
timeout=0
cid=16397

- 测试保存执行ID @1
- 测试保存执行ID @2
- 测试保存执行ID @3
- 测试保存执行ID @4
- 测试保存执行ID @0

*/

$executionTester = new executionTaoTest();
r($executionTester->saveSessionTest(1)) && p() && e('1'); // 测试保存执行ID
r($executionTester->saveSessionTest(2)) && p() && e('2'); // 测试保存执行ID
r($executionTester->saveSessionTest(3)) && p() && e('3'); // 测试保存执行ID
r($executionTester->saveSessionTest(4)) && p() && e('4'); // 测试保存执行ID
r($executionTester->saveSessionTest(0)) && p() && e('0'); // 测试保存执行ID