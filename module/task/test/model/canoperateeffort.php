#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';
su('admin');

/**

title=taskModel->canOperateEffort();
timeout=0
cid=18771

- 判断非多人任务 @1
- 判断已完成的串行多人任务并且不判断日志 @0
- 判断已关闭的串行多人任务并且不判断日志 @0
- 判断已取消的串行多人任务并且不判断日志 @0
- 判断已暂停的串行多人任务并且不判断日志 @0
- 判断当前用户不在多人任务团队成员列表中 @0
- 判断串行多人任务的指派给不是当前用户 @0
- 判断串行多人任务的指派给是当前用户 @1
- 判断当前用户在并行多人任务团队成员列表中 @1
- 判断已完成多人任务并且日志创建者和当前用户一致 @1
- 判断已完成多人任务并且日志创建者和当前用户不一致 @0
- 判断已关闭的串行多人任务并且判断日志 @0
- 判断已取消的串行多人任务并且判断日志 @0
- 判断已暂停的串行多人任务并且判断日志 @0
- 判断进行中的串行多人任务并且当前日志创建者不是当前用户 @0
- 判断进行中的串行多人任务并且当前日志创建者是当前用户 @1

*/

$user = new stdclass();
$user->account = 'dev1';
$user->status  = 'wait';
$user->order   = '1';
$users[] = $user;

$user = new stdclass();
$user->account = 'dev2';
$user->status  = 'wait';
$user->order   = '2';
$users[] = $user;

$task = new stdclass();
$task->mode = '';
$task->openedBy   = 'dev1';
$task->assignedTo = 'admin';

global $app;
$app->user->admin = false;

$taskTest = new taskTest();
r($taskTest->canOperateEffort($task)) && p() && e('1');  //判断非多人任务

$task->mode = 'linear';
$task->team = $users;
$task->status = 'done';
r($taskTest->canOperateEffort($task)) && p() && e('0');  //判断已完成的串行多人任务并且不判断日志
$task->status = 'closed';
r($taskTest->canOperateEffort($task)) && p() && e('0');  //判断已关闭的串行多人任务并且不判断日志
$task->status = 'cancel';
r($taskTest->canOperateEffort($task)) && p() && e('0');  //判断已取消的串行多人任务并且不判断日志
$task->status = 'pause';
r($taskTest->canOperateEffort($task)) && p() && e('0');  //判断已暂停的串行多人任务并且不判断日志

$task->status = 'wait';
r($taskTest->canOperateEffort($task)) && p() && e('0');  //判断当前用户不在多人任务团队成员列表中

$user = new stdclass();
$user->account = 'admin';
$user->status  = 'wait';
$user->order   = '0';
$users[] = $user;

$task->mode       = 'linear';
$task->assignedTo = 'dev1';
$task->team       = $users;
r($taskTest->canOperateEffort($task)) && p() && e('0');  //判断串行多人任务的指派给不是当前用户
$task->assignedTo = 'admin';
r($taskTest->canOperateEffort($task)) && p() && e('1');  //判断串行多人任务的指派给是当前用户

$task->mode = 'multi';
r($taskTest->canOperateEffort($task)) && p() && e('1');  //判断当前用户在并行多人任务团队成员列表中

$effort = new stdclass();
$effort->account = 'admin';

$task->mode   = 'linear';
$task->status = 'done';
r($taskTest->canOperateEffort($task, $effort)) && p() && e('1');  //判断已完成多人任务并且日志创建者和当前用户一致

$effort->account = 'dev1';
r($taskTest->canOperateEffort($task, $effort)) && p() && e('0');  //判断已完成多人任务并且日志创建者和当前用户不一致

$task->mode   = 'linear';
$task->status = 'closed';
r($taskTest->canOperateEffort($task, $effort)) && p() && e('0');  //判断已关闭的串行多人任务并且判断日志
$task->status = 'cancel';
r($taskTest->canOperateEffort($task, $effort)) && p() && e('0');  //判断已取消的串行多人任务并且判断日志
$task->status = 'pause';
r($taskTest->canOperateEffort($task, $effort)) && p() && e('0');  //判断已暂停的串行多人任务并且判断日志

$task->status = 'doing';
r($taskTest->canOperateEffort($task, $effort)) && p() && e('0');  //判断进行中的串行多人任务并且当前日志创建者不是当前用户
$effort->account = 'admin';
r($taskTest->canOperateEffort($task, $effort)) && p() && e('1');  //判断进行中的串行多人任务并且当前日志创建者是当前用户
