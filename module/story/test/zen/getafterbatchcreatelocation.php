#!/usr/bin/env php
<?php

/**

title=测试 storyZen::getAfterBatchCreateLocation();
timeout=0
cid=18675

- 步骤1：有storyID无session返回projectstory链接 @getafterbatchcreatelocation.php?m=projectstory&f=view&storyID=1&projectID=0
- 步骤2：有storyID且不在project tab（有session） @projectstory-story-5.html
- 步骤3：有executionID且无storyID场景 @execution-story-10.html
- 步骤4：product tab返回browse链接 @getafterbatchcreatelocation.php?m=product&f=browse&productID=2&branch=all&browseType=unclosed&queryID=0&storyType=requirement
- 步骤5：有session返回storyList @execution-story-8.html

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/storyzen.unittest.class.php';

// 2. zendata数据准备
zenData('story')->loadYaml('story', false, 2)->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$storyTest = new storyZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
global $app;
$app->project = 0;

// 步骤1：测试有storyID且在product tab场景
$app->tab = 'product';
$app->session->storyList = '';
r($storyTest->getAfterBatchCreateLocationTest(1, '0', 0, 1, 'story')) && p() && e('getafterbatchcreatelocation.php?m=projectstory&f=view&storyID=1&projectID=0'); // 步骤1：有storyID无session返回projectstory链接

// 步骤2：测试有storyID且不在product tab场景（有session storyList）
$app->tab = 'project';
$app->project = 5;
$app->session->storyList = 'projectstory-story-5.html';
r($storyTest->getAfterBatchCreateLocationTest(1, '0', 0, 2, 'story')) && p() && e('projectstory-story-5.html'); // 步骤2：有storyID且不在project tab（有session）

// 步骤3：测试有executionID且无storyID场景
$app->tab = 'execution';
$app->session->storyList = 'execution-story-10.html';
r($storyTest->getAfterBatchCreateLocationTest(1, '0', 10, 0, 'story')) && p() && e('execution-story-10.html'); // 步骤3：有executionID且无storyID场景

// 步骤4：测试无executionID无storyID且在product tab场景
$app->tab = 'product';
$app->session->storyList = '';
r($storyTest->getAfterBatchCreateLocationTest(2, 'all', 0, 0, 'requirement')) && p() && e('getafterbatchcreatelocation.php?m=product&f=browse&productID=2&branch=all&browseType=unclosed&queryID=0&storyType=requirement'); // 步骤4：product tab返回browse链接

// 步骤5：测试无executionID无storyID且不在product tab且有session的场景
$app->tab = 'execution';
$app->session->storyList = 'execution-story-8.html';
r($storyTest->getAfterBatchCreateLocationTest(3, '0', 0, 0, 'story')) && p() && e('execution-story-8.html'); // 步骤5：有session返回storyList