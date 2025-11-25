#!/usr/bin/env php
<?php

/**

title=测试 productZen::getProjectProductList();
timeout=0
cid=17594

- 步骤1:isProjectStory为false时,返回空数组 @0
- 步骤2:isProjectStory为true且项目ID为11,获取关联产品数量 @3
- 步骤3:isProjectStory为true且项目ID为12,获取关联产品数量 @2
- 步骤4:isProjectStory为true且项目ID为13,获取关联产品数量 @1
- 步骤5:isProjectStory为true且项目ID为0,返回所有产品 @6
- 步骤6:isProjectStory为true且项目ID为999(不存在),返回空数组 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$product = zenData('product');
$product->id->range('1-10');
$product->name->range('产品1,产品2,产品3,产品4,产品5,产品6,产品7,产品8,产品9,产品10');
$product->type->range('normal');
$product->status->range('normal');
$product->gen(10);

$project = zenData('project');
$project->id->range('11-20');
$project->type->range('project');
$project->status->range('doing');
$project->hasProduct->range('1');
$project->gen(10);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('11{3},12{2},13{1}');
$projectProduct->product->range('1,2,3,4,5,6');
$projectProduct->branch->range('0');
$projectProduct->plan->range('0');
$projectProduct->gen(6);

su('admin');

$productTest = new productZenTest();

r($productTest->getProjectProductListTest(0, false)) && p() && e('0'); // 步骤1:isProjectStory为false时,返回空数组
r($productTest->getProjectProductListTest(11, true)) && p() && e('3'); // 步骤2:isProjectStory为true且项目ID为11,获取关联产品数量
r($productTest->getProjectProductListTest(12, true)) && p() && e('2'); // 步骤3:isProjectStory为true且项目ID为12,获取关联产品数量
r($productTest->getProjectProductListTest(13, true)) && p() && e('1'); // 步骤4:isProjectStory为true且项目ID为13,获取关联产品数量
r($productTest->getProjectProductListTest(0, true)) && p() && e('6'); // 步骤5:isProjectStory为true且项目ID为0,返回所有产品
r($productTest->getProjectProductListTest(999, true)) && p() && e('0'); // 步骤6:isProjectStory为true且项目ID为999(不存在),返回空数组