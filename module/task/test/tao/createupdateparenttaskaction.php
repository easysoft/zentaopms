#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';

/**

title=taskTao->createUpdateParentTaskAction();
cid=1
pid=1
*/

zenData('user')->loadYaml('user')->gen(5);
zenData('project')->loadYaml('project')->gen(1);
su('user4');

$task = new taskTest();

zenData('task')->loadYaml('task')->gen(6);

$oldParentTask = new stdclass();

$oldParentTask->id     = 1;
$oldParentTask->status = 'closed';
r($task->createUpdateParentTaskActionTest($oldParentTask)) && p('action') && e('finished');
$oldParentTask->status = 'pause';
r($task->createUpdateParentTaskActionTest($oldParentTask)) && p('action') && e('finished');
$oldParentTask->status = 'cancel';
r($task->createUpdateParentTaskActionTest($oldParentTask)) && p('action') && e('finished');
$oldParentTask->status = 'wait';
r($task->createUpdateParentTaskActionTest($oldParentTask)) && p('action') && e('finished');
$oldParentTask->status = 'doing';
r($task->createUpdateParentTaskActionTest($oldParentTask)) && p('action') && e('finished');

$oldParentTask->id     = 2;
$oldParentTask->status = 'done';
r($task->createUpdateParentTaskActionTest($oldParentTask)) && p('action') && e('closed');
$oldParentTask->status = 'pause';
r($task->createUpdateParentTaskActionTest($oldParentTask)) && p('action') && e('closed');
$oldParentTask->status = 'cancel';
r($task->createUpdateParentTaskActionTest($oldParentTask)) && p('action') && e('closed');
$oldParentTask->status = 'wait';
r($task->createUpdateParentTaskActionTest($oldParentTask)) && p('action') && e('closed');
$oldParentTask->status = 'doing';
r($task->createUpdateParentTaskActionTest($oldParentTask)) && p('action') && e('closed');

$oldParentTask->id     = 3;
$oldParentTask->status = 'done';
r($task->createUpdateParentTaskActionTest($oldParentTask)) && p('action') && e('paused');
$oldParentTask->status = 'cancel';
r($task->createUpdateParentTaskActionTest($oldParentTask)) && p('action') && e('paused');
$oldParentTask->status = 'wait';
r($task->createUpdateParentTaskActionTest($oldParentTask)) && p('action') && e('paused');
$oldParentTask->status = 'doing';
r($task->createUpdateParentTaskActionTest($oldParentTask)) && p('action') && e('paused');
$oldParentTask->status = 'closed';
r($task->createUpdateParentTaskActionTest($oldParentTask)) && p('action') && e('paused');

$oldParentTask->id     = 4;
$oldParentTask->status = 'done';
r($task->createUpdateParentTaskActionTest($oldParentTask)) && p('action') && e('canceled');
$oldParentTask->status = 'wait';
r($task->createUpdateParentTaskActionTest($oldParentTask)) && p('action') && e('canceled');
$oldParentTask->status = 'doing';
r($task->createUpdateParentTaskActionTest($oldParentTask)) && p('action') && e('canceled');
$oldParentTask->status = 'closed';
r($task->createUpdateParentTaskActionTest($oldParentTask)) && p('action') && e('canceled');
$oldParentTask->status = 'pause';
r($task->createUpdateParentTaskActionTest($oldParentTask)) && p('action') && e('canceled');

$oldParentTask->id     = 5;
$oldParentTask->status = 'wait';
r($task->createUpdateParentTaskActionTest($oldParentTask)) && p('action') && e('started');
$oldParentTask->status = 'pause';
r($task->createUpdateParentTaskActionTest($oldParentTask)) && p('action') && e('restarted');
$oldParentTask->status = 'done';
r($task->createUpdateParentTaskActionTest($oldParentTask)) && p('action') && e('activated');
$oldParentTask->status = 'closed';
r($task->createUpdateParentTaskActionTest($oldParentTask)) && p('action') && e('activated');
$oldParentTask->status = 'cancel';
r($task->createUpdateParentTaskActionTest($oldParentTask)) && p('action') && e('activated');

$oldParentTask->id     = 6;
$oldParentTask->status = 'done';
r($task->createUpdateParentTaskActionTest($oldParentTask)) && p('action') && e('adjusttasktowait');
$oldParentTask->status = 'pause';
r($task->createUpdateParentTaskActionTest($oldParentTask)) && p('action') && e('adjusttasktowait');
$oldParentTask->status = 'cancel';
r($task->createUpdateParentTaskActionTest($oldParentTask)) && p('action') && e('adjusttasktowait');
$oldParentTask->status = 'closed';
r($task->createUpdateParentTaskActionTest($oldParentTask)) && p('action') && e('adjusttasktowait');
$oldParentTask->status = 'cancel';
r($task->createUpdateParentTaskActionTest($oldParentTask)) && p('action') && e('adjusttasktowait');
