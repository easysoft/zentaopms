#!/usr/bin/env php
<?php
/**
title=测试 userModel->checkNeedModifyPassword();
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';

$userTest = new userTest();

global $app, $config;
$config->safe = new stdclass();

$user1 = (object)array('account' => 'user1', 'visits' => 0);
$user2 = (object)array('account' => 'user1', 'visits' => 1);
$user3 = (object)array('account' => 'user1', 'visits' => 1, 'phone' => '', 'mobile' => '', 'birthday' => '', 'password' => '123456');;
$user4 = (object)array('account' => 'user1', 'visits' => 1, 'phone' => '', 'mobile' => '', 'birthday' => '', 'password' => 'Admin123@');;

$config->safe->modifyPasswordFirstLogin = 0;
$config->safe->changeWeak               = 0;
$config->safe->mode                     = 0;

r($userTest->checkNeedModifyPasswordTest($user1, 0)) && p('modifyPassword,modifyPasswordReason') && e('``,``'); // 首次登录修改密码功能关闭，首次登录无需修改密码。

$config->safe->modifyPasswordFirstLogin = 1;
r($userTest->checkNeedModifyPasswordTest($user1, 0)) && p('modifyPassword,modifyPasswordReason') && e('1,modifyPasswordFirstLogin'); // 首次登录修改密码功能开启，首次登录需要修改密码。
r($userTest->checkNeedModifyPasswordTest($user2, 0)) && p('modifyPassword,modifyPasswordReason') && e('``,``');                      // 首次登录修改密码功能开启，非首次登录无需修改密码。

r($userTest->checkNeedModifyPasswordTest($user3, 0)) && p('modifyPassword,modifyPasswordReason') && e('``,``'); // 修改弱密码功能关闭，弱密码无需修改密码。

unset($config->safe->weak); // 重置弱密码设置，防止数据库中的设置影响单元测试。
$config->safe->changeWeak = 1;
r($userTest->checkNeedModifyPasswordTest($user3, 0)) && p('modifyPassword,modifyPasswordReason') && e('1,weak'); // 修改弱密码功能开启，弱密码需要修改密码。
r($userTest->checkNeedModifyPasswordTest($user4, 0)) && p('modifyPassword,modifyPasswordReason') && e('``,``');  // 修改弱密码功能开启，强密码无需修改密码。

$config->safe->mode = 1;
$app->setModuleName('index');
$app->setMethodName('index');
r($userTest->checkNeedModifyPasswordTest($user4, 0)) && p('modifyPassword,modifyPasswordReason') && e('``,``'); // 密码强度检查功能设为 1，密码强度为 0，模块为 index，方法为 index，无需修改密码。

$app->setModuleName('user');
r($userTest->checkNeedModifyPasswordTest($user4, 0)) && p('modifyPassword,modifyPasswordReason') && e('``,``'); // 密码强度检查功能设为 1，密码强度为 0，模块为 user，方法为 index，无需修改密码。

$app->setMethodName('login');
r($userTest->checkNeedModifyPasswordTest($user4, 0)) && p('modifyPassword,modifyPasswordReason') && e('1,passwordStrengthWeak'); // 密码强度检查功能设为 1，密码强度为 0，模块为 user，方法为 login，需要修改密码。
r($userTest->checkNeedModifyPasswordTest($user4, 1)) && p('modifyPassword,modifyPasswordReason') && e('``,``');                  // 密码强度检查功能设为 1，密码强度为 1，模块为 user，方法为 login，无需修改密码。
