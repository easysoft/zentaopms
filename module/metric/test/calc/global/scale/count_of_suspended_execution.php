#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=count_of_suspended_execution
cid=1
pid=1

*/

zdTable('project')->config('project_close', $useCommon = true, $levels = 4)->gen(10);
zdTable('project')->config('execution', $useCommon = true, $levels = 4)->gen(356, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('20'); // 测试356条数据。

zdTable('project')->config('project_close', $useCommon = true, $levels = 4)->gen(10);
zdTable('project')->config('execution', $useCommon = true, $levels = 4)->gen(652, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('42'); // 测试652条数据。

zdTable('project')->config('project_close', $useCommon = true, $levels = 4)->gen(10);
zdTable('project')->config('execution', $useCommon = true, $levels = 4)->gen(1265, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('78'); // 测试1265条数据。
