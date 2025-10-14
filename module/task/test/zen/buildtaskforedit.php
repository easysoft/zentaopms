#!/usr/bin/env php
<?php

/**

title=测试 taskZen::buildTaskForEdit();
timeout=0
cid=0

- 步骤1：正常任务更新
 - 属性estimate @8
 - 属性left @5
 - 属性consumed @3
 - 属性status @doing
- 步骤2：任务完成状态更新
 - 属性status @done
 - 属性left @0
 - 属性finishedBy @admin
- 步骤3：任务取消状态更新
 - 属性status @cancel
 - 属性canceledBy @admin
- 步骤4：estimate负数异常 @预计工时不能为负数。
- 步骤5：left负数异常 @预计剩余不能为负数。
- 步骤6：consumed负数异常 @已消耗工时不能为负数。
- 步骤7：名称变更版本递增属性version @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/taskzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$taskTable = zenData('task')->loadYaml('task');
$taskTable->gen(10);

zenData('user')->gen(5);
zenData('project')->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$taskTest = new taskZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($taskTest->buildTaskForEditTest((object)array('id' => 1, 'estimate' => 8, 'left' => 5, 'consumed' => 3, 'status' => 'doing', 'assignedTo' => 'admin', 'name' => '新任务名称'))) && p('estimate,left,consumed,status') && e('8,5,3,doing'); // 步骤1：正常任务更新
r($taskTest->buildTaskForEditTest((object)array('id' => 2, 'estimate' => 10, 'left' => 0, 'consumed' => 10, 'status' => 'done', 'assignedTo' => 'admin', 'name' => '已完成任务'))) && p('status,left,finishedBy') && e('done,0,admin'); // 步骤2：任务完成状态更新
r($taskTest->buildTaskForEditTest((object)array('id' => 3, 'estimate' => 5, 'left' => 5, 'consumed' => 0, 'status' => 'cancel', 'assignedTo' => 'admin', 'name' => '取消的任务'))) && p('status,canceledBy') && e('cancel,admin'); // 步骤3：任务取消状态更新
r($taskTest->buildTaskForEditTest((object)array('id' => 4, 'estimate' => -1, 'left' => 3, 'consumed' => 2, 'status' => 'doing', 'assignedTo' => 'admin', 'name' => '负数估算任务'))) && p() && e('预计工时不能为负数。'); // 步骤4：estimate负数异常
r($taskTest->buildTaskForEditTest((object)array('id' => 5, 'estimate' => 8, 'left' => -1, 'consumed' => 3, 'status' => 'doing', 'assignedTo' => 'admin', 'name' => '负数剩余任务'))) && p() && e('预计剩余不能为负数。'); // 步骤5：left负数异常
r($taskTest->buildTaskForEditTest((object)array('id' => 6, 'estimate' => 8, 'left' => 5, 'consumed' => -1, 'status' => 'doing', 'assignedTo' => 'admin', 'name' => '负数消耗任务'))) && p() && e('已消耗工时不能为负数。'); // 步骤6：consumed负数异常
r($taskTest->buildTaskForEditTest((object)array('id' => 7, 'estimate' => 6, 'left' => 6, 'consumed' => 0, 'status' => 'wait', 'assignedTo' => 'admin', 'name' => '新名称任务'))) && p('version') && e('2'); // 步骤7：名称变更版本递增