#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('company')->gen(1);

/**

title=测试 commonModel::setUser();
timeout=0
cid=1

- 没有登录的用户，账号和姓名都是guest
 - 属性account @guest
 - 属性realname @guest
- 登录admin账号，账号和姓名都是admin
 - 属性account @admin
 - 属性realname @admin

*/

global $tester, $app;
$tester->loadModel('common')->setUser();

r($app->user) && p('account,realname') && e('guest,guest'); // 没有登录的用户，账号和姓名都是guest

su('admin');
r($app->user) && p('account,realname') && e('admin,admin'); // 登录admin账号，账号和姓名都是admin
