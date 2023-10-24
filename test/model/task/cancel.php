#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=taskModel->cancel();
cid=1
pid=1

wait状态任务取消 >> status,wait,cancel
doing状态任务取消 >> status,doing,cancel
done状态任务取消 >> status,done,cancel
pause状态任务取消 >> status,pause,cancel
closed状态任务取消 >> status,closed,cancel

*/

$taskIDlist = array('31','32','33','34','36');

$task = new taskTest();
r($task->cancelTest($taskIDlist[0])) && p('0:field,old,new') && e('status,wait,cancel');   //wait状态任务取消
r($task->cancelTest($taskIDlist[1])) && p('0:field,old,new') && e('status,doing,cancel');  //doing状态任务取消
r($task->cancelTest($taskIDlist[2])) && p('0:field,old,new') && e('status,done,cancel');   //done状态任务取消
r($task->cancelTest($taskIDlist[3])) && p('0:field,old,new') && e('status,pause,cancel');  //pause状态任务取消
r($task->cancelTest($taskIDlist[4])) && p('0:field,old,new') && e('status,closed,cancel'); //closed状态任务取消
