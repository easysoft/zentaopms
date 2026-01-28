#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('task')->loadYaml('task_checkhaschildren')->gen(12);

/**

title=taskModel->checkHasChildren();
timeout=0
cid=18867

*/

$task = $tester->loadModel('task');

$parentTaskList = array(1, 2, 3);
$aloneTaskList  = array(4, 5, 6);
$childTaskList  = array(7, 8, 9, 10, 11, 12);

r($task->checkhaschildren($parentTaskList[0])) && p() && e('1'); // 测试任务1是否为父任务
r($task->checkhaschildren($parentTaskList[1])) && p() && e('1'); // 测试任务2是否为父任务
r($task->checkhaschildren($parentTaskList[2])) && p() && e('1'); // 测试任务3是否为父任务

r($task->checkhaschildren($aloneTaskList[0])) && p() && e('0'); // 测试任务4是否为父任务
r($task->checkhaschildren($aloneTaskList[1])) && p() && e('0'); // 测试任务5是否为父任务
r($task->checkhaschildren($aloneTaskList[2])) && p() && e('0'); // 测试任务6是否为父任务

r($task->checkhaschildren($childTaskList[0])) && p() && e('0'); // 测试任务7是否为父任务
r($task->checkhaschildren($childTaskList[1])) && p() && e('0'); // 测试任务8是否为父任务
r($task->checkhaschildren($childTaskList[2])) && p() && e('0'); // 测试任务9是否为父任务
r($task->checkhaschildren($childTaskList[3])) && p() && e('0'); // 测试任务10是否为父任务
r($task->checkhaschildren($childTaskList[4])) && p() && e('0'); // 测试任务11是否为父任务
r($task->checkhaschildren($childTaskList[5])) && p() && e('0'); // 测试任务12是否为父任务
