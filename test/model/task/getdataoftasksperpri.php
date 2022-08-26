#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=taskModel->getDataOfTasksPerPri();
cid=1
pid=1

统计优先级为1的任务数量 >> 1,228
统计优先级为2的任务数量 >> 2,228
统计优先级为3的任务数量 >> 3,227
统计优先级为4的任务数量 >> 4,227

*/

$task = new taskTest();
r($task->getDataOfTasksPerPriTest()) && p('1:name,value') && e('1,228'); //统计优先级为1的任务数量
r($task->getDataOfTasksPerPriTest()) && p('2:name,value') && e('2,228'); //统计优先级为2的任务数量
r($task->getDataOfTasksPerPriTest()) && p('3:name,value') && e('3,227'); //统计优先级为3的任务数量
r($task->getDataOfTasksPerPriTest()) && p('4:name,value') && e('4,227'); //统计优先级为4的任务数量