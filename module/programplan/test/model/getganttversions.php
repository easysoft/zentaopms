#!/usr/bin/env php
<?php
/**

title=测试 programplanModel->getGanttVersions();
timeout=0
cid=1

- 执行$versions @6
- 执行$versions[1]
 - 属性id @1
 - 属性reviewType @deliverable
 - 属性version @版本号1
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
$object->category->range('1,1,gantt');
$object->categoryVersion->range('`{"1":1}`,`{"1":2}`,[]');
$object->status->range('pass,pass,gantt');
$object->gen(10);

$review = zenData('review');
$review->object->range('1-10');
$review->status->range('pass');
$review->deliverable->range('1');
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

$programplanModel = $tester->loadModel('programplan');

$versions = $programplanModel->getGanttVersions(1, 0, 'gantt');
r(count($versions)) && p() && e('6');
r($versions[1]) && p('id,reviewType,version') && e('1,deliverable,版本号1');
r($versions[9]) && p('id,reviewType,type') && e('9,gantt,taged');
r($versions[7]) && p('id,reviewType,version') && e('7,deliverable,版本号7');
