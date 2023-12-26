#!/usr/bin/env php
<?php

/**

title=count_of_case_in_product
timeout=0
cid=1

- 测试产品1的用例数第0条的value属性 @40
- 测试产品3的用例数第0条的value属性 @40
- 测试产品5的用例数第0条的value属性 @40
- 测试产品7的用例数第0条的value属性 @40
- 测试产品9的用例数第0条的value属性 @40

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', true, 4)->gen(10);
zdTable('case')->config('case', true, 4)->gen(800);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r($calc->getResult(array('product' => '1'))) && p('0:value') && e('40'); // 测试产品1的用例数
r($calc->getResult(array('product' => '3'))) && p('0:value') && e('40'); // 测试产品3的用例数
r($calc->getResult(array('product' => '5'))) && p('0:value') && e('40'); // 测试产品5的用例数
r($calc->getResult(array('product' => '7'))) && p('0:value') && e('40'); // 测试产品7的用例数
r($calc->getResult(array('product' => '9'))) && p('0:value') && e('40'); // 测试产品9的用例数