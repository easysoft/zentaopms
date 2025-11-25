#!/usr/bin/env php
<?php

/**

title=测试 programplanZen::buildPlansForCreate();
timeout=0
cid=17789

- 执行 @0
- 执行属性end[0] @"计划完成时间"必须大于"计划开始时间"
- 执行属性begin[0] @0
- 执行属性name[0] @包含子阶段，阶段名称不能为空。
- 执行属性percent @相同父阶段的子阶段工作量占比之和不超过100%

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

// 准备基础项目数据 - 简化数据准备
$table = zenData('project');
$table->id->range('1');
$table->name->range('测试项目');
$table->type->range('project');
$table->begin->range('`2024-01-01`');
$table->end->range('`2024-12-31`');
$table->hasProduct->range('1');
$table->model->range('waterfall');
$table->status->range('doing');
$table->acl->range('open');
$table->parent->range('0');
$table->isTpl->range('0');
$table->gen(1);

global $config, $tester;
$config->setPercent = '1';
$tester->loadModel('programplan');
$config->programplan->custom->createWaterfallFields .= ',percent';

// 使用直接调用测试方法，专注于验证错误情况
$zen = initReference('programplan', 'zen');
$func = $zen->getMethod('buildPlansForCreate');

// 测试步骤1：空名称错误
$_POST = array(
    'level' => array(0),
    'name'  => array('')
);
$instance = $zen->newInstance();
$result = $func->invokeArgs($instance, [1, 0]);
r(dao::getError()) && p('') && e('0');

dao::$errors = array();
unset($_POST);

// 测试步骤3：日期错误检查
$_POST = array(
    'level'  => array(0),
    'name'   => array('测试阶段'),
    'begin'  => array('2024-04-01'),
    'end'    => array('2024-03-01')
);
$instance = $zen->newInstance();
$result = $func->invokeArgs($instance, [1, 0]);
r(dao::getError()) && p('end[0]')   && e('"计划完成时间"必须大于"计划开始时间"');
r(dao::getError()) && p('begin[0]') && e('0');

dao::$errors = array();
unset($_POST);

// 测试步骤4：父级名称为空错误
$_POST = array(
    'level' => array(0, 1),
    'name'  => array('', '子阶段')
);
$instance = $zen->newInstance();
$result = $func->invokeArgs($instance, [1, 0]);
r(dao::getError()) && p('name[0]') && e('包含子阶段，阶段名称不能为空。');

dao::$errors = array();
unset($_POST);

// 测试步骤5：百分比超限错误
$_POST = array(
    'level'   => array(0, 1, 1),
    'name'    => array('父阶段', '子阶段1', '子阶段2'),
    'percent' => array('100', '60', '50'),
    'begin'   => array('2024-02-01', '2024-02-01', '2024-03-01'),
    'end'     => array('2024-04-30', '2024-02-28', '2024-04-30')
);
$instance = $zen->newInstance();
$result = $func->invokeArgs($instance, [1, 0]);
r(dao::getError()) && p('percent') && e('相同父阶段的子阶段工作量占比之和不超过100%');
