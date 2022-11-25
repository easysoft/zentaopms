#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

/**

title=测试 userModel->getWeakUsers();
cid=1
pid=1

获取密码较弱的用户 >> 0

*/
$user = new userTest();
a($user->getWeakUsersTest());die;

r($user->getWeakUsersTest()) && p('') && e('0'); //获取密码较弱的用户