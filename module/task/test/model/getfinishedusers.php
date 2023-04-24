#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/task.class.php';
su('admin');

/**

title=taskModel->getDataOfTasksPerEstimate();
cid=1
pid=1

获取空数据 >> 0
获取完成多人任务ID为1的人员 >> 0

*/

$task = new taskTest();

$taskObject = $task->getByIDTest(1); 

r($task->getFinishedUsersTest()) && p() && e('0');                                   // 获取空数据
r($task->getFinishedUsersTest($taskObject->id, $taskObject->team)) && p() && e('0'); // 获取完成多人任务ID为1的人员