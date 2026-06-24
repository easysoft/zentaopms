#!/usr/bin/env php
<?php

/**

title=测试 aiModel::buildMultiRowTestPromptDataPreview();
timeout=0
cid=0

- 普通多行数据包含分组标题 @1
- 普通多行数据包含第一行 @1
- release bugs 使用纯文本列表 @1
- 缺少 common 时返回空字符串 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$aiTest   = new aiModelTest();
$titleData = array
(
    'stories' => array('common' => '需求', 'title' => '标题', 'pri' => '优先级'),
    'bugs'    => array('common' => 'Bug', 'title' => '标题')
);
$testData = array
(
    'stories' => array('title' => array('需求一', '需求二'), 'pri' => array('5', '3')),
    'bugs'    => array('title' => 'Bug一、Bug二')
);

$preview        = $aiTest->buildMultiRowTestPromptDataPreviewTest('story', 'stories', array('title', 'pri'), $titleData, $testData);
$releasePreview = $aiTest->buildMultiRowTestPromptDataPreviewTest('release', 'bugs', array('title'), $titleData, $testData);
$emptyPreview   = $aiTest->buildMultiRowTestPromptDataPreviewTest('story', 'tasks', array('name'), array('tasks' => array('name' => '任务')), array('tasks' => array('name' => array('任务一'))));

r(strpos($preview, '##### 需求：') !== false)          && p() && e('1');
r(strpos($preview, '| 需求一 | 5 |') !== false)        && p() && e('1');
r(strpos($releasePreview, "Bug一、Bug二\n") !== false) && p() && e('1');
r($emptyPreview === '')                                && p() && e('1');
