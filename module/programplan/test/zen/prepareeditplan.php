#!/usr/bin/env php
<?php

/**

title=测试 programplanZen::prepareEditPlan();
timeout=0
cid=0

- 执行func模块的invokeArgs方法，参数是$instance, [3, 1, $plan, $parentStage]) ? 'success' : 'fail  @success
- 执行属性end @"计划完成时间"必须大于"计划开始时间"
- 执行属性begin @子阶段计划开始不能小于父阶段的计划开始时间 2024-01-01
- 执行属性end @子阶段计划完成不能超过父阶段的计划完成时间 2024-01-31
- 执行func模块的invokeArgs方法，参数是$instance, [3, 2, $researchPlan, null]) ? 'success' : 'fail  @success

*/

include dirname(__FILE__, 5). '/test/lib/init.php';
su('admin');

// 准备基础数据
$table = zenData('project');
$table->id->range('1-3');
$table->name->range('测试项目,调研项目,阶段1');
$table->type->range('project{2},stage');
$table->begin->range('`2024-01-01`{3}');
$table->end->range('`2024-12-31`{3}');
$table->model->range('scrum,research,scrum');
$table->acl->range('open{3}');
$table->attribute->range('mix{2},request');
$table->milestone->range('0{3}');
$table->percent->range('0{3}');
$table->parent->range('0{3}');
$table->status->range('doing{3}');
$table->gen(3);

global $tester;
$zen = initReference('programplan', 'zen');
$func = $zen->getMethod('prepareEditPlan');
$instance = $zen->newInstance();

// 初始化所需的模型
$instance->programplan = $tester->loadModel('programplan');

// 测试步骤1: 正常情况 - 编辑一个有效的计划
$plan = new stdclass();
$plan->parent = 1;
$plan->begin = '2024-01-10';
$plan->end = '2024-01-20';
$plan->percent = 15;

$parentStage = new stdclass();
$parentStage->begin = '2024-01-01';
$parentStage->end = '2024-01-31';

r($func->invokeArgs($instance, [3, 1, $plan, $parentStage]) ? 'success' : 'fail') && p() && e('success');

dao::$errors = array();

// 测试步骤2: 结束时间早于开始时间
$invalidPlan = new stdclass();
$invalidPlan->parent = 1;
$invalidPlan->begin = '2024-01-20';
$invalidPlan->end = '2024-01-10';
$invalidPlan->percent = 10;

$func->invokeArgs($instance, [3, 1, $invalidPlan, $parentStage]);
r(dao::getError()) && p('end') && e('"计划完成时间"必须大于"计划开始时间"');

dao::$errors = array();

// 测试步骤3: 开始时间早于父阶段开始时间
$earlyPlan = new stdclass();
$earlyPlan->parent = 1;
$earlyPlan->begin = '2023-12-15';
$earlyPlan->end = '2024-01-20';
$earlyPlan->percent = 10;

$func->invokeArgs($instance, [3, 1, $earlyPlan, $parentStage]);
r(dao::getError()) && p('begin') && e('子阶段计划开始不能小于父阶段的计划开始时间 2024-01-01');

dao::$errors = array();

// 测试步骤4: 结束时间晚于父阶段结束时间
$latePlan = new stdclass();
$latePlan->parent = 1;
$latePlan->begin = '2024-01-10';
$latePlan->end = '2024-02-15';
$latePlan->percent = 10;

$func->invokeArgs($instance, [3, 1, $latePlan, $parentStage]);
r(dao::getError()) && p('end') && e('子阶段计划完成不能超过父阶段的计划完成时间 2024-01-31');

dao::$errors = array();

// 测试步骤5: 调研模式项目保持原有属性
$researchPlan = new stdclass();
$researchPlan->parent = 0;
$researchPlan->begin = '2024-01-10';
$researchPlan->end = '2024-01-20';
$researchPlan->percent = 10;

r($func->invokeArgs($instance, [3, 2, $researchPlan, null]) ? 'success' : 'fail') && p() && e('success');
