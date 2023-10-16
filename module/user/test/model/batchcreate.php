#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';
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
$normalUser['account']  = array(0 => 'newtestuser1', 1 => 'newtestuser2', 2 => 'newtestuser3');
$normalUser['realname'] = array(0 => '新测试用户1', 1 => '新测试用户2', 2 => '新测试用户3');
$normalUser['visions']  = array(0 => array('rnd'), 1 => array('rnd','lite'), 2 => array('lite'));
$normalUser['role']     = array(0 => 'qa', 1 => 'dev', 2 => 'pm');
$normalUser['email']    = array(0 => 'testasd@163.com', 1 => '', 2 => '11773@qq.com');
$normalUser['password'] = array(0 => 'Zentao123@', 1 => 'Zentao123@', 2 => 'Zentao123@');
$normalUser['dept']     = array(0 => 1, 1 => 1, 2 => 1);
$normalUser['join']     = array(0 => '2023-01-01', 1 => '2023-01-01', 2 => '2023-01-01');
$normalUser['type']     = array(0 => 'inside', 1 => 'inside', 2 => 'inside');
$normalUser['group']    = array(0 => array(), 1 => array(), 2 => array());
$normalUser['gender']   = array(0 => 'm', 1 => 'm', 2 => 'm');
$normalUser['compnay']  = array(0 => '', 1 => '', 2 => '');
$normalUser['commiter'] = array(0 => '', 1 => '', 2 => '');
$normalUser['skype']    = array(0 => '', 1 => '', 2 => '');
$normalUser['qq']       = array(0 => '', 1 => '', 2 => '');
$normalUser['dingding'] = array(0 => '', 1 => '', 2 => '');
$normalUser['weixin']   = array(0 => '', 1 => '', 2 => '');
$normalUser['mobile']   = array(0 => '', 1 => '', 2 => '');
$normalUser['slack']    = array(0 => '', 1 => '', 2 => '');
$normalUser['whatsapp'] = array(0 => '', 1 => '', 2 => '');
$normalUser['phone']    = array(0 => '', 1 => '', 2 => '');
$normalUser['address']  = array(0 => '', 1 => '', 2 => '');
$normalUser['zipcode']  = array(0 => '', 1 => '', 2 => '');
$normalUser['ditto']    = array(0 => '', 1 => '', 2 => '');

r($user->batchCreateUserTest($normalUser)) && p('0:account')  && e('newtestuser1'); //获取插入的第一个用户的account
zdTable('user')->gen(1);
r($user->batchCreateUserTest($normalUser)) && p('2:realname') && e('新测试用户3');  //获取插入的最后一个用户的realname
