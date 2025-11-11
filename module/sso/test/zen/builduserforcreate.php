#!/usr/bin/env php
<?php

/**

title=测试 ssoZen::buildUserForCreate();
timeout=0
cid=0

- 步骤1:测试正常创建用户对象account字段正确属性account @testuser001
- 步骤2:测试用户对象realname字段正确属性realname @Test User
- 步骤3:测试用户对象email字段正确属性email @test001
- 步骤4:测试用户对象gender字段正确属性gender @m
- 步骤5:测试ranzhi字段值与post的account值相同属性ranzhi @testuser001
- 步骤6:测试ranzhi字段根据不同account正确设置属性ranzhi @user123
- 步骤7:测试空account时ranzhi字段为空属性ranzhi @~~

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. 用户登录(选择合适角色)
su('admin');

// 3. 创建测试实例(变量名与模块名一致)
$ssoTest = new ssoZenTest();

// 4. 强制要求:必须包含至少5个测试步骤
$_POST['account']  = 'testuser001';
$_POST['realname'] = 'Test User';
$_POST['email']    = 'test001';
$_POST['gender']   = 'm';
r($ssoTest->buildUserForCreateTest()) && p('account') && e('testuser001'); // 步骤1:测试正常创建用户对象account字段正确
r($ssoTest->buildUserForCreateTest()) && p('realname') && e('Test User'); // 步骤2:测试用户对象realname字段正确
r($ssoTest->buildUserForCreateTest()) && p('email') && e('test001'); // 步骤3:测试用户对象email字段正确
r($ssoTest->buildUserForCreateTest()) && p('gender') && e('m'); // 步骤4:测试用户对象gender字段正确
r($ssoTest->buildUserForCreateTest()) && p('ranzhi') && e('testuser001'); // 步骤5:测试ranzhi字段值与post的account值相同
$_POST['account'] = 'user123';
r($ssoTest->buildUserForCreateTest()) && p('ranzhi') && e('user123'); // 步骤6:测试ranzhi字段根据不同account正确设置
$_POST['account'] = '';
r($ssoTest->buildUserForCreateTest()) && p('ranzhi') && e('~~'); // 步骤7:测试空account时ranzhi字段为空