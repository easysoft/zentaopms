#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

$now = date('Y-m-d H:i:s', time() - 20 * 60);
$now = str_replace(array('-', ':'), array(), $now);

$user = zdTable('user');
$user->id->range('1-10');
$user->account->range('admin,user1,user2,user3,user4,user5,user6,user7,user8,user9');
$user->password->range('a0933c1218a4e745bacdcf572b10eba7');
$user->realname->range('1-10')->prefix('用户');
$user->locked->range("$now:2m")->type('timestamp')->format('YY/MM/DD hh:mm:ss');
$user->gen(10);

/**

title=测试 userModel->cleanLocked();
cid=1
pid=1

获取user2账号的锁定状态 >> unlocked
获取user7账号的锁定状态 >> locked

*/

$user = new userTest();
$userLocked   = $user->checkLockedTest('user2');
$unlockedUser = $user->checkLockedTest('user7');

r($userLocked)   && p() && e('unlocked');   //获取user2账号的锁定状态
r($unlockedUser) && p() && e('locked'); //获取user7账号的锁定状态
