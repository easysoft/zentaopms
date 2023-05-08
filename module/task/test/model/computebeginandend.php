#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/task.class.php';

zdTable('task')->config('task_computebeginandend')->gen(10);

/**

title=taskModel->computeBeginAndEnd();
cid=1
pid=1

*/

$taskIDList = array('1', '2', '3', '4', '100001');

$task = new taskTest();
r($task->computeBeginAndEndTest($taskIDList[0])) && p('estStarted,realStarted,deadline') && e('2021-01-01,2021-01-02 00:00:00,2021-01-03'); //根据taskID计算没有父任务的预计开始 实际开始 截止日期
r($task->computeBeginAndEndTest($taskIDList[1])) && p('estStarted,realStarted,deadline') && e('2021-01-17,2021-01-18 16:00:00,2021-01-28'); //根据taskID计算有子任务的父任务预计开始 实际开始 截止日期
r($task->computeBeginAndEndTest($taskIDList[2])) && p('estStarted,realStarted,deadline') && e('2021-01-09,2021-01-10 08:00:00,2021-01-11'); //根据子任务全部取消的父任务的计算预计开始 实际开始 截止日期
r($task->computeBeginAndEndTest($taskIDList[3])) && p('estStarted,realStarted,deadline') && e('2021-01-13,2021-01-14 12:00:00,2021-01-15'); //根据不存在子任务的父任务的计算预计开始 实际开始 截止日期
r($task->computeBeginAndEndTest($taskIDList[4])) && p('estStarted,realStarted,deadline') && e('0,0,0');                                     //根据不存在的taskID计算预计开始 实际开始 截止日期
