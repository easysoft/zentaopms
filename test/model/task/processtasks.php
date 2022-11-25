#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=taskModel->processTasks();
cid=1
pid=1

根据tasks计算executionID为1的执行下所有任务的进度 任务1 >> ,100
根据tasks计算executionID为1的执行下所有任务的进度 任务601 >> 7,100
根据tasks计算executionID为1的执行下所有任务的进度 任务602 >> 8,80
根据tasks计算executionID为1的执行下所有任务的进度 任务603 >> ,71
根据tasks计算executionID为2的执行下所有任务的进度 任务2 >> ,80
根据tasks计算executionID为2的执行下所有任务的进度 任务604 >> 10,67
根据tasks计算executionID为2的执行下所有任务的进度 任务605 >> ,64
根据tasks计算executionID为2的执行下所有任务的进度 任务606 >> ,62

*/

$executionID = array('101', '102');

$task = new taskTest();
r($task->processTasksTest($executionID['0'])) && p('1:delay,progress')   && e(',100');  //根据tasks计算executionID为1的执行下所有任务的进度 任务1
r($task->processTasksTest($executionID['0'])) && p('601:delay,progress') && e('7,100');  //根据tasks计算executionID为1的执行下所有任务的进度 任务601
r($task->processTasksTest($executionID['0'])) && p('602:delay,progress') && e('8,80');   //根据tasks计算executionID为1的执行下所有任务的进度 任务602
r($task->processTasksTest($executionID['0'])) && p('603:delay,progress') && e(',71');   //根据tasks计算executionID为1的执行下所有任务的进度 任务603
r($task->processTasksTest($executionID['1'])) && p('2:delay,progress')   && e(',80');   //根据tasks计算executionID为2的执行下所有任务的进度 任务2
r($task->processTasksTest($executionID['1'])) && p('604:delay,progress') && e('10,67'); //根据tasks计算executionID为2的执行下所有任务的进度 任务604
r($task->processTasksTest($executionID['1'])) && p('605:delay,progress') && e(',64');   //根据tasks计算executionID为2的执行下所有任务的进度 任务605
r($task->processTasksTest($executionID['1'])) && p('606:delay,progress') && e(',62');   //根据tasks计算executionID为2的执行下所有任务的进度 任务606