#!/usr/bin/env php
<?php
global $app, $lang;

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$app->loadLang('bi');

/**

title=测试 biModel::quoteFilterValue();
timeout=0
cid=0

- 单个字符串值转为字面量并转义引号 @1
- 数组值按项转为字面量列表 @1
- 数字值转为字面量 @1
- 对象值拒绝请求 @1
- 嵌套数组值拒绝请求 @1

*/

$testObj = new biModelTest();

r($testObj->quoteFilterValueTest("a'b") === "'a\\'b'")                       && p() && e(1); // 单个字符串值转为字面量并转义引号
r($testObj->quoteFilterValueTest(array('a', 'b')) === "'a', 'b'")            && p() && e(1); // 数组值按项转为字面量列表
r($testObj->quoteFilterValueTest(123) === "'123'")                           && p() && e(1); // 数字值转为字面量
r($testObj->quoteFilterValueTest(new stdClass()) === $lang->bi->errorIllegalFilter['value']) && p() && e(1); // 对象值拒绝请求
r($testObj->quoteFilterValueTest(array(array('a'))) === $lang->bi->errorIllegalFilter['value']) && p() && e(1); // 嵌套数组值拒绝请求
