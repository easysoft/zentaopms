#!/usr/bin/env php
<?php

/**

title=测试 storyTao::checkConditions();
timeout=0
cid=18611

- 步骤1：开源版本测试 @1
- 步骤2：企业版无工作流动作 @1
- 步骤3：企业版有条件满足 @1
- 步骤4：企业版条件验证 @1
- 步骤5：企业版扩展类型测试 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('story');
$table->id->range('1-10');
$table->title->range('需求1,需求2,需求3,需求4,需求5,需求6,需求7,需求8,需求9,需求10');
$table->type->range('story{10}');
$table->status->range('draft{3},active{4},closed{3}');
$table->product->range('1-5');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$storyTest = new storyTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
$story = new stdclass();
$story->id = 1;
$story->type = 'story';
$story->status = 'active';

r($storyTest->checkConditionsTest('edit', $story)) && p() && e('1'); // 步骤1：开源版本测试
r($storyTest->checkConditionsTest('review', $story)) && p() && e('1'); // 步骤2：企业版无工作流动作
r($storyTest->checkConditionsTest('close', $story)) && p() && e('1'); // 步骤3：企业版有条件满足
r($storyTest->checkConditionsTest('activate', $story)) && p() && e('1'); // 步骤4：企业版条件验证
r($storyTest->checkConditionsTest('change', $story)) && p() && e('1'); // 步骤5：企业版扩展类型测试