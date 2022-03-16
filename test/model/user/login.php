#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';
su('admin');

/**

title=userModel->loginTest();
cid=1
pid=1

*/

$user = new stdclass();
$user->id      = 1;
$user->account = 'admin';

$userClass = new userTest();
$user = $userClass->loginTest($user);

r($rights['rights'])                 && p('action:comment')    && e('1'); //获取用户的权限，返回权限列表
r($rights['rights'])                 && p('testtask:activate') && e('1'); //获取用户的权限，返回权限列表
r($user->authorizeTest('sadf!!@#a')) && p('projects')          && e('');  //获取不存在的用户的权限，返回空
r($user->authorizeTest(''))          && p('')                  && e('0'); //获取空用户名的权限，返回空
