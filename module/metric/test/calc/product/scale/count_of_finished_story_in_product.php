#!/usr/bin/env php
<?php

/**

title=count_of_finished_story_in_product
timeout=0
cid=1

- 测试分组数。 @5
- 测试产品5的需求数。第0条的value属性 @2
- 测试已删除产品6的需求数。第0条的value属性 @0
- 测试不存在的产品的需求数。 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('story')->config('story_stage_closedreason', true, 4)->gen(2000);
zdTable('product')->config('product', true, 4)->gen(10);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult()))                   && p('')        && e('5'); // 测试分组数。
r($calc->getResult(array('product' => '5')))   && p('0:value') && e('2'); // 测试产品5的需求数。
r($calc->getResult(array('product' => '6')))   && p('0:value') && e('0'); // 测试已删除产品6的需求数。
r($calc->getResult(array('product' => '999'))) && p('')        && e('0'); // 测试不存在的产品的需求数。