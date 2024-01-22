#!/usr/bin/env php
<?php

/**

title=scale_of_story_in_product
timeout=0
cid=1

- 测试产品1的需求规模数。第0条的value属性 @560
- 测试产品3的需求规模数。第0条的value属性 @680
- 测试已删除产品4的需求规模数。第0条的value属性 @0
- 测试不存在的产品的需求规模数。 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', true, 4)->gen(10);
zdTable('story')->config('story_stage_closedreason', true, 4)->gen(2000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r($calc->getResult(array('product' => '1')))   && p('0:value') && e('560'); // 测试产品1的需求规模数。
r($calc->getResult(array('product' => '3')))   && p('0:value') && e('680'); // 测试产品3的需求规模数。
r($calc->getResult(array('product' => '4')))   && p('0:value') && e('0');   // 测试已删除产品4的需求规模数。
r($calc->getResult(array('product' => '999'))) && p('')        && e('0');   // 测试不存在的产品的需求规模数。