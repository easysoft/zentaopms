#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', $useCommon = true, $levels = 4)->gen(10);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=rate_of_fixed_bug
cid=1
pid=1

*/

zdTable('bug')->config('bug_resolution_status', $useCommon = true, $levels = 4)->gen(123, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('0.0833'); // 测试123条数据Bug数。

zdTable('bug')->config('bug_resolution_status', $useCommon = true, $levels = 4)->gen(989, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('0.0833'); // 测试123条数据Bug数。

zdTable('bug')->config('bug_resolution_status', $useCommon = true, $levels = 4)->gen(1234, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('0.0833'); // 测试1234条数据Bug数。
