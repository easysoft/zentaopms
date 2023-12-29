#!/usr/bin/env php
<?php

/**

title=测试 programplanTao->buildTaskGroup();
cid=0

- 传入空数据 @0
- 检查包含多人任务的admin的分组的任务数。 @4
- 检查包含多人任务的user1的分组的任务数。 @4
- 检查包含多人任务的user2的分组的任务数。 @3
- 检查不包含多人任务的admin的分组的任务数。 @3
- 检查不包含多人任务的user1的分组的任务数。 @3
- 检查不包含多人任务的user2的分组的任务数。 @3

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$taskTeam = zdTable('taskteam');
$taskTeam->task->range('1');
$taskTeam->account->range('account,user1');
$taskTeam->gen(2);
$task     = zdTable('task');
$task->execution->range('1');
$task->assignedTo->range('admin,user1,user2');
$task->mode->range('multi,``{10}');
$task->gen(10);

global $tester;
$tester->loadModel('programplan');

$tasks = $tester->programplan->dao->select('*')->from(TABLE_TASK)->fetchAll('id');

r($tester->programplan->buildTaskGroup(array())) && p() && e('0');  //传入空数据

$taskGroup = $tester->programplan->buildTaskGroup($tasks);
r(count($taskGroup['admin'])) && p() && e('4');   // 检查包含多人任务的admin的分组的任务数。
r(count($taskGroup['user1'])) && p() && e('4');   // 检查包含多人任务的user1的分组的任务数。
r(count($taskGroup['user2'])) && p() && e('3');   // 检查包含多人任务的user2的分组的任务数。

unset($tasks[1]);
$taskGroup = $tester->programplan->buildTaskGroup($tasks);
r(count($taskGroup['admin'])) && p() && e('3');   // 检查不包含多人任务的admin的分组的任务数。
r(count($taskGroup['user1'])) && p() && e('3');   // 检查不包含多人任务的user1的分组的任务数。
r(count($taskGroup['user2'])) && p() && e('3');   // 检查不包含多人任务的user2的分组的任务数。
