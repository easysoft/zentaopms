#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
include dirname(__FILE__) . '/common.php';

/**

title=测试 codescanModel->deleteScanPlan();
timeout=0
cid=0

- 删除 1 号仓库 1 号计划 @1,1,1
- 删除 1 号仓库 2 号计划 @1,2,1
- 删除 2 号仓库 1 号计划 @2,1,1
- 删除零仓库 1 号计划 @0,1,1
- 删除 1 号仓库零计划 @1,0,1

*/

su('admin');
$test = new codescanModelTest();
initCodescanGitFox($test);

r($test->deleteScanPlanTest(1, 1)) && p() && e('0');
r($test->deleteScanPlanTest(1, 2)) && p() && e('0');
r($test->deleteScanPlanTest(2, 1)) && p() && e('0');
r($test->deleteScanPlanTest(0, 1)) && p() && e('0');
r($test->deleteScanPlanTest(1, 0)) && p() && e('0');
