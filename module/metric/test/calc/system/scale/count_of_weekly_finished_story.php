#!/usr/bin/env php
<?php

/**

title=count_of_weekly_finished_story
timeout=0
cid=1

- 测试分组数 @7
- 测试2015年第36周的需求数。第0条的value属性 @1
- 测试2019年第27周的需求数。第0条的value属性 @1
- 测试错误的周数。第0条的value属性 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/lib/calc.unittest.class.php';

zendata('product')->loadYaml('product', true, 4)->gen(10);
zendata('story')->loadYaml('story_status_closedreason', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('7'); // 测试分组数

r($calc->getResult(array('year' => '2015', 'week' => '36'))) && p('0:value') && e('1'); // 测试2015年第36周的需求数。
r($calc->getResult(array('year' => '2019', 'week' => '27'))) && p('0:value') && e('1'); // 测试2019年第27周的需求数。
r($calc->getResult(array('year' => '2016', 'week' => '99'))) && p('0:value') && e('0'); // 测试错误的周数。