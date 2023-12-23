#!/usr/bin/env php
<?php
/**

title=count_of_pending_deployment
timeout=0
cid=1

*/

include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('deploy')->config('deploy', true, 4)->gen(10);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r($calc->getResult()) && p('0:value') && e('6'); // 测试待处理上线计划数。
