#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

zdTable('user')->gen(1);

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
$normalUser['visions']  = array(1 => array('rnd'), 2 => array('rnd','lite'), 3 => array('lite'));
$normalUser['role']     = array(1 => 'qa', 2 => 'dev', 3 => 'pm');
$normalUser['email']    = array(1 => 'testasd@163.com', 2 => '', 3 => '11773@qq.com');
$normalUser['password'] = array(1 => 'Zentao123@', 2 => 'Zentao123@', 3 => 'Zentao123@');
$normalUser['dept']     = array(1 => '', 2 => '', 3 => '');
$normalUser['join']     = array(1 => '2023-01-01', 2 => '2023-01-01', 3 => '2023-01-01');
$normalUser['type']     = array(1 => 'inside', 2 => 'inside', 3 => 'inside');
$normalUser['group']    = array(1 => array(), 2 => array(), 3 => array());
$normalUser['gender']   = array(1 => 'm', 2 => 'm', 3 => 'm');
$normalUser['compnay']  = array(1 => '', 2 => '', 3 => '');
$normalUser['commiter'] = array(1 => '', 2 => '', 3 => '');
$normalUser['skype']    = array(1 => '', 2 => '', 3 => '');
$normalUser['qq']       = array(1 => '', 2 => '', 3 => '');
$normalUser['dingding'] = array(1 => '', 2 => '', 3 => '');
$normalUser['weixin']   = array(1 => '', 2 => '', 3 => '');
$normalUser['mobile']   = array(1 => '', 2 => '', 3 => '');
$normalUser['slack']    = array(1 => '', 2 => '', 3 => '');
$normalUser['whatsapp'] = array(1 => '', 2 => '', 3 => '');
$normalUser['phone']    = array(1 => '', 2 => '', 3 => '');
$normalUser['address']  = array(1 => '', 2 => '', 3 => '');
$normalUser['zipcode']  = array(1 => '', 2 => '', 3 => '');
$normalUser['ditto']    = array(1 => '', 2 => '', 3 => '');

r($user->batchCreateUserTest($normalUser)) && p('0:account')  && e('newtestuser1'); //获取插入的第一个用户的account
zdTable('user')->gen(1);
r($user->batchCreateUserTest($normalUser)) && p('2:realname') && e('新测试用户3');  //获取插入的最后一个用户的realname
