#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('feedback')->config('feedback', $useCommon = true, $levels = 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=count_of_closed_feedback
cid=1
pid=1

*/

zdTable('feedback')->config('feedback', $useCommon = true, $levels = 4)->gen(356, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('15'); // 测试356条数据。

zdTable('feedback')->config('feedback', $useCommon = true, $levels = 4)->gen(652, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('27'); // 测试652条数据。

zdTable('feedback')->config('feedback', $useCommon = true, $levels = 4)->gen(1265, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('53'); // 测试1265条数据。
