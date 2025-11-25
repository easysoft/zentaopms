#!/usr/bin/env php
<?php

/**

title=测试 zaiModel::convertTargetToMarkdown();
timeout=0
cid=19768

- 测试转换story类型的目标对象 @1
- 测试story返回attrs属性 @1
- 测试转换bug类型的目标对象 @1
- 测试bug返回attrs属性 @1
- 测试转换未知类型的目标对象 @1
- 测试未知类型返回title @1
- 测试未知类型返回attrs属性 @1
- 测试转换具有name属性的目标对象 @1
- 测试name对象返回attrs属性 @1
- 验证objectType属性 @unknowntype
- 验证objectID属性 @1
- 验证objectKey属性 @unknowntype-1
- 验证story的objectType @story
- 验证story的objectID @1
- 验证bug的objectType @bug
- 验证bug的objectID @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zai.unittest.class.php';

su('admin');

global $tester;
$zai = new zaiTest();

// 创建测试用的故事对象
$storyTarget = new stdClass();
$storyTarget->id = 1;
$storyTarget->title = '测试故事标题';
$storyTarget->status = 'active';
$storyTarget->stage = 'planned';
$storyTarget->pri = 3;
$storyTarget->version = 1;
$storyTarget->category = 'feature';
$storyTarget->source = 'customer';
$storyTarget->estimate = 5;
$storyTarget->product = 1;
$storyTarget->plan = 1;
$storyTarget->branch = 0;
$storyTarget->parent = 0;
$storyTarget->module = 1;
$storyTarget->keywords = '测试关键词';
$storyTarget->assignedTo = 'admin';
$storyTarget->assignedDate = '2023-01-01';
$storyTarget->reviewedDate = '2023-01-02';
$storyTarget->reviewedBy = 'admin';
$storyTarget->openedBy = 'admin';
$storyTarget->openedDate = '2023-01-01';
$storyTarget->stagedBy = 'admin';

// 创建测试用的bug对象
$bugTarget = new stdClass();
$bugTarget->id = 1;
$bugTarget->title = '测试Bug2 - 界面显示异常';
$bugTarget->pri = 3;
$bugTarget->severity = 2;
$bugTarget->status = 'resolved';
$bugTarget->resolution = 'fixed';
$bugTarget->type = 'designdefect';
$bugTarget->product = 1;
$bugTarget->project = 0;
$bugTarget->execution = 0;
$bugTarget->module = 2;
$bugTarget->branch = 0;
$bugTarget->plan = 0;
$bugTarget->story = 0;
$bugTarget->relatedBug = '';
$bugTarget->keywords = '';
$bugTarget->steps = '<p>界面显示不正常</p>';
$bugTarget->resolvedBy = 'developer2';
$bugTarget->resolvedDate = '2023-01-02 15:00:00';
$bugTarget->resolvedBuild = 'build002';
$bugTarget->openedBy = 'tester2';
$bugTarget->openedDate = '2023-01-01 14:00:00';
$bugTarget->openedBuild = 'build001';
$bugTarget->assignedTo = 'developer2';
$bugTarget->assignedDate = '2023-01-01 15:00:00';
$bugTarget->closedBy = 'admin';
$bugTarget->closedDate = '2023-01-03 09:00:00';
$bugTarget->feedbackBy = '';
$bugTarget->activatedDate = '';
$bugTarget->task = 5;


// 创建未知类型的目标对象
$unknownTarget = new stdClass();
$unknownTarget->id = 1;
$unknownTarget->title = '未知类型对象标题';
$unknownTarget->description = '这是一个未知类型的对象';

// 创建具有name属性的目标对象
$namedTarget = new stdClass();
$namedTarget->id = 2;
$namedTarget->name = '具有名称的对象';
$namedTarget->description = '这是一个具有name属性的对象';

/* 测试转换story类型的目标对象 */
$storyResult = $zai->convertTargetToMarkdownTest('story', $storyTarget);
r(isset($storyResult['content']) && !empty($storyResult['content'])) && p() && e('1'); // 测试转换story类型的目标对象
r(isset($storyResult['attrs']) && is_array($storyResult['attrs'])) && p() && e('1'); // 测试story返回attrs属性

/* 测试转换bug类型的目标对象 */
$bugResult = $zai->convertTargetToMarkdownTest('bug', $bugTarget);
r(isset($bugResult['content']) && !empty($bugResult['content'])) && p() && e('1'); // 测试转换bug类型的目标对象
r(isset($bugResult['attrs']) && is_array($bugResult['attrs'])) && p() && e('1'); // 测试bug返回attrs属性

/* 测试转换未知类型的目标对象 */
$unknownResult = $zai->convertTargetToMarkdownTest('unknowntype', $unknownTarget);
r(isset($unknownResult['content'])) && p() && e('1'); // 测试转换未知类型的目标对象
r(isset($unknownResult['title']) && !empty($unknownResult['title'])) && p() && e('1'); // 测试未知类型返回title
r(isset($unknownResult['attrs']) && is_array($unknownResult['attrs'])) && p() && e('1'); // 测试未知类型返回attrs属性

/* 测试转换具有name属性的目标对象 */
$namedResult = $zai->convertTargetToMarkdownTest('customtype', $namedTarget);
r(isset($namedResult['title']) && !empty($namedResult['title'])) && p() && e('1'); // 测试转换具有name属性的目标对象
r(isset($namedResult['attrs']) && is_array($namedResult['attrs'])) && p() && e('1'); // 测试name对象返回attrs属性

/* 验证默认属性设置 */
r($unknownResult['attrs']['objectType']) && p() && e('unknowntype'); // 验证objectType属性
r($unknownResult['attrs']['objectID']) && p() && e('1'); // 验证objectID属性
r($unknownResult['attrs']['objectKey']) && p() && e('unknowntype-1'); // 验证objectKey属性

/* 验证story类型的特殊属性 */
r($storyResult['attrs']['objectType']) && p() && e('story'); // 验证story的objectType
r($storyResult['attrs']['objectID']) && p() && e('1'); // 验证story的objectID

/* 验证bug类型的特殊属性 */
r($bugResult['attrs']['objectType']) && p() && e('bug'); // 验证bug的objectType
r($bugResult['attrs']['objectID']) && p() && e('1'); // 验证bug的objectID
