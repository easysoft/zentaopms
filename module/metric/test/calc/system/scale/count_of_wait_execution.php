#!/usr/bin/env php
<?php

/**

title=count_of_wait_execution
timeout=0
cid=1

- 测试356条数据。第0条的value属性 @10
- 测试652条数据。第0条的value属性 @21
- 测试1265条数据。第0条的value属性 @39

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/lib/calc.unittest.class.php';

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

zendata('project')->loadYaml('project_close', true, 4)->gen(10);
zendata('project')->loadYaml('execution', true, 4)->gen(356, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('10'); // 测试356条数据。

zendata('project')->loadYaml('project_close', true, 4)->gen(10);
zendata('project')->loadYaml('execution', true, 4)->gen(652, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('21'); // 测试652条数据。

zendata('project')->loadYaml('project_close', true, 4)->gen(10);
zendata('project')->loadYaml('execution', true, 4)->gen(1265, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('39'); // 测试1265条数据。