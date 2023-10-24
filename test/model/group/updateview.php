#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
su('admin');

/**

title=测试 groupModel->updateView();
cid=1
pid=1

针对ID为1的权限分组，设置全选权限，查看返回的数据元素数量 >> 2
针对ID为1的权限分组，设置任务创建权限，查看返回的数据元素数量 >> 3
针对ID为1的权限分组，设置任务创建权限，查看是否有创建方法 >> create

*/

global $tester;
$tester->loadModel('group');

$_POST['allchecker'] = 1;
$tester->group->updateView(1);
$group1 = $tester->group->getByID(1);

$_POST['actions']['task']['create'] = 'create';
$tester->group->updateView(1);
$group2 = $tester->group->getByID(1);

r(count($group1->acl)) && p()              && e('2');      // 针对ID为1的权限分组，设置全选权限，查看返回的数据元素数量
r(count($group2->acl)) && p()              && e('3');      // 针对ID为1的权限分组，设置任务创建权限，查看返回的数据元素数量
r($group2->acl)        && p('task:create') && e('create'); // 针对ID为1的权限分组，设置任务创建权限，查看是否有创建方法

