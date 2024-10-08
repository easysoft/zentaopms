#!/usr/bin/env php
<?php

/**

title=consume_of_annual_closed_project
timeout=0
cid=1

- 测试分组数。 @10
- 测试2011年项目1任务消耗的工时数。第0条的value属性 @48
- 测试2012年项目3任务消耗的工时数第0条的value属性 @0
- 测试不存在年份的反馈数 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/lib/calc.unittest.class.php';

zendata('project')->loadYaml('project_close', true, 4)->gen(80);
zendata('task')->loadYaml('task_consumed', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('10'); // 测试分组数。

r($calc->getResult(array('project' => '1', 'year' => '2011'))) && p('0:value') && e('48'); // 测试2011年项目1任务消耗的工时数。
r($calc->getResult(array('project' => '3', 'year' => '2012'))) && p('0:value') && e('0');  // 测试2012年项目3任务消耗的工时数
r($calc->getResult(array('project' => '4', 'year' => '9999'))) && p('')        && e('0');  // 测试不存在年份的反馈数