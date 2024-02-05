#!/usr/bin/env php
<?php

/**

title=count_of_annual_delivered_story_in_product
timeout=0
cid=1

- 测试分组数。 @18
- 测试产品5 2014年。第0条的value属性 @1
- 测试产品5 2015年。第0条的value属性 @12
- 测试产品7 2015年。第0条的value属性 @3

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', true, 4)->gen(10);
zdTable('story')->config('story_stage_closedreason', true, 4)->gen(2000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('18'); // 测试分组数。

r($calc->getResult(array('product' => '5', 'year' => '2014'))) && p('0:value') && e('1');  // 测试产品5 2014年。
r($calc->getResult(array('product' => '5', 'year' => '2015'))) && p('0:value') && e('12'); // 测试产品5 2015年。
r($calc->getResult(array('product' => '7', 'year' => '2015'))) && p('0:value') && e('3');  // 测试产品7 2015年。