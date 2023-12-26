#!/usr/bin/env php
<?php

/**

title=count_of_finished_story
timeout=0
cid=1

- 测试839条数据全局已完成研发需求数。第0条的value属性 @3
- 测试500条数据全局已完成研发需求数。第0条的value属性 @2
- 测试1252条数据全局已完成研发需求数。第0条的value属性 @5

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

$metric = new metricTest();

zdTable('product')->config('product', true, 4)->gen(10);
zdTable('story')->config('story_stage_closedreason', true, 4)->gen(839, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('3'); // 测试839条数据全局已完成研发需求数。

zdTable('story')->config('story_stage_closedreason', true, 4)->gen(500, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('2'); // 测试500条数据全局已完成研发需求数。

zdTable('story')->config('story_stage_closedreason', true, 4)->gen(1252, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('5'); // 测试1252条数据全局已完成研发需求数。