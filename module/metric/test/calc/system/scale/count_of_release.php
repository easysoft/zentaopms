#!/usr/bin/env php
<?php

/**

title=count_of_release
timeout=0
cid=1

- 测试839条数据全局发布数。第0条的value属性 @420
- 测试500条数据全局发布数。第0条的value属性 @250
- 测试1252条数据全局发布数。第0条的value属性 @626

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/lib/calc.unittest.class.php';

$metric = new metricTest();

zendata('product')->loadYaml('product', true, 4)->gen(10);
zendata('project')->loadYaml('project', true, 4)->gen(10);

zendata('release')->loadYaml('release', true, 4)->gen(839, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('420'); // 测试839条数据全局发布数。

zendata('release')->loadYaml('release', true, 4)->gen(500, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('250'); // 测试500条数据全局发布数。

zendata('release')->loadYaml('release', true, 4)->gen(1252, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('626'); // 测试1252条数据全局发布数。