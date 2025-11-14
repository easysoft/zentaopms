#!/usr/bin/env php
<?php

/**

title=测试 metricModel::getViewTableData();
timeout=0
cid=17131

- 执行metricTest模块的getViewTableDataTest方法，参数是$systemMetric, $systemResult 
 - 第0条的value属性 @100
 - 第0条的calcType属性 @cron
- 执行metricTest模块的getViewTableDataTest方法，参数是$productMetric, $productResult 
 - 第0条的scopeID属性 @1
 - 第0条的value属性 @200
- 执行metricTest模块的getViewTableDataTest方法，参数是$systemMetric, array  @0
- 执行metricTest模块的getViewTableDataTest方法，参数是$systemMetric, $invalidResult 
 - 第0条的value属性 @300
 - 第0条的calcType属性 @inference
- 执行metricTest模块的getViewTableDataTest方法，参数是$systemMetric, $floatResult 第0条的value属性 @123.46

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/metric.unittest.class.php';

zenData('metric')->loadYaml('metric_getviewtabledata', false, 2)->gen(5);
zenData('product')->loadYaml('product_getviewtabledata', false, 2)->gen(3);

su('admin');

$metricTest = new metricTest();

// 构造测试用的metric对象
$systemMetric = new stdclass();
$systemMetric->scope = 'system';
$systemMetric->code = 'test_system';

$productMetric = new stdclass();
$productMetric->scope = 'product';
$productMetric->code = 'test_product';

// 测试步骤1：正常输入情况 - 系统范围数据
$systemResult = array(
    (object)array('date' => '2024-01-01', 'value' => '100', 'calcType' => 'cron', 'calculatedBy' => 'system', 'year' => '2024', 'month' => '01', 'day' => '01')
);
r($metricTest->getViewTableDataTest($systemMetric, $systemResult)) && p('0:value,calcType') && e('100,cron');

// 测试步骤2：正常输入情况 - 产品范围数据
$productResult = array(
    (object)array('date' => '2024-01-01', 'product' => 1, 'value' => '200', 'calcType' => 'cron', 'calculatedBy' => 'system', 'year' => '2024', 'month' => '01', 'day' => '01')
);
r($metricTest->getViewTableDataTest($productMetric, $productResult)) && p('0:scopeID,value') && e('1,200');

// 测试步骤3：边界值输入 - 空结果数组
r($metricTest->getViewTableDataTest($systemMetric, array())) && p() && e('0');

// 测试步骤4：异常输入情况 - 无效日期数据
$invalidResult = array(
    (object)array('value' => '300', 'calcType' => 'inference', 'calculatedBy' => 'manual', 'year' => '', 'month' => '', 'day' => '')
);
r($metricTest->getViewTableDataTest($systemMetric, $invalidResult)) && p('0:value,calcType') && e('300,inference');

// 测试步骤5：数值处理验证 - 浮点数值四舍五入
$floatResult = array(
    (object)array('date' => '2024-01-01', 'value' => '123.456789', 'calcType' => 'cron', 'calculatedBy' => 'system', 'year' => '2024', 'month' => '01', 'day' => '01')
);
r($metricTest->getViewTableDataTest($systemMetric, $floatResult)) && p('0:value') && e('123.46');