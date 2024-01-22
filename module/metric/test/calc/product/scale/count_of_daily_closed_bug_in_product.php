#!/usr/bin/env php
<?php

/**

title=count_of_daily_closed_bug_in_product
timeout=0
cid=1

- 测试分组数。 @88
- 测试某月10日解决的bug数 @1
- 测试某月30日解决的bug数 @2
- 测试2012-02-05解决的bug数第0条的value属性 @1
- 测试2012-03-21解决的bug数第0条的value属性 @1

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', true, 4)->gen(10);
zdTable('bug')->config('bug_resolution_status', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('88'); // 测试分组数。

r(count($calc->getResult(array('day' => '10')))) && p('') && e('1'); // 测试某月10日解决的bug数
r(count($calc->getResult(array('day' => '30')))) && p('') && e('2'); // 测试某月30日解决的bug数

r($calc->getResult(array('year' => '2016', 'month' => '01', 'day' => '30'))) && p('0:value') && e('1'); // 测试2012-02-05解决的bug数
r($calc->getResult(array('year' => '2016', 'month' => '03', 'day' => '30'))) && p('0:value') && e('1'); // 测试2012-03-21解决的bug数