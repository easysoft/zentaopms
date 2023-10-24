#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/task.class.php';
su('admin');

/**

title=taskModel->pause();
cid=1
pid=1

wait状态任务暂停 >> status,wait,pause
doing状态任务暂停 >> status,doing,pause
done状态任务暂停 >> status,done,pause
cancel状态任务暂停 >> status,cancel,pause
closed状态任务暂停 >> status,closed,pause

*/

$taskIDList = array('19','20','21','23','24');

$task = new taskTest();
r($task->pauseTest($taskIDList[0])) && p('0:field,old,new') && e('status,wait,pause');   //wait状态任务暂停
r($task->pauseTest($taskIDList[1])) && p('0:field,old,new') && e('status,doing,pause');  //doing状态任务暂停
r($task->pauseTest($taskIDList[2])) && p('0:field,old,new') && e('status,done,pause');   //done状态任务暂停
r($task->pauseTest($taskIDList[3])) && p('0:field,old,new') && e('status,cancel,pause'); //cancel状态任务暂停
r($task->pauseTest($taskIDList[4])) && p('0:field,old,new') && e('status,closed,pause'); //closed状态任务暂停
