#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::completePivot();
timeout=0
cid=17361

- 执行$result1->settings @1
- 执行$result2->settings ===  @1
- 执行$result3->name == '测试透视表3 @1
- 执行$result4->desc == '复杂描述 @1
- 执行$result5->settings['chart']['type'] @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

$pivotTest = new pivotTest();

// 测试步骤1：正常情况 - 测试settings JSON解码
$pivot1 = new stdClass();
$pivot1->settings = '{"columns":[{"field":"id","label":"ID"}]}';
$pivot1->name = '{"zh-cn":"测试透视表1","en":"Test Pivot 1"}';
$pivot1->desc = '{"zh-cn":"测试描述1","en":"Test Description 1"}';
$result1 = $pivotTest->completePivotTest($pivot1);
r(is_array($result1->settings)) && p() && e('1');

// 测试步骤2：边界值 - settings为空的情况
$pivot2 = new stdClass();
$pivot2->settings = '';
$pivot2->name = '{"zh-cn":"测试透视表2","en":"Test Pivot 2"}';
$result2 = $pivotTest->completePivotTest($pivot2);
r($result2->settings === '') && p() && e('1');

// 测试步骤3：正常多语言name处理
$pivot3 = new stdClass();
$pivot3->name = '{"zh-cn":"测试透视表3","en":"Test Pivot 3"}';
$result3 = $pivotTest->completePivotTest($pivot3);
r($result3->name == '测试透视表3') && p() && e('1');

// 测试步骤4：正常多语言desc处理
$pivot4 = new stdClass();
$pivot4->desc = '{"zh-cn":"复杂描述","en":"Complex Description"}';
$result4 = $pivotTest->completePivotTest($pivot4);
r($result4->desc == '复杂描述') && p() && e('1');

// 测试步骤5：复杂settings解码
$pivot5 = new stdClass();
$pivot5->settings = '{"chart":{"type":"bar"}}';
$result5 = $pivotTest->completePivotTest($pivot5);
r(isset($result5->settings['chart']['type'])) && p() && e('1');