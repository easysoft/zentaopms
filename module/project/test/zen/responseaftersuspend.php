#!/usr/bin/env php
<?php

/**

title=测试 projectZen::responseAfterSuspend();
timeout=0
cid=17966

- 步骤1：正常情况 @success
- 步骤2：边界值 @success
- 步骤3：边界值 @success
- 步骤4：边界值 @success
- 步骤5：业务规则 @success
- 步骤6：异常输入 @projectID must be non-negative
- 步骤7：异常输入 @projectID parameter cannot be null

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/projectzen.unittest.class.php';

// 2. zendata数据准备
$table = zenData('project');
$table->id->range('1-10');
$table->name->range('项目{1-10}');
$table->status->range('doing{5},suspended{3},closed{2}');
$table->openedBy->range('admin');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例
$projectTest = new projectzenTest();

// 5. 强制要求：必须包含至少5个测试步骤

// 测试步骤1：正常情况 - 有备注和变更时创建动作日志
$changes = array(
    (object)array('field' => 'status', 'old' => 'doing', 'new' => 'suspended')
);
$comment = '暂停项目测试';
r($projectTest->responseAfterSuspendTest(1, $changes, $comment)) && p() && e('success'); // 步骤1：正常情况

// 测试步骤2：边界值测试 - 只有备注没有变更
$changes = array();
$comment = '仅有备注的暂停';
r($projectTest->responseAfterSuspendTest(2, $changes, $comment)) && p() && e('success'); // 步骤2：边界值

// 测试步骤3：边界值测试 - 只有变更没有备注
$changes = array(
    (object)array('field' => 'suspendedBy', 'old' => '', 'new' => 'admin')
);
$comment = '';
r($projectTest->responseAfterSuspendTest(3, $changes, $comment)) && p() && e('success'); // 步骤3：边界值

// 测试步骤4：边界值测试 - 既无备注也无变更
$changes = array();
$comment = '';
r($projectTest->responseAfterSuspendTest(4, $changes, $comment)) && p() && e('success'); // 步骤4：边界值

// 测试步骤5：业务规则验证 - 项目ID为0的情况
$changes = array();
$comment = '';
r($projectTest->responseAfterSuspendTest(0, $changes, $comment)) && p() && e('success'); // 步骤5：业务规则

// 测试步骤6：异常情况验证 - 项目ID为负数
$changes = array();
$comment = '';
r($projectTest->responseAfterSuspendTest(-1, $changes, $comment)) && p() && e('projectID must be non-negative'); // 步骤6：异常输入

// 测试步骤7：异常情况验证 - 项目ID为空
$changes = array();
$comment = '';
r($projectTest->responseAfterSuspendTest(null, $changes, $comment)) && p() && e('projectID parameter cannot be null'); // 步骤7：异常输入