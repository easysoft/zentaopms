#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('project')->config('project_delayed',     $useCommon = true, $levels = 4)->gen(10);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=count_of_delayed_finished_project_which_finished
timeout=0
cid=1

*/

r($calc->getResult()) && p('0:value') && e('2'); // 测试按全局统计的完成项目中延期完成项目数。
