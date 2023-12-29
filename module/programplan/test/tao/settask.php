#!/usr/bin/env php
<?php

/**

title=测试 loadModel->setTask()
cid=0

- 传入空数据，检查datas数据。 @0
- 传入空数据，检查stageIndex数据。 @0
- 传入空任务，检查data数据数。 @10
- 传入任务， selectCustom为空，检查data数据数。 @10
- 传入正常数据，检查 data数据数量。 @30
- 传入正常数据，检查 data数据的第一条信息。
 - 属性id @1-1
 - 属性type @task
 - 属性start_date @28-09-2023
 - 属性bar_height @24
- 传入正常数据，检查 stageIndex的第一条信息。
 - 第1条的planID属性 @1
 - 第1条的parent属性 @0
 - 第1条的totalEstimate属性 @10
 - 第1条的totalConsumed属性 @6
 - 第1条的totalReal属性 @6

*/

include dirname(__FILE__, 5). '/test/lib/init.php';
su('admin');

$project = zdTable('project');
$project->type->range('stage');
$project->begin->range('`2023-09-28`');
$project->end->range('`2024-04-02`');
$project->gen(10);
$task = zdTable('task');
$task->execution->range('1-10');
$task->estStarted->range('`2023-09-28`');
$task->realStarted->range('`2023-09-28`');
$task->deadline->range('`2024-04-02`');
$task->gen(20);
zdTable('team')->gen(20);

global $tester;
$tester->loadModel('programplan');

$plans = $tester->programplan->dao->select('*')->from(TABLE_PROJECT)->where('type')->eq('stage')->fetchAll('id');
$tasks = $tester->programplan->dao->select('*')->from(TABLE_TASK)->where('execution')->in(array_keys($plans))->fetchAll('id');
$datas = $stageIndex = $planIdList = $reviewDeadline = array();

$nullResult = $tester->programplan->setTask(array(), $plans, '', array(), array());
r(count($nullResult['datas']))          && p() && e('0'); //传入空数据，检查datas数据。
r(count($nullResult['stageIndex']))     && p() && e('0'); //传入空数据，检查stageIndex数据。

$planResult = $tester->programplan->initGanttPlans($plans);
$datas      = $planResult['datas'];
$stageIndex = $planResult['stageIndex'];

$noTaskResult = $tester->programplan->setTask(array(), $plans, 'task', $datas, $stageIndex);
r(count($noTaskResult['datas']['data'])) && p() && e('10'); //传入空任务，检查data数据数。

$noTaskResult = $tester->programplan->setTask($tasks, $plans, '', $datas, $stageIndex);
r(count($noTaskResult['datas']['data'])) && p() && e('10'); //传入任务， selectCustom为空，检查data数据数。

$normalResult = $tester->programplan->setTask($tasks, $plans, 'task', $datas, $stageIndex);
$datas      = $normalResult['datas'];
$stageIndex = $normalResult['stageIndex'];
r(count($normalResult['datas']['data']))     && p()                                                        && e('30');                     //传入正常数据，检查 data数据数量。
r($normalResult['datas']['data']['1-1'])     && p('id,type,start_date,bar_height')                         && e('1-1,task,28-09-2023,24'); //传入正常数据，检查 data数据的第一条信息。
r($normalResult['stageIndex'])               && p('1:planID,parent,totalEstimate,totalConsumed,totalReal') && e('1,0,10,6,6');             //传入正常数据，检查 stageIndex的第一条信息。
