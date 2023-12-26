#!/usr/bin/env php
<?php

/**

title=count_of_annual_created_feedback_in_product
timeout=0
cid=1

- 测试按产品的年度新增反馈分组数。 @46
- 测试2015年产品9新增的反馈数。第0条的value属性 @4
- 测试2016年产品9新增的反馈数。第0条的value属性 @2
- 测试已删除产品8新增的反馈数。 @0
- 测试不存在的产品的反馈数。 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', true, 4)->gen(10);
zdTable('feedback')->config('feedback', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult()))                                     && p('')        && e('46'); // 测试按产品的年度新增反馈分组数。
r($calc->getResult(array('product' => '9', 'year' => '2015')))   && p('0:value') && e('4');  // 测试2015年产品9新增的反馈数。
r($calc->getResult(array('product' => '9', 'year' => '2016')))   && p('0:value') && e('2');  // 测试2016年产品9新增的反馈数。
r($calc->getResult(array('product' => '8', 'year' => '2022')))   && p('')        && e('0');  // 测试已删除产品8新增的反馈数。
r($calc->getResult(array('product' => '999', 'year' => '2021'))) && p('')        && e('0');  // 测试不存在的产品的反馈数。