#!/usr/bin/env php
<?php

/**

title=测试 aiModel::checkDuplicatedAppName();
timeout=0
cid=15001

- 执行aiTest模块的checkDuplicatedAppNameTest方法，参数是'测试应用1', 5  @1
- 执行aiTest模块的checkDuplicatedAppNameTest方法，参数是'不存在的应用', 10  @0
- 执行aiTest模块的checkDuplicatedAppNameTest方法，参数是'测试应用2', 3  @0
- 执行aiTest模块的checkDuplicatedAppNameTest方法，参数是'', 1  @0
- 执行aiTest模块的checkDuplicatedAppNameTest方法，参数是'已删除应用', 1  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$table = zenData('ai_miniprogram');
$table->id->range('1-6');
$table->name->range('测试应用1{2}, 测试应用2, 测试应用3, 唯一应用, 已删除应用');
$table->category->range('工具{3}, 效率{2}, 测试{1}');
$table->desc->range('这是一个测试应用{6}');
$table->model->range('1{6}');
$table->icon->range('writinghand-7{6}');
$table->createdBy->range('admin{6}');
$table->createdDate->range('`2023-01-01 10:00:00`');
$table->editedBy->range('admin{6}');
$table->editedDate->range('`2023-01-01 10:00:00`');
$table->published->range('0{6}');
$table->deleted->range('0{5}, 1{1}');
$table->prompt->range('测试提示词{6}');
$table->builtIn->range('0{6}');
$table->gen(6);

su('admin');

$aiTest = new aiModelTest();

r($aiTest->checkDuplicatedAppNameTest('测试应用1', 5)) && p() && e('1');
r($aiTest->checkDuplicatedAppNameTest('不存在的应用', 10)) && p() && e('0');
r($aiTest->checkDuplicatedAppNameTest('测试应用2', 3)) && p() && e('0');
r($aiTest->checkDuplicatedAppNameTest('', 1)) && p() && e('0');
r($aiTest->checkDuplicatedAppNameTest('已删除应用', 1)) && p() && e('0');