#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';

/**

title=taskTao->autoUpdateTaskByStatus();
cid=18860

- 执行task模块的autoUpdateTaskByStatusTest方法，参数是$parentTask, $childTask, 'done'
 - 属性status @done
 - 属性assignedTo @user1
 - 属性assignedDate @~c:<3~
 - 属性finishedBy @user4
 - 属性finishedDate @~c:<3~
- 执行task模块的autoUpdateTaskByStatusTest方法，参数是$parentTask, $childTask, 'cancel'
 - 属性status @cancel
 - 属性assignedTo @user1
 - 属性assignedDate @~c:<3~
 - 属性canceledBy @user4
 - 属性canceledDate @~c:<3~
- 执行task模块的autoUpdateTaskByStatusTest方法，参数是$parentTask, $childTask, 'cancel'
 - 属性status @cancel
 - 属性assignedTo @user1
 - 属性assignedDate @~c:<3~
 - 属性canceledBy @user4
 - 属性canceledDate @~c:<3~
- 执行task模块的autoUpdateTaskByStatusTest方法，参数是$parentTask, $childTask, 'closed'
 - 属性status @closed
 - 属性assignedTo @closed
 - 属性assignedDate @~c:<3~
 - 属性closedBy @user4
 - 属性closedDate @~c:<3~
 - 属性closedReason @done
- 执行task模块的autoUpdateTaskByStatusTest方法，参数是$parentTask, $childTask, 'doing'
 - 属性status @doing
 - 属性finishedBy @~~
 - 属性finishedDate @~c:>100~
 - 属性closedBy @~~
 - 属性closedDate @~c:>100~
- 执行task模块的autoUpdateTaskByStatusTest方法，参数是$parentTask, $childTask, 'wait'
 - 属性status @wait
 - 属性finishedBy @~~
 - 属性finishedDate @~c:>100~
 - 属性closedBy @~~
 - 属性closedDate @~c:>100~
- 执行task模块的autoUpdateTaskByStatusTest方法，参数是$parentTask, $childTask, 'doing'
 - 属性status @doing
 - 属性finishedBy @~~
 - 属性finishedDate @~c:>100~
 - 属性closedBy @~~
 - 属性closedDate @~c:>100~
 - 属性assignedTo @user3
 - 属性assignedDate @~c:<3~
- 执行task模块的autoUpdateTaskByStatusTest方法，参数是$parentTask, $childTask, 'doing'
 - 属性lastEditedBy @user4
 - 属性lastEditedDate @~c:<3~

*/

zenData('user')->loadYaml('user')->gen(5);
su('user4');

$task = new taskTest();

$taskTable = zenData('task');
$taskTable->story->range(0);
$taskTable->gen(10);

$parentTask = new stdclass();
$parentTask->id         = 1;
$parentTask->openedBy   = 'user1';
$parentTask->assignedTo = 'user2';
$parentTask->status     = 'doing';

$childTask = new stdclass();
$childTask->assignedTo = 'user3';

r($task->autoUpdateTaskByStatusTest($parentTask, $childTask, 'done'))   && p('status,assignedTo,assignedDate,finishedBy,finishedDate') && e('done,user1,~c:<3~,user4,~c:<3~');
r($task->autoUpdateTaskByStatusTest($parentTask, $childTask, 'cancel')) && p('status,assignedTo,assignedDate,canceledBy,canceledDate') && e('cancel,user1,~c:<3~,user4,~c:<3~');
r($task->autoUpdateTaskByStatusTest($parentTask, $childTask, 'cancel')) && p('status,assignedTo,assignedDate,canceledBy,canceledDate') && e('cancel,user1,~c:<3~,user4,~c:<3~');

r($task->autoUpdateTaskByStatusTest($parentTask, $childTask, 'closed')) && p('status,assignedTo,assignedDate,closedBy,closedDate,closedReason') && e('closed,closed,~c:<3~,user4,~c:<3~,done');

r($task->autoUpdateTaskByStatusTest($parentTask, $childTask, 'doing')) && p('status,finishedBy,finishedDate,closedBy,closedDate') && e('doing,~~,~c:>100~,~~,~c:>100~');
r($task->autoUpdateTaskByStatusTest($parentTask, $childTask, 'wait'))  && p('status,finishedBy,finishedDate,closedBy,closedDate') && e('wait,~~,~c:>100~,~~,~c:>100~');

$parentTask->assignedTo = 'closed';
r($task->autoUpdateTaskByStatusTest($parentTask, $childTask, 'doing')) && p('status,finishedBy,finishedDate,closedBy,closedDate,assignedTo,assignedDate') && e('doing,~~,~c:>100~,~~,~c:>100~,user3,~c:<3~');

r($task->autoUpdateTaskByStatusTest($parentTask, $childTask, 'doing')) && p('lastEditedBy,lastEditedDate') && e('user4,~c:<3~');
