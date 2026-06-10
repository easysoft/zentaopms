#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('space')->gen(20);
zenData('group')->loadYaml('devopsgroup')->gen(10);
zenData('usergroup')->loadYaml('devopsusergroup')->gen(10);
zenData('grouppriv')->loadYaml('devopsgrouppriv')->gen(12);
zenData('user')->gen(5);

/**

title=测试 commonModel::resetDevOpsPriv();
timeout=0
cid=0

- 查看重设权限之前user1的权限数量 @2
- 不传spaceID且无session时早退，权限不变 @2
- 传入有效spaceID后查看user1的权限数量 @4
- 重设权限后，查看user1的权限第repo条的browse属性 @1
- 重设权限后，查看user1的权限第pipeline条的view属性 @1
- 重设权限后，查看user1的权限第artifact条的edit属性 @1

*/

su('user1');
global $tester, $app;
$tester->loadModel('common');

r(count($app->user->rights['rights'])) && p() && e(2); // 查看重设权限之前user1的权限数量

$tester->common->resetDevOpsPriv(0);
r(count($app->user->rights['rights'])) && p() && e(2); // 不传spaceID且无session时早退，权限不变

$tester->common->resetDevOpsPriv(11);

r(count($app->user->rights['rights'])) && p() && e(4); // 查看重设权限之后user1的权限数量

r($app->user->rights['rights']) && p('repo:browse')      && e(1); // 重设权限后，查看user1的权限
r($app->user->rights['rights']) && p('pipeline:view')    && e(1); // 重设权限后，查看user1的权限
r($app->user->rights['rights']) && p('artifact:edit')    && e(1); // 重设权限后，查看user1的权限
