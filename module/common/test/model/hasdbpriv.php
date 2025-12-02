#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('user')->gen(10);
zenData('task')->gen(1);
zenData('story')->gen(1);
zenData('bug')->gen(1);

/**

title=测试 commonModel::hasDBPriv();
timeout=0
cid=15679

- 查看admin是否有bug-browse的权限 @1
- 查看admin是否有bug-edit的权限 @1
- 查看admin是否有bug-delete的权限 @1
- 查看admin是否有product-browse的权限 @1
- 查看admin是否有story-edit的权限 @1
- 查看admin是否有story-delete的权限 @1
- 查看admin是否有execution-task的权限 @1
- 查看admin是否有task-edit的权限 @1
- 查看admin是否有task-delete的权限 @1
- 查看user1是否有bug-browse的权限 @1
- 查看user1是否有bug-edit的权限 @1
- 查看user1是否有bug-delete的权限 @1
- 查看user1是否有product-browse的权限 @1
- 查看user1是否有story-edit的权限 @1
- 查看user1是否有story-delete的权限 @1
- 查看user1是否有execution-task的权限 @1
- 查看user1是否有task-edit的权限 @1
- 查看user1是否有task-delete的权限 @1

*/

global $tester;
$task  = $tester->loadModel('task')->fetchById(1);
$story = $tester->loadModel('story')->fetchById(1);
$bug   = $tester->loadModel('bug')->fetchById(1);

$result1 = commonModel::hasDBPriv($bug, 'bug', 'browse');
$result2 = commonModel::hasDBPriv($bug, 'bug', 'edit');
$result3 = commonModel::hasDBPriv($bug, 'bug', 'delete');
$result4 = commonModel::hasDBPriv($story, 'product', 'browse');
$result5 = commonModel::hasDBPriv($story, 'story', 'edit');
$result6 = commonModel::hasDBPriv($story, 'story', 'delete');
$result7 = commonModel::hasDBPriv($task, 'execution', 'task');
$result8 = commonModel::hasDBPriv($task, 'task', 'edit');
$result9 = commonModel::hasDBPriv($task, 'task', 'delete');

r($result1) && p() && e('1'); // 查看admin是否有bug-browse的权限
r($result2) && p() && e('1'); // 查看admin是否有bug-edit的权限
r($result3) && p() && e('1'); // 查看admin是否有bug-delete的权限
r($result4) && p() && e('1'); // 查看admin是否有product-browse的权限
r($result5) && p() && e('1'); // 查看admin是否有story-edit的权限
r($result6) && p() && e('1'); // 查看admin是否有story-delete的权限
r($result7) && p() && e('1'); // 查看admin是否有execution-task的权限
r($result8) && p() && e('1'); // 查看admin是否有task-edit的权限
r($result9) && p() && e('1'); // 查看admin是否有task-delete的权限

su('user1');

$result1 = commonModel::hasDBPriv($bug, 'bug', 'browse');
$result2 = commonModel::hasDBPriv($bug, 'bug', 'edit');
$result3 = commonModel::hasDBPriv($bug, 'bug', 'delete');
$result4 = commonModel::hasDBPriv($story, 'product', 'browse');
$result5 = commonModel::hasDBPriv($story, 'story', 'edit');
$result6 = commonModel::hasDBPriv($story, 'story', 'delete');
$result7 = commonModel::hasDBPriv($task, 'execution', 'task');
$result8 = commonModel::hasDBPriv($task, 'task', 'edit');
$result9 = commonModel::hasDBPriv($task, 'task', 'delete');

r($result1) && p() && e('1'); // 查看user1是否有bug-browse的权限
r($result2) && p() && e('1'); // 查看user1是否有bug-edit的权限
r($result3) && p() && e('1'); // 查看user1是否有bug-delete的权限
r($result4) && p() && e('1'); // 查看user1是否有product-browse的权限
r($result5) && p() && e('1'); // 查看user1是否有story-edit的权限
r($result6) && p() && e('1'); // 查看user1是否有story-delete的权限
r($result7) && p() && e('1'); // 查看user1是否有execution-task的权限
r($result8) && p() && e('1'); // 查看user1是否有task-edit的权限
r($result9) && p() && e('1'); // 查看user1是否有task-delete的权限