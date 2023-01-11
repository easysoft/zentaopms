#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

$user = zdTable('user');
$user->gen(100);

/**

title=测试 userModel->failPlus();
cid=1
pid=1

获取user2用户的登录失败次数 >> 4
获取user90用户的登录失败次数 >> 0

*/

$user = new userTest();

$user2  = $user->failPlusTest('user2');
$user90 = $user->failPlusTest('user90');

r($user2)  && p() && e('1'); //获取user2用户的登录失败次数
r($user90) && p() && e('0'); //获取user90用户的登录失败次数
