#!/usr/bin/env php
<?php

/**

title=count_of_daily_gitfox_commits
timeout=0
cid=1

- 测试统计区间。第0条 @2026-09-15
- 测试统计区间。第1条 @2026-09-16
- 测试单日统计区间。 @2026-09-15
- 测试缺少统计参数时返回空值。 @~~
- 测试统计记录条数。 @2
- 测试2026.09.15。第0条的day属性 @15
- 测试2026.09.16。第1条的value属性 @3

*/
include dirname(__FILE__, 7) . '/test/lib/init.php';
include dirname(__FILE__, 6) . '/metric/calc.class.php';
include dirname(__FILE__, 6) . '/metric/calc/system/scale/count_of_daily_gitfox_commits.php';

$calc = new count_of_daily_gitfox_commits();
$calc->setResult('2026-09-15', 5);
$calc->setResult('2026-09-16', 3);
$calc->setResult('2026-09-16', 0); // 提交数为0时不覆盖已有统计结果。

r($calc->getDateRange(array('year' => '2026', 'month' => '9', 'day' => '16,15'))) && p('0') && e('2026-09-15');      // 测试统计区间的起始日期。
r($calc->getDateRange(array('year' => '2026', 'month' => '9', 'day' => '16,15'))) && p('1') && e('2026-09-16');      // 测试统计区间的结束日期。
r($calc->getDateRange(array('year' => '2026', 'month' => '9', 'day' => '15')))    && p('0') && e('2026-09-15');      // 测试单日统计区间。
r($calc->getDateRange(array()))                                                    && p('0') && e('~~');             // 测试缺少统计参数时返回空值。

r(count($calc->getResult(array('year' => '2026', 'month' => '09', 'day' => '15')))) && p() && e('2');                 // 测试统计记录条数。
r($calc->getResult(array('year' => '2026', 'month' => '09', 'day' => '15')))       && p('0:day') && e('15');         // 测试2026.09.15。
r($calc->getResult(array('year' => '2026', 'month' => '09', 'day' => '16')))       && p('1:value') && e('3');       // 测试2026.09.16，提交数为0时不覆盖原值。
