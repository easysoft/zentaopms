#!/usr/bin/env php
<?php

/**

title=开源版m=user&f=resetpassword测试
timeout=0
cid=1

- 重置密码过期提示测试
 - 最终测试状态 @SUCCESS
 - 测试结果 @重置密码过期提示正确
- 重置密码表单元素展示与交互测试
 - 最终测试状态 @SUCCESS
 - 测试结果 @重置密码表单元素展示与交互正确

*/
include dirname(__FILE__, 2) . '/lib/ui/resetpassword.ui.class.php';

$user = zenData('user');
$user->id->range('2-3');
$user->account->range('user1,user2');
$user->realname->range('用户1,用户2');
$user->password->range($config->uitest->defaultPassword)->format('md5');
$user->role->range('dev,qa');
$user->gender->range('f,m');
$user->gen(2);

$tester = new resetpasswordTester();

r($tester->verifyExpiredMessage())    && p('status,message') && e('SUCCESS,重置密码过期提示正确');           // 重置密码过期提示测试
r($tester->verifyResetPasswordForm()) && p('status,message') && e('SUCCESS,重置密码表单元素展示与交互正确'); // 重置密码表单元素展示与交互测试

$tester->closeBrowser();