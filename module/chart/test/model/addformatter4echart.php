#!/usr/bin/env php
<?php

/**

title=测试 chartModel::addFormatter4Echart();
timeout=0
cid=0

- 执行chartTest模块的addFormatter4EchartTest方法，参数是$waterpoloOptions, 'waterpolo' 第series条的0:label:formatter属性 @RAWJS<(params) => (params.value * 100).toFixed(2) + '%'>RAWJS
- return value.length <= 11 ? value : value.substring(0, 11) + '...'}>RAWJS");第xAxis条的axisLabel:formatter属性 @RAWJS<(value) => {value = value.toString(
- return value.length <= 11 ? value : value.substring(0, 11) + '...'}>RAWJS");第xAxis条的axisLabel:formatter属性 @RAWJS<(value) => {value = value.toString(
- 执行chartTest模块的addFormatter4EchartTest方法，参数是$pieOptions, 'pie' 第series条的0:type属性 @pie
- return value.length <= 11 ? value : value.substring(0, 11) + '...'}>RAWJS");第xAxis条的axisLabel:formatter属性 @RAWJS<(value) => {value = value.toString(

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/chart.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
// 不需要数据库数据准备，直接测试方法逻辑

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$chartTest = new chartTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
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
r($chartTest->addFormatter4EchartTest($lineOptions, 'line')) && p('xAxis:axisLabel:formatter') && e("RAWJS<(value) => {value = value.toString(); return value.length <= 11 ? value : value.substring(0, 11) + '...'}>RAWJS");

// 测试步骤3：簇状条形图类型，应该添加标签formatter
$cluBarOptions = array(
    'xAxis' => array(),
    'yAxis' => array()
);
r($chartTest->addFormatter4EchartTest($cluBarOptions, 'cluBarX')) && p('xAxis:axisLabel:formatter') && e("RAWJS<(value) => {value = value.toString(); return value.length <= 11 ? value : value.substring(0, 11) + '...'}>RAWJS");

// 测试步骤4：饼图类型，不在canLabelRotate配置中，应该保持原样
$pieOptions = array(
    'series' => array(array('type' => 'pie')),
    'legend' => array()
);
r($chartTest->addFormatter4EchartTest($pieOptions, 'pie')) && p('series:0:type') && e('pie');

// 测试步骤5：空选项数组输入line类型，仍会添加formatter
r($chartTest->addFormatter4EchartTest(array(), 'line')) && p('xAxis:axisLabel:formatter') && e("RAWJS<(value) => {value = value.toString(); return value.length <= 11 ? value : value.substring(0, 11) + '...'}>RAWJS");