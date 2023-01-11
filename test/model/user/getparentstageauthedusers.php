#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

$userView = zdTable('userview');
$userView->gen(400);

/**

title=测试 userModel->getParentStageAuthedUsers();
cid=1
pid=1

传入ID为0的情况获取有权限的用户 >> 0
传入一个阶段ID获取有权限的用户 >> dev12
传入一个项目集ID获取有权限的用户 >> 0

*/
$user = new userTest();

r($user->getParentStageAuthedUsersTest(0))   && p()         && e('0');      //传入阶段ID为0获取有权限的用户
r($user->getParentStageAuthedUsersTest(212)) && p('dev12')  && e('dev12');  //传入一个阶段ID为212获取有权限的用户
r($user->getParentStageAuthedUsersTest(1))   && p()         && e('0');      //传入一个阶段ID为1获取有权限的用户
