#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 commonModel::setUserConfig();
timeout=0
cid=15715

- 没有登录的用户，账号和姓名都是guest
 - 属性account @guest
 - 属性realname @guest
- 登录admin账号，账号和姓名都是admin
 - 属性account @admin
 - 属性realname @admin
- 查看设置的公司名称 @易软天创网络科技有限公司
- 查看设置的公司ID @1

*/

global $tester;
$tester->loadModel('common')->setUserConfig();

global $app;
r($app->user) && p('account,realname') && e('guest,guest'); // 没有登录的用户，账号和姓名都是guest

su('admin');
r($app->user) && p('account,realname') && e('admin,admin'); // 登录admin账号，账号和姓名都是admin

r($app->company->name) && p('') && e('易软天创网络科技有限公司'); // 查看设置的公司名称
r($app->company->id)   && p('') && e('1'); // 查看设置的公司ID