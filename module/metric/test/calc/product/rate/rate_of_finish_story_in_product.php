#!/usr/bin/env php
<?php

/**

title=rate_of_finish_story_in_product.php
timeout=0
cid=1

- 测试产品1的研发需求完成率。第0条的value属性 @0.0333
- 测试产品3的研发需求完成率。第0条的value属性 @0.0333
- 测试产品5的研发需求完成率。第0条的value属性 @0.0333
- 测试产品7的研发需求完成率。第0条的value属性 @0.0333
- 测试产品9的研发需求完成率。第0条的value属性 @0.0333
- 测试产品10的研发需求完成率。 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', true, 4)->gen(10);
zdTable('story')->config('story_stage_closedreason', true, 4)->gen(3000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r($calc->getResult(array('product' => '1')))  && p('0:value') && e('0.0333'); // 测试产品1的研发需求完成率。
r($calc->getResult(array('product' => '3')))  && p('0:value') && e('0.0333'); // 测试产品3的研发需求完成率。
r($calc->getResult(array('product' => '5')))  && p('0:value') && e('0.0333'); // 测试产品5的研发需求完成率。
r($calc->getResult(array('product' => '7')))  && p('0:value') && e('0.0333'); // 测试产品7的研发需求完成率。
r($calc->getResult(array('product' => '9')))  && p('0:value') && e('0.0333'); // 测试产品9的研发需求完成率。
r($calc->getResult(array('product' => '10'))) && p('')        && e('0');      // 测试产品10的研发需求完成率。