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

su('admin');
r($app->user) && p('account')  && e('admin'); // 登录admin账号，账号和姓名都是admin
r($app->user) && p('realname') && e('admin'); // 登录admin账号，账号和姓名都是admin

r($app->company->id)   && p('') && e('1'); // 查看设置的公司ID
