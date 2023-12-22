#!/usr/bin/env php
<?php

/**

title=ssoModel->addZTUser();
cid=0

- 低密码强度。第password1条的0属性 @密码必须10位及以上，且包含大小写字母、数字、特殊符号。
- 用户名已经存在。 @该用户名已经存在，请更换用户名，或直接绑定到该用户。
- 邮箱格式错误。第email条的0属性 @『邮箱』应当为合法的EMAIL。
- 正确关联ranzhi账号。
 - 属性account @user10
 - 属性ranzhi @ranzhi1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('user')->gen(5);

global $tester;
$ssoModel = $tester->loadModel('sso');
$ssoModel->config->safe = new stdclass();
$ssoModel->config->safe->mode = 2;

$data = new stdclass();
$data->account          = 'admin';
$data->password1        = '123456';
$data->password2        = '123456';
$data->realname         = 'admin';
$data->gender           = 'm';
$data->email            = 'admin';
$data->role             = 'dev';
$data->passwordStrength = '1';
$data->passwordLength   = strlen($data->password1);

$ssoData = new stdclass();
$ssoData->account    = 'ranzhi1';
$_SESSION['ssoData'] = $ssoData;

$ssoModel->addZTUser($data);
r(dao::getError()) && p('password1:0') && e('密码必须10位及以上，且包含大小写字母、数字、特殊符号。'); //低密码强度。

$data->password1        = 'Admin123';
$data->password2        = 'Admin123';
$data->passwordStrength = '2';
$data->passwordLength   = strlen($data->password1);
$ssoModel->addZTUser($data);
r(dao::getError()) && p('0') && e('该用户名已经存在，请更换用户名，或直接绑定到该用户。'); //用户名已经存在。

$data->account = 'user10';
$ssoModel->addZTUser($data);
r(dao::getError()) && p('email:0') && e('『邮箱』应当为合法的EMAIL。'); //邮箱格式错误。

$data->email = 'admin@163.com';
$ssoModel->addZTUser($data);
r((array)$ssoModel->fetchById(6, 'user')) && p('account,ranzhi') && e('user10,ranzhi1'); //正确关联ranzhi账号。
