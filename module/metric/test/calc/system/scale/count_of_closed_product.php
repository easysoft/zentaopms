#!/usr/bin/env php
<?php

/**

title=count_of_closed_product
timeout=0
cid=1

- 测试产品数量第0条的value属性 @44

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product_shadow', true, 4)->gen(356, true, false);
$metric = new metricTest();
$calc = $metric->calcMetric(__FILE__);

r($calc->getResult()) && p('0:value') && e('44'); // 测试产品数量