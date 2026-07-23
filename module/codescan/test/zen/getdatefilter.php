#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 codescanZen->getDateFilter();
timeout=0
cid=0

- 测试$lastWeek查询 >> 1
- 测试$thisWeek查询 >> 1
- 测试$lastMonth查询 >> 1
- 测试$thisMonth查询 >> 1
- 测试$today查询 >> 1

*/


su('admin');
$test = new codescanZenTest();

r(is_array($test->getDateFilterTest('$lastWeek'))) && p() && e('1');
r(is_array($test->getDateFilterTest('$thisWeek'))) && p() && e('1');
r(is_array($test->getDateFilterTest('$lastMonth'))) && p() && e('1');
r(is_array($test->getDateFilterTest('$thisMonth'))) && p() && e('1');
r(is_array($test->getDateFilterTest('$today'))) && p() && e('1');
