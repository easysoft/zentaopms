#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

/**

title=测试 userModel::getParentStageAuthedUsers();
cid=1
pid=1

传入ID为0的情况获取有权限的用户 >> 0
传入一个阶段ID获取有权限的用户 >> admin
传入一个项目集ID获取有权限的用户 >> 0
传入一个看板类型的项目ID获取有权限的用户 >> admin

*/
$user = new userTest();

r($user->getParentStageAuthedUsersTest(0))   && p()         && e('0');      //传入ID为0的情况获取有权限的用户
r($user->getParentStageAuthedUsersTest(131)) && p('admin')  && e('admin');  //传入一个阶段ID获取有权限的用户
r($user->getParentStageAuthedUsersTest(1))   && p()         && e('0');      //传入一个项目集ID获取有权限的用户
r($user->getParentStageAuthedUsersTest(161)) && p('admin') && e('admin');   //传入一个看板类型的项目ID获取有权限的用户