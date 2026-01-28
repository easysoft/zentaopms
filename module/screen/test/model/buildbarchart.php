#!/usr/bin/env php
<?php

/**

title=测试 screenModel::buildBarChart();
timeout=0
cid=18204

- 执行$result属性key @BarCrossrange
- 执行$result属性chartConfig->chartKey @VBarCrossrange
- 执行$result属性option->xAxis->type @category
- 执行$result属性request->requestHttpType @get
- 执行$result属性option->yAxis->type @value
- 执行$result2属性key @BarCrossrange
- 执行$result
 - 属性option->backgroundColor @rgba(0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$screenTest = new screenModelTest();

// 测试步骤1：无设置的图表配置
$chartWithoutSettings = new stdClass();
$chartWithoutSettings->settings = '';
$chartWithoutSettings->sql = '';

$component = new stdClass();
$component->option = new stdClass();

$result = $screenTest->buildBarChartTest($component, $chartWithoutSettings);

r($result) && p('key') && e('BarCrossrange');
r($result) && p('chartConfig->chartKey') && e('VBarCrossrange');
r($result) && p('option->xAxis->type') && e('category');
r($result) && p('request->requestHttpType') && e('get');
r($result) && p('option->yAxis->type') && e('value');

// 测试步骤6：有SQL但无设置的图表
$chartWithSqlNoSettings = new stdClass();
$chartWithSqlNoSettings->settings = '';
$chartWithSqlNoSettings->sql = 'SELECT name, count FROM test_table';

$result2 = $screenTest->buildBarChartTest($component, $chartWithSqlNoSettings);
r($result2) && p('key') && e('BarCrossrange');

// 测试步骤7：检查背景色设置
r($result) && p('option->backgroundColor') && e('rgba(0,0,0,0)');