#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

/**

title=测试 userModel->getPairs();
cid=1
pid=1

所有用户头像的数量 >> 999
查找account为"user99"的用户的头像路径 >> /home/z/user/15.png

*/
$user = new userTest();

r(count($user->getAvatarPairsTest())) && p()         && e('999');                 //所有用户头像的数量
r($user->getAvatarPairsTest())        && p('user99') && e('/home/z/user/15.png'); //查找account为"user99"的用户的头像路径