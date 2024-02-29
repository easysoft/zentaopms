#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';

// zdTable('feedback')->config('feedback_create')->gen(5000);

$metric = new metricTest();

/**

title=getMetricByCode
timeout=0
cid=1

- 测试按产品统计的计划数第0条的value属性 @50
- 测试按产品统计年度关闭反馈数第0条的value属性 @0

*/

$options = array('product' => '7');
r($metric->getMetricByCode('count_of_plan_in_product', $options)) && p('0:value') && e('50');  // 测试按产品统计的计划数

$options = array('product' => '9', 'year' => '2014');
r($metric->getMetricByCode('count_of_annual_closed_feedback_in_product', $options)) && p('0:value') && e('0');  // 测试按产品统计年度关闭反馈数