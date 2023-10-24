#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

/**

title=测试 userModel->grantUserView();
cid=1
pid=1

获取admin账户可见的前两个项目的ID >> 12,13
获取admin账户可见的前两个产品的ID >> 11,12
获取test2账户可见的前两个项目的ID >> 12,11
获取user10账户可见的前两个项目的ID >> 19,11

*/

$user = new userTest();

$adminViews  = $user->grantUserViewTest('admin');
$test2Views  = $user->grantUserViewTest('test2');
$user10Views = $user->grantUserViewTest('user10');
$nullViews   = $user->grantUserViewTest(null);

$adminProjects  = substr($adminViews->projects, 0, 5);
$adminProducts  = substr($adminViews->products, 0, 5);
$test2Projects  = substr($test2Views->projects, 0, 5);
$user10Projects = substr($user10Views->projects, 0, 5);

r($adminProjects)  && p()  && e('12,13'); //获取admin账户可见的前两个项目的ID
r($adminProducts)  && p()  && e('11,12'); //获取admin账户可见的前两个产品的ID
r($test2Projects)  && p()  && e('12,11'); //获取test2账户可见的前两个项目的ID
r($user10Projects) && p()  && e('19,11'); //获取user10账户可见的前两个项目的ID
r($nullViews)      && p()  && e('');      //获取null账户可见的项目、产品等
