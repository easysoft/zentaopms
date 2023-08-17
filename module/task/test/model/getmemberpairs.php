#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/task.class.php';
zdTable('user')->gen(5);
su('admin');

zdTable('task')->config('task')->gen(9);
zdTable('taskteam')->config('taskteam')->gen(6);
zdTable('team')->config('team')->gen(6);

/**

title=taskModel->getMemberPairs();
timeout=0
cid=1

*/

global $tester;
$taskModel = $tester->loadModel('task');

$normalTask = $taskModel->getByID(1);
$parentTask = $taskModel->getByID(6);
$childTask  = $taskModel->getByID(7);
$linearTask = $taskModel->getByID(8);
$multiTask  = $taskModel->getByID(9);

r($taskModel->getMemberPairs($normalTask)) && p()        && e('0');       // 获取普通任务的团队成员
r($taskModel->getMemberPairs($parentTask)) && p()        && e('0');       // 获取父任务的团队成员
r($taskModel->getMemberPairs($childTask))  && p()        && e('0');       // 获取子任务的团队成员
r($taskModel->getMemberPairs($linearTask)) && p('admin') && e('A:admin'); // 获取串行任务的团队成员
r($taskModel->getMemberPairs($multiTask))  && p('user1') && e('U:用户1'); // 获取并行任务的团队成员
