#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 programModel::create();
cid=1
pid=1

传入任务数组，返回计算后的总消耗 >> 13
传入任务数组，返回计算后的总预计 >> 15
传入任务数组，返回计算后的总剩余 >> 7
传入任务数组，返回计算后的总工时 >> 20
传入任务数组，返回计算后的进度 >> 65

*/

global $tester;
$tester->loadModel('program');
$task1 = new stdclass();
$task1->consumed = 2;
$task1->left     = 2;
$task1->estimate = 2;
$task1->status   = 'active';

$task2 = new stdclass();
$task2->consumed = 4;
$task2->left     = 5;
$task2->estimate = 6;
$task2->status   = 'active';

$task3 = new stdclass();
$task3->consumed = 7;
$task3->left     = 7;
$task3->estimate = 7;
$task3->status   = 'closed';

$tasks[] = $task1;
$tasks[] = $task2;
$tasks[] = $task3;

$projectTasks[1] = $tasks;

$hours = $tester->program->computeProgress($projectTasks);

r($hours) && p('1:totalConsumed') && e('13'); // 传入任务数组，返回计算后的总消耗
r($hours) && p('1:totalEstimate') && e('15'); // 传入任务数组，返回计算后的总预计
r($hours) && p('1:totalLeft')     && e('7');  // 传入任务数组，返回计算后的总剩余
r($hours) && p('1:totalReal')     && e('20'); // 传入任务数组，返回计算后的总工时
r($hours) && p('1:progress')      && e('65'); // 传入任务数组，返回计算后的进度