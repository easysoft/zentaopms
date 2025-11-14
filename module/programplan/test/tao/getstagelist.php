#!/usr/bin/env php
<?php

/**

title=测试 programplanTao::getStageList();
timeout=0
cid=17773

- 步骤1：正常情况 - 获取指定执行下的阶段第2条的name属性 @阶段1-1
- 步骤2：边界值 - 不存在的执行ID和产品ID @0
- 步骤3：异常输入 - 空执行ID @0
- 步骤4：不同browseType测试 @0
- 步骤5：排序测试第3条的name属性 @阶段1-2
- 步骤6：leaf类型测试第4条的name属性 @阶段2-1
- 步骤7：权限控制测试第2条的name属性 @阶段1-1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/programplan.unittest.class.php';

// 2. zendata数据准备
$projectTable = zenData('project');
$projectTable->id->range('1-10');
$projectTable->project->range('0,1,1,2,2,3,3,4,4,5');
$projectTable->name->range('项目1,阶段1-1,阶段1-2,阶段2-1,阶段2-2,阶段3-1,阶段3-2,阶段4-1,阶段4-2,阶段5-1');
$projectTable->type->range('project,stage,stage,stage,stage,stage,stage,stage,stage,stage');
$projectTable->status->range('wait{3},doing{4},closed{3}');
$projectTable->deleted->range('0{8},1{2}');
$projectTable->enabled->range('on{8},off{2}');
$projectTable->model->range('scrum{5},waterfallplus{3},ipd{2}');
$projectTable->gen(10);

$productTable = zenData('projectproduct');
$productTable->project->range('1-10');
$productTable->product->range('1{3},2{3},3{4}');
$productTable->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$programplanTest = new programplanTest();

// 5. 测试步骤（至少5个）
r($programplanTest->getStageListTest(1, 1, 'all', 'id_asc')) && p('2:name') && e('阶段1-1'); // 步骤1：正常情况 - 获取指定执行下的阶段
r($programplanTest->getStageListTest(999, 999, 'all', 'id_asc')) && p() && e('0'); // 步骤2：边界值 - 不存在的执行ID和产品ID
r($programplanTest->getStageListTest(0, 1, 'all', 'id_asc')) && p() && e('0'); // 步骤3：异常输入 - 空执行ID
r($programplanTest->getStageListTest(1, 1, 'parent', 'id_asc')) && p() && e('0'); // 步骤4：不同browseType测试
r($programplanTest->getStageListTest(1, 1, 'all', 'id_desc')) && p('3:name') && e('阶段1-2'); // 步骤5：排序测试
r($programplanTest->getStageListTest(2, 2, 'leaf', 'id_asc')) && p('4:name') && e('阶段2-1'); // 步骤6：leaf类型测试
su('user'); // 切换非管理员用户
r($programplanTest->getStageListTest(1, 1, 'all', 'id_asc')) && p('2:name') && e('阶段1-1'); // 步骤7：权限控制测试