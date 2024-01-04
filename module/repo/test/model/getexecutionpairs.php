#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/repo.class.php';
su('admin');

/**

title=测试 repoModel->getExecutionPairs();
timeout=0
cid=1

- 获取gitlab类型版本库1的分支
 - 属性master @master
 - 属性branch1 @branch1
- 获取gitlab类型版本库1的分支加label
 - 属性master @Branch::master
 - 属性branch1 @Branch::branch1

*/

$execution = zdTable('project');
$execution->id->range('1-10');
$execution->name->range('项目集1,项目1,项目2,项目3,迭代1,迭代2,阶段1,阶段2,看板1,看板2');
$execution->type->range('program,project,sprint{2},stage{2},kanban{2}');
$execution->model->range('[],scrum,waterfall,kanban,[]{6}');
$execution->parent->range('0,1{3},2{2},3{2},4{2}');
$execution->project->range('0{4},2{2},3{2},4{2}');
$execution->status->range('doing');
$execution->vision->range('rnd');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(10);

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
$projectProduct->project->range('2{3}, 3{3}, 4{3}, 5, 6, 7, 8, 9, 10');
$projectProduct->product->range('1-3');
$projectProduct->branch->range('0');
$projectProduct->gen(9);

$productID = 3;
$branch = 1;

$repo = $tester->loadModel('repo');

r($repo->getExecutionPairs($productID)) && p('4,3') && e('项目3,项目2'); // 获取执行键值对
