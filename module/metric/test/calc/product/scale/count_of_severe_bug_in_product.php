#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', $useCommon = true, $levels = 4)->gen(10);
zdTable('bug')->config('bug_severity', $useCommon = true, $levels = 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=count_of_severe_bug_in_product
cid=1
pid=1

*/

r(count($calc->getResult()))                   && p('')        && e('5');  // 测试严重程度1、2级bug按产品分组数。
r($calc->getResult(array('product' => '1')))   && p('0:value') && e('36'); // 测试产品1严重程度1、2级的bug数。
r($calc->getResult(array('product' => '3')))   && p('0:value') && e('40'); // 测试产品3严重程度1、2级的bug数。
r($calc->getResult(array('product' => '4')))   && p('0:value') && e('0');  // 测试已删除产品4严重程度1、2级的bug数。
r($calc->getResult(array('product' => '999'))) && p('')        && e('0');  // 测试不存在的产品严重程度1、2级的bug数。
