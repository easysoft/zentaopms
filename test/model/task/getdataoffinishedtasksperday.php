#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=taskModel->getDataOffinishedTasksPerDay();
cid=1
pid=1

统计没有每日完成的任务数量 >> 0

*/

$task = new taskTest();
r($task->getDataOffinishedTasksPerDayTest()) && p() && e('0'); //统计没有每日完成的任务数量