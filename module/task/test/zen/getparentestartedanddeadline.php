#!/usr/bin/env php
<?php

/**

title=测试 taskZen::getParentEstStartedAndDeadline();
timeout=0
cid=0

- 步骤1：正常情况返回数量 @2
- 步骤2：空数组返回数量 @0
- 步骤3：不存在ID返回数量 @0
- 步骤4：验证父任务1开始时间第1条的estStarted属性 @2024-01-01
- 步骤5：验证父任务2截止时间第2条的deadline属性 @2024-02-15

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

// 2. zendata数据准备
$table = zenData('task');
$table->id->range('1-5');
$table->parent->range('0{2},1{1},4{1},0{1}');
$table->path->range(',1,,4,,1,2,,4,5,,7,');
$table->project->range('1{5}');
$table->execution->range('1{5}');
$table->name->range('根任务1,根任务2,子任务1-1,子任务2-1,独立任务');
$table->type->range('devel{2},test{2},design{1}');
$table->status->range('wait{3},doing{2}');
$table->estStarted->range('`2024-01-01`,`2024-02-01`,`0000-00-00`,`0000-00-00`,`2024-03-01`');
$table->deadline->range('`2024-01-31`,`2024-02-28`,`2024-01-15`,`2024-02-15`,`0000-00-00`');
$table->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$taskTest = new taskZenTest();

// 5. 强制要求：必须包含至少5个测试步骤
r(count($taskTest->getParentEstStartedAndDeadlineTest(array(1, 2)))) && p() && e(2); // 步骤1：正常情况返回数量
r(count($taskTest->getParentEstStartedAndDeadlineTest(array()))) && p() && e(0); // 步骤2：空数组返回数量
r(count($taskTest->getParentEstStartedAndDeadlineTest(array(999)))) && p() && e(0); // 步骤3：不存在ID返回数量
r($taskTest->getParentEstStartedAndDeadlineTest(array(1))) && p('1:estStarted') && e('2024-01-01'); // 步骤4：验证父任务1开始时间
r($taskTest->getParentEstStartedAndDeadlineTest(array(2))) && p('2:deadline') && e('2024-02-15'); // 步骤5：验证父任务2截止时间