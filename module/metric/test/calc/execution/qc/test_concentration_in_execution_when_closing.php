#!/usr/bin/env php
<?php

/**

title=test_concentration_in_execution_when_closing
timeout=0
cid=1

- 测试分组数。 @1
- 测试项目1。第0条的value属性 @4.6667

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/lib/calc.unittest.class.php';

zendata('product')->loadYaml('product', true, 4)->gen(10);
zendata('project')->loadYaml('project_type', true, 4)->gen(10);
zendata('story')->loadYaml('story_closeddate', true, 4)->gen(1000);
zendata('projectstory')->loadYaml('executionstory', true, 4)->gen(1000);

zendata('project')->loadYaml('execution', true, 4)->gen(10, false);
zendata('bug')->loadYaml('bug_resolution_status', true, 4)->gen(500);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($metric->getReuseCalcResult($calc))) && p('') && e('5'); // 测试分组数。

r($metric->getReuseCalcResult($calc, array('execution' => '16'))) && p('0:value') && e('4.6667'); // 测试项目1。
