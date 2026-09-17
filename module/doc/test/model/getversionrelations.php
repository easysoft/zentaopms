#!/usr/bin/env php
<?php

/**

title=测试 docModel->getVersionRelations();
timeout=0
cid=0

- 开源版不返回版本关系 @0
- 文档ID为空时不返回版本关系 @0
- 版本号为空时不返回版本关系 @0
- 指定版本返回全部交付物与基线关系 @4
- 同一交付物可以关联多个基线 @2
- 交付物返回类型 @D1
- 交付物使用评审版本 @R1
- 交付物返回基线标题 @Baseline-A
- 交付物返回基线版本 @B1
- 评审版本为空时返回项目评审ID @12
- 评审版本为空时回退到交付物版本 @PD2
- 评审版本为空时返回基线ID @11
- 评审版本为空时返回基线标题 @Baseline-B
- 已删除交付物和不匹配的基线不返回 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$review = zenData('review');
$review->id->range('11-15');
$review->version->range('R1,R2,R3,R4,R5');
$review->isBaseline->range('0,1,0,0,0');
$review->gen(5);

$projectDeliverable = zenData('projectdeliverable');
$projectDeliverable->id->range('1-7');
$projectDeliverable->project->range('101,101,101,102,101,101,101');
$projectDeliverable->deliverable->range('1-7');
$projectDeliverable->name->range('PD1,PD2,PD3,PD4,PD5,PD6,PD7');
$projectDeliverable->doc->range('10,10,10,10,10,11,10');
$projectDeliverable->docVersion->range('1,1,1,1,2,1,1');
$projectDeliverable->review->range('11,12,13,14,11,11,15');
$projectDeliverable->version->range('PD1,PD2,PD3,PD4,PD5,PD6,PD7');
$projectDeliverable->submittedBy->range('admin');
$projectDeliverable->gen(7);

global $tester;
$tester->dao->update(TABLE_REVIEW)->set('version')->eq('')->where('id')->eq(12)->exec();
$tester->dao->update(TABLE_PROJECTDELIVERABLE)->set('submittedBy')->eq('')->where('id')->in('2,3')->exec();

$deliverable = zenData('deliverable');
$deliverable->id->range('1-7');
$deliverable->name->range('D1,D2,D3,D4,D5,D6,D7');
$deliverable->deleted->range('0,0,0,0,0,0,1');
$deliverable->gen(7);

$object = zenData('object');
$object->id->range('10-15');
$object->project->range('101,101,102,101,101,101');
$object->title->range('Baseline-A,Baseline-B,Baseline-C,Baseline-D,Baseline-E,Baseline-F');
$object->version->range('B1,B2,B3,B4,B5,B6');
$object->categoryVersion->range('`{"1":11}`,`{"1":11,"2":12}`,`{"4":14}`,`{"1":99}`,`{"1":11}`,`{"1":11}`');
$object->type->range('taged');
$object->status->range('pass,pass,pass,pass,wait,pass');
$object->deleted->range('0,0,0,0,0,1');
$object->gen(6);

su('admin');

global $config;
$config->edition = 'open';

$doc = new docModelTest();
r(count($doc->getVersionRelationsTest(10, 1))) && p() && e('0'); // 开源版不返回版本关系

$config->edition = 'ipd';
r(count($doc->getVersionRelationsTest(0, 1)))  && p() && e('0'); // 文档ID为空时不返回版本关系
r(count($doc->getVersionRelationsTest(10, 0))) && p() && e('0'); // 版本号为空时不返回版本关系

$relations = $doc->getVersionRelationsTest(10, 1);
$grouped   = array();
foreach($relations as $relation) $grouped[$relation->deliverableID][] = $relation;

r(count($relations))    && p() && e('4'); // 指定版本返回全部交付物与基线关系
r(count($grouped[1]))   && p() && e('2'); // 同一交付物可以关联多个基线

$deliverable1Baselines = array();
foreach($grouped[1] as $relation) $deliverable1Baselines[$relation->baselineID] = $relation;
r($deliverable1Baselines[10]) && p('deliverableType') && e('D1'); // 交付物返回类型
r($deliverable1Baselines[10]) && p('deliverableVersion') && e('R1'); // 交付物使用评审版本
r($deliverable1Baselines[10]) && p('baseline') && e('Baseline-A'); // 交付物返回基线标题
r($deliverable1Baselines[10]) && p('baselineVersion') && e('B1'); // 交付物返回基线版本
r($grouped[2][0]) && p('reviewID') && e('12'); // 评审版本为空时返回项目评审ID
r($grouped[2][0]) && p('deliverableVersion') && e('PD2'); // 评审版本为空时回退到交付物版本
r($grouped[2][0]) && p('baselineID') && e('11'); // 评审版本为空时返回基线ID
r($grouped[2][0]) && p('baseline') && e('Baseline-B'); // 评审版本为空时返回基线标题
r(isset($grouped[3]) || isset($grouped[7])) && p() && e('0'); // 已删除交付物和不匹配的基线不返回
