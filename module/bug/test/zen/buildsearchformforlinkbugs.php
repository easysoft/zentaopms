#!/usr/bin/env php
<?php

/**

title=测试 bugZen::buildSearchFormForLinkBugs();
timeout=0
cid=0

- 步骤1：有产品项目的正常Bug搜索表单构建
 - 属性hasProduct @1
 - 属性hasExecution @1
 - 属性hasPlan @1
- 步骤2：无产品项目的Bug搜索表单构建
 - 属性hasProduct @0
 - 属性hasExecution @0
 - 属性hasPlan @0
- 步骤3：单迭代项目的Bug搜索表单构建
 - 属性hasProduct @0
 - 属性hasExecution @0
 - 属性hasPlan @0
- 步骤4：QA页面的Bug搜索表单构建
 - 属性hasProduct @1
 - 属性hasExecution @1
 - 属性hasPlan @1
- 步骤5：带查询ID的搜索表单构建
 - 属性hasProduct @1
 - 属性hasExecution @1
 - 属性hasPlan @1
- 步骤6：带排除Bug参数的搜索表单构建
 - 属性hasProduct @1
 - 属性hasExecution @1
 - 属性hasPlan @1
- 步骤7：空Bug对象的搜索表单构建
 - 属性hasProduct @1
 - 属性hasExecution @1
 - 属性hasPlan @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

$bug = zenData('bug');
$bug->id->range('1-10');
$bug->project->range('1{3},2{3},3{2},4{1},0{1}');
$bug->product->range('1{4},2{3},3{2},4{1}');
$bug->title->range('Test Bug{1-10}');
$bug->status->range('active');
$bug->gen(10);

$project = zenData('project');
$project->id->range('1-5');
$project->name->range('测试项目{1-5}');
$project->type->range('project');
$project->status->range('doing');
$project->hasProduct->range('1{3},0{2}');
$project->multiple->range('1{3},0{2}');
$project->gen(5);

$product = zenData('product');
$product->id->range('1-5');
$product->name->range('测试产品{1-5}');
$product->type->range('normal{3},branch{2}');
$product->status->range('normal');
$product->gen(5);

su('admin');

$bugTest = new bugTest();

// 创建测试Bug对象
$normalBug = new stdClass();
$normalBug->id = 1;
$normalBug->project = 1;
$normalBug->product = 1;
$normalBug->title = 'Normal Bug';

$noProductBug = new stdClass();
$noProductBug->id = 2;
$noProductBug->project = 2;
$noProductBug->product = 2;
$noProductBug->title = 'No Product Bug';

$singleProjectBug = new stdClass();
$singleProjectBug->id = 3;
$singleProjectBug->project = 3;
$singleProjectBug->product = 3;
$singleProjectBug->title = 'Single Project Bug';

$qaBug = new stdClass();
$qaBug->id = 4;
$qaBug->project = 4;
$qaBug->product = 4;
$qaBug->title = 'QA Bug';

$emptyBug = new stdClass();
$emptyBug->id = 5;
$emptyBug->project = 0;
$emptyBug->product = 5;
$emptyBug->title = 'Empty Bug';

r($bugTest->buildSearchFormForLinkBugsTest($normalBug, '', 0)) && p('hasProduct,hasExecution,hasPlan') && e('1,1,1');        // 步骤1：有产品项目的正常Bug搜索表单构建
r($bugTest->buildSearchFormForLinkBugsTest($noProductBug, '', 0)) && p('hasProduct,hasExecution,hasPlan') && e('0,0,0');     // 步骤2：无产品项目的Bug搜索表单构建
r($bugTest->buildSearchFormForLinkBugsTest($singleProjectBug, '', 0)) && p('hasProduct,hasExecution,hasPlan') && e('0,0,0'); // 步骤3：单迭代项目的Bug搜索表单构建
r($bugTest->buildSearchFormForLinkBugsTest($qaBug, '', 0)) && p('hasProduct,hasExecution,hasPlan') && e('1,1,1');            // 步骤4：QA页面的Bug搜索表单构建
r($bugTest->buildSearchFormForLinkBugsTest($normalBug, '', 10)) && p('hasProduct,hasExecution,hasPlan') && e('1,1,1');       // 步骤5：带查询ID的搜索表单构建
r($bugTest->buildSearchFormForLinkBugsTest($normalBug, '1,2,3', 0)) && p('hasProduct,hasExecution,hasPlan') && e('1,1,1');   // 步骤6：带排除Bug参数的搜索表单构建
r($bugTest->buildSearchFormForLinkBugsTest($emptyBug, '', 0)) && p('hasProduct,hasExecution,hasPlan') && e('1,1,1');         // 步骤7：空Bug对象的搜索表单构建