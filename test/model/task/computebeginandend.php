#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=taskModel->computeBeginAndEnd();
cid=1
pid=1

根据taskID计算没有父计划的开始结束时间 >> 0,6
根据taskID计算有父计划的父开始结束时间 >> 0,7
根据不存在的taskID计算开始结束时间 >> 0

*/

$taskIDList = array('1', '901', '100001');

$task = new taskTest();
r($task->computeBeginAndEndTest($taskIDList[0])) && p('estStartedDiff,deadlineDiff') && e('0,6'); //根据taskID计算没有父计划的开始结束时间
r($task->computeBeginAndEndTest($taskIDList[1])) && p('estStartedDiff,deadlineDiff') && e('0,7'); //根据taskID计算有父计划的父开始结束时间
r($task->computeBeginAndEndTest($taskIDList[2])) && p('estStartedDiff,deadlineDiff') && e('0');   //根据不存在的taskID计算开始结束时间
