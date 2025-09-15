#!/usr/bin/env php
<?php

/**

title=测试 metricModel::getTimeTable();
timeout=0
cid=0

- 执行metricTest模块的getTimeTableTest方法，参数是$normalData  @date
 @date
属性name @date
- 执行metricTest模块的getTimeTableTest方法，参数是array 属性1 @0
- 执行metricTest模块的getTimeTableTest方法，参数是$weekData, 'week'  @date
 @date
属性name @date
- 执行metricTest模块的getTimeTableTest方法，参数是$normalData, 'day', false  @value
属性1 @value
属性name @value
- 执行metricTest模块的getTimeTableTest方法，参数是$mixedData  @date
 @date
属性name @date
- 执行metricTest模块的getTimeTableTest方法，参数是$normalData  @数值
属性1 @数值
属性title @数值

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metric.unittest.class.php';

su('admin');

$metricTest = new metricTest();

// 测试步骤1：正常日期数据处理，验证返回的是包含两个元素的数组
$normalData = array();
$normalData[0] = new stdClass();
$normalData[0]->dateString = '2023-10-15';
$normalData[0]->value = 10;
$normalData[0]->calcTime = '2023-10-15 10:00:00';
$normalData[0]->calcType = 'auto';
$normalData[0]->calculatedBy = 'admin';

$normalData[1] = new stdClass();
$normalData[1]->dateString = '2023-10-14';
$normalData[1]->value = 5;
$normalData[1]->calcTime = '2023-10-14 09:00:00';
$normalData[1]->calcType = 'manual';
$normalData[1]->calculatedBy = 'admin';

// 测试步骤1：正常日期数据处理，检查表头名称
r($metricTest->getTimeTableTest($normalData)) && p('0,0,name') && e('date');

// 测试步骤2：空数据输入处理，检查返回的数据部分长度
r($metricTest->getTimeTableTest(array())) && p('1') && e('0');

// 测试步骤3：周类型日期处理，检查是否能处理周数据
$weekData = array();
$weekData[0] = new stdClass();
$weekData[0]->dateString = '2023-42';
$weekData[0]->value = 20;
$weekData[0]->calcTime = '2023-10-15 11:00:00';
$weekData[0]->calcType = 'auto';
$weekData[0]->calculatedBy = 'admin';

r($metricTest->getTimeTableTest($weekData, 'week')) && p('0,0,name') && e('date');

// 测试步骤4：不包含计算时间数据，检查值字段名称
r($metricTest->getTimeTableTest($normalData, 'day', false)) && p('0,1,name') && e('value');

// 测试步骤5：混合日期数据处理，检查能否处理混合数据
$mixedData = array();
$mixedData[0] = new stdClass();
$mixedData[0]->date = '2023-10-16';
$mixedData[0]->value = 15;
$mixedData[0]->calcTime = '2023-10-16 12:00:00';
$mixedData[0]->calcType = 'auto';
$mixedData[0]->calculatedBy = 'admin';

$mixedData[1] = new stdClass();
$mixedData[1]->dateString = '2023-10-13';
$mixedData[1]->value = 8;
$mixedData[1]->calcTime = '2023-10-13 08:00:00';
$mixedData[1]->calcType = 'manual';
$mixedData[1]->calculatedBy = 'admin';

r($metricTest->getTimeTableTest($mixedData)) && p('0,0,name') && e('date');

// 测试步骤6：数据排序验证，检查返回的表头结构
r($metricTest->getTimeTableTest($normalData)) && p('0,1,title') && e('数值');