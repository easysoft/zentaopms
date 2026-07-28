#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
include dirname(__FILE__) . '/common.php';

/**

title=测试 codescanModel->deleteSolution();
timeout=0
cid=0

- 删除 1 号方案 @1,1
- 删除 2 号方案 @2,1
- 删除 3 号方案 @3,1
- 删除 4 号方案 @4,1
- 删除 0 号方案 @0,1

*/

su('admin');
$test = new codescanModelTest();
initCodescanGitFox($test);

r($test->deleteSolutionTest(1)) && p() && e('0');
r($test->deleteSolutionTest(2)) && p() && e('0');
r($test->deleteSolutionTest(3)) && p() && e('0');
r($test->deleteSolutionTest(4)) && p() && e('0');
r($test->deleteSolutionTest(0)) && p() && e('0');
