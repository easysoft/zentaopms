#!/usr/bin/env php
<?php

/**

title=测试 executionZen::setUserMoreLink();
timeout=0
cid=16447

- 步骤1：传入execution对象数组第0条的pm1属性 @P:项目经理1
- 步骤2：传入单个execution对象第0条的pm1属性 @P:项目经理1
- 步骤3：传入null参数，验证返回数组长度 @4
- 步骤4：传入空数组，验证返回数组长度 @4
- 步骤5：验证PO用户获取第1条的po1属性 @P:产品经理1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$user = zenData('user');
$user->account->range('admin,pm1,pm2,po1,po2,qd1,qd2,rd1,rd2,dev1');
$user->realname->range('管理员,项目经理1,项目经理2,产品经理1,产品经理2,测试主管1,测试主管2,研发主管1,研发主管2,开发1');
$user->role->range('admin{1},pm{2},po{2},qd{2},rd{2},dev{1}');
$user->deleted->range('0');
$user->gen(10);

$project = zenData('project');
$project->id->range('1-5');
$project->name->range('项目1,项目2,项目3,项目4,项目5');
$project->type->range('sprint');
$project->parent->range('0');
$project->status->range('doing');
$project->deleted->range('0');
$project->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$executionTest = new executionZenTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($executionTest->setUserMoreLinkTest(array((object)array('PO' => 'po1', 'PM' => 'pm1', 'QD' => 'qd1', 'RD' => 'rd1'), (object)array('PO' => 'po2', 'PM' => 'pm2', 'QD' => 'qd2', 'RD' => 'rd2')))) && p('0:pm1') && e('P:项目经理1'); // 步骤1：传入execution对象数组
r($executionTest->setUserMoreLinkTest((object)array('PO' => 'po1', 'PM' => 'pm1', 'QD' => 'qd1', 'RD' => 'rd1'))) && p('0:pm1') && e('P:项目经理1'); // 步骤2：传入单个execution对象
r(count($executionTest->setUserMoreLinkTest(null))) && p() && e('4'); // 步骤3：传入null参数，验证返回数组长度
r(count($executionTest->setUserMoreLinkTest(array()))) && p() && e('4'); // 步骤4：传入空数组，验证返回数组长度
r($executionTest->setUserMoreLinkTest((object)array('PO' => 'po1', 'PM' => 'pm1', 'QD' => 'qd1', 'RD' => 'rd1'))) && p('1:po1') && e('P:产品经理1'); // 步骤5：验证PO用户获取