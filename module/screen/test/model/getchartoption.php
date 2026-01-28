#!/usr/bin/env php
<?php

/**

title=测试 screenModel::getChartOption();
timeout=0
cid=18239

- 执行$result1) || is_object($result1) || $result1 ===  @1
- 执行$result2) || is_object($result2) || $result2 ===  @1
- 执行$result3) || is_object($result3) || $result3 ===  @1
- 执行$result4) || is_object($result4) || $result4 ===  @1
- 执行$result5 ===  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

// 创建测试实例
$screenTest = new screenModelTest();

// 创建模拟图表对象
$chart = new stdclass();
$chart->id = 1;
$chart->sql = 'SELECT 1 as test';
$chart->settings = json_encode(array());

// 创建模拟组件对象
$component = new stdclass();
$component->option = new stdclass();

// 测试步骤1：测试line类型
$component->type = 'line';
$result1 = $screenTest->objectModel->getChartOption($chart, $component);
r(is_string($result1) || is_object($result1) || $result1 === '') && p() && e(1);

// 测试步骤2：测试bar类型
$component->type = 'bar';
$result2 = $screenTest->objectModel->getChartOption($chart, $component);
r(is_string($result2) || is_object($result2) || $result2 === '') && p() && e(1);

// 测试步骤3：测试pie类型
$component->type = 'pie';
$result3 = $screenTest->objectModel->getChartOption($chart, $component);
r(is_string($result3) || is_object($result3) || $result3 === '') && p() && e(1);

// 测试步骤4：测试table类型
$component->type = 'table';
$result4 = $screenTest->objectModel->getChartOption($chart, $component);
r(is_string($result4) || is_object($result4) || $result4 === '') && p() && e(1);

// 测试步骤5：测试未知类型
$component->type = 'unknown';
$result5 = $screenTest->objectModel->getChartOption($chart, $component);
r($result5 === '') && p() && e(1);