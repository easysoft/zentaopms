#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=taskModel->getTaskEstimate();
cid=1
pid=1

查看任务预计 >> 55

*/

$taskID      = '55';
$waitstart   = array('assignedTo' => 'user92','consumed' => '10');

$task = new taskTest();
$task->startTest($taskID,$waitstart);
r($task->getTaskEstimateTest($taskID)) && p('0:task') && e('55'); // 查看任务预计