#!/usr/bin/env php
<?php

/**

title=测试 storyZen::getAfterReviewLocation();
timeout=0
cid=0

- 步骤1：from为project的情况 @execution-storyView-1.html
- 步骤2：from为project多执行项目 @execution-storyView-2.html
- 步骤3：from不为execution @story-view-3-0-0-story.html
- 步骤4：from为execution @execution-storyView-4.html
- 步骤5：from为空字符串 @requirement-view-5-0-0-requirement.html

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/storyzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$projectTable = zenData('project');
$projectTable->id->range('1-10');
$projectTable->name->range('项目{1-10}');
$projectTable->type->range('project');
$projectTable->multiple->range('0{5},1{5}');  // 前5个单执行项目，后5个多执行项目
$projectTable->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$storyZenTest = new storyZenTest();

// 设置session项目
global $tester;
$tester->session->project = 1;

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($storyZenTest->getAfterReviewLocationTest(1, 'story', 'project')) && p() && e('execution-storyView-1.html'); // 步骤1：from为project的情况
r($storyZenTest->getAfterReviewLocationTest(2, 'requirement', 'project')) && p() && e('execution-storyView-2.html'); // 步骤2：from为project多执行项目
r($storyZenTest->getAfterReviewLocationTest(3, 'story', 'other')) && p() && e('story-view-3-0-0-story.html'); // 步骤3：from不为execution
r($storyZenTest->getAfterReviewLocationTest(4, 'story', 'execution')) && p() && e('execution-storyView-4.html'); // 步骤4：from为execution
r($storyZenTest->getAfterReviewLocationTest(5, 'requirement', '')) && p() && e('requirement-view-5-0-0-requirement.html'); // 步骤5：from为空字符串