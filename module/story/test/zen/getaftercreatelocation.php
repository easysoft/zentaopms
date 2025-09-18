#!/usr/bin/env php
<?php

/**

title=测试 storyZen::getAfterCreateLocation();
timeout=0
cid=0

- 步骤1：正常产品浏览情况 @product-browse-1-all--0-story-id_desc.html
- 步骤2：项目执行情况（project类型） @execution-story-1.html
- 步骤3：执行情况（execution类型） @execution-story-3.html
- 步骤4：需求类型情况 @execution-story-5.html
- 步骤5：分支产品情况 @product-browse-4-all--0-story-id_desc.html

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/storyzen.unittest.class.php';

// 2. zendata数据准备
$project = zenData('project');
$project->id->range('1-10');
$project->name->range('项目1,项目2,执行1,执行2,项目3,执行3,项目4,执行4,项目5,执行5');
$project->type->range('project{2},execution{2},project{2},execution{2},project{2}');
$project->status->range('wait,doing{9}');
$project->gen(10);

$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3,产品4,产品5');
$product->status->range('normal{5}');
$product->type->range('normal{3},branch{2}');
$product->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$storyZenTest = new storyZenTest();

// 5. 测试步骤
r($storyZenTest->getAfterCreateLocationTest(1, 'all', 0, 1, 'story', '')) && p() && e('product-browse-1-all--0-story-id_desc.html'); // 步骤1：正常产品浏览情况
r($storyZenTest->getAfterCreateLocationTest(1, 'all', 1, 1, 'story', '')) && p() && e('execution-story-1.html'); // 步骤2：项目执行情况（project类型）
r($storyZenTest->getAfterCreateLocationTest(1, 'all', 3, 1, 'story', '')) && p() && e('execution-story-3.html'); // 步骤3：执行情况（execution类型）
r($storyZenTest->getAfterCreateLocationTest(2, '', 5, 1, 'requirement', '')) && p() && e('execution-story-5.html'); // 步骤4：需求类型情况
r($storyZenTest->getAfterCreateLocationTest(4, 'all', 0, 1, 'story', '')) && p() && e('product-browse-4-all--0-story-id_desc.html'); // 步骤5：分支产品情况