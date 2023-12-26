#!/usr/bin/env php
<?php

/**

title=count_of_monthly_delivered_story_in_product
timeout=0
cid=1

- 测试分组数。 @75
- 测试产品5 2012年2月。第0条的value属性 @2
- 测试产品5 2012年3月。第0条的value属性 @3
- 测试产品7 2012年5月。第0条的value属性 @3

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', true, 4)->gen(10);
zdTable('story')->config('story_stage_closedreason', true, 4)->gen(2000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('75'); // 测试分组数。

r($calc->getResult(array('product' => '9', 'year' => '2012', 'month' => '02'))) && p('0:value') && e('2'); // 测试产品5 2012年2月。
r($calc->getResult(array('product' => '9', 'year' => '2012', 'month' => '03'))) && p('0:value') && e('3'); // 测试产品5 2012年3月。
r($calc->getResult(array('product' => '9', 'year' => '2012', 'month' => '05'))) && p('0:value') && e('3'); // 测试产品7 2012年5月。