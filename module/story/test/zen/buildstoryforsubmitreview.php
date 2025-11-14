#!/usr/bin/env php
<?php

/**

title=测试 storyZen::buildStoryForSubmitReview();
timeout=0
cid=18674

- 步骤1：不需要评审的正常情况属性status @active
- 步骤2：有评审者的正常情况属性status @active
- 步骤3：缺少评审者的错误情况属性reviewer @『评审人员』不能为空。
- 步骤4：评审者数组包含空值属性status @active
- 步骤5：无效故事ID的边界测试属性status @active

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/storyzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('story');
$table->id->range('1-10');
$table->product->range('1');
$table->title->range('需求标题{1-10}');
$table->status->range('active');
$table->stage->range('wait');
$table->type->range('story');
$table->version->range('1');
$table->openedBy->range('admin');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$storyZenTest = new storyZenTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($storyZenTest->buildStoryForSubmitReviewTest(1, array('needNotReview' => '1', 'reviewer' => array()))) && p('status') && e('active'); // 步骤1：不需要评审的正常情况
r($storyZenTest->buildStoryForSubmitReviewTest(1, array('needNotReview' => '0', 'reviewer' => array('user1', 'user2')))) && p('status') && e('active'); // 步骤2：有评审者的正常情况
r($storyZenTest->buildStoryForSubmitReviewTest(1, array('needNotReview' => '0', 'reviewer' => array()))) && p('reviewer') && e('『评审人员』不能为空。'); // 步骤3：缺少评审者的错误情况
r($storyZenTest->buildStoryForSubmitReviewTest(1, array('needNotReview' => '0', 'reviewer' => array('user1', '', 'user2', null)))) && p('status') && e('active'); // 步骤4：评审者数组包含空值
r($storyZenTest->buildStoryForSubmitReviewTest(999, array('needNotReview' => '1', 'reviewer' => array()))) && p('status') && e('active'); // 步骤5：无效故事ID的边界测试