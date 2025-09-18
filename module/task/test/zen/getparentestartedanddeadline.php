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
- 步骤5：验证父任务4截止时间第4条的deadline属性 @2024-02-28
- 步骤6：子任务从路径倒序查找开始时间第2条的estStarted属性 @2024-01-03
- 步骤7：多层级任务路径查找开始时间第7条的estStarted属性 @2024-01-03

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

// 2. zendata数据准备
$table = zenData('task');
$table->id->range('1-8');
$table->parent->range('0{2},1{2},4{2},1{1},4{1}');
$table->path->range(',1,,4,,1,2,,1,3,,4,5,,4,6,,1,7,,4,8,');
$table->project->range('1{8}');
$table->execution->range('1{8}');
$table->name->range('根任务1,根任务2,子任务1-1,子任务1-2,子任务2-1,子任务2-2,孙任务1-1-1,子任务2-3');
$table->type->range('devel{4},test{4}');
$table->status->range('wait{3},doing{3},done{2}');
$table->estStarted->range('`2024-01-01`,`2024-02-01`,`0000-00-00`,`2024-01-03`,`0000-00-00`,`2024-02-05`,`2024-01-02`,`2024-02-10`');
$table->deadline->range('`2024-01-31`,`2024-02-28`,`2024-01-15`,`0000-00-00`,`2024-02-15`,`0000-00-00`,`2024-01-20`,`2024-02-25`');
$table->gen(8);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$taskTest = new taskZenTest();

// 5. 强制要求：必须包含至少5个测试步骤
r(count($taskTest->getParentEstStartedAndDeadlineTest(array(1, 4)))) && p() && e(2); // 步骤1：正常情况返回数量
r(count($taskTest->getParentEstStartedAndDeadlineTest(array()))) && p() && e(0); // 步骤2：空数组返回数量
r(count($taskTest->getParentEstStartedAndDeadlineTest(array(999, 1000)))) && p() && e(0); // 步骤3：不存在ID返回数量
r($taskTest->getParentEstStartedAndDeadlineTest(array(1))) && p('1:estStarted') && e('2024-01-01'); // 步骤4：验证父任务1开始时间
r($taskTest->getParentEstStartedAndDeadlineTest(array(4))) && p('4:deadline') && e('2024-02-28'); // 步骤5：验证父任务4截止时间
r($taskTest->getParentEstStartedAndDeadlineTest(array(2))) && p('2:estStarted') && e('2024-01-03'); // 步骤6：子任务从路径倒序查找开始时间
r($taskTest->getParentEstStartedAndDeadlineTest(array(7))) && p('7:estStarted') && e('2024-01-03'); // 步骤7：多层级任务路径查找开始时间