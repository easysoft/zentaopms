#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('productplan')->gen(55);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=count_of_plan_in_product
cid=1
pid=1

*/

r(count($calc->getResult()))                 && p('')        && e('5'); // 测试产品计划按产品分组数。
r($calc->getResult(array('prod' => '42')))   && p('0:value') && e('2'); // 测试产品42下计划数。
r($calc->getResult(array('prod' => '53')))   && p('0:value') && e('1'); // 测试产品53下计划数。
r($calc->getResult(array('prod' => '1111'))) && p('')        && e('0'); // 测试不存在的产品下计划数。
