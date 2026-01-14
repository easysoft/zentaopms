#!/usr/bin/env php
<?php

/**

title=测试 ssoModel::createUser();
timeout=0
cid=18403

- 步骤1：用户已存在属性data @该用户名已经存在，请更换用户名，或直接绑定到该用户。
- 步骤2：正常创建属性status @success
- 步骤3：无效性别属性status @fail
- 步骤4：空账号属性status @success
- 步骤5：创建另一个用户属性status @success

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备
zendata('user')->loadYaml('user_createuser', false, 2)->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$ssoTest = new ssoModelTest();

// 5. 测试步骤执行

// 测试步骤1：用户已存在的情况
$existingUser = new stdclass();
$existingUser->account  = 'admin';
$existingUser->realname = '绑定用户1';
$existingUser->email    = 'user@test.com';
$existingUser->gender   = 'm';
$existingUser->ranzhi   = 'bindUser1';
r($ssoTest->createTest($existingUser)) && p('data') && e('该用户名已经存在，请更换用户名，或直接绑定到该用户。'); // 步骤1：用户已存在

// 测试步骤2：正常创建新用户的情况
$newUser = new stdclass();
$newUser->account  = 'newuser1';
$newUser->realname = '新用户1';
$newUser->email    = 'newuser1@test.com';
$newUser->gender   = 'f';
$newUser->ranzhi   = 'ranzhiUser1';
r($ssoTest->createTest($newUser)) && p('status') && e('success'); // 步骤2：正常创建

// 测试步骤3：无效性别字段的情况
$invalidGenderUser = new stdclass();
$invalidGenderUser->account  = 'newuser2';
$invalidGenderUser->realname = '新用户2';
$invalidGenderUser->email    = 'newuser2@test.com';
$invalidGenderUser->gender   = 'invalid';
$invalidGenderUser->ranzhi   = 'ranzhiUser2';
r($ssoTest->createTest($invalidGenderUser)) && p('status') && e('fail'); // 步骤3：无效性别

// 测试步骤4：账号为空的边界值测试（期望成功，因为数据库允许空账号）
$emptyAccountUser = new stdclass();
$emptyAccountUser->account  = '';
$emptyAccountUser->realname = '测试用户';
$emptyAccountUser->email    = 'test@test.com';
$emptyAccountUser->gender   = 'm';
$emptyAccountUser->ranzhi   = 'ranzhiUser3';
r($ssoTest->createTest($emptyAccountUser)) && p('status') && e('success'); // 步骤4：空账号

// 测试步骤5：正常创建另一个用户的情况（验证多用户创建）
$anotherUser = new stdclass();
$anotherUser->account  = 'newuser3';
$anotherUser->realname = '新用户3';
$anotherUser->email    = 'newuser3@test.com';
$anotherUser->gender   = 'f';
$anotherUser->ranzhi   = 'ranzhiUser4';
r($ssoTest->createTest($anotherUser)) && p('status') && e('success'); // 步骤5：创建另一个用户