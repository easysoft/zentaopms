#!/usr/bin/env php
<?php
/**
title=测试 userModel->keepkeepLogin();
cid=1
pid=1
*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('user')->gen(2);
zdTable('company')->gen(1);

su('admin');

$userModel = $tester->loadModel('user');

/* 保持登录状态的 cookie 置空以供比较。*/
$tester->cookie->set('keepLogin', 'off');
$tester->cookie->set('za', '');
$tester->cookie->set('zp', '');

$admin = $userModel->getById('admin');
$user1 = $userModel->getById('user1');

r($tester->cookie->keepkeepLogin) && p() && e('off'); // 未勾选保持登录状态，cookie 中保持登录状态的值是 off。
r($tester->cookie->za)            && p() && e('0');   // 未勾选保持登录状态，cookie 中 za 的值为空。
r($tester->cookie->zp)            && p() && e('0');   // 未勾选保持登录状态，cookie 中 zp 的值为空。

/* admin 用户保持登录状态。*/
$userModel->keepLogin($admin);
$zp = sha1($admin->account . $admin->password . $tester->server->request_time);
r($tester->cookie->keepLogin) && p() && e('on');    // 勾选保持登录状态，cookie 中保持登录状态的值是 on。
r($tester->cookie->za)        && p() && e('admin'); // 勾选保持登录状态，cookie 中 za 的值是 admin。
r($tester->cookie->zp == $zp) && p() && e(1);       // 勾选保持登录状态，cookie 中 zp 的值是 admin 的密码加密后的值。

/* user1 用户保持登录状态。*/
$userModel->keepLogin($user1);
$zp = sha1($user1->account . $user1->password . $tester->server->request_time);
r($tester->cookie->keepLogin) && p() && e('on');    // 勾选保持登录状态，cookie 中保持登录状态的值是 on。
r($tester->cookie->za)        && p() && e('user1'); // 勾选保持登录状态，cookie 中 za 的值是 user1。
r($tester->cookie->zp == $zp) && p() && e(1);       // 勾选保持登录状态，cookie 中 zp 的值是 user1 的密码加密后的值。
