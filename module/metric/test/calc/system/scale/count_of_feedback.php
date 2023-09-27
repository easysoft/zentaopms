#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('feedback')->config('feedback', $useCommon = true, $levels = 4)->gen(200);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=count_of_feedback
timeout=0
cid=1

*/

r($calc->getResult()) && p('0:value') && e('54'); // 测试全局范围反馈总数
