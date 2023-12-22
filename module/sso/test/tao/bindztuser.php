#!/usr/bin/env php
<?php

/**

title=ssoModel->bindZTUser();
cid=0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$user = zdTable('user');
$user->password->range('e10adc3949ba59abbe56e057f20f883e');
$user->gen(5);

global $tester;
$ssoModel = $tester->loadModel('sso');

$data = new stdclass();
$data->bindUser     = 'admin';
$data->bindPassword = '';

$ssoData = new stdclass();
$ssoData->account    = 'ranzhi1';
$_SESSION['ssoData'] = $ssoData;

$ssoModel->bindZTUser($data);
r(dao::getError()) && p('0') && e('密码不能为空'); //没有传入用户密码。

$data->bindPassword = '1234567';
$ssoModel->bindZTUser($data);
r(dao::getError()) && p('0') && e('该用户的登录密码错误，或该用户不存在！'); //传入用户密码错误。

$data->bindPassword = '123456';
$data->bindUser     = 'user10';
$ssoModel->bindZTUser($data);
r(dao::getError()) && p('0') && e('该用户的登录密码错误，或该用户不存在！'); //传入用户账号错误。

$data->bindUser = 'admin';
$ssoModel->bindZTUser($data);
r((array)$ssoModel->fetchById(1, 'user')) && p('account,ranzhi') && e('admin,ranzhi1'); //正确关联ranzhi账号。
