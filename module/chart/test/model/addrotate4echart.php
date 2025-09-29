#!/usr/bin/env php
<?php

/**

title=测试 chartModel::addRotate4Echart();
timeout=0
cid=0

- 执行$result1['xAxis']['axisLabel']['rotate']) && $result1['xAxis']['axisLabel']['rotate'] == 30 @1
- 执行$result2['yAxis']['axisLabel']['rotate']) && $result2['yAxis']['axisLabel']['rotate'] == 30 @1
- 执行$result3['xAxis']['axisLabel']['rotate']) && $result3['xAxis']['axisLabel']['rotate'] == 30 @1
- 执行$result3['yAxis']['axisLabel']['rotate']) && $result3['yAxis']['axisLabel']['rotate'] == 30 @1
- 执行$result4['xAxis']['axisLabel']['rotate']) && !isset($result4['yAxis']['axisLabel']['rotate'] @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/chart.unittest.class.php';

$chartTest = new chartTest();

// 步骤1：测试支持旋转的图表类型且设置了rotateX
$options1 = array('xAxis' => array('data' => array('A', 'B', 'C')), 'yAxis' => array('type' => 'value'));
$settings1 = array('rotateX' => 'use');
$result1 = $chartTest->addRotate4EchartTest($options1, $settings1, 'line');
r(isset($result1['xAxis']['axisLabel']['rotate']) && $result1['xAxis']['axisLabel']['rotate'] == 30) && p() && e('1');

// 步骤2：测试支持旋转的图表类型且设置了rotateY
$options2 = array('xAxis' => array('data' => array('A', 'B', 'C')), 'yAxis' => array('type' => 'value'));
$settings2 = array('rotateY' => 'use');
$result2 = $chartTest->addRotate4EchartTest($options2, $settings2, 'cluBarX');
r(isset($result2['yAxis']['axisLabel']['rotate']) && $result2['yAxis']['axisLabel']['rotate'] == 30) && p() && e('1');

// 步骤3：测试支持旋转的图表类型且同时设置了rotateX和rotateY，检查xAxis
$options3 = array('xAxis' => array('data' => array('A', 'B', 'C')), 'yAxis' => array('type' => 'value'));
$settings3 = array('rotateX' => 'use', 'rotateY' => 'use');
$result3 = $chartTest->addRotate4EchartTest($options3, $settings3, 'stackedBar');
r(isset($result3['xAxis']['axisLabel']['rotate']) && $result3['xAxis']['axisLabel']['rotate'] == 30) && p() && e('1');

// 步骤4：测试支持旋转的图表类型且同时设置了rotateX和rotateY，检查yAxis
r(isset($result3['yAxis']['axisLabel']['rotate']) && $result3['yAxis']['axisLabel']['rotate'] == 30) && p() && e('1');

// 步骤5：测试不支持旋转的图表类型
$options4 = array('series' => array(array('type' => 'pie', 'data' => array('A', 'B', 'C'))));
$settings4 = array('rotateX' => 'use');
$result4 = $chartTest->addRotate4EchartTest($options4, $settings4, 'pie');
r(!isset($result4['xAxis']['axisLabel']['rotate']) && !isset($result4['yAxis']['axisLabel']['rotate'])) && p() && e('1');