#!/usr/bin/env php
<?php

/**

title=测试 bugZen::responseAfterBatchCreate();
timeout=0
cid=15470

- 执行$result1['result']) && $result1['result'] @1
- 执行$result2['result']) && $result2['result'] @1
- 执行$result3['result']) && $result3['result'] @1
- 执行$result4['result']) && $result4['result'] @1
- 执行$result5['result']) && $result5['result'] @1

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
$execution->id->range('101-103');
$execution->type->range('sprint');
$execution->status->range('doing');
$execution->gen(3);

su('admin');

global $tester;
$bugTest = new bugZenTest();

// 测试1:正常批量创建bug,返回成功
$result1 = $bugTest->responseAfterBatchCreateTest(1, '0', 0, array(1, 2, 3), '');
r(isset($result1['result']) && $result1['result']) && p() && e('1');

// 测试2:测试在modal中批量创建bug(无executionID),返回成功
$_GET['onlybody'] = 'yes';
$result2 = $bugTest->responseAfterBatchCreateTest(2, '0', 0, array(4, 5), '批量创建成功');
r(isset($result2['result']) && $result2['result']) && p() && e('1');

// 测试3:测试在modal中批量创建bug(有executionID),返回成功
$_GET['onlybody'] = 'yes';
$result3 = $bugTest->responseAfterBatchCreateTest(3, '0', 101, array(6, 7), '');
r(isset($result3['result']) && $result3['result']) && p() && e('1');

// 测试4:测试自定义成功消息,返回成功
unset($_GET['onlybody']);
$result4 = $bugTest->responseAfterBatchCreateTest(4, '0', 0, array(8, 9), '自定义成功消息');
r(isset($result4['result']) && $result4['result']) && p() && e('1');

// 测试5:测试空bug ID列表,返回成功
$result5 = $bugTest->responseAfterBatchCreateTest(5, '0', 0, array(), '');
r(isset($result5['result']) && $result5['result']) && p() && e('1');