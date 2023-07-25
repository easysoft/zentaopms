#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';


$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=count_of_execution
cid=1
pid=1

*/

zdTable('project')->config('project_type', $useCommon = true, $levels = 4)->gen(356, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('120'); // 测试356条数据。

zdTable('project')->config('project_type', $useCommon = true, $levels = 4)->gen(652, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('210'); // 测试652条数据。

zdTable('project')->config('project_type', $useCommon = true, $levels = 4)->gen(1265, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('390'); // 测试1265条数据。
