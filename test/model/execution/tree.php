#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
zdTable('user')->gen(5);
su('admin');

$execution = zdTable('project');
$execution->id->range('1-5');
$execution->name->range('项目集1,项目1,迭代1,阶段1,看板1');
$execution->type->range('execution,project,sprint,stage,kanban');
$execution->parent->range('0,1,2{3}');
$execution->status->range('wait{3},suspended,closed,doing');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);

$product = zdTable('product');
$product->id->range('1-2');
$product->name->range('正常产品1,多分支产品1');
$product->type->range('normal,branch');
$product->status->range('normal');
$product->createdBy->range('admin,user1');
$product->gen(2);

$branch = zdTable('branch');
$branch->id->range('1-2');
$branch->product->range('2');
$branch->name->range('分支1,分支2');
$branch->status->range('active');
$branch->gen(2);

$projectProduct = zdTable('projectproduct');
$projectProduct->project->range('3{3}, 4{3}, 5{3}');
$projectProduct->product->range('1-2{2}');
$projectProduct->branch->range('0-2');
$projectProduct->gen(9);

/**

title=测试 executionModel::tree();
cid=1
pid=1

检查执行列表的树状结构 >> 正常产品1

*/

global $tester;
$treeHtml = $tester->loadModel('execution')->tree();

r(strip_tags($treeHtml)) && p() && e('正常产品1'); // 检查执行列表的树状结构
