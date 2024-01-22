#!/usr/bin/env php
<?php

/**

title=count_of_annual_finished_productplan_in_product
timeout=0
cid=1

- 测试分组数。 @55
- 测试2011年产品1计划数。第0条的value属性 @5
- 测试2011年产品3计划数。第0条的value属性 @6
- 测试2011年产品5计划数。第0条的value属性 @6
- 测试2021年产品1计划数。第0条的value属性 @4
- 测试不存在的产品的计划数。 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', true, 4)->gen(10);
zdTable('productplan')->config('productplan', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('55'); // 测试分组数。
r($calc->getResult(array('year' => '2011', 'product' => '1'))) && p('0:value') && e('5'); // 测试2011年产品1计划数。
r($calc->getResult(array('year' => '2011', 'product' => '3'))) && p('0:value') && e('6'); // 测试2011年产品3计划数。
r($calc->getResult(array('year' => '2011', 'product' => '5'))) && p('0:value') && e('6');  // 测试2011年产品5计划数。
r($calc->getResult(array('year' => '2021', 'product' => '1'))) && p('0:value') && e('4');  // 测试2021年产品1计划数。
r($calc->getResult(array('product' => '9999'))) && p('') && e('0'); // 测试不存在的产品的计划数。