#!/usr/bin/env php
<?php

/**

title=测试 zaiModel::convertStoryToMarkdown();
timeout=0
cid=19767

- 测试转换完整的需求对象 @1
- 测试转换没有spec的需求对象 @2
- 测试验证Markdown内容包含基本信息 @1
- 测试验证属性设置正确 @1
- 测试验证标题包含ID @1
- 测试验证Markdown内容格式正确 @1
- 测试验证不同状态的需求转换 @1
- 测试验证Markdown内容包含需求描述 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zai.unittest.class.php';

zenData('story')->gen(2);
zenData('storyspec')->gen(1);

su('admin');

global $tester;
$zai = new zaiTest();

// 创建完整的需求对象
$story1 = new stdClass();
$story1->id = 1;
$story1->title = '测试需求1';
$story1->status = 'active';
$story1->stage = 'planned';
$story1->pri = 3;
$story1->version = 1;
$story1->category = 'feature';
$story1->source = 'customer';
$story1->estimate = 5;
$story1->product = 1;
$story1->plan = 1;
$story1->branch = 0;
$story1->parent = 0;
$story1->module = 1;
$story1->keywords = '测试关键词';
$story1->assignedTo = 'admin';
$story1->assignedDate = '2023-01-01 10:00:00';
$story1->reviewedDate = '2023-01-02 10:00:00';
$story1->reviewedBy = 'admin';
$story1->openedBy = 'admin';
$story1->openedDate = '2023-01-01 09:00:00';
$story1->stagedBy = 'admin';

// 创建没有spec的需求对象
$story2 = new stdClass();
$story2->id = 2;
$story2->title = '测试需求2';
$story2->status = 'draft';
$story2->stage = 'wait';
$story2->pri = 2;
$story2->version = 1;
$story2->category = 'interface';
$story2->source = 'po';
$story2->estimate = 3;
$story2->product = 1;
$story2->plan = 0;
$story2->branch = 0;
$story2->parent = 0;
$story2->module = 2;
$story2->keywords = '';
$story2->assignedTo = '';
$story2->assignedDate = '';
$story2->reviewedDate = '';
$story2->reviewedBy = '';
$story2->openedBy = 'admin';
$story2->openedDate = '2023-01-03 09:00:00';
$story2->stagedBy = '';

/* 测试转换完整的需求对象 */
$result1 = $zai->convertStoryToMarkdownTest($story1);
r($result1) && p('id') && e('1'); // 测试转换完整的需求对象

/* 测试转换没有spec的需求对象 */
$result2 = $zai->convertStoryToMarkdownTest($story2);
r($result2) && p('id') && e('2'); // 测试转换没有spec的需求对象

/* 测试验证Markdown内容包含基本信息 */
$contentContainsStoryId = strpos($result1['content'], '#1') !== false;
r($contentContainsStoryId) && p() && e('1'); // 测试验证Markdown内容包含基本信息

/* 测试验证属性设置正确 */
r(isset($result1['attrs']) ? 1 : 0) && p() && e('1'); // 测试验证属性设置正确

/* 测试验证标题格式正确 */
$titleContainsId = strpos($result1['title'], '#1') !== false;
r($titleContainsId) && p() && e('1'); // 测试验证标题包含ID

/* 测试验证Markdown内容格式正确 */
$contentContainsBasicInfo = strpos($result1['content'], '基本信息') !== false;
r($contentContainsBasicInfo) && p() && e('1'); // 测试验证Markdown内容格式正确

/* 测试验证不同状态的需求转换 */
r(isset($result1['attrs']['status']) ? 1 : 0) && p() && e('1'); // 测试验证不同状态的需求转换

/* 测试验证Markdown内容包含需求描述 */
$contentContainsStoryTitle = strpos($result1['content'], '测试需求1') !== false;
r($contentContainsStoryTitle) && p() && e('1'); // 测试验证Markdown内容包含需求描述
