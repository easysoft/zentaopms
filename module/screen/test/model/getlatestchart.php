#!/usr/bin/env php
<?php

/**

title=测试 screenModel::getLatestChart();
timeout=0
cid=0

- 执行screenTest模块的getLatestChartTest方法，参数是$selectComponent 属性key @Select
- 执行screenTest模块的getLatestChartTest方法，参数是$noSourceComponent 属性key @test
- 执行screenTest模块的getLatestChartTest方法，参数是$metricComponent 属性hasComponent @1
- 执行screenTest模块的getLatestChartTest方法，参数是$chartComponent 属性hasComponent @1
- 执行screenTest模块的getLatestChartTest方法，参数是$pivotComponent 属性hasComponent @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

// 设置所需的objectTables配置
global $config;
$config->objectTables['chart'] = TABLE_CHART;
$config->objectTables['pivot'] = TABLE_PIVOT;
$config->objectTables['metric'] = TABLE_METRIC;

// 准备测试数据
zenData('metric')->loadYaml('metric_getlatestchart', false, 2)->gen(5);
zenData('chart')->loadYaml('chart_getlatestchart', false, 2)->gen(3);
zenData('pivot')->loadYaml('pivot_getlatestchart', false, 2)->gen(3);

// 登录管理员用户
su('admin');

// 创建测试实例
$screenTest = new screenTest();

// 测试步骤1：测试key为Select的组件直接返回
$selectComponent = new stdClass();
$selectComponent->key = 'Select';
$selectComponent->chartConfig = new stdClass();
$selectComponent->chartConfig->sourceID = '1';
r($screenTest->getLatestChartTest($selectComponent)) && p('key') && e('Select');

// 测试步骤2：测试没有sourceID的组件直接返回
$noSourceComponent = new stdClass();
$noSourceComponent->key = 'test';
$noSourceComponent->chartConfig = new stdClass();
r($screenTest->getLatestChartTest($noSourceComponent)) && p('key') && e('test');

// 测试步骤3：测试metric类型图表组件处理
$metricComponent = new stdClass();
$metricComponent->key = 'metric';
$metricComponent->chartConfig = new stdClass();
$metricComponent->chartConfig->sourceID = '1';
$metricComponent->chartConfig->package = 'Metrics';
r($screenTest->getLatestChartTest($metricComponent)) && p('hasComponent') && e('1');

// 测试步骤4：测试chart类型图表组件处理
$chartComponent = new stdClass();
$chartComponent->key = 'chart';
$chartComponent->chartConfig = new stdClass();
$chartComponent->chartConfig->sourceID = '1';
$chartComponent->chartConfig->package = 'Charts';
r($screenTest->getLatestChartTest($chartComponent)) && p('hasComponent') && e('1');

// 测试步骤5：测试pivot类型图表组件处理
$pivotComponent = new stdClass();
$pivotComponent->key = 'pivot';
$pivotComponent->chartConfig = new stdClass();
$pivotComponent->chartConfig->sourceID = '1';
$pivotComponent->chartConfig->package = 'Tables';
r($screenTest->getLatestChartTest($pivotComponent)) && p('hasComponent') && e('1');