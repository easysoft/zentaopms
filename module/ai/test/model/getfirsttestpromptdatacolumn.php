#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getFirstTestPromptDataColumn();
timeout=0
cid=0

- 返回第一个存在测试数据的字段 @title
- 跳过不存在字段后返回下一个字段 @pri
- 无匹配字段时返回空字符串 @
- 空字段列表返回空字符串 @
- 分组不存在时返回空字符串 @

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$aiTest  = new aiModelTest();
$testData = array('stories' => array('title' => array('需求一'), 'pri' => array('5')));

r($aiTest->getFirstTestPromptDataColumnTest('stories', array('title', 'pri'), $testData)) && p() && e('title');
r($aiTest->getFirstTestPromptDataColumnTest('stories', array('ghost', 'pri'), $testData)) && p() && e('pri');
r($aiTest->getFirstTestPromptDataColumnTest('stories', array('ghost'), $testData) === '') && p() && e('1');
r($aiTest->getFirstTestPromptDataColumnTest('stories', array(), $testData) === '')        && p() && e('1');
r($aiTest->getFirstTestPromptDataColumnTest('tasks', array('name'), $testData) === '')    && p() && e('1');
