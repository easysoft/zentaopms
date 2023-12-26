#!/usr/bin/env php
<?php
/**
title=测试 userModel->updatePassword();
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';

zdTable('user')->gen(1);

su('admin');

global $app;

$userTest = new userTest();

/* 安全配置相关功能在 checkPassword 单元测试用例中有详细测试，此处重置为默认值以减少对当前用例的影响。*/
unset($config->safe->mode);
unset($config->safe->weak);

$random   = updateSessionRandom();
$password = md5(654321);
$original = md5($app->user->password  . $random);

$user1 = (object)array('originalPassword' => $original, 'password' => $password, 'password1' => '',        'password2' => $password, 'passwordStrength' => 0, 'passwordLength' => 6);
$user2 = (object)array('originalPassword' => $original, 'password' => $password, 'password1' => $password, 'password2' => $password, 'passwordStrength' => 0, 'passwordLength' => 5);
$user3 = (object)array('originalPassword' => $original, 'password' => $password, 'password1' => $password, 'password2' => '',        'passwordStrength' => 0, 'passwordLength' => 6);
$user4 = (object)array('originalPassword' => '',        'password' => $password, 'password1' => $password, 'password2' => $password, 'passwordStrength' => 0, 'passwordLength' => 6);
$user5 = (object)array('originalPassword' => $original, 'password' => $password, 'password1' => $password, 'password2' => $password, 'passwordStrength' => 0, 'passwordLength' => 6);

$result = $userTest->updatePasswordTest($user1);
r($result) && p('result')           && e(0);                    // 密码为空，返回 false。
r($result) && p('errors:password1') && e('『密码』不能为空。'); // 密码为空，提示错误信息。

$result = $userTest->updatePasswordTest($user2);
r($result) && p('result')           && e(0);                   // 密码长度不够，返回 false。
r($result) && p('errors:password1') && e('密码须6位及以上。'); // 密码长度不够，提示错误信息。

$result = $userTest->updatePasswordTest($user3);
r($result) && p('result')           && e(0);                    // 两次密码不相同，返回 false。
r($result) && p('errors:password1') && e('两次密码应该相同。'); // 两次密码不相同，提示错误信息。

$result = $userTest->updatePasswordTest($user4);
r($result) && p('result')                  && e(0);              // 原密码不正确，返回 false。
r($result) && p('errors:originalPassword') && e('原密码不正确'); // 原密码不正确，提示错误信息。

$result = $userTest->updatePasswordTest($user5);
r($result) && p('result')           && e(1);    // 密码符合要求，返回 true。
r($result) && p('errors:0')         && e('~~'); // 密码符合要求，不提示错误信息。
r($result) && p('errors:password1') && e('~~'); // 密码符合要求，不提示错误信息。

$resetUser = $userTest->getByIdTest($app->user->account);
r($resetUser->password == $password) && p() && e(1); // 数据库中的密码修改成功。

r($app->user->password == $password) && p() && e(1); // app 中的密码修改成功。
r($app->user->modifyPassword)        && p() && e(0); // app 中的是否强制修改免密重置为 false。
r($app->user->modifyPasswordReason)  && p() && e(0); // app 中的强制修改密码原因重置为空。

r($_SESSION['user']->password == $password) && p() && e(1); // session 中的密码修改成功。
