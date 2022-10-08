#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=taskModel->needUpdateBugStatus();
cid=1
pid=1

计算不来源于bug的任务是否需要更新bug状态 >> 2
计算来源于bug的任务是否需要更新bug状态 bug状态active >> 1
计算来源于bug的任务是否需要更新bug状态 bug状态resolved >> 2

*/

$task1 = new stdclass();
$task1->fromBug = 0;

$task2 = new stdclass();
$task2->fromBug = 1;

$task3 = new stdclass();
$task3->fromBug = 51;

$task = new taskTest();
r($task->needUpdateBugStatusTest($task1)) && p() && e('2'); //计算不来源于bug的任务是否需要更新bug状态
r($task->needUpdateBugStatusTest($task2)) && p() && e('1'); //计算来源于bug的任务是否需要更新bug状态 bug状态active
r($task->needUpdateBugStatusTest($task3)) && p() && e('2'); //计算来源于bug的任务是否需要更新bug状态 bug状态resolved