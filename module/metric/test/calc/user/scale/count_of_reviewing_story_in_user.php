#!/usr/bin/env php
<?php

/**

title=count_of_reviewing_story_in_user
timeout=0
cid=1

- 测试分组数。 @3
- 测试用户dev。第0条的value属性 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/lib/calc.class.php';

zendata('product')->loadYaml('product', true, 4)->gen(10);
zendata('story')->loadYaml('story_status_version', true, 4)->gen(1000);
zendata('storyreview')->loadYaml('storyreview', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('3'); // 测试分组数。

r($calc->getResult(array('user' => 'dev'))) && p('0:value') && e('0'); // 测试用户dev。