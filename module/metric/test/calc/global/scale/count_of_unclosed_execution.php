#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', $useCommon = true, $levels = 4)->gen(10);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=count_of_unclosed_execution
cid=1
pid=1

*/

zdTable('project')->config('project_type', $useCommon = true, $levels = 4)->gen(356, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('72'); // 测试356条数据。

zdTable('project')->config('project_type', $useCommon = true, $levels = 4)->gen(652, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('126'); // 测试652条数据。

zdTable('project')->config('project_type', $useCommon = true, $levels = 4)->gen(1265, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('234'); // 测试1265条数据。
