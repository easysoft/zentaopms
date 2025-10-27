#!/usr/bin/env php
<?php

/**

title=测试 bugZen::buildBugsForBatchEdit();
timeout=0
cid=0

- 步骤1：正常情况 @2
- 步骤2：边界值 @0
- 步骤3：关闭状态 @original_user
- 步骤4：重复bug处理 @0
- 步骤5：解决方案处理 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

zenData('bug')->loadYaml('bug', false, 2)->gen(10);
zenData('user')->loadYaml('user', false, 2)->gen(10);
zenData('product')->loadYaml('product', false, 2)->gen(5);

su('admin');

$bugTest = new bugTest();

// 步骤1：正常批量编辑多个bug
$oldBugs1 = array();
$oldBug1 = new stdclass();
$oldBug1->id = 1;
$oldBug1->status = 'active';
$oldBug1->assignedTo = 'user1';
$oldBug1->openedBy = 'admin';
$oldBug1->resolution = '';
$oldBug1->duplicateBug = 5;
$oldBugs1[] = $oldBug1;

$oldBug2 = new stdclass();
$oldBug2->id = 2;
$oldBug2->status = 'active';
$oldBug2->assignedTo = 'user2';
$oldBug2->openedBy = 'admin';
$oldBug2->resolution = 'fixed';
$oldBug2->duplicateBug = 0;
$oldBugs1[] = $oldBug2;

// 步骤1：正常批量编辑多个bug
$result1 = $bugTest->buildBugsForBatchEditTest($oldBugs1);
r(count($result1)) && p() && e('2'); // 步骤1：正常情况

// 步骤2：传入空数组
r(count($bugTest->buildBugsForBatchEditTest(array()))) && p() && e('0'); // 步骤2：边界值

// 步骤3：测试已关闭bug的指派人员不变
$oldBugs3 = array();
$oldBug3 = new stdclass();
$oldBug3->id = 1;
$oldBug3->status = 'closed';
$oldBug3->assignedTo = 'original_user';
$oldBug3->openedBy = 'admin';
$oldBug3->resolution = '';
$oldBugs3[] = $oldBug3;

$result3 = $bugTest->buildBugsForBatchEditTest($oldBugs3);
r(isset($result3[0]) ? $result3[0]->assignedTo : '') && p() && e('original_user'); // 步骤3：关闭状态

// 步骤4：测试解决方案不是duplicate时duplicateBug为0
$oldBugs4 = array();
$oldBug4 = new stdclass();
$oldBug4->id = 1;
$oldBug4->status = 'active';
$oldBug4->assignedTo = 'user1';
$oldBug4->openedBy = 'admin';
$oldBug4->resolution = 'fixed';
$oldBug4->duplicateBug = 5;
$oldBugs4[] = $oldBug4;

$result4 = $bugTest->buildBugsForBatchEditTest($oldBugs4);
r(isset($result4[0]) ? $result4[0]->duplicateBug : 1) && p() && e('0'); // 步骤4：重复bug处理

// 步骤5：测试有解决方案时设置确认状态和解决人
$oldBugs5 = array();
$oldBug5 = new stdclass();
$oldBug5->id = 1;
$oldBug5->status = 'active';
$oldBug5->assignedTo = '';
$oldBug5->openedBy = 'tester';
$oldBug5->resolution = 'fixed';
$oldBug5->resolvedBy = '';
$oldBugs5[] = $oldBug5;

$result5 = $bugTest->buildBugsForBatchEditTest($oldBugs5);
r(isset($result5[0]) ? $result5[0]->confirmed : 0) && p() && e('1'); // 步骤5：解决方案处理