#!/usr/bin/env php
<?php
/**
title=测试 userModel->resetPassword();
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';

zdTable('user')->gen(2);

su('admin');

$userTest = new userTest();

/* 安全配置相关功能在 checkPassword 单元测试用例中有详细测试，此处重置为默认值以减少对当前用例的影响。*/
unset($config->safe->mode);
unset($config->safe->weak);

$password = md5(123456);
$user1    = (object)array('account' => 'user2', 'password' => $password, 'password1' => $password, 'password2' => $password, 'passwordStrength' => 0, 'passwordLength' => 6);
$user2    = (object)array('account' => 'admin', 'password' => $password, 'password1' => '',        'password2' => $password, 'passwordStrength' => 0, 'passwordLength' => 6);
$user3    = (object)array('account' => 'admin', 'password' => $password, 'password1' => $password, 'password2' => $password, 'passwordStrength' => 0, 'passwordLength' => 5);
$user4    = (object)array('account' => 'admin', 'password' => $password, 'password1' => $password, 'password2' => '123456',  'passwordStrength' => 0, 'passwordLength' => 6);
$user5    = (object)array('account' => 'user1', 'password' => $password, 'password1' => $password, 'password2' => $password, 'passwordStrength' => 0, 'passwordLength' => 6);

$result = $userTest->resetPasswordTest($user1);
r($result) && p('result')   && e(0);            // user2 用户不存在，返回 false。
r($result) && p('errors:0') && e('用户不存在'); // user2 用户不存在，提示错误信息。

$result = $userTest->resetPasswordTest($user2);
r($result) && p('result')           && e(0);                    // admin 用户存在，但密码为空，返回 false。
r($result) && p('errors:password1') && e('『密码』不能为空。'); // admin 用户存在，但密码为空，提示错误信息。

$result = $userTest->resetPasswordTest($user3);
r($result) && p('result')           && e(0);                   // admin 用户存在，但密码长度不够，返回 false。
r($result) && p('errors:password1') && e('密码须6位及以上。'); // admin 用户存在，但密码长度不够，提示错误信息。

$result = $userTest->resetPasswordTest($user4);
r($result) && p('result')           && e(0);                    // admin 用户存在，但两次密码不相同，返回 false。
r($result) && p('errors:password1') && e('两次密码应该相同。'); // admin 用户存在，但两次密码不相同，提示错误信息。

$result = $userTest->resetPasswordTest($user5);
r($result) && p('result')           && e(1);    // user1 用户存在，且密码符合要求，返回 true。
r($result) && p('errors:0')         && e('``'); // user1 用户存在，且密码符合要求，不提示错误信息。
r($result) && p('errors:password1') && e('``'); // user1 用户存在，且密码符合要求，不提示错误信息。

$resetUser = $userTest->getByIdTest($user5->account);
r($resetUser->password == $password) && p() && e(1); // user1 用户密码重置成功。
