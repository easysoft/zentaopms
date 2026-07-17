#!/usr/bin/env php
<?php

/**

title=测试 aiModel::buildTestPromptDataPreview();
timeout=0
cid=0

- 预览包含单行数据 @1
- 预览包含多行数据 @1
- 缺少标题数据的分组被跳过 @1
- 预览包含多行分组标题 @1
- 空分组返回空字符串 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$aiTest = new aiModelTest();

$categorized = array('story' => array('title'), 'stories' => array('title'), 'ghost' => array('name'));
$titleData   = array('story' => array('title' => '标题'), 'stories' => array('common' => '需求', 'title' => '标题'));
$testData    = array('story' => array('title' => '需求标题'), 'stories' => array('title' => array('需求一', '需求二')), 'ghost' => array('name' => '幽灵'));
$preview     = $aiTest->buildTestPromptDataPreviewTest('story', $categorized, $titleData, $testData);
$emptyPreview = $aiTest->buildTestPromptDataPreviewTest('story', array(), $titleData, $testData);

r(strpos($preview, "##### 标题：\n需求标题") !== false) && p() && e('1');
r(strpos($preview, '| 需求一 |') !== false)             && p() && e('1');
r(strpos($preview, '幽灵') === false)                   && p() && e('1');
r(strpos($preview, '##### 需求：') !== false)           && p() && e('1');
r($emptyPreview === '')                                 && p() && e('1');
