#!/usr/bin/env php
<?php

/**

title=scale_of_weekly_finished_story
timeout=0
cid=1

- 测试分组数 @7
- 测试2013年第16周的需求数。第0条的value属性 @7
- 测试2017年第7周的需求数。第0条的value属性 @9
- 测试错误的周数。第0条的value属性 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/lib/calc.unittest.class.php';

zendata('product')->loadYaml('product', true, 4)->gen(10);
zendata('story')->loadYaml('story_status_closedreason', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('7'); // 测试分组数

r($calc->getResult(array('year' => '2013', 'week' => '19'))) && p('0:value') && e('7'); // 测试2013年第16周的需求数。
r($calc->getResult(array('year' => '2014', 'week' => '46'))) && p('0:value') && e('3'); // 测试2017年第7周的需求数。
r($calc->getResult(array('year' => '2016', 'week' => '99'))) && p('0:value') && e('0'); // 测试错误的周数。
