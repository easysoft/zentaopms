#!/usr/bin/env php
<?php
/**

title=测试 installModel->grantPriv();
timeout=0
cid=1

- 没有提交信息时候的报错信息。
 - 第company条的0属性 @公司名称不能为空
 - 第account条的0属性 @管理员帐号不能为空
 - 第password条的0属性 @管理员密码不能为空
- 密码少于六位时的错误信息。第password条的0属性 @密码应该符合规则，长度至少为六位。
- 密码比较简单时的错误信息。第password条的0属性 @密码必须6位及以上，且包含大小写字母、数字。
- 正确添加时是否有错误信息。 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

global $tester, $config;
$tester->loadModel('install');

$tester->install->grantPriv();
r(dao::getError()) && p('company:0;account:0;password:0') && e('公司名称不能为空,管理员帐号不能为空,管理员密码不能为空'); // 没有提交信息时候的报错信息。

$_POST['company']  = '我的公司';
$_POST['account']  = 'admin';
$_POST['password'] = '1';
$tester->install->grantPriv();
r(dao::getError()) && p('password:0') && e('密码应该符合规则，长度至少为六位。'); // 密码少于六位时的错误信息。

$_POST['password'] = '123haha';
$tester->install->grantPriv();
r(dao::getError()) && p('password:0') && e('密码必须6位及以上，且包含大小写字母、数字。'); // 密码比较简单时的错误信息。

$_POST['password'] = 'Admin123';
$tester->install->grantPriv();
r(dao::getError()) && p() && e(0); // 正确添加时是否有错误信息。
