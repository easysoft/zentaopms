#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
zdTable('user')->gen(1000);
su('admin');

/**

title=测试 userModel->getCanCreateStoryUsers();
cid=1
pid=1

查找系统中有权限创建、批量创建需求的用户 >> A:admin;T:研发主管48

*/

$user = new userTest();
r($user->getCanCreateStoryUsersTest()) && p('admin;td48') && e('A:admin;T:研发主管48'); //查找系统中有权限创建、批量创建需求的用户