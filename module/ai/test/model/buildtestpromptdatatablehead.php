#!/usr/bin/env php
<?php

/**

title=测试 aiModel::buildTestPromptDataTableHead();
timeout=0
cid=0

- 构建两列表头第 1 行 @| 标题 | 优先级 |
- 构建两列表头第 2 行 @| --- | --- |
- 跳过不存在字段 @| 标题 |
- 构建单列表头分割行 @| --- |
- 空字段列表返回空表头 @|

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$aiTest   = new aiModelTest();
$titleData = array('stories' => array('title' => '标题', 'pri' => '优先级'));
$head     = explode("\n", trim($aiTest->buildTestPromptDataTableHeadTest('stories', array('title', 'pri'), $titleData)));
$skipHead = explode("\n", trim($aiTest->buildTestPromptDataTableHeadTest('stories', array('title', 'ghost'), $titleData)));
$singleHead = explode("\n", trim($aiTest->buildTestPromptDataTableHeadTest('stories', array('title'), $titleData)));
$emptyHead  = explode("\n", trim($aiTest->buildTestPromptDataTableHeadTest('stories', array(), $titleData)));

r($head)     && p('0') && e('| 标题 | 优先级 |');
r($head)     && p('1') && e('| --- | --- |');
r($skipHead) && p('0') && e('| 标题 |');
r($singleHead) && p('1') && e('| --- |');
r($emptyHead)  && p('0') && e('|');
