#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('task')->loadYaml('task_computebeginandend')->gen(10);

/**

title=taskModel->computeBeginAndEnd();
timeout=0
cid=18775

- 根据taskID计算没有父任务的预计开始 实际开始 截止日期
 - 属性estStartedDiff @0
 - 属性deadlineDiff @0
- 根据taskID计算有子任务的父任务预计开始 实际开始 截止日期
 - 属性estStartedDiff @29
 - 属性deadlineDiff @25
- 根据子任务全部取消的父任务的计算预计开始 实际开始 截止日期
 - 属性estStartedDiff @28
 - 属性deadlineDiff @29
- 根据不存在子任务的父任务的计算预计开始 实际开始 截止日期
 - 属性estStartedDiff @27
 - 属性deadlineDiff @28
- 根据不存在的taskID计算预计开始 实际开始 截止日期
 - 属性estStartedDiff @0
 - 属性deadlineDiff @0

*/

$taskIDList = array('1', '2', '3', '4', '100001');

$task = new taskModelTest();
r($task->computeBeginAndEndTest($taskIDList[0])) && p('estStartedDiff,deadlineDiff') && e('0,0');   // 根据taskID计算没有父任务的预计开始 实际开始 截止日期
r($task->computeBeginAndEndTest($taskIDList[1])) && p('estStartedDiff,deadlineDiff') && e('29,25'); // 根据taskID计算有子任务的父任务预计开始 实际开始 截止日期
r($task->computeBeginAndEndTest($taskIDList[2])) && p('estStartedDiff,deadlineDiff') && e('28,29'); // 根据子任务全部取消的父任务的计算预计开始 实际开始 截止日期
r($task->computeBeginAndEndTest($taskIDList[3])) && p('estStartedDiff,deadlineDiff') && e('27,28'); // 根据不存在子任务的父任务的计算预计开始 实际开始 截止日期
r($task->computeBeginAndEndTest($taskIDList[4])) && p('estStartedDiff,deadlineDiff') && e('0,0');   // 根据不存在的taskID计算预计开始 实际开始 截止日期
