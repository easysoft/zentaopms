#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/transfer.class.php';
zdTable('product')->gen(10);
$module = zdTable('module');
$module->root->range('1');
$module->type->range('story');
$module->gen(10);
su('admin');

$rows[2] = new stdclass();
$rows[3] = new stdclass();

$rows[2]->id       = 1;
$rows[2]->product  = '正常产品1(#1)';
$rows[2]->module   = '这是一个模块1(#1)' . "\n" . '这是一个模块3(#3)';
$rows[2]->branch   = 0;
$rows[2]->title    = '需求1';
$rows[2]->spec     = '需求1描述';
$rows[2]->source   = '客户';
$rows[2]->pri      = 1;
$rows[2]->keywords = 1;
$rows[2]->estimate = 1;

$rows[3]->id       = 2;
$rows[3]->product  = '正常产品2(#2)';
$rows[3]->module   = '这是一个模块2(#2)';
$rows[3]->branch   = '分支一';
$rows[3]->title    = '需求2';
$rows[3]->spec     = '需求2描述';
$rows[3]->source   = '市场';
$rows[3]->pri      = 2;
$rows[3]->keywords = 2;
$rows[3]->estimate = 2;

/**

title=测试 transfer->extractElements();
timeout=0
cid=1

- 测试当产品为单选时，是否正常识别第3条的product属性 @2
- 测试语言项是否正常第3条的source属性 @market
- 测试优先级语言项第3条的pri属性 @2
- 测试当分支为空时是否显示原值第3条的branch属性 @分支一
- 测试当需求的模块为多选时，是都正常识别属性1 @3

*/

$transfer = new transferTest();
$result   = $transfer->parseExcelDropdownValuesTest('story', $rows);

r($result) && p('3:product') && e('2');         // 测试当产品为单选时，是否正常识别
r($result) && p('3:source')  && e('market');    // 测试语言项是否正常
r($result) && p('3:pri')     && e('2');         // 测试优先级语言项
r($result) && p('3:branch')  && e('分支一');    // 测试当分支为空时是否显示原值

r(explode(',', $result[2]->module)) && p('1')  && e('3'); // 测试当需求的模块为多选时，是都正常识别
