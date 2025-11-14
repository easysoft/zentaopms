#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::processDTableCols();
timeout=0
cid=17417

- 执行$result1) && is_array($result1 @1
- 执行$result2) && is_array($result2 @1
- 执行$result3 @1
- 执行$result4) && is_array($result4 @1
- 执行$result5) && is_array($result5 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

$pivotTest = new pivotTest();

// 测试1：多列输入，验证返回数组不为空
$result1 = $pivotTest->processDTableColsTest(array('id' => array('title' => 'ID'), 'name' => array('title' => '名称'), 'status' => array('title' => '状态')));
r(!empty($result1) && is_array($result1)) && p() && e('1');

// 测试2：单列输入，验证返回数组不为空
$result2 = $pivotTest->processDTableColsTest(array('id' => array('title' => 'ID')));
r(!empty($result2) && is_array($result2)) && p() && e('1');

// 测试3：空输入，验证返回数组
$result3 = $pivotTest->processDTableColsTest(array());
r(is_array($result3)) && p() && e('1');

// 测试4：特殊字符测试
$result4 = $pivotTest->processDTableColsTest(array('field_@#' => array('title' => 'Title@#$')));
r(!empty($result4) && is_array($result4)) && p() && e('1');

// 测试5：中文标题测试
$result5 = $pivotTest->processDTableColsTest(array('项目' => array('title' => '项目管理')));
r(!empty($result5) && is_array($result5)) && p() && e('1');