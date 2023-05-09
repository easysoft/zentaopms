#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/task.class.php';
su('admin');

zdTable('task')->config('task')->gen(8);
zdTable('action')->config('action')->gen(1);

/**

title=taskModel->updateKanban4BatchCreate();
timeout=0
cid=2

*/

$parentIdList = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');

$task = new taskTest();

r($task->afterSplitTaskTest($parentIdList[1], '2', 'children'))         && p('9')        && e('9');          // 测试如果父任务有工时消耗时是否成功克隆子任务。
r($task->afterSplitTaskTest($parentIdList[1], '2', 'parent'))           && p('status')   && e('doing');      // 测试父任务状态是否改变。
r($task->afterSplitTaskTest($parentIdList[3], '4', 'parentAction'))     && p('actionID') && e('5');          // 测试父任务动态是否创建成功。
r($task->afterSplitTaskTest($parentIdList[3], '4', 'parentEstStarted')) && p()           && e('2021-01-09'); // 测试父任务预计开始时间是否更新。
r($task->afterSplitTaskTest($parentIdList[3], '4', 'parentDeadline'))   && p()           && e('2021-01-23'); // 测试父任务截止时间是否更新。
