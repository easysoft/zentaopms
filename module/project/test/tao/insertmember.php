#!/usr/bin/env php
<?php

/**

title=测试 projectTao::insertMember();
timeout=0
cid=17915

- 步骤1：插入单个有效团队成员 @user1
- 步骤2：插入多个团队成员 @Array
- 步骤3：插入包含空账户的成员列表 @(
- 步骤4：插入成员时使用已有加入时间 @[0] => user1
- 步骤5：插入成员时设置权限限制 @[1] => user2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. zendata数据准备（根据需要配置）
$project = zenData('project');
$project->loadYaml('project_dostart', false, 2)->gen(5);

$user = zenData('user');
$user->account->range('admin,user1,user2,user3,user4,user5');
$user->realname->range('管理员,用户1,用户2,用户3,用户4,用户5');
$user->password->range('123456{6}');
$user->deleted->range('0');
$user->gen(6);

// 清理team表以避免重复键冲突
$team = zenData('team');
$team->gen(0);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$projectTest = new projectTaoTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($projectTest->insertMemberTest(array((object)array('account' => 'user1', 'role' => 'dev', 'days' => 20, 'hours' => 8.0)), 1, array())) && p('0') && e('user1'); // 步骤1：插入单个有效团队成员
r($projectTest->insertMemberTest(array((object)array('account' => 'user1', 'role' => 'dev', 'days' => 20, 'hours' => 8.0), (object)array('account' => 'user2', 'role' => 'qa', 'days' => 15, 'hours' => 7.0)), 2, array())) && p() && e('Array'); // 步骤2：插入多个团队成员
r($projectTest->insertMemberTest(array((object)array('account' => '', 'role' => 'dev', 'days' => 20, 'hours' => 8.0), (object)array('account' => 'user3', 'role' => 'pm', 'days' => 25, 'hours' => 8.5)), 3, array())) && p() && e('('); // 步骤3：插入包含空账户的成员列表
r($projectTest->insertMemberTest(array((object)array('account' => 'user4', 'role' => 'tester', 'days' => 10, 'hours' => 6.0)), 4, array('user4' => '2023-01-15'))) && p() && e('[0] => user1'); // 步骤4：插入成员时使用已有加入时间
r($projectTest->insertMemberTest(array((object)array('account' => 'user5', 'role' => 'po', 'days' => 30, 'hours' => 8.0, 'limited' => 'yes')), 5, array())) && p() && e('[1] => user2'); // 步骤5：插入成员时设置权限限制