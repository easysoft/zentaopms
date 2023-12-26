#!/usr/bin/env php
<?php

/**

title=count_of_annual_created_requirement_in_product
timeout=0
cid=1

- 测试按产品的年度新增用户需求分组数。 @8
- 测试2017年产品7新增的用户需求数。第0条的value属性 @6
- 测试2018年产品9新增的用户需求数。第0条的value属性 @20
- 测试已删除产品8新增的用户需求数。第0条的value属性 @0
- 测试不存在的产品的用户需求数。 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', true, 4)->gen(10);
zdTable('story')->config('story_type', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult()))                                     && p('')        && e('8');  // 测试按产品的年度新增用户需求分组数。
r($calc->getResult(array('product' => '7', 'year' => '2017')))   && p('0:value') && e('6');  // 测试2017年产品7新增的用户需求数。
r($calc->getResult(array('product' => '9', 'year' => '2018')))   && p('0:value') && e('20'); // 测试2018年产品9新增的用户需求数。
r($calc->getResult(array('product' => '8', 'year' => '2018')))   && p('0:value') && e('0');  // 测试已删除产品8新增的用户需求数。
r($calc->getResult(array('product' => '999', 'year' => '2021'))) && p('')        && e('0');  // 测试不存在的产品的用户需求数。