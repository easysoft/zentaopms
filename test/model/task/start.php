#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=测试taskModel->startTest();
cid=1
pid=1

wait状态任务开始 >> status,wait,doing
doing状态任务开始 >> 此任务已被启动，不能重复启动！
pause状态任务开始 >> status,pause,doing
closed状态任务开始 >> status,closed,doing

*/

$taskIDList = array('1','4','6');

$waitstart   = array('assignedTo' => 'user92','consumed' => '10');
$pausestart  = array('assignedTo' => 'user95','consumed' => '10');
$closedstart = array('assignedTo' => 'user97','consumed' => '10');

$task = new taskTest();
r($task->startTest($taskIDList[0],$waitstart))   && p('0:field,old,new') && e('status,wait,doing');   // wait状态任务开始
r($task->startTest($taskIDList[0],$waitstart))   && p() && e('此任务已被启动，不能重复启动！');       // doing状态任务开始
r($task->startTest($taskIDList[1],$pausestart))  && p('0:field,old,new') && e('status,pause,doing');  // pause状态任务开始
r($task->startTest($taskIDList[2],$closedstart)) && p('0:field,old,new') && e('status,closed,doing'); // closed状态任务开始
