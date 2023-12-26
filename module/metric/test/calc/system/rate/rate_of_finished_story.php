#!/usr/bin/env php
<?php

/**

title=rate_of_finished_story
timeout=0
cid=1

- 测试按全局统计的研发需求完成率第0条的value属性 @0.0614

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', true, 4)->gen(10);
zdTable('story')->config('story_status_closedreason', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r($calc->getResult()) && p('0:value') && e('0.0614'); // 测试按全局统计的研发需求完成率