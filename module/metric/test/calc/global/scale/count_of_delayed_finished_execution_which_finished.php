#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('project')->config('project_close',     $useCommon = true, $levels = 4)->gen(10);
zdTable('project')->config('execution_delayed', $useCommon = true, $levels = 4)->gen(100, false);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=count_of_delayed_finished_execution_which_finished
timeout=0
cid=1

*/

r($calc->getResult()) && p('') && e('16'); // 测试分组数
