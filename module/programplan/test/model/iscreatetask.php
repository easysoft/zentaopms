#!/usr/bin/env php
<?php
/**

title=测试 programplanModel->isCreateTask();
cid=0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/programplan.class.php';
su('admin');

zdTable('project')->config('project')->gen(5);
zdTable('project')->config('stage')->gen(5, $isClear = false);

$task = zdTable('task');
$task->execution->range('1-5');
$task->gen(10);

$programplan = new programplanTest();
r($programplan->isCreateTaskTest(-1)) && p() && e('1'); // 测试 id 为 -1 的阶段下是否创建了任务，结果为 1
r($programplan->isCreateTaskTest(0))  && p() && e('1'); // 测试 id 为 0 的阶段下是否创建了任务，结果为 1
r($programplan->isCreateTaskTest(1))  && p() && e('0'); // 测试 id 为 1 的阶段下是否创建了任务，结果为 0
r($programplan->isCreateTaskTest(6))  && p() && e('1'); // 测试 id 为 6 的阶段下是否创建了任务，结果为 0
