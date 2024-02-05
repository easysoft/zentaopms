#!/usr/bin/env php
<?php

/**

title=count_of_story
timeout=0
cid=1

- 测试839条数据全局研发需求数。第0条的value属性 @210
- 测试500条数据全局研发需求数。第0条的value属性 @130
- 测试1252条数据全局研发需求数。第0条的value属性 @320

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

$metric = new metricTest();

zdTable('product')->config('product', true, 4)->gen(10);
zdTable('story')->config('story_stage', true, 4)->gen(839, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('210'); // 测试839条数据全局研发需求数。

zdTable('story')->config('story_stage', true, 4)->gen(500, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('130'); // 测试500条数据全局研发需求数。

zdTable('story')->config('story_stage', true, 4)->gen(1252, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('320'); // 测试1252条数据全局研发需求数。