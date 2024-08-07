#!/usr/bin/env php
<?php

/**

title=count_of_effective_bug_in_execution
timeout=0
cid=1

- 测试分组数。 @6
- 测试项目2。第0条的value属性 @11

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/lib/calc.unittest.class.php';

zendata('product')->loadYaml('product', true, 4)->gen(10);
zendata('project')->loadYaml('project', true, 4)->gen(200);
zendata('bug')->loadYaml('bug', true, 4)->gen(500);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);
a($calc->getResult());die;

r(count($calc->getResult())) && p('') && e('6'); // 测试分组数。

//r($calc->getResult(array('project' => '4'))) && p('0:value') && e('11');  // 测试项目2。
