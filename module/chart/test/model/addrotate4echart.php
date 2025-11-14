#!/usr/bin/env php
<?php

/**

title=测试 chartModel::addRotate4Echart();
timeout=0
cid=15563

- 执行$result1 @1
- 执行$result2['xAxis']['axisLabel']['rotate']) ? $result2['xAxis']['axisLabel']['rotate'] : 0 @30
- 执行$result3['yAxis']['axisLabel']['rotate']) ? $result3['yAxis']['axisLabel']['rotate'] : 0 @30
- 执行$result4['xAxis']['axisLabel']['rotate']) ? $result4['xAxis']['axisLabel']['rotate'] : 0 @30
- 执行$result5['yAxis']['axisLabel']['rotate']) ? $result5['yAxis']['axisLabel']['rotate'] : 0 @30
- 执行$result6 @1
- 执行$result7 @1

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/chart.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$chartTest = new chartTest();

// 4. 执行测试步骤(至少5个)
$result1 = $chartTest->addRotate4EchartTest(array('series' => array(array('type' => 'pie', 'data' => array())), 'xAxis' => array(), 'yAxis' => array()), array('type' => 'pie', 'rotateX' => 'use', 'rotateY' => 'use'), 'pie');
$result2 = $chartTest->addRotate4EchartTest(array('series' => array(), 'xAxis' => array('type' => 'category'), 'yAxis' => array('type' => 'value')), array('type' => 'line', 'rotateX' => 'use'), 'line');
$result3 = $chartTest->addRotate4EchartTest(array('series' => array(), 'xAxis' => array('type' => 'category'), 'yAxis' => array('type' => 'value')), array('type' => 'cluBarX', 'rotateY' => 'use'), 'cluBarX');
$result4 = $chartTest->addRotate4EchartTest(array('series' => array(), 'xAxis' => array('type' => 'category'), 'yAxis' => array('type' => 'value')), array('type' => 'stackedBar', 'rotateX' => 'use', 'rotateY' => 'use'), 'stackedBar');
$result5 = $result4;
$result6 = $chartTest->addRotate4EchartTest(array('series' => array(), 'xAxis' => array('type' => 'value'), 'yAxis' => array('type' => 'category')), array('type' => 'cluBarY', 'rotateX' => 'no'), 'cluBarY');
$result7 = $chartTest->addRotate4EchartTest(array('series' => array(), 'xAxis' => array('type' => 'value'), 'yAxis' => array('type' => 'category')), array('type' => 'stackedBarY'), 'stackedBarY');

r(is_array($result1)) && p() && e('1');
r(isset($result2['xAxis']['axisLabel']['rotate']) ? $result2['xAxis']['axisLabel']['rotate'] : 0) && p() && e('30');
r(isset($result3['yAxis']['axisLabel']['rotate']) ? $result3['yAxis']['axisLabel']['rotate'] : 0) && p() && e('30');
r(isset($result4['xAxis']['axisLabel']['rotate']) ? $result4['xAxis']['axisLabel']['rotate'] : 0) && p() && e('30');
r(isset($result5['yAxis']['axisLabel']['rotate']) ? $result5['yAxis']['axisLabel']['rotate'] : 0) && p() && e('30');
r(is_array($result6)) && p() && e('1');
r(is_array($result7)) && p() && e('1');