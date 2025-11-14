#!/usr/bin/env php
<?php

/**

title=测试 storyZen::getAfterChangeLocation();
timeout=0
cid=18676

- 步骤1：execution tab场景 @execution-storyView-1.html
- 步骤2：非project tab场景 @story-view-1-0-0-story.html
- 步骤3：project tab且多项目模式场景 @projectstory-view-1.html
- 步骤4：project tab且非多项目模式场景 @story-view-1-0-5-story.html
- 步骤5：不同storyType参数场景 @requirement-view-2-0-0-requirement.html

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/storyzen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$storyTest = new storyZenTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
global $app;

// 步骤1：测试execution tab场景
$app->tab = 'execution';
r($storyTest->getAfterChangeLocationTest(1, 'story')) && p() && e('execution-storyView-1.html'); // 步骤1：execution tab场景

// 步骤2：测试非project tab场景（product tab）
$app->tab = 'product';
r($storyTest->getAfterChangeLocationTest(1, 'story')) && p() && e('story-view-1-0-0-story.html'); // 步骤2：非project tab场景

// 步骤3：测试project tab且多项目模式场景
$app->tab = 'project';
$app->session->multiple = true;
r($storyTest->getAfterChangeLocationTest(1, 'story')) && p() && e('projectstory-view-1.html'); // 步骤3：project tab且多项目模式场景

// 步骤4：测试project tab且非多项目模式场景
$app->tab = 'project';
$app->session->multiple = false;
$app->session->project = 5;
r($storyTest->getAfterChangeLocationTest(1, 'story')) && p() && e('story-view-1-0-5-story.html'); // 步骤4：project tab且非多项目模式场景

// 步骤5：测试不同storyType参数场景（requirement类型）
$app->tab = 'product';
r($storyTest->getAfterChangeLocationTest(2, 'requirement')) && p() && e('requirement-view-2-0-0-requirement.html'); // 步骤5：不同storyType参数场景