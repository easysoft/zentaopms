#!/usr/bin/env php
<?php

/**

title=count_of_monthly_created_release_in_product
timeout=0
cid=1

- 测试分组数。 @200
- 测试2019.09。第0条的value属性 @1
- 测试2020.04。第0条的value属性 @1
- 测试不存在的产品的发布数。 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', true, 4)->gen(10);
zdTable('release')->config('release', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('200'); // 测试分组数。

r($calc->getResult(array('product' => '9', 'year' => '2019', 'month' => '09'))) && p('0:value') && e('1');  // 测试2019.09。
r($calc->getResult(array('product' => '9', 'year' => '2020', 'month' => '04'))) && p('0:value') && e('1');  // 测试2020.04。

r($calc->getResult(array('product' => '999', 'year' => '2018'))) && p('') && e('0');  // 测试不存在的产品的发布数。