#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('task')->loadYaml('task_pause')->gen(7);
zenData('project')->loadYaml('project_pause')->gen(1);

/**

title=taskModel->pause();
timeout=0
cid=18835

*/

$taskIDList = array('1', '2', '3', '4', '5', '7');

$task = new taskModelTest();
r($task->pauseTest($taskIDList[0])) && p('0:field,old,new') && e('status,wait,pause');   // wait状态任务暂停
r($task->pauseTest($taskIDList[1])) && p('0:field,old,new') && e('status,doing,pause');  // doing状态任务暂停
r($task->pauseTest($taskIDList[2])) && p('0:field,old,new') && e('status,done,pause');   // done状态任务暂停
r($task->pauseTest($taskIDList[3])) && p('0:field,old,new') && e('status,cancel,pause'); // cancel状态任务暂停
r($task->pauseTest($taskIDList[4])) && p('0:field,old,new') && e('status,closed,pause'); // closed状态任务暂停
r($task->pauseTest($taskIDList[5])) && p('0:field,old,new') && e('status,doing,pause');  // doing状态子任务暂停
