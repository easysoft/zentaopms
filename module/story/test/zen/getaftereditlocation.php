#!/usr/bin/env php
<?php

/**

title=测试 storyZen::getAfterEditLocation();
timeout=0
cid=0

- 步骤1：非项目标签页跳转 @story-view-1-0-0-story.html
- 步骤2：单执行项目跳转 @execution-storyView-2-2.html
- 步骤3：多执行项目跳转 @projectstory-view-3-3.html
- 步骤4：不同storyType处理 @epic-view-4-0-0-epic.html
- 步骤5：项目不存在处理 @story-view-5-0-0-story.html

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

// 2. 准备测试数据
$table = zenData('story');
$table->id->range('1-10');
$table->product->range('1-3');
$table->title->range('需求1,需求2,需求3,需求4,需求5,需求6,需求7,需求8,需求9,需求10');
$table->type->range('story,requirement,epic');
$table->status->range('active{6},draft{2},closed{2}');
$table->gen(10);

$project = zenData('project');
$project->id->range('1-5');
$project->name->range('项目1,项目2,项目3,项目4,项目5');
$project->type->range('project{3},sprint{2}');
$project->multiple->range('1,0,1,0,1');
$project->status->range('wait,doing,suspended,closed,wait');
$project->gen(5);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$storyTest = new storyTest();

// 5. 执行测试步骤
r($storyTest->getAfterEditLocationTest(1, 'story', 'product', 1, 1)) && p() && e('story-view-1-0-0-story.html'); // 步骤1：非项目标签页跳转
r($storyTest->getAfterEditLocationTest(2, 'requirement', 'project', 2, 0)) && p() && e('execution-storyView-2-2.html'); // 步骤2：单执行项目跳转
r($storyTest->getAfterEditLocationTest(3, 'story', 'project', 3, 1)) && p() && e('projectstory-view-3-3.html'); // 步骤3：多执行项目跳转
r($storyTest->getAfterEditLocationTest(4, 'epic', 'product', 4, 0)) && p() && e('epic-view-4-0-0-epic.html'); // 步骤4：不同storyType处理
r($storyTest->getAfterEditLocationTest(5, 'story', 'project', 0, 0)) && p() && e('story-view-5-0-0-story.html'); // 步骤5：项目不存在处理