#!/usr/bin/env php
<?php

/**

title=测试 screenModel::getProjectTaskTable();
timeout=0
cid=0

- 步骤1：正常情况有项目数据属性count @~~
- 步骤2：空项目列表测试 @rray()
- 步骤3：无任务数据年月测试 @rray()
- 步骤4：无任务数据的项目测试 @rray()
- 步骤5：有任务数据的二月查询属性count @~~

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$projectTable = zenData('project');
$projectTable->id->range('1-15');
$projectTable->name->range('Project A, Project B, Project C, Project D, Project E');
$projectTable->type->range('project{5}, sprint{4}, stage{3}, kanban{2}, waterfallexec{1}');
$projectTable->status->range('wait{3}, doing{8}, done{3}, closed{1}');
$projectTable->deleted->range('0{13}, 1{2}');
$projectTable->project->range('0{5}, 1{4}, 2{3}, 3{2}, 4{1}');
$projectTable->gen(15);

$taskTable = zenData('task');
$taskTable->id->range('1-10');
$taskTable->project->range('1{5}, 2{3}, 3{2}');
$taskTable->execution->range('6{5}, 7{3}, 8{2}');
$taskTable->openedBy->range('admin{4}, user1{3}, user2{2}, user3{1}');
$taskTable->openedDate->range("`2023-01-15`{5}, `2023-02-15`{3}, `2023-03-15`{2}");
$taskTable->finishedBy->range('admin{3}, user1{2}, \'\'{5}');
$taskTable->finishedDate->range("`2023-01-20`{3}, `2023-02-20`{2}, NULL{5}");
$taskTable->deleted->range('0{9}, 1{1}');
$taskTable->vision->range('rnd{8}, or{1}, lite{1}');
$taskTable->status->range('wait{2}, doing{4}, done{3}, cancel{1}');
$taskTable->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$screenTest = new screenTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($screenTest->getProjectTaskTableTest('2023', '01', array(1 => 'Project A', 2 => 'Project B'))) && p('count') && e('~~'); // 步骤1：正常情况有项目数据
r($screenTest->getProjectTaskTableTest('2023', '02', array())) && p() && e(array()); // 步骤2：空项目列表测试
r($screenTest->getProjectTaskTableTest('2024', '01', array(1 => 'Project A'))) && p() && e(array()); // 步骤3：无任务数据年月测试
r($screenTest->getProjectTaskTableTest('2023', '12', array(3 => 'Project C', 4 => 'Project D'))) && p() && e(array()); // 步骤4：无任务数据的项目测试
r($screenTest->getProjectTaskTableTest('2023', '02', array(1 => 'Project A', 2 => 'Project B'))) && p('count') && e('~~'); // 步骤5：有任务数据的二月查询