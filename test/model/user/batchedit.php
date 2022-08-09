#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
$db->switchDB();
su('admin');

/**

title=测试 userModel->batchEdit();
cid=1
pid=1

获取编辑后的第一个用户的account >> newtestuser1
获取编辑的最后一个用户的真实姓名 >> 新测试用户3

*/

$user = new userTest();
$normalUser = array();
$normalUser['account']  = array(998 => 'newtestuser1', 999 => 'newtestuser2', 1000 => 'newtestuser3');
$normalUser['realname'] = array(998 => '新测试用户1', 999 => '新测试用户2', 1000 => '新测试用户3');
$normalUser['visions']  = array(998 => 'rnd', 999 => 'rnd,lite', 1000 => 'lite');
$normalUser['role']     = array(998 => 'qa', 999 => 'dev', 1000 => 'pm');
$normalUser['email']    = array(998 => 'testasd@163.com', 999 => '', 1000 => '11773@qq.com');
$normalUser['password'] = array(998 => 'e10adc3949ba59abbe56e057f20f883e', 999 => 'e10adc3949ba59abbe56e057f20f883e', 1000 => 'e10adc3949ba59abbe56e057f20f883e');

r($user->batchEditUserTest($normalUser)) && p('998:account')   && e('newtestuser1'); //获取编辑后的第一个用户的account
r($user->batchEditUserTest($normalUser)) && p('1000:realname') && e('新测试用户3');  //获取编辑的最后一个用户的真实姓名

$db->restoreDB();