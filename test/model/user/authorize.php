#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/user.class.php';

su('admin');

$user = zdTable('user');
$user->gen(300);

$userGroup = zdTable('usergroup');
$userGroup->gen(100);

/**

title=测试 userModel->authorize();
cid=1
pid=1

获取用户的权限，返回权限列表，是否有产品首页权限 >> 1
获取游客的权限，返回权限列表，是否有任务详情的权限 >> 1
获取不存在的用户的可访问项目，返回空 >> 0
获取空的用户的权限，返回空 >> 0

*/

$user = new userTest();

$normalUser    = $user->authorizeTest('test10');
$guest         = $user->authorizeTest('guest');
$notExistsUser = $user->authorizeTest('sadf!!@#a');

r($normalUser['rights'])      && p('product:index') && e('1'); //获取用户的权限，返回权限列表，是否有产品首页权限
r($guest['rights'])           && p('task:view')     && e('1'); //获取游客的权限，返回权限列表，是否有任务详情的权限
r($notExistsUser['projects']) && p('')              && e('0'); //获取不存在的用户的可访问项目，返回空
r($user->authorizeTest(''))   && p('')              && e('0'); //获取空的用户的权限，返回空
