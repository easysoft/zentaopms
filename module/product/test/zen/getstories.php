#!/usr/bin/env php
<?php

/**

title=测试 productZen::getStories();
timeout=0
cid=0

- 步骤1：正常获取产品需求列表 @3
- 步骤2：获取项目需求列表 @0
- 步骤3：按模块获取需求列表 @1
- 步骤4：按类型获取需求列表 @1
- 步骤5：无效产品ID测试 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$product = zenData('product');
$product->id->range('1-3');
$product->name->range('产品{1-3}');
$product->code->range('product{1-3}');
$product->type->range('normal');
$product->status->range('normal');
$product->vision->range('rnd');
$product->gen(3);

$story = zenData('story');
$story->id->range('1-10');
$story->product->range('1-3');
$story->title->range('需求{1-10}');
$story->type->range('story{8},requirement{2}');
$story->status->range('active{6},closed{2},draft{2}');
$story->stage->range('wait,planned,projected,developing,testing,verified,released,closed');
$story->vision->range('rnd');
$story->module->range('0{5},1001-1003{5}');
$story->gen(10);

$module = zenData('module');
$module->id->range('1001-1003');
$module->root->range('1-3');
$module->name->range('模块{1-3}');
$module->type->range('story');
$module->gen(3);

$project = zenData('project');
$project->id->range('1-2');
$project->name->range('项目{1-2}');
$project->type->range('project');
$project->status->range('doing');
$project->hasProduct->range('1');
$project->gen(2);

$projectproduct = zenData('projectproduct');
$projectproduct->project->range('1-2');
$projectproduct->product->range('1-2');
$projectproduct->gen(2);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$productTest = new productTest();

// 5. 强制要求：必须包含至少5个测试步骤
r(count($productTest->getStoriesZenTest(0, 1, '', 0, 0, 'story', 'allstory', 'id_desc', null))) && p() && e('3'); // 步骤1：正常获取产品需求列表
r(count($productTest->getStoriesZenTest(1, 1, '', 0, 0, 'story', 'allstory', 'id_desc', null))) && p() && e('0'); // 步骤2：获取项目需求列表
r(count($productTest->getStoriesZenTest(0, 1, '', 1001, 0, 'story', 'bymodule', 'id_desc', null))) && p() && e('1'); // 步骤3：按模块获取需求列表
r(count($productTest->getStoriesZenTest(0, 1, '', 0, 0, 'requirement', 'allstory', 'id_desc', null))) && p() && e('1'); // 步骤4：按类型获取需求列表
r(count($productTest->getStoriesZenTest(0, 999, '', 0, 0, 'story', 'allstory', 'id_desc', null))) && p() && e('0'); // 步骤5：无效产品ID测试