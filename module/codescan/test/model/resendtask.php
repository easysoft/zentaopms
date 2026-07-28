#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
zenData('entry')->loadYaml('entry', false, 2)->gen(1, true, false);

/**

title=测试 codescanModel->resendTask();
timeout=0
cid=0

- 重试 1 号任务 @1,1
- 重试 2 号任务 @2,1
- 重试 3 号任务 @3,1
- 重试 4 号任务 @4,1
- 重试 0 号任务 @0,1

*/

su('admin');
$test = new codescanModelTest();

r($test->resendTaskTest(1)) && p() && e('0');
r($test->resendTaskTest(2)) && p() && e('0');
r($test->resendTaskTest(3)) && p() && e('0');
r($test->resendTaskTest(4)) && p() && e('0');
r($test->resendTaskTest(0)) && p() && e('0');
