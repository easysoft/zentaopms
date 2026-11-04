#!/usr/bin/env php
<?php

/**

title=count_of_daily_gitfox_merged_reviews
timeout=0
cid=1

- 测试分组数。 @2
- 测试2026.09.15。第0条的value属性 @1
- 测试2026.09.16。第0条的value属性 @2
- 测试第一条记录的年份 @2026
- 测试第一条记录的日期 @15
- 测试未合并的评审不计数。
- 测试2026.09.17。 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/lib/calc.unittest.class.php';

zendata('ops_ppm')->gen(0);

$ppm = zendata('ops_ppm');
$ppm->id->range('1-4');
$ppm->title->range('review1,review2,review3,review4');
$ppm->status->range('merged,opened,merged,merged');
$ppm->mergedDate->range('`2026-09-15 10:00:00`,`2026-09-17 10:00:00`,`2026-09-16 11:00:00`,`2026-09-16 20:00:00`');
$ppm->gen(4);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('2'); // 测试分组数。

r($calc->getResult(array('year' => '2026', 'month' => '09', 'day' => '15'))) && p('0:value') && e('1'); // 测试2026.09.15。
r($calc->getResult(array('year' => '2026', 'month' => '09', 'day' => '16'))) && p('0:value') && e('2'); // 测试2026.09.16。
r($calc->getResult(array('year' => '2026', 'month' => '09', 'day' => '15'))) && p('0:year') && e('2026'); // 测试第一条记录的年份。
r($calc->getResult(array('year' => '2026', 'month' => '09', 'day' => '15'))) && p('0:day') && e('15');   // 测试第一条记录的日期。
r($calc->getResult(array('year' => '2026', 'month' => '09', 'day' => '17'))) && p() && e('0');          // 测试未合并的评审不计数。
