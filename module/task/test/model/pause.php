#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=taskModel->pause();
timeout=0
cid=18835

- wait状态任务暂停
 - 第0条的field属性 @status
 - 第0条的old属性 @wait
 - 第0条的new属性 @pause
- doing状态任务暂停
 - 第0条的field属性 @status
 - 第0条的old属性 @doing
 - 第0条的new属性 @pause
- done状态任务暂停
 - 第0条的field属性 @status
 - 第0条的old属性 @done
 - 第0条的new属性 @pause
- cancel状态任务暂停
 - 第0条的field属性 @status
 - 第0条的old属性 @cancel
 - 第0条的new属性 @pause
- closed状态任务暂停
 - 第0条的field属性 @status
 - 第0条的old属性 @closed
 - 第0条的new属性 @pause
- doing状态子任务暂停
 - 第0条的field属性 @status
 - 第0条的old属性 @doing
 - 第0条的new属性 @pause

*/

zenData('task')->loadYaml('task_pause')->gen(7);
zenData('project')->loadYaml('project_pause')->gen(1);

$user = zenData('user');
$user->account->range('admin,user1');
$user->realname->range('管理员,用户1');
$user->gen(2);

su('admin');

$taskIDList  = array('1', '2', '3', '4', '5', '7');

$task = new taskModelTest();
r($task->pauseTest($taskIDList[0])) && p('0:field,old,new') && e('status,wait,pause');   // wait状态任务暂停
r($task->pauseTest($taskIDList[1])) && p('0:field,old,new') && e('status,doing,pause');  // doing状态任务暂停
r($task->pauseTest($taskIDList[2])) && p('0:field,old,new') && e('status,done,pause');   // done状态任务暂停
r($task->pauseTest($taskIDList[3])) && p('0:field,old,new') && e('status,cancel,pause'); // cancel状态任务暂停
r($task->pauseTest($taskIDList[4])) && p('0:field,old,new') && e('status,closed,pause'); // closed状态任务暂停
r($task->pauseTest($taskIDList[5])) && p('0:field,old,new') && e('status,doing,pause');  // doing状态子任务暂停
