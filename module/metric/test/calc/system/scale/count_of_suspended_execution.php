#!/usr/bin/env php
<?php

/**

title=count_of_suspended_execution
timeout=0
cid=1

- 测试356条数据。第0条的value属性 @20
- 测试652条数据。第0条的value属性 @42
- 测试1265条数据。第0条的value属性 @78

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/lib/calc.class.php';

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

zendata('project')->loadYaml('project_close', true, 4)->gen(10);
zendata('project')->loadYaml('execution', true, 4)->gen(356, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('20'); // 测试356条数据。

zendata('project')->loadYaml('project_close', true, 4)->gen(10);
zendata('project')->loadYaml('execution', true, 4)->gen(652, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('42'); // 测试652条数据。

zendata('project')->loadYaml('project_close', true, 4)->gen(10);
zendata('project')->loadYaml('execution', true, 4)->gen(1265, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('78'); // 测试1265条数据。