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
根据执行id,按pri升序获取第一个任务pri >> 3
根据执行id,按pri降序获取第一个任务pri >> 1

*/

$executionID = '101';
$productID   = 0;
$type        = 'all';
$modules     = 0;
$orderBy     = array('status_asc, id_desc', 'pri_desc', 'pri_asc');
$count       = array('0','1');

$task = new taskTest();
$priDescTasks = $task->getExecutionTasksTest($executionID,  $productID, $type, $modules, $orderBy[1]);
$priAscTasks  = $task->getExecutionTasksTest($executionID,  $productID, $type, $modules, $orderBy[2]);

r($task->getExecutionTasksTest($executionID))                                                       && p('1:name') && e('开发任务11'); //根据执行id获取任务列表
r($task->getExecutionTasksTest($executionID,  $productID, $type, $modules, $orderBy[0], $count[1])) && p()         && e('4');          //根据执行id获取任务数量
r(array_shift($priDescTasks))                                                                       && p('pri')    && e('3');          //根据执行id,按pri升序获取第一个任务pri
r(array_shift($priAscTasks))                                                                        && p('pri')    && e('1');          //根据执行id,按pri降序获取第一个任务pri
