#!/usr/bin/env php
<?php

/**

title=测试 taskZen::assignKanbanForCreate();
timeout=0
cid=18897

- 步骤1：正常情况，使用默认区域和泳道
 - 属性regionID @1
 - 属性laneID @1
 - 属性regionPairs @3
 - 属性lanePairs @2
- 步骤2：指定区域ID，获取该区域下默认泳道
 - 属性regionID @2
 - 属性laneID @3
 - 属性regionPairs @3
 - 属性lanePairs @2
- 步骤3：同时指定区域和泳道ID
 - 属性regionID @3
 - 属性laneID @6
 - 属性regionPairs @3
 - 属性lanePairs @2
- 步骤4：不同的执行ID，使用默认区域和泳道
 - 属性regionID @4
 - 属性laneID @7
 - 属性regionPairs @3
 - 属性lanePairs @2
- 步骤5：传入0值，期望使用默认区域和泳道
 - 属性regionID @1
 - 属性laneID @1
 - 属性regionPairs @3
 - 属性lanePairs @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$project = zenData('project');
$project->id->range('1-5');
$project->name->range('看板项目{1}, 项目{4}');
$project->type->range('kanban{1}, sprint{4}');
$project->status->range('doing{5}');
$project->gen(5);

$kanbanregion = zenData('kanbanregion');
$kanbanregion->id->range('1-9');
$kanbanregion->space->range('0{9}'); // execution模式需要space=0
$kanbanregion->kanban->range('1{3}, 2{3}, 3{3}'); // 为每个executionID创建3个region
$kanbanregion->name->range('待办区域, 进行中区域, 完成区域');
$kanbanregion->order->range('1-3');
$kanbanregion->deleted->range('0{9}');
$kanbanregion->gen(9);

$kanbanlane = zenData('kanbanlane');
$kanbanlane->id->range('1-18');
$kanbanlane->execution->range('1{6}, 2{6}, 3{6}');
$kanbanlane->type->range('task{18}');
$kanbanlane->region->range('1{2}, 2{2}, 3{2}, 4{2}, 5{2}, 6{2}, 7{2}, 8{2}, 9{2}'); // 为每个region创建2个lane
$kanbanlane->name->range('任务泳道1, 任务泳道2');
$kanbanlane->order->range('1-18');
$kanbanlane->deleted->range('0{18}');
$kanbanlane->gen(18);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$taskZenTest = new taskZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($taskZenTest->assignKanbanForCreateTest(1, array())) && p('regionID,laneID,regionPairs,lanePairs') && e('1,1,3,2'); // 步骤1：正常情况，使用默认区域和泳道
r($taskZenTest->assignKanbanForCreateTest(1, array('regionID' => 2))) && p('regionID,laneID,regionPairs,lanePairs') && e('2,3,3,2'); // 步骤2：指定区域ID，获取该区域下默认泳道
r($taskZenTest->assignKanbanForCreateTest(1, array('regionID' => 3, 'laneID' => 6))) && p('regionID,laneID,regionPairs,lanePairs') && e('3,6,3,2'); // 步骤3：同时指定区域和泳道ID
r($taskZenTest->assignKanbanForCreateTest(2, array())) && p('regionID,laneID,regionPairs,lanePairs') && e('4,7,3,2'); // 步骤4：不同的执行ID，使用默认区域和泳道
r($taskZenTest->assignKanbanForCreateTest(1, array('regionID' => 0, 'laneID' => 0))) && p('regionID,laneID,regionPairs,lanePairs') && e('1,1,3,2'); // 步骤5：传入0值，期望使用默认区域和泳道