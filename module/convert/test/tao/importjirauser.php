#!/usr/bin/env php
<?php

/**

title=测试 convertTao::importJiraUser();
timeout=0
cid=0

- 步骤4：空数据列表导入 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$user = zenData('user');
$user->account->range('admin,test1,test2,existing{1}');
$user->password->range('123456{4}');
$user->realname->range('管理员,测试用户1,测试用户2,已存在用户{1}');
$user->email->range('admin@test.com,test1@test.com,test2@test.com,existing@test.com{1}');
$user->gender->range('m{4}');
$user->type->range('inside{4}');
$user->deleted->range('0{4}');
$user->gen(4);

$usergroup = zenData('usergroup');
$usergroup->account->range('admin,test1,test2{1}');
$usergroup->group->range('1{3}');
$usergroup->project->range('{3}');
$usergroup->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($convertTest->importJiraUserTest(array(
    (object)array('account' => 'newuser1', 'email' => 'newuser1@test.com', 'realname' => '新用户1', 'join' => '2023-01-01 00:00:00'),
    (object)array('account' => 'newuser2', 'email' => 'newuser2@test.com', 'realname' => '新用户2')
))) && p() && e(1); // 步骤1：正常的Jira用户数据导入

r($convertTest->importJiraUserTest(array(
    (object)array('account' => 'existing', 'email' => 'existing@test.com', 'realname' => '已存在用户'),
    (object)array('account' => 'newuser3', 'email' => 'newuser3@test.com', 'realname' => '新用户3')
))) && p() && e(1); // 步骤2：包含已存在用户的数据导入

r($convertTest->importJiraUserTest(array(
    (object)array('account' => 'atlassian1', 'email' => 'user@connect.atlassian.com', 'realname' => 'Atlassian用户1'),
    (object)array('account' => 'newuser4', 'email' => 'newuser4@test.com', 'realname' => '新用户4')
))) && p() && e(1); // 步骤3：包含Atlassian内部账号的数据导入

r($convertTest->importJiraUserTest(array())) && p() && e(1); // 步骤4：空数据列表导入

r($convertTest->importJiraUserTest(array(
    (object)array('account' => 'invaliduser', 'email' => '', 'realname' => '无邮箱用户'),
    (object)array('account' => 'validuser', 'email' => 'valid@test.com', 'realname' => '有效用户')
))) && p() && e(1); // 步骤5：包含无效邮箱的用户数据导入