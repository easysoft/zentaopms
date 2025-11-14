#!/usr/bin/env php
<?php

/**

title=测试 programplanModel::isClickable();
timeout=0
cid=17751

- 步骤1：测试空阶段对象的非create操作权限 @1
- 步骤2：测试空阶段对象的create操作权限 @1
- 步骤3：测试有ID但无任务的阶段create操作权限 @1
- 步骤4：测试有ID且有任务的阶段create操作权限 @0
- 步骤5：测试有ID阶段的非create操作权限 @1
- 步骤6：测试大小写敏感的CREATE操作权限 @0
- 步骤7：测试只有已删除任务的阶段create操作权限 @1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/programplan.unittest.class.php';

// 2. zendata数据准备
$project = zenData('project');
$project->id->range('1-10');
$project->name->range('阶段1,阶段2,阶段3,阶段4,阶段5,阶段6,阶段7,阶段8,阶段9,阶段10');
$project->type->range('stage{5},sprint{5}');
$project->status->range('wait{3},doing{4},done{3}');
$project->deleted->range('0{9},1{1}');
$project->gen(10);

$task = zenData('task');
$task->id->range('1-15');
$task->execution->range('6{5},7{3},8{2},9{3},11{2}');
$task->name->range('任务1,任务2,任务3,任务4,任务5,任务6,任务7,任务8,任务9,任务10,任务11,任务12,任务13,任务14,任务15');
$task->status->range('wait{5},doing{5},done{5}');
$task->deleted->range('0{13},1{2}');
$task->gen(15);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$programplanTest = new programplanTest();

// 5. 测试数据准备
$emptyStage = new stdclass();

$stageWithoutTasks = new stdclass();
$stageWithoutTasks->id = 5;

$stageWithTasks = new stdclass();
$stageWithTasks->id = 6;

$stageWithDeletedTasks = new stdclass();
$stageWithDeletedTasks->id = 10;

// 6. 执行测试步骤（至少5个）
r($programplanTest->isClickableTest($emptyStage, 'close')) && p() && e('1'); // 步骤1：测试空阶段对象的非create操作权限
r($programplanTest->isClickableTest($emptyStage, 'create')) && p() && e('1'); // 步骤2：测试空阶段对象的create操作权限
r($programplanTest->isClickableTest($stageWithoutTasks, 'create')) && p() && e('1'); // 步骤3：测试有ID但无任务的阶段create操作权限
r($programplanTest->isClickableTest($stageWithTasks, 'create')) && p() && e('0'); // 步骤4：测试有ID且有任务的阶段create操作权限
r($programplanTest->isClickableTest($stageWithTasks, 'edit')) && p() && e('1'); // 步骤5：测试有ID阶段的非create操作权限
r($programplanTest->isClickableTest($stageWithTasks, 'CREATE')) && p() && e('0'); // 步骤6：测试大小写敏感的CREATE操作权限
r($programplanTest->isClickableTest($stageWithDeletedTasks, 'create')) && p() && e('1'); // 步骤7：测试只有已删除任务的阶段create操作权限