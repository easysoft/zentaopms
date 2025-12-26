#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';

$task = zenData('task');
$task->id->range('1-5');
$task->name->range('1-5')->prefix('任务');
$task->mode->range('multi');
$task->status->range('wait,doing,done,pause,cancel,closed');
$task->assignedTo->range('admin,user1');
$task->openedBy->range('admin,user2,user1');
$task->estimate->range('0');
$task->consumed->range('0');
$task->left->range('0');
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

zenData('story')->gen(5);

global $tester;
$tester->loadModel('task');

$taskIdList = array(1, 2, 3, 4, 5);
$tasks        = array();
$oldTasks     = array();
foreach($taskIdList as $id)
{
    $task = $tester->task->getByID($id);
    $oldTasks[]     = $task;
    $tasks[]        = $task;
}

$tasks[0]->status       = 'doing';
$tasks[0]->finishedDate = null;

$tasks[1]->status       = 'done';
$tasks[1]->finishedDate = '2023-04-27';

$members1 = new stdclass();
$members1->account  = 'admin';
$members1->estimate = 1;
$members1->left     = 1;

$members2 = new stdclass();
$members2->account  = 'user1';
$members2->estimate = 2;
$members2->left     = 2;

$members3 = new stdclass();
$members3->account  = 'user3';
$members3->estimate = 3;
$members3->left     = 3;

$members = array(array($members1, $members2), array($members3));

/**

title=taskModel->computeMultipleHours();
timeout=0
cid=18777

- taskID 1 只有老task计算多人工时
 - 属性id @1
 - 属性assignedTo @admin
 - 属性status @doing
 - 属性estimate @5.00
 - 属性left @4.00
- taskID 2 只有老task计算多人工时
 - 属性id @2
 - 属性assignedTo @user1
 - 属性status @done
 - 属性estimate @13.00
 - 属性left @0.00
- taskID 4 只有老task计算多人工时
 - 属性id @3
 - 属性assignedTo @admin
 - 属性status @done
 - 属性estimate @15.00
 - 属性left @4.00
- taskID 4 只有老task计算多人工时
 - 属性id @4
 - 属性assignedTo @user1
 - 属性status @pause
 - 属性estimate @17.00
 - 属性left @2.00
- taskID 5 只有老task计算多人工时
 - 属性id @5
 - 属性assignedTo @admin
 - 属性status @cancel
 - 属性estimate @0.00
 - 属性left @0.00
- taskID 1 有传入task计算多人工时
 - 属性id @1
 - 属性assignedTo @admin
 - 属性status @doing
 - 属性estimate @5.00
 - 属性left @4.00
- taskID 2 有传入task计算多人工时
 - 属性id @2
 - 属性assignedTo @user1
 - 属性status @done
 - 属性estimate @13.00
  - 属性left @0.00
- taskID 1 有传入task 传入members计算多人工时
 - 属性id @1
 - 属性assignedTo @~~
 - 属性status @doing
 - 属性estimate @3
 - 属性left @3
- taskID 2 有传入task 传入members计算多人工时
 - 属性id @2
 - 属性assignedTo @user1
 - 属性status @doing
 - 属性estimate @3
 - 属性left @3
- taskID 1 有传入task 传入members 不自动更新状态计算多人工时
 - 属性id @1
 - 属性assignedTo @~~
 - 属性status @doing
 - 属性estimate @3
 - 属性left @3
- taskID 2 有传入task 传入members 不自动更新状态计算多人工时
 - 属性id @2
 - 属性assignedTo @user1
 - 属性status @done
 - 属性estimate @3
 - 属性left @3

*/

$task = new taskTest();
r($task->computeMultipleHoursTest($oldTasks[0]))                                && p('id,assignedTo,status,estimate,left') && e('1,admin,doing,5.00,4.00');  // taskID 1 只有老task计算多人工时
r($task->computeMultipleHoursTest($oldTasks[1]))                                && p('id,assignedTo,status,estimate,left') && e('2,user1,done,13.00,0.00');  // taskID 2 只有老task计算多人工时
r($task->computeMultipleHoursTest($oldTasks[2]))                                && p('id,assignedTo,status,estimate,left') && e('3,admin,done,15.00,4.00');  // taskID 4 只有老task计算多人工时
r($task->computeMultipleHoursTest($oldTasks[3]))                                && p('id,assignedTo,status,estimate,left') && e('4,user1,pause,17.00,2.00'); // taskID 4 只有老task计算多人工时
r($task->computeMultipleHoursTest($oldTasks[4]))                                && p('id,assignedTo,status,estimate,left') && e('5,admin,cancel,0.00,0.00'); // taskID 5 只有老task计算多人工时
r($task->computeMultipleHoursTest($oldTasks[0], $tasks[0]))                     && p('id,assignedTo,status,estimate,left') && e('1,admin,doing,5.00,4.00');  // taskID 1 有传入task计算多人工时
r($task->computeMultipleHoursTest($oldTasks[1], $tasks[1]))                     && p('id,assignedTo,status,estimate,left') && e('2,user1,done,13.00,0.00');  // taskID 2 有传入task计算多人工时
r($task->computeMultipleHoursTest($oldTasks[0], $tasks[0], $members[0]))        && p('id,assignedTo,status,estimate,left') && e('1,~~,doing,3,3');           // taskID 1 有传入task 传入members计算多人工时
r($task->computeMultipleHoursTest($oldTasks[1], $tasks[1], $members[1]))        && p('id,assignedTo,status,estimate,left') && e('2,user1,doing,3,3');        // taskID 2 有传入task 传入members计算多人工时
r($task->computeMultipleHoursTest($oldTasks[0], $tasks[0], $members[0], false)) && p('id,assignedTo,status,estimate,left') && e('1,~~,doing,3,3');           // taskID 1 有传入task 传入members 不自动更新状态计算多人工时
r($task->computeMultipleHoursTest($oldTasks[1], $tasks[1], $members[1], false)) && p('id,assignedTo,status,estimate,left') && e('2,user1,done,3,3');         // taskID 2 有传入task 传入members 不自动更新状态计算多人工时
