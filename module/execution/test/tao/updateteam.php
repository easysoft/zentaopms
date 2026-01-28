#!/usr/bin/env php
<?php

/**

title=测试 executionTao::updateTeam();
timeout=0
cid=16399

- 步骤1：正常添加新成员 @4
- 步骤2：移除现有成员 @0
- 步骤3：正常团队更新 @7
- 步骤4：空成员列表（保留必要角色） @1
- 步骤5：角色成员处理 @5

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. zendata数据准备（根据需要配置）
$execution = zenData('project');
$execution->id->range('1,2,3');
$execution->name->range('执行1,执行2,执行3');
$execution->type->range('sprint');
$execution->status->range('wait');
$execution->project->range('1');
$execution->PO->range('po1,po2,admin');
$execution->PM->range('pm1,pm2,admin');
$execution->QD->range('qd1,qd2,admin');
$execution->RD->range('rd1,rd2,admin');
$execution->openedBy->range('admin');
$execution->acl->range('private');
$execution->days->range('10');
$execution->gen(3);

$user = zenData('user');
$user->id->range('1-10');
$user->account->range('admin,po1,pm1,qd1,rd1,user1,user2,user3,user4,user5');
$user->realname->range('管理员,产品经理1,项目经理1,测试主管1,开发主管1,用户1,用户2,用户3,用户4,用户5');
$user->role->range('admin,po,pm,qd,rd,dev,dev,test,test,qa');
$user->deleted->range('0');
$user->gen(10);

$team = zenData('team');
$team->id->range('1-5');
$team->root->range('1{3},2{2}');
$team->type->range('execution');
$team->account->range('admin,user1,user2,admin,user3');
$team->role->range('admin,dev,dev,admin,test');
$team->join->range('`2024-01-01`');
$team->days->range('10');
$team->hours->range('8.0');
$team->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$executionTest = new executionTaoTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($executionTest->updateTeamTest(1, 'add', array('user4', 'user5'))) && p() && e('4'); // 步骤1：正常添加新成员
r($executionTest->updateTeamTest(1, 'remove', array('user1'))) && p() && e('0'); // 步骤2：移除现有成员
r($executionTest->updateTeamTest(1, 'normal', array())) && p() && e('7'); // 步骤3：正常团队更新
r($executionTest->updateTeamTest(3, 'empty', array())) && p() && e('1'); // 步骤4：空成员列表（保留必要角色）
r($executionTest->updateTeamTest(2, 'roles', array('po1', 'pm1', 'qd1', 'rd1'))) && p() && e('5'); // 步骤5：角色成员处理