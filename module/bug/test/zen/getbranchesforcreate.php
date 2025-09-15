#!/usr/bin/env php
<?php

/**

title=测试 bugZen::getBranchesForCreate();
timeout=0
cid=0

- 执行bugTest模块的getBranchesForCreateTest方法，参数是$bug1 属性productID @1
- 执行bugTest模块的getBranchesForCreateTest方法，参数是$bug2 属性productID @6
- 执行bugTest模块的getBranchesForCreateTest方法，参数是$bug3 属性productID @1
- 执行bugTest模块的getBranchesForCreateTest方法，参数是$bug4 属性productID @6
- 执行bugTest模块的getBranchesForCreateTest方法，参数是$bug5 属性productID @6

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

$product = zenData('product');
$product->id->range('1-10');
$product->name->range('product{10}');
$product->type->range('normal{5},branch{5}');
$product->status->range('normal');
$product->deleted->range('0');
$product->gen(10);

$branch = zenData('branch');
$branch->id->range('1-20');
$branch->product->range('6-10:2');
$branch->name->range('branch{20}');
$branch->status->range('active');
$branch->deleted->range('0');
$branch->gen(10);

su('admin');

$bugTest = new bugTest();

$bug1 = new stdclass();
$bug1->productID = 1;
$bug1->branch = '';
$bug1->projectID = 0;
$bug1->executionID = 0;

$bug2 = new stdclass();
$bug2->productID = 6;
$bug2->branch = '1';
$bug2->projectID = 0;
$bug2->executionID = 0;

$bug3 = new stdclass();
$bug3->productID = 1;
$bug3->branch = '';
$bug3->projectID = 0;
$bug3->executionID = 0;

$bug4 = new stdclass();
$bug4->productID = 6;
$bug4->branch = '1';
$bug4->projectID = 0;
$bug4->executionID = 0;

$bug5 = new stdclass();
$bug5->productID = 6;
$bug5->branch = '1';
$bug5->projectID = 1;
$bug5->executionID = 0;

global $app;
$app->tab = 'qa';
r($bugTest->getBranchesForCreateTest($bug1)) && p('productID') && e('1');

$app->tab = 'qa';
r($bugTest->getBranchesForCreateTest($bug2)) && p('productID') && e('6');

$app->tab = 'execution';
r($bugTest->getBranchesForCreateTest($bug3)) && p('productID') && e('1');

$app->tab = 'execution';
r($bugTest->getBranchesForCreateTest($bug4)) && p('productID') && e('6');

$app->tab = 'project';
r($bugTest->getBranchesForCreateTest($bug5)) && p('productID') && e('6');