#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=taskModel->getUserTasks();
cid=1
pid=1

根据指派人员查看任务 >> 开发任务12

*/

$taskID     = '2';
$assignedTo = 'user92';

$task = new taskTest();
r($task->getUserTasksTest($taskID,$assignedTo)) && p('2:name') && e('开发任务12'); // 根据指派人员查看任务
