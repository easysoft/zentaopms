#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=taskModel->getDataOfTasksPerEstimate();
cid=1
pid=1

统计预计工时为0的任务数量 >> 0,83
统计预计工时为1的任务数量 >> 1,83
统计预计工时为2的任务数量 >> 2,83
统计预计工时为3的任务数量 >> 3,83
统计预计工时为4的任务数量 >> 4,83
统计预计工时为5的任务数量 >> 5,83
统计预计工时为6的任务数量 >> 6,82
统计预计工时为7的任务数量 >> 7,82

*/

$task = new taskTest();
r($task->getDataOfTasksPerEstimateTest()) && p('0:name,value') && e('0,83'); //统计预计工时为0的任务数量
r($task->getDataOfTasksPerEstimateTest()) && p('1:name,value') && e('1,83'); //统计预计工时为1的任务数量
r($task->getDataOfTasksPerEstimateTest()) && p('2:name,value') && e('2,83'); //统计预计工时为2的任务数量
r($task->getDataOfTasksPerEstimateTest()) && p('3:name,value') && e('3,83'); //统计预计工时为3的任务数量
r($task->getDataOfTasksPerEstimateTest()) && p('4:name,value') && e('4,83'); //统计预计工时为4的任务数量
r($task->getDataOfTasksPerEstimateTest()) && p('5:name,value') && e('5,83'); //统计预计工时为5的任务数量
r($task->getDataOfTasksPerEstimateTest()) && p('6:name,value') && e('6,82'); //统计预计工时为6的任务数量
r($task->getDataOfTasksPerEstimateTest()) && p('7:name,value') && e('7,82'); //统计预计工时为7的任务数量