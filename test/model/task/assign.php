#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=taskModel->assign();
cid=1
pid=1

wait状态任务指派 >> assignedTo,po82,user92
doing状态任务指派 >> assignedTo,,user93
done状态任务指派 >> assignedTo,,user94
pause状态任务指派 >> assignedTo,,user95
cancel状态任务指派 >> assignedTo,,user96
closed状态任务指派 >> assignedTo,,user97

*/

$taskIDlist = array('1','2','3','4','5','6');

$waitTask   = array('assignedTo' => 'user92','status' => 'wait', 'left' => '1');
$doingTask  = array('assignedTo' => 'user93','status' => 'doing');
$doneTask   = array('assignedTo' => 'user94','status' => 'done');
$pauseTask  = array('assignedTo' => 'user95','status' => 'pause');
$cancelTask = array('assignedTo' => 'user96','status' => 'cancel');
$closedTask = array('assignedTo' => 'user97','status' => 'closed');

$task = new taskTest();
r($task->assignTest($taskIDlist[0],$waitTask))   && p('0:field,old,new') && e('assignedTo,po82,user92'); // wait状态任务指派
r($task->assignTest($taskIDlist[1],$doingTask))  && p('0:field,old,new') && e('assignedTo,,user93');     // doing状态任务指派
r($task->assignTest($taskIDlist[2],$doneTask))   && p('0:field,old,new') && e('assignedTo,,user94');     // done状态任务指派
r($task->assignTest($taskIDlist[3],$pauseTask))  && p('0:field,old,new') && e('assignedTo,,user95');     // pause状态任务指派
r($task->assignTest($taskIDlist[4],$cancelTask)) && p('0:field,old,new') && e('assignedTo,,user96');     // cancel状态任务指派
r($task->assignTest($taskIDlist[5],$closedTask)) && p('0:field,old,new') && e('assignedTo,,user97');     // closed状态任务指派
