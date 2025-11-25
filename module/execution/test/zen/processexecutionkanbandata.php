#!/usr/bin/env php
<?php

/**

title=测试 executionZen::processExecutionKanbanData();
timeout=0
cid=16436

- 步骤1：正常情况不做处理 @2
- 步骤2：边界值测试保留2个 @2
- 步骤3：大量数据保留2个 @2
- 步骤4：空数据测试 @2
- 步骤5：数据结构验证 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
// zendata('execution')->loadYaml('execution_processexecutionkanbandata', false, 2)->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$executionTest = new executionZenTest();

// 准备测试数据
$projectID = 1;
$status = 'closed';

// 步骤1：正常情况 - 执行数量≤2，不需要处理
$myExecutions1 = array(
    'closed' => array(
        1 => (object)array('id' => 1, 'name' => '执行1', 'closedDate' => '2023-01-01'),
        2 => (object)array('id' => 2, 'name' => '执行2', 'closedDate' => '2023-02-01')
    )
);
$kanbanGroup1 = array(
    1 => array(
        'closed' => array(
            3 => (object)array('id' => 3, 'name' => '执行3', 'closedDate' => '2023-03-01')
        )
    )
);

// 步骤2：边界值 - 执行数量=3，需要处理
$myExecutions2 = array(
    'closed' => array(
        1 => (object)array('id' => 1, 'name' => '执行1', 'closedDate' => '2023-01-01'),
        2 => (object)array('id' => 2, 'name' => '执行2', 'closedDate' => '2023-02-01'),
        3 => (object)array('id' => 3, 'name' => '执行3', 'closedDate' => '2023-03-01')
    )
);
$kanbanGroup2 = array(
    1 => array(
        'closed' => array(
            4 => (object)array('id' => 4, 'name' => '执行4', 'closedDate' => '2023-04-01'),
            5 => (object)array('id' => 5, 'name' => '执行5', 'closedDate' => '2023-05-01'),
            6 => (object)array('id' => 6, 'name' => '执行6', 'closedDate' => '2023-06-01')
        )
    )
);

// 步骤3：大量数据 - 执行数量>3
$myExecutions3 = array(
    'closed' => array(
        1 => (object)array('id' => 1, 'name' => '执行1', 'closedDate' => '2023-01-01'),
        2 => (object)array('id' => 2, 'name' => '执行2', 'closedDate' => '2023-02-01'),
        3 => (object)array('id' => 3, 'name' => '执行3', 'closedDate' => '2023-03-01'),
        4 => (object)array('id' => 4, 'name' => '执行4', 'closedDate' => '2023-04-01'),
        5 => (object)array('id' => 5, 'name' => '执行5', 'closedDate' => '2023-05-01')
    )
);
$kanbanGroup3 = array(
    1 => array(
        'closed' => array(
            6 => (object)array('id' => 6, 'name' => '执行6', 'closedDate' => '2023-06-01'),
            7 => (object)array('id' => 7, 'name' => '执行7', 'closedDate' => '2023-07-01'),
            8 => (object)array('id' => 8, 'name' => '执行8', 'closedDate' => '2023-08-01'),
            9 => (object)array('id' => 9, 'name' => '执行9', 'closedDate' => '2023-09-01')
        )
    )
);

// 步骤4：空数据测试
$myExecutions4 = array();
$kanbanGroup4 = array();

// 步骤5：数据结构验证
$myExecutions5 = array(
    'closed' => array(
        1 => (object)array('id' => 1, 'name' => '执行1', 'closedDate' => '2023-01-01')
    )
);
$kanbanGroup5 = array(
    1 => array(
        'closed' => array(
            2 => (object)array('id' => 2, 'name' => '执行2', 'closedDate' => '2023-02-01')
        )
    )
);

// 5. 强制要求：必须包含至少5个测试步骤
r(count($executionTest->processExecutionKanbanDataTest($myExecutions1, $kanbanGroup1, $projectID, $status)[0]['closed'])) && p() && e('2'); // 步骤1：正常情况不做处理
r(count($executionTest->processExecutionKanbanDataTest($myExecutions2, $kanbanGroup2, $projectID, $status)[0]['closed'])) && p() && e('2'); // 步骤2：边界值测试保留2个
r(count($executionTest->processExecutionKanbanDataTest($myExecutions3, $kanbanGroup3, $projectID, $status)[0]['closed'])) && p() && e('2'); // 步骤3：大量数据保留2个
r(count($executionTest->processExecutionKanbanDataTest($myExecutions4, $kanbanGroup4, $projectID, $status))) && p() && e('2'); // 步骤4：空数据测试
r(count($executionTest->processExecutionKanbanDataTest($myExecutions5, $kanbanGroup5, $projectID, $status)[0]['closed'])) && p() && e('1'); // 步骤5：数据结构验证