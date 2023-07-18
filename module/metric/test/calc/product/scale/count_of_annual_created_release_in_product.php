#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('release')->config('release_create')->gen(100);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=count_of_annual_created_release_in_product
timeout=0
cid=1

*/

r(count($calc->getResult()))                                     && p('')        && e('10'); // 测试分组数。
r($calc->getResult(array('product' => '1', 'year' => '2018')))   && p('0:value') && e('10'); // 测试产品1的发布数。
r($calc->getResult(array('product' => '10', 'year' => '2018')))  && p('0:value') && e('10'); // 测试产品10的发布数。
r($calc->getResult(array('product' => '999', 'year' => '2018'))) && p('')        && e('0');  // 测试不存在的产品的发布数。
