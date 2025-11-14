#!/usr/bin/env php
<?php

/**

title=测试 productModel::buildSearchConfig();
timeout=0
cid=17474

- 测试 productID=1, storyType='story' 的正常情况第fields条的title属性 @需求名称
- 测试 productID=1, storyType='requirement' 的情况，验证 title 字段名称被替换第fields条的title属性 @用户需求名称
- 测试 productID=1, storyType='epic' 的情况，验证 title 字段名称被替换第fields条的title属性 @用户需求名称
- 测试 productID=2 且产品类型为 branch，验证 branch 字段存在第fields条的branch属性 @所属分支
- 测试 productID=1, storyType='story' 验证module配置正确属性module @story

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';
su('admin');

$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3,产品4,产品5');
$product->type->range('normal,branch,platform,normal,branch');
$product->status->range('normal');
$product->gen(5);

$module = zenData('module');
$module->id->range('1-10');
$module->root->range('1-5');
$module->name->range('模块1,模块2,模块3,模块4,模块5,模块6,模块7,模块8,模块9,模块10');
$module->type->range('story');
$module->gen(10);

$plan = zenData('productplan');
$plan->id->range('1-5');
$plan->product->range('1-5');
$plan->title->range('计划1,计划2,计划3,计划4,计划5');
$plan->gen(5);

$branch = zenData('branch');
$branch->id->range('1-5');
$branch->product->range('2,2,3,3,5');
$branch->name->range('分支1,分支2,分支3,分支4,分支5');
$branch->gen(5);

$productTest = new productTest();

r($productTest->buildSearchConfigTest(1, 'story')) && p('fields:title') && e('需求名称'); // 测试 productID=1, storyType='story' 的正常情况
r($productTest->buildSearchConfigTest(1, 'requirement')) && p('fields:title') && e('用户需求名称'); // 测试 productID=1, storyType='requirement' 的情况，验证 title 字段名称被替换
r($productTest->buildSearchConfigTest(1, 'epic')) && p('fields:title') && e('用户需求名称'); // 测试 productID=1, storyType='epic' 的情况，验证 title 字段名称被替换
r($productTest->buildSearchConfigTest(2, 'story')) && p('fields:branch') && e('所属分支'); // 测试 productID=2 且产品类型为 branch，验证 branch 字段存在
r($productTest->buildSearchConfigTest(1, 'story')) && p('module') && e('story'); // 测试 productID=1, storyType='story' 验证module配置正确