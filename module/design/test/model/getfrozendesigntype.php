#!/usr/bin/env php
<?php
/**

title=测试 designModel->getFrozenDesignType();
cid=0

- 获取冻结的概要设计属性1 @1
- 获取冻结的详细设计属性2 @2
- 获取冻结的数据库设计属性3 @3
- 获取冻结的接口设计属性4 @4
- 获取冻结的概要设计属性5 @5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

$projectDeliverable = zenData('projectdeliverable');
$projectDeliverable->id->range('1-5');
$projectDeliverable->name->range('p1,p2,p3,p4,p5');
$projectDeliverable->project->range('1-5');
$projectDeliverable->deliverable->range('1-5');
$projectDeliverable->doc->range('0');
$projectDeliverable->docVersion->range('0');
$projectDeliverable->review->range('1-5');
$projectDeliverable->frozen->range('yes');
$projectDeliverable->gen(5);

$deliverable = zenData('deliverable');
$deliverable->id->range('1-5');
$deliverable->category->range('HLDS,DDS,DBDS,ADS');
$deliverable->gen(5);

global $tester;
$designModel = $tester->loadModel('design');
r($designModel->getFrozenDesignType(1)) && p('1') && e('1'); // 获取冻结的概要设计
r($designModel->getFrozenDesignType(2)) && p('2') && e('2'); // 获取冻结的详细设计
r($designModel->getFrozenDesignType(3)) && p('3') && e('3'); // 获取冻结的数据库设计
r($designModel->getFrozenDesignType(4)) && p('4') && e('4'); // 获取冻结的接口设计
r($designModel->getFrozenDesignType(5)) && p('5') && e('5'); // 获取冻结的概要设计
