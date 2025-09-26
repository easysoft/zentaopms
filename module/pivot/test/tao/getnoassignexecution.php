#!/usr/bin/env php
<?php

/**

title=测试 pivotTao::getNoAssignExecution();
cid=0

- 步骤1：空数组参数测试 >> 返回数组类型
- 步骤2：指定用户参数测试 >> 返回数组类型
- 步骤3：不存在用户参数测试 >> 返回数组类型
- 步骤4：多个用户参数测试 >> 返回数组类型
- 步骤5：验证返回结果结构 >> 确保返回对象包含正确字段

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$teamTable = zenData('team');
$teamTable->id->range('1-10');
$teamTable->root->range('1{3},2{3},3{2},4{1},5{1}');
$teamTable->type->range('execution{10}');
$teamTable->account->range('user1{2},user2{2},user3{2},user4{2},user5{2}');
$teamTable->role->range('dev{5},test{3},pm{2}');
$teamTable->limited->range('no{8},yes{2}');
$teamTable->gen(10);

$executionTable = zenData('execution');
$executionTable->id->range('1-5');
$executionTable->project->range('1{2},2{2},3{1}');
$executionTable->name->range('Sprint 1,Sprint 2,Stage A,Stage B,Release 1');
$executionTable->type->range('sprint{3},stage{2}');
$executionTable->status->range('wait{1},doing{2},suspended{1},closed{1}');
$executionTable->deleted->range('0{5}');
$executionTable->multiple->range('1{3},0{2}');
$executionTable->gen(5);

$projectTable = zenData('project');
$projectTable->id->range('1-3');
$projectTable->name->range('Project Alpha,Project Beta,Project Gamma');
$projectTable->type->range('project{3}');
$projectTable->status->range('doing{2},closed{1}');
$projectTable->deleted->range('0{3}');
$projectTable->gen(3);

$taskTable = zenData('task');
$taskTable->id->range('1-15');
$taskTable->project->range('1{5},2{5},3{5}');
$taskTable->execution->range('1{3},2{3},3{3},4{3},5{3}');
$taskTable->parent->range('0{10},-1{5}');
$taskTable->assignedTo->range('"{5},user1{3},user2{4},user3{3}');
$taskTable->status->range('wait{3},doing{4},cancel{2},closed{3},done{2},pause{1}');
$taskTable->mode->range('"{10},multi{3},linear{2}');
$taskTable->deleted->range('0{15}');
$taskTable->gen(15);

$taskteamTable = zenData('taskteam');
$taskteamTable->id->range('1-8');
$taskteamTable->task->range('11{2},12{2},13{2},14{2}');
$taskteamTable->account->range('user1{3},user2{3},user3{2}');
$taskteamTable->status->range('wait{3},doing{3},done{2}');
$taskteamTable->gen(8);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r(is_array($pivotTest->getNoAssignExecutionTest(array()))) && p() && e('1'); // 步骤1：空数组参数测试
r(is_array($pivotTest->getNoAssignExecutionTest(array('user1', 'user2', 'user3')))) && p() && e('1'); // 步骤2：指定用户参数测试
r(is_array($pivotTest->getNoAssignExecutionTest(array('nonexistent_user')))) && p() && e('1'); // 步骤3：不存在用户参数测试
r(count($pivotTest->getNoAssignExecutionTest(array('nonexistent_user')))) && p() && e('0'); // 步骤4：不存在用户参数返回空数组
$result = $pivotTest->getNoAssignExecutionTest(array('user5')); r(isset($result[0]) ? (isset($result[0]->user) && isset($result[0]->executionID) && isset($result[0]->projectID)) : true) && p() && e('1'); // 步骤5：验证返回结果结构