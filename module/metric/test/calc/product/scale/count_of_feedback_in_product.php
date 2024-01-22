#!/usr/bin/env php
<?php

/**

title=count_of_feedback_in_product
timeout=0
cid=1

- 测试反馈按产品分组数。 @5
- 测试产品1的反馈数。第0条的value属性 @54
- 测试产品3的反馈数。第0条的value属性 @54
- 测试已删除产品4的反馈数。第0条的value属性 @0
- 测试不存在的产品的反馈数。 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('feedback')->config('feedback', true, 4)->gen(1000);
zdTable('product')->config('product', true, 4)->gen(10);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult()))                   && p('')        && e('5');  // 测试反馈按产品分组数。
r($calc->getResult(array('product' => '1')))   && p('0:value') && e('54'); // 测试产品1的反馈数。
r($calc->getResult(array('product' => '3')))   && p('0:value') && e('54'); // 测试产品3的反馈数。
r($calc->getResult(array('product' => '4')))   && p('0:value') && e('0');  // 测试已删除产品4的反馈数。
r($calc->getResult(array('product' => '999'))) && p('')        && e('0');  // 测试不存在的产品的反馈数。