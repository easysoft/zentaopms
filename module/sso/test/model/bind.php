#!/usr/bin/env php
<?php

/**

title=ssoModel->bind();
cid=0

- 绑定关联ranzhi账号。
 - 属性account @admin
 - 属性ranzhi @ranzhi1
- 添加关联ranzhi账号。
 - 属性account @user10
 - 属性ranzhi @ranzhi2

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$user = zdTable('user');
$user->password->range('e10adc3949ba59abbe56e057f20f883e');
$user->gen(5);

global $tester;
$ssoModel = $tester->loadModel('sso');

$_POST = array();
$_POST['bindUser']     = 'admin';
$_POST['bindPassword'] = '123456';
$_POST['bindType']     = 'bind';

$ssoData = new stdclass();
$ssoData->account    = 'ranzhi1';
$_SESSION['ssoData'] = $ssoData;

$ssoModel->bind();
r((array)$ssoModel->fetchById(1, 'user')) && p('account,ranzhi') && e('admin,ranzhi1'); //绑定关联ranzhi账号。

$_POST = array();
$_POST['account']          = 'user10';
$_POST['password1']        = '123456';
$_POST['password2']        = '123456';
$_POST['realname']         = 'admin';
$_POST['gender']           = 'm';
$_POST['email']            = 'admin@163.com';
$_POST['role']             = 'dev';
$_POST['bindType']         = 'add';
$_POST['passwordStrength'] = '2';
$_POST['passwordLength']   = strlen($_POST['password1']);

$ssoData = new stdclass();
$ssoData->account    = 'ranzhi2';
$_SESSION['ssoData'] = $ssoData;

$ssoModel->config->safe = new stdclass();
$ssoModel->config->safe->mode = 2;
$ssoModel->bind();
r((array)$ssoModel->fetchById(6, 'user')) && p('account,ranzhi') && e('user10,ranzhi2'); //添加关联ranzhi账号。
