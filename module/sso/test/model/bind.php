#!/usr/bin/env php
<?php

/**

title=测试 ssoModel::bind();
timeout=0
cid=18401

- 执行ssoTest模块的bindTest方法
 - 属性account @admin
 - 属性ranzhi @ranzhi1
- 执行ssoTest模块的bindTest方法 @该用户的登录密码错误，或该用户不存在！
- 执行ssoTest模块的bindTest方法 @该用户的登录密码错误，或该用户不存在！
- 执行ssoTest模块的bindTest方法 @密码不能为空
- 执行ssoTest模块的bindTest方法 @该用户名已经存在，请更换用户名，或直接绑定到该用户。
- 执行ssoTest模块的bindTest方法第account条的0属性 @『用户名』只能是字母、数字或下划线的组合三位以上。
- 执行ssoTest模块的bindTest方法 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$user = zenData('user');
$user->account->range('admin,user1,user2,test1,test2');
$user->password->range('e10adc3949ba59abbe56e057f20f883e{5}');
$user->realname->range('管理员,用户1,用户2,测试1,测试2');
$user->email->range('admin@test.com,user1@test.com,user2@test.com,test1@test.com,test2@test.com');
$user->deleted->range('0{5}');
$user->gen(5);

su('admin');

$ssoTest = new ssoModelTest();

// 测试步骤1：正常绑定已存在用户到ranzhi账号
$_POST = array();
$_POST['bindUser']     = 'admin';
$_POST['bindPassword'] = '123456';
$_POST['bindType']     = 'bind';

$ssoData = new stdclass();
$ssoData->account    = 'ranzhi1';
$_SESSION['ssoData'] = $ssoData;

r($ssoTest->bindTest()) && p('account,ranzhi') && e('admin,ranzhi1');

// 测试步骤2：绑定时输入错误密码
$_POST = array();
$_POST['bindUser']     = 'admin';
$_POST['bindPassword'] = 'wrongpassword';
$_POST['bindType']     = 'bind';

$ssoData = new stdclass();
$ssoData->account    = 'ranzhi2';
$_SESSION['ssoData'] = $ssoData;

r($ssoTest->bindTest()) && p('0') && e('该用户的登录密码错误，或该用户不存在！');

// 测试步骤3：绑定时用户名不存在
$_POST = array();
$_POST['bindUser']     = 'nonexistent';
$_POST['bindPassword'] = '123456';
$_POST['bindType']     = 'bind';

$ssoData = new stdclass();
$ssoData->account    = 'ranzhi3';
$_SESSION['ssoData'] = $ssoData;

r($ssoTest->bindTest()) && p('0') && e('该用户的登录密码错误，或该用户不存在！');

// 测试步骤4：绑定缺少密码字段
$_POST = array();
$_POST['bindUser']     = 'admin';
$_POST['bindPassword'] = '';
$_POST['bindType']     = 'bind';

$ssoData = new stdclass();
$ssoData->account    = 'ranzhi4';
$_SESSION['ssoData'] = $ssoData;

r($ssoTest->bindTest()) && p('0') && e('密码不能为空');

// 测试步骤5：添加用户时账号已存在
$_POST = array();
$_POST['account']          = 'admin';
$_POST['password1']        = '123456';
$_POST['password2']        = '123456';
$_POST['realname']         = '管理员2';
$_POST['gender']           = 'm';
$_POST['email']            = 'admin2@test.com';
$_POST['role']             = 'dev';
$_POST['bindType']         = 'add';
$_POST['passwordStrength'] = '2';
$_POST['passwordLength']   = strlen($_POST['password1']);

$ssoData = new stdclass();
$ssoData->account    = 'ranzhi5';
$_SESSION['ssoData'] = $ssoData;

global $tester;
$ssoTest->objectModel->config->safe = new stdclass();
$ssoTest->objectModel->config->safe->mode = 2;

r($ssoTest->bindTest()) && p('0') && e('该用户名已经存在，请更换用户名，或直接绑定到该用户。');

// 测试步骤6：添加用户时账号格式无效
$_POST = array();
$_POST['account']          = 'invalid@account';
$_POST['password1']        = '123456';
$_POST['password2']        = '123456';
$_POST['realname']         = '测试用户';
$_POST['gender']           = 'm';
$_POST['email']            = 'test@test.com';
$_POST['role']             = 'dev';
$_POST['bindType']         = 'add';
$_POST['passwordStrength'] = '2';
$_POST['passwordLength']   = strlen($_POST['password1']);

$ssoData = new stdclass();
$ssoData->account    = 'ranzhi6';
$_SESSION['ssoData'] = $ssoData;

r($ssoTest->bindTest()) && p('account:0') && e('『用户名』只能是字母、数字或下划线的组合三位以上。');

// 测试步骤7：绑定类型为无效值
$_POST = array();
$_POST['bindType'] = 'invalid';

$ssoData = new stdclass();
$ssoData->account    = 'ranzhi7';
$_SESSION['ssoData'] = $ssoData;

r($ssoTest->bindTest()) && p() && e('0');