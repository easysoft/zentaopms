#!/usr/bin/env php
<?php

/**

title=count_of_normal_product
timeout=0
cid=1

- 测试356条数据正常产品数。第0条的value属性 @13
- 测试652条数据正常产品数。第0条的value属性 @25
- 测试1265条数据正常产品数。第0条的value属性 @125

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

$metric = new metricTest();

zdTable('product')->config('product_shadow', true, 4)->gen(100, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('13'); // 测试356条数据正常产品数。

zdTable('product')->config('product_shadow', true, 4)->gen(200, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('25'); // 测试652条数据正常产品数。

zdTable('product')->config('product_shadow', true, 4)->gen(1000, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('125'); // 测试1265条数据正常产品数。