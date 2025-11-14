#!/usr/bin/env php
<?php

/**

title=测试 programplanZen::prepareEditPlan();
timeout=0
cid=17794

- 步骤1: 正常编辑阶段,所有日期都合法
 - 属性begin @2024-02-01
 - 属性end @2024-03-31
- 步骤2: 结束日期早于开始日期属性end @"计划完成时间"必须大于"计划开始时间"
- 步骤3: 子阶段开始日期早于父阶段开始日期属性begin @子阶段计划开始不能小于父阶段的计划开始时间 2024-01-01
- 步骤4: 子阶段结束日期晚于父阶段结束日期属性end @子阶段计划完成不能超过父阶段的计划完成时间 2024-12-31
- 步骤5: 无父阶段的正常编辑
 - 属性begin @2024-02-01
 - 属性end @2024-03-31
- 步骤6: research模型保留原阶段属性
 - 属性begin @2024-02-01
 - 属性end @2024-03-31
- 步骤7: 无效项目ID,但日期合法
 - 属性begin @2024-03-01
 - 属性end @2024-05-31

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

// 准备基础项目数据
$table = zenData('project');
$table->id->range('1-10');
$table->name->range('测试项目{1-10}');
$table->type->range('project{3},stage{7}');
$table->begin->range('`2024-01-01`');
$table->end->range('`2024-12-31`');
$table->hasProduct->range('1');
$table->model->range('waterfall,waterfall,research,waterfall,waterfall,waterfall,waterfall,waterfall,waterfall,waterfall');
$table->status->range('doing');
$table->acl->range('open');
$table->parent->range('0,0,0,1,1,2,2,2,2,2');
$table->percent->range('0,0,0,10,20,30,40,50,60,10');
$table->attribute->range('request');
$table->milestone->range('0');
$table->gen(10);

zenData('user');

global $config, $tester;
$config->setPercent = '1';

$tester->loadModel('programplan');

$zen = initReference('programplan', 'zen');
$func = $zen->getMethod('prepareEditPlan');
$instance = $zen->newInstance();
$instance->programplan = $tester->loadModel('programplan');

// 步骤1: 正常编辑阶段,所有日期都合法
$plan1 = new stdclass();
$plan1->parent = 1;
$plan1->begin = '2024-02-01';
$plan1->end = '2024-03-31';
$plan1->percent = 20;
$plan1->product = 0;

$parentStage1 = new stdclass();
$parentStage1->id = 1;
$parentStage1->begin = '2024-01-01';
$parentStage1->end = '2024-12-31';
$parentStage1->percent = 100;
$parentStage1->product = 0;

$result1 = $func->invokeArgs($instance, [4, 1, $plan1, $parentStage1]);
r($result1) && p('begin,end') && e('2024-02-01,2024-03-31'); // 步骤1: 正常编辑阶段,所有日期都合法

dao::$errors = array();

// 步骤2: 结束日期早于开始日期
$plan2 = new stdclass();
$plan2->parent = 1;
$plan2->begin = '2024-04-01';
$plan2->end = '2024-03-01';
$plan2->percent = 10;
$plan2->product = 0;

$result2 = $func->invokeArgs($instance, [5, 1, $plan2, $parentStage1]);
r(dao::getError()) && p('end') && e('"计划完成时间"必须大于"计划开始时间"'); // 步骤2: 结束日期早于开始日期

dao::$errors = array();

// 步骤3: 子阶段开始日期早于父阶段开始日期
$plan3 = new stdclass();
$plan3->parent = 1;
$plan3->begin = '2023-12-01';
$plan3->end = '2024-03-31';
$plan3->percent = 10;
$plan3->product = 0;

$parentStage3 = new stdclass();
$parentStage3->id = 1;
$parentStage3->begin = '2024-01-01';
$parentStage3->end = '2024-12-31';
$parentStage3->product = 0;

$result3 = $func->invokeArgs($instance, [6, 1, $plan3, $parentStage3]);
r(dao::getError()) && p('begin') && e('子阶段计划开始不能小于父阶段的计划开始时间 2024-01-01'); // 步骤3: 子阶段开始日期早于父阶段开始日期

dao::$errors = array();

// 步骤4: 子阶段结束日期晚于父阶段结束日期
$plan4 = new stdclass();
$plan4->parent = 1;
$plan4->begin = '2024-02-01';
$plan4->end = '2025-01-31';
$plan4->percent = 10;
$plan4->product = 0;

$parentStage4 = new stdclass();
$parentStage4->id = 1;
$parentStage4->begin = '2024-01-01';
$parentStage4->end = '2024-12-31';
$parentStage4->product = 0;

$result4 = $func->invokeArgs($instance, [7, 1, $plan4, $parentStage4]);
r(dao::getError()) && p('end') && e('子阶段计划完成不能超过父阶段的计划完成时间 2024-12-31'); // 步骤4: 子阶段结束日期晚于父阶段结束日期

dao::$errors = array();

// 步骤5: 无父阶段的正常编辑
$plan5 = new stdclass();
$plan5->parent = 0;
$plan5->begin = '2024-02-01';
$plan5->end = '2024-03-31';
$plan5->percent = 20;
$plan5->product = 0;

$result5 = $func->invokeArgs($instance, [1, 1, $plan5, null]);
r($result5) && p('begin,end') && e('2024-02-01,2024-03-31'); // 步骤5: 无父阶段的正常编辑

dao::$errors = array();

// 步骤6: research模型保留原阶段属性
$plan6 = new stdclass();
$plan6->parent = 0;
$plan6->begin = '2024-02-01';
$plan6->end = '2024-03-31';
$plan6->percent = 10;
$plan6->product = 0;

$result6 = $func->invokeArgs($instance, [3, 3, $plan6, null]);
r($result6) && p('begin,end') && e('2024-02-01,2024-03-31'); // 步骤6: research模型保留原阶段属性

dao::$errors = array();

// 步骤7: 无效项目ID,但日期合法
$plan7 = new stdclass();
$plan7->parent = 1;
$plan7->begin = '2024-03-01';
$plan7->end = '2024-05-31';
$plan7->percent = 15;
$plan7->product = 0;

$result7 = $func->invokeArgs($instance, [5, 1, $plan7, $parentStage1]);
r($result7) && p('begin,end') && e('2024-03-01,2024-05-31'); // 步骤7: 无效项目ID,但日期合法