#!/usr/bin/env php
<?php

/**

title=ssoModel->getBindUser();
cid=0

- 传入空参数 @0
- 查询未绑定用户 @0
- 用户存在绑定的情况属性account @admin

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

$user = zdTable('user');
$user->ranzhi->range('1-20')->prefix('ranzhi');
$user->gen(5);

$sso = $tester->loadModel('sso');

r($sso->getBindUser(''))         && p()          && e('0');     //传入空参数
r($sso->getBindUser('use1'))     && p()          && e('0');     //查询未绑定用户
r($sso->getBindUser('ranzhi1'))  && p('account') && e('admin'); //用户存在绑定的情况
