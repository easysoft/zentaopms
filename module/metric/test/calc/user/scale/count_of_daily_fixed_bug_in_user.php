#!/usr/bin/env php
<?php

/**

title=count_of_daily_fixed_bug_in_user
timeout=0
cid=1

- 测试分组数。 @160

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zendata('product')->loadYaml('product_shadow', true, 4)->gen(10);
zendata('project')->loadYaml('project_status', true, 4)->gen(10);
zendata('bug')->loadYaml('bug_resolution_status', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('160'); // 测试分组数。