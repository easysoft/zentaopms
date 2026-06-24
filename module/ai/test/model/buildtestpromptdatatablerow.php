#!/usr/bin/env php
<?php

/**

title=测试 aiModel::buildTestPromptDataTableRow();
timeout=0
cid=0

- 构建完整表格行 @| 需求一 | 5 |
- 缺少字段时跳过该单元格 @| 需求二 |
- 索引不存在时返回空表格行 @|

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$aiTest  = new aiModelTest();
$testData = array('stories' => array('title' => array('需求一', '需求二'), 'pri' => array('5')));
$pathInfo = array('title', 'pri');

r(trim($aiTest->buildTestPromptDataTableRowTest('stories', $pathInfo, $testData, 0))) && p() && e('| 需求一 | 5 |');
r(trim($aiTest->buildTestPromptDataTableRowTest('stories', $pathInfo, $testData, 1))) && p() && e('| 需求二 |');
r(trim($aiTest->buildTestPromptDataTableRowTest('stories', $pathInfo, $testData, 3))) && p() && e('|');
