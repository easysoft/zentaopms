#!/usr/bin/env php
<?php

/**

title=测试 executionZen::setRecentExecutions();
timeout=0
cid=0

- 测试首次添加执行ID @1
- 测试添加新执行ID到已有列表 @2,1

- 测试添加重复执行ID并去重 @1,2,3

- 测试列表超过5个时截断 @7,1,2,3,4

- 测试session非multiple模式 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

zenData('user')->gen(5);
su('admin');

$execution = zenData('project');
$execution->id->range('1-100');
$execution->name->range('执行1,执行2,执行3,执行4,执行5,执行6,执行7,执行8,执行9,执行10');
$execution->type->range('sprint');
$execution->status->range('doing');
$execution->deleted->range('0');
$execution->gen(10);

$executionTest = new executionZenTest();

r($executionTest->setRecentExecutionsTest(1, '', true)) && p() && e('1'); // 测试首次添加执行ID
r($executionTest->setRecentExecutionsTest(2, '1', true)) && p() && e('2,1'); // 测试添加新执行ID到已有列表
r($executionTest->setRecentExecutionsTest(1, '2,1,3', true)) && p() && e('1,2,3'); // 测试添加重复执行ID并去重
r($executionTest->setRecentExecutionsTest(7, '1,2,3,4,5', true)) && p() && e('7,1,2,3,4'); // 测试列表超过5个时截断
r($executionTest->setRecentExecutionsTest(1, '', false)) && p() && e('0'); // 测试session非multiple模式