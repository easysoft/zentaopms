#!/usr/bin/env php
<?php

/**

title=count_of_daily_gitfox_commits
timeout=0
cid=1

- 测试统计区间。第0条 @2026-09-15
- 测试统计区间。第1条 @2026-09-16
- 测试2026.09.15。第0条的value属性 @5
- 测试2026.09.16。第0条的value属性 @3
- 测试不存在的日期。 @0

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 6) . '/metric/calc.class.php';
include dirname(__FILE__, 6) . '/metric/calc/system/scale/count_of_daily_gitfox_commits.php';

$calc = new count_of_daily_gitfox_commits();
$calc->setResult('2026-09-15', 5);
$calc->setResult('2026-09-16', 3);
$calc->setResult('2026-09-16', 0);

r($calc->getDateRange(array('year' => '2026', 'month' => '9', 'day' => '16,15'))) && p('0') && e('2026-09-15'); // 测试统计区间。
r($calc->getDateRange(array('year' => '2026', 'month' => '9', 'day' => '16,15'))) && p('1') && e('2026-09-16'); // 测试统计区间。

r($calc->getResult(array('year' => '2026', 'month' => '09', 'day' => '15'))) && p('0:value') && e('5'); // 测试2026.09.15。
r($calc->getResult(array('year' => '2026', 'month' => '09', 'day' => '16'))) && p('0:value') && e('3'); // 测试2026.09.16。
r($calc->getResult(array('year' => '2026', 'month' => '09', 'day' => '17'))) && p('') && e('0'); // 测试不存在的日期。
