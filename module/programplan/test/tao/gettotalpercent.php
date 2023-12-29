#!/usr/bin/env php
<?php

/**

title=测试 programplanTao->getTotalPercent();
cid=0

- 统计普通阶段下百分比。 @70
- 统计含有子阶段百分比。 @80
- 统计子阶段百分比。 @100
- 统计只有一个阶段百分比。 @0
- 统计父阶段下百分比。 @90

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

$project = zdTable('project');
$project->project->range('0,0,1{7},2{10}');
$project->parent->range('0,0,1{4},3{3},2');
$project->type->range('project,project,stage{100}');
$project->milestone->range('0{3},1{2},0{10}');
$project->percent->range('90,10,20,30,40,10,20,30,40,10,20,30');
$project->gen(10)->fixPath();

$projectProduct = zdTable('projectproduct');
$projectProduct->project->range('2-20');
$projectProduct->product->range('3');
$projectProduct->gen(10);

global $tester;
$tester->loadModel('programplan');
$tester->programplan->app->user->admin = true;

r($tester->programplan->getTotalPercent($tester->programplan->getById(4)))       && p() && e('70');  //统计普通阶段下百分比。
r($tester->programplan->getTotalPercent($tester->programplan->getById(3)))       && p() && e('80');  //统计含有子阶段百分比。
r($tester->programplan->getTotalPercent($tester->programplan->getById(7)))       && p() && e('100'); //统计子阶段百分比。
r($tester->programplan->getTotalPercent($tester->programplan->getById(10)))      && p() && e('0');   //统计只有一个阶段百分比。
r($tester->programplan->getTotalPercent($tester->programplan->getById(3), true)) && p() && e('90');  //统计父阶段下百分比。
