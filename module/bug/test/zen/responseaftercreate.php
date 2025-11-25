#!/usr/bin/env php
<?php

/**

title=测试 bugZen::responseAfterCreate();
timeout=0
cid=15472

- 执行$result1['result']) && $result1['result'] @1
- 执行$result2['result']) && $result2['result'] @1
- 执行$result3['result']) && $result3['result'] @1
- 执行$result4['result']) && $result4['result'] @1
- 执行$result5['result']) && $result5['result'] @1
- 执行$result6['result']) && $result6['result'] @1
- 执行$result7['result']) && $result7['result'] @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 准备product测试数据
$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3,产品4,产品5');
$product->type->range('normal');
$product->status->range('normal');
$product->gen(5);

// 准备execution测试数据
$execution = zenData('project');
$execution->id->range('101-105');
$execution->type->range('sprint');
$execution->status->range('doing');
$execution->gen(5);

// 准备bug测试数据
$bug = zenData('bug');
$bug->id->range('1-10');
$bug->product->range('1-5');
$bug->execution->range('101-105');
$bug->title->range('Bug1,Bug2,Bug3,Bug4,Bug5');
$bug->status->range('active');
$bug->gen(10);

su('admin');

global $tester;
$bugTest = new bugZenTest();

// 创建测试用的bug对象
$testBug = new stdClass();
$testBug->id = 1;
$testBug->product = 1;
$testBug->execution = 0;
$testBug->branch = '0';
$testBug->module = 0;

// 测试1:正常创建bug,在product标签页(默认情况)
$params1 = array();
$result1 = $bugTest->responseAfterCreateTest($testBug, $params1, '');
r(isset($result1['result']) && $result1['result']) && p() && e('1');

// 测试2:在execution标签页创建bug
global $app;
$app->tab = 'execution';
$testBug2 = clone $testBug;
$testBug2->id = 2;
$testBug2->execution = 101;
$params2 = array('executionID' => 101);
$result2 = $bugTest->responseAfterCreateTest($testBug2, $params2, '');
r(isset($result2['result']) && $result2['result']) && p() && e('1');

// 测试3:在project标签页创建bug
$app->tab = 'project';
$testBug3 = clone $testBug;
$testBug3->id = 3;
$params3 = array('projectID' => 102);
$result3 = $bugTest->responseAfterCreateTest($testBug3, $params3, '');
r(isset($result3['result']) && $result3['result']) && p() && e('1');

// 测试4:在modal中创建bug
$app->tab = 'product';
$_GET['onlybody'] = 'yes';
$testBug4 = clone $testBug;
$testBug4->id = 4;
$params4 = array();
$result4 = $bugTest->responseAfterCreateTest($testBug4, $params4, '');
r(isset($result4['result']) && $result4['result']) && p() && e('1');

// 测试5:自定义成功消息
unset($_GET['onlybody']);
$app->tab = 'product';
$testBug5 = clone $testBug;
$testBug5->id = 5;
$params5 = array();
$result5 = $bugTest->responseAfterCreateTest($testBug5, $params5, '自定义成功消息');
r(isset($result5['result']) && $result5['result']) && p() && e('1');

// 测试6:测试带有branch的bug
$testBug6 = clone $testBug;
$testBug6->id = 6;
$testBug6->branch = '1';
$params6 = array();
$result6 = $bugTest->responseAfterCreateTest($testBug6, $params6, '');
r(isset($result6['result']) && $result6['result']) && p() && e('1');

// 测试7:测试带有module的bug
$testBug7 = clone $testBug;
$testBug7->id = 7;
$testBug7->module = 10;
$params7 = array();
$result7 = $bugTest->responseAfterCreateTest($testBug7, $params7, '');
r(isset($result7['result']) && $result7['result']) && p() && e('1');