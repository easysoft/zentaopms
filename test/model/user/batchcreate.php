#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
$db->switchDB();
su('admin');

/**

title=测试 userModel->batchCreate();
cid=1
pid=1

获取插入的第一个用户的account >> newtestuser1
获取插入的最后一个用户的realname >> 新测试用户3

*/

$user = new userTest();
$normalUser = array();
$normalUser['account']  = array(1 => 'newtestuser1', 2 => 'newtestuser2', 3 => 'newtestuser3');
$normalUser['realname'] = array(1 => '新测试用户1', 2 => '新测试用户2', 3 => '新测试用户3');
$normalUser['visions']  = array(1 => 'rnd', 2 => 'rnd,lite', 3 => 'lite');
$normalUser['role']     = array(1 => 'qa', 2 => 'dev', 3 => 'pm');
$normalUser['email']    = array(1 => 'testasd@163.com', 2 => '', 3 => '11773@qq.com');
$normalUser['password'] = array(1 => 'e10adc3949ba59abbe56e057f20f883e', 2 => 'e10adc3949ba59abbe56e057f20f883e', 3 => 'e10adc3949ba59abbe56e057f20f883e');

r($user->batchCreateUserTest($normalUser)) && p('0:account')  && e('newtestuser1'); //获取插入的第一个用户的account
r($user->batchCreateUserTest($normalUser)) && p('2:realname') && e('新测试用户3');  //获取插入的最后一个用户的realname

$db->restoreDB();