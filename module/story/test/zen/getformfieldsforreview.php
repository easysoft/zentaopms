#!/usr/bin/env php
<?php

/**

title=测试 storyZen::getFormFieldsForReview();
timeout=0
cid=0

- 步骤1：正常情况返回非空结果 @not_empty
- 步骤2：无效ID属性error @invalid_story_id
- 步骤3：不存在的需求ID属性error @story_not_found
- 步骤4：检查返回结果不为空 @not_empty
- 步骤5：检查返回结果不为空 @not_empty

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('story');
$table->id->range('1-5');
$table->title->range('软件需求1,软件需求2,软件需求3,软件需求4,软件需求5');
$table->type->range('story');
$table->status->range('reviewing{2},active{3}');
$table->version->range('1{3},2{2}');
$table->product->range('1');
$table->assignedTo->range('user1,user2,admin');
$table->pri->range('1-4');
$table->estimate->range('4,8,16,32,64');
$table->lastEditedBy->range('admin');
$table->openedBy->range('admin');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$storyTest = new storyTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($storyTest->getFormFieldsForReviewTest(1)) && p() && e('not_empty'); // 步骤1：正常情况返回非空结果
r($storyTest->getFormFieldsForReviewTest(0)) && p('error') && e('invalid_story_id'); // 步骤2：无效ID
r($storyTest->getFormFieldsForReviewTest(999)) && p('error') && e('story_not_found'); // 步骤3：不存在的需求ID
r($storyTest->getFormFieldsForReviewTest(2)) && p() && e('not_empty'); // 步骤4：检查返回结果不为空
r($storyTest->getFormFieldsForReviewTest(3)) && p() && e('not_empty'); // 步骤5：检查返回结果不为空