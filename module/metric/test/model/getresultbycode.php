#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';

zdTable('user')->config('user', true)->gen(30);
zdTable('product')->config('product', true)->gen(10);
zdTable('productplan')->config('productplan', true)->gen(50);
zdTable('feedback')->config('feedback_create', true)->gen(50);

$metric = new metricTest();

/**

title=getMetricByCode
timeout=0
cid=1

- 测试按产品统计的计划数第0条的value属性 @2
- 测试按产品统计年度关闭反馈数第0条的value属性 @4

*/

$options = array('product' => '7');
r($metric->getMetricByCode('count_of_productplan_in_product', $options)) && p('0:value') && e('2');  // 测试按产品统计的计划数

$options = array('product' => '9', 'year' => date('Y'));
r($metric->getMetricByCode('count_of_annual_closed_feedback_in_product', $options)) && p('0:value') && e('4');  // 测试按产品统计年度关闭反馈数
