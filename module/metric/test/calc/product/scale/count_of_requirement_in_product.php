#!/usr/bin/env php
<?php

/**

title=count_of_requirement_in_product
timeout=0
cid=1

- 测试按产品统计的用户需求分组数。 @5
- 测试产品5的用户需求数。第0条的value属性 @30
- 测试产品7的用户需求数。第0条的value属性 @20
- 测试已删除产品8的用户需求数。第0条的value属性 @0
- 测试不存在的产品的用户需求数。 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', true, 4)->gen(10);
zdTable('story')->config('story_type', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult()))                   && p('')        && e('5');  // 测试按产品统计的用户需求分组数。
r($calc->getResult(array('product' => '5')))   && p('0:value') && e('30'); // 测试产品5的用户需求数。
r($calc->getResult(array('product' => '7')))   && p('0:value') && e('20'); // 测试产品7的用户需求数。
r($calc->getResult(array('product' => '8')))   && p('0:value') && e('0');  // 测试已删除产品8的用户需求数。
r($calc->getResult(array('product' => '999'))) && p('')        && e('0');  // 测试不存在的产品的用户需求数。