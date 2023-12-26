#!/usr/bin/env php
<?php

/**

title=count_of_release
timeout=0
cid=1

- 测试839条数据全局发布数。第0条的value属性 @210
- 测试500条数据全局发布数。第0条的value属性 @125
- 测试1252条数据全局发布数。第0条的value属性 @313

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

$metric = new metricTest();

zdTable('product')->config('product', true, 4)->gen(10);
zdTable('release')->config('release', true, 4)->gen(839, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('210'); // 测试839条数据全局发布数。

zdTable('release')->config('release', true, 4)->gen(500, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('125'); // 测试500条数据全局发布数。

zdTable('release')->config('release', true, 4)->gen(1252, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('313'); // 测试1252条数据全局发布数。