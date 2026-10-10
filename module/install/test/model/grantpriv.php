#!/usr/bin/env php
<?php
/**

title=测试 installModel->grantPriv();
timeout=0
cid=16774

- 没有提交信息时候的报错信息。
 - 第company条的0属性 @『公司名称』不能为空。
 - 第account条的0属性 @『管理员帐号』不能为空。
 - 第password条的0属性 @『管理员密码』不能为空。
- 管理员帐号不合法时的错误信息。第account条的0属性 @『用户名』只能是字母、数字或下划线的组合三位以上。
- 密码少于六位时的错误信息。第password条的0属性 @密码应该符合规则，长度至少为六位。
- 密码比较简单时的错误信息。第password条的0属性 @密码必须6位及以上，且包含大小写字母、数字。
- 正确添加管理员时返回true。 @1
- 正确添加管理员时没有错误信息。 @0
- 再次添加其他管理员账号时返回true。 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

global $tester, $config;
$tester->loadModel('install');

/* 清空用户表，保证新增管理员账号时不与已有数据冲突。*/
zenData('user')->gen(0);

$data = new stdclass();
$data->company  = '';
$data->account  = '';
$data->password = '';
$tester->install->grantPriv($data);
r(dao::getError()) && p('company:0;account:0;password:0') && e('『公司名称』不能为空。,『管理员帐号』不能为空。,『管理员密码』不能为空。'); // 没有提交信息时候的报错信息。

$data->company  = '我的公司';
$data->account  = 'a';
$data->password = 'Admin123';
$tester->install->grantPriv($data);
r(dao::getError()) && p('account:0') && e('『用户名』只能是字母、数字或下划线的组合三位以上。'); // 管理员帐号不合法时的错误信息。

$data->account  = 'admin';
$data->password = '1';
$tester->install->grantPriv($data);
r(dao::getError()) && p('password:0') && e('密码应该符合规则，长度至少为六位。'); // 密码少于六位时的错误信息。

$data->password = '123haha';
$tester->install->grantPriv($data);
r(dao::getError()) && p('password:0') && e('密码必须6位及以上，且包含大小写字母、数字。'); // 密码比较简单时的错误信息。

$data->password = 'Admin123';
r($tester->install->grantPriv($data)) && p() && e('1'); // 正确添加管理员时返回true。
r(dao::getError()) && p() && e('0');                    // 正确添加管理员时没有错误信息。

$data->account = 'user2';
r($tester->install->grantPriv($data)) && p() && e('1'); // 再次添加其他管理员账号时返回true。
