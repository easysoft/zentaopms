#!/usr/bin/env php
<?php

/**

title=测试 loadModel->buildPlanDataForGantt()
cid=0

*/

include dirname(__FILE__, 5). '/test/lib/init.php';
su('admin');

$project = zdTable('project');
$project->type->range('stage');
$project->begin->range('`2023-09-28`');
$project->percent->range('10');
$project->gen(1);

global $tester;
$tester->loadModel('programplan');
$tester->programplan->config->setPercent = false;

$plan = $tester->programplan->getById('1');
$plan->isParent = false;

$planItem = $tester->programplan->buildPlanDataForGantt($plan);
r((array)$planItem) && p('id,type,text,start_date') && e("1,plan,项目集1,28-09-2023"); //检查普通阶段，构建Gantt数据。
r(isset($planItem->percent)) && p() && e('0'); //检查是否存在percent字段

$tester->programplan->config->setPercent = true;
$plan->milestone = true;
r((array)$tester->programplan->buildPlanDataForGantt($plan)) && p('id,type,text,start_date,percent') && e("1,plan,项目集1<icon class='icon icon-flag icon-sm red'></icon>,28-09-2023,10"); //里程碑阶段，并设置显示percent，检查构建数据。
