#!/usr/bin/env php
<?php

/**

title=count_of_monthly_created_bug_in_product
timeout=0
cid=1

- 测试分组数。 @93
- 测试2012.01。第0条的value属性 @4
- 测试2012.02。第0条的value属性 @2
- 测试2012.03。第0条的value属性 @4

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', true, 4)->gen(10);
zdTable('bug')->config('bug_resolution_status', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('93'); // 测试分组数。

r($calc->getResult(array('product' => '9', 'year' => '2012', 'month' => '01'))) && p('0:value') && e('4'); // 测试2012.01。
r($calc->getResult(array('product' => '9', 'year' => '2012', 'month' => '02'))) && p('0:value') && e('2'); // 测试2012.02。
r($calc->getResult(array('product' => '9', 'year' => '2012', 'month' => '03'))) && p('0:value') && e('4'); // 测试2012.03。