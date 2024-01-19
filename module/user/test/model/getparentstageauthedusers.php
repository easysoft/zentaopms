#!/usr/bin/env php
<?php

/**

title=测试 userModel->getParentStageAuthedUsers();
cid=0

- 传入阶段ID为0获取有权限的用户 @0
- 传入一个阶段ID为212获取有权限的用户属性dev12 @dev12
- 传入一个阶段ID为1获取有权限的用户 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';
zdTable('user')->gen(10);
su('admin');

$userView = zdTable('userview');
$userView->gen(400);

$user = new userTest();

r($user->getParentStageAuthedUsersTest(0))   && p()         && e('0');      //传入阶段ID为0获取有权限的用户
r($user->getParentStageAuthedUsersTest(212)) && p('dev12')  && e('dev12');  //传入一个阶段ID为212获取有权限的用户
r($user->getParentStageAuthedUsersTest(1))   && p()         && e('0');      //传入一个阶段ID为1获取有权限的用户
