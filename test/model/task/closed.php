#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=taskModel->close();
cid=1
pid=1

wait状态任务关闭 >> status,wait,closed
doing状态任务关闭 >> status,doing,closed
done状态任务关闭 >> status,done,closed
pause状态任务关闭 >> status,pause,closed
cancel状态任务关闭 >> status,cancel,closed

*/

$taskIDList = array('25','26','27','28','29');

$task = new taskTest();
r($task->closeTest($taskIDList[0])) && p('0:field,old,new') && e('status,wait,closed');   //wait状态任务关闭
r($task->closeTest($taskIDList[1])) && p('0:field,old,new') && e('status,doing,closed');  //doing状态任务关闭
r($task->closeTest($taskIDList[2])) && p('0:field,old,new') && e('status,done,closed');   //done状态任务关闭
r($task->closeTest($taskIDList[3])) && p('0:field,old,new') && e('status,pause,closed');  //pause状态任务关闭
r($task->closeTest($taskIDList[4])) && p('0:field,old,new') && e('status,cancel,closed'); //cancel状态任务关闭
