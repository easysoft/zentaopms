#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

/**

title=测试 userModel->computeUserView();
cid=1
pid=1

获取强制计算前admin账户可见的前两个项目集ID >> ,1,2,
获取强制计算前admin账户可见的前两个产品ID >> ,1,2,
获取强制计算前admin账户可见的前两个项目集ID >> 5,6
获取强制计算前admin账户可见的前两个产品ID >> ,11,12,
获取user10账户可见的产品ID >> 99
获取test2账户可见的项目ID >> ,12
用户名传null，则获取当前登录账户的views >> admin

*/

$user = new userTest();

$adminViews = $user->computeUserViewTest('admin');
$adminPrograms = substr($adminViews->programs, 0, 5);
$adminProducts = substr($adminViews->products, 0, 5);

$computedAdminViews = $user->computeUserViewTest('admin', true);
$computedAdminPrograms = substr($computedAdminViews->programs, 0, 3);
$computedAdminProducts = substr($computedAdminViews->products, 0, 5);

r($adminPrograms)                       && p()           && e(',1,2,');   //获取强制计算前admin账户可见的前两个项目集ID
r($adminProducts)                       && p()           && e(',1,2,');   //获取强制计算前admin账户可见的前两个产品ID
r($computedAdminPrograms)               && p()           && e('5,6');     //获取强制计算前admin账户可见的前两个项目集ID
r($computedAdminProducts)               && p()           && e(',11,12,'); //获取强制计算前admin账户可见的前两个产品ID
r($user->computeUserViewTest('user10')) && p('products') && e('99');      //获取user10账户可见的产品ID
r($user->computeUserViewTest('test2'))  && p('projects') && e(',12');     //获取test2账户可见的项目ID
r($user->computeUserViewTest(null))     && p('account')  && e('admin');   //用户名传null，则获取当前登录账户的views
