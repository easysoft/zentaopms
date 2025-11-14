#!/usr/bin/env php
<?php

/**

title=测试 taskZen::buildUsersAndMembersToForm();
timeout=0
cid=18917

- 步骤1：正常任务用户成员构建
 - 属性success @1
 - 属性hasMembers @1
 - 属性hasUsers @1
- 步骤2：团队任务用户成员构建
 - 属性success @1
 - 属性hasMembers @1
 - 属性hasUsers @1
 - 属性hasManageLink @1
- 步骤3：关闭任务用户成员构建
 - 属性success @1
 - 属性hasMembers @1
 - 属性hasUsers @1
 - 属性hasManageLink @1
- 步骤4：无效任务ID用户成员构建
 - 属性success @1
 - 属性hasMembers @0
 - 属性hasUsers @0
- 步骤5：research项目任务用户成员构建
 - 属性success @1
 - 属性hasMembers @1
 - 属性hasUsers @1
 - 属性hasManageLink @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$taskTable = zenData('task');
$taskTable->id->range('1-10');
$taskTable->project->range('1-5');
$taskTable->execution->range('1-5');
$taskTable->name->range('测试任务1,测试任务2,测试任务3,团队任务1,团队任务2');
$taskTable->status->range('wait{3},doing{3},done{2},closed{1},cancel{1}');
$taskTable->assignedTo->range('admin{4},user1{3},user2{2},closed{1}');
$taskTable->openedBy->range('admin{5},user1{5}');
$taskTable->type->range('design{3},devel{3},test{2},study{1},discuss{1}');
$taskTable->gen(10);

$projectTable = zenData('project');
$projectTable->id->range('1-5');
$projectTable->name->range('项目1,项目2,项目3,研究项目1,研究项目2');
$projectTable->model->range('scrum{3},waterfall{1},research{1}');
$projectTable->status->range('wait{2},doing{2},done{1}');
$projectTable->gen(5);

$userTable = zenData('user');
$userTable->id->range('1-10');
$userTable->account->range('admin,user1,user2,user3,user4,user5,user6,user7,user8,user9');
$userTable->realname->range('管理员,用户1,用户2,用户3,用户4,用户5,用户6,用户7,用户8,用户9');
$userTable->role->range('admin{1},dev{4},qa{2},pm{2},po{1}');
$userTable->deleted->range('0{8},1{2}');
$userTable->gen(10);

$teamTable = zenData('team');
$teamTable->id->range('1-12');
$teamTable->root->range('1,1,1,2,2,3,3,4,4,5,5,5');
$teamTable->type->range('project{9},execution{3}');
$teamTable->account->range('admin,user1,user2,user3,user4,user5,user6,user7,user8,user9,admin,user1');
$teamTable->role->range('dev{6},qa{3},pm{3}');
$teamTable->gen(12);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$taskTest = new taskZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($taskTest->buildUsersAndMembersToFormTest(1, 1)) && p('success,hasMembers,hasUsers') && e('1,1,1'); // 步骤1：正常任务用户成员构建
r($taskTest->buildUsersAndMembersToFormTest(2, 4)) && p('success,hasMembers,hasUsers,hasManageLink') && e('1,1,1,1'); // 步骤2：团队任务用户成员构建
r($taskTest->buildUsersAndMembersToFormTest(3, 9)) && p('success,hasMembers,hasUsers,hasManageLink') && e('1,1,1,1'); // 步骤3：关闭任务用户成员构建
r($taskTest->buildUsersAndMembersToFormTest(4, 999)) && p('success,hasMembers,hasUsers') && e('1,0,0'); // 步骤4：无效任务ID用户成员构建
r($taskTest->buildUsersAndMembersToFormTest(5, 5)) && p('success,hasMembers,hasUsers,hasManageLink') && e('1,1,1,1'); // 步骤5：research项目任务用户成员构建