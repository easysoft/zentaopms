#!/usr/bin/env php
<?php

/**

title=测试 productplanZen::buildLinkStorySearchForm();
timeout=0
cid=0

- 执行productplanTest模块的buildLinkStorySearchFormTest方法，参数是$normalPlan, 1, 'id_desc'
 - 属性queryID @1
 - 属性style @simple
 - 属性hasProductField @0
 - 属性hasBranchField @0
- 执行productplanTest模块的buildLinkStorySearchFormTest方法，参数是$branchPlan, 2, 'id_asc'
 - 属性queryID @2
 - 属性style @simple
 - 属性hasProductField @0
 - 属性hasBranchField @1
- 执行productplanTest模块的buildLinkStorySearchFormTest方法，参数是$platformPlan, 0, 'pri_desc'
 - 属性queryID @0
 - 属性style @simple
 - 属性hasProductField @0
 - 属性hasBranchField @1
- 执行productplanTest模块的buildLinkStorySearchFormTest方法，参数是$normalPlan, 0, 'status_desc'
 - 属性queryID @0
 - 属性style @simple
 - 属性hasTitleField @1
- 执行productplanTest模块的buildLinkStorySearchFormTest方法，参数是$normalPlan, 5, 'title_asc'
 - 属性queryID @5
 - 属性hasPlanParam @1
 - 属性hasModuleParam @1
- 执行productplanTest模块的buildLinkStorySearchFormTest方法，参数是$branchPlan, 3, 'id_desc'
 - 属性queryID @3
 - 属性hasPlanParam @1
 - 属性hasModuleParam @1
 - 属性hasBranchField @1
- 执行productplanTest模块的buildLinkStorySearchFormTest方法，参数是$platformPlan, 1, 'pri_asc'
 - 属性style @simple
 - 属性hasStatusParam @1
 - 属性hasGradeParam @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$productTable = zenData('product');
$productTable->name->range('1-10');
$productTable->name->prefix('产品');
$productTable->type->range('normal{3},branch{3},platform{4}');
$productTable->status->range('normal');
$productTable->desc->range('');
$productTable->gen(10);

$planTable = zenData('productplan');
$planTable->product->range('1{5},6{5},9{5}');
$planTable->branch->range('0{5},1,2,1,2,1,3,4,3,4,5');
$planTable->parent->range('0');
$planTable->title->range('1-15');
$planTable->title->prefix('计划');
$planTable->status->range('wait{5},doing{5},done{5}');
$planTable->desc->range('计划描述');
$planTable->begin->range('[2024-01-01],[2024-02-01],[2024-03-01]');
$planTable->end->range('[2024-12-31],[2025-01-31],[2025-02-28]');
$planTable->gen(15);

$branchTable = zenData('branch');
$branchTable->product->range('6{5},9{5}');
$branchTable->name->range('1-10');
$branchTable->name->prefix('分支');
$branchTable->status->range('active');
$branchTable->gen(10);

$moduleTable = zenData('module');
$moduleTable->name->range('1-20');
$moduleTable->name->prefix('模块');
$moduleTable->root->range('1{5},6{5},9{10}');
$moduleTable->type->range('story');
$moduleTable->parent->range('0');
$moduleTable->gen(20);

su('admin');

$productplanTest = new productplanZenTest();

// 构造不同类型产品的计划对象
$normalPlan = new stdClass();
$normalPlan->id = 1;
$normalPlan->product = 1;
$normalPlan->branch = '0';

$branchPlan = new stdClass();
$branchPlan->id = 6;
$branchPlan->product = 6;
$branchPlan->branch = '1,2';

$platformPlan = new stdClass();
$platformPlan->id = 11;
$platformPlan->product = 9;
$platformPlan->branch = '3,4';

r($productplanTest->buildLinkStorySearchFormTest($normalPlan, 1, 'id_desc')) && p('queryID,style,hasProductField,hasBranchField') && e('1,simple,0,0');
r($productplanTest->buildLinkStorySearchFormTest($branchPlan, 2, 'id_asc')) && p('queryID,style,hasProductField,hasBranchField') && e('2,simple,0,1');
r($productplanTest->buildLinkStorySearchFormTest($platformPlan, 0, 'pri_desc')) && p('queryID,style,hasProductField,hasBranchField') && e('0,simple,0,1');
r($productplanTest->buildLinkStorySearchFormTest($normalPlan, 0, 'status_desc')) && p('queryID,style,hasTitleField') && e('0,simple,1');
r($productplanTest->buildLinkStorySearchFormTest($normalPlan, 5, 'title_asc')) && p('queryID,hasPlanParam,hasModuleParam') && e('5,1,1');
r($productplanTest->buildLinkStorySearchFormTest($branchPlan, 3, 'id_desc')) && p('queryID,hasPlanParam,hasModuleParam,hasBranchField') && e('3,1,1,1');
r($productplanTest->buildLinkStorySearchFormTest($platformPlan, 1, 'pri_asc')) && p('style,hasStatusParam,hasGradeParam') && e('simple,1,1');