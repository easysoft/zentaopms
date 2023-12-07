#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';
zdTable('user')->gen(500);
su('admin');

/**

title=测试 userModel->setCurrentUserFirst();
cid=1
pid=1

获取当前用户信息 >> admin,qa

*/

$user = new userTest();
$admin = array(
    'account'  => 'admin',
    'role'     => 'qa',
    'realname' => 'admin'
);
r($user->setCurrentUserFirstTest(array('admin'=>$admin))) && p('admin:account,role') && e('admin,qa'); //获取当前用户信息
