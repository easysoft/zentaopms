#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=taskModel->computeWorkingHours();
cid=1
pid=1

根据taskID计算没有子任务的计划工时 >> 1,0,3,0
根据taskID计算有父任务的子任务工时 >> 601,18,30,18
根据不存在的taskID计算工时 >> 0

*/

$taskIDList = array('1', '601', '100001');

$task = new taskTest();
r($task->computeWorkingHoursTest($taskIDList[0])) && p('id,estimate,consumed,left') && e('1,0,3,0');      //根据taskID计算没有子任务的计划工时
r($task->computeWorkingHoursTest($taskIDList[1])) && p('id,estimate,consumed,left') && e('601,18,30,18'); //根据taskID计算有父任务的子任务工时
r($task->computeWorkingHoursTest($taskIDList[2])) && p('id,estimate,consumed,left') && e('0');            //根据不存在的taskID计算工时
