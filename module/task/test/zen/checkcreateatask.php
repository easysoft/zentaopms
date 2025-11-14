#!/usr/bin/env php
<?php

/**

title=测试 taskZen::checkCreateTask();
timeout=0
cid=18920

- 步骤1：正常任务创建检查 @1
- 步骤2：预计工时为负数的异常情况属性estimate @预计不能为负数
- 步骤3：多人模式下未分配团队成员属性assignedTo @多人任务团队不能为空。
- 步骤4：启用日期限制且日期有效的情况 @1
- 步骤5：启用日期限制但开始日期晚于截止日期属性deadline @"截止日期"必须大于"预计开始"

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$task = zenData('task');
$task->id->range('1-10');
$task->project->range('1-5');
$task->execution->range('1-5');
$task->estimate->range('1-10');
$task->name->range('任务{1-10}');
$task->status->range('wait');
$task->gen(10);

$project = zenData('project');
$project->id->range('1-10');
$project->name->range('项目{1-10}');
$project->type->range('project{5},execution{5}');
$project->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$taskZenTest = new taskZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 测试步骤1：正常情况 - 预计工时为正数
$normalTask = new stdClass();
$normalTask->estimate = 8;
$normalTask->execution = 1;
$normalTask->project = 1;
$normalTask->estStarted = '2024-01-01';
$normalTask->deadline = '2024-01-31';
$normalTask->parent = 0;
r($taskZenTest->checkCreateTaskTest($normalTask, array())) && p() && e('1'); // 步骤1：正常任务创建检查

// 测试步骤2：预计工时为负数
$negativeTask = new stdClass();
$negativeTask->estimate = -5;
$negativeTask->execution = 1;
$negativeTask->project = 1;
$negativeTask->estStarted = '2024-01-01';
$negativeTask->deadline = '2024-01-31';
$negativeTask->parent = 0;
r($taskZenTest->checkCreateTaskTest($negativeTask, array())) && p('estimate') && e('预计不能为负数'); // 步骤2：预计工时为负数的异常情况

// 测试步骤3：多人模式下未分配团队成员
$multipleTask = new stdClass();
$multipleTask->estimate = 5;
$multipleTask->execution = 1;
$multipleTask->project = 1;
$multipleTask->estStarted = '2024-01-01';
$multipleTask->deadline = '2024-01-31';
$multipleTask->parent = 0;
$multipleTask->multiple = true;
r($taskZenTest->checkCreateTaskTest($multipleTask, array())) && p('assignedTo') && e('多人任务团队不能为空。'); // 步骤3：多人模式下未分配团队成员

// 测试步骤4：启用日期限制且日期有效
$dateLimitTask = new stdClass();
$dateLimitTask->estimate = 3;
$dateLimitTask->execution = 1;
$dateLimitTask->project = 1;
$dateLimitTask->estStarted = '2024-01-10';
$dateLimitTask->deadline = '2024-01-20';
$dateLimitTask->parent = 0;
r($taskZenTest->checkCreateTaskTest($dateLimitTask, array('user1', 'user2'))) && p() && e('1'); // 步骤4：启用日期限制且日期有效的情况

// 测试步骤5：日期冲突 - 开始日期晚于截止日期
$conflictDateTask = new stdClass();
$conflictDateTask->estimate = 4;
$conflictDateTask->execution = 1;
$conflictDateTask->project = 1;
$conflictDateTask->estStarted = '2024-01-31';
$conflictDateTask->deadline = '2024-01-01';
$conflictDateTask->parent = 0;
r($taskZenTest->checkCreateTaskTest($conflictDateTask, array())) && p('deadline') && e('"截止日期"必须大于"预计开始"'); // 步骤5：启用日期限制但开始日期晚于截止日期