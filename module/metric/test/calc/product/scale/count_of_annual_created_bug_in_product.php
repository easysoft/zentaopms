#!/usr/bin/env php
<?php

/**

title=count_of_created_bug_in_product
timeout=0
cid=1

- 测试创建bug按产品分组数。 @17
- 测试产品1创建的bug数。第0条的value属性 @24
- 测试产品3创建的bug数。第0条的value属性 @14
- 测试已删除产品4创建的bug数。第0条的value属性 @0
- 测试不存在的产品创建的bug数。 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', true, 4)->gen(10);
zdTable('bug')->config('bug_resolution_status', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult()))                   && p('')        && e('17'); // 测试创建bug按产品分组数。
r($calc->getResult(array('product' => '1')))   && p('0:value') && e('24'); // 测试产品1创建的bug数。
r($calc->getResult(array('product' => '3')))   && p('0:value') && e('14'); // 测试产品3创建的bug数。
r($calc->getResult(array('product' => '4')))   && p('0:value') && e('0');  // 测试已删除产品4创建的bug数。
r($calc->getResult(array('product' => '999'))) && p('')        && e('0');  // 测试不存在的产品创建的bug数。