#!/usr/bin/env php
<?php

/**

title=count_of_monthly_fixed_bug_in_product
timeout=0
cid=1

- 测试分组数。 @21
- 测试2013.03。第0条的value属性 @1
- 测试2013.04。第0条的value属性 @2
- 测试2012.09。第0条的value属性 @1
- 测试不存在的产品。 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('bug')->config('bug_resolution_status', true, 4)->gen(1000);
zdTable('product')->config('product', true, 4)->gen(10);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('21'); // 测试分组数。

r($calc->getResult(array('product' => '7', 'year' => '2013', 'month' => '03'))) && p('0:value') && e('1'); // 测试2013.03。
r($calc->getResult(array('product' => '7', 'year' => '2013', 'month' => '04'))) && p('0:value') && e('2'); // 测试2013.04。
r($calc->getResult(array('product' => '9', 'year' => '2012', 'month' => '09'))) && p('0:value') && e('1'); // 测试2012.09。

r($calc->getResult(array('product' => '999', 'year' => '2021'))) && p('') && e('0'); // 测试不存在的产品。