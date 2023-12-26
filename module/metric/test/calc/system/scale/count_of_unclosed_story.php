#!/usr/bin/env php
<?php

/**

title=count_of_unclosed_story
timeout=0
cid=1

- 测试839条数据全局未关闭研发需求数。第0条的value属性 @189
- 测试500条数据全局未关闭研发需求数。第0条的value属性 @117
- 测试1252条数据全局未关闭研发需求数。第0条的value属性 @288

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

$metric = new metricTest();

zdTable('product')->config('product', true, 4)->gen(10);
zdTable('story')->config('story_stage', true, 4)->gen(839, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('189'); // 测试839条数据全局未关闭研发需求数。

zdTable('story')->config('story_stage', true, 4)->gen(500, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('117'); // 测试500条数据全局未关闭研发需求数。

zdTable('story')->config('story_stage', true, 4)->gen(1252, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('288'); // 测试1252条数据全局未关闭研发需求数。