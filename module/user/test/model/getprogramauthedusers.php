#!/usr/bin/env php
<?php

/**

title=测试 userModel::getProgramAuthedUsers();
timeout=0
cid=19626

- 步骤1：正常情况 @2
- 步骤2：包含利益相关者 @4
- 步骤3：包含白名单用户 @4
- 步骤4：父项目集内部公开 @4
- 步骤5：多层父项目集权限 @6

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$project = zenData('project');
$project->id->range('1-10');
$project->type->range('program{5},project{5}');
$project->parent->range('0{2},1{2},2{1},3{5}');
$project->path->range('`,1,`,`,1,2,`,`,1,2,3,`,`,1,3,`,`,4,`,`,4,5,`,`,4,6,`,`,4,7,`,`,4,8,`');
$project->name->range('项目集1,项目集2,子项目集1,子项目集2,子项目集3,项目1,项目2,项目3,项目4,项目5');
$project->openedBy->range('admin,user1,user2,user3,user4,admin,user1,user2,user3,user4');
$project->PM->range('pm1,pm2,pm3,pm4,pm5,pm1,pm2,pm3,pm4,pm5');
$project->acl->range('open{2},program{3},private{5}');
$project->gen(10);

$company = zenData('company');
$company->id->range('1');
$company->admins->range('`,admin,superuser,`');
$company->gen(1);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$userTest = new userModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r(count($userTest->getProgramAuthedUsersTest((object)array('id' => 1, 'openedBy' => 'admin', 'PM' => 'pm1', 'parent' => 0, 'acl' => 'open', 'path' => ',1,')))) && p() && e('2'); // 步骤1：正常情况
r(count($userTest->getProgramAuthedUsersTest((object)array('id' => 2, 'openedBy' => 'user1', 'PM' => 'pm2', 'parent' => 0, 'acl' => 'open', 'path' => ',2,'), array('stakeholder1' => 'stakeholder1'), array(), array()))) && p() && e('4'); // 步骤2：包含利益相关者
r(count($userTest->getProgramAuthedUsersTest((object)array('id' => 3, 'openedBy' => 'user2', 'PM' => 'pm3', 'parent' => 0, 'acl' => 'open', 'path' => ',3,'), array(), array('whitelist1' => 'whitelist1'), array()))) && p() && e('4'); // 步骤3：包含白名单用户
r(count($userTest->getProgramAuthedUsersTest((object)array('id' => 4, 'openedBy' => 'user3', 'PM' => 'pm4', 'parent' => 1, 'acl' => 'program', 'path' => ',1,4,')))) && p() && e('4'); // 步骤4：父项目集内部公开
r(count($userTest->getProgramAuthedUsersTest((object)array('id' => 5, 'openedBy' => 'user4', 'PM' => 'pm5', 'parent' => 4, 'acl' => 'program', 'path' => ',1,4,5,')))) && p() && e('6'); // 步骤5：多层父项目集权限

/* 恢复公司管理员，避免影响后续用例。*/
global $tester, $app;
$tester->dao->update(TABLE_COMPANY)->set('admins')->eq(',admin,')->where('id')->eq(1)->exec();
$app->company->admins = ',admin,';
