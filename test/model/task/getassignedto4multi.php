#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=taskModel->getAssignedTo4Multi();
cid=1
pid=1

获取非多人任务的指派给 >> admin
获取非串行多人任务的指派给 >> null
获取串行多人任务的指派给 >> admin
获取排除已经完成的串行多人任务的指派给 >> dev1
获取串行多人任务的下一个指派给 >> admin
获取已经完成的串行多人任务的指派给 >> dev1

*/

$user = new stdclass();
$user->account = 'admin';
$user->status  = 'wait';
$user->order   = '0';
$users[] = $user;

$user = new stdclass();
$user->account = 'dev1';
$user->status  = 'wait';
$user->order   = '1';
$users[] = $user;

$user = new stdclass();
$user->account = 'admin';
$user->status  = 'wait';
$user->order   = '2';
$users[] = $user;

$task = new stdclass();
$task->mode = '';
$task->openedBy   = 'dev1';
$task->assignedTo = 'admin';

$taskTest = new taskTest();
r($taskTest->getAssignedTo4Multi($users, $task)) && p() && e('admin');  //获取非多人任务的指派给

$task->mode = 'multi';
$task->team = $users;
r($taskTest->getAssignedTo4Multi($users, $task)) && p() && e('admin');  //获取非串行多人任务的指派给

$task->mode = 'linear';
r($taskTest->getAssignedTo4Multi($users, $task)) && p() && e('admin');  //获取串行多人任务的指派给

$users[0]->status = 'done';
r($taskTest->getAssignedTo4Multi($users, $task)) && p() && e('dev1');  //获取排除已经完成的串行多人任务的指派给
r($taskTest->getAssignedTo4Multi($users, $task, 'next')) && p() && e('admin');  //获取串行多人任务的下一个指派给

$users[1]->status = 'done';
$users[2]->status = 'done';
r($taskTest->getAssignedTo4Multi($users, $task)) && p() && e('dev1');  //获取已经完成的串行多人任务的指派给
