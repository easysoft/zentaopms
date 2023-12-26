#!/usr/bin/env php
<?php

/**

title=rate_of_fixed_bug_product
timeout=0
cid=1

- 测试分组数。 @5
- 测试产品1的bug修复率。第0条的value属性 @0.0833
- 测试产品3的bug修复率。第0条的value属性 @0.0833
- 测试产品5的bug修复率。第0条的value属性 @0.0833
- 测试产品7的bug修复率。第0条的value属性 @0.0833
- 测试产品9的bug修复率。第0条的value属性 @0.0833
- 测试产品10的bug修复率。 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', true, 4)->gen(10);
zdTable('bug')->config('bug_resolution_status', true, 4)->gen(3000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('5'); // 测试分组数。

r($calc->getResult(array('product' => '1')))  && p('0:value') && e('0.0833'); // 测试产品1的bug修复率。
r($calc->getResult(array('product' => '3')))  && p('0:value') && e('0.0833'); // 测试产品3的bug修复率。
r($calc->getResult(array('product' => '5')))  && p('0:value') && e('0.0833'); // 测试产品5的bug修复率。
r($calc->getResult(array('product' => '7')))  && p('0:value') && e('0.0833'); // 测试产品7的bug修复率。
r($calc->getResult(array('product' => '9')))  && p('0:value') && e('0.0833'); // 测试产品9的bug修复率。
r($calc->getResult(array('product' => '10'))) && p('')        && e('0');      // 测试产品10的bug修复率。