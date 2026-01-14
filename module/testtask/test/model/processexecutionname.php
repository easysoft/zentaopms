#!/usr/bin/env php
<?php

/**

title=测试 testtaskModel::processExecutionName();
timeout=0
cid=19214

- 步骤1：有项目名和执行名的多执行任务第0条的executionName属性 @ProjectA/SprintA
- 步骤2：只有项目名的多执行任务第0条的executionName属性 @ProjectB
- 步骤3：非多执行任务不变第0条的executionName属性 @SprintC
- 步骤4：空数组处理 @0
- 步骤5：新任务对象测试第0条的executionName属性 @ProjectF/SprintF

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$testtaskTest = new testtaskModelTest();

// 4. 准备测试数据
$task1 = new stdclass();
$task1->multiple = true;
$task1->projectName = 'ProjectA';
$task1->executionName = 'SprintA';

$task2 = new stdclass();
$task2->multiple = true;
$task2->projectName = 'ProjectB';
$task2->executionName = '';

$task3 = new stdclass();
$task3->multiple = false;
$task3->projectName = 'ProjectC';
$task3->executionName = 'SprintC';

$task4 = new stdclass();
$task4->multiple = true;
$task4->projectName = '';
$task4->executionName = 'SprintD';

$task5 = new stdclass();
$task5->multiple = true;
$task5->projectName = 'ProjectE';
$task5->executionName = 'SprintE';

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testtaskTest->processExecutionNameTest(array($task1))) && p('0:executionName') && e('ProjectA/SprintA'); // 步骤1：有项目名和执行名的多执行任务
r($testtaskTest->processExecutionNameTest(array($task2))) && p('0:executionName') && e('ProjectB'); // 步骤2：只有项目名的多执行任务
r($testtaskTest->processExecutionNameTest(array($task3))) && p('0:executionName') && e('SprintC'); // 步骤3：非多执行任务不变
r($testtaskTest->processExecutionNameTest(array())) && p() && e('0'); // 步骤4：空数组处理

// 为第5步创建新的任务对象以避免之前测试的影响
$taskNew = new stdclass();
$taskNew->multiple = true;
$taskNew->projectName = 'ProjectF';
$taskNew->executionName = 'SprintF';
r($testtaskTest->processExecutionNameTest(array($taskNew))) && p('0:executionName') && e('ProjectF/SprintF'); // 步骤5：新任务对象测试