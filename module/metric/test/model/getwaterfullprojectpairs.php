#!/usr/bin/env php
<?php

/**

title=测试 metricModel::getWaterfullProjectPairs();
timeout=0
cid=17133

- 步骤1：查询rnd视域瀑布项目数量 @4
- 步骤2：查询ops视域瀑布项目数量 @0
- 步骤3：查询lite视域瀑布项目数量 @0
- 步骤4：查询test视域瀑布项目数量 @0
- 步骤5：查询不存在视域瀑布项目数量 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('project')->loadYaml('project_getwaterfullprojectpairs')->gen(20);

su('admin');

$metricTest = new metricModelTest();

r(count($metricTest->getWaterfullProjectPairsTest('rnd'))) && p() && e('4');     // 步骤1：查询rnd视域瀑布项目数量
r(count($metricTest->getWaterfullProjectPairsTest('ops'))) && p() && e('0');     // 步骤2：查询ops视域瀑布项目数量
r(count($metricTest->getWaterfullProjectPairsTest('lite'))) && p() && e('0');    // 步骤3：查询lite视域瀑布项目数量
r(count($metricTest->getWaterfullProjectPairsTest('test'))) && p() && e('0');    // 步骤4：查询test视域瀑布项目数量
r(count($metricTest->getWaterfullProjectPairsTest('notexist'))) && p() && e('0'); // 步骤5：查询不存在视域瀑布项目数量