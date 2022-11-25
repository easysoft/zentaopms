#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=taskModel->getDataOfTasksPerExecution();
cid=1
pid=1

统计executionID为101的执行的任务数量 >> 迭代1,14
统计executionID为102的执行的任务数量 >> 迭代2,4
统计executionID为103的执行的任务数量 >> 迭代3,4
统计executionID为104的执行的任务数量 >> 迭代4,4
统计executionID为105的执行的任务数量 >> 迭代5,4
统计executionID为106的执行的任务数量 >> 迭代6,4
统计executionID为107的执行的任务数量 >> 迭代7,4

*/

$task = new taskTest();
r($task->getDataOfTasksPerExecutionTest()) && p('101:name,value')       && e('迭代1,14'); //统计executionID为101的执行的任务数量
r($task->getDataOfTasksPerExecutionTest()) && p('102:name,value')       && e('迭代2,4');  //统计executionID为102的执行的任务数量
r($task->getDataOfTasksPerExecutionTest()) && p('103:name,value')       && e('迭代3,4');  //统计executionID为103的执行的任务数量
r($task->getDataOfTasksPerExecutionTest()) && p('104:name,value')       && e('迭代4,4');  //统计executionID为104的执行的任务数量
r($task->getDataOfTasksPerExecutionTest()) && p('105:name,value')       && e('迭代5,4');  //统计executionID为105的执行的任务数量
r($task->getDataOfTasksPerExecutionTest()) && p('106:name,value')       && e('迭代6,4');  //统计executionID为106的执行的任务数量
r($task->getDataOfTasksPerExecutionTest()) && p('107:name,value')       && e('迭代7,4');  //统计executionID为107的执行的任务数量