#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=taskModel->getExecutionTasks();
cid=1
pid=1

根据执行id获取任务列表 >> 开发任务11
根据执行id获取任务数量 >> 4

*/

$executionID = '101';
$count       = array('0','1');

$task = new taskTest();
r($task->getExecutionTasksTest($executionID,$count[0])) && p('1:name') && e('开发任务11'); //根据执行id获取任务列表
r($task->getExecutionTasksTest($executionID,$count[1])) && p()         && e('4');          //根据执行id获取任务数量