#!/usr/bin/env php
<?php

/**

title=测试 screenModel::buildLineChart();
timeout=0
cid=0

- 步骤1：无settings默认配置 @LineCommon
- 步骤2：有settings无sql处理 @1
- 步骤3：有settings和sql数据处理 @1
- 步骤4：空settings处理 @1
- 步骤5：无效json settings处理 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

su('admin');

$screenTest = new screenTest();

// 测试步骤1：无settings的组件构建默认折线图
$component1 = new stdclass();
$chart1 = new stdclass();
$chart1->settings = null;
$result1 = $screenTest->buildLineChartTest($component1, $chart1);
r($result1->key) && p() && e('LineCommon'); // 步骤1：无settings默认配置

// 测试步骤2：有settings但无sql的组件处理
$component2 = new stdclass();
$component2->option = new stdclass();
$chart2 = new stdclass();
$chart2->settings = '{"xaxis":[{"name":"product","field":"product"}],"yaxis":[{"name":"data1","field":"data1"}]}';
$chart2->sql = null;
$result2 = $screenTest->buildLineChartTest($component2, $chart2);
r(isset($result2->styles)) && p() && e('1'); // 步骤2：有settings无sql处理

// 测试步骤3：有settings和sql的数据处理
$component3 = new stdclass();
$component3->option = new stdclass();
$component3->option->dataset = new stdclass();
$chart3 = new stdclass();
$chart3->id = 1001;
$chart3->settings = '{"xaxis":[{"name":"date","field":"date"}],"yaxis":[{"name":"count","field":"count"}]}';
$chart3->sql = 'SELECT "2023-01-01" as date, 10 as count';
$chart3->driver = 'mysql';
$result3 = $screenTest->buildLineChartTest($component3, $chart3);
r(isset($result3->option->dataset)) && p() && e('1'); // 步骤3：有settings和sql数据处理

// 测试步骤4：空settings的处理 - 应该走默认配置路径
$component4 = new stdclass();
$chart4 = new stdclass();
$chart4->settings = '';
$result4 = $screenTest->buildLineChartTest($component4, $chart4);
$result4_expected = isset($result4->key) && $result4->key == 'LineCommon' ? '1' : '0';
r($result4_expected) && p() && e('1'); // 步骤4：空settings处理

// 测试步骤5：无效json settings处理 - 应该走有settings但处理失败的路径
$component5 = new stdclass();
$component5->option = new stdclass();
$chart5 = new stdclass();
$chart5->settings = 'invalid json';
$chart5->sql = null;
$result5 = $screenTest->buildLineChartTest($component5, $chart5);
r(isset($result5->styles)) && p() && e('1'); // 步骤5：无效json settings处理