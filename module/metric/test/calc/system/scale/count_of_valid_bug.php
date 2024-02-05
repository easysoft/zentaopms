#!/usr/bin/env php
<?php

/**

title=count_of_valid_bug
timeout=0
cid=1

- 测试356条数据Bug数。第0条的value属性 @48
- 测试652条数据Bug数。第0条的value属性 @84
- 测试1265条数据Bug数。第0条的value属性 @165

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', true, 4)->gen(10);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

zdTable('bug')->config('bug_resolution_status', true, 4)->gen(356, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('48'); // 测试356条数据Bug数。

zdTable('bug')->config('bug_resolution_status', true, 4)->gen(652, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('84'); // 测试652条数据Bug数。

zdTable('bug')->config('bug_resolution_status', true, 4)->gen(1265, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('165'); // 测试1265条数据Bug数。