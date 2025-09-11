#!/usr/bin/env php
<?php

/**

title=测试 pivotTao::getExecutionList();
timeout=0
cid=0

- 步骤1：正常时间范围查询，返回4条记录 @4
- 步骤2：空时间参数查询，返回4条记录 @4
- 步骤3：指定执行ID列表，返回2条记录 @2
- 步骤4：未来时间范围查询，无记录 @0
- 步骤5：验证返回数据结构中第一条的项目ID第0条的projectID属性 @10

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. zendata数据准备
$project = zenData('project');
$project->id->range('1-10');
$project->name->range('项目{1-10}');
$project->type->range('project{5},sprint{3},stage{2}');
$project->status->range('wait{2},doing{3},suspended{1},closed{4}');
$project->multiple->range('1{8},0{2}');
$project->realBegan->range('20240101{4},20240201{3},20240301{3}');
$project->realEnd->range('20240601{4},20240701{3},20240801{3}');
$project->deleted->range('0');
$project->gen(10);

$task = zenData('task');
$task->id->range('1-20');
$task->project->range('1-10');
$task->execution->range('1-10');
$task->name->range('任务{1-20}');
$task->status->range('wait{5},doing{5},done{5},cancel{3},closed{2}');
$task->parent->range('0{18},1{2}');
$task->estimate->range('1-8');
$task->consumed->range('0.5-6');
$task->deleted->range('0');
$task->gen(20);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$pivotTest = new pivotTest();

// 5. 强制要求：必须包含至少5个测试步骤
r(count($pivotTest->getExecutionListTest('2024-01-01', '2024-12-31', array()))) && p() && e('4'); // 步骤1：正常时间范围查询，返回4条记录
r(count($pivotTest->getExecutionListTest('', '', array()))) && p() && e('4'); // 步骤2：空时间参数查询，返回4条记录
r(count($pivotTest->getExecutionListTest('2024-01-01', '2024-12-31', array(7, 8)))) && p() && e('2'); // 步骤3：指定执行ID列表，返回2条记录
r(count($pivotTest->getExecutionListTest('2025-01-01', '2025-12-31', array()))) && p() && e('0'); // 步骤4：未来时间范围查询，无记录
r($pivotTest->getExecutionListTest('2024-01-01', '2024-12-31', array())) && p('0:projectID') && e('10'); // 步骤5：验证返回数据结构中第一条的项目ID