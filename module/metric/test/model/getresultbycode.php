#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/calc.unittest.class.php';

zenData('user')->loadYaml('user', true)->gen(30);
zenData('product')->loadYaml('product', true)->gen(10);
zenData('productplan')->loadYaml('productplan', true)->gen(50);
zenData('feedback')->loadYaml('feedback_create', true)->gen(50);

$metric = new metricTest();

/**

title=getMetricByCode
timeout=0
cid=17122

- 测试按产品统计的计划数第1个结果
 - 属性product @1
 - 属性value @3
- 测试按产品统计的计划数第2个结果
 - 属性product @3
 - 属性value @3
- 测试按产品统计的计划数第3个结果
 - 属性product @5
 - 属性value @3
- 测试按产品统计的计划数第4个结果
 - 属性product @7
 - 属性value @2
- 测试按产品统计年度关闭反馈数第1个结果
 - 属性product @1
 - 属性value @4
- 测试按产品统计年度关闭反馈数第2个结果
 - 属性product @3
 - 属性value @4
- 测试按产品统计年度关闭反馈数第3个结果
 - 属性product @5
 - 属性value @4
- 测试按产品统计年度关闭反馈数第4个结果
 - 属性product @7

*/

$result1 = $metric->getMetricByCode('count_of_productplan_in_product');
r($result1[0]) && p('product,value') && e('1,3');  // 测试按产品统计的计划数第1个结果
r($result1[1]) && p('product,value') && e('3,3');  // 测试按产品统计的计划数第2个结果
r($result1[2]) && p('product,value') && e('5,3');  // 测试按产品统计的计划数第3个结果
r($result1[3]) && p('product,value') && e('7,2');  // 测试按产品统计的计划数第4个结果

$result2 = $metric->getMetricByCode('count_of_annual_closed_feedback_in_product');
r($result2[0]) && p('product,value') && e('1,4');  // 测试按产品统计年度关闭反馈数第1个结果
r($result2[1]) && p('product,value') && e('3,4');  // 测试按产品统计年度关闭反馈数第2个结果
r($result2[2]) && p('product,value') && e('5,4');  // 测试按产品统计年度关闭反馈数第3个结果
r($result2[3]) && p('product,value') && e('7,4');  // 测试按产品统计年度关闭反馈数第4个结果