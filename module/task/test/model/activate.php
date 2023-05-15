#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/task.class.php';

/**

title=taskModel->activate();
timeout=0
cid=1

- wait状态任务激活
 - 第0条的field属性 @status
 - 第0条的old属性 @wait
 - 第0条的new属性 @doing

- done状态任务激活
 - 第0条的field属性 @status
 - 第0条的old属性 @done
 - 第0条的new属性 @doing

- pause状态任务激活
 - 第0条的field属性 @status
 - 第0条的old属性 @pause
 - 第0条的new属性 @doing

- cancel状态任务激活
 - 第0条的field属性 @status
 - 第0条的old属性 @cancel
 - 第0条的new属性 @doing

- closed状态任务激活
 - 第0条的field属性 @status
 - 第0条的old属性 @closed
 - 第0条的new属性 @doing

*/

zdTable('task')->gen(10);
zdTable('project')->config('execution')->gen(10);
zdTable('story')->gen(30);

$taskIDList = array(1, 3, 4, 5, 6);

$task = new taskTest();
r($task->activateTest($taskIDList[0])) && p('0:field,old,new') && e('status,wait,doing');   //wait状态任务激活
r($task->activateTest($taskIDList[1])) && p('0:field,old,new') && e('status,done,doing');   //done状态任务激活
r($task->activateTest($taskIDList[2])) && p('0:field,old,new') && e('status,pause,doing');  //pause状态任务激活
r($task->activateTest($taskIDList[3])) && p('0:field,old,new') && e('status,cancel,doing'); //cancel状态任务激活
r($task->activateTest($taskIDList[4])) && p('0:field,old,new') && e('status,closed,doing'); //closed状态任务激活