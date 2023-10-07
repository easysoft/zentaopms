#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('pipeline')->config('pipeline', $useCommon = true, $levels = 4)->gen(10);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=count_of_pipeline
cid=1
pid=1

*/

r($calc->getResult()) && p('0:value') && e('10'); // 测试流水线数。
