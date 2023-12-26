#!/usr/bin/env php
<?php

/**

title=count_of_annual_created_release_in_product
timeout=0
cid=1

- 测试分组数。 @55
- 测试产品3,2020年的发布数。第0条的value属性 @2
- 测试产品5,2020年的发布数。第0条的value属性 @4
- 测试已删除产品4的发布数。第0条的value属性 @0
- 测试不存在的产品的发布数。 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', true, 4)->gen(10);
zdTable('release')->config('release', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult()))                                     && p('')        && e('55'); // 测试分组数。
r($calc->getResult(array('product' => '3', 'year' => '2020')))   && p('0:value') && e('2');  // 测试产品3,2020年的发布数。
r($calc->getResult(array('product' => '5', 'year' => '2020')))   && p('0:value') && e('4');  // 测试产品5,2020年的发布数。
r($calc->getResult(array('product' => '4', 'year' => '2020')))   && p('0:value') && e('0');  // 测试已删除产品4的发布数。
r($calc->getResult(array('product' => '999', 'year' => '2018'))) && p('')        && e('0');  // 测试不存在的产品的发布数。