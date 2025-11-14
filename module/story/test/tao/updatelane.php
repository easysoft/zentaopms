#!/usr/bin/env php
<?php

/**

title=测试 storyTao::updateLane();
timeout=0
cid=18661

- 步骤1：正常需求story类型 @success
- 步骤2：正常需求requirement类型 @success
- 步骤3：不存在需求ID @success
- 步骤4：无关联项目需求ID @success
- 步骤5：其他需求类型测试 @success

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$project = zenData('project');
$project->loadYaml('project_updatelane', false, 2)->gen(10);

$projectStory = zenData('projectstory');
$projectStory->loadYaml('projectstory_updatelane', false, 2)->gen(15);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$storyTest = new storyTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($storyTest->updateLaneTest(1, 'story')) && p() && e('success'); // 步骤1：正常需求story类型
r($storyTest->updateLaneTest(2, 'requirement')) && p() && e('success'); // 步骤2：正常需求requirement类型
r($storyTest->updateLaneTest(999, 'story')) && p() && e('success'); // 步骤3：不存在需求ID
r($storyTest->updateLaneTest(100, 'story')) && p() && e('success'); // 步骤4：无关联项目需求ID
r($storyTest->updateLaneTest(10, 'epic')) && p() && e('success'); // 步骤5：其他需求类型测试