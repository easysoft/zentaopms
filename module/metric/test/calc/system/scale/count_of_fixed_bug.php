#!/usr/bin/env php
<?php

/**

title=count_of_fixed_bug
timeout=0
cid=1

- 测试356条数据Bug数。第0条的value属性 @4
- 测试652条数据Bug数。第0条的value属性 @7
- 测试1265条数据Bug数。第0条的value属性 @14

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/lib/calc.unittest.class.php';

zendata('product')->loadYaml('product', true, 4)->gen(10);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

zendata('bug')->loadYaml('bug_resolution_status', true, 4)->gen(356, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('4'); // 测试356条数据Bug数。

zendata('bug')->loadYaml('bug_resolution_status', true, 4)->gen(652, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('7'); // 测试652条数据Bug数。

zendata('bug')->loadYaml('bug_resolution_status', true, 4)->gen(1265, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('14'); // 测试1265条数据Bug数。