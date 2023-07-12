#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('productplan')->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=count_of_annual_created_plan_in_product
timeout=0
cid=1

*/
r(count($calc->getResult()))                                     && p('')        && e('30'); // 测试计划按产品分组数。
r($calc->getresult(array('product' => '1', 'year' => '2023')))   && p('0:value') && e('45'); // 测试产品1,2023年创建的的计划数。
r($calc->getresult(array('product' => '7', 'year' => '2023')))   && p('0:value') && e('43'); // 测试产品7,2023年创建的的计划数。
r($calc->getresult(array('product' => '100', 'year' => '2023'))) && p('')        && e('0');  // 测试不存在的产品的计划数。
