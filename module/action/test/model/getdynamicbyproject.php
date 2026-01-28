#!/usr/bin/env php
<?php

/**

title=测试 actionModel::getDynamicByProject();
timeout=0
cid=14897

- 正常项目ID获取所有用户动态 @20
- 指定用户admin获取项目动态 @10
- 指定用户user1获取项目动态 @0
- 无效项目ID（0）获取动态 @30
- 不存在的项目ID获取动态 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// zendata数据准备
$action = zenData('actionrecent');
$action->id->range('1-30');
$action->objectType->range('task{10},story{10},bug{10}');
$action->objectID->range('1-100');
$action->project->range('101{20},102{5},103{5}');
$action->actor->range('admin{10},dev17{10},test18{10}');
$action->action->range('opened{15},edited{10},closed{5}');
$action->date->range('(-1h)-(+1w):1D')->type('timestamp')->format('YY/MM/DD hh:mm:ss');
$action->gen(30);

zenData('project')->loadYaml('execution')->gen(12);
zenData('user')->loadYaml('user')->gen(3);

// 用户登录
su('admin');

// 创建测试实例
$actionTest = new actionModelTest();

r($actionTest->getDynamicByProjectTest(101)) && p() && e('20');          // 正常项目ID获取所有用户动态
r($actionTest->getDynamicByProjectTest(101, 'admin')) && p() && e('10'); // 指定用户admin获取项目动态
r($actionTest->getDynamicByProjectTest(101, 'user1')) && p() && e('0');  // 指定用户user1获取项目动态
r($actionTest->getDynamicByProjectTest(0)) && p() && e('30');            // 无效项目ID（0）获取动态
r($actionTest->getDynamicByProjectTest(999)) && p() && e('0');           // 不存在的项目ID获取动态
