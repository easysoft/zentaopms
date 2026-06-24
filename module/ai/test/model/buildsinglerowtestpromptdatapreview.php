#!/usr/bin/env php
<?php

/**

title=测试 aiModel::buildSingleRowTestPromptDataPreview();
timeout=0
cid=0

- 普通字段生成标题和值 @1
- task.story 字段被跳过 @0
- 缺少字段时返回空字符串 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$aiTest   = new aiModelTest();
$titleData = array('story' => array('title' => '标题', 'spec' => '描述'), 'task' => array('story' => '相关需求'));
$testData  = array('story' => array('title' => '需求标题', 'spec' => '需求描述'), 'task' => array('story' => '需求一'));

$storyPreview = $aiTest->buildSingleRowTestPromptDataPreviewTest('story', array('title', 'spec'), $titleData, $testData);
$taskPreview  = $aiTest->buildSingleRowTestPromptDataPreviewTest('task', array('story'), $titleData, $testData);
$emptyPreview = $aiTest->buildSingleRowTestPromptDataPreviewTest('story', array('ghost'), $titleData, $testData);

r(strpos($storyPreview, "##### 标题：\n需求标题") !== false) && p() && e('1');
r(strpos($taskPreview, '相关需求') === false)                 && p() && e('1');
r($emptyPreview === '')                                      && p() && e('1');
