#!/usr/bin/env php
<?php

/**

title=测试 executionZen::buildExecutionKanbanData();
timeout=0
cid=16412

- 步骤1：正常情况测试返回数组结构 @0
- 步骤2：空项目列表返回项目计数 @0
- 步骤3：空执行列表返回项目计数 @0
- 步骤4：多状态数据返回项目计数 @0
- 步骤5：已关闭执行数据返回项目计数 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

// 2. zendata数据准备
zendata('project')->loadYaml('zt_project_buildexecutionkanbandata', false, 2)->gen(10);
zendata('team')->loadYaml('zt_team_buildexecutionkanbandata', false, 2)->gen(15);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$executionTest = new executionZenTest();

// 准备测试数据
$projectIdList1 = array(1, 2, 3);
$executions1 = array();
for($i = 4; $i <= 10; $i++) {
    $execution = new stdClass();
    $execution->id = $i;
    $execution->project = ($i <= 6) ? 1 : (($i <= 8) ? 2 : 3);
    $execution->status = ($i <= 5) ? 'wait' : (($i <= 7) ? 'doing' : (($i == 8) ? 'suspended' : 'closed'));
    $execution->closedDate = ($execution->status == 'closed') ? '2023-01-0' . ($i - 8) : '';
    $executions1[] = $execution;
}

$projectIdList2 = array();
$executions2 = $executions1;

$projectIdList3 = array(1, 2);
$executions3 = array();

$projectIdList4 = array(1, 2);
$executions4 = array();
for($i = 4; $i <= 7; $i++) {
    $execution = new stdClass();
    $execution->id = $i;
    $execution->project = ($i <= 5) ? 1 : 2;
    $execution->status = ($i == 4) ? 'wait' : (($i == 5) ? 'doing' : (($i == 6) ? 'suspended' : 'closed'));
    $execution->closedDate = ($execution->status == 'closed') ? '2023-01-01' : '';
    $executions4[] = $execution;
}

$projectIdList5 = array(1);
$executions5 = array();
for($i = 4; $i <= 8; $i++) {
    $execution = new stdClass();
    $execution->id = $i;
    $execution->project = 1;
    $execution->status = 'closed';
    $execution->closedDate = '2023-01-0' . ($i - 3);
    $executions5[] = $execution;
}

// 5. 强制要求：必须包含至少5个测试步骤
r($executionTest->buildExecutionKanbanDataTest($projectIdList1, $executions1)) && p('0') && e('0'); // 步骤1：正常情况测试返回数组结构
r($executionTest->buildExecutionKanbanDataTest($projectIdList2, $executions2)) && p('0') && e('0'); // 步骤2：空项目列表返回项目计数
r($executionTest->buildExecutionKanbanDataTest($projectIdList3, $executions3)) && p('0') && e('0'); // 步骤3：空执行列表返回项目计数
r($executionTest->buildExecutionKanbanDataTest($projectIdList4, $executions4)) && p('0') && e('0'); // 步骤4：多状态数据返回项目计数
r($executionTest->buildExecutionKanbanDataTest($projectIdList5, $executions5)) && p('0') && e('0'); // 步骤5：已关闭执行数据返回项目计数