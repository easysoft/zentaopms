#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

$task = zenData('task');
$task->id->range('1-5');
$task->name->range('1-5')->prefix('任务');
$task->mode->range('multi');
$task->status->range('wait,doing,done,pause,cancel,closed');
$task->assignedTo->range('admin,user1');
$task->openedBy->range('admin,user2,user1');
$task->gen(5);

$taskTeam = zenData('taskteam');
$taskTeam->id->range('1-20');
$taskTeam->task->range('1{2},2{3},3{2},4{3}');
$taskTeam->account->range('admin,user1,admin,user1,user2');
$taskTeam->estimate->range('1{2},2{3},3,4{2},5');
$taskTeam->left->range('1{2},0{3},1{3},0{2}');
$taskTeam->consumed->range('0{11},1{4},0{2},1{3}');
$taskTeam->status->range('wait{11},doing,done,done,done,wait,wait,doing,done,done');
$taskTeam->gen(20);

global $tester;
$tester->loadModel('task');

$taskIdList = array(1, 2, 3, 4, 5);

$tasks        = array();
$oldTasks     = array();
$currentTasks = array();
foreach($taskIdList as $id)
{
    $task = $tester->task->getByID($id);
    $oldTasks[]     = $task;
    $tasks[]        = $task;
    $currentTasks[] = $task;
}

$tasks[0]->status       = 'doing';
$tasks[0]->finishedDate = null;

$tasks[1]->status       = 'done';
$tasks[1]->finishedDate = '2023-04-27';

$currentTasks[0]->assignedTo = 'user1';
$currentTasks[0]->estimate   = 9;
$currentTasks[0]->left       = 0;
$currentTasks[0]->consumed   = 10;

$currentTasks[1]->assignedTo = 'admin';
$currentTasks[1]->estimate   = 8;
$currentTasks[1]->left       = 8;
$currentTasks[1]->consumed   = 0;

$member1 = new stdclass();
$members = array(array('admin', 'user1'), array('user2'));
$autoStatus = array(true, false);
$hasEfforts = array(true, false);

/**

title=taskModel->computeTaskStatus();
timeout=0
cid=18869

*/

$task   = new taskTaoTest();
$task1  = $task->computeTaskStatusTest($currentTasks[0], $oldTasks[0], $tasks[0], $autoStatus[0], $hasEfforts[0], $members[0]);
$task2  = $task->computeTaskStatusTest($currentTasks[0], $oldTasks[1], $tasks[0], $autoStatus[0], $hasEfforts[0], $members[0]);
$task3  = $task->computeTaskStatusTest($currentTasks[0], $oldTasks[1], $tasks[1], $autoStatus[0], $hasEfforts[0], $members[0]);
$task4  = $task->computeTaskStatusTest($currentTasks[0], $oldTasks[1], $tasks[1], $autoStatus[1], $hasEfforts[0], $members[0]);
$task5  = $task->computeTaskStatusTest($currentTasks[0], $oldTasks[1], $tasks[1], $autoStatus[1], $hasEfforts[1], $members[0]);
$task6  = $task->computeTaskStatusTest($currentTasks[0], $oldTasks[1], $tasks[1], $autoStatus[1], $hasEfforts[1], $members[1]);
$task7  = $task->computeTaskStatusTest($currentTasks[1], $oldTasks[0], $tasks[0], $autoStatus[0], $hasEfforts[0], $members[0]);
$task8  = $task->computeTaskStatusTest($currentTasks[1], $oldTasks[1], $tasks[0], $autoStatus[0], $hasEfforts[0], $members[0]);
$task9  = $task->computeTaskStatusTest($currentTasks[1], $oldTasks[1], $tasks[1], $autoStatus[0], $hasEfforts[0], $members[0]);
$task10 = $task->computeTaskStatusTest($currentTasks[1], $oldTasks[1], $tasks[1], $autoStatus[1], $hasEfforts[0], $members[0]);
$task11 = $task->computeTaskStatusTest($currentTasks[1], $oldTasks[1], $tasks[1], $autoStatus[1], $hasEfforts[1], $members[0]);
$task12 = $task->computeTaskStatusTest($currentTasks[1], $oldTasks[1], $tasks[1], $autoStatus[1], $hasEfforts[1], $members[1]);

r($task1)  && p('status,assignedTo,estimate,left,consumed') && e('doing,user1,9,0,10'); // 查询 task1  情况的task信息 currentTask[0] taskID 1 currentTasksestimate 状态自动变更 没有工时消耗 团队成员members[0]
r($task2)  && p('status,assignedTo,estimate,left,consumed') && e('doing,user1,9,0,10'); // 查询 task2  情况的task信息 currentTask[0] taskID 1 currentTasksestimate 状态自动变更 没有工时消耗 团队成员members[0]
r($task3)  && p('status,assignedTo,estimate,left,consumed') && e('doing,user1,9,0,10'); // 查询 task3  情况的task信息 currentTask[0] taskID 1 currentTasksestimate 状态自动变更 没有工时消耗 团队成员members[0]
r($task4)  && p('status,assignedTo,estimate,left,consumed') && e('doing,user1,9,0,10'); // 查询 task4  情况的task信息 currentTask[0] taskID 1 currentTasksestimate 状态非自动变更 没有工时消耗 团队成员members[0]
r($task5)  && p('status,assignedTo,estimate,left,consumed') && e('doing,user1,9,0,10'); // 查询 task5  情况的task信息 currentTask[0] taskID 1 currentTasksestimate 状态非自动变更 有工时消耗 团队成员members[0]
r($task6)  && p('status,assignedTo,estimate,left,consumed') && e('doing,user1,9,0,10'); // 查询 task6  情况的task信息 currentTask[0] taskID 1 currentTasksestimate 状态非自动变更 有工时消耗 团队成员members[1]
r($task7)  && p('status,assignedTo,estimate,left,consumed') && e('done,admin,8,8,0');   // 查询 task7  情况的task信息 currentTask[1] taskID 2 currentTasksestimate 状态自动变更 有工时消耗 团队成员members[0]
r($task8)  && p('status,assignedTo,estimate,left,consumed') && e('done,admin,8,8,0');   // 查询 task8  情况的task信息 currentTask[1] taskID 2 currentTasksestimate 状态自动变更 有工时消耗 团队成员members[0]
r($task9)  && p('status,assignedTo,estimate,left,consumed') && e('done,admin,8,8,0');   // 查询 task9  情况的task信息 currentTask[1] taskID 2 currentTasksestimate 状态自动变更 有工时消耗 团队成员members[0]
r($task10) && p('status,assignedTo,estimate,left,consumed') && e('done,admin,8,8,0');   // 查询 task10 情况的task信息 currentTask[1] taskID 2 currentTasksestimate 状态非自动变更 有工时消耗 团队成员members[0]
r($task11) && p('status,assignedTo,estimate,left,consumed') && e('done,admin,8,8,0');   // 查询 task11 情况的task信息 currentTask[1] taskID 2 currentTasksestimate 状态非自动变更 没有工时消耗 团队成员members[0]
r($task12) && p('status,assignedTo,estimate,left,consumed') && e('done,admin,8,8,0');   // 查询 task12 情况的task信息 currentTask[1] taskID 2 currentTasksestimate 状态非自动变更 没有工时消耗 团队成员members[1]
