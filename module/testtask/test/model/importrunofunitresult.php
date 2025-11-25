#!/usr/bin/env php
<?php

/**

title=测试 testtaskModel::importRunOfUnitResult();
timeout=0
cid=19200

- 步骤1：正常导入测试运行数据 @success
- 步骤2：测试不同参数的测试运行数据 @success
- 步骤3：测试最小caseID为0 @success
- 步骤4：测试不同版本的测试运行数据 @success
- 步骤5：测试完整的测试运行数据导入 @success

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testtask.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$testrun = zenData('testrun');
$testrun->id->range('1-10');
$testrun->task->range('1-5');
$testrun->case->range('1-20');
$testrun->version->range('1-3');
$testrun->assignedTo->range('admin,user1,user2,test3,dev4');
$testrun->lastRunner->range('admin,user1,user2');
$testrun->lastRunDate->range('`2024-01-01 10:00:00`,`2024-01-02 11:00:00`,`2024-01-03 12:00:00`');
$testrun->lastRunResult->range('pass,fail,blocked');
$testrun->status->range('normal,wait,done');
$testrun->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testtaskTest = new testtaskTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testtaskTest->importRunOfUnitResultTest((object)array('version' => 2, 'lastRunner' => 'testuser', 'lastRunDate' => '2024-01-01 15:00:00', 'lastRunResult' => 'pass'), 101, (object)array('task' => 6, 'status' => 'normal'))) && p() && e('success'); // 步骤1：正常导入测试运行数据
r($testtaskTest->importRunOfUnitResultTest((object)array('version' => 1, 'lastRunner' => 'testuser2', 'lastRunDate' => '2024-01-02 15:00:00', 'lastRunResult' => 'pass'), 102, (object)array('task' => 7, 'status' => 'normal'))) && p() && e('success'); // 步骤2：测试不同参数的测试运行数据
r($testtaskTest->importRunOfUnitResultTest((object)array('version' => 1, 'lastRunner' => 'admin', 'lastRunDate' => '2024-01-02 10:00:00', 'lastRunResult' => 'fail'), 0, (object)array('task' => 8, 'status' => 'wait'))) && p() && e('success'); // 步骤3：测试最小caseID为0
r($testtaskTest->importRunOfUnitResultTest((object)array('version' => 3, 'lastRunner' => 'testuser4', 'lastRunDate' => '2024-01-04 15:00:00', 'lastRunResult' => 'blocked'), 103, (object)array('task' => 9, 'status' => 'normal'))) && p() && e('success'); // 步骤4：测试不同版本的测试运行数据
r($testtaskTest->importRunOfUnitResultTest((object)array('version' => 1, 'lastRunner' => 'developer', 'lastRunDate' => '2024-01-03 14:30:00', 'lastRunResult' => 'blocked'), 104, (object)array('task' => 10, 'status' => 'done', 'assignedTo' => 'tester'))) && p() && e('success'); // 步骤5：测试完整的测试运行数据导入