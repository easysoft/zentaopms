#!/usr/bin/env php
<?php
include dirname(__FILE__, 2) . '/lib/ui/delete.ui.class.php';

/**

title=开源版m=user&f=delete测试
timeout=0
cid=1

- 使用错误密码删除用户测试
 - 最终测试状态 @SUCCESS
 - 测试结果 @使用错误密码删除用户测试通过
- 使用正确密码删除用户测试
 - 最终测试状态 @SUCCESS
 - 测试结果 @使用正确密码删除用户测试通过

*/

// 生成随机可删除的测试用户，避免与现有用户冲突
$suffix   = date('ymdHis') . mt_rand(100, 999);
$account  = "uidel_{$suffix}";
$realname = "UI_testuser_{$suffix}";

$user = zenData('user');
$user->id->setNull();  // 使用自增ID
$user->account->range($account);
$user->realname->range($realname);
$user->password->range($config->uitest->defaultPassword)->format('md5');
$user->dept->range('1');
$user->role->range('dev');
$user->gen(1, false);

$tester = new deleteTester($account);

r($tester->verifyDeleteWithWrongPassword())   && p('status,message') && e('SUCCESS,使用错误密码删除用户测试通过'); // 使用错误密码删除用户测试
r($tester->verifyDeleteWithCorrectPassword()) && p('status,message') && e('SUCCESS,使用正确密码删除用户测试通过'); // 使用正确密码删除用户测试

$tester->closeBrowser();