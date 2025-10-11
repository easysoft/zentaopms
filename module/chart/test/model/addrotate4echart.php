#!/usr/bin/env php
<?php

/**

title=测试 chartModel::addRotate4Echart();
timeout=0
cid=0

- 执行chartTest模块的addRotate4EchartTest方法，参数是$options1, $settings1, 'line' 第xAxis条的axisLabel:rotate属性 @30
- 执行chartTest模块的addRotate4EchartTest方法，参数是$options2, $settings2, 'cluBarX' 第yAxis条的axisLabel:rotate属性 @30
- 执行chartTest模块的addRotate4EchartTest方法，参数是$options3, $settings3, 'stackedBar' 第xAxis条的axisLabel:rotate属性 @30
- 执行chartTest模块的addRotate4EchartTest方法，参数是$options3, $settings3, 'stackedBar' 第yAxis条的axisLabel:rotate属性 @30
- 执行chartTest模块的addRotate4EchartTest方法，参数是$options4, $settings4, 'pie' 第xAxis条的axisLabel:rotate属性 @~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/chart.unittest.class.php';

su('admin');

$chartTest = new chartTest();

// 步骤1：测试支持旋转的图表类型且设置了rotateX
$options1 = array('xAxis' => array('data' => array('A', 'B', 'C')), 'yAxis' => array('type' => 'value'));
$settings1 = array('rotateX' => 'use');
r($chartTest->addRotate4EchartTest($options1, $settings1, 'line')) && p('xAxis:axisLabel:rotate') && e('30');

// 步骤2：测试支持旋转的图表类型且设置了rotateY
$options2 = array('xAxis' => array('data' => array('A', 'B', 'C')), 'yAxis' => array('type' => 'value'));
$settings2 = array('rotateY' => 'use');
r($chartTest->addRotate4EchartTest($options2, $settings2, 'cluBarX')) && p('yAxis:axisLabel:rotate') && e('30');

// 步骤3：测试支持旋转的图表类型且同时设置了rotateX和rotateY，检查xAxis
$options3 = array('xAxis' => array('data' => array('A', 'B', 'C')), 'yAxis' => array('type' => 'value'));
$settings3 = array('rotateX' => 'use', 'rotateY' => 'use');
r($chartTest->addRotate4EchartTest($options3, $settings3, 'stackedBar')) && p('xAxis:axisLabel:rotate') && e('30');

// 步骤4：测试支持旋转的图表类型且同时设置了rotateX和rotateY，检查yAxis
r($chartTest->addRotate4EchartTest($options3, $settings3, 'stackedBar')) && p('yAxis:axisLabel:rotate') && e('30');

// 步骤5：测试不支持旋转的图表类型
$options4 = array('series' => array(array('type' => 'pie', 'data' => array('A', 'B', 'C'))));
$settings4 = array('rotateX' => 'use');
r($chartTest->addRotate4EchartTest($options4, $settings4, 'pie')) && p('xAxis:axisLabel:rotate') && e('~~');