#!/usr/bin/env php
<?php

/**

title=count_of_daily_gitfox_resolved_issues
timeout=0
cid=1

- 测试分组数。 @1
- 测试2026.09.15。第0条的value属性 @2

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 4) . '/lib/calc.unittest.class.php';

zendata('ops_scan_issues')->gen(0);

$issue = zendata('ops_scan_issues');
$issue->id->range('1-4');
$issue->issueKey->range('key1,key2,key3,key4');
$issue->status->range('solved,closed,ignore,wait');
$issue->resolvedDate->range('2026-09-15 10:00:00,2026-09-15 12:00:00,2026-09-15 13:00:00,2026-09-15 14:00:00');
$issue->deleted->range('0{4}');
$issue->gen(4);

$metric = new metricTest();
$calc   = $metric->calcMetric(__FILE__);

r(count($calc->getResult())) && p('') && e('1'); // 测试分组数。

r($calc->getResult(array('year' => '2026', 'month' => '09', 'day' => '15'))) && p('0:value') && e('2'); // 测试2026.09.15。
