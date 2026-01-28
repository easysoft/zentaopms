#!/usr/bin/env php
<?php

/**

title=测试 kanbanTao::appendTeamMember();
timeout=0
cid=16969

- 步骤1：正常情况 @Array
- 步骤2：空数组 @(
- 步骤3：无多人任务 @[4] => stdClass Object
- 步骤4：混合任务 @(
- 步骤5：多人任务有团队成员 @[id] => 4

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. zendata数据准备（根据需要配置）
$taskTable = zenData('task');
$taskTable->id->range('1-10');
$taskTable->name->range('任务{1-10}');
$taskTable->mode->range('linear{3},multi{4},linear{3}');
$taskTable->gen(10);

$taskTeamTable = zenData('taskteam');
$taskTeamTable->id->range('1-8');
$taskTeamTable->task->range('4,4,5,5,6,6,7,7');
$taskTeamTable->account->range('user1,user2,user1,user3,user2,user4,user1,user2');
$taskTeamTable->gen(8);

$userTable = zenData('user');
$userTable->id->range('1-5');
$userTable->account->range('user1,user2,user3,user4,user5');
$userTable->realname->range('用户1,用户2,用户3,用户4,用户5');
$userTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$kanbanTest = new kanbanTaoTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($kanbanTest->appendTeamMemberTest(array('4' => (object)array('id' => 4, 'mode' => 'multi'), '5' => (object)array('id' => 5, 'mode' => 'multi')))) && p() && e('Array'); // 步骤1：正常情况
r($kanbanTest->appendTeamMemberTest(array())) && p() && e('('); // 步骤2：空数组
r($kanbanTest->appendTeamMemberTest(array('1' => (object)array('id' => 1, 'mode' => 'linear'), '2' => (object)array('id' => 2, 'mode' => 'linear')))) && p() && e('[4] => stdClass Object'); // 步骤3：无多人任务
r($kanbanTest->appendTeamMemberTest(array('1' => (object)array('id' => 1, 'mode' => 'linear'), '4' => (object)array('id' => 4, 'mode' => 'multi')))) && p() && e('('); // 步骤4：混合任务
r($kanbanTest->appendTeamMemberTest(array('7' => (object)array('id' => 7, 'mode' => 'multi')))) && p() && e('[id] => 4'); // 步骤5：多人任务有团队成员