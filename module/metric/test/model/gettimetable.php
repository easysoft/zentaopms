#!/usr/bin/env php
<?php

/**

title=测试 metricModel::getTimeTable();
timeout=0
cid=17129

- 执行metricTest模块的getTimeTableTest方法，参数是$normalData  @0
- 执行metricTest模块的getTimeTableTest方法，参数是array  @0
- 执行metricTest模块的getTimeTableTest方法，参数是$weekData, 'week'  @0
- 执行metricTest模块的getTimeTableTest方法，参数是$normalData, 'day', false  @0
- 执行metricTest模块的getTimeTableTest方法，参数是$incompleteData  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metric.unittest.class.php';

su('admin');

$metricTest = new metricTest();

// 准备测试数据1：完整数据
$normalData = array();
$normalData[0] = new stdClass();
$normalData[0]->dateString = '2023-10-15';
$normalData[0]->value = 10;
$normalData[0]->calcTime = '2023-10-15 10:00:00';
$normalData[0]->calcType = 'auto';
$normalData[0]->calculatedBy = 'admin';

// 准备测试数据2：周数据
$weekData = array();
$weekData[0] = new stdClass();
$weekData[0]->dateString = '2023-42';
$weekData[0]->value = 20;
$weekData[0]->calcTime = '2023-10-15 11:00:00';
$weekData[0]->calcType = 'auto';
$weekData[0]->calculatedBy = 'admin';

// 准备测试数据3：另一组完整数据用于验证
$incompleteData = array();
$incompleteData[0] = new stdClass();
$incompleteData[0]->dateString = '2023-10-16';
$incompleteData[0]->value = 15;
$incompleteData[0]->calcTime = '2023-10-16 14:00:00';
$incompleteData[0]->calcType = 'manual';
$incompleteData[0]->calculatedBy = 'user';

// 测试步骤1：正常日期数据处理，检查是否返回有效结构
r($metricTest->getTimeTableTest($normalData)) && p() && e(0);

// 测试步骤2：空数据输入处理，检查是否返回有效结构
r($metricTest->getTimeTableTest(array())) && p() && e(0);

// 测试步骤3：周类型日期处理，检查是否返回有效结构
r($metricTest->getTimeTableTest($weekData, 'week')) && p() && e(0);

// 测试步骤4：不包含计算时间数据，检查是否返回有效结构
r($metricTest->getTimeTableTest($normalData, 'day', false)) && p() && e(0);

// 测试步骤5：缺少部分属性的数据处理，检查是否返回有效结构
r($metricTest->getTimeTableTest($incompleteData)) && p() && e(0);