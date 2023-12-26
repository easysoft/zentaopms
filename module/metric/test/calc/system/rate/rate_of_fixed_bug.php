#!/usr/bin/env php
<?php

/**

title=rate_of_fixed_bug
timeout=0
cid=1

- 测试123条数据Bug数。第0条的value属性 @0.0833
- 测试123条数据Bug数。第0条的value属性 @0.0833
- 测试1234条数据Bug数。第0条的value属性 @0.0833

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', true, 4)->gen(10);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

zdTable('bug')->config('bug_resolution_status', true, 4)->gen(123, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('0.0833'); // 测试123条数据Bug数。

zdTable('bug')->config('bug_resolution_status', true, 4)->gen(989, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('0.0833'); // 测试123条数据Bug数。

zdTable('bug')->config('bug_resolution_status', true, 4)->gen(1234, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('0.0833'); // 测试1234条数据Bug数。