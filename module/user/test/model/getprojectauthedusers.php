#!/usr/bin/env php
<?php

/**

title=测试 userModel::getProjectAuthedUsers();
timeout=0
cid=19629

- 步骤1：基本项目角色获取属性user1 @user1
- 步骤2：包含利益相关者属性stakeholder1 @stakeholder1
- 步骤3：项目集内公开项目属性user4 @user4
- 步骤4：私有迭代项目属性user7 @user7
- 步骤5：各种用户组合并测试属性team1 @team1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/user.unittest.class.php';

// 2. zendata数据准备
$table = zenData('project');
$table->id->range('1-10');
$table->name->range('项目1,项目2,项目集1,父项目1,迭代1,迭代2,项目3,项目4,项目5,项目6');
$table->type->range('project{6},sprint{4}');
$table->parent->range('0{6},4{4}');
$table->project->range('0{6},4{4}');
$table->path->range(',1,,2,,3,,1,2,3,,4,,4,,4,,4,');
$table->openedBy->range('user1,user2,admin,user3,user4,user5,user6,user7,user8,user9');
$table->PM->range('pm1,pm2,pm3,pm4,pm5,pm6,pm7,pm8,pm9,pm10');
$table->PO->range('po1,po2,po3,po4,po5,po6,po7,po8,po9,po10');
$table->QD->range('qd1,qd2,qd3,qd4,qd5,qd6,qd7,qd8,qd9,qd10');
$table->RD->range('rd1,rd2,rd3,rd4,rd5,rd6,rd7,rd8,rd9,rd10');
$table->acl->range('open{3},program{3},private{4}');
$table->deleted->range('0');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$userTest = new userTest();

// 创建测试项目对象
$basicProject = new stdClass();
$basicProject->id = 1;
$basicProject->type = 'project';
$basicProject->parent = 0;
$basicProject->path = ',1,';
$basicProject->acl = 'open';
$basicProject->openedBy = 'user1';
$basicProject->PM = 'pm1';
$basicProject->PO = 'po1';
$basicProject->QD = 'qd1';
$basicProject->RD = 'rd1';

$programProject = new stdClass();
$programProject->id = 4;
$programProject->type = 'project';
$programProject->parent = 3;
$programProject->path = ',3,4,';
$programProject->acl = 'program';
$programProject->openedBy = 'user4';
$programProject->PM = 'pm4';
$programProject->PO = 'po4';
$programProject->QD = 'qd4';
$programProject->RD = 'rd4';

$sprintProject = new stdClass();
$sprintProject->id = 7;
$sprintProject->type = 'sprint';
$sprintProject->project = 4;
$sprintProject->acl = 'private';
$sprintProject->openedBy = 'user7';
$sprintProject->PM = 'pm7';
$sprintProject->PO = 'po7';
$sprintProject->QD = 'qd7';
$sprintProject->RD = 'rd7';

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($userTest->getProjectAuthedUsersTest($basicProject, array(), array(), array())) && p('user1') && e('user1'); // 步骤1：基本项目角色获取
r($userTest->getProjectAuthedUsersTest($basicProject, array('stakeholder1' => 'stakeholder1'), array(), array())) && p('stakeholder1') && e('stakeholder1'); // 步骤2：包含利益相关者
r($userTest->getProjectAuthedUsersTest($programProject, array(), array(), array())) && p('user4') && e('user4'); // 步骤3：项目集内公开项目
r($userTest->getProjectAuthedUsersTest($sprintProject, array(), array(), array())) && p('user7') && e('user7'); // 步骤4：私有迭代项目
r($userTest->getProjectAuthedUsersTest($basicProject, array(), array('team1' => 'team1'), array('whitelist1' => 'whitelist1'), array('customAdmin' => 'customAdmin'))) && p('team1') && e('team1'); // 步骤5：各种用户组合并测试