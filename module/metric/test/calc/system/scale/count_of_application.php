#!/usr/bin/env php
<?php

/**

title=count_of_application
timeout=0
cid=1

- 测试应用数。第0条的value属性 @10

*/

include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/lib/calc.class.php';

zendata('pipeline')->loadYaml('pipeline', true, 4)->gen(5);
zendata('instance')->loadYaml('instance', true, 4)->gen(5);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r($calc->getResult()) && p('0:value') && e('10'); // 测试应用数。