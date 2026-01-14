#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

/**

title=taskTao->mergeChildIntoParent();
cid=18887

- 传入空数组。 @0
- 检查归并后的第一个任务 @2
- 检查归并后的第二个任务 @1
- 检查归并后的第三个任务 @3
- 检查归并后的第四个任务 @5
- 检查归并后的第五个任务 @4
- 检查归并后的最后一个任务 @7

*/

$tasks = array();
$tasks['1']         = new stdclass();
$tasks['1']->id     = 1;
$tasks['1']->parent = 2;
$tasks['2']         = new stdclass();
$tasks['2']->id     = 2;
$tasks['2']->parent = 0;
$tasks['3']         = new stdclass();
$tasks['3']->id     = 3;
$tasks['3']->parent = 2;
$tasks['4']         = new stdclass();
$tasks['4']->id     = 4;
$tasks['4']->parent = 10;
$tasks['5']         = new stdclass();
$tasks['5']->id     = 5;
$tasks['5']->parent = 2;
$tasks['6']         = new stdclass();
$tasks['6']->id     = 6;
$tasks['6']->parent = 0;
$tasks['7']         = new stdclass();
$tasks['7']->id     = 7;
$tasks['7']->parent = 0;

global $tester;
$taskModel = $tester->loadModel('task');
r(count($taskModel->mergeChildIntoParent(array())))   && p() && e('0'); //传入空数组。

$mergedTasks = $taskModel->mergeChildIntoParent($tasks);
$firstTask   = array_shift($mergedTasks);
r($firstTask->id) && p() && e('2'); //检查归并后的第一个任务

$secondTask = array_shift($mergedTasks);
r($secondTask->id) && p() && e('1'); //检查归并后的第二个任务

$thirdTask = array_shift($mergedTasks);
r($thirdTask->id) && p() && e('3'); //检查归并后的第三个任务

$fourthTask = array_shift($mergedTasks);
r($fourthTask->id) && p() && e('5'); //检查归并后的第四个任务

$fifthTask = array_shift($mergedTasks);
r($fifthTask->id) && p() && e('4'); //检查归并后的第五个任务

$lastTask = array_pop($mergedTasks);
r($lastTask->id) && p() && e('7'); //检查归并后的最后一个任务
