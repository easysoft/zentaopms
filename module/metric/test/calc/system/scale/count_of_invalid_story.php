#!/usr/bin/env php
<?php

/**

title=count_of_valid_story
timeout=0
cid=1

- 测试500条数据全局无效研发需求数。第0条的value属性 @60
- 测试839条数据全局无效研发需求数。第0条的value属性 @90
- 测试1252条数据全局无效研发需求数。第0条的value属性 @135

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/lib/calc.unittest.class.php';

$metric = new metricTest();

zendata('product')->loadYaml('product', true, 4)->gen(10);
zendata('story')->loadYaml('story_status_closedreason', true, 4)->gen(500, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('60'); // 测试500条数据全局无效研发需求数。

zendata('story')->loadYaml('story_status_closedreason', true, 4)->gen(839, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('90'); // 测试839条数据全局无效研发需求数。

zendata('story')->loadYaml('story_status_closedreason', true, 4)->gen(1252, true, false);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('135'); // 测试1252条数据全局无效研发需求数。