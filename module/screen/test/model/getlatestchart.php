#!/usr/bin/env php
<?php

/**

title=测试 screenModel::getLatestChart();
timeout=0
cid=18242

- 执行$result1属性key @Select
- 执行$result2属性key @test
- 执行$result3属性hasComponent @1
- 执行$result4属性hasComponent @1
- 执行$result5属性hasComponent @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

// 准备测试数据
$chart = zenData('chart');
$chart->id->range('1-5');
$chart->name->range('测试图表1,测试图表2,测试图表3,测试图表4,测试图表5');
$chart->settings->range('{"package":"Charts"}');
$chart->gen(5);

$pivot = zenData('pivot');
$pivot->id->range('1-5');
$pivot->name->range('测试透视表1,测试透视表2,测试透视表3,测试透视表4,测试透视表5');
$pivot->settings->range('{"package":"Tables"}');
$pivot->gen(5);

$metric = zenData('metric');
$metric->id->range('1-5');
$metric->name->range('测试度量1,测试度量2,测试度量3,测试度量4,测试度量5');
$metric->gen(5);

// 登录管理员用户
su('admin');

// 创建测试实例
$screenTest = new screenTest();

// 测试步骤1：测试Select组件直接返回
$selectComponent = new stdClass();
$selectComponent->key = 'Select';
$selectComponent->chartConfig = new stdClass();
$selectComponent->chartConfig->sourceID = '1';
$result1 = $screenTest->getLatestChartTest($selectComponent);
r($result1) && p('key') && e('Select');

// 测试步骤2：测试无sourceID组件直接返回
$noSourceComponent = new stdClass();
$noSourceComponent->key = 'test';
$noSourceComponent->chartConfig = new stdClass();
$result2 = $screenTest->getLatestChartTest($noSourceComponent);
r($result2) && p('key') && e('test');

// 测试步骤3：测试Chart类型组件处理
$chartComponent = new stdClass();
$chartComponent->key = 'chart';
$chartComponent->chartConfig = new stdClass();
$chartComponent->chartConfig->sourceID = '1';
$chartComponent->chartConfig->package = 'Charts';
$result3 = $screenTest->getLatestChartTest($chartComponent);
r($result3) && p('hasComponent') && e('1');

// 测试步骤4：测试Pivot类型组件处理
$pivotComponent = new stdClass();
$pivotComponent->key = 'pivot';
$pivotComponent->chartConfig = new stdClass();
$pivotComponent->chartConfig->sourceID = '1';
$pivotComponent->chartConfig->package = 'Tables';
$result4 = $screenTest->getLatestChartTest($pivotComponent);
r($result4) && p('hasComponent') && e('1');

// 测试步骤5：测试Metric类型组件处理
$metricComponent = new stdClass();
$metricComponent->key = 'metric';
$metricComponent->chartConfig = new stdClass();
$metricComponent->chartConfig->sourceID = '1';
$metricComponent->chartConfig->package = 'Metrics';
$result5 = $screenTest->getLatestChartTest($metricComponent);
r($result5) && p('hasComponent') && e('1');