#!/usr/bin/env php
<?php

/**

title=ssoModel->getBindUsers();
cid=0

- 查询记录条数 @4
- 获取ranzhi1绑定的用户属性ranzhi1 @user1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

$user = zdTable('user');
$user->ranzhi->range('``,ranzhi1,ranzhi2,ranzhi3,ranzhi4');
$user->gen(5);

$sso = $tester->loadModel('sso');

$users = $sso->getBindUsers();
r(count($users)) && p()          && e('4');     // 查询记录条数
r($users)        && p('ranzhi1') && e('user1'); // 获取ranzhi1绑定的用户
