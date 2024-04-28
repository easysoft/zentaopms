#!/usr/bin/env php
<?php

/**

title=ac_of_weekly_all_in_waterfall
timeout=0
cid=1

- 测试分组数。 @5
- 测试项目1。第0条的value属性 @8.5

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/calc.class.php';

zdTable('project')->config('waterfall', true, 4)->gen(10);
zdTable('effort')->config('effort_ac', true, 4)->gen(1000);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('5'); // 测试分组数。

r($calc->getResult(array('project' => '1', 'year' => '2024', 'week' => '05'))) && p('0:value') && e('8.5'); // 测试项目1。