#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/execution.class.php';
zdTable('user')->gen(5);
su('admin');

$execution = zdTable('project');
$execution->id->range('1-5');
$execution->name->range('项目集1,项目1,迭代1,阶段1,看板1');
$execution->type->range('program,project,sprint,stage,kanban');
$execution->parent->range('0,1,2{3}');
$execution->status->range('wait{3},suspended,closed,doing');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);

$product = zdTable('product');
$product->id->range('1-3');
$product->name->range('1-3')->prefix('产品');
$product->code->range('1-3')->prefix('product');
$product->type->range('normal');
$product->status->range('normal');
$product->PO->range('admin');
$product->QD->range('user1');
$product->RD->range('user2');
$product->gen(3);

$projectProduct = zdTable('projectproduct');
$projectProduct->project->range('3{3}, 4{3}, 5{3}');
$projectProduct->product->range('1-3');
$projectProduct->branch->range('0');
$projectProduct->gen(9);

/**

title=executionModel->getDefaultManagers();
timeout=0
cid=1

*/

$executionID = array(3, 4, 5);

global $tester;
$tester->loadModel('execution');
r($tester->execution->getDefaultManagers($executionID[0])) && p('PO') && e('admin'); // 获取迭代关联的产品相关负责人
r($tester->execution->getDefaultManagers($executionID[1])) && p('QD') && e('user1'); // 获取阶段关联的产品相关负责人
r($tester->execution->getDefaultManagers($executionID[2])) && p('RD') && e('user2'); // 获取看板关联的产品相关负责人
