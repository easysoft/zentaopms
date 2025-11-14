#!/usr/bin/env php
<?php

/**

title=测试 chartModel::genLineChart();
timeout=0
cid=15566

- 执行$result1
 - 第series[0]条的type属性 @line
 - 第xAxis条的type属性 @category
- 执行$result2['xAxis']['data'][0]) ? $result2['xAxis']['data'][0] :  @2023-01-01
- 执行chartTest模块的genLineChartSeriesCountTest方法，参数是'multiSeries'  @2
- 执行chartTest模块的genLineChartSeriesCountTest方法，参数是'empty'  @0
- 执行chartTest模块的genLineChartSeriesCountTest方法，参数是'normal'  @1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/chart.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$chartTest = new chartTest();

// 4. 执行测试步骤(至少5个)
$result1 = $chartTest->genLineChartTest('normal');
$result2 = $chartTest->genLineChartTest('dateSort');

r($result1) && p('series[0]:type;xAxis:type') && e('line,category');
r(isset($result2['xAxis']['data'][0]) ? $result2['xAxis']['data'][0] : '') && p() && e('2023-01-01');
r($chartTest->genLineChartSeriesCountTest('multiSeries')) && p() && e('2');
r($chartTest->genLineChartSeriesCountTest('empty')) && p() && e('0');
r($chartTest->genLineChartSeriesCountTest('normal')) && p() && e('1');