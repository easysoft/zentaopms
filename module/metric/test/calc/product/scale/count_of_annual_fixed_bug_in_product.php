#!/usr/bin/env php
<?php

/**

title=count_of_annual_fixed_bug_in_product
timeout=0
cid=1

- 测试分组数。 @12
- 测试2015年产品3修复的bug数。第0条的value属性 @2
- 测试2016年产品5修复的bug数。第0条的value属性 @3
- 测试已删除产品4修复的bug数。第0条的value属性 @0
- 测试不存在的产品。 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('bug')->config('bug_resolution_status', true, 4)->gen(1000);
zdTable('product')->config('product', true, 4)->gen(10);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult()))                                     && p('')        && e('12'); // 测试分组数。
r($calc->getResult(array('product' => '3',  'year' => '2018')))  && p('0:value') && e('2');  // 测试2015年产品3修复的bug数。
r($calc->getResult(array('product' => '5',  'year' => '2017')))  && p('0:value') && e('3');  // 测试2016年产品5修复的bug数。
r($calc->getResult(array('product' => '4',  'year' => '2019')))  && p('0:value') && e('0');  // 测试已删除产品4修复的bug数。
r($calc->getResult(array('product' => '999', 'year' => '2021'))) && p('')        && e('0');  // 测试不存在的产品。