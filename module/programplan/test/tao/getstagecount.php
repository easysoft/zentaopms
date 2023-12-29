#!/usr/bin/env php
<?php

/**

title=测试 programplanTao->getStageCount();
cid=0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$project = zdTable('project');
$project->parent->range('0,1{100}');
$project->type->range('project,stage{100}');
$project->milestone->range('0{3},1{100}');
$project->gen(5);

global $tester;
$tester->loadModel('programplan');

r($tester->programplan->getStageCount(0))              && p() && e('0');  //传入空的planID。
r($tester->programplan->getStageCount(10))             && p() && e('0');  //传入不存在的planID。
r($tester->programplan->getStageCount(1))              && p() && e('4');  //传入planID=1。
r($tester->programplan->getStageCount(1, 'milestone')) && p() && e('2');  //传入planID=1, 并且是里程碑。
