#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('project')->gen(20);
zenData('group')->loadYaml('projectgroup')->gen(10);
zenData('usergroup')->loadYaml('projectusergroup')->gen(10);
zenData('grouppriv')->loadYaml('projectgrouppriv')->gen(12);
zenData('user')->gen(5);

/**

title=测试 commonModel::resetProjectPriv();
timeout=0
cid=1

- 查看重设权限之前user1的权限数量 @2
- 重设权限后，查看user1的权限第story条的edit属性 @1
- 重设权限后，查看user1的权限第bug条的delete属性 @1

*/

su('user1');
global $tester, $app;
$tester->loadModel('common');

r(count($app->user->rights['rights'])) && p() && e(2); // 查看重设权限之前user1的权限数量

$tester->common->resetProjectPriv(12);

r($app->user->rights['rights']) && p('story:edit') && e(1); // 重设权限后，查看user1的权限
r($app->user->rights['rights']) && p('bug:delete') && e(1); // 重设权限后，查看user1的权限