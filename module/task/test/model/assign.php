#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';

/**

title=taskModel->assign();
timeout=0
cid=18766

- wait状态任务指派
 - 第0条的field属性 @assignedTo
 - 第0条的old属性 @old1
 - 第0条的new属性 @user12
- wait状态任务指派修改预计剩余
 - 第1条的field属性 @left
 - 第1条的old属性 @0
 - 第1条的new属性 @1
- doing状态任务指派
 - 第0条的field属性 @assignedTo
 - 第0条的old属性 @old2
 - 第0条的new属性 @user13
- doing状态任务指派,预计剩余为0 @『预计剩余』不能为空。
- done状态任务指派
 - 第0条的field属性 @assignedTo
 - 第0条的old属性 @old3
 - 第0条的new属性 @user14
- pause状态任务指派
 - 第0条的field属性 @assignedTo
 - 第0条的old属性 @old4
 - 第0条的new属性 @user15
- cancel状态任务指派
 - 第0条的field属性 @assignedTo
 - 第0条的old属性 @old5
 - 第0条的new属性 @user16

*/

zenData('task')->loadYaml('task')->gen(6);
zenData('user')->loadYaml('user')->gen(50);
zenData('project')->loadYaml('project')->gen(20);

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