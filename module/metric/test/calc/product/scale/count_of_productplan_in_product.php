#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', $useCommon = true, $levels = 4)->gen(10);
zdTable('productplan')->config('productplan', $useCommon = true, $levels = 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=count_of_productplan_in_product
cid=1
pid=1

*/

r(count($calc->getResult()))                    && p('')        && e('5');  // 测试产品计划按产品分组数。
r($calc->getResult(array('product' => '1')))    && p('0:value') && e('50'); // 测试产品1下计划数。
r($calc->getResult(array('product' => '3')))    && p('0:value') && e('50'); // 测试产品3下计划数。
r($calc->getResult(array('product' => '4')))    && p('0:value') && e('0');  // 测试已删除产品4下计划数。
r($calc->getResult(array('product' => '1111'))) && p('')        && e('0');  // 测试不存在的产品下计划数。
