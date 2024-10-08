#!/usr/bin/env php
<?php

/**

title=count_of_user
timeout=0
cid=1

- 测试356条数据。第0条的value属性 @5
- 测试652条数据。第0条的value属性 @10

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/lib/calc.unittest.class.php';

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

zendata('user')->loadYaml('user', true, 4)->gen(10, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('5'); // 测试356条数据。

zendata('user')->loadYaml('user', true, 4)->gen(20, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('10'); // 测试652条数据。