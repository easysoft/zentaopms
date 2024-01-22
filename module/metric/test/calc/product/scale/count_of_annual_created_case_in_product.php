#!/usr/bin/env php
<?php

/**

title=count_of_annual_created_case_in_product
timeout=0
cid=1

- 测试分组数 @10
- 测试2010年产品1新增的用例数第0条的value属性 @37
- 测试2019年产品5新增的用例数第0条的value属性 @31
- 测试2012年产品7新增的用例数第0条的value属性 @32
- 测试不存在的产品的新增用例数 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', true, 4)->gen(10);
zdTable('case')->config('case', true, 4)->gen(800);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult()))                                     && p('')        && e('10'); // 测试分组数
r($calc->getResult(array('product' => '1', 'year' => '2010')))   && p('0:value') && e('37'); // 测试2010年产品1新增的用例数
r($calc->getResult(array('product' => '5', 'year' => '2019')))   && p('0:value') && e('31'); // 测试2019年产品5新增的用例数
r($calc->getResult(array('product' => '7', 'year' => '2012')))   && p('0:value') && e('32'); // 测试2012年产品7新增的用例数
r($calc->getResult(array('product' => '999', 'year' => '2021'))) && p('')        && e('0');  // 测试不存在的产品的新增用例数