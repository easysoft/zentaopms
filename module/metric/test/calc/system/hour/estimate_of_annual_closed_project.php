#!/usr/bin/env php
<?php

/**

title=estimate_of_annual_closed_project
timeout=0
cid=1

- 测试分组数 @1
- 测试2011年关闭项目的任务预计工时数第0条的value属性 @447

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/lib/calc.unittest.class.php';

zendata('project')->loadYaml('project_close', true, 4)->gen(10);
zendata('project')->loadYaml('execution', true, 4)->gen(100, false);
zendata('task')->loadYaml('task', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('1'); // 测试分组数

r($calc->getResult(array('year' => '2011'))) && p('0:value') && e('447'); // 测试2011年关闭项目的任务预计工时数