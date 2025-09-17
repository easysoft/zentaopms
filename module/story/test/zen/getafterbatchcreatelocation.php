#!/usr/bin/env php
<?php

/**

title=测试 storyZen::getAfterBatchCreateLocation();
timeout=0
cid=0

- 步骤1：有storyID且在product标签页 @story-view-1-0-0-story.html
- 步骤2：有storyID且为requirement类型 @requirement-view-2-0-0-requirement.html
- 步骤3：有storyID且在project标签页 @projectstory-view-3-1.html
- 步骤4：有executionID时 @execution-story-5.html
- 步骤5：在product标签页无storyID和executionID @product-browse-2-main-unclosed-0-story.html

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('story');
$table->id->range('1-10');
$table->title->range('软件需求{0-9}');
$table->type->range('story{5},requirement{5}');
$table->product->range('1-3');
$table->status->range('active{8},closed{2}');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$storyTest = new storyTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
// 设置app->tab为product以测试产品标签页场景
global $app;
$app->tab = 'product';

r($storyTest->getAfterBatchCreateLocationTest(1, '0', 0, 1, 'story')) && p() && e('story-view-1-0-0-story.html'); // 步骤1：有storyID且在product标签页
r($storyTest->getAfterBatchCreateLocationTest(1, '0', 0, 2, 'requirement')) && p() && e('requirement-view-2-0-0-requirement.html'); // 步骤2：有storyID且为requirement类型

// 设置app->tab为project以测试项目标签页场景
$app->tab = 'project';
$app->project = 1;

r($storyTest->getAfterBatchCreateLocationTest(1, '0', 0, 3, 'story')) && p() && e('projectstory-view-3-1.html'); // 步骤3：有storyID且在project标签页

// 恢复product标签页测试executionID和默认情况
$app->tab = 'product';

r($storyTest->getAfterBatchCreateLocationTest(1, '0', 5, 0, 'story')) && p() && e('execution-story-5.html'); // 步骤4：有executionID时
r($storyTest->getAfterBatchCreateLocationTest(2, 'main', 0, 0, 'story')) && p() && e('product-browse-2-main-unclosed-0-story.html'); // 步骤5：在product标签页无storyID和executionID