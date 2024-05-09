#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';

/**

title=taskTao->getParentStatusById();
cid=1
pid=1
*/

$task = $tester->loadModel('task');

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

r($task->getParentStatusById(1)) && p() && e('doing');
r($task->getParentStatusById(2)) && p() && e('doing');
r($task->getParentStatusById(3)) && p() && e('doing');
r($task->getParentStatusById(4)) && p() && e('doing');
r($task->getParentStatusById(5)) && p() && e('doing');
r($task->getParentStatusById(6)) && p() && e('doing');
r($task->getParentStatusById(7)) && p() && e('doing');
r($task->getParentStatusById(8)) && p() && e('doing');
r($task->getParentStatusById(9)) && p() && e('wait');

/**
pause + done   = doing
pause + closed = doing
pause + pause  = doing
pause + cancel = doing
pause + doing  = doing
pause + wait   = doing
*/

zenData('task')->loadYaml('taskpause')->gen(18, true, false);

r($task->getParentStatusById(11)) && p() && e('doing');
r($task->getParentStatusById(12)) && p() && e('doing');
r($task->getParentStatusById(13)) && p() && e('pause');
r($task->getParentStatusById(14)) && p() && e('doing');
r($task->getParentStatusById(15)) && p() && e('doing');
r($task->getParentStatusById(16)) && p() && e('doing');

/**
wait + wait   = wait
wait + closed = wait
wait + cancel = wait
*/

zenData('task')->loadYaml('taskwait')->gen(9, true, false);

r($task->getParentStatusById(21)) && p() && e('wait');
r($task->getParentStatusById(22)) && p() && e('wait');
r($task->getParentStatusById(23)) && p() && e('wait');

/**
done + done   = done
done + closed = done
done + cancel = done
*/

zenData('task')->loadYaml('taskdone')->gen(9, true, false);

r($task->getParentStatusById(31)) && p() && e('done');
r($task->getParentStatusById(32)) && p() && e('done');
r($task->getParentStatusById(33)) && p() && e('done');

/**
closed + closed = closed
closed + cancel = closed
cancel + cancel = cancel
*/

zenData('task')->loadYaml('taskclose')->gen(9, true, false);

r($task->getParentStatusById(41)) && p() && e('closed');
r($task->getParentStatusById(42)) && p() && e('closed');
r($task->getParentStatusById(43)) && p() && e('cancel');


