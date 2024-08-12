#!/usr/bin/env php
<?php

/**

title=count_of_case_in_execution
timeout=0
cid=1

- 测试分组数。 @20
- 测试执行21第0条的value属性 @5

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/lib/calc.unittest.class.php';

zendata('product')->loadYaml('product', true, 4)->gen(10);
zendata('project')->loadYaml('project_type', true, 4)->gen(100);
zendata('case')->loadYaml('case', true, 4)->gen(1000);
zendata('projectcase')->loadYaml('projectcase', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('20'); // 测试分组数。

r($calc->getResult(array('execution' => '21'))) && p('0:value') && e('5');  // 测试执行21