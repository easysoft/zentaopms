#!/usr/bin/env php
<?php

/**

title=count_of_daily_closed_bug
timeout=0
cid=1

- 测试分组数。 @222
- 测试2016.02.09第0条的value属性 @1
- 测试2016.02.19第0条的value属性 @1

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', true, 4)->gen(10);
zdTable('bug')->config('bug_resolution_status', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('222'); // 测试分组数。

r($calc->getResult(array('year' => '2016', 'month' => '02', 'day' => '09'))) && p('0:value') && e('1'); // 测试2016.02.09
r($calc->getResult(array('year' => '2016', 'month' => '02', 'day' => '19'))) && p('0:value') && e('1'); // 测试2016.02.19