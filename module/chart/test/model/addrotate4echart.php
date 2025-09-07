#!/usr/bin/env php
<?php

/**

title=测试 chartModel::addRotate4Echart();
timeout=0
cid=0

- 执行chartModel模块的addRotate4Echart方法，参数是$options1, $settings1, 'line' 属性xAxis @30
属性axisLabel @30
属性rotate @30
- 执行chartModel模块的addRotate4Echart方法，参数是$options2, $settings2, 'cluBarX' 属性yAxis @30
属性axisLabel @30
属性rotate @30
- 执行chartModel模块的addRotate4Echart方法，参数是$options3, $settings3, 'stackedBar' 属性xAxis @30
属性axisLabel @30
属性rotate @30
- 执行chartModel模块的addRotate4Echart方法，参数是$options3, $settings3, 'stackedBar' 属性yAxis @30
属性axisLabel @30
属性rotate @30
- 执行$result4['series'][0]['type'] @0
- 执行$result5['xAxis']['axisLabel']['rotate'] @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

global $tester;
$chartModel = $tester->loadModel('chart');

// 步骤1：测试支持旋转的图表类型且设置了rotateX
$options1 = array('xAxis' => array('data' => array('A', 'B', 'C')), 'yAxis' => array('type' => 'value'));
$settings1 = array('rotateX' => 'use');
r($chartModel->addRotate4Echart($options1, $settings1, 'line')) && p('xAxis,axisLabel,rotate') && e('30');

// 步骤2：测试支持旋转的图表类型且设置了rotateY
$options2 = array('xAxis' => array('data' => array('A', 'B', 'C')), 'yAxis' => array('type' => 'value'));
$settings2 = array('rotateY' => 'use');
r($chartModel->addRotate4Echart($options2, $settings2, 'cluBarX')) && p('yAxis,axisLabel,rotate') && e('30');

// 步骤3：测试支持旋转的图表类型且同时设置了rotateX和rotateY，检查xAxis
$options3 = array('xAxis' => array('data' => array('A', 'B', 'C')), 'yAxis' => array('type' => 'value'));
$settings3 = array('rotateX' => 'use', 'rotateY' => 'use');
r($chartModel->addRotate4Echart($options3, $settings3, 'stackedBar')) && p('xAxis,axisLabel,rotate') && e('30');

// 步骤4：测试支持旋转的图表类型且同时设置了rotateX和rotateY，检查yAxis
r($chartModel->addRotate4Echart($options3, $settings3, 'stackedBar')) && p('yAxis,axisLabel,rotate') && e('30');

// 步骤5：测试不支持旋转的图表类型
$options4 = array('series' => array(array('type' => 'pie', 'data' => array('A', 'B', 'C'))));
$settings4 = array('rotateX' => 'use');
$result4 = $chartModel->addRotate4Echart($options4, $settings4, 'pie');
r(isset($result4['series'][0]['type'])) && p() && e('0');

// 步骤6：测试支持旋转但未设置rotate选项
$options5 = array('xAxis' => array('data' => array('A', 'B', 'C')), 'yAxis' => array('type' => 'value'));
$settings5 = array();
$result5 = $chartModel->addRotate4Echart($options5, $settings5, 'line');
r(isset($result5['xAxis']['axisLabel']['rotate'])) && p() && e('0');