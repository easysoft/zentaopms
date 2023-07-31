#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('project')->config('project_status', $useCommon = true, $levels = 4)->gen(10);
zdTable('project')->config('execution', $useCommon = true, $levels = 4)->gen(20, false);
zdTable('task')->config('task', $useCommon = true, $levels = 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=count_of_task_in_project
timeout=0
cid=1

*/

r(count($calc->getResult())) && p('') && e('90'); // 测试分组数。
