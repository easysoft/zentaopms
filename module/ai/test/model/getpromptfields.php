#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getPromptFields();
timeout=0
cid=0

- 执行aiTest模块的getPromptFieldsTest方法，参数是1  @5
- 执行aiTest模块的getPromptFieldsTest方法，参数是2  @5
- 执行aiTest模块的getPromptFieldsTest方法，参数是999  @2
- 执行aiTest模块的getPromptFieldsTest方法，参数是3  @5
- 执行aiTest模块的getPromptFieldsTest方法，参数是0  @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

$table = zenData('ai_promptfield');
$table->id->range('1-20');
$table->appID->range('1{5},2{5},3{5},999{2},0{3}');
$table->name->range('字段1,字段2,字段3,字段4,字段5,字段6,字段7,字段8,字段9,字段10,字段11,字段12,字段13,字段14,字段15,字段16,字段17,字段18,字段19,字段20');
$table->type->range('text,textarea,radio,checkbox');
$table->options->range('选项1,选项2,选项3,选项4,选项5,[]{15}');
$table->required->range('1,0');
$table->gen(20);

su('admin');

$aiTest = new aiTest();

r(count($aiTest->getPromptFieldsTest(1))) && p() && e('5');
r(count($aiTest->getPromptFieldsTest(2))) && p() && e('5');
r(count($aiTest->getPromptFieldsTest(999))) && p() && e('2');
r(count($aiTest->getPromptFieldsTest(3))) && p() && e('5');
r(count($aiTest->getPromptFieldsTest(0))) && p() && e('3');
