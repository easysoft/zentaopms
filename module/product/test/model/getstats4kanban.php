#!/usr/bin/env php
<?php

/**

title=productModel->getStats4Kanban();
cid=0

- 执行product模块的getStats4KanbanTest方法
 - 第1条的name属性 @正常产品1
 - 第2条的name属性 @正常产品2
 - 第3条的name属性 @正常产品3
- 执行product模块的getStats4KanbanTest方法，参数是2
 - 第13条的name属性 @项目13
 - 第14条的name属性 @项目14
- 执行product模块的getStats4KanbanTest方法，参数是5
 - 第12条的name属性 @项目22
 - 第19条的name属性 @项目29
- 执行product模块的getStats4KanbanTest方法，参数是1, true  @1
- 执行product模块的getStats4KanbanTest方法，参数是3, true  @2
- 执行product模块的getStats4KanbanTest方法，参数是4, true  @6

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
r($product->getStats4KanbanTest(5))       && p('12:name;19:name')      && e('项目22,项目29');
r($product->getStats4KanbanTest(1, true)) && p()                       && e('1');
r($product->getStats4KanbanTest(3, true)) && p()                       && e('2');
r($product->getStats4KanbanTest(4, true)) && p()                       && e('6');
