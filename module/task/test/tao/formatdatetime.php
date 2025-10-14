#!/usr/bin/env php
<?php

/**

title=测试 taskTao::formatDatetime();
timeout=0
cid=0

- 空对象输入应该直接返回空对象 @0
- 零日期应该转换为null属性deadline @~~
- 正常日期应该保持不变属性deadline @2023-12-31
- 空字符串应该转换为null属性finishedDate @~~
- null值应该保持为null属性canceledDate @~~
- 非日期字段应该保持不变
 - 属性name @test task
 - 属性status @wait
- 多个日期字段同时处理
 - 属性deadline @~~
 - 属性finishedDate @~~
 - 属性assignedDate @2023-12-31
 - 属性openedDate @~~

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$taskTest = new taskTest();

// 4. 测试步骤（必须包含至少5个测试步骤）

// 步骤1：空对象输入情况
r($taskTest->formatDatetimeTest()) && p() && e(0); // 空对象输入应该直接返回空对象

// 步骤2：带有零日期的日期字段
$taskWithZeroDate = new stdclass();
$taskWithZeroDate->deadline = '0000-00-00';
r($taskTest->formatDatetimeTest($taskWithZeroDate)) && p('deadline') && e('~~'); // 零日期应该转换为null

// 步骤3：带有正常日期的日期字段
$taskWithValidDate = new stdclass();
$taskWithValidDate->deadline = '2023-12-31';
r($taskTest->formatDatetimeTest($taskWithValidDate)) && p('deadline') && e('2023-12-31'); // 正常日期应该保持不变

// 步骤4：带有空字符串的日期字段
$taskWithEmptyString = new stdclass();
$taskWithEmptyString->finishedDate = '';
r($taskTest->formatDatetimeTest($taskWithEmptyString)) && p('finishedDate') && e('~~'); // 空字符串应该转换为null

// 步骤5：带有null值的日期字段
$taskWithNull = new stdclass();
$taskWithNull->canceledDate = null;
r($taskTest->formatDatetimeTest($taskWithNull)) && p('canceledDate') && e('~~'); // null值应该保持为null

// 步骤6：非日期字段应该保持不变
$taskWithNonDateField = new stdclass();
$taskWithNonDateField->name = 'test task';
$taskWithNonDateField->status = 'wait';
$taskWithNonDateField->deadline = '';
r($taskTest->formatDatetimeTest($taskWithNonDateField)) && p('name,status') && e('test task,wait'); // 非日期字段应该保持不变

// 步骤7：多个日期字段同时处理
$taskWithMultipleDates = new stdclass();
$taskWithMultipleDates->deadline = '0000-00-00';
$taskWithMultipleDates->finishedDate = '';
$taskWithMultipleDates->assignedDate = '2023-12-31';
$taskWithMultipleDates->openedDate = null;
r($taskTest->formatDatetimeTest($taskWithMultipleDates)) && p('deadline,finishedDate,assignedDate,openedDate') && e('~~,~~,2023-12-31,~~'); // 多个日期字段同时处理