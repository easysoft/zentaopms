#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=taskModel->getDataOfTasksPerDeadline();
cid=1
pid=1

统计日期为+1days的任务数量 >> 17
统计日期为+2days的任务数量 >> 17
统计日期为+3days的任务数量 >> 18
统计日期为+4days的任务数量 >> 18
统计日期为-1days的任务数量 >> 17
统计日期为-2days的任务数量 >> 17
统计日期为-3days的任务数量 >> 17
统计日期为-4days的任务数量 >> 17

*/

$task = new taskTest();
r($task->getDataOfTasksPerDeadlineTest(0)) && p("0:value") && e("17"); //统计日期为+1days的任务数量
r($task->getDataOfTasksPerDeadlineTest(1)) && p("1:value") && e("17"); //统计日期为+2days的任务数量
r($task->getDataOfTasksPerDeadlineTest(2)) && p("2:value") && e("18"); //统计日期为+3days的任务数量
r($task->getDataOfTasksPerDeadlineTest(3)) && p("3:value") && e("18"); //统计日期为+4days的任务数量
r($task->getDataOfTasksPerDeadlineTest(4)) && p("4:value") && e("17"); //统计日期为-1days的任务数量
r($task->getDataOfTasksPerDeadlineTest(5)) && p("5:value") && e("17"); //统计日期为-2days的任务数量
r($task->getdataoftasksperdeadlinetest(6)) && p("6:value") && e("17"); //统计日期为-3days的任务数量
r($task->getdataoftasksperdeadlinetest(7)) && p("7:value") && e("17"); //统计日期为-4days的任务数量