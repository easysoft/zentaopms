#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';

/**

title=taskModel->updateParentStatus();
timeout=0
cid=1

*/

zenData('user')->loadYaml('user')->gen(3);
zenData('project')->loadYaml('project')->gen(1);

su('user1');
$_SERVER['HTTP_HOST'] = 'pms.zentao.com';
$task = new taskTest();

/**
doing           + done   = doing
doing           + closed = doing
doing           + pause  = doing
doing           + cancel = doing
doing           + doing  = doing
doing           + wait   = doing
done            + wait   = doing
closeReasonDone + wait   = doing
*/

zenData('task')->loadYaml('taskdoing')->gen(26, true, false);

r($task->updateParentStatusTest(10)) && p('status') && e('doing');
r($task->updateParentStatusTest(12)) && p('status') && e('doing');
r($task->updateParentStatusTest(14)) && p('status') && e('doing');
r($task->updateParentStatusTest(16)) && p('status') && e('doing');
r($task->updateParentStatusTest(18)) && p('status') && e('doing');
r($task->updateParentStatusTest(20)) && p('status') && e('doing');
r($task->updateParentStatusTest(22)) && p('status') && e('doing');
r($task->updateParentStatusTest(24)) && p('status') && e('doing');
r($task->updateParentStatusTest(26)) && p('status') && e('wait');

/**
pause + done   = doing
pause + closed = doing
pause + pause  = doing
pause + cancel = doing
pause + doing  = doing
pause + wait   = doing
*/

zenData('task')->loadYaml('taskpause')->gen(18, true, false);

r($task->updateParentStatusTest(8))  && p('status') && e('doing');
r($task->updateParentStatusTest(10)) && p('status') && e('doing');
r($task->updateParentStatusTest(12)) && p('status') && e('pause');
r($task->updateParentStatusTest(14)) && p('status') && e('doing');
r($task->updateParentStatusTest(16)) && p('status') && e('doing');
r($task->updateParentStatusTest(18)) && p('status') && e('doing');

/**
wait + wait   = wait
wait + closed = wait
wait + cancel = wait
*/

zenData('task')->loadYaml('taskwait')->gen(9, true, false);

r($task->updateParentStatusTest(4)) && p('status') && e('wait');
r($task->updateParentStatusTest(6)) && p('status') && e('wait');
r($task->updateParentStatusTest(8)) && p('status') && e('wait');

/**
done + done   = done
done + closed = done
done + cancel = done
*/

zenData('task')->loadYaml('taskdone')->gen(9, true, false);

r($task->updateParentStatusTest(4)) && p('status,finishedBy,assignedTo') && e('done,user1,user2');
r($task->updateParentStatusTest(6)) && p('status,finishedBy,assignedTo') && e('done,user1,user2');
r($task->updateParentStatusTest(8)) && p('status,finishedBy,assignedTo') && e('done,user1,user2');

/**
closed + closed = closed
closed + cancel = closed
cancel + cancel = cancel
*/

zenData('task')->loadYaml('taskclose')->gen(9, true, false);

r($task->updateParentStatusTest(4)) && p('status,closedBy,assignedTo,closedReason') && e('closed,user1,closed,done');
r($task->updateParentStatusTest(6)) && p('status,closedBy,assignedTo,closedReason') && e('closed,user1,closed,done');
r($task->updateParentStatusTest(8)) && p('status,finishedBy,canceledBy')            && e('cancel,~~,user1,user2,assignedTo');


