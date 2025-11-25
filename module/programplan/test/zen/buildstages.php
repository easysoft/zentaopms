#!/usr/bin/env php
<?php

/**

title=测试 programplanZen::buildStages();
timeout=0
cid=17790

- 步骤1：测试gantt类型正常情况 @2
- 步骤2：测试assignedTo类型正常情况 @2
- 步骤3：测试带产品ID的情况 @2
- 步骤4：测试不同排序方式 @2
- 步骤5：测试其他项目ID @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/programplan.unittest.class.php';

// 2. zendata数据准备
zendata('project')->loadYaml('project_buildstages', false, 2)->gen(10);
zendata('product')->loadYaml('product_buildstages', false, 2)->gen(5);
zendata('task')->loadYaml('task_buildstages', false, 2)->gen(20);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$programplanTest = new programplanTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($programplanTest->buildStagesTest(1, 0, 0, 'gantt', 'order_asc', '', 0)) && p() && e('2'); // 步骤1：测试gantt类型正常情况
r($programplanTest->buildStagesTest(2, 0, 0, 'assignedTo', 'order_asc', '', 0)) && p() && e('2'); // 步骤2：测试assignedTo类型正常情况
r($programplanTest->buildStagesTest(1, 1, 0, 'gantt', 'order_asc', '', 0)) && p() && e('2'); // 步骤3：测试带产品ID的情况
r($programplanTest->buildStagesTest(1, 0, 0, 'gantt', 'id_desc', '', 0)) && p() && e('2'); // 步骤4：测试不同排序方式
r($programplanTest->buildStagesTest(3, 0, 0, 'gantt', 'begin_asc', '', 0)) && p() && e('2'); // 步骤5：测试其他项目ID