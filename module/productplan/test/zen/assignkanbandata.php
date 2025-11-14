#!/usr/bin/env php
<?php

/**

title=测试 productplanZen::assignKanbanData();
timeout=0
cid=17657

- 执行productplanTest模块的assignKanbanDataTest方法，参数是1, 'normal', '0', 'begin_asc'
 - 属性productType @normal
 - 属性methodCalled @yes
- 执行productplanTest模块的assignKanbanDataTest方法，参数是6, 'branch', 'all', 'begin_asc'
 - 属性productType @branch
 - 属性methodCalled @yes
- 执行productplanTest模块的assignKanbanDataTest方法，参数是7, 'branch', '1', 'begin_asc'
 - 属性productType @branch
 - 属性methodCalled @yes
- 执行productplanTest模块的assignKanbanDataTest方法，参数是2, 'normal', '0', 'invalid_order'
 - 属性productType @normal
 - 属性methodCalled @yes
- 执行productplanTest模块的assignKanbanDataTest方法，参数是8, 'branch', '2', 'end_desc'
 - 属性productType @branch
 - 属性methodCalled @yes

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$productTable = zenData('product');
$productTable->name->range('1-10');
$productTable->name->prefix('产品');
$productTable->type->range('normal{5},branch{3},platform{2}');
$productTable->status->range('normal');
$productTable->desc->range('');
$productTable->gen(10);

$planTable = zenData('productplan');
$planTable->product->range('1{10},2{5},6{8},7{7}');
$planTable->branch->range('0{15},1{8},2{7}');
$planTable->parent->range('0');
$planTable->title->range('1-30');
$planTable->title->prefix('计划');
$planTable->status->range('wait{10},doing{10},done{10}');
$planTable->desc->range('计划描述');
$planTable->begin->range('[2024-01-01],[2024-02-01],[2024-03-01],[2024-04-01],[2024-05-01]');
$planTable->end->range('[2024-12-31],[2025-01-31],[2025-02-28],[2025-03-31],[2025-04-30]');
$planTable->gen(30);

$branchTable = zenData('branch');
$branchTable->product->range('6{5},7{5}');
$branchTable->name->range('1-10');
$branchTable->name->prefix('分支');
$branchTable->status->range('active');
$branchTable->gen(10);

su('admin');

$productplanTest = new productplanZenTest();

r($productplanTest->assignKanbanDataTest(1, 'normal', '0', 'begin_asc')) && p('productType,methodCalled') && e('normal,yes');
r($productplanTest->assignKanbanDataTest(6, 'branch', 'all', 'begin_asc')) && p('productType,methodCalled') && e('branch,yes');
r($productplanTest->assignKanbanDataTest(7, 'branch', '1', 'begin_asc')) && p('productType,methodCalled') && e('branch,yes');
r($productplanTest->assignKanbanDataTest(2, 'normal', '0', 'invalid_order')) && p('productType,methodCalled') && e('normal,yes');
r($productplanTest->assignKanbanDataTest(8, 'branch', '2', 'end_desc')) && p('productType,methodCalled') && e('branch,yes');