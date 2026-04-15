#!/usr/bin/env php
<?php

/**

title=测试 convertTao::createExecution();
timeout=0
cid=15838

- 步骤1：正常创建带Sprint的执行计划 @6
- 步骤2：只创建默认执行计划，无Sprint @8
- 步骤3：测试多个Sprint的执行计划创建 @9
- 步骤4：测试不同Sprint状态和空日期的处理 @12
- 步骤5：测试空角色参与者的处理 @14

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('project');
$table->id->range('1-10');
$table->name->range('项目{1-10}');
$table->code->range('project{1-10}');
$table->status->range('wait,doing,done');
$table->type->range('project');
$table->PM->range('admin,user1,user2');
$table->openedBy->range('admin');
$table->openedDate->range('`2023-01-01 00:00:00`');
$table->gen(5);

$userTable = zenData('user');
$userTable->id->range('1-10');
$userTable->account->range('admin,user1,user2,user3,user4');
$userTable->realname->range('管理员,用户1,用户2,用户3,用户4');
$userTable->role->range('admin,dev,qa,pm,td');
$userTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTaoTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($convertTest->createExecutionTest(1001, array(1001 => array((object)array('id' => 1, 'name' => 'Sprint 1', 'state' => 'active', 'goal' => 'Test sprint', 'startDate' => '2023-01-01T00:00:00.000+0000', 'endDate' => '2023-01-15T00:00:00.000+0000'))), array(1001 => array('jira_user1', 'jira_user2')))) && p('0') && e('6'); // 步骤1：正常创建带Sprint的执行计划
r($convertTest->createExecutionTest(1002, array(), array())) && p('0') && e('8'); // 步骤2：只创建默认执行计划，无Sprint
r($convertTest->createExecutionTest(1003, array(1003 => array((object)array('id' => 2, 'name' => 'Sprint 2', 'state' => 'future', 'goal' => 'Future sprint', 'startDate' => '2023-02-01T00:00:00.000+0000', 'endDate' => '2023-02-15T00:00:00.000+0000'), (object)array('id' => 3, 'name' => 'Sprint 3', 'state' => 'closed', 'goal' => 'Closed sprint', 'startDate' => '2023-03-01T00:00:00.000+0000', 'endDate' => '2023-03-15T00:00:00.000+0000'))), array(1003 => array('jira_user3', 'jira_admin')))) && p('0') && e('9'); // 步骤3：测试多个Sprint的执行计划创建
r($convertTest->createExecutionTest(1004, array(1004 => array((object)array('id' => 4, 'name' => 'Sprint 4', 'state' => 'unknown', 'goal' => 'Unknown state', 'startDate' => '', 'endDate' => ''))), array(1004 => array('jira_lead')))) && p('0') && e('12'); // 步骤4：测试不同Sprint状态和空日期的处理
r($convertTest->createExecutionTest(1005, array(1005 => array((object)array('id' => 5, 'name' => 'Sprint 5', 'state' => 'active', 'goal' => '', 'startDate' => '2023-05-01T00:00:00.000+0000', 'endDate' => '2023-05-15T00:00:00.000+0000'))), array())) && p('0') && e('14'); // 步骤5：测试空角色参与者的处理
