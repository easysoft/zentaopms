#!/usr/bin/env php
<?php

/**

title=cv_weekly_in_waterfall
timeout=0
cid=1

- 测试分组数。 @5
- 测试项目1。第0条的value属性 @108.4647

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/lib/calc.unittest.class.php';

zendata('project')->loadYaml('waterfall', true, 4)->gen(10);
zendata('project')->loadYaml('stage', true, 4)->gen(40, false);
zendata('task')->loadYaml('task_waterfall', true, 4)->gen(1000);
zendata('effort')->loadYaml('effort_ac', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($metric->getReuseCalcResult($calc))) && p('') && e('5'); // 测试分组数。

r($metric->getReuseCalcResult($calc, array('project' => '1', 'year' => '2024', 'week' => '05'))) && p('0:value') && e('108.4647'); // 测试项目1。