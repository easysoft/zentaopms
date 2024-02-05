#!/usr/bin/env php
<?php

/**

title=count_of_annual_created_story_in_product
timeout=0
cid=1

- 测试分组数. @7
- 测试2016年产品7创建的需求数。第0条的value属性 @14
- 测试2017年产品7创建的需求数。第0条的value属性 @6
- 测试已删除产品8创建的需求数。第0条的value属性 @0
- 测试不存在的年份创建的需求数。 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('story')->config('story_type', true, 4)->gen(1000);
zdTable('product')->config('product', true, 4)->gen(10);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult()))                                   && p('')        && e('7');  // 测试分组数.
r($calc->getResult(array('product' => '7', 'year' => '2016'))) && p('0:value') && e('14'); // 测试2016年产品7创建的需求数。
r($calc->getResult(array('product' => '7', 'year' => '2017'))) && p('0:value') && e('6');  // 测试2017年产品7创建的需求数。
r($calc->getResult(array('product' => '8', 'year' => '2017'))) && p('0:value') && e('0');  // 测试已删除产品8创建的需求数。
r($calc->getResult(array('product' => '9', 'year' => '2024'))) && p('')        && e('0');  // 测试不存在的年份创建的需求数。