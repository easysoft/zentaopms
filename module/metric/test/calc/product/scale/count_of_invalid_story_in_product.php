#!/usr/bin/env php
<?php

/**

title=count_of_invalid_story_in_product
timeout=0
cid=1

- 测试分组数 @5
- 测试产品1无效需求数第0条的value属性 @60
- 测试产品3无效需求数第0条的value属性 @60
- 测试已删除产品4无效需求数第0条的value属性 @0
- 测试不存在产品的无效需求数 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('story')->config('story_status_closedreason', true, 4)->gen(2000);
zdTable('product')->config('product', true, 4)->gen(10);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult()))                   && p('')        && e('5');  // 测试分组数
r($calc->getResult(array('product' => '1')))   && p('0:value') && e('60'); // 测试产品1无效需求数
r($calc->getResult(array('product' => '3')))   && p('0:value') && e('60'); // 测试产品3无效需求数
r($calc->getResult(array('product' => '4')))   && p('0:value') && e('0');  // 测试已删除产品4无效需求数
r($calc->getResult(array('product' => '999'))) && p('')        && e('0');  // 测试不存在产品的无效需求数