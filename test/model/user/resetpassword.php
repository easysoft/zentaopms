#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

zdTable('user')->gen(10);

/**

title=测试 userModel->resetPassword();
cid=1
pid=1

重设用户密码，返回重设后的加密后的密码 >> 367ef140036b0feb2f90d70d33255eea
两次密码不相同的情况 >> 两次密码应该相同。<br/>
密码强度小于系统设定 >> 密码必须6位及以上，且包含大小写字母、数字。<br/>
使用常见简单密码，给出错误提示 >> 密码不能使用【123456,password,12345,12345678,qwerty,123456789,1234,1234567,abc123,111111,123123】这些常用弱口令。

*/

$user = new userTest();
$normalUser = array();
$normalUser['account']          = 'admin';
$normalUser['password1']        = '367ef140036b0feb2f90d70d33255eea';
$normalUser['password2']        = '367ef140036b0feb2f90d70d33255eea';
$normalUser['passwordStrength'] = 1;

$weakPassword = $normalUser;
$weakPassword['passwordStrength'] = 0;

$differentPassword = $normalUser;
$differentPassword['password2'] = '!@#!@#asfasf';

$simplePassword = $normalUser;
$simplePassword['password1'] = 'e10adc3949ba59abbe56e057f20f883e';
$simplePassword['password2'] = 'e10adc3949ba59abbe56e057f20f883e';

r($user->resetPasswordTest($normalUser))         && p('password')    && e('367ef140036b0feb2f90d70d33255eea');                 //重设用户密码，返回重设后的加密后的密码
r($user->resetPasswordTest($differentPassword))  && p('password1:0') && e('两次密码应该相同。<br/>');                          //两次密码不相同的情况
r($user->resetPasswordTest($weakPassword))       && p('password1:0') && e('密码必须6位及以上，且包含大小写字母、数字。<br/>'); //密码强度小于系统设定
r($user->resetPasswordTest($simplePassword))     && p('password1')   && e('密码不能使用【123456,password,12345,12345678,qwerty,123456789,1234,1234567,abc123,111111,123123】这些常用弱口令。'); //使用常见简单密码，给出错误提示
