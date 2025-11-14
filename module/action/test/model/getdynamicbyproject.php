#!/usr/bin/env php
<?php

/**

title=测试 actionModel::getDynamicByProject();
timeout=0
cid=14897

- 执行actionTest模块的getDynamicByProjectTest方法，参数是101  @20
- 执行actionTest模块的getDynamicByProjectTest方法，参数是101, 'admin'  @10
- 执行actionTest模块的getDynamicByProjectTest方法  @0
- 执行actionTest模块的getDynamicByProjectTest方法，参数是999  @0
- 执行actionTest模块的getDynamicByProjectTest方法，参数是101, 'all', 'today'  @5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

// zendata数据准备
$action = zenData('action');
$action->id->range('1-30');
$action->objectType->range('task{10},story{10},bug{10}');
$action->objectID->range('1-100');
$action->project->range('101{20},102{5},103{5}');
$action->actor->range('admin{10},dev17{10},test18{10}');
$action->action->range('opened{15},edited{10},closed{5}');
$action->date->range('(-1h)-(+1w):1D');
$action->gen(30);

zenData('project')->loadYaml('execution')->gen(12);
zenData('user')->loadYaml('user')->gen(5);

// 用户登录
su('admin');

// 创建测试实例
$actionTest = new actionTest();

// 测试步骤1：正常项目ID获取所有用户动态
r($actionTest->getDynamicByProjectTest(101)) && p() && e('20');
// 测试步骤2：指定用户admin获取项目动态
r($actionTest->getDynamicByProjectTest(101, 'admin')) && p() && e('10');
// 测试步骤3：无效项目ID（0）获取动态
r($actionTest->getDynamicByProjectTest(0)) && p() && e('0');
// 测试步骤4：不存在的项目ID获取动态
r($actionTest->getDynamicByProjectTest(999)) && p() && e('0');
// 测试步骤5：指定时间段获取项目动态
r($actionTest->getDynamicByProjectTest(101, 'all', 'today')) && p() && e('5');