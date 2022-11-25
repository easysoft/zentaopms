#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=taskModel->getDataOfTasksPerFinishedBy();
cid=1
pid=1

统计没有完成者的任务数量 >> 0

*/

$task = new taskTest();
r($task->getDataOfTasksPerFinishedByTest()) && p() && e('0'); //统计没有完成者的任务数量