#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 commonModel::setUserConfig();
timeout=0
cid=15715

- 没有登录的用户，账号和姓名都是guest属性account @guest
- 没有登录的用户，账号和姓名都是guest属性realname @guest
- 登录admin账号，账号和姓名都是admin属性account @admin
- 登录admin账号，账号和姓名都是admin属性realname @admin
- 查看设置的公司ID @1

*/

global $tester;
$tester->loadModel('common')->setUserConfig();

global $app;
r($app->user) && p('account')  && e('guest'); // 没有登录的用户，账号和姓名都是guest
r($app->user) && p('realname') && e('guest'); // 没有登录的用户，账号和姓名都是guest

$zd_company = zenData('company');
$zd_company->id->range('1');
$zd_company->name->range('禅道软件');
$zd_company->admins->range('admin');
$zd_company->guest->range('0');
$zd_company->gen(1);
$dbh->exec("UPDATE zt_company SET admins = ',admin,' WHERE id = 1");
global $app;
$app->company = $dbh->query('SELECT * FROM zt_company WHERE id = 1')->fetch(PDO::FETCH_OBJ);

$zd_user = zenData('user');
$zd_user->id->range('1-1');
$zd_user->account->range('admin');
$zd_user->realname->range('admin');
$zd_user->password->range('e10adc3949ba59abbe56e057f20f883e');
$zd_user->visions->range('rnd');
$zd_user->deleted->range('0');
$zd_user->gen(1);

su('admin');
r($app->user) && p('account')  && e('admin'); // 登录admin账号，账号和姓名都是admin
r($app->user) && p('realname') && e('admin'); // 登录admin账号，账号和姓名都是admin

r($app->company->id)   && p('') && e('1'); // 查看设置的公司ID
