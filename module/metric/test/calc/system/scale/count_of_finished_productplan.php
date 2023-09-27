#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', $useCommon = true, $levels = 4)->gen(10);

$metric = new metricTest();

/**

title=count_of_finished_productplan
timeout=0
cid=1

*/

zdTable('productplan')->config('productplan', $useCommon = true, $levels = 4)->gen(356, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('44'); // 测试356条数据产品已完成计划数。

zdTable('productplan')->config('productplan', $useCommon = true, $levels = 4)->gen(652, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('81'); // 测试652条数据产品已完成计划数。

zdTable('productplan')->config('productplan', $useCommon = true, $levels = 4)->gen(1265, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('158'); // 测试1265条数据产品已完成计划数。

