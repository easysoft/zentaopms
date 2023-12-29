#!/usr/bin/env php
<?php

/**

title=测试 loadModel->getLinkProductsForCreate()
cid=0

- projectID=0, productID=0，检查产品数据。 @3
- projectID=0, productID=0，检查分支数据。 @0;1;2;3;4
- projectID=3, productID=0，检查产品数据。 @0
- projectID=3, productID=0，检查分支数据。 @0
- projectID=3, productID=3，检查产品数据。 @3
- projectID=3, productID=3，检查分支数据。 @1

*/

include dirname(__FILE__, 5). '/test/lib/init.php';
su('admin');

$project = zdTable('project');
$project->project->range('0,0,1{7},2{10}');
$project->parent->range('0,0,1{4},3{3},2');
$project->type->range('project,project,stage{100}');
$project->milestone->range('0{3},1{2},0{10}');
$project->percent->range('90,10,20,30,40,10,20,30,40,10,20,30');
$project->gen(10)->fixPath();

zdTable('product')->gen(5);
zdTable('branch')->gen(10);
$projectProduct = zdTable('projectproduct');
$projectProduct->project->range('2-20');
$projectProduct->product->range('3');
$projectProduct->branch->range('0-4');
$projectProduct->gen(10);

global $tester;
$tester->loadModel('programplan');

$linkedProducts = $tester->programplan->getLinkProductsForCreate(0, 0);
r($linkedProducts['products'])                  && p('0') && e('3');         // projectID=0, productID=0，检查产品数据。
r(implode(';', $linkedProducts['branch'][0]))   && p()    && e('0;1;2;3;4'); // projectID=0, productID=0，检查分支数据。

$linkedProducts = $tester->programplan->getLinkProductsForCreate(3, 0);
r($linkedProducts['products'][0]) && p() && e('0'); // projectID=3, productID=0，检查产品数据。
r($linkedProducts['branch'])      && p() && e('0'); // projectID=3, productID=0，检查分支数据。

$linkedProducts = $tester->programplan->getLinkProductsForCreate(3, 3);
r($linkedProducts['products'][0])  && p() && e('3'); // projectID=3, productID=3，检查产品数据。
r($linkedProducts['branch'][0][1]) && p() && e('1'); // projectID=3, productID=3，检查分支数据。
