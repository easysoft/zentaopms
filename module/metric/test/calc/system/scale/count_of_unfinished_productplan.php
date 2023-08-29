#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', $useCommon = true, $levels = 4)->gen(10);

$metric = new metricTest();

/**

title=count_of_unfinished_productplan
timeout=0
cid=1

*/

zdTable('productplan')->config('productplan', $useCommon = true, $levels = 4)->gen(356, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('45'); // 测试356条数据产品未完成计划数。

zdTable('productplan')->config('productplan', $useCommon = true, $levels = 4)->gen(652, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('82'); // 测试652条数据产品未完成计划数。

zdTable('productplan')->config('productplan', $useCommon = true, $levels = 4)->gen(1265, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('159'); // 测试1265条数据产品未完成计划数。


