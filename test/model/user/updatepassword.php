#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
$db->switchDB();
su('admin');

/**

title=测试 userModel->updatePassword();
cid=1
pid=1

编辑用户密码，返回编辑后的密码 >> dcf859ce8dd8f998bdfe4ae6c22c329e
两次密码不相同的情况 >> 两次密码应该相同。
密码小于设定强度的情况 >> 您的密码强度小于系统设定。

*/

$user = new userTest();
$normalUser = array();
$normalUser['originalPassword'] = 'e79f8fb9726857b212401e42e5b7e18b';
$normalUser['password1']        = 'dcf859ce8dd8f998bdfe4ae6c22c329e';
$normalUser['password2']        = 'dcf859ce8dd8f998bdfe4ae6c22c329e';
$normalUser['passwordStrength'] = 1;

$weakPassword = $normalUser;
$weakPassword['passwordStrength'] = 0;

$differentPassword = $normalUser;
$differentPassword['password2'] = 'asdasfasf!@#!@#asfasf';

r($user->updatePasswordTest(1000, $normalUser))         && p('password')    && e('dcf859ce8dd8f998bdfe4ae6c22c329e'); //编辑用户密码，返回编辑后的密码
r($user->updatePasswordTest(1000, $differentPassword))  && p('password:0')  && e('两次密码应该相同。');               //两次密码不相同的情况
r($user->updatePasswordTest(1000, $weakPassword))       && p('password1:0') && e('您的密码强度小于系统设定。');       //密码小于设定强度的情况

$db->restoreDB();