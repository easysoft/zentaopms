#!/usr/bin/env php
<?php

/**

title=测试 userModel->unbind();
cid=0

- 获取user7对应的然之用户，重置为空 @success
- 获取不存在的用户的对应的然之用户，返回空 @success

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';
zdTable('user')->gen(10);
su('admin');

$now = date('Y-m-d H:i:s');

$user = zdTable('user');
$user->id->range('1-10');
$user->account->range('admin,user1,user2,user3,user4,user5,user6,user7,user8,user9');
$user->password->range('a0933c1218a4e745bacdcf572b10eba7');
$user->realname->range('1-10')->prefix('用户');
$user->ranzhi->range('admin,user1,user2,user3,user4,user5,user6,user7,user8,user9');
$user->gen(10);

$user = new userTest();
$userLocked   = $user->unbindTest('user7');
$notExistUser = $user->unbindTest('test999');

r($userLocked)   && p('') && e('success'); //获取user7对应的然之用户，重置为空
r($notExistUser) && p('') && e('success');    //获取不存在的用户的对应的然之用户，返回空
