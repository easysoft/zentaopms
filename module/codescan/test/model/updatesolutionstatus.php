#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
include dirname(__FILE__) . '/common.php';

/**

title=测试 codescanModel->updateSolutionStatus();
timeout=0
cid=0

- 更新 1 号方案状态 @1,1
- 更新 2 号方案状态 @2,1
- 更新 3 号方案状态 @3,1
- 更新 4 号方案状态 @4,1
- 更新 0 号方案状态 @0,1

*/

su('admin');
$test = new codescanModelTest();
initCodescanGitFox($test);

r($test->updateSolutionStatusTest(1)) && p() && e('0');
r($test->updateSolutionStatusTest(2)) && p() && e('0');
r($test->updateSolutionStatusTest(3)) && p() && e('0');
r($test->updateSolutionStatusTest(4)) && p() && e('0');
r($test->updateSolutionStatusTest(0)) && p() && e('0');
