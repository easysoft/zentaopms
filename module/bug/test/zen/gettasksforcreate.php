#!/usr/bin/env php
<?php

/**

title=测试 bugZen::getTasksForCreate();
timeout=0
cid=0

- 步骤1：有效executionID属性tasksCount @5
- 步骤2：executionID为0属性tasksCount @0
- 步骤3：executionID为空字符串属性tasksCount @0
- 步骤4：executionID为非数字属性tasksCount @0
- 步骤5：executionID为负数属性tasksCount @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$taskTable = zenData('task');
$taskTable->id->range('1-10');
$taskTable->name->range('Task1,Task2,Task3,Task4,Task5,Task6,Task7,Task8,Task9,Task10');
$taskTable->execution->range('101{5},102{3},103{2}');
$taskTable->status->range('wait{3},doing{4},done{3}');
$taskTable->type->range('devel{6},test{3},study{1}');
$taskTable->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$bugTest = new bugTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($bugTest->getTasksForCreateTest((object)array('executionID' => 101))) && p('tasksCount') && e('5');              // 步骤1：有效executionID
r($bugTest->getTasksForCreateTest((object)array('executionID' => 0))) && p('tasksCount') && e('0');                // 步骤2：executionID为0
r($bugTest->getTasksForCreateTest((object)array('executionID' => ''))) && p('tasksCount') && e('0');               // 步骤3：executionID为空字符串
r($bugTest->getTasksForCreateTest((object)array('executionID' => 'abc'))) && p('tasksCount') && e('0');            // 步骤4：executionID为非数字
r($bugTest->getTasksForCreateTest((object)array('executionID' => -1))) && p('tasksCount') && e('0');               // 步骤5：executionID为负数