#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

/**

title=userModel->computeUserViewTest();
cid=1
pid=1

获取admin账户可见的前两个项目ID >> ,1,3,
获取admin账户可见的前两个产品ID >> ,1,2,
获取user10账户可见的产品ID >> 99
用户名传null，则获取当前登录账户的views >> admin

*/

$user = new userTest();

$adminViews = $user->computeUserViewTest('admin', true);
$adminProjects = substr($adminViews->projects, 0, 5);
$adminProducts = substr($adminViews->products, 0, 5);

r($adminProjects)                       && p()           && e(',1,3,');  //获取admin账户可见的前两个项目ID
r($adminProducts)                       && p()           && e(',1,2,');  //获取admin账户可见的前两个产品ID
r($user->computeUserViewTest('user10')) && p('products') && e('99');     //获取user10账户可见的产品ID
r($user->computeUserViewTest('test2'))  && p('projects') && e('');       //获取test2账户可见的项目ID
r($user->computeUserViewTest(null))     && p('account')  && e('admin');  //用户名传null，则获取当前登录账户的views
system("./ztest init");