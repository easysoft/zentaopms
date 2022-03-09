#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=taskModel->computeWorkingHours();
cid=1
pid=1

根据taskID计算没有父计划的计划工时 >> 1,0,3,0
根据taskID计算有父计划的父计划工时 >> 601,18,30,18

*/

$taskIDList = array('1', '601');

$task = new taskTest();
r($task->computeWorkingHoursTest($taskIDList[0])) && p('id,estimate,consumed,left') && e('1,0,3,0');      //根据taskid计算没有父计划的计划工时
r($task->computeWorkingHoursTest($taskIDList[1])) && p('id,estimate,consumed,left') && e('601,18,31,13'); //根据taskID计算有父计划的父计划工时
system("./ztest init");
