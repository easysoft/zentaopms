#!/usr/bin/env php
<?php

/**

title=测试 loadModel->buildGanttData4IPD()
cid=0

*/

include dirname(__FILE__, 5). '/test/lib/init.php';
su('admin');

zdTable('review')->gen(20);
zdTable('object')->gen(20);
$project = zdTable('project');
$project->type->range('stage');
$project->attribute->range('devel');
$project->begin->range('`2023-09-28`');
$project->end->range('`2024-04-02`');
$project->gen(10);

global $tester;
$tester->loadModel('programplan');

$tester->programplan->app->loadConfig('stage');
$tester->programplan->config->stage->ipdReviewPoint = new stdclass();
$tester->programplan->config->stage->ipdReviewPoint->devel = array('PP');

$plans = $tester->programplan->dao->select('*')->from(TABLE_PROJECT)->where('type')->eq('stage')->fetchAll('id');
$plans = $tester->programplan->processPlans($plans);

$normalResult = $tester->programplan->initGanttPlans($plans);

$tester->programplan->config->edition = 'open';
$datas = $tester->programplan->buildGanttData4IPD($normalResult['datas'], 1, 1, 'point', $normalResult['reviewDeadline']);
r(isset($datas['data']['1-PP-1'])) && p() && e("0"); //禅道版本为开源版。

$tester->programplan->config->edition = 'ipd';
$datas = $tester->programplan->buildGanttData4IPD($normalResult['datas'], 1, 1, 'point', $normalResult['reviewDeadline']);
r(count($datas['data']))    && p()                        && e('20');                                                               //gantt数据数。
r($datas['data']['1-PP-1']) && p('id,reviewID,type,text') && e("1-PP-1,1,point,<i class='icon-seal'></i> 这个是评审或基线的标题1"); //禅道版本为IPD。

$datas = $tester->programplan->buildGanttData4IPD($normalResult['datas'], 1, 1, '', $normalResult['reviewDeadline']);
r(isset($datas['data']['1-PP-1'])) && p() && e("0"); //selectCustom 不包含 point。
