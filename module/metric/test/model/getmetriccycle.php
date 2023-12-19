#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';

$metric = new metricTest();

zdTable('metriclib')->config('metriclib_system_product', true)->gen(80);

/**

title=getMetricCycle
cid=1
pid=1

*/

$records = array();
$records[1]  = array('year' => 2023);
$records[2]  = array('month' => 12);
$records[3]  = array('week' => 52);
$records[4]  = array('day' => 21);
$records[5]  = array('year' => 2023, 'month' => 12);
$records[6]  = array('year' => 2023, 'week' => 52);
$records[7]  = array('year' => 2023, 'day' => 21);
$records[8]  = array('month' => 12, 'week' => 52);
$records[9]  = array('month' => 12, 'week' => 52);
$records[9]  = array('week' => 52, 'day' => 21);
$records[10] = array('month' => 12, 'week' => 52, 'day' => 21);
$records[11] = array('year' => 2023, 'week' => 52, 'day' => 21);
$records[12] = array('year' => 2023, 'month' => 12, 'day' => 21);
$records[13] = array('year' => 2023, 'month' => 12, 'week' => 52);
$records[14] = array('year' => 2023, 'month' => 12, 'week' => 52, 'day' => 21);

r($metric->getMetricCycle($records[1]))  && p() && e('year');  // 测试record[1]的收集周期
r($metric->getMetricCycle($records[2]))  && p() && e('0');     // 测试record[2]的收集周期
r($metric->getMetricCycle($records[3]))  && p() && e('0');     // 测试record[3]的收集周期
r($metric->getMetricCycle($records[4]))  && p() && e('0');     // 测试record[4]的收集周期
r($metric->getMetricCycle($records[5]))  && p() && e('month'); // 测试record[5]的收集周期
r($metric->getMetricCycle($records[6]))  && p() && e('week');  // 测试record[6]的收集周期
r($metric->getMetricCycle($records[7]))  && p() && e('year');  // 测试record[7]的收集周期
r($metric->getMetricCycle($records[8]))  && p() && e('0');     // 测试record[8]的收集周期
r($metric->getMetricCycle($records[9]))  && p() && e('0');     // 测试record[9]的收集周期
r($metric->getMetricCycle($records[10])) && p() && e('0');     // 测试record[10]的收集周期
r($metric->getMetricCycle($records[11])) && p() && e('week');  // 测试record[11]的收集周期
r($metric->getMetricCycle($records[12])) && p() && e('day');   // 测试record[12]的收集周期
r($metric->getMetricCycle($records[13])) && p() && e('month'); // 测试record[13]的收集周期
r($metric->getMetricCycle($records[14])) && p() && e('day');   // 测试record[14]的收集周期
