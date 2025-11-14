#!/usr/bin/env php
<?php

/**

title=测试 chartModel::addFormatter4Echart();
timeout=0
cid=15562

- 执行$result1 @1
- 执行$result2 @1
- 执行$result3 @1
- 执行$result4 @1
- 执行$result5 @1
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
$result1 = $chartTest->addFormatter4EchartTest(array('series' => array(array('type' => 'liquidFill', 'data' => array(0.75), 'label' => array())), 'tooltip' => array('show' => true)), 'waterpolo');
$result2 = $chartTest->addFormatter4EchartTest(array('series' => array(), 'xAxis' => array('type' => 'category', 'data' => array()), 'yAxis' => array('type' => 'value')), 'line');
$result3 = $chartTest->addFormatter4EchartTest(array('series' => array(), 'xAxis' => array('type' => 'category'), 'yAxis' => array('type' => 'value')), 'cluBarX');
$result4 = $chartTest->addFormatter4EchartTest(array('series' => array(array('type' => 'pie', 'data' => array())), 'legend' => array()), 'pie');
$result5 = $chartTest->addFormatter4EchartTest(array('series' => array(), 'xAxis' => array('type' => 'bar'), 'yAxis' => array()), 'stackedBar');
$result6 = $chartTest->addFormatter4EchartTest(array('series' => array(), 'xAxis' => array(), 'yAxis' => array('type' => 'category')), 'cluBarY');
$result7 = $chartTest->addFormatter4EchartTest(array(), 'line');

r(is_array($result1)) && p() && e('1');
r(is_array($result2)) && p() && e('1');
r(is_array($result3)) && p() && e('1');
r(is_array($result4)) && p() && e('1');
r(is_array($result5)) && p() && e('1');
r(is_array($result6)) && p() && e('1');
r(is_array($result7)) && p() && e('1');