#!/usr/bin/env php
<?php
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

$metric = new metricTest();

/**

title=count_of_valid_story
timeout=0
cid=1

*/

zdTable('story')->config('story_status_closedreason', $useCommon = true, $levels = 4)->gen(500);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('146'); // 测试500条数据全局有效研发需求数。

zdTable('story')->config('story_status_closedreason', $useCommon = true, $levels = 4)->gen(839);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('240'); // 测试839条数据全局有效研发需求数。

zdTable('story')->config('story_status_closedreason', $useCommon = true, $levels = 4)->gen(1252);
$calc = $metric->calcMetric(__FILE__);
r($calc->getResult()) && p('0:value') && e('360'); // 测试1252条数据全局有效研发需求数。
