#!/usr/bin/env php
<?php

/**

title=count_of_monthly_closed_bug_in_product
timeout=0
cid=1

- 测试分组数。 @95
- 测试2015.11。第0条的value属性 @3
- 测试2015.12。第0条的value属性 @4
- 测试2016.02。第0条的value属性 @3

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', true, 4)->gen(10);
zdTable('bug')->config('bug_resolution_status', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('95'); // 测试分组数。

r($calc->getResult(array('product' => '9', 'year' => '2015', 'month' => '11'))) && p('0:value') && e('3'); // 测试2015.11。
r($calc->getResult(array('product' => '9', 'year' => '2015', 'month' => '12'))) && p('0:value') && e('4'); // 测试2015.12。
r($calc->getResult(array('product' => '9', 'year' => '2016', 'month' => '02'))) && p('0:value') && e('3'); // 测试2016.02。