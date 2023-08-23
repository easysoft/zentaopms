#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/task.class.php';
su('admin');

zdTable('project')->config('project', true)->gen(5);
zdTable('task')->config('task', true)->gen(10);

/**

title=taskModel->updateEsDateByGantt();
timeout=0
cid=1

*/

$taskTester = new taskTest();

$executionID = 3;
$taskID      = 1;
$beginList   = array('2020-10-01', '2020-12-01');
$endList     = array('2022-11-01', '2022-01-01');

r($taskTester->updateEsDateByGanttTest($executionID, $beginList[0], $endList[0], 'plan')) && p('0') && e('已超出项目计划开始时间，请先修改项目计划开始时间'); // 测试检查阶段开始日期
r($taskTester->updateEsDateByGanttTest($executionID, $beginList[1], $endList[0], 'plan')) && p('0') && e('已超出项目计划结束时间，请先修改项目计划结束时间'); // 测试检查阶段结束日期
r($taskTester->updateEsDateByGanttTest($executionID, $beginList[1], $endList[1], 'plan')) && p()    && e('1');                                                // 测试更新阶段日期

r($taskTester->updateEsDateByGanttTest($taskID, $beginList[0], $endList[0], 'task')) && p('0') && e('已超出阶段计划开始时间，请先修改阶段计划开始时间'); // 测试检查任务开始日期
r($taskTester->updateEsDateByGanttTest($taskID, $beginList[1], $endList[0], 'task')) && p('0') && e('已超出阶段计划结束时间，请先修改阶段计划结束时间'); // 测试检查任务结束日期
r($taskTester->updateEsDateByGanttTest($taskID, $beginList[1], $endList[1], 'task')) && p()    && e('1');                                                // 测试更新任务日期
