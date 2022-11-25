#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

/**

title=测试 userModel->getDataInJSON();
cid=1
pid=1

传入admin用户信息，返回公司信息 >> 易软天创网络科技有限公司
传入admin用户信息，返回用户名信息 >> admin
传入null用户信息，返回公司信息 >> 易软天创网络科技有限公司

*/
$user = new userTest();

$admin = new stdclass();
$admin->id       = 1;
$admin->account  = 'admin';
$admin->password = '123456';
$admin->deleted  = '1';

r($user->getDataInJSONTest($admin)) && p('user:company')  && e('易软天创网络科技有限公司'); //传入admin用户信息，返回公司信息
r($user->getDataInJSONTest($admin)) && p('user:account')  && e('admin');                    //传入admin用户信息，返回用户名信息
r($user->getDataInJSONTest($admin)) && p('user:password') && e('');                         //传入admin用户信息，返回密码信息
r($user->getDataInJSONTest(null))   && p('user:company')  && e('易软天创网络科技有限公司'); //传入null用户信息，返回公司信息