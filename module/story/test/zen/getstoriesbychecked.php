#!/usr/bin/env php
<?php

/**

title=测试 storyZen::getStoriesByChecked();
timeout=0
cid=0

- 步骤1：正常输入有效需求ID列表 @0
- 步骤2：输入包含子需求格式的ID列表 @0
- 步骤3：输入空的ID列表 @0
- 步骤4：输入不存在的需求ID @0
- 步骤5：输入包含正常需求ID @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('story');
$table->id->range('1-10');
$table->title->range('软件需求1,软件需求2,软件需求3,软件需求4,软件需求5,软件需求6,软件需求7,软件需求8,软件需求9,软件需求10');
$table->type->range('story{10}');
$table->status->range('active{5},draft{3},closed{2}');
$table->stage->range('wait{3},planned{4},projected{2},developing{1}');
$table->twins->range('{8},1,2{1}');
$table->deleted->range('0{10}');
$table->product->range('1{10}');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$storyTest = new storyTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($storyTest->getStoriesByCheckedTest(array('1', '2', '3'))) && p() && e('0'); // 步骤1：正常输入有效需求ID列表
r($storyTest->getStoriesByCheckedTest(array('parent-4', 'parent-5'))) && p() && e('0'); // 步骤2：输入包含子需求格式的ID列表
r($storyTest->getStoriesByCheckedTest(array())) && p() && e('0'); // 步骤3：输入空的ID列表
r($storyTest->getStoriesByCheckedTest(array('999', '1000'))) && p() && e('0'); // 步骤4：输入不存在的需求ID
r($storyTest->getStoriesByCheckedTest(array('1'))) && p() && e('0'); // 步骤5：输入包含正常需求ID