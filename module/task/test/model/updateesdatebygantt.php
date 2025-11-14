#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';
su('admin');

zenData('project')->loadYaml('project', true)->gen(5)->fixPath();
zenData('task')->loadYaml('task', true)->gen(10)->fixPath();

/**

title=taskModel->updateEsDateByGantt();
timeout=0
cid=18850

- 测试检查阶段开始日期 @已超出项目计划开始时间，请先修改项目计划开始时间
- 测试检查阶段结束日期 @已超出项目计划结束时间，请先修改项目计划结束时间
- 测试更新阶段日期 @1
- 测试检查任务开始日期 @1
- 测试检查任务结束日期 @1
- 测试更新任务日期 @1

*/

$taskTester = new taskTest();

$executionID = 3;
$taskID      = 1;
$beginList   = array('2020-10-01', '2020-12-01');
$endList     = array('2022-11-01', '2022-01-01');

r($taskTester->updateEsDateByGanttTest($executionID, $beginList[0], $endList[0], 'plan')) && p('0') && e('已超出项目计划开始时间，请先修改项目计划开始时间'); // 测试检查阶段开始日期
r($taskTester->updateEsDateByGanttTest($executionID, $beginList[1], $endList[0], 'plan')) && p('0') && e('已超出项目计划结束时间，请先修改项目计划结束时间'); // 测试检查阶段结束日期
r($taskTester->updateEsDateByGanttTest($executionID, $beginList[1], $endList[1], 'plan')) && p()    && e('1');                                                // 测试更新阶段日期

r($taskTester->updateEsDateByGanttTest($taskID, $beginList[0], $endList[0], 'task')) && p('0') && e('1'); // 测试检查任务开始日期
r($taskTester->updateEsDateByGanttTest($taskID, $beginList[1], $endList[0], 'task')) && p('0') && e('1'); // 测试检查任务结束日期
r($taskTester->updateEsDateByGanttTest($taskID, $beginList[1], $endList[1], 'task')) && p()    && e('1'); // 测试更新任务日期
