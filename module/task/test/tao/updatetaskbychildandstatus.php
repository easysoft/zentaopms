#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';

/**

title=taskTao->getParentStatusById();
cid=1
pid=1
*/

zenData('user')->loadYaml('user')->gen(5);
su('user4');

$task = new taskTest();

zenData('task')->gen(10);

$parentTask = new stdclass();
$parentTask->id         = 1;
$parentTask->openedBy   = 'user1';
$parentTask->assignedTo = 'user2';

$childTask = new stdclass();
$childTask->assignedTo = 'user3';

r($task->updateTaskByChildAndStatusTest($parentTask, $childTask, 'done'))   && p('status,assignedTo,assignedDate,finishedBy,finishedDate') && e('done,user1,~c:<3~,user4,~c:<3~');
r($task->updateTaskByChildAndStatusTest($parentTask, $childTask, 'cancel')) && p('status,assignedTo,assignedDate,canceledBy,canceledDate') && e('cancel,user1,~c:<3~,user4,~c:<3~');
r($task->updateTaskByChildAndStatusTest($parentTask, $childTask, 'cancel')) && p('status,assignedTo,assignedDate,canceledBy,canceledDate') && e('cancel,user1,~c:<3~,user4,~c:<3~');

r($task->updateTaskByChildAndStatusTest($parentTask, $childTask, 'closed')) && p('status,assignedTo,assignedDate,closedBy,closedDate,closedReason') && e('closed,closed,~c:<3~,user4,~c:<3~,done');

r($task->updateTaskByChildAndStatusTest($parentTask, $childTask, 'doing')) && p('status,finishedBy,finishedDate,closedBy,closedDate') && e('doing,~~,~c:>100~,~~,~c:>100~');
r($task->updateTaskByChildAndStatusTest($parentTask, $childTask, 'wait'))  && p('status,finishedBy,finishedDate,closedBy,closedDate') && e('wait,~~,~c:>100~,~~,~c:>100~');

$parentTask->assignedTo = 'closed';
r($task->updateTaskByChildAndStatusTest($parentTask, $childTask, 'doing')) && p('status,finishedBy,finishedDate,closedBy,closedDate,assignedTo,assignedDate') && e('doing,~~,~c:>100~,~~,~c:>100~,user3,~c:<3~');

r($task->updateTaskByChildAndStatusTest($parentTask, $childTask, 'doing')) && p('lastEditedBy,lastEditedDate,parent') && e('user4,~c:<3~,-1');


