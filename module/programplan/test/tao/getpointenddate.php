#!/usr/bin/env php
<?php

/**

title=测试 loadModel->getPointEndDate()
cid=0

*/

include dirname(__FILE__, 5). '/test/lib/init.php';
su('admin');

$project = zdTable('project');
$project->parent->range('0,1{100}');
$project->type->range('project,stage{100}');
$project->milestone->range('0{3},1{100}');
$project->gen(5);

global $tester;
$tester->loadModel('programplan');

$point = new stdclass();
$point->end      = '2023-09-28';
$point->category = '';

$planID = 1;

$reviewDeadline = array();
$reviewDeadline[1]['stageEnd'] = '2023-12-28';

r($tester->programplan->getPointEndDate($planID, $point, array()))         && p() && e('2023-09-28');             //传入point->end不为空

$point->end = null;
r($tester->programplan->getPointEndDate($planID, $point, $reviewDeadline)) && p() && e('2023-12-28');             //传入point->end为空

$point->category = 'DCP';
r($tester->programplan->getPointEndDate($planID, $point, $reviewDeadline)) && p() && e('2023-12-26');             //传入point->category为DCP

$point->category = 'TR';
r($tester->programplan->getPointEndDate($planID, $point, $reviewDeadline)) && p() && e('2023-12-21');             //传入point->category为TR

$reviewDeadline[1]['taskEnd'] = '2023-12-18';
r($tester->programplan->getPointEndDate($planID, $point, $reviewDeadline)) && p() && e('2023-12-18');             //传入reviewDeadline含有taskEnd数据
