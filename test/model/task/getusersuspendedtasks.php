#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('test14');

/**

title=taskModel->getUserSuspendedTasks();
cid=1
pid=1

根据指派人员查看执行和任务对应关系 >> 开发任务33

*/

$taskID     = '23';
$assignedTo = 'test14';

$task = new taskTest();
//var_dump($task->getUserSuspendedTasksTest($taskID,$assignedTo));die;
r($task->getUserSuspendedTasksTest($taskID,$assignedTo)) && p(''.$taskID.':name') && e('开发任务33'); // 根据指派人员查看执行和任务对应关系