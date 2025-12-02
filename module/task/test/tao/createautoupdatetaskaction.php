#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';

/**

title=taskTao->createAutoUpdateTaskAction();
cid=18872

- 执行task模块的createAutoUpdateTaskActionTest方法，参数是$oldParentTask 属性action @adjusttasktowait
- 执行task模块的createAutoUpdateTaskActionTest方法，参数是$oldParentTask 属性action @adjusttasktowait
- 执行task模块的createAutoUpdateTaskActionTest方法，参数是$oldParentTask 属性action @adjusttasktowait
- 执行task模块的createAutoUpdateTaskActionTest方法，参数是$oldParentTask 属性action @adjusttasktowait
- 执行task模块的createAutoUpdateTaskActionTest方法，参数是$oldParentTask 属性action @adjusttasktowait
- 执行task模块的createAutoUpdateTaskActionTest方法，参数是$oldParentTask 属性action @activated
- 执行task模块的createAutoUpdateTaskActionTest方法，参数是$oldParentTask 属性action @restarted
- 执行task模块的createAutoUpdateTaskActionTest方法，参数是$oldParentTask 属性action @activated
- 执行task模块的createAutoUpdateTaskActionTest方法，参数是$oldParentTask 属性action @started
- 执行task模块的createAutoUpdateTaskActionTest方法，参数是$oldParentTask 属性action @activated
- 执行task模块的createAutoUpdateTaskActionTest方法，参数是$oldParentTask  @0
- 执行task模块的createAutoUpdateTaskActionTest方法，参数是$oldParentTask 属性action @finished
- 执行task模块的createAutoUpdateTaskActionTest方法，参数是$oldParentTask 属性action @finished
- 执行task模块的createAutoUpdateTaskActionTest方法，参数是$oldParentTask 属性action @finished
- 执行task模块的createAutoUpdateTaskActionTest方法，参数是$oldParentTask 属性action @finished
- 执行task模块的createAutoUpdateTaskActionTest方法，参数是$oldParentTask 属性action @canceled
- 执行task模块的createAutoUpdateTaskActionTest方法，参数是$oldParentTask 属性action @canceled
- 执行task模块的createAutoUpdateTaskActionTest方法，参数是$oldParentTask 属性action @canceled
- 执行task模块的createAutoUpdateTaskActionTest方法，参数是$oldParentTask 属性action @canceled
- 执行task模块的createAutoUpdateTaskActionTest方法，参数是$oldParentTask 属性action @canceled
- 执行task模块的createAutoUpdateTaskActionTest方法，参数是$oldParentTask 属性action @closed
- 执行task模块的createAutoUpdateTaskActionTest方法，参数是$oldParentTask 属性action @closed
- 执行task模块的createAutoUpdateTaskActionTest方法，参数是$oldParentTask 属性action @closed
- 执行task模块的createAutoUpdateTaskActionTest方法，参数是$oldParentTask 属性action @closed
- 执行task模块的createAutoUpdateTaskActionTest方法，参数是$oldParentTask 属性action @closed
- 执行task模块的createAutoUpdateTaskActionTest方法，参数是$oldParentTask 属性action @adjusttasktowait
- 执行task模块的createAutoUpdateTaskActionTest方法，参数是$oldParentTask 属性action @adjusttasktowait
- 执行task模块的createAutoUpdateTaskActionTest方法，参数是$oldParentTask 属性action @adjusttasktowait
- 执行task模块的createAutoUpdateTaskActionTest方法，参数是$oldParentTask 属性action @adjusttasktowait
- 执行task模块的createAutoUpdateTaskActionTest方法，参数是$oldParentTask 属性action @adjusttasktowait

*/

zenData('action')->gen(0);
zenData('user')->loadYaml('user')->gen(5);
zenData('project')->loadYaml('project')->gen(3);
su('user4');

$task = new taskTest();

zenData('task')->loadYaml('task')->gen(6);

$oldParentTask = new stdclass();

$oldParentTask->id     = 1;
$oldParentTask->status = 'closed';
r($task->createAutoUpdateTaskActionTest($oldParentTask)) && p('action') && e('adjusttasktowait');
$oldParentTask->status = 'pause';
r($task->createAutoUpdateTaskActionTest($oldParentTask)) && p('action') && e('adjusttasktowait');
$oldParentTask->status = 'cancel';
r($task->createAutoUpdateTaskActionTest($oldParentTask)) && p('action') && e('adjusttasktowait');
$oldParentTask->status = 'wait';
r($task->createAutoUpdateTaskActionTest($oldParentTask)) && p('action') && e('adjusttasktowait');
$oldParentTask->status = 'doing';
r($task->createAutoUpdateTaskActionTest($oldParentTask)) && p('action') && e('adjusttasktowait');

$oldParentTask->id     = 2;
$oldParentTask->status = 'done';
r($task->createAutoUpdateTaskActionTest($oldParentTask)) && p('action') && e('activated');
$oldParentTask->status = 'pause';
r($task->createAutoUpdateTaskActionTest($oldParentTask)) && p('action') && e('restarted');
$oldParentTask->status = 'cancel';
r($task->createAutoUpdateTaskActionTest($oldParentTask)) && p('action') && e('activated');
$oldParentTask->status = 'wait';
r($task->createAutoUpdateTaskActionTest($oldParentTask)) && p('action') && e('started');
$oldParentTask->status = 'doing';
r($task->createAutoUpdateTaskActionTest($oldParentTask)) && p('action') && e('activated');

$oldParentTask->id     = 3;
$oldParentTask->status = 'done';
r($task->createAutoUpdateTaskActionTest($oldParentTask)) && p()         && e('0');
$oldParentTask->status = 'cancel';
r($task->createAutoUpdateTaskActionTest($oldParentTask)) && p('action') && e('finished');
$oldParentTask->status = 'wait';
r($task->createAutoUpdateTaskActionTest($oldParentTask)) && p('action') && e('finished');
$oldParentTask->status = 'doing';
r($task->createAutoUpdateTaskActionTest($oldParentTask)) && p('action') && e('finished');
$oldParentTask->status = 'closed';
r($task->createAutoUpdateTaskActionTest($oldParentTask)) && p('action') && e('finished');

$oldParentTask->id     = 4;
$oldParentTask->status = 'done';
r($task->createAutoUpdateTaskActionTest($oldParentTask)) && p('action') && e('canceled');
$oldParentTask->status = 'wait';
r($task->createAutoUpdateTaskActionTest($oldParentTask)) && p('action') && e('canceled');
$oldParentTask->status = 'doing';
r($task->createAutoUpdateTaskActionTest($oldParentTask)) && p('action') && e('canceled');
$oldParentTask->status = 'closed';
r($task->createAutoUpdateTaskActionTest($oldParentTask)) && p('action') && e('canceled');
$oldParentTask->status = 'pause';
r($task->createAutoUpdateTaskActionTest($oldParentTask)) && p('action') && e('canceled');

$oldParentTask->id     = 5;
$oldParentTask->status = 'wait';
r($task->createAutoUpdateTaskActionTest($oldParentTask)) && p('action') && e('closed');
$oldParentTask->status = 'pause';
r($task->createAutoUpdateTaskActionTest($oldParentTask)) && p('action') && e('closed');
$oldParentTask->status = 'done';
r($task->createAutoUpdateTaskActionTest($oldParentTask)) && p('action') && e('closed');
$oldParentTask->status = 'closed';
r($task->createAutoUpdateTaskActionTest($oldParentTask)) && p('action') && e('closed');
$oldParentTask->status = 'cancel';
r($task->createAutoUpdateTaskActionTest($oldParentTask)) && p('action') && e('closed');

$oldParentTask->id     = 6;
$oldParentTask->status = 'done';
r($task->createAutoUpdateTaskActionTest($oldParentTask)) && p('action') && e('adjusttasktowait');
$oldParentTask->status = 'pause';
r($task->createAutoUpdateTaskActionTest($oldParentTask)) && p('action') && e('adjusttasktowait');
$oldParentTask->status = 'cancel';
r($task->createAutoUpdateTaskActionTest($oldParentTask)) && p('action') && e('adjusttasktowait');
$oldParentTask->status = 'closed';
r($task->createAutoUpdateTaskActionTest($oldParentTask)) && p('action') && e('adjusttasktowait');
$oldParentTask->status = 'cancel';
r($task->createAutoUpdateTaskActionTest($oldParentTask)) && p('action') && e('adjusttasktowait');
