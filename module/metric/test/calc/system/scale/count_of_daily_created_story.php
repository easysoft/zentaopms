#!/usr/bin/env php
<?php

/**

title=count_of_daily_created_story
timeout=0
cid=1

- 测试分组数。 @160
- 测试2017.09.21。第0条的value属性 @2

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', true, 4)->gen(10);
zdTable('story')->config('story_status', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('160'); // 测试分组数。

r($calc->getResult(array('year' => '2017', 'month' => '09', 'day' => '21'))) && p('0:value') && e('2'); // 测试2017.09.21。