#!/usr/bin/env php
<?php
/**

title=测试 projectModel->getGanttVersions();
timeout=0
cid=1

- 执行$versions @10
- 执行$versions[10]
 - 属性id @10
 - 属性reviewType @baseline
 - 属性version @版本号10
- 执行$versions[9]
 - 属性id @9
 - 属性reviewType @gantt
 - 属性type @taged
- 执行$versions[7]
 - 属性id @7
 - 属性reviewType @deliverable
 - 属性version @版本号7

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

$object = zenData('object');
$object->project->range('1');
$object->type->range('reviewed,taged,taged');
$object->category->range('1,1,0');
$object->status->range('pass,pass,gantt');
$object->gen(10);

$review = zenData('review');
$review->object->range('1-10');
$review->status->range('pass');
$review->type->range('deliverable,baseline');
$review->gen(10);

$deliverable = zenData('deliverable')->loadYaml('deliverable');
$deliverable->category->range('PP');
$deliverable->gen(1);
$projectDeliverable = zenData('projectdeliverable');
$projectDeliverable->id->range('1-10');
$projectDeliverable->deliverable->range('1');
$projectDeliverable->project->range('1');
$projectDeliverable->name->range('1-4');
$projectDeliverable->review->range('1-10');
$projectDeliverable->doc->range('1-10');
$projectDeliverable->createdBy->range('admin');
$projectDeliverable->gen(10);
su('admin');

global $tester;

$projectModel = $tester->loadModel('project');

$versions = $projectModel->getGanttVersions(1);
r(count($versions)) && p() && e('10');
r($versions[10]) && p('id,reviewType,version') && e('10,baseline,版本号10');
r($versions[9]) && p('id,reviewType,type') && e('9,gantt,taged');
r($versions[7]) && p('id,reviewType,version') && e('7,deliverable,版本号7');