#!/usr/bin/env php
<?php

/**

title=count_of_line
timeout=0
cid=1

- 测试产品线数第0条的value属性 @100

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/lib/calc.unittest.class.php';

zendata('project')->loadYaml('program', true, 4)->gen(5);
zendata('module')->loadYaml('line', true, 4)->gen(200);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r($calc->getResult()) && p('0:value') && e('100'); // 测试产品线数