#!/usr/bin/env php
<?php

/**

title=测试 storyModel::addGradeLabel();
timeout=0
cid=18461

- 测试步骤1：单个需求处理第0条的text属性 @单个需求测试
- 测试步骤2：空数组输入 @0
- 测试步骤3：多个需求数量验证第2条的text属性 @需求3
- 测试步骤4：数据结构验证第0条的text属性 @结构测试需求
- 测试步骤5：特殊字符处理第0条的text属性 @特殊字符需求test

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

// zendata数据准备
$story = zenData('story');
$story->version->range(1);
$story->gen(5);
zenData('storyspec')->gen(5);
zenData('product')->gen(1);

// 用户登录
su('admin');

// 创建测试实例
$storyTest = new storyTest();

// 测试步骤1：输入单个需求 - 验证基本功能
$singleStory = array(1 => '单个需求测试');
r($storyTest->addGradeLabelTest($singleStory)) && p('0:text') && e('单个需求测试'); // 测试步骤1：单个需求处理

// 测试步骤2：输入空的需求列表 - 验证空输入处理
$emptyStories = array();
r($storyTest->addGradeLabelTest($emptyStories)) && p() && e(0); // 测试步骤2：空数组输入

// 测试步骤3：输入多个需求 - 验证多个需求的数量
$multipleStories = array(1 => '需求1', 2 => '需求2', 3 => '需求3');
r($storyTest->addGradeLabelTest($multipleStories)) && p('2:text') && e('需求3'); // 测试步骤3：多个需求数量验证

// 测试步骤4：验证返回数据结构完整性 - 检查text字段
$structureStories = array(5 => '结构测试需求');
r($storyTest->addGradeLabelTest($structureStories)) && p('0:text') && e('结构测试需求'); // 测试步骤4：数据结构验证

// 测试步骤5：验证包含特殊字符的需求标题
$specialStories = array(10 => '特殊字符需求test');
r($storyTest->addGradeLabelTest($specialStories)) && p('0:text') && e('特殊字符需求test'); // 测试步骤5：特殊字符处理