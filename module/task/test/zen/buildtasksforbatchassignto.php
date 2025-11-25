#!/usr/bin/env php
<?php

/**

title=测试 taskZen::buildTasksForBatchAssignTo();
timeout=0
cid=18913

- 步骤1：指派普通任务给指定用户 @3
- 步骤2：多人任务指派给团队成员 @2
- 步骤3：多人任务指派给非团队成员 @0
- 步骤4：不符合状态的多人任务指派（doing状态且是多人任务） @0
- 步骤5：已关闭任务指派 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('task');
$table->id->range('1-10');
$table->project->range('1{10}');
$table->execution->range('1{10}');
$table->status->range('doing{3}, done{2}, doing{2}, closed{2}, doing{1}');
$table->assignedTo->range('admin{10}');
$table->openedBy->range('admin{10}');
$table->lastEditedBy->range('admin{10}');
$table->deleted->range('0{10}');
$table->gen(10);

$tableTeam = zenData('taskteam');
$tableTeam->id->range('1-6');
$tableTeam->task->range('4{2}, 5{2}, 6{2}');
$tableTeam->account->range('user1{3}, user2{3}');
$tableTeam->status->range('doing{6}');
$tableTeam->gen(6);

zenData('user')->gen(5);
zenData('project')->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$taskTest = new taskZenTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($taskTest->buildTasksForBatchAssignToTest(array(1, 2, 3), 'user1')) && p() && e(3); // 步骤1：指派普通任务给指定用户
r($taskTest->buildTasksForBatchAssignToTest(array(4, 5), 'user1')) && p() && e(2); // 步骤2：多人任务指派给团队成员
r($taskTest->buildTasksForBatchAssignToTest(array(4, 5), 'user3')) && p() && e(0); // 步骤3：多人任务指派给非团队成员
r($taskTest->buildTasksForBatchAssignToTest(array(6), 'user1')) && p() && e(0); // 步骤4：不符合状态的多人任务指派（doing状态且是多人任务）
r($taskTest->buildTasksForBatchAssignToTest(array(8, 9), 'user1')) && p() && e(0); // 步骤5：已关闭任务指派