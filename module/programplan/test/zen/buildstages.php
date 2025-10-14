#!/usr/bin/env php
<?php

/**

title=测试 programplanZen::buildStages();
timeout=0
cid=0

- 步骤1：测试lists类型获取阶段数据 @0
- 步骤2：测试gantt类型获取阶段数据 @error
- 步骤3：测试assignedTo类型获取阶段数据 @error
- 步骤4：测试无效项目ID @0
- 步骤5：测试无效类型参数 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/programplan.unittest.class.php';

zenData('project')->loadYaml('project_buildstages', false, 2)->gen(10);
zenData('product')->loadYaml('product_buildstages', false, 2)->gen(5);
zenData('task')->loadYaml('task_buildstages', false, 2)->gen(20);

su('admin');

$programplanTest = new programplanTest();

r($programplanTest->buildStagesTest(1, 1, 0, 'lists', 'id_asc', '', 0)) && p() && e(0); // 步骤1：测试lists类型获取阶段数据
r($programplanTest->buildStagesTest(1, 1, 0, 'gantt', 'id_asc', '', 0)) && p() && e('error'); // 步骤2：测试gantt类型获取阶段数据
r($programplanTest->buildStagesTest(1, 1, 0, 'assignedTo', 'id_asc', '', 0)) && p() && e('error'); // 步骤3：测试assignedTo类型获取阶段数据
r($programplanTest->buildStagesTest(9999, 1, 0, 'lists', 'id_asc', '', 0)) && p() && e(0); // 步骤4：测试无效项目ID
r($programplanTest->buildStagesTest(1, 1, 0, 'invalid', 'id_asc', '', 0)) && p() && e(0); // 步骤5：测试无效类型参数