#!/usr/bin/env php
<?php

/**

title=taskModel->afterSplitTask();
cid=18762

- 测试如果父任务有工时消耗时是否成功克隆子任务。属性9 @9
- 测试父任务状态是否改变。属性status @wait
- 测试父任务动态是否创建成功。属性actionID @4
- 测试父任务预计开始时间是否更新。 @2021-01-09
- 测试父任务截止时间是否更新。 @2021-01-23

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('project')->loadYaml('project')->gen(5);
zenData('task')->loadYaml('task')->gen(8);
zenData('action')->loadYaml('action')->gen(1);

$parentIdList = array(1, 3);

$task = new taskModelTest();

r($task->afterSplitTaskTest($parentIdList[0], array(2), 'children'))         && p('9')        && e('9');          // 测试如果父任务有工时消耗时是否成功克隆子任务。
r($task->afterSplitTaskTest($parentIdList[0], array(2), 'parent'))           && p('status')   && e('wait');       // 测试父任务状态是否改变。
r($task->afterSplitTaskTest($parentIdList[1], array(4), 'parentAction'))     && p('actionID') && e('4');          // 测试父任务动态是否创建成功。
r($task->afterSplitTaskTest($parentIdList[1], array(4), 'parentEstStarted')) && p()           && e('2021-01-09'); // 测试父任务预计开始时间是否更新。
r($task->afterSplitTaskTest($parentIdList[1], array(4), 'parentDeadline'))   && p()           && e('2021-01-23'); // 测试父任务截止时间是否更新。
