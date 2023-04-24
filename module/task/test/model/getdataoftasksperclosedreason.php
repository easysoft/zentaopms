#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/task.class.php';
su('admin');

/**

title=taskModel->getDataOfTasksPerClosedReason();
cid=1
pid=1

统计没有完成原因的任务数量 >> 0

*/

$task = new taskTest();
r($task->getDataOfTasksPerClosedReasonTest()) && p() && e('0'); //统计没有完成原因的任务数量