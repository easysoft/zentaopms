#!/usr/bin/env php
<?php

/**

title=测试 productZen::setTrackMenu();
timeout=0
cid=0

- 执行productTest模块的setTrackMenuTest方法，参数是1, 'main', 0 
 - 属性cookieSet @1
 - 属性sessionsSaved @1
 - 属性productMenuCalled @1
 - 属性paramsValid @1
- 执行productTest模块的setTrackMenuTest方法，参数是2, 'branch1', 1 
 - 属性cookieSet @1
 - 属性sessionsSaved @1
 - 属性projectMenuCalled @1
 - 属性paramsValid @1
- 执行productTest模块的setTrackMenuTest方法，参数是0, 'main', 0 属性paramsValid @0
- 执行productTest模块的setTrackMenuTest方法，参数是3, '', 0 
 - 属性cookieSet @1
 - 属性branchValid @1
 - 属性productMenuCalled @1
- 执行productTest模块的setTrackMenuTest方法，参数是4, 'main', 2 
 - 属性projectMenuCalled @1
 - 属性productMenuCalled @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

zendata('product');
$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3,产品4,产品5');
$product->code->range('product1,product2,product3,product4,product5');
$product->status->range('normal');
$product->type->range('normal');
$product->PO->range('admin,user1,user2,admin,user1');
$product->gen(5);

zendata('project');
$project = zenData('project');
$project->id->range('1-3');
$project->name->range('项目1,项目2,项目3');
$project->code->range('project1,project2,project3');
$project->status->range('wait,doing,done');
$project->type->range('project');
$project->gen(3);

su('admin');

$productTest = new productTest();

r($productTest->setTrackMenuTest(1, 'main', 0)) && p('cookieSet,sessionsSaved,productMenuCalled,paramsValid') && e('1,1,1,1');
r($productTest->setTrackMenuTest(2, 'branch1', 1)) && p('cookieSet,sessionsSaved,projectMenuCalled,paramsValid') && e('1,1,1,1');
r($productTest->setTrackMenuTest(0, 'main', 0)) && p('paramsValid') && e('0');
r($productTest->setTrackMenuTest(3, '', 0)) && p('cookieSet,branchValid,productMenuCalled') && e('1,1,1');
r($productTest->setTrackMenuTest(4, 'main', 2)) && p('projectMenuCalled,productMenuCalled') && e('1,0');