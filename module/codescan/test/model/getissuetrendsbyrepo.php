#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1);
su('admin');

/**

title=测试 codescanModel->getIssueTrendsByRepo();
timeout=0
cid=0

- 测试day范围 >> 0
- 测试day维度趋势返回0 >> 2,day,0,none
- 测试week范围 >> 0
- 测试month范围 >> 0
- 测试month维度趋势返回0 >> 1,month,0,none

*/

$test = new codescanModelTest();

r($test->getissuetrendsbyrepoTest(1, 0, 'day')) && p() && e('0');
r($test->getissuetrendsbyrepoTest(2, 0, 'day')) && p() && e('0');
r($test->getissuetrendsbyrepoTest(0, 0, 'week')) && p() && e('0');
r($test->getissuetrendsbyrepoTest(1, 0, 'month')) && p() && e('0');
r($test->getissuetrendsbyrepoTest(3, 0, 'year')) && p() && e('0');
