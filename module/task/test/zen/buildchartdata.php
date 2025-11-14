#!/usr/bin/env php
<?php

/**

title=测试 taskZen::buildChartData();
timeout=0
cid=18900

- 期望返回3个图表的数组 @3
- 执行$charts2['tasksPerStatus']->type @pie
- 执行$charts3['tasksPerType']->type @bar
- 执行$charts4['tasksPerPri']->type @line
- 执行taskZenTest模块的buildChartDataTest方法，参数是''  @3

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$task = zenData('task');
$task->id->range('1-20');
$task->name->range('测试任务1,测试任务2,测试任务3,测试任务4,测试任务5,任务6,任务7,任务8,任务9,任务10{10}');
$task->status->range('wait{5},doing{3},done{7},cancel{3},pause{2}');
$task->pri->range('1{3},2{5},3{7},4{5}');
$task->type->range('devel{8},test{5},design{4},affair{3}');
$task->assignedTo->range('admin{5},user1{4},user2{4},user3{3},user4{2},closed{2}');
$task->execution->range('1{10},2{6},3{4}');
$task->module->range('1{8},2{6},3{4},4{2}');
$task->estimate->range('1{5},2{6},4{4},8{3},16{2}');
$task->consumed->range('0{8},1{5},2{4},4{2},8{1}');
$task->left->range('0{7},1{6},2{4},4{2},8{1}');
$task->finishedBy->range('admin{4},user1{3},user2{2},user3{1},[]{10}');
$task->closedReason->range('[]{15},done{3},cancel{1},bydesign{1}');
$task->gen(20);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$taskZenTest = new taskZenTest();

// 模拟POST数据：选择要生成的图表类型
$_POST['charts'] = array('tasksPerStatus', 'tasksPerType', 'tasksPerPri');

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 步骤1：使用默认图表类型构建图表数据（验证返回图表数量）
r(count($taskZenTest->buildChartDataTest('default'))) && p() && e('3'); // 期望返回3个图表的数组

// 步骤2：使用pie图表类型构建图表数据
$charts2 = $taskZenTest->buildChartDataTest('pie');
r($charts2['tasksPerStatus']->type) && p() && e('pie');

// 步骤3：使用bar图表类型构建图表数据
$charts3 = $taskZenTest->buildChartDataTest('bar');
r($charts3['tasksPerType']->type) && p() && e('bar');

// 步骤4：使用line图表类型构建图表数据
$charts4 = $taskZenTest->buildChartDataTest('line');
r($charts4['tasksPerPri']->type) && p() && e('line');

// 步骤5：传入空字符串图表类型构建图表数据（验证返回图表数量）
r(count($taskZenTest->buildChartDataTest(''))) && p() && e('3');