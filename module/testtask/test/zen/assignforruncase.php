#!/usr/bin/env php
<?php

/**

title=测试 testtaskZen::assignForRunCase();
timeout=0
cid=19225

- 步骤1：正常情况 @success
- 步骤2：runID为0 @success
- 步骤3：caseID为0 @success
- 步骤4：版本号为负数 @success
- 步骤5：confirm为yes @success

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testtaskzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$user = zenData('user');
$user->account->range('admin,user1,user2,tester');
$user->password->range('123456{4}');
$user->realname->range('管理员,用户1,用户2,测试员');
$user->role->range('admin{1},qa{3}');
$user->gen(4);

$case = zenData('case');
$case->id->range('1-10');
$case->product->range('1{5},2{5}');
$case->title->range('测试用例{1-10}');
$case->type->range('feature{5},performance{3},config{2}');
$case->status->range('normal{8},blocked{2}');
$case->version->range('1{8},2{2}');
$case->gen(10);

$run = zenData('testrun');
$run->id->range('1-5');
$run->task->range('1{3},2{2}');
$run->case->range('1-5');
$run->version->range('1');
$run->lastRunner->range('admin{3},user1{2}');
$run->lastRunResult->range('pass{3},fail{2}');
$run->status->range('normal{4},blocked{1}');
$run->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testtaskTest = new testtaskZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testtaskTest->assignForRunCaseTest()) && p() && e('success'); // 步骤1：正常情况
r($testtaskTest->assignForRunCaseTest(null, null, 0, 1, 1, '')) && p() && e('success'); // 步骤2：runID为0
r($testtaskTest->assignForRunCaseTest(null, null, 1, 0, 1, '')) && p() && e('success'); // 步骤3：caseID为0
r($testtaskTest->assignForRunCaseTest(null, null, 1, 1, -1, '')) && p() && e('success'); // 步骤4：版本号为负数
r($testtaskTest->assignForRunCaseTest(null, null, 1, 1, 1, 'yes')) && p() && e('success'); // 步骤5：confirm为yes