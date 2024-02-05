#!/usr/bin/env php
<?php

/**

title=count_of_unclosed_execution
timeout=0
cid=1

- 测试356条数据。第0条的value属性 @60
- 测试652条数据。第0条的value属性 @126
- 测试1265条数据。第0条的value属性 @235

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

zdTable('project')->config('project_close', true, 4)->gen(10);
zdTable('project')->config('execution', true, 4)->gen(356, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('60'); // 测试356条数据。

zdTable('project')->config('project_close', true, 4)->gen(10);
zdTable('project')->config('execution', true, 4)->gen(652, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('126'); // 测试652条数据。

zdTable('project')->config('project_close', true, 4)->gen(10);
zdTable('project')->config('execution', true, 4)->gen(1265, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('235'); // 测试1265条数据。