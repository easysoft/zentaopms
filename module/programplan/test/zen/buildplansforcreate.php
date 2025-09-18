#!/usr/bin/env php
<?php

/**

title=测试 programplanZen::buildPlansForCreate();
timeout=0
cid=0

- 执行属性name[0] @名称不能为空
- 执行属性name[1] @名称已存在
- 执行属性end[0] @完成时间不能小于开始时间
- 执行属性name[0] @父级名称不能为空
- 执行属性percent @工作量占比总和不能超过100%

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

// 准备基础项目数据 - 简化数据准备
$table = zenData('project');
$table->id->range('1');
$table->name->range('测试项目');
$table->type->range('project');
$table->begin->range('2024-01-01');
$table->end->range('2024-12-31');
$table->hasProduct->range('1');
$table->model->range('scrum');
$table->status->range('doing');
$table->acl->range('open');
$table->parent->range('0');
$table->isTpl->range('0');
$table->gen(1);

// 使用直接调用测试方法，专注于验证错误情况
global $tester;
$zen = initReference('programplan', 'zen');
$func = $zen->getMethod('buildPlansForCreate');

// 测试步骤1：空名称错误
$_POST = array(
    'level' => array(0),
    'name'  => array('')
);
$instance = $zen->newInstance();
$result = $func->invokeArgs($instance, [1, 0]);
r(dao::getError()) && p('name[0]') && e('名称不能为空');

dao::$errors = array();
unset($_POST);

// 测试步骤2：重复名称检查
$_POST = array(
    'level' => array(0, 0),
    'name'  => array('重复名称', '重复名称')
);
$instance = $zen->newInstance();
$result = $func->invokeArgs($instance, [1, 0]);
r(dao::getError()) && p('name[1]') && e('名称已存在');

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
r(dao::getError()) && p('end[0]') && e('完成时间不能小于开始时间');

dao::$errors = array();
unset($_POST);

// 测试步骤4：父级名称为空错误
$_POST = array(
    'level' => array(0, 1),
    'name'  => array('', '子阶段')
);
$instance = $zen->newInstance();
$result = $func->invokeArgs($instance, [1, 0]);
r(dao::getError()) && p('name[0]') && e('父级名称不能为空');

dao::$errors = array();
unset($_POST);

// 测试步骤5：百分比超限错误
global $config;
$config->setPercent = '1';
$_POST = array(
    'level'   => array(0, 1, 1),
    'name'    => array('父阶段', '子阶段1', '子阶段2'),
    'percent' => array('100', '60', '50'),
    'begin'   => array('2024-02-01', '2024-02-01', '2024-03-01'),
    'end'     => array('2024-04-30', '2024-02-28', '2024-04-30')
);
$instance = $zen->newInstance();
$result = $func->invokeArgs($instance, [1, 0]);
r(dao::getError()) && p('percent') && e('工作量占比总和不能超过100%');