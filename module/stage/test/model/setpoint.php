#!/usr/bin/env php
<?php
/**

title=测试 stageModel->setPoint();
cid=1

- 测试修改评审点标题 @TR1 change
- 测试修改评审点的审批流 @2
- 测试修改评审点的顺序 @2
- 测试删除评审点 @1
- 测试添加评审点第6条的title属性 @test add point

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('stage')->loadYaml('stage')->gen(5);
zenData('user')->gen(5);

$decision = zenData('decision');
$decision->id->range('1-5');
$decision->workflowGroup->range('1-5');
$decision->stage->range('1-5');
$decision->order->range('1');
$decision->title->range('TR1,TR2,TR3,CDCP,PDCP');
$decision->type->range('TR{3},DCP{2}');
$decision->gen(5);

$approvalFlowObject = zenData('approvalflowobject');
$approvalFlowObject->id->range('1-5');
$approvalFlowObject->root->range('4');
$approvalFlowObject->flow->range('1');
$approvalFlowObject->objectID->range('1-5');
$approvalFlowObject->objectType->range('decision');
$approvalFlowObject->extra->range('review');
$approvalFlowObject->gen(5);

global $tester;
$stageTester = $tester->loadModel('stage');

$changeTitle = new stdClass();
$changeTitle->id    = 1;
$changeTitle->title = 'TR1 change';
$changeTitle->flow  = '1';
$stageTester->setPoint('TR', 1, array(1 => $changeTitle));
$result = $stageTester->dao->select('title')->from(TABLE_DECISION)->where('id')->eq(1)->fetch('title');
r($result) && p() && e('TR1 change');  //测试修改评审点标题

$changeFlow = new stdClass();
$changeFlow->id    = 2;
$changeFlow->title = 'TR2';
$changeFlow->flow  = 2;
$stageTester->setPoint('TR', 2, array(1 => $changeFlow));
$result = $stageTester->dao->select('flow')->from(TABLE_APPROVALFLOWOBJECT)->where('objectType')->eq('decision')->andWhere('objectID')->eq(2)->fetch('flow');
r($result) && p() && e('2'); //测试修改评审点的审批流

$changeOrder = new stdClass();
$changeOrder->id    = 3;
$changeOrder->title = 'TR3';
$changeOrder->flow  = 1;
$stageTester->setPoint('TR', 3, array(2 => $changeOrder));
$result = $stageTester->dao->select('`order`')->from(TABLE_DECISION)->where('id')->eq(3)->fetch('order');
r($result) && p() && e('2'); //测试修改评审点的顺序

$stageTester->setPoint('DCP', 4, array());
$result = $stageTester->dao->select('*')->from(TABLE_DECISION)->where('stage')->eq(4)->fetchAll('id');
r($result) && p('4:deleted') && e('1'); //测试删除评审点

$addPoint = new stdClass();
$addPoint->id    = 0;
$addPoint->title = 'test add point';
$addPoint->flow  = 1;
$stageTester->setPoint('DCP', 4, array(1 => $addPoint));
$result = $stageTester->dao->select('*')->from(TABLE_DECISION)->where('stage')->eq(4)->fetchAll('id');
r($result) && p('6:title') && e('test add point'); //测试添加评审点
