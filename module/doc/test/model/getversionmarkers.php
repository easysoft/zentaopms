#!/usr/bin/env php
<?php

/**

title=测试 docModel->getVersionMarkers();
timeout=0
cid=0

- 开源版不返回版本标记 @0
- 文档ID为空时不返回版本标记 @0
- 返回有标记的版本数量 @2
- 同版本包含交付物标记 @1
- 同版本包含基线标记 @1
- 第二版本包含交付物标记 @1
- 第二版本包含基线标记 @1
- 版本号为空的数据不生成标记 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$review = zenData('review');
$review->id->range('1-3');
$review->isBaseline->range('0,1,1');
$review->gen(3);

$projectDeliverable = zenData('projectdeliverable');
$projectDeliverable->id->range('1-6');
$projectDeliverable->project->range('1');
$projectDeliverable->deliverable->range('1-6');
$projectDeliverable->name->range('D1,D2,D3,D4,D5,D6');
$projectDeliverable->doc->range('10,10,10,10,10,11');
$projectDeliverable->docVersion->range('1,1,1,2,0,1');
$projectDeliverable->review->range('1,2,3,3,1,1');
$projectDeliverable->submittedBy->range('admin');
$projectDeliverable->gen(6);

global $tester;
$tester->dao->update(TABLE_PROJECTDELIVERABLE)->set('submittedBy')->eq('')->where('id')->eq(2)->exec();

su('admin');

global $config;
$config->edition = 'open';

$doc = new docModelTest();
r(count($doc->getVersionMarkersTest(10))) && p() && e('0'); // 开源版不返回版本标记

$config->edition = 'ipd';
r(count($doc->getVersionMarkersTest(0))) && p() && e('0'); // 文档ID为空时不返回版本标记

$markers = $doc->getVersionMarkersTest(10);
r(count($markers)) && p() && e('2'); // 返回有标记的版本数量
r($markers) && p('1:deliverable') && e('1'); // 同版本包含交付物标记
r($markers) && p('1:baseline') && e('1'); // 同版本包含基线标记
r($markers) && p('2:deliverable') && e('1'); // 第二版本包含交付物标记
r($markers) && p('2:baseline') && e('1'); // 第二版本包含基线标记
r(isset($markers[0])) && p() && e('0'); // 版本号为空的数据不生成标记
