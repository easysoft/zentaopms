#!/usr/bin/env php
<?php

/**

title=测试 loadModel->getPointEndDate()
cid=17770

- 传入point->end不为空 @2023-09-28
- 传入point->end为空 @2023-12-21
- 传入point->category为DCP @2023-12-26
- 传入point->category为TR @2023-12-21
- 传入reviewDeadline含有taskEnd数据 @2023-12-18

*/

include dirname(__FILE__, 5). '/test/lib/init.php';
su('admin');

$project = zenData('project');
$project->parent->range('0,1{100}');
$project->type->range('project,stage{100}');
$project->milestone->range('0{3},1{100}');
$project->gen(5);

$decision = zenData('decision');
$decision->id->range('1-2');
$decision->type->range('TR,DCP');
$decision->gen(2);

global $tester;
$tester->loadModel('programplan');

$point = new stdclass();
$point->end      = '2023-09-28';
$point->category = '';

$planID = 1;

$reviewDeadline = array();
$reviewDeadline[1]['stageBegin'] = '2023-01-01';
$reviewDeadline[1]['stageEnd']   = '2023-12-28';

r($tester->programplan->getPointEndDate($planID, $point, array()))         && p() && e('2023-09-28');             //传入point->end不为空

$point->end = null;
$point->id  = 1;
r($tester->programplan->getPointEndDate($planID, $point, $reviewDeadline)) && p() && e('2023-12-21');             //传入point->end为空

$point->id = 2;
r($tester->programplan->getPointEndDate($planID, $point, $reviewDeadline)) && p() && e('2023-12-26');             //传入point->category为DCP

$point->id = 1;
r($tester->programplan->getPointEndDate($planID, $point, $reviewDeadline)) && p() && e('2023-12-21');             //传入point->category为TR

$reviewDeadline[1]['taskEnd'] = '2023-12-18';
r($tester->programplan->getPointEndDate($planID, $point, $reviewDeadline)) && p() && e('2023-12-18');             //传入reviewDeadline含有taskEnd数据
