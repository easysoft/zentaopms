#!/usr/bin/env php
<?php

/**

title=count_of_productplan
timeout=0
cid=1

- 测试356条数据产品计划数。第0条的value属性 @89
- 测试652条数据产品计划数。第0条的value属性 @163
- 测试1265条数据产品计划数。第0条的value属性 @317

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zendata('product')->loadYaml('product', true, 4)->gen(10);

$metric = new metricTest();

zendata('productplan')->loadYaml('productplan', true, 4)->gen(356, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('89'); // 测试356条数据产品计划数。

zendata('productplan')->loadYaml('productplan', true, 4)->gen(652, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('163'); // 测试652条数据产品计划数。

zendata('productplan')->loadYaml('productplan', true, 4)->gen(1265, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('317'); // 测试1265条数据产品计划数。