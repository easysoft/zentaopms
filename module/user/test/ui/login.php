#!/usr/bin/env php
<?php
include dirname(__FILE__, 2) . '/lib/ui/login.ui.class.php';

/**

title=开源版m=user&f=login测试
timeout=0
cid=1

- 使用正确账号密码登录测试
 - 最终测试状态 @SUCCESS
 - 测试结果 @使用正确账号密码登录测试通过
- 使用错误密码登录测试
 - 最终测试状态 @SUCCESS
 - 测试结果 @使用错误密码登录测试通过
- 不存在的用户登录测试
 - 最终测试状态 @SUCCESS
 - 测试结果 @不存在的用户登录测试通过

*/

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

$tester = new loginTester();

r($tester->verifyLoginCorrectCredentials())             && p('status,message') && e('SUCCESS,使用正确账号密码登录测试通过'); // 使用正确账号密码登录测试
r($tester->verifyLoginIncorrectCredentials())           && p('status,message') && e('SUCCESS,使用错误密码登录测试通过');     // 使用错误密码登录测试
r($tester->verifyLoginIncorrectCredentials('notexist')) && p('status,message') && e('SUCCESS,不存在的用户登录测试通过');     // 不存在的用户登录测试

$tester->closeBrowser();