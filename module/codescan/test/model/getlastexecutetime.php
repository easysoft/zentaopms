#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry', false, 2)->gen(1);
su('admin');

/**

title=测试 codescanModel->getLastExecuteTime();
timeout=0
cid=0

- step1 >> 0
- step2 >> 1
- step3 >> 0
- step4 >> 1
- step5 >> 0

*/

$test = new codescanModelTest();

r($test->getlastexecutetimeTest()) && p() && e('0');
$result = $test->getlastexecutetimeTest();
r(is_string($result) ? '1' : '0') && p() && e('1');
r($test->getlastexecutetimeTest()) && p() && e('0');
$result2 = $test->getlastexecutetimeTest();
r(is_string($result2) ? '1' : '0') && p() && e('1');
r($test->getlastexecutetimeTest()) && p() && e('0');
