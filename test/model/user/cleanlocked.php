#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

$user = zdTable('user');
$user->id->range('1-10');
$user->account->range('admin,user1,user2,user3,user4,user5,user6,user7,user8,user9');
$user->password->range('a0933c1218a4e745bacdcf572b10eba7');
$user->realname->range('1-10')->prefix('用户');
$user->locked->range("20230110 143412")->type('timestamp')->format('YY/MM/DD hh:mm:ss');
$user->gen(10);

/**

title=测试 userModel->cleanLocked();
cid=1
pid=1

获取user8的锁定时间，重置为空 >> success
获取不存在的用户的锁定时间，返回空 >> fail

*/

$user = new userTest();
$userLocked   = $user->cleanLockedTest('user8');
$notExistUser = $user->cleanLockedTest('test999');

r($userLocked)   && p('') && e('success'); //获取user8的锁定时间，重置为空
r($notExistUser) && p('') && e('fail');    //获取不存在的用户的锁定时间，返回空
