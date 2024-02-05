#!/usr/bin/env php
<?php

/**

title=count_of_daily_created_bug_in_product
timeout=0
cid=1

- 测试分组数。 @264
- 测试某月13日新增的bug数 @5
- 测试某月23日新增的bug数 @5
- 测试2012年某月6日产品3新增的bug数第0条的value属性 @1
- 测试2012-11-06新增的bug数第0条的value属性 @1

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', true, 4)->gen(10);
zdTable('bug')->config('bug_resolution_status', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('264'); // 测试分组数。

r(count($calc->getResult(array('day' => '13')))) && p('') && e('5'); // 测试某月13日新增的bug数
r(count($calc->getResult(array('day' => '23')))) && p('') && e('5'); // 测试某月23日新增的bug数

r($calc->getResult(array('year' => '2012', 'product' => '3', 'day' => '06'))) && p('0:value') && e('1'); // 测试2012年某月6日产品3新增的bug数
r($calc->getResult(array('year' => '2012', 'month' => '11', 'day' => '06')))  && p('0:value') && e('1'); // 测试2012-11-06新增的bug数