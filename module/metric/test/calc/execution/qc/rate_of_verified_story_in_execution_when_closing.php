#!/usr/bin/env php
<?php

/**

title=test_concentration_in_execution_when_closing
timeout=0
cid=1

- 测试分组数。 @6
- 测试项目1。第0条的value属性 @1.1667

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/lib/calc.unittest.class.php';

zendata('product')->loadYaml('product', true, 4)->gen(10);
zendata('project')->loadYaml('project_type', true, 4)->gen(100);
zendata('story')->loadYaml('story_stage_verified', true, 4)->gen(1000);
zendata('projectstory')->loadYaml('executionstory', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($metric->getReuseCalcResult($calc))) && p('') && e('6'); // 测试分组数。

r($metric->getReuseCalcResult($calc, array('project' => '1'))) && p('0:value') && e('1.1667'); // 测试项目1。