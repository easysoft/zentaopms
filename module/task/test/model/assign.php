#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/task.class.php';

/**

title=taskModel->assign();
timeout=0
cid=1

*/

zdTable('task')->config('task')->gen(6);
zdTable('user')->config('user')->gen(50);
zdTable('project')->config('project')->gen(20);

su('user12');

$taskIDlist = array(1, 2, 3, 4, 5, 6);

$waitTask       = array('assignedTo' => 'user12','status' => 'wait');
$waitTaskLeft   = array('assignedTo' => 'user11','status' => 'wait', 'left' => 1);
$doingTask      = array('assignedTo' => 'user13','status' => 'doing');
$doingTaskLeft  = array('assignedTo' => 'user10','status' => 'doing', 'left' => 0);
$doneTask       = array('assignedTo' => 'user14','status' => 'done');
$pauseTask      = array('assignedTo' => 'user15','status' => 'pause');
$cancelTask     = array('assignedTo' => 'user16','status' => 'cancel');
$closedTaskLeft = array('assignedTo' => 'user17','status' => 'closed');

$_SERVER['HTTP_HOST'] = 'pms.zentao.com';

$task = new taskTest();
r($task->assignTest($taskIDlist[0], $waitTask))       && p('0:field,old,new') && e('assignedTo,old1,user12'); // wait状态任务指派
r($task->assignTest($taskIDlist[0], $waitTaskLeft))   && p('1:field,old,new') && e('left,0,1');               // wait状态任务指派修改预计剩余
r($task->assignTest($taskIDlist[1], $doingTask))      && p('0:field,old,new') && e('assignedTo,old2,user13'); // doing状态任务指派
r($task->assignTest($taskIDlist[1], $doingTaskLeft))  && p()                  && e('『预计剩余』不能为空。');   // doing状态任务指派,预计剩余为0
r($task->assignTest($taskIDlist[2], $doneTask))       && p('0:field,old,new') && e('assignedTo,old3,user14'); // done状态任务指派
r($task->assignTest($taskIDlist[3], $pauseTask))      && p('0:field,old,new') && e('assignedTo,old4,user15'); // pause状态任务指派
r($task->assignTest($taskIDlist[4], $cancelTask))     && p('0:field,old,new') && e('assignedTo,old5,user16'); // cancel状态任务指派
