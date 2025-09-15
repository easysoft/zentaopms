#!/usr/bin/env php
<?php

/**

title=测试 chartModel::addFormatter4Echart();
cid=0

- 测试步骤1：水球图类型添加formatter >> 期望添加水球图的formatter
- 测试步骤2：折线图类型添加formatter >> 期望添加标签formatter
- 测试步骤3：簇状条形图类型添加formatter >> 期望添加标签formatter
- 测试步骤4：饼图类型不添加formatter >> 期望原options不变
- 测试步骤5：空选项数组处理 >> 期望返回空数组

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/chart.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$chartTest = new chartTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
// 测试步骤1：水球图类型，应该添加formatter到series和tooltip
$waterpoloOptions = array(
    'series' => array(array('label' => array(), 'type' => 'liquidFill')),
    'tooltip' => array()
);
r($chartTest->addFormatter4EchartTest($waterpoloOptions, 'waterpolo')) && p('series:0:label:formatter') && e("RAWJS<(params) => (params.value * 100).toFixed(2) + '%'>RAWJS");

// 测试步骤2：折线图类型，应该添加标签formatter到xAxis和yAxis
$lineOptions = array(
    'xAxis' => array('axisLabel' => array()),
    'yAxis' => array('axisLabel' => array())
);
r($chartTest->addFormatter4EchartTest($lineOptions, 'line')) && p('xAxis:axisLabel:formatter') && e("RAWJS<(value) => {value = value.toString(); return value.length <= 11 ? value : value.substring(0, 11) + '...'}>");

// 测试步骤3：簇状条形图类型，应该添加标签formatter
$cluBarOptions = array(
    'xAxis' => array(),
    'yAxis' => array()
);
r($chartTest->addFormatter4EchartTest($cluBarOptions, 'cluBarX')) && p('xAxis:axisLabel:formatter') && e("RAWJS<(value) => {value = value.toString(); return value.length <= 11 ? value : value.substring(0, 11) + '...'}>");

// 测试步骤4：饼图类型，不在canLabelRotate配置中，应该保持原样
$pieOptions = array(
    'series' => array(array('type' => 'pie')),
    'legend' => array()
);
r($chartTest->addFormatter4EchartTest($pieOptions, 'pie')) && p('series:0:type') && e('pie');

// 测试步骤5：空选项数组，应该返回空数组
r($chartTest->addFormatter4EchartTest(array(), 'line')) && p() && e('~~');