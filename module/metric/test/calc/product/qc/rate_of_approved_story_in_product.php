#!/usr/bin/env php
<?php

/**

title=rate_of_approved_story_in_product
timeout=0
cid=1

- 测试分组数。 @5
- 测试。第0条的value属性 @0.2

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', true, 4)->gen(10);
zdTable('story')->config('story_status', true, 4)->gen(1000);
zdTable('storyreview')->config('storyreview', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('5'); // 测试分组数。

r($calc->getResult(array('product' => '5'))) && p('0:value') && e('0.2');  // 测试。