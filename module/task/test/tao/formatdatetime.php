#!/usr/bin/env php
<?php

/**

title=测试 taskTao::formatDatetime();
timeout=0
cid=0

- 空对象没有name属性应该返回0属性name @0
- 零日期应该转换为null属性deadline @~~
- 正常日期应该保持不变属性deadline @2023-12-31
- 空字符串应该转换为null属性finishedDate @~~
- null值应该保持为null属性canceledDate @~~

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/task.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$task = new taskTest();

// 4. 测试步骤（必须包含至少5个测试步骤）

// 步骤1：空对象输入情况
r($task->formatDatetimeTest()) && p('name') && e('0'); // 空对象没有name属性应该返回0

// 步骤2：带有零日期的日期字段
$taskWithZeroDate = new stdclass();
$taskWithZeroDate->deadline = '0000-00-00';
r($task->formatDatetimeTest($taskWithZeroDate)) && p('deadline') && e('~~'); // 零日期应该转换为null

// 步骤3：带有正常日期的日期字段
$taskWithValidDate = new stdclass();
$taskWithValidDate->deadline = '2023-12-31';
r($task->formatDatetimeTest($taskWithValidDate)) && p('deadline') && e('2023-12-31'); // 正常日期应该保持不变

// 步骤4：带有空字符串的日期字段
$taskWithEmptyString = new stdclass();
$taskWithEmptyString->finishedDate = '';
r($task->formatDatetimeTest($taskWithEmptyString)) && p('finishedDate') && e('~~'); // 空字符串应该转换为null

// 步骤5：带有null值的日期字段
$taskWithNull = new stdclass();
$taskWithNull->canceledDate = null;
r($task->formatDatetimeTest($taskWithNull)) && p('canceledDate') && e('~~'); // null值应该保持为null