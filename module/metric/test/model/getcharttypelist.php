#!/usr/bin/env php
<?php

/**

title=测试 metricModel::getChartTypeList();
timeout=0
cid=17083

- 步骤1：测试系统度量表头 @4
- 步骤2：测试对象度量表头（含date） @3
- 步骤3：测试对象度量表头（无date） @3
- 步骤4：测试空数组表头 @4
- 步骤5：测试非标准表头 @4
- 步骤6：验证对象度量不包含pie @0
- 步骤7：验证系统度量包含pie @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/calc.unittest.class.php';

$metric = new metricTest();

// 测试数据准备：不同类型的表头数据
$systemHeader = array();
$systemHeader[] = array('name' => 'value', 'title' => '数值', 'width' => 96);
$systemHeader[] = array('name' => 'calcTime', 'title' => '采集时间', 'width' => 128);

$objectHeaderWithDate = array();
$objectHeaderWithDate[] = array('name' => 'scope', 'title' => '产品名称', 'width' => 160);
$objectHeaderWithDate[] = array('name' => 'date', 'title' => '日期', 'width' => 96);
$objectHeaderWithDate[] = array('name' => 'value', 'title' => '数值', 'width' => 96);
$objectHeaderWithDate[] = array('name' => 'calcTime', 'title' => '采集时间', 'width' => 128);

$objectHeaderWithoutDate = array();
$objectHeaderWithoutDate[] = array('name' => 'scope', 'title' => '产品名称', 'width' => 160);
$objectHeaderWithoutDate[] = array('name' => 'value', 'title' => '数值', 'width' => 96);
$objectHeaderWithoutDate[] = array('name' => 'calcTime', 'title' => '采集时间', 'width' => 128);

$emptyHeader = array();

$nonStandardHeader = array();
$nonStandardHeader[] = array('name' => 'custom', 'title' => '自定义字段', 'width' => 100);
$nonStandardHeader[] = array('name' => 'other', 'title' => '其他字段', 'width' => 120);

r(count($metric->getChartTypeList($systemHeader))) && p() && e(4); // 步骤1：测试系统度量表头
r(count($metric->getChartTypeList($objectHeaderWithDate))) && p() && e(3); // 步骤2：测试对象度量表头（含date）
r(count($metric->getChartTypeList($objectHeaderWithoutDate))) && p() && e(3); // 步骤3：测试对象度量表头（无date）
r(count($metric->getChartTypeList($emptyHeader))) && p() && e(4); // 步骤4：测试空数组表头
r(count($metric->getChartTypeList($nonStandardHeader))) && p() && e(4); // 步骤5：测试非标准表头
r(array_key_exists('pie', $metric->getChartTypeList($objectHeaderWithDate))) && p() && e('0'); // 步骤6：验证对象度量不包含pie
r(array_key_exists('pie', $metric->getChartTypeList($systemHeader))) && p() && e('1'); // 步骤7：验证系统度量包含pie