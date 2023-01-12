#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

zdTable('user')->gen(15);

/**

title=测试 userModel->batchEdit();
cid=1
pid=1

获取编辑后的第一个用户的account >> newtestuser1
获取编辑的最后一个用户的真实姓名 >> 新测试用户3

*/

$user = new userTest();
$normalUser = array();
$normalUser['account']  = array(8 => 'newtestuser1', 9 => 'newtestuser2', 10 => 'newtestuser3');
$normalUser['realname'] = array(8 => '新测试用户1', 9 => '新测试用户2', 10 => '新测试用户3');
$normalUser['commiter'] = array(8 => 'user1', 9 => 'user2', 10 => 'user3');
$normalUser['email']    = array(8 => 'testasd@163.com', 9 => '', 10 => '11773@qq.com');
$normalUser['type']     = array(8 => 'inside', 9 => 'inside', 10 => 'outside');
$normalUser['join']     = array(8 => '0000-00-00', 9 => '0000-00-00', 10 => '0000-00-00');
$normalUser['skype']    = array(8 => '', 9 => '', 10 => '');
$normalUser['qq']       = array(8 => '', 9 => '', 10 => '');
$normalUser['dingding'] = array(8 => '', 9 => '', 10 => '');
$normalUser['weixin']   = array(8 => '', 9 => '', 10 => '');
$normalUser['mobile']   = array(8 => '', 9 => '', 10 => '');
$normalUser['slack']    = array(8 => '', 9 => '', 10 => '');
$normalUser['whatsapp'] = array(8 => '', 9 => '', 10 => '');
$normalUser['phone']    = array(8 => '', 9 => '', 10 => '');
$normalUser['address']  = array(8 => '', 9 => '', 10 => '');
$normalUser['zipcode']  = array(8 => '', 9 => '', 10 => '');
$normalUser['visions']  = array(8 => array('rnd'), 9 => array('rnd', 'lite'), 10 => array('lite'));
$normalUser['dept']     = array(8 => '1', 9 => '1', 10 => '1');
$normalUser['role']     = array(8 => 'qa', 9 => 'dev', 10 => 'pm');
$normalUser['password'] = array(8 => 'e10adc3949ba59abbe56e057f20f883e', 9 => 'e10adc3949ba59abbe56e057f20f883e', 10 => 'e10adc3949ba59abbe56e057f20f883e');

r($user->batchEditUserTest($normalUser)) && p('8:account')   && e('newtestuser1'); //获取编辑后的第一个用户的account
r($user->batchEditUserTest($normalUser)) && p('10:realname') && e('新测试用户3');  //获取编辑的最后一个用户的真实姓名
