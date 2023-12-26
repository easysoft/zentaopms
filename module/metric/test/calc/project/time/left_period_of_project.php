#!/usr/bin/env php
<?php

/**

title=left_period_of_project
timeout=0
cid=1

- 测试分组数。 @100

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('project')->config('project_status', true, 4)->gen(100);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('100'); // 测试分组数。