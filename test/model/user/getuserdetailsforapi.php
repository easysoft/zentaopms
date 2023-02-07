#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
zdTable('user')->gen(500);
su('admin');

/**

title=测试 userModel::getUserDetailsForAPI();
cid=1
pid=1

获取admin用户的url >> getuserdetailsforapi
获取test2用户的真实姓名 >> 测试2
获取user10的头像信息 >> http:///home/z/tmp/10.png

*/
$user = new userTest();
$userList = array('admin', 'test2', 'user10', '');
$users    = $user->getUserDetailsForAPITest($userList);

r($users) && p('admin:url')      && e('getuserdetailsforapi');          //获取admin用户的url
r($users) && p('test2:realname') && e('测试2');                     //获取test2用户的真实姓名
r($users) && p('user10:avatar')  && e('http:///home/z/tmp/10.png'); //获取user10的头像信息