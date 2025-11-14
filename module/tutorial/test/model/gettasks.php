#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getTasks();
timeout=0
cid=19485

- 步骤1：获取任务列表，验证数组长度 @2
- 步骤2：验证第一个任务状态第1条的status属性 @wait
- 步骤3：验证第二个任务状态第2条的status属性 @done
- 步骤4：验证第一个任务名称第1条的name属性 @Test task
- 步骤5：验证第二个任务名称第2条的name属性 @Done task

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tutorial.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$tutorialTest = new tutorialTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r(count($tutorialTest->getTasksTest())) && p() && e('2'); // 步骤1：获取任务列表，验证数组长度
r($tutorialTest->getTasksTest()) && p('1:status') && e('wait'); // 步骤2：验证第一个任务状态
r($tutorialTest->getTasksTest()) && p('2:status') && e('done'); // 步骤3：验证第二个任务状态
r($tutorialTest->getTasksTest()) && p('1:name') && e('Test task'); // 步骤4：验证第一个任务名称
r($tutorialTest->getTasksTest()) && p('2:name') && e('Done task'); // 步骤5：验证第二个任务名称