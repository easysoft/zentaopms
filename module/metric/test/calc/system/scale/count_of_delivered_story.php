#!/usr/bin/env php
<?php

/**

title=count_of_delivered_story
timeout=0
cid=1

- 测试839条数据全局已交付研发需求数。第0条的value属性 @48
- 测试500条数据全局已关闭研发需求数。第0条的value属性 @32
- 测试1252条数据全局已关闭研发需求数。第0条的value属性 @80

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

$metric = new metricTest();

zdTable('product')->config('product', true, 4)->gen(10);
zdTable('story')->config('story_stage_closedreason', true, 4)->gen(839, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('48'); // 测试839条数据全局已交付研发需求数。

zdTable('story')->config('story_stage_closedreason', true, 4)->gen(500, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('32'); // 测试500条数据全局已关闭研发需求数。

zdTable('story')->config('story_stage_closedreason', true, 4)->gen(1252, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('80'); // 测试1252条数据全局已关闭研发需求数。