#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

$user = zdTable('user');
$user->id->range('1001-1005');
$user->account->range('1-5')->prefix("account");
$user->realname->range('1-5')->prefix("用户名");
$user->type->range('inside{4},ountside{1}');
$user->avatar->range('1-5')->prefix('/home/z/user/');
$user->deleted->range('0{4}, 1{1}');
$user->gen(5);

/**

title=测试 userModel->getPairs();
cid=1
pid=1

未删除的用户头像的数量 >> 4
所有用户头像的数量 >> 5
在未删除用户中查找account为"account1"的用户的头像路径 >> /home/z/user/1
在未删除用户中查找account为"account2"的用户的头像路径 >> /home/z/user/2
在未删除用户中查找account为"account3"的用户的头像路径 >> /home/z/user/3
在未删除用户中查找account为"account4"的用户的头像路径 >> /home/z/user/4
在所有用户中查找account为"account5"的用户的头像路径 >> /home/z/user/5

*/
$user = new userTest();

r(count($user->getAvatarPairsTest()))      && p()           && e('4');              // 未删除的用户头像的数量
r(count($user->getAvatarPairsTest('all'))) && p()           && e('5');              // 所有用户头像的数量
r($user->getAvatarPairsTest())             && p('account1') && e('/home/z/user/1'); // 在未删除用户中查找account为"account1"的用户的头像路径
r($user->getAvatarPairsTest())             && p('account2') && e('/home/z/user/2'); // 在未删除用户中查找account为"account2"的用户的头像路径
r($user->getAvatarPairsTest())             && p('account3') && e('/home/z/user/3'); // 在未删除用户中查找account为"account3"的用户的头像路径
r($user->getAvatarPairsTest())             && p('account4') && e('/home/z/user/4'); // 在未删除用户中查找account为"account4"的用户的头像路径
r($user->getAvatarPairsTest('all'))        && p('account5') && e('/home/z/user/5'); // 在所有用户中查找account为"account5"的用户的头像路径
