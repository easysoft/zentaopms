#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

zdTable('user')->gen(10);

/**

title=测试 userModel->updatePassword();
cid=1
pid=1

编辑用户密码，返回编辑后的密码 >> dcf859ce8dd8f998bdfe4ae6c22c329e
两次密码不相同的情况 >> 两次密码应该相同。<br/>
密码小于设定强度的情况 >> 密码必须6位及以上，且包含大小写字母、数字。<br/>

*/

$user = new userTest();
$normalUser = array();
$normalUser['originalPassword'] = 'bac0bbaaf7192f219bebd5387e88c5d7';
$normalUser['password1']        = 'dcf859ce8dd8f998bdfe4ae6c22c329e';
$normalUser['password2']        = 'dcf859ce8dd8f998bdfe4ae6c22c329e';
$normalUser['passwordStrength'] = 1;

$weakPassword = $normalUser;
$weakPassword['passwordStrength'] = 0;

$differentPassword = $normalUser;
$differentPassword['password2'] = 'asdasfasf!@#!@#asfasf';

r($user->updatePasswordTest(10, $normalUser))         && p('password')    && e('dcf859ce8dd8f998bdfe4ae6c22c329e');                  //编辑用户密码，返回编辑后的密码
r($user->updatePasswordTest(10, $differentPassword))  && p('password1:0') && e('两次密码应该相同。<br/>');                           //两次密码不相同的情况
r($user->updatePasswordTest(10, $weakPassword))       && p('password1:0') && e('密码必须6位及以上，且包含大小写字母、数字。<br/>');  //密码小于设定强度的情况
