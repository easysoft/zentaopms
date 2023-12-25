#!/usr/bin/env php
<?php
/**

title=productModel->getStats4Kanban();
timeout=0
cid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/product.class.php';

$project = zdTable('project');
$project->type->range('program{10},project{10},sprint{10}');
$project->project->range('0{10},1-10,11-20');
$project->gen(30);

zdTable('product')->gen(30);
zdTable('projectproduct')->gen(30);
zdTable('productplan')->gen(30);
zdTable('team')->gen(30);
zdTable('release')->gen(30);

$product = new productTest('admin');

r($product->getStats4KanbanTest(0))       && p('1:name;2:name;3:name') && e('正常产品1;正常产品2;正常产品3');
r($product->getStats4KanbanTest(2))       && p('13:name;14:name')      && e('项目13;项目14');
r($product->getStats4KanbanTest(5))       && p('11:name;19:name')      && e('项目21,项目29');
r($product->getStats4KanbanTest(1, true)) && p()                       && e('1');
r($product->getStats4KanbanTest(3, true)) && p()                       && e('4');
r($product->getStats4KanbanTest(4, true)) && p()                       && e('6');
