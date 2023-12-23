#!/usr/bin/env php
<?php
/**
title=count_of_reviewing_story_in_user
timeout=0
cid=1
*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product', true, 4)->gen(10);
zdTable('story')->config('story_status', true, 4)->gen(1000);
zdTable('storyreview')->config('storyreview', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('6'); // 测试分组数。

r($calc->getResult(array('user' => 'dev'))) && p('0:value') && e('6'); // 测试用户dev。
