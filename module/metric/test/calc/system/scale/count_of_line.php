#!/usr/bin/env php
<?php

/**

title=count_of_line
timeout=0
cid=1

- 测试产品线数第0条的value属性 @80

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('project')->config('program', true, 4)->gen(5);
zdTable('module')->config('line', true, 4)->gen(200);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r($calc->getResult()) && p('0:value') && e('80'); // 测试产品线数