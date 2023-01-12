#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';

$execution = zdTable('project');
$execution->id->range('1-5');
$execution->name->range('项目1,项目2,迭代1,迭代2,迭代3');
$execution->type->range('project{2},sprint,waterfall,kanban');
$execution->status->range('doing{3},closed,doing');
$execution->parent->range('0,0,1,1,2');
$execution->grade->range('2{2},1{3}');
$execution->path->range('1,2,`1,3`,`1,4`,`2,5`')->prefix(',')->postfix(',');
$execution->begin->range('20230102 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20230212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);

$product = zdTable('product');
$product->id->range('1-3');
$product->name->range('1-3')->prefix('产品');
$product->code->range('1-3')->prefix('product');
$product->type->range('normal');
$product->status->range('normal');
$product->gen(3);

zdTable('user')->gen(5);
su('admin');

/**

title=测试executionModel->updateProductsTest();
cid=1
pid=1

测试修改敏捷执行关联产品 >> 1
测试修改瀑布执行关联产品 >> 2
测试修改看板执行关联产品 >> 3

*/

$executionIDList = array('3','4','5');
$productIDlist   = array('1','2','3');
$products        = array('products' => $productIDlist);

$execution = new executionTest();
r($execution->updateProductsTest($executionIDList[0], $products)) && p('0:product') && e('1'); // 测试修改敏捷执行关联产品
r($execution->updateProductsTest($executionIDList[1], $products)) && p('1:product') && e('2'); // 测试修改瀑布执行关联产品
r($execution->updateProductsTest($executionIDList[2], $products)) && p('2:product') && e('3'); // 测试修改看板执行关联产品
