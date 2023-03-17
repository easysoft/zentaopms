#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=taskModel->computeHours4Multiple();
cid=1
pid=1

task状态为wait只有老task计算多人工时 >> 1,po82,wait,3,3,3
task状态为wait有新老task计算多人工时 >> 1,po82,wait,3,3,3
task状态为wait有新老task和团队计算多人工时 >> 1,po82,doing,3,3,3
task状态为done只有老task计算多人工时 >> 903,po82,done,3,3,3
task状态为done有新老task计算多人工时 >> 903,po82,done,3,3,3
task状态为done有新老task和团队计算多人工时 >> 903,po82,doing,3,3,3
task状态为pause只有老task计算多人工时 >> 910,,pause,9,12,9
task状态为pause有新老task计算多人工时 >> 910,,pause,9,12,9
task状态为pause只有老task计算多人工时 >> 910,po82,doing,3,3,3
老task不存在的情况有新老task和团队计算多人工时 >> 0
老task不存在的情况有新老task计算多人工时 >> 0
新task不存在的情况有新老task和团队计算多人工时 >> 10001,po82,doing,3,3,3

*/
$task1 = new stdclass();
$task1->id         = 1;
$task1->status     = 'wait';
$task1->assignedTo = '';
$task1->openedBy   = '';

$task2 = new stdclass();
$task2->id         = 1;
$task2->status     = 'wait';
$task2->assignedTo = 'user92';
$task2->openedBy   = '';

$task3 = new stdclass();
$task3->id         = 903;
$task3->status     = 'done';
$task3->assignedTo = '';
$task3->openedBy   = '';

$task4 = new stdclass();
$task4->id         = 903;
$task4->status     = 'done';
$task4->assignedTo = 'po82';
$task4->openedBy   = '';

$task5 = new stdclass();
$task5->id         = 910;
$task5->status     = 'pause';
$task5->assignedTo = '';
$task5->openedBy   = '';

$task6 = new stdclass();
$task6->id         = 910;
$task6->status     = 'pause';
$task6->assignedTo = '';
$task6->openedBy   = '';

$task7 = new stdclass();
$task7->id         = 100001;
$task7->status     = 'done';
$task7->assignedTo = '';
$task7->openedBy   = '';

$task8 = new stdclass();
$task8->id         = 10001;
$task8->status     = 'wait';
$task8->assignedTo = '';
$task8->openedBy   = '';

$user1 = new stdclass();
$user1->account = 'po82';
$user1->estimate = 1;
$user1->consumed = 1;
$user1->left     = 1;

$user2 = new stdclass();
$user2->account = 'user92';
$user2->estimate = 2;
$user2->consumed = 2;
$user2->left     = 2;

$team = array($user1, $user2);

$autoStatusList = array(true, false);

$task = new taskTest();
r($task->computeHours4MultipleTest($task1))                         && p('id,assignedTo,status,estimate,consumed,left') && e('1,po82,wait,3,3,3'); // task状态为wait只有老task计算多人工时
r($task->computeHours4MultipleTest($task1, $task2))                 && p('id,assignedTo,status,estimate,consumed,left') && e('1,po82,wait,3,3,3'); // task状态为wait有新老task计算多人工时
r($task->computeHours4MultipleTest($task1, $task2, $team))          && p('id,assignedTo,status,estimate,consumed,left') && e('1,po82,doing,3,3,3'); // task状态为wait有新老task和团队计算多人工时
r($task->computeHours4MultipleTest($task3))                         && p('id,assignedTo,status,estimate,consumed,left') && e('903,po82,done,3,3,3'); // task状态为done只有老task计算多人工时
r($task->computeHours4MultipleTest($task3, $task4))                 && p('id,assignedTo,status,estimate,consumed,left') && e('903,po82,done,3,3,3'); // task状态为done有新老task计算多人工时
r($task->computeHours4MultipleTest($task3, $task4, $team))          && p('id,assignedTo,status,estimate,consumed,left') && e('903,po82,doing,3,3,3'); // task状态为done有新老task和团队计算多人工时
r($task->computeHours4MultipleTest($task5))                         && p('id,assignedTo,status,estimate,consumed,left') && e('910,,pause,9,12,9'); // task状态为pause只有老task计算多人工时
r($task->computeHours4MultipleTest($task5, $task1, array(), false)) && p('id,assignedTo,status,estimate,consumed,left') && e('910,,pause,9,12,9'); // task状态为pause有新老task计算多人工时
r($task->computeHours4MultipleTest($task5, $task6, $team))          && p('id,assignedTo,status,estimate,consumed,left') && e('910,po82,doing,3,3,3'); // task状态为pause只有老task计算多人工时
r($task->computeHours4MultipleTest($task7))                         && p('id,assignedTo,status,estimate,consumed,left') && e('0'); // 老task不存在的情况有新老task和团队计算多人工时
r($task->computeHours4MultipleTest($task7, $task8))                 && p('id,assignedTo,status,estimate,consumed,left') && e('0'); // 老task不存在的情况有新老task计算多人工时
r($task->computeHours4MultipleTest($task1, $task8, $team)) && p('id,assignedTo,status,estimate,consumed,left') && e('10001,po82,doing,3,3,3'); // 新task不存在的情况有新老task和团队计算多人工时
