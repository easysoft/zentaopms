#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';
zdTable('user')->gen(10);
su('admin');

/**

title=测试 userModel->getDataInJSON();
cid=1
pid=1

传入用户1信息，返回公司信息     >> 易软天创网络科技有限公司
传入用户2信息，返回用户名信息   >> account2
传入用户3信息，返回真实姓名信息 >> 用户3
传入用户4信息，隐藏密码信息     >> N/A
传入用户5信息，隐藏是否删除     >> N/A
传入null，返回空                >> N/A

*/
$userTester = new userTest();

$user2 = array();
$user2['account']  = 'account2';
$user2['realname'] = '用户2';
$user2['password'] = 'qweqwe';
$user2['deleted']  = 0;

$user3 = array();
$user3['account']  = 'account3';
$user3['realname'] = '用户3';
$user3['password'] = 'qweqwe';
$user3['deleted']  = 0;

$user4 = array();
$user4['account']  = 'account4';
$user4['realname'] = '用户4';
$user4['password'] = 'qweqwe';
$user4['deleted']  = 1;

$user5 = array();
$user5['account']  = 'account5';
$user5['realname'] = '用户5';
$user5['password'] = 'qweqwe';
$user5['deleted']  = 1;

r($userTester->getDataInJSONTest($user2))    && p('user:account')  && e('account2');                 //传入用户2信息，返回用户名信息
r($userTester->getDataInJSONTest($user3))    && p('user:realname') && e('用户3');                    //传入用户3信息，返回真实姓名信息
r($userTester->getDataInJSONTest($user4))    && p('user:password') && e('~~');                       //传入用户4信息，隐藏密码信息
r($userTester->getDataInJSONTest($user5))    && p('user:deleted')  && e('~~');                       //传入用户5信息，隐藏是否删除
