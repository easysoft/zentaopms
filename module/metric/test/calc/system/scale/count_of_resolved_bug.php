#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', $useCommon = true, $levels = 4)->gen(10);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=count_of_resolved_bug
cid=1
pid=1

*/

zdTable('bug')->config('bug_resolution_status', $useCommon = true, $levels = 4)->gen(356, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('32'); // 测试356条数据Bug数。

zdTable('bug')->config('bug_resolution_status', $useCommon = true, $levels = 4)->gen(652, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('56'); // 测试652条数据Bug数。

zdTable('bug')->config('bug_resolution_status', $useCommon = true, $levels = 4)->gen(1265, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('110'); // 测试1265条数据Bug数。
