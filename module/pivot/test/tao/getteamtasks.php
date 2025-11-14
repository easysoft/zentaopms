#!/usr/bin/env php
<?php

/**

title=测试 pivotTao::getTeamTasks();
timeout=0
cid=17449

- 步骤1：正常情况属性1 @0.00
- 步骤2：空数组 @0
- 步骤3：不存在任务ID @0
- 步骤4：单个任务ID属性1 @0.00
- 步骤5：混合存在和不存在的ID，只返回存在的任务数据
 - 属性1 @0.00
 - 属性2 @1.00

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. zendata数据准备
zenData('task')->loadYaml('task')->gen(10);
zenData('taskteam')->loadYaml('taskteam')->gen(15);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($pivotTest->getTeamTasksTest(array(1, 2, 3))) && p('1') && e('0.00'); // 步骤1：正常情况
r($pivotTest->getTeamTasksTest(array())) && p() && e('0'); // 步骤2：空数组
r($pivotTest->getTeamTasksTest(array(999, 1000))) && p() && e('0'); // 步骤3：不存在任务ID
r($pivotTest->getTeamTasksTest(array(1))) && p('1') && e('0.00'); // 步骤4：单个任务ID
r($pivotTest->getTeamTasksTest(array(1, 2, 999))) && p('1,2') && e('0.00,1.00'); // 步骤5：混合存在和不存在的ID，只返回存在的任务数据