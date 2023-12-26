#!/usr/bin/env php
<?php

/**

title=count_of_closed_story
timeout=0
cid=1

- 测试839条数据全局已关闭研发需求数。第0条的value属性 @42
- 测试500条数据全局已关闭研发需求数。第0条的value属性 @28
- 测试1252条数据全局已关闭研发需求数。第0条的value属性 @63

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

$metric = new metricTest();

zdTable('product')->config('product', true, 4)->gen(10);
zdTable('story')->config('story_status_closedreason', true, 4)->gen(839, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('42'); // 测试839条数据全局已关闭研发需求数。

zdTable('story')->config('story_status_closedreason', true, 4)->gen(500, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('28'); // 测试500条数据全局已关闭研发需求数。

zdTable('story')->config('story_status_closedreason', true, 4)->gen(1252, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('63'); // 测试1252条数据全局已关闭研发需求数。