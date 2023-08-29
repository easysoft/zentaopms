#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product_shadow', $useCommon = true, $levels = 4)->gen(356, true, false);
$metric = new metricTest();
$calc = $metric->calcMetric(__FILE__);

/**

title=count_of_closed_product
timeout=0
cid=1

*/

r($calc->getResult()) && p('0:value') && e('44'); // 测试产品数量

