#!/usr/bin/env php
<?php

/**

title=测试 programplanModel->deleteExtraStageAndTask();
cid=0

- 删除多余阶段和任务：验证result字段 @1
- 删除多余阶段和任务：验证stageCount字段 @2
- 删除多余阶段和任务：验证taskCount字段 @2
- 删除多余阶段和任务：验证第一条阶段记录的deleted字段 @1
- 删除多余阶段和任务：验证第一条阶段记录的id字段 @1
- 删除多余阶段和任务：验证第二条阶段记录的deleted字段 @1
- 删除多余阶段和任务：验证第二条阶段记录的id字段 @2

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

$project = zenData('project');
$project->id->range('1-3');
$project->type->range('stage{3}');
$project->deleted->range('0{3}');
$project->gen(3);

$task = zenData('task');
$task->id->range('1-3');
$task->execution->range('1{3}');
$task->name->range('任务A,任务B,任务C');
$task->status->range('wait{3}');
$task->deleted->range('0{3}');
$task->gen(3);

$programplan = new programplanModelTest();

$result = $programplan->deleteExtraStageAndTaskTest(array(1, 2), array(1, 2));
r($result) && p('result')     && e('1'); // 删除多余阶段和任务：验证result字段
r($result) && p('stageCount') && e('2'); // 删除多余阶段和任务：验证stageCount字段
r($result) && p('taskCount')  && e('2'); // 删除多余阶段和任务：验证taskCount字段
r($result) && p('0:deleted')  && e('1'); // 删除多余阶段和任务：验证第一条阶段记录的deleted字段
r($result) && p('0:id')       && e('1'); // 删除多余阶段和任务：验证第一条阶段记录的id字段
r($result) && p('1:deleted')  && e('1'); // 删除多余阶段和任务：验证第二条阶段记录的deleted字段
r($result) && p('1:id')       && e('2'); // 删除多余阶段和任务：验证第二条阶段记录的id字段
