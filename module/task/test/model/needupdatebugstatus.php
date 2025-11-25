#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';

zenData('user')->gen(5);
su('admin');

/**

title=taskModel->needUpdateBugStatus();
timeout=0
cid=18834

- 计算不来源于bug的任务是否需要更新bug状态 @0
- 计算来源于bug的任务是否需要更新bug状态 bug状态为active @1
- 计算来源于bug的任务是否需要更新bug状态 bug状态为resolved @0
- 计算来源于bug的任务是否需要更新bug状态 bug状态为closed @1
- 计算来源于bug的任务是否需要更新bug状态 bug状态为active @1

*/
$bug = zenData('bug');
$bug->id->range('1-4');
$bug->product->range('1-3');
$bug->title->prefix("Bug")->range('1-4');
$bug->assignedTo->prefix("user")->range('1-4');
$bug->status->range("active,resolved,closed");
$bug->gen(4);

$task1 = new stdclass();
$task1->fromBug = 0;

$task2 = new stdclass();
$task2->fromBug = 1;

$task3 = new stdclass();
$task3->fromBug = 2;

$task4 = new stdclass();
$task4->fromBug = 3;

$task5 = new stdclass();
$task5->fromBug = 4;

$task = new taskTest();
r($task->objectModel->needUpdateBugStatus($task1)) && p() && e('0'); //计算不来源于bug的任务是否需要更新bug状态
r($task->objectModel->needUpdateBugStatus($task2)) && p() && e('1'); //计算来源于bug的任务是否需要更新bug状态 bug状态为active
r($task->objectModel->needUpdateBugStatus($task3)) && p() && e('0'); //计算来源于bug的任务是否需要更新bug状态 bug状态为resolved
r($task->objectModel->needUpdateBugStatus($task4)) && p() && e('1'); //计算来源于bug的任务是否需要更新bug状态 bug状态为closed
r($task->objectModel->needUpdateBugStatus($task5)) && p() && e('1'); //计算来源于bug的任务是否需要更新bug状态 bug状态为active