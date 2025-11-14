#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::processDateVar();
timeout=0
cid=17416

- 测试空字符串 @1
- 测试普通字符串 @normal_string
- 测试$MONDAY占位符 @1
- 测试$SUNDAY占位符 @1
- 测试$MONTHBEGIN占位符 @1
- 测试$MONTHEND占位符 @1
- 测试datetime类型 @1
- 测试null值 @1
- 测试数字输入 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

$pivotTest = new pivotTest();

// 计算期望的日期值
$monday = date('Y-m-d', time() - (date('N') - 1) * 24 * 3600);
$sunday = date('Y-m-d', time() + (7 - date('N')) * 24 * 3600);
$monthbegin = date('Y-m-d', time() - (date('j') - 1) * 24 * 3600);
$monthend = date('Y-m-d', time() + (date('t') - date('j')) * 24 * 3600);
$mondayDatetime = date('Y-m-d H:i:s', time() - (date('N') - 1) * 24 * 3600);

r($pivotTest->processDateVarTest('') === '') && p() && e('1');           // 测试空字符串
r($pivotTest->processDateVarTest('normal_string')) && p() && e('normal_string'); // 测试普通字符串
r($pivotTest->processDateVarTest('$MONDAY') == $monday) && p() && e('1');  // 测试$MONDAY占位符
r($pivotTest->processDateVarTest('$SUNDAY') == $sunday) && p() && e('1');  // 测试$SUNDAY占位符
r($pivotTest->processDateVarTest('$MONTHBEGIN') == $monthbegin) && p() && e('1'); // 测试$MONTHBEGIN占位符
r($pivotTest->processDateVarTest('$MONTHEND') == $monthend) && p() && e('1'); // 测试$MONTHEND占位符
r(substr($pivotTest->processDateVarTest('$MONDAY', 'datetime'), 0, 10) == $monday) && p() && e('1'); // 测试datetime类型
r($pivotTest->processDateVarTest(null) === '') && p() && e('1');           // 测试null值
r($pivotTest->processDateVarTest(123) === '') && p() && e('1');            // 测试数字输入