#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('project')->config('project_status', $useCommon = true, $levels = 4)->gen(100);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

/**

title=left_period_of_project
cid=1
pid=1

*/

r(count($calc->getResult())) && p('') && e('100'); // 测试分组数。
