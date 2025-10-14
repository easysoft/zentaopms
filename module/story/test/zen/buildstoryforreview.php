#!/usr/bin/env php
<?php

/**

title=测试 storyZen::buildStoryForReview();
timeout=0
cid=0

- 步骤1：正常评审通过情况属性lastEditedBy @admin
- 步骤2：评审拒绝情况属性closedReason @bydesign
- 步骤3：缺少comment字段第comment条的0属性 @comment字段不能为空
- 步骤4：缺少reviewedDate字段属性reviewedDate @评审时间不能为空
- 步骤5：重复需求缺少duplicateStory @重复需求不能为空

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zendata('story')->loadYaml('story_buildstoryforreview', false, 2)->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$storyTest = new storyTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($storyTest->buildStoryForReviewTest(1, array('comment' => '评审通过', 'result' => 'pass', 'reviewedDate' => '2023-12-01'))) && p('lastEditedBy') && e('admin'); // 步骤1：正常评审通过情况
r($storyTest->buildStoryForReviewTest(2, array('comment' => '拒绝需求', 'result' => 'reject', 'closedReason' => 'bydesign', 'reviewedDate' => '2023-12-01'))) && p('closedReason') && e('bydesign'); // 步骤2：评审拒绝情况
r($storyTest->buildStoryForReviewTest(3, array('result' => 'pass', 'reviewedDate' => '2023-12-01'))) && p('comment:0') && e('comment字段不能为空'); // 步骤3：缺少comment字段
r($storyTest->buildStoryForReviewTest(4, array('comment' => '评审意见', 'result' => 'pass'))) && p('reviewedDate') && e('评审时间不能为空'); // 步骤4：缺少reviewedDate字段
r($storyTest->buildStoryForReviewTest(5, array('comment' => '拒绝需求', 'result' => 'reject', 'closedReason' => 'duplicate', 'reviewedDate' => '2023-12-01'))) && p('0') && e('重复需求不能为空'); // 步骤5：重复需求缺少duplicateStory