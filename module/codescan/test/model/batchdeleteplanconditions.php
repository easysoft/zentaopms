#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
zenData('entry')->loadYaml('entry', false, 2)->gen(1, true, false);

/**

title=测试 codescanModel->batchDeletePlanConditions();
timeout=0
cid=0

- 删除单个条件ID @1,1,1,11,1
- 删除两个条件ID @1,2,2,21,22,1
- 删除空条件列表 @2,1,0,empty,1
- 删除零仓库零计划条件 @0,0,1,0,1
- 删除三个条件ID @3,4,3,31,32,33,1

*/

su('admin');
$test = new codescanModelTest();

r($test->batchDeletePlanConditionsTest(1, 1, array(11))) && p() && e('0');
r($test->batchDeletePlanConditionsTest(1, 2, array(21, 22))) && p() && e('0');
r($test->batchDeletePlanConditionsTest(2, 1, array())) && p() && e('0');
r($test->batchDeletePlanConditionsTest(0, 0, array(0))) && p() && e('0');
r($test->batchDeletePlanConditionsTest(3, 4, array(31, 32, 33))) && p() && e('0');
