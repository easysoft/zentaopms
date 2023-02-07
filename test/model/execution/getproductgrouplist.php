#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
zdTable('user')->gen(5);
su('admin');

$execution = zdTable('project');
$execution->id->range('1-10');
$execution->name->range('项目集1,项目1,项目2,项目3,项目4,项目5,项目6,迭代1,阶段1,看板1');
$execution->type->range('program,project{6},sprint,stage,kanban');
$execution->model->range('[],scrum{2},waterfall{2},kanban{2},[]{3}');
$execution->parent->range('0,1{6},2,3,4');
$execution->status->range('doing');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(10);

$product = zdTable('product');
$product->id->range('1-10');
$product->name->range('1-10')->prefix('产品');
$product->code->range('1-10')->prefix('product');
$product->type->range('normal');
$product->program->range('1{2}, 2{3}, 3{5}');
$product->status->range('normal');
$product->gen(10);

$projectProduct = zdTable('projectproduct');
$projectProduct->project->range('8{3}, 9{3}, 10{3}');
$projectProduct->product->range('1-9');
$projectProduct->branch->range('0');
$projectProduct->gen(9);

/**

title=测试executionModel->getProductGroupListTest();
cid=1
pid=1

查询当前用户访问的产品相关项目集 >> 项目集1
查询当前用户访问的产品相关数量 >> 10

*/

$count = array('0','1');

$executionTester = new executionTest();
r($executionTester->getProductGroupListTest($count[0])) && p('0:name') && e('项目集1'); // 查询当前用户访问的产品相关项目集
r($executionTester->getProductGroupListTest($count[1])) && p()         && e('10');      // 查询当前用户访问的产品相关数量
