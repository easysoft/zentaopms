#!/usr/bin/env php
<?php

/**

title=测试 searchTao::checkRelatedObjectPriv();
timeout=0
cid=0

- 步骤1：正常bug对象产品权限检查 @1
- 步骤2：正常task对象执行权限检查 @1
- 步骤3：空对象列表检查 @1
- 步骤4：无权限产品对象检查 @0
- 步骤5：effort对象混合权限检查 @2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$bug = zenData('bug');
$bug->id->range('1-10');
$bug->product->range('1-3,5{2},7{5}');
$bug->execution->range('1,2,3,4,5,1,2,3,4,5');
$bug->title->range('Bug1,Bug2,Bug3,Bug4,Bug5,Bug6,Bug7,Bug8,Bug9,Bug10');
$bug->gen(10);

$task = zenData('task');
$task->id->range('1-5');
$task->execution->range('1,2,3,4,5');
$task->name->range('Task1,Task2,Task3,Task4,Task5');
$task->gen(5);

$effort = zenData('effort');
$effort->id->range('1-5');
$effort->product->range('1,2,3,4,5');
$effort->execution->range('1,2,3,4,5');
$effort->work->range('Effort1,Effort2,Effort3,Effort4,Effort5');
$effort->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$searchTest = new searchTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($searchTest->checkRelatedObjectPrivTest('bug', TABLE_BUG, array(1 => (object)array('id' => 1)), array(1 => 1, 2 => 2, 3 => 3), '1,2,3', '1,2,3')) && p() && e(1); // 步骤1：正常bug对象产品权限检查
r($searchTest->checkRelatedObjectPrivTest('task', TABLE_TASK, array(1 => (object)array('id' => 1)), array(1 => 1, 2 => 2, 3 => 3), '1,2,3', '1,2,3')) && p() && e(1); // 步骤2：正常task对象执行权限检查
r($searchTest->checkRelatedObjectPrivTest('bug', TABLE_BUG, array(1 => (object)array('id' => 1)), array(), '1,2,3', '1,2,3')) && p() && e(1); // 步骤3：空对象列表检查
r($searchTest->checkRelatedObjectPrivTest('bug', TABLE_BUG, array(1 => (object)array('id' => 1), 2 => (object)array('id' => 2)), array(5 => 1, 7 => 2), '1,2,3', '1,2,3')) && p() && e(0); // 步骤4：无权限产品对象检查
r($searchTest->checkRelatedObjectPrivTest('effort', TABLE_EFFORT, array(1 => (object)array('id' => 1), 2 => (object)array('id' => 2)), array(1 => 1, 2 => 2), '1,2', '1,2')) && p() && e(2); // 步骤5：effort对象混合权限检查