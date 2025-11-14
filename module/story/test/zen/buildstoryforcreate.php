#!/usr/bin/env php
<?php

/**

title=测试 storyZen::buildStoryForCreate();
timeout=0
cid=18671

- 步骤1：正常情况属性title @测试需求标题
- 步骤2：从执行创建属性stage @projected
- 步骤3：从bug创建属性fromBug @1
- 步骤4：需求类型属性title @测试需求标题
- 步骤5：异常输入属性title @测试需求标题

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/storyzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('story');
$table->id->range('1-10');
$table->product->range('1-5');
$table->title->range('需求标题{1-10}');
$table->status->range('active');
$table->stage->range('wait');
$table->type->range('story,requirement');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$storyTest = new storyZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($storyTest->buildStoryForCreateTest(0, 0, 'story')) && p('title') && e('测试需求标题'); // 步骤1：正常情况
r($storyTest->buildStoryForCreateTest(1, 0, 'story')) && p('stage') && e('projected'); // 步骤2：从执行创建
r($storyTest->buildStoryForCreateTest(0, 1, 'story')) && p('fromBug') && e('1'); // 步骤3：从bug创建
r($storyTest->buildStoryForCreateTest(0, 0, 'requirement')) && p('title') && e('测试需求标题'); // 步骤4：需求类型
r($storyTest->buildStoryForCreateTest(-1, -1, 'invalid')) && p('title') && e('测试需求标题'); // 步骤5：异常输入