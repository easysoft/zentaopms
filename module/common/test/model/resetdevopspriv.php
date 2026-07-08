#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('group')->loadYaml('devopsgroup')->gen(10);
zenData('usergroup')->loadYaml('devopsusergroup')->gen(10);
zenData('grouppriv')->loadYaml('devopsgrouppriv')->gen(12);
zenData('user')->gen(5);

/**

title=测试 commonModel::resetDevOpsPriv();
timeout=0
cid=0

- 查看重设权限之前user1的权限数量 @2
- 不传spaceID且无session时早退，权限保持不变 @2
- 显式传入spaceID=0时早退，权限保持不变 @2
- session->devopsSpace为空时早退，权限保持不变 @2
- 重设权限后user1权限数据结构仍可读 @1
- 重设权限后rights为数组 @1

*/

su('user1');
global $tester, $app;
$tester->loadModel('common');

r(count($app->user->rights['rights'])) && p() && e(2); // 查看重设权限之前user1的权限数量

$tester->common->resetDevOpsPriv(0);
r(count($app->user->rights['rights'])) && p() && e(2); // 不传spaceID且无session时早退，权限保持不变

$tester->common->resetDevOpsPriv();
r(count($app->user->rights['rights'])) && p() && e(2); // 显式传入spaceID=0时早退，权限保持不变

unset($_SESSION['devopsSpace']);
$tester->common->resetDevOpsPriv(0);
r(count($app->user->rights['rights'])) && p() && e(2); // session->devopsSpace为空时早退，权限保持不变

r(isset($app->user->rights['rights']) ? 1 : 0) && p() && e(1); // 重设权限后user1权限数据结构仍可读

r(is_array($app->user->rights['rights']) ? 1 : 0) && p() && e(1); // 重设权限后rights为数组
