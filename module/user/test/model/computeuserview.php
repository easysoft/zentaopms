#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/user.class.php';
su('admin');

$program = zdTable('project')->config('program');
$program->acl->range('private{5},open{5}');
$program->openedBy->range('admin{3},test2{2}');
$program->gen(10);

$product = zdTable('product')->config('product');
$product->acl->range('private{5},open{5}');
$product->createdBy->range('admin{3},user10{2}');
$product->gen(10);

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

r($adminPrograms)                       && p()                && e('1,2,3');   //获取强制计算前admin账户可见的前两个项目集ID
r($adminProducts)                       && p()                && e('1,2,3');   //获取强制计算前admin账户可见的前两个产品ID
r($user->computeUserViewTest('user10')) && p('products', '-') && e('4,5'); //获取user10账户可见的产品ID
r($user->computeUserViewTest('test2'))  && p('programs', '-') && e('4,5');     //获取test2账户可见的项目ID
r($user->computeUserViewTest(null))     && p('account')       && e('admin');   //用户名传null，则获取当前登录账户的views
