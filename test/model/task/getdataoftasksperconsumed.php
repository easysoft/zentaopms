#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=taskModel->getDataOfTasksPerConsumed();
cid=1
pid=1

统计消耗工时为3的任务数量 >> 3,90
统计消耗工时为4的任务数量 >> 4,90
统计消耗工时为5的任务数量 >> 5,90
统计消耗工时为6的任务数量 >> 6,91
统计消耗工时为7的任务数量 >> 7,91
统计消耗工时为8的任务数量 >> 8,91
统计消耗工时为9的任务数量 >> 9,91

*/

$task = new taskTest();
r($task->getDataOfTasksPerConsumedTest()) && p('3:name,value') && e('3,90'); //统计消耗工时为3的任务数量
r($task->getDataOfTasksPerConsumedTest()) && p('4:name,value') && e('4,90'); //统计消耗工时为4的任务数量
r($task->getDataOfTasksPerConsumedTest()) && p('5:name,value') && e('5,90'); //统计消耗工时为5的任务数量
r($task->getDataOfTasksPerConsumedTest()) && p('6:name,value') && e('6,91'); //统计消耗工时为6的任务数量
r($task->getDataOfTasksPerConsumedTest()) && p('7:name,value') && e('7,91'); //统计消耗工时为7的任务数量
r($task->getDataOfTasksPerConsumedTest()) && p('8:name,value') && e('8,91'); //统计消耗工时为8的任务数量
r($task->getDataOfTasksPerConsumedTest()) && p('9:name,value') && e('9,91'); //统计消耗工时为9的任务数量