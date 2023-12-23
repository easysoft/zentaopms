#!/usr/bin/env php
<?php
/**

title=count_of_marker_release
timeout=0
cid=1

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

$metric = new metricTest();

zdTable('product')->config('product', true, 4)->gen(10);
zdTable('project')->config('project', true, 4)->gen(10);

zdTable('release')->config('release_marker', true, 4)->gen(839, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('105'); // 测试839条数据全局里程碑发布数。

zdTable('release')->config('release_marker', true, 4)->gen(500, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('63'); // 测试500条数据全局里程碑发布数。

zdTable('release')->config('release_marker', true, 4)->gen(1252, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('157'); // 测试1252条数据全局里程碑发布数。
