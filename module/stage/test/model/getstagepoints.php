#!/usr/bin/env php
<?php

/**

title=测试 stageModel->getStagePoints();
timeout=0
cid=1

- 获取阶段1的TR评审点
 - 第1条的stage属性 @1
 - 第1条的title属性 @TR1
- 获取阶段2的TR评审点
 - 第2条的stage属性 @2
 - 第2条的title属性 @TR2
- 获取阶段2的TR评审点
 - 第3条的stage属性 @3
 - 第3条的title属性 @TR3
- 获取阶段4的DCP评审点
 - 第4条的stage属性 @4
 - 第4条的title属性 @CDCP
- 获取阶段5的DCP评审点
 - 第5条的stage属性 @5
 - 第5条的title属性 @PDCP

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/stage.unittest.class.php';

zenData('stage')->loadYaml('stage')->gen(5);
zenData('user')->gen(5);

$decision = zenData('decision');
$decision->id->range('1-5');
$decision->workflowGroup->range('1-5');
$decision->stage->range('1-5');
$decision->title->range('TR1,TR2,TR3,CDCP,PDCP');
$decision->type->range('TR{3},DCP{2}');
$decision->gen(5);

$approvalFlowObject = zenData('approvalflowobject');
$approvalFlowObject->id->range('1-5');
$approvalFlowObject->root->range('4');
$approvalFlowObject->objectID->range('1-5');
$approvalFlowObject->objectType->range('decision');
$approvalFlowObject->extra->range('review');
$approvalFlowObject->gen(5);

global $tester;
$stageTester = $tester->loadModel('stage');
r($stageTester->getStagePoints('TR', 1))  && p('1:stage,title') && e('1,TR1');  //获取阶段1的TR评审点
r($stageTester->getStagePoints('TR', 2))  && p('2:stage,title') && e('2,TR2');  //获取阶段2的TR评审点
r($stageTester->getStagePoints('TR', 3))  && p('3:stage,title') && e('3,TR3');  //获取阶段2的TR评审点
r($stageTester->getStagePoints('DCP', 4)) && p('4:stage,title') && e('4,CDCP'); //获取阶段4的DCP评审点
r($stageTester->getStagePoints('DCP', 5)) && p('5:stage,title') && e('5,PDCP'); //获取阶段5的DCP评审点
