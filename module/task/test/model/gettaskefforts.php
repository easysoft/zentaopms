#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/task.class.php';
su('admin');

/**

title=taskModel->getTaskEfforts();
cid=1
pid=1

查看任务预计 >> 55

*/

$taskID      = '55';
$waitstart   = array('assignedTo' => 'user92','consumed' => '10');

$task = new taskTest();
$task->startTest($taskID,$waitstart);
r($task->getTaskEffortsTest($taskID)) && p('0:task') && e('55'); // 查看任务预计
