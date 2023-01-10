#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

zdTable('user')->gen(30);

/**

title=测试 userModel->getPairs();
cid=1
pid=1

获取用户名和职位Key的键值对 >> qa
获取用户名和职位Value的键值对 >> 测试
从指定accounts中获取到的键值对数量 => 2
从指定accounts中获取到的键值对数量 => 2

*/

$accounts = array('!asd12d', '中文用户名', 'user10', 'user20', 'cccfff');
$user  = new userTest();

r($user->getUserRolesTest($accounts, true))         && p('user20') && e('qa');   //获取用户名和职位Key的键值对
r($user->getUserRolesTest($accounts, false))        && p('user20') && e('测试'); //获取用户名和职位Value的键值对
r(count($user->getUserRolesTest($accounts, true)))  && p()        && e(2);       //从指定accounts中获取到的Key键值对数量
r(count($user->getUserRolesTest($accounts, false))) && p()        && e(2);       //从指定accounts中获取到的Value键值对数量
