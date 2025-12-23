#!/usr/bin/env php
<?php
/**

title=测试 projectModel::hasFrozenObject();
timeout=0
cid=0

- 检查传空值 @0
- 检查是否有冻结的需求 @1
- 检查是否有冻结的阶段 @1
- 检查是否有冻结的概要设计 @1
- 检查是否有冻结的详细设计 @1
- 检查是否有冻结的数据库设计 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';

$projectDeliverable = zenData('projectdeliverable');
$projectDeliverable->id->range('1-5');
$projectDeliverable->name->range('p1,p2,p3,p4,p5');
$projectDeliverable->project->range('1-5');
$projectDeliverable->deliverable->range('1-5');
$projectDeliverable->doc->range('1-5');
$projectDeliverable->docVersion->range('1');
$projectDeliverable->review->range('1-5');
$projectDeliverable->frozen->range('yes');
$projectDeliverable->gen(5);

$deliverable = zenData('deliverable');
$deliverable->id->range('1-5');
$deliverable->category->range('SRS,PP,HLDS,DDS,DBDS');
$deliverable->gen(5);

su('admin');

global $tester;
$projectModel = $tester->loadModel('project');
r($projectModel->hasFrozenObject(0, ''))        && p() && e('0'); // 检查传空值
r($projectModel->hasFrozenObject(1, 'SRS'))     && p() && e('1'); // 检查是否有冻结的需求
r($projectModel->hasFrozenObject(2, 'PP'))      && p() && e('1'); // 检查是否有冻结的阶段
r($projectModel->hasFrozenObject(3, 'HLDS'))    && p() && e('1'); // 检查是否有冻结的概要设计
r($projectModel->hasFrozenObject(4, 'DDS'))     && p() && e('1'); // 检查是否有冻结的详细设计
r($projectModel->hasFrozenObject(5, 'DBDS'))    && p() && e('1'); // 检查是否有冻结的数据库设计
