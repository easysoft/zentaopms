#!/usr/bin/env php
<?php

/**

title=测试 repoModel::getExecutionPairs();
timeout=0
cid=18055

- 执行repoTest模块的getExecutionPairsTest方法，参数是1  @0
- 执行repoTest模块的getExecutionPairsTest方法，参数是999  @0
- 执行repoTest模块的getExecutionPairsTest方法  @Array
- 执行repoTest模块的getExecutionPairsTest方法，参数是2, 0  @(
- 执行repoTest模块的getExecutionPairsTest方法，参数是3  @[6] => 看板1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';

$execution = zenData('project');
$execution->id->range('1-6');
$execution->name->range('项目集1,项目1,项目2,迭代1,迭代2,看板1');
$execution->type->range('program,project{2},sprint{2},kanban');
$execution->model->range('[],scrum{2},[]{3}');
$execution->parent->range('0,1,1,2,2,3');
$execution->project->range('0{3},2,2,3');
$execution->status->range('doing');
$execution->grade->range('1{3},2{3}');
$execution->path->range(',1,,2,1,,3,2,1,,4,3,2,1,,5,4,3,2,1,,6,5,4,3,2,1,');
$execution->vision->range('rnd');
$execution->openedBy->range('admin');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->deleted->range('0');
$execution->gen(6);

$product = zenData('product');
$product->id->range('1-3');
$product->name->range('1-3')->prefix('产品');
$product->code->range('1-3')->prefix('product');
$product->type->range('normal');
$product->status->range('normal');
$product->PO->range('admin');
$product->QD->range('user1');
$product->RD->range('user2');
$product->gen(3);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('2,3,4,5,6');
$projectProduct->product->range('1{2},2{2},3');
$projectProduct->branch->range('0');
$projectProduct->gen(5);

su('admin');

$repoTest = new repoTest();

r($repoTest->getExecutionPairsTest(1)) && p() && e('0');
r($repoTest->getExecutionPairsTest(999)) && p() && e('0');
r($repoTest->getExecutionPairsTest(0)) && p() && e('Array');
r($repoTest->getExecutionPairsTest(2, 0)) && p() && e('(');
r($repoTest->getExecutionPairsTest(3)) && p() && e('[6] => 看板1');