#!/usr/bin/env php
<?php
/**
title=count_of_daily_review_story_in_user
timeout=0
cid=1
*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('product')->config('product_shadow', true, 4)->gen(20);
zdTable('story')->config('story_projected', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('40'); // 测试分组数。

r($calc->getResult(array('user' => 'po', 'year' => '2018', 'month' => '01', 'day' => '28'))) && p('0:value') && e('1'); // 测试po在2018年1月28日完成的任务。
r($calc->getResult(array('user' => 'po', 'year' => '2018', 'month' => '02', 'day' => '13'))) && p('0:value') && e('0'); // 测试po在2018年2月13日完成的任务。
