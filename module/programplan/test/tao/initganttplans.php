#!/usr/bin/env php
<?php

/**

title=测试 loadModel->initGanttPlans()
cid=0

*/

include dirname(__FILE__, 5). '/test/lib/init.php';
su('admin');

$project = zdTable('project');
$project->type->range('stage');
$project->begin->range('`2023-09-28`');
$project->end->range('`2024-04-02`');
$project->gen(10);

global $tester;
$tester->loadModel('programplan');

$plans = $tester->programplan->dao->select('*')->from(TABLE_PROJECT)->where('type')->eq('stage')->fetchAll('id');
$datas = $stageIndex = $planIdList = $reviewDeadline = array();

$nullResult = $tester->programplan->initGanttPlans(array());
r(count($nullResult['datas']))          && p() && e('0'); //传入空数据，检查datas数据。
r(count($nullResult['stageIndex']))     && p() && e('0'); //传入空数据，检查stageIndex数据。
r(count($nullResult['planIdList']))     && p() && e('0'); //传入空数据，检查planIdList数据。
r(count($nullResult['reviewDeadline'])) && p() && e('0'); //传入空数据，检查reviewDeadline数据。

$normalResult = $tester->programplan->initGanttPlans($plans);
r(count($normalResult['datas']['data']))     && p()                                                        && e('10');                   //传入正常数据，检查 data 数据数量。
r($normalResult['datas']['data'])            && p('1:id,type,start_date,bar_height')                       && e('1,plan,28-09-2023,24'); //传入正常数据，检查 data数据的第一条信息。
r($normalResult['stageIndex'])               && p('1:planID,parent,totalEstimate,totalConsumed,totalReal') && e('1,0,0,0,0');            //传入正常数据，检查 stageIndex的第一条信息。
r(count($normalResult['planIdList']))        && p()                                                        && e('10');                   //传入正常数据，检查 planIdList 数据数量。
r($normalResult['reviewDeadline'])           && p('1:stageEnd')                                            && e('2024-04-02');           //传入正常数据，检查 reviewDeadline的第一条数据。
