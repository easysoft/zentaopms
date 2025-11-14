#!/usr/bin/env php
<?php

/**

title=测试 taskZen::checkLegallyDate();
timeout=0
cid=18925

- 步骤1：正常情况 @success
- 步骤2：日期冲突属性deadline @"截止日期"必须大于"预计开始"
- 步骤3：父任务开始日期冲突属性estStarted @任务的预计开始日期小于了父任务的预计开始日期：2024-01-15
- 步骤4：父任务截止日期冲突属性deadline @任务的截止日期大于了父任务的截止日期：2024-02-28
- 步骤5：零日期处理 @success

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
// 不需要生成测试数据，直接在测试中创建对象

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$taskZenTest = new taskZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 测试步骤1：正常日期情况
$normalTask = new stdClass();
$normalTask->estStarted = '2024-01-01';
$normalTask->deadline = '2024-01-31';
r($taskZenTest->checkLegallyDateTest($normalTask, false, null, null)) && p() && e('success'); // 步骤1：正常情况

// 测试步骤2：异常日期情况 - 截止日期小于开始日期
$invalidTask = new stdClass();
$invalidTask->estStarted = '2024-01-31';
$invalidTask->deadline = '2024-01-01';
r($taskZenTest->checkLegallyDateTest($invalidTask, false, null, null)) && p('deadline') && e('"截止日期"必须大于"预计开始"'); // 步骤2：日期冲突

// 测试步骤3：子任务开始时间早于父任务（启用日期限制）
$childTask = new stdClass();
$childTask->estStarted = '2024-01-01';
$childTask->deadline = '2024-01-31';

$parentTask = new stdClass();
$parentTask->estStarted = '2024-01-15';
$parentTask->deadline = '2024-02-28';
r($taskZenTest->checkLegallyDateTest($childTask, true, $parentTask, null)) && p('estStarted') && e('任务的预计开始日期小于了父任务的预计开始日期：2024-01-15'); // 步骤3：父任务开始日期冲突

// 测试步骤4：子任务截止时间晚于父任务（启用日期限制）
$childTask2 = new stdClass();
$childTask2->estStarted = '2024-01-20';
$childTask2->deadline = '2024-03-01';
r($taskZenTest->checkLegallyDateTest($childTask2, true, $parentTask, null)) && p('deadline') && e('任务的截止日期大于了父任务的截止日期：2024-02-28'); // 步骤4：父任务截止日期冲突

// 测试步骤5：零日期情况
$zeroDateTask = new stdClass();
$zeroDateTask->estStarted = '0000-00-00';
$zeroDateTask->deadline = '0000-00-00';
r($taskZenTest->checkLegallyDateTest($zeroDateTask, false, null, null)) && p() && e('success'); // 步骤5：零日期处理