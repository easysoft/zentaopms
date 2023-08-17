#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/task.class.php';

zdTable('user')->gen(5);
su('admin');

/**

title=taskModel->needUpdateBugStatus();
timeout=0
cid=1

*/
$bug = zdTable('bug');
$bug->id->range('1-3');
$bug->product->range('1-3');
$bug->title->prefix("Bug")->range('1-3');
$bug->assignedTo->prefix("user")->range('1-3');
$bug->status->range("active,resolved");
$bug->gen(3);

$task1 = new stdclass();
$task1->fromBug = 0;

$task2 = new stdclass();
$task2->fromBug = 1;

$task3 = new stdclass();
$task3->fromBug = 2;

$task = new taskTest();
r($task->objectModel->needUpdateBugStatus($task1)) && p() && e('0'); //计算不来源于bug的任务是否需要更新bug状态
r($task->objectModel->needUpdateBugStatus($task2)) && p() && e('1'); //计算来源于bug的任务是否需要更新bug状态 bug状态为active
r($task->objectModel->needUpdateBugStatus($task3)) && p() && e('0'); //计算来源于bug的任务是否需要更新bug状态 bug状态为resolved
