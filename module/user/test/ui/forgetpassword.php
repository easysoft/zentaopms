#!/usr/bin/env php
<?php
include dirname(__FILE__, 2) . '/lib/ui/forgetpassword.ui.class.php';

/**

title=开源版m=user&f=forgetpassword测试
timeout=0
cid=1

- 登录页面忘记密码链接跳转测试
 - 最终测试状态 @SUCCESS
 - 测试结果 @登录页面忘记密码链接跳转测试成功
- 邮箱重置页发送重置邮件测试
 - 最终测试状态 @SUCCESS
 - 测试结果 @邮箱重置页面发送重置邮件测试成功

*/

// 确保admin存在，忽略database duplicate entry报错
ob_start();
$user = zenData('user');
$user->id->range('1');
$user->account->range('admin');
$user->realname->range('管理员');
$user->password->range($config->uitest->defaultPassword)->format('md5');
$user->role->range('admin');
$user->gen(1, false);
$__out = ob_get_clean();
if($__out && strpos($__out, 'Duplicate entry') === false) echo $__out;

ob_start();
$user = zenData('user');
$user->id->range('1001-1002');
$user->account->range('user1,user2');
$user->realname->range('用户1,用户2');
$user->password->range($config->uitest->defaultPassword)->format('md5');
$user->gen(2, false);
$__out = ob_get_clean();
if($__out && strpos($__out, 'Duplicate entry') === false) echo $__out;

$tester = new forgetpasswordTester();
r($tester->verifyForgetPasswordLinkOnLogin()) && p('status,message') && e('SUCCESS,登录页面忘记密码链接跳转测试成功'); // 登录页面忘记密码链接跳转测试
r($tester->verifyForgetPasswordViaMail())     && p('status,message') && e('SUCCESS,邮箱重置页面发送重置邮件测试成功'); // 邮箱重置页发送重置邮件测试
$tester->closeBrowser();