#!/usr/bin/env php
<?php

/**

title=测试 testtaskModel::getGroupByCases();
timeout=0
cid=19175

- 步骤1：正常单个用例ID，返回1个分组 @1
- 步骤2：多个用例ID数组返回3个分组 @3
- 步骤3：空用例ID列表返回0个分组 @0
- 步骤4：不存在的用例ID返回0个分组 @0
- 步骤5：验证返回结果非空 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testtask.unittest.class.php';

// 2. zendata数据准备
$testrun = zenData('testrun');
$testrun->id->range('1-10');
$testrun->task->range('1-3');
$testrun->case->range('1-5');
$testrun->version->range('1-2');
$testrun->assignedTo->range('admin,user1,user2');
$testrun->lastRunner->range('admin,user1,user2');
$testrun->lastRunDate->range('`2024-01-01 10:00:00`,`2024-01-02 10:00:00`,`2024-01-03 10:00:00`');
$testrun->lastRunResult->range('pass,fail,blocked,skipped');
$testrun->status->range('wait,done,blocked');
$testrun->gen(10);

$testtask = zenData('testtask');
$testtask->id->range('1-3');
$testtask->project->range('1-2');
$testtask->product->range('1-2');
$testtask->name->range('测试任务1,测试任务2,测试任务3');
$testtask->execution->range('1-3');
$testtask->build->range('1,2,3');
$testtask->type->range('feature,integration,system');
$testtask->owner->range('admin,user1,user2');
$testtask->pri->range('1-3');
$testtask->begin->range('`2024-01-01`,`2024-01-02`,`2024-01-03`');
$testtask->end->range('`2024-01-10`,`2024-01-15`,`2024-01-20`');
$testtask->status->range('wait,doing,done');
$testtask->gen(3);

$build = zenData('build');
$build->id->range('1-3');
$build->project->range('1-2');
$build->product->range('1-2');
$build->branch->range('0,1,2');
$build->execution->range('1-3');
$build->name->range('Build1,Build2,Build3');
$build->date->range('`2024-01-01`,`2024-01-02`,`2024-01-03`');
$build->builder->range('admin,user1,user2');
$build->gen(3);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$testtaskTest = new testtaskTest();

// 5. 强制要求：必须包含至少5个测试步骤
r(count($testtaskTest->getGroupByCasesTest(1))) && p() && e('1'); // 步骤1：正常单个用例ID，返回1个分组
r(count($testtaskTest->getGroupByCasesTest(array(1, 2, 3)))) && p() && e('3'); // 步骤2：多个用例ID数组返回3个分组
r(count($testtaskTest->getGroupByCasesTest(array()))) && p() && e('0'); // 步骤3：空用例ID列表返回0个分组
r(count($testtaskTest->getGroupByCasesTest(999))) && p() && e('0'); // 步骤4：不存在的用例ID返回0个分组
r(empty($testtaskTest->getGroupByCasesTest(array(1)))) && p() && e('0'); // 步骤5：验证返回结果非空