#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/task.class.php';

zdTable('task')->config('task_computebeginandend')->gen(10);

/**

title=taskModel->computeBeginAndEnd();
timeout=0
cid=1

*/

$taskIDList = array('1', '2', '3', '4', '100001');

$task = new taskTest();
r($task->computeBeginAndEndTest($taskIDList[0])) && p('estStartedDiff,deadlineDiff') && e('22,22'); // 根据taskID计算没有父任务的预计开始 实际开始 截止日期
r($task->computeBeginAndEndTest($taskIDList[1])) && p('estStartedDiff,deadlineDiff') && e('18,16'); // 根据taskID计算有子任务的父任务预计开始 实际开始 截止日期
r($task->computeBeginAndEndTest($taskIDList[2])) && p('estStartedDiff,deadlineDiff') && e('20,20'); // 根据子任务全部取消的父任务的计算预计开始 实际开始 截止日期
r($task->computeBeginAndEndTest($taskIDList[3])) && p('estStartedDiff,deadlineDiff') && e('19,19'); // 根据不存在子任务的父任务的计算预计开始 实际开始 截止日期
r($task->computeBeginAndEndTest($taskIDList[4])) && p('estStartedDiff,deadlineDiff') && e('0,0');   // 根据不存在的taskID计算预计开始 实际开始 截止日期
